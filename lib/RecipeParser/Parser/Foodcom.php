<?php

class RecipeParser_Parser_Foodcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Photo -- skip logo if it was used in place of photo
        if (strpos($recipe->photo_url, "FDC_Logo_vertical.png") !== false
            || strpos($recipe->photo_url, "FDC_share-logo.png") !== false) 
        {
            $recipe->photo_url = '';
        }
        if ($recipe->photo_url) {
            $recipe->photo_url = str_replace("/thumbs/", "/large/", $recipe->photo_url);
        }

        // Yield
        $yield = '';
        $nodes = $xpath->query('//*[@class="yield"]');

        // Find as 'yield'
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Text::formatYield($line);
            $recipe->yield = $line;

        // Or as number of 'servings'
        } else {
            $nodes = $xpath->query('//*[@class="servings"]//*[@class="value"]');
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $line = RecipeParser_Text::formatYield($line);
                $recipe->yield = $line;
            }
        }

        return $recipe;
    }

}
