<?php

class RecipeParser_Parser_Bhgcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Notes -- Collect the non-standard cook times and baking temps,
        // and also any tips/notes that appear at the end of the recipe instructions.
        $notes = array();

        $nodes = $xpath->query('//*[@class="recipeTips"]//li');
        foreach ($nodes as $node) {
            $value = RecipeParser_Text::FormatAsOneLine($node->nodeValue);
            $value = preg_replace("/^(Tip|Note)\s*(.*)$/", "$2", $value);
            $notes[] = $value;
        }

        $nodes = $xpath->query('//*[@class="recipeInfo"]//*[@class="type"]');
        foreach ($nodes as $node) {
            $value = RecipeParser_Text::formatAsOneLine($node->nodeValue);
            if (strpos($value, "Makes:") !== false) {
                continue;
            }
            $notes[] = $value;
        }
        
        $recipe->notes = implode("\n\n", $notes);

        // Adjust Photo URL for larger dimensions
        $recipe->photo_url = preg_replace("/\/l_([^\/]+)/", "/550_$1", $recipe->photo_url);

        // Title
        $nodes = $xpath->query('//h1[@class="recipe__mainHeading"]');
        $recipe->title = $nodes->item(0)->nodeValue;

        // Ingredients
        if (!count($recipe->ingredients[0]["list"])) {
            $nodes = $xpath->query('//div[@class="recipe__ingredientContainer"]//*');
            foreach ($nodes as $node) {
                if ($node->nodeName == 'h4') {
                    // Found section heading.
                    $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
                    $recipe->addIngredientsSection(ucfirst(strtolower($line)));
                } else if ($node->nodeName == 'ul') {
                    // Found ingredient list.
                    foreach ($node->childNodes as $childNode) {
                        $line = RecipeParser_Text::formatAsOneLine($childNode->nodeValue);
                        $recipe->appendIngredient($line);
                    }
                }
            }
        }

        // Source
        // Source
        if (!$recipe->source) {
            $recipe->source = "Bhg.com";
        }

        return $recipe;
    }

}
