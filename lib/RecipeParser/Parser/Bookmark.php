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
        $title_og_meta = "";

        $nodes = $xpath->query('//title');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Text::formatTitle($line);
            if ($line) {
                $title_tag = $line;
            }
        }
        $nodes = $xpath->query('//meta[@property="og:title"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->getAttribute("content");
            $line = RecipeParser_Text::formatTitle($line);
            if ($line) {
                $title_og_meta = $line;
            }
        }

        // Which title string to use?
        if ($title_og_meta) {
            $title = $title_og_meta;
        } else if ($title_tag) {
            $title = $title_tag;
        } else {
            $title = "Recipe from $url";
        }
        $recipe->title = $title;

        // Get image from Open Graph tag
        $nodes = $xpath->query('//meta[@property="og:image"]');
        if ($nodes->length) {
            $photo_url = $nodes->item(0)->getAttribute("content");
            if ($photo_url) {
                $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
            }
        }

        return $recipe;
    }

}
