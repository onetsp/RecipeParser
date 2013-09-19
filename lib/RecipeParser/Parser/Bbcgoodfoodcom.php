<?php

class RecipeParser_Parser_Bbcgoodfoodcom {

    public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Ingredients
        $recipe->resetIngredients();           

        $nodes = $xpath->query('//*[@id="recipe-ingredients"]/div/*');
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

?>
