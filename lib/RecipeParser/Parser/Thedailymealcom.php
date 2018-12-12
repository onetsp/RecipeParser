<?php

class RecipeParser_Parser_Thedailymealcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($html, $url);
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        //
        // Some of the ingredient lines in on The Daily Meal do not adhere to
        // the usual microdata formatting.  Here we fall back to looking for a
        // regular list within a higher-level ingredients div.
        //
        if (!empty($recipe->ingredients)) {
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

        $myxpath->singleNodeLookup('//table[@class="edamam-data"]/tr[2]/td[2]', null, "yield", $recipe);

        return $recipe;
    }

}
