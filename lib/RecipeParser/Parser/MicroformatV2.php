<?php

class RecipeParser_Parser_MicroformatV2 {

    static public function parse(DOMDocument $doc, $url) {
        $recipe = new RecipeParser_Recipe();
        $xpath = new DOMXPath($doc);

        $hrecipe = null;
        if (!$hrecipe) {
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " h-recipe ")]');
            if ($nodes->length) {
                $hrecipe = $nodes->item(0);
            }
        }

        if ($hrecipe) {
            // Title
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " p-name ")]', $hrecipe);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $recipe->title = RecipeParser_Text::formatTitle($line);
            }

            // Description
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " p-summary ")]', $hrecipe);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $recipe->description = RecipeParser_Text::formatAsParagraphs($line);
            }

            // Credits
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " p-author ")]', $hrecipe);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $recipe->credits = RecipeParser_Text::formatCredits($line);
            }

            // Photo
            $photo_url = "";
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " u-photo ")]', $hrecipe);
            if ($nodes->length) {
                $photo_url = $nodes->item(0)->getAttribute('src');
            }
            if (!$photo_url) {
                // for <img> as sub-node of class="photo"
                $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " u-photo ")]//img', $hrecipe);
                if ($nodes->length) {
                    $photo_url = $nodes->item(0)->getAttribute('src');
                }
            }
            if ($photo_url) {
                $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
            }

            // Yield
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " p-yield ")]', $hrecipe);
            if ($nodes->length) {
                $yield_value = $nodes->item(0)->getAttribute('value');
                $yield_line = RecipeParser_Text::formatYield($nodes->item(0)->nodeValue);
                $recipe->yield = $yield_value ?: $yield_line;
            }

            // Total time
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " dt-duration ")]', $hrecipe);
            if ($nodes->length) {
                $time_value = RecipeParser_Text::iso8601ToMinutes($nodes->item(0)->getAttribute('datetime'));
                $time_line = RecipeParser_Times::toMinutes($nodes->item(0)->nodeValue);
                $recipe->time['total'] = $time_value ?: $time_line;
            }

            // Ingredients
            $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " p-ingredient ")]');
            foreach ($nodes as $node) {

                $line = $node->nodeValue;
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

?>
