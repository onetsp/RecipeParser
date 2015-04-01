<?php

class RecipeParser_Parser_Thedailymealcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        //
        // Some of the ingredient lines in on The Daily Meal do not adhere to
        // the usual microdata formatting.  Here we fall back to looking for a
        // regular list within a higher-level ingredients div.
        //
        if (!$recipe->ingredients->length) {
            $nodes = $xpath->query("//div[@class='content']/div[@class='ingredient']/ul/li");  
               foreach ($nodes as $node) {
                $value = RecipeParser_Text::formatAsOneLine($node->nodeValue);
                if (empty($value)) {
                    continue;
                }
                if (RecipeParser_Text::matchSectionName($value)) {
                    $value = RecipeParser_Text::formatSectionName($value);
                    $recipe->addIngredientsSection($value);
                } else {
                    $recipe->appendIngredient($value);
                }
            }
        }

        //
        // The Daily Meal provides servings details via Edamam's plugin.
        //
        if (!$recipe->yield) {
            $nodes = $xpath->query("//table[@class='edamam-data']/tr[2]/td[2]");
            if ($nodes->length) {
                $recipe->yield = RecipeParser_Text::formatYield($nodes->item(0)->nodeValue);
            }
        }

        return $recipe;
    }

}
