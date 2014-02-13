<?php

class RecipeParser_Parser_Realsimplecom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Photo
        $recipe->photo_url = str_replace("_75.jpg", "_300.jpg", $recipe->photo_url);

        return $recipe;
    }

}

?>
