<?php

class RecipeParser_Parser_Bigovencom {

    static public function parse($html, $url) {
        // Get all of the standard hrecipe stuff we can find.
        $recipe = RecipeParser_Parser_Microformat::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Yield
        $nodes = $xpath->query('//*[@name="resizeTo"]');
        if ($nodes->length) {
            $line = trim($nodes->item(0)->getAttribute("value")) . " servings";
            $recipe->yield = RecipeParser_Text::formatYield($line);
        }

        // Ingredients
        $recipe->resetIngredients();

        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " ingredient ")]');
        foreach ($nodes as $node) {

            $parts = array();
            foreach ($node->childNodes as $n) {
                $parts[] = $n->nodeValue;
            }
            $line = implode(' ', $parts);
            $line = str_replace(" ; ", "; ", $line);
            $line = RecipeParser_Text::formatAsOneLine($line);

            $recipe->appendIngredient($line);
        }

        // Instructions
        $recipe->resetInstructions();

        $nodes = $xpath->query('//div[@class="display-field"]/p');
        foreach ($nodes as $node) {
            $line = trim($node->nodeValue);
            if ($line == strtoupper($line)) {
                $line = RecipeParser_Text::formatSectionName($line);
                $recipe->addInstructionsSection($line);
            } else {
                $recipe->appendInstruction($line);
            }
        }

        return $recipe;
    }

}
