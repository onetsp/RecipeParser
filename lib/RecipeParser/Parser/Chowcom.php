<?php

class RecipeParser_Parser_Chowcom {

    static public function parse($html, $url) {
        // Get all of the standard bits we can find.
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($html, $url);

        // Titles include "recipe"
        if (preg_match("/ Recipe$/", $recipe->title)) {
            $recipe->title = trim(preg_replace("/(.*) Recipe$/", "$1", $recipe->title));
        }

        // Cleanup description
        if ($recipe->description) {
            $recipe->description = preg_replace("/^(Read our review of|This (dish|recipe) was featured as part|See more recipes) .*$/m", "", $recipe->description);
            $recipe->description = preg_replace("/[\r\n]{3,}/", "\n\n", $recipe->description);
            $recipe->description = trim($recipe->description);
        }

        return $recipe;
    }

}
