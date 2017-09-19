<?php

class RecipeParser_Parser_12tomatoescom {

    static public function parse($html, $url) {

        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // ---- OVERRIDES

        // Title
        $nodes = $xpath->query('//h3//strong');
        if ($nodes->length) {
            $line = RecipeParser_Text::formatAsOneLine($nodes->item(0)->nodeValue);
            $recipe->title = $line;
        }

        // Yield
        $nodes = $xpath->query('//div[@class="post-body"]//p');
        foreach ($nodes as $node) {
            $line = trim($node->nodeValue);
            if (strpos($line, "Yield") === 0) {
                $line = RecipeParser_Text::formatYield($line);
                $recipe->yield = $line;
                break;
            }
        }

        // Ingredients
        $nodes = $xpath->query('//div[@class="post-body"]//ul/li');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->appendIngredient($line);
        }

        // Instructions
        $nodes = $xpath->query('//div[@class="post-body"]//ol/li');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->appendInstruction($line);
        }

        // Image
        $recipe->photo_url = RecipeParser_Text::getMetaProperty($xpath, "og:image");

        return $recipe;
    }

}
