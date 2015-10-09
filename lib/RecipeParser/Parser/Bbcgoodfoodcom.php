<?php

class RecipeParser_Parser_Bbcgoodfoodcom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR BDCGOODFOOD.COM

        // Ingredients
        $recipe->resetIngredients();           

        $nodes = null;
        if (!$nodes || !$nodes->length) {
            $nodes = $xpath->query('//*[@id="recipe-ingredients"]//div[@class="view-content"]/*');
        }
        if (!$nodes || !$nodes->length) {
            $nodes = $xpath->query('//*[@id="recipe-ingredients"]//div[@class="ingredient-lists separator-serated tab-content"]/*');
        }
        foreach ($nodes as $node) {
            if ($node->nodeName == 'h3') {
                $line = $node->nodeValue;
                $line = RecipeParser_Text::formatSectionName($line);
                $recipe->addIngredientsSection($line);
            
            } else if ($node->nodeName == 'ul') {
                foreach ($node->childNodes as $subnode) {
                    $line = $subnode->nodeValue;
                    $line = RecipeParser_Text::formatAsOneLine($line);
                    $recipe->appendIngredient($line);
                }
            }
        }

        return $recipe;
    }

}
