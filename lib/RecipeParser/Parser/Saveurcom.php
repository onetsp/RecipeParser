<?php

class RecipeParser_Parser_Saveurcom {

    static public function parse($html, $url) {
#        $recipe = new RecipeParser_Recipe();

        $recipe = RecipeParser_Parser_General::parse($html, $url);
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        $myxpath->singleNodeLookup('//h1', null, "title", $recipe);
        $myxpath->singleNodeLookup('//span[@property="recipeYield"]', null, "yield", $recipe);

        $myxpath->simpleIngredientsListLookup('//*[@property="ingredients"]', $recipe);
        $myxpath->simpleInstructionsListLookup('//*[@property="recipeInstructions"]', $recipe);

        // Description
        $str = "";
        $nodes = $xpath->query('//*[@class="field-body"]//p');
        foreach ($nodes as $node) {
            if ($str) {
                $str .= "\n\n";
            }
            $str = $node->nodeValue;
            $str = RecipeParser_Text::formatAsOneLine($str);
        }
        $recipe->description = $str;

        return $recipe;
    }

}
