<?php

class RecipeParser_Parser_Recipage {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_Microformat::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // correct missing title
        if (empty($recipe->title)) {
            $nodes = $xpath->query(".//*[@itemprop='blogPost']/*[@itemprop='name']");
            if ($nodes->length) {
                $recipe->title = RecipeParser_Text::formatTitle($nodes->item(0)->nodeValue);
            }
        }

        return $recipe;
    }

}
