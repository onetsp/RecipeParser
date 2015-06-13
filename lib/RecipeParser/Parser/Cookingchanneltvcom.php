<?php

class RecipeParser_Parser_Cookingchanneltvcom {

    static public function parse($html, $url) {
        $recipe = new RecipeParser_Recipe();

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Title
        $nodes = $xpath->query('//*[@class="rTitle fn"]');
        if ($nodes->length) {
            $line = RecipeParser_Text::formatTitle($nodes->item(0)->nodeValue);
            $recipe->title = $line;
        }

        // Yield
        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " yield ")]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $recipe->yield = RecipeParser_Text::formatYield($line);
        }

        // Times
        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " prepTime ")]/span');
        if ($nodes->length) {
            $line = $nodes->item(1)->getAttribute("title");
            $recipe->time['prep'] = RecipeParser_Text::iso8601ToMinutes($line);
        }
        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " rspec-cook-time ")]/span');
        if ($nodes->length) {
            $line = $nodes->item(1)->getAttribute("title");
            $recipe->time['cook'] = RecipeParser_Text::iso8601ToMinutes($line);
        }
        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " totaltime ")]/span');
        if ($nodes->length) {
            $line = $nodes->item(1)->getAttribute("title");
            $recipe->time['total'] = RecipeParser_Text::iso8601ToMinutes($line);
        }

        // Ingredients
        $nodes = $xpath->query('//*[@class="ingredient"]');
        foreach ($nodes as $node) {
            $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
            $recipe->appendIngredient($line);
        }

        // Instructions
        $nodes = $xpath->query('//*[@class="instructions"]');
        if ($nodes->length) {
            $blob = "";
            foreach ($nodes->item(0)->childNodes as $node) {
                $blob .= RecipeParser_Text::formatAsOneLine($node->nodeValue) . " ";
                if ($node->nodeName == "p") {
                    $blob .= "\n\n";
                }
            }

            // Minor cleanup
            $blob = str_replace(" , ", ", ", $blob);
            $blob = str_replace(" . ", ". ", $blob);
            $blob = str_replace("  ", " ", $blob);

            foreach (explode("\n\n", $blob) as $line) {
                $line = RecipeParser_Text::formatAsOneLine($line);
                $recipe->appendInstruction($line);
            }
        }

        // Photo
        $nodes = $xpath->query('//a[@class="img-enlarge"]');
        if ($nodes->length) {
            $photo_url = $nodes->item(0)->getAttribute("href");
            $photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
            $recipe->photo_url = $photo_url;
        }

        return $recipe;
    }

}
