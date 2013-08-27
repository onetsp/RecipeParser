<?php

class RecipeParser_Parser_Thepioneerwomancom {

    public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($html, $url);
        return $recipe;
    }

}

?>
