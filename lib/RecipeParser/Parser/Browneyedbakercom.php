<?php

class RecipeParser_Parser_Browneyedbakercom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard hrecipe stuff we can find.
        $recipe = RecipeParser_Parser_Microformat::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " ingredient ")]');
        foreach ($nodes as $node) {
            foreach (explode(PHP_EOL, $node->nodeValue) as $line) {
                $line = trim($line);
                $line = RecipeParser_Text::formatAsOneLine($line);

                // Skip lines that contain no word-like characters (sometimes used as section dividers).
                if (!preg_match("/\w/", $line)) {
                    continue;
                }

                // Section name delineated with dashes. E.g. "---Cake---"
                if (preg_match('/^\-+([^\-]{1}.*[^\-]{1})\-+$/', $line, $m)) {
                    $line = RecipeParser_Text::formatSectionName($m[1]);
                    $recipe->addIngredientsSection($line);
                    continue;
                }

                // Section name with colon.
                if (preg_match('/^(.+)\:$/', $line, $m)) {
                    $line = RecipeParser_Text::formatSectionName($m[1]);
                    $recipe->addIngredientsSection($line);
                    continue;
                }

                $recipe->appendIngredient($line);
            }
        }

        return $recipe;
    }

}

