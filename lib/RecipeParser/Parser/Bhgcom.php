<?php

class RecipeParser_Parser_Bhgcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        $myxpath->singleNodeLookup('//h1', null, "title", $recipe);

        return $recipe;
    }

}
