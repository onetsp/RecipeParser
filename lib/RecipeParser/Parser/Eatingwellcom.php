<?php

class RecipeParser_Parser_Eatingwellcom {

    static public function parse(DOMDocument $doc, $url) {
        $recipe = new RecipeParser_Recipe();
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR EATINGWELL.COM

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
        $nodes = $xpath->query('//meta[@property="og:image"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->getAttribute("content");
            $recipe->photo_url = $line;
        }

        return $recipe;
    }

}

