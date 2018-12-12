<?php

class RecipeParser_Parser_Foodnetworkcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_General::parse($html, $url);
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        // Cooking times and yield
        $data = array();
        $dd = "";
        $dt = "";

        // Find all of the yields and times in dt and dd nodes
        $nodes = $xpath->query('//*[@class="parbase recipeInfo"]//dl/*');
        foreach ($nodes as $node) {
            if ($node->nodeName == 'dt') {
                $value = strtolower(RecipeParser_Text::formatSectionName($node->nodeValue));
                $dt = $value;
            } else if ($node->nodeName == 'dd') {
                $value = RecipeParser_Text::formatAsOneLine($node->nodeValue);
                $dd = $value;
            }
            if ($dt && $dd) {
                $data[$dt] = $dd;
                $dd = $dt = "";
            }
        }

        // Assign data to recipe
        foreach (array('prep', 'cook', 'total') as $key) {
            if (isset($data[$key])) {
                $recipe->time[$key] = RecipeParser_Times::toMinutes($data[$key]);
            }
        }
        if (isset($data['yield'])) {
            $recipe->yield = RecipeParser_Text::formatYield($data['yield']);
        }

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//div[@class="o-Ingredients__m-Body"]');
        if ($nodes->length) {
            $nodes = $nodes->item(0)->childNodes;

            foreach ($nodes as $node) {
                if ($node->nodeName == "h6") {
                    $value = $node->nodeValue;
                    $value = RecipeParser_Text::formatSectionName($value);
                    $recipe->addIngredientsSection($value);

                } else if ($node->nodeName == "ul") {
                    $inners = $xpath->query('li', $node);
                    foreach ($inners as $inner) {
                        $value = $inner->nodeValue;
                        $value = RecipeParser_Text::formatAsOneLine($value);
                        $recipe->appendIngredient($value);
                    }
                } 
            }
        }

        // Instructions
        $recipe->resetInstructions();
        $nodes = $xpath->query('//section[@id="site"]//*[@class="o-Method__m-Body"]/*');
        foreach ($nodes as $node) {
            if ($node->nodeName == "h4") {
                $value = $node->nodeValue;
                $value = RecipeParser_Text::formatSectionName($value);
                $recipe->addInstructionsSection($value);
            } else if ($node->nodeName == "p") {
                $value = $node->nodeValue;
                $value = RecipeParser_Text::formatAsOneLine($value);
                if (!self::excludeInstruction($value)) {
                    $recipe->appendInstruction($value);
                }
            }
        }

        // Source
        if (!$recipe->source) {
            $nodes = $xpath->query('//*[@class="o-Attribution__a-Name"]');
            if ($nodes->length > 0) {
                $value = $nodes->item(0)->nodeValue;
                $value = RecipeParser_Text::formatAsOneLine($value);
                $recipe->source = $value;
            } else {
                $recipe->source = "Food Network Kitchen";
            }
        }

        return $recipe;
    }


    public static $instruction_filters = array(
        "/^photographs? by/i",
        "/^watch how to make this recipe/i",
        "/^recipe courtesy/i",
        "/^from food network kitchens/i",
        "/^copyright/i",
    );

    public static function excludeInstruction($str) {
        foreach (self::$instruction_filters as $filter) {
            if (preg_match($filter, $str)) {
                return true;
            }
        }
        return false;
    }

}
