<?php

class RecipeParser_Parser_Saveurcom {

    public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($html, $url);
        
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // TODO: Get rid of "    [title] => Sponsored Recipe: Ghirardelli's® Chocolate Chip Bundt Cake"



        return $recipe;
    }

}

?>
