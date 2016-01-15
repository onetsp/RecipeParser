<?php

class RecipeParser_Parser_12tomatoescom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR 12TOMATOES.COM

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
        $nodes = $xpath->query('//meta[@property="og:image"]');
        foreach ($nodes as $node) {
            $line = $node->getAttribute("content");
            $recipe->photo_url = $line;
            break;
        }

        return $recipe;
    }

}
