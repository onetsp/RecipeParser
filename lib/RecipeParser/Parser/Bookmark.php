<?php

class RecipeParser_Parser_Bookmark {
    // DOES NOT EXTEND IMPORTER_ABSTRACT!

    public static function getBookmarkAsRecipeStruct($html, $url) {

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");

        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // This recipe will be stored as a bookmark
        $recipe = new RecipeStruct();
        $recipe->url = $url;
        $recipe->status = "bookmark";

        // Find the page title
        $title = "";
        $title_tag = "";
        $og_title = "";

        $og_title = RecipeParser_Text::getMetaProperty($xpath, "og:title");
        $og_title = RecipeParser_Text::formatTitle($og_title);

        $nodes = $xpath->query('//title');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Text::formatTitle($line);
            if ($line) {
                $title_tag = $line;
            }
        }

        // Which title string to use?
        if ($og_title) {
            $title = $og_title;
        } else if ($title_tag) {
            $title = $title_tag;
        } else {
            $title = "Recipe from $url";
        }
        $recipe->title = $title;

        // Photo
        $photo_url = RecipeParser_Text::getMetaProperty($xpath, "og:image");
        if ($photo_url) {
            $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
        }

        return $recipe;
    }

}
