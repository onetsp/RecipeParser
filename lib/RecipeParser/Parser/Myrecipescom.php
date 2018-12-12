<?php

class RecipeParser_Parser_Myrecipescom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        $myxpath->singleNodeLookup('//meta[@property="og:title"]', "content", "title", $recipe);
        $myxpath->singleNodeLookup('//*[@itemprop="author"]/*[@class="field-sponsor"]', null, "credits", $recipe);

        $myxpath->simpleIngredientsListLookup('//*[@class="field-ingredients"]', $recipe);
        $myxpath->simpleInstructionsListLookup('//*[@itemprop="recipeInstructions"]//p', $recipe);

        // Times
        $searches = array('prep' => 'prep: ',
                          'cook' => 'cook: ',
                          'total' => 'total: ');

        $nodes = $xpath->query('//*[@class="recipe-time-info"]');
        foreach ($nodes as $node) {
            $line = trim(strtolower($node->nodeValue));
            foreach ($searches as $key=>$value) {
                if (strpos($line, $value) === 0) {
                    $line = str_replace($value, "", $line);
                    $recipe->time[$key] = RecipeParser_Times::toMinutes($line);
                }
            }
        }

        return $recipe;
    }

}
