<?php

class RecipeParser_Parser_Cookingnytimescom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        // Notes
        if (!$recipe->notes) {
            $nodes = $xpath->query('//*[@class="recipe-note-description"]');
            if ($nodes->length) {
                $value = trim($nodes->item(0)->nodeValue);
                $value = preg_replace("/^Notes?:?\s*/i", '', $value);
                $recipe->notes = trim($value);
            }
        }

        return $recipe;
    }

}
