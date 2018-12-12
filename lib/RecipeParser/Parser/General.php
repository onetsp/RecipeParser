<?php

class RecipeParser_Parser_General {

    static public function parse($html, $url) {
        $recipe = new RecipeParser_Recipe();
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        // Title
        $myxpath->singleNodeLookup('//title', null, "title", $recipe);
        if (!$recipe->title) {
            $myxpath->singleNodeLookup('//meta[@itemprop="og:title"]', "content", "title", $recipe);
        }
        if (!$recipe->title) {
            $recipe->title = "Recipe from $url";
        }

        // Photo
        $nodes = $xpath->query('//meta[@property="og:image"]');
        if ($nodes->length) {
            foreach ($nodes as $node) {
                // Get the first image that looks like a jpg (ignore pngs and gifs that are probably site icons)
                $photo_url = $node->getAttribute("content");
                if (preg_match("/\.(jpeg|jpg)$/i", $photo_url)) {
                    $photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
                    $recipe->photo_url = $photo_url;
                    break;
                }
            }
        }

        return $recipe;
    }

}
