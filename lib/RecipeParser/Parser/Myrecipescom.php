<?php

class RecipeParser_Parser_Myrecipescom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_StructuredData::parse($html, $url);
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        $myxpath->simpleInstructionsListLookup('//*[@class="step"]/p', $recipe);

        return $recipe;
    }

}
