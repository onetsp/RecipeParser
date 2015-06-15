<?php

class RecipeParser_Parser_Foodnetworkcouk {

    static public function parse($html, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);


        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//div[@id="ingredients-box"]//ul/li');
        foreach ($nodes as $node) {
            if ($node->getAttribute("itemprop")) {
                $line = $node->nodeValue;
                $line = RecipeParser_Text::formatAsOneLine($line);
                $recipe->appendIngredient($line);
            } else {
                $line = $node->nodeValue;
                $line = RecipeParser_Text::formatSEctionName($line);
                $recipe->addIngredientsSection($line);
            }
        }

        // Instructions
        $recipe->resetInstructions();
        $nodes = $xpath->query('//*[@id="method-box"]//p');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            if ($line) {
                $recipe->appendInstruction($line);
            }
        }

        return $recipe;
    }

}
