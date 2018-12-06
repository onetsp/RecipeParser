<?php

class RecipeParser_Parser_Bonappetitcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_General::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Yield
        $nodes = $xpath->query('//*[@class="recipe__header__servings recipe__header__servings--basically"]');
        if ($nodes->length) {
            $value = $nodes->item(0)->nodeValue;
            $value = RecipeParser_Text::formatYield($value);
            $recipe->yield = $value;
        }

        // Cooking times
        $nodes = $xpath->query('//*[@class="recipe__header__times recipe__header__times--basically"]');
        foreach ($nodes as $node) {  
            $value = $node->nodeValue;

            // Prep times
            if (stripos($value, "prep time") !== false) {
                $value = preg_replace("/prep time\:(.*)/i", "$1", $value);
                $recipe->time['prep'] = RecipeParser_Times::toMinutes($value);
            } else if (stripos($value, "total time") !== false) {
                $value = preg_replace("/total time\:(.*)/i", "$1", $value);
                $recipe->time['total'] = RecipeParser_Times::toMinutes($value);
            }
        }

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//div[@class="ingredientsGroup"]/*');
        foreach ($nodes as $node) {
            if ($node->nodeName == 'h3') {
                $line = RecipeParser_Text::formatSectionName($node->nodeValue);
                $recipe->addIngredientsSection($line);
                continue;
            } else if ($node->nodeName == 'ul') {
                $ing_nodes = $node->childNodes;
                foreach ($ing_nodes as $ing_node) {
                    if ($ing_node->nodeName == 'li') {
                        $line = trim($ing_node->nodeValue);
                        $line = RecipeParser_Text::formatAsOneLine($line);
                        if ($line) {
                            $recipe->appendIngredient($line);
                        }
                    }
                }
                continue;
            }
        }

        // Instructions
        $recipe->resetInstructions();
        $nodes = $xpath->query('//div[@class="steps-wrapper"]/*');
        foreach ($nodes as $node) {

            // <h4> contains section name.
            if ($node->nodeName == 'h4') {
                $line = RecipeParser_Text::formatSectionName($node->nodeValue);
                if (!empty($line)) {
                    $recipe->addInstructionsSection($line);
                }
                continue;
            }

            // Each step is in ul->li
            if ($node->nodeName == 'ul') {
                foreach ($node->childNodes as $child) {
                    $line = RecipeParser_Text::formatAsOneLine($child->nodeValue);
                    if ($line) {
                        $recipe->appendInstruction($line);
                    }
                }
                continue;
            }
        }

        return $recipe;
    }

}
