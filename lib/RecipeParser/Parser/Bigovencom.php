<?php

class RecipeParser_Parser_Bigovencom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microformat stuff we can find.
        $recipe = RecipeParser_Parser_Microformat::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR BIGOVEN.COM

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
