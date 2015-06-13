<?php

class RecipeParser_Parser_Realsimplecom {

    static public function parse($html, $url) {
        $recipe = new RecipeParser_Recipe();

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Title
        $nodes = $xpath->query('//*[@id="page-title"]');
        if ($nodes->length) {
            $line = RecipeParser_Text::formatTitle($nodes->item(0)->nodeValue);
            $recipe->title = $line;
        }

        // Times
        $nodes = $xpath->query('//*[@class="field-recipe-time"]');
        foreach ($nodes as $node) {
            $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);

            if (strpos($line, "Hands-On Time") !== false) {
                $line = str_replace("Hands-On Time ", "", $line);
                $recipe->time["prep"] = RecipeParser_Times::toMinutes($line);
            } else if (strpos($line, "Total Time") !== false) {
                $line = str_replace("Total Time ", "", $line);
                $recipe->time["total"] = RecipeParser_Times::toMinutes($line);

            }
        }

        // Yield
        $nodes = $xpath->query('//*[@class="field-yield"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Text::formatYield($line);
            $recipe->yield = $line;
        }

        // Ingredients
        $nodes = $xpath->query('//*[@class="field-ingredients"]');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->appendIngredient($line);
        }

        // Instructions
        $nodes = $xpath->query('//*[@class="field-instructions"]//li');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->appendInstruction($line);
        }

        // Photo
        $nodes = $xpath->query('//*[@property="og:image"]');
        if ($nodes->length) {
            $photo_url = $nodes->item(0)->getAttribute('content');
            $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
        }

        return $recipe;
    }

}
