<?php

class RecipeParser_Parser_General {

    static public function parse($html, $url) {

        $recipe = new RecipeParser_Recipe();

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Title
		$html_title = "";
		$og_title = "";

        $nodes = $xpath->query('//title');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $html_title = RecipeParser_Text::formatTitle($line);
        }
        $og_title = RecipeParser_Text::formatTitle(RecipeParser_Text::getMetaProperty($xpath, "og:title"));
        
		// Pick which title to use
        if ($og_title) {
            $title = $og_title;
        } else if ($html_title) {
            $title = $html_title;
        } else {
            $title = "Recipe from $url";
        }
        $recipe->title = $title;
		

        // Photo URL
        $recipe->photo_url = RecipeParser_Text::getMetaProperty($xpath, "og:image");

/*
        $nodes = $xpath->query('//meta[@property="og:image"]');
        if ($nodes->length) {
            foreach ($nodes as $node) {
                // Get the first image that looks like a jpg (ignore pngs and gifs that are probably site icons)
                $photo_url = $node->getAttribute("content");
                if (preg_match("/\.jpg$/", $photo_url)) {
                    $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
                    break;
                }
            }
        }
*/

        return $recipe;
    }

}
