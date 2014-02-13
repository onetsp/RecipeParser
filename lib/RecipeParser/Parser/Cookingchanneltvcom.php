<?php

class RecipeParser_Parser_Cookingchanneltvcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_Microformat::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Yield
        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " yield ")]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $recipe->yield = RecipeParser_Text::formatYield($line);
        }

        // Times
        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " prepTime ")]/span');
        if ($nodes->length) {
            $line = $nodes->item(1)->getAttribute("title");
            $recipe->time['prep'] = RecipeParser_Text::iso8601ToMinutes($line);
        }
        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " rspec-cook-time ")]/span');
        if ($nodes->length) {
            $line = $nodes->item(1)->getAttribute("title");
            $recipe->time['cook'] = RecipeParser_Text::iso8601ToMinutes($line);
        }
        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " totaltime ")]/span');
        if ($nodes->length) {
            $line = $nodes->item(1)->getAttribute("title");
            $recipe->time['total'] = RecipeParser_Text::iso8601ToMinutes($line);
        }

        // Ingredients
        $recipe->resetIngredients();

        $ing_nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " ingredients ")]/*');
        foreach ($ing_nodes as $ing_node) {

            if ($ing_node->getAttribute('class') == "ingr-divider") {
                $line = RecipeParser_Text::formatSectionName($ing_node->nodeValue);
                $recipe->addIngredientsSection($line);
                continue;
            }

            // Extract ingredients from inside of <ul class="ingredientsList">
            // Child nodes should all be <li>
            if ($ing_node->nodeName == 'ul') {
                        
                foreach ($ing_node->childNodes as $node) {
                    $line = trim($node->nodeValue);
                    $recipe->appendIngredient($line);
                }
                continue;
            }

        }

        return $recipe;
    }

}
