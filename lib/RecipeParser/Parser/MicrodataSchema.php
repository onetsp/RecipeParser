<?php

class RecipeParser_Parser_MicrodataSchema {

    static public function parse(DOMDocument $doc, $url) {
        $recipe = new RecipeParser_Recipe();
        $xpath = new DOMXPath($doc);

        $microdata = null;
        $nodes = $xpath->query('//*[contains(@itemtype, "//schema.org/Recipe") or contains(@itemtype, "//schema.org/recipe")]');
        if ($nodes->length) {
            $microdata = $nodes->item(0);
        }

        // Parse elements
        if ($microdata) {

            // Title
            $nodes = $xpath->query('.//*[@itemprop="name"]', $microdata);
            if ($nodes->length) {
                if ($nodes->item(0)->hasAttribute('content')) {
                    $line = $nodes->item(0)->getAttribute('content');
                } else {
                    $line = $nodes->item(0)->nodeValue;
                }
                $value = trim($line);
                $recipe->title = RecipeParser_Text::formatTitle($value);
            }

            // Summary
            $nodes = $xpath->query('.//*[@itemprop="description"]', $microdata);
            if ($nodes->length) {
                if ($nodes->item(0)->hasAttribute('content')) {
                    $line = $nodes->item(0)->getAttribute('content');
                } else {
                    $line = $nodes->item(0)->nodeValue;
                }
                $value = RecipeParser_Text::formatAsParagraphs($line);
                $recipe->description = $value;
            }

            // Times
            $searches = array('prepTime' => 'prep',
                              'cookTime' => 'cook',
                              'totalTime' => 'total');
            foreach ($searches as $itemprop => $time_key) {
                $nodes = $xpath->query('.//*[@itemprop="' . $itemprop . '"]', $microdata);
                if ($nodes->length) {
                    if (strlen($nodes->item(0)->getAttribute('content')) >= 2) { #bug in bonappetit where content is only "P"
                        $value = $nodes->item(0)->getAttribute('content');
                        $value = RecipeParser_Text::iso8601ToMinutes($value);
                    } else if ($value = $nodes->item(0)->getAttribute('datetime')) {
                        $value = RecipeParser_Text::iso8601ToMinutes($value);
                    } else {
                        $value = trim($nodes->item(0)->nodeValue);
                        $value = RecipeParser_Times::toMinutes($value);
                    }
                    if ($value) {
                        $recipe->time[$time_key] = $value;
                    }
                }
            }

            // Yield
            $nodes = $xpath->query('.//*[@itemprop="recipeYield"]', $microdata);
            if (!$nodes->length) {
                $nodes = $xpath->query('.//*[@itemprop="recipeyield"]', $microdata);
            }
            if ($nodes->length) {
                if ($nodes->item(0)->hasAttribute('content')) {
                    $line = $nodes->item(0)->getAttribute('content');
                } else {
                    $line = $nodes->item(0)->nodeValue;
                }
                $recipe->yield = RecipeParser_Text::formatYield($line);
            }

            // Ingredients 
            $nodes = $xpath->query('.//*[@itemprop="ingredients"]', $microdata);
            if (!$nodes->length) {
                $nodes = $xpath->query('.//*[@itemprop="recipeIngredient"]', $microdata);
            }
            foreach ($nodes as $node) {
                if ($nodes->item(0)->hasAttribute('content')) {
                    $line = $node->getAttribute('content');
                } else {
                    $line = $node->nodeValue;
                }
                $value = RecipeParser_Text::formatAsOneLine($line);
                if (empty($value)) {
                    continue;
                }
                if (strlen($value) > 150) {
                    // probably a mistake, like a run-on of existing ingredients?
                    continue;
                }

                if (RecipeParser_Text::matchSectionName($value)) {
                    $value = RecipeParser_Text::formatSectionName($value);
                    $recipe->addIngredientsSection($value);
                } else {
                    $recipe->appendIngredient($value);
                }
            }

            // Instructions
            $found = false;

            // Look for markup that uses <li> tags for each instruction.
            if (!$found) {
                $nodes = $xpath->query('.//*[@itemprop="recipeInstructions"]//li', $microdata);
                if ($nodes->length) {
                    RecipeParser_Text::parseInstructionsFromNodes($nodes, $recipe);
                    $found = true;
                }
            }

            // Look for instructions as direct descendents of "recipeInstructions".
            if (!$found) {
                $nodes = $xpath->query('.//*[@itemprop="recipeInstructions"]/*', $microdata);
                if ($nodes->length) {
                    RecipeParser_Text::parseInstructionsFromNodes($nodes, $recipe);
                    $found = true;

                    // Recipe.com gets caught up in here, but doesn't have well-formed nodes wrapping each ingredient.
                }
            }

            // Some sites will use an "instruction" class for each line.
            if (!$found) {
                $nodes = $xpath->query('.//*[@itemprop="recipeInstructions"]//*[contains(concat(" ", normalize-space(@class), " "), " instruction ")]', $microdata);
                if ($nodes->length) {
                    RecipeParser_Text::parseInstructionsFromNodes($nodes, $recipe);
                    $found = true;
                }
            }

            // Either multiple recipeInstructions nodes, or one node with a blob of text.
            if (!$found) {
                $nodes = $xpath->query('.//*[@itemprop="recipeInstructions"]', $microdata);
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

            // Photo
            $photo_url = "";
            if (!$photo_url) {
                // try to find open graph url
                $nodes = $xpath->query('//meta[@property="og:image"]');
                if ($nodes->length) {
                    $photo_url = $nodes->item(0)->getAttribute('content');
                }
            }
            if (!$photo_url) {
                $nodes = $xpath->query('.//*[@itemprop="image"]', $microdata);
                if ($nodes->length) {
                    $photo_url = $nodes->item(0)->getAttribute('src');
                }
            }
            if (!$photo_url) {
                // for <img> as sub-node of class="photo"
                $nodes = $xpath->query('.//*[@itemprop="image"]//img', $microdata);
                if ($nodes->length) {
                    $photo_url = $nodes->item(0)->getAttribute('src');
                }
            }
            if ($photo_url) {
                $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
            }

            // Credits
            $line = "";
            $nodes = $xpath->query('.//*[@itemprop="author"]', $microdata);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
            }
            $nodes = $xpath->query('.//*[@itemprop="publisher"]', $microdata);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
            }

            $recipe->credits = RecipeParser_Text::formatCredits($line);
        }

        return $recipe;
    }

}
