<?php

class RecipeParser_Parser_General {

    static public function parse($html, $url) {

        $recipe = new RecipeParser_Recipe();

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        $recipe->title = RecipeParser_Text::formatTitle(RecipeParser_Text::getMetaProperty($xpath, "og:title"));
        $recipe->photo_url = RecipeParser_Text::getMetaProperty($xpath, "og:image");



        return $recipe;
    }

}
