<?php

class RecipeParser_Parser_Eatingwellcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_General::parse($html, $url);
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        $myxpath->singleNodeLookup('//*[@itemprop="description"]', null, "description", $recipe);
        $myxpath->singleNodeLookup('//span[@itemprop="author"]', null, "credits", $recipe);
        $myxpath->singleNodeLookup('//*[@itemprop="recipeyield"]', null, "yield", $recipe);

        $myxpath->singleNodeLookup('//*[@itemprop="prepTime"]', null, "time_prep", $recipe);
        $myxpath->singleNodeLookup('//*[@itemprop="totalTime"]', null, "time_total", $recipe);

        $myxpath->simpleIngredientsListLookup('//*[@itemprop="ingredients"]', $recipe);
        $myxpath->simpleInstructionsListLookup('//*[@itemprop="recipeinstructions"]/li', $recipe);

        return $recipe;
    }

}

