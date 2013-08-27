<?php

class RecipeParser_Parser_Bbccouk {

    public function parse($html, $url) {
        // Get all of the standard hrecipe stuff we can find.
        $recipe = RecipeParser_Parser_Microformat::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Multi-stage ingredients
        $nodes = $xpath->query('//dl[@id="stages"]/*');
        if ($nodes->length) {
            $recipe->resetIngredients();

            foreach ($nodes as $node) {
                if ($node->nodeName == 'dt') {
                    $value = $node->nodeValue;
                    $value = RecipeParser_Text::formatSectionName($value);
                    $recipe->addIngredientsSection($value);
                
                } else if ($node->nodeName == 'dd') {
                    $subs = $xpath->query('.//*[@class="ingredient"]', $node);
                    foreach ($subs as $sub) {
                        $value = trim($sub->nodeValue);
                        $recipe->appendIngredient($value);
                    }
                }

            }

        }

        return $recipe;
    }

}

?>
