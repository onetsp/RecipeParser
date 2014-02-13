<?php

class RecipeParser_Parser_Recipecom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_Microformat::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // --- Items not properly definied in Recipe.com's microformat markup.

        // Title -- Fallback if "fn" is not defined.
        if (!$recipe->title) {
            $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " heading1 ")]');
            if ($nodes->length) {
                $recipe->title = trim($nodes->item(0)->nodeValue);
            }
        }

        // Photo -- Fallback if "photo" is not defined.
        if (!$recipe->photo_url) {
            $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " PB10 ")]/img');
            if ($nodes->length) {
                $url = $nodes->item(0)->getAttribute('src');
                $recipe->photo_url = RecipeParser_Text::formatPhotoUrl($url, $this->url);
            }
        }

        // Yield
        $nodes = $xpath->query('//*[@class="servingsize"]');
        if ($nodes->length) {
            $line = trim($nodes->item(0)->nodeValue);
            $recipe->yield = RecipeParser_Text::formatYield($line);
        }

        // Credits
        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " partnerName ")]');
        if ($nodes->length) {
            $line = RecipeParser_Text::FormatAsOneLine($nodes->item(0)->nodeValue);
            $line = preg_replace('/\s*Recipe from\s+(.*)$/', "$1", $line);
            $recipe->credits = trim($line);
        }

        return $recipe;
    }

}

?>
