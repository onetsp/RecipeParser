<?php

class RecipeParser_Parser_Eatingwellcom {

    static public function parse($html, $url) {
        $recipe = new RecipeParser_Recipe();

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Title
        $nodes = $xpath->query('//h1[@itemprop="name"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Text::formatTitle($line);
            $recipe->title = $line;
        }

        // Description
        $nodes = $xpath->query('//*[@itemprop="description"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->description = $line;
        }

        // Author
        $nodes = $xpath->query('//span[@itemprop="author"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Text::formatCredits($line);
            $recipe->credits = $line;
        }

        // Prep Times
        $nodes = $xpath->query('//*[@itemprop="prepTime"]');
        if ($nodes->length) {
            $value = $nodes->item(0)->getAttribute("content");
            $recipe->time['prep'] = RecipeParser_Text::iso8601ToMinutes($value);
        }

        // Total Time
        $nodes = $xpath->query('//*[@itemprop="totalTime"]');
        if ($nodes->length) {
            $value = $nodes->item(0)->getAttribute("content");
            $recipe->time['total'] = RecipeParser_Text::iso8601ToMinutes($value);
        }

        // Yield
        $nodes = $xpath->query('//*[@itemprop="recipeyield"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $recipe->yield = RecipeParser_Text::formatYield($line);
        }

        // Ingredients
        $nodes = $xpath->query('//*[@itemprop="ingredients"]');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->appendIngredient($line);
        }

        // Instructions
        $nodes = $xpath->query('//*[@itemprop="recipeinstructions"]/li');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->appendInstruction($line);
        }

        // Photo
        $recipe->photo_url = RecipeParser_Text::getMetaProperty($xpath, "og:image");

        return $recipe;
    }

}

