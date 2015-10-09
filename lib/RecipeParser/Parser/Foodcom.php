<?php

class RecipeParser_Parser_Foodcom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR FOOD.COM

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
