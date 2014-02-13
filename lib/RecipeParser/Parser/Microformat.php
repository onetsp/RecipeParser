<?php

class RecipeParser_Parser_Microformat {

    static public function parse($html, $url) {
        $recipe = new RecipeParser_Recipe();

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        $hrecipe = null;
        if (!$hrecipe) {
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " hrecipe ")]');
            if ($nodes->length) {
                $hrecipe = $nodes->item(0);
            }
        }
        if (!$hrecipe) {
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " hRecipe ")]');
            if ($nodes->length) {
                $hrecipe = $nodes->item(0);
            }
        }

        if ($hrecipe) {

            // Title
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " fn ")]', $hrecipe);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $recipe->title = RecipeParser_Text::formatTitle($line);
            }

            // Summary 
            $nodes = $xpath->query('.//*[@class="summary"]', $hrecipe);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $recipe->description = RecipeParser_Text::formatAsParagraphs($line);
            }

            // Credits
            $nodes = $xpath->query('.//*[@class="author"]', $hrecipe);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $recipe->credits = RecipeParser_Text::formatCredits($line);
            }

            // Photo
            $photo_url = "";
            $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " photo ")]', $hrecipe);
            if ($nodes->length) {
                $photo_url = $nodes->item(0)->getAttribute('src');
            }
            if (!$photo_url) {
                // for <img> as sub-node of class="photo"
                $nodes = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " photo ")]//img', $hrecipe);
                if ($nodes->length) {
                    $photo_url = $nodes->item(0)->getAttribute('src');
                }
            }
            if ($photo_url) {
                $recipe->photo_url = RecipeParser_Text::formatPhotoUrl($photo_url, $url);
            }

            // Yield
            $nodes = $xpath->query('.//*[@class="yield"]', $hrecipe);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $recipe->yield = RecipeParser_Text::formatYield($line);
            }

            // Prep Times
            $nodes = $xpath->query('.//*[@class="prepTime"]//*[@class="value-title"]', $hrecipe);
            if ($nodes->length) {
                $value = $nodes->item(0)->getAttribute('title');
                $recipe->time['prep'] = RecipeParser_Text::iso8601ToMinutes($value);
            } else {
                $nodes = $xpath->query('.//*[@class="preptime"]', $hrecipe);
                if ($nodes->length) {
                    $value = $nodes->item(0)->nodeValue;
                    $recipe->time['prep'] = RecipeParser_Times::toMinutes($value);
                }
            }

            // Cook Times
            $nodes = $xpath->query('.//*[@class="cookTime"]//*[@class="value-title"]', $hrecipe);
            if ($nodes->length) {
                $value = $nodes->item(0)->getAttribute('title');
                $recipe->time['cook'] = RecipeParser_Text::iso8601ToMinutes($value);
            } else {
                $nodes = $xpath->query('.//*[@class="cooktime"]', $hrecipe);
                if ($nodes->length) {
                    $value = $nodes->item(0)->nodeValue;
                    $recipe->time['cook'] = RecipeParser_Times::toMinutes($value);
                }
            }

            // Total Time / Duration
            $nodes = $xpath->query('.//*[@class="totalTime"]//*[@class="value-title"]', $hrecipe);
            if ($nodes->length) {
                $value = $nodes->item(0)->getAttribute('title');
                $recipe->time['total'] = RecipeParser_Text::iso8601ToMinutes($value);
            } else {
                $nodes = $xpath->query('.//*[@class="duration"]//*[@class="value-title"]', $hrecipe);
                if ($nodes->length) {
                    $value = $nodes->item(0)->getAttribute('title');
                    $recipe->time['total'] = RecipeParser_Text::iso8601ToMinutes($value);
                } else {
                    $nodes = $xpath->query('.//*[@class="duration"]', $hrecipe);
                    if ($nodes->length) {
                        $value = $nodes->item(0)->nodeValue;
                        $recipe->time['total'] = RecipeParser_Times::toMinutes($value);
                    }
                }
            }

            // Ingredients
            $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " ingredient ")]');
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

            // Instructions
            $found = false;

            // Look for usage of <li> to denote each step of the instructions.
            if (!$found) {
                $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " instructions ")]//li');
                if ($nodes->length) {
                    RecipeParser_Text::parseInstructionsFromNodes($nodes, $recipe);
                    $found = true;
                }
            }

            // Look for "instruction class for each step of the instructions.
            if (!$found) {
                $query = '//*[contains(concat(" ", normalize-space(@class), " "), " instructions ")]'
                        .'//*[contains(concat(" ", normalize-space(@class), " "), " instruction ")]';
                $nodes = $xpath->query($query);
                if ($nodes->length) {
                    RecipeParser_Text::parseInstructionsFromNodes($nodes, $recipe);
                    $found = true;
                }

            }

            // Default. Multiple instructions nodes, or one with a blob of text.
            if (!$found) {
                $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " instructions ")]');
                if ($nodes->length > 1) {
                    // Multiple nodes
                    RecipeParser_Text::parseInstructionsFromNodes($nodes, $recipe);
                    $found = true;
                } else if ($nodes->length == 1) {
                    // Blob
                    $str = $nodes->item(0)->nodeValue;
                    RecipeParser_Text::parseInstructionsFromBlob($str, $recipe);
                    $found = true;
                }
            }

        }

        return $recipe;
    }

}

?>
