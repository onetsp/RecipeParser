<?php

class RecipeParser_Parser_Tasteofhomecom {

    public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Notes
        $nodes = $xpath->query('//div[@class="rd_editornote margin_bottom"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $line = preg_replace("/Editor's Note:\s+/", "", $line);
            $recipe->notes = $line;
        }

        // Override image
        $nodes = $xpath->query('//meta[@itemprop="image"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->getAttribute("content");
            $recipe->photo_url = $line;
        }

        return $recipe;
    }

}

?>
