<?php

class RecipeParser_Parser_Foodnetworkcouk {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR FOODNETWORKCO.UK

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
