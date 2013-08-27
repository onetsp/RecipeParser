<?php

class RecipeParser_Parser_MicrodataDataVocabulary {

    public function parse($html, $url) {

        $recipe = new RecipeParser_Recipe();

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Find the top-level node for Recipe microdata
        $microdata = null;
        $nodes = $xpath->query('//*[@itemtype="http://data-vocabulary.org/Recipe"]');
        if ($nodes->length) {
            $microdata = $nodes->item(0);
        }

        // Parse elements
        if ($microdata) {

            // Title
            $nodes = $xpath->query('.//*[@itemprop="name"]', $microdata);
            if ($nodes->length) {
                $recipe->title = trim($nodes->item(0)->nodeValue);
            }

            // Summary
            $nodes = $xpath->query('.//*[@itemprop="summary"]', $microdata);
            if ($nodes->length) {
                $value = trim($nodes->item(0)->nodeValue);
                $recipe->description = $value;
            }

            // Times
            $searches = array('prepTime' => 'prep',
                              'cookTime' => 'cook',
                              'totalTime' => 'total');
            foreach ($searches as $itemprop => $time_key) {
                $nodes = $xpath->query('.//*[@itemprop="' . $itemprop . '"]', $microdata);
                if ($nodes->length) {
                    if ($value = $nodes->item(0)->getAttribute('datetime')) {
                        $value = RecipeParser_Text::iso8601ToMinutes($value);
                    } else {
                        $value = trim($nodes->item(0)->nodeValue);
                        $value = Times::toMinutes($value);
                    }
                    if ($value) {
                        $recipe->time[$time_key] = $value;
                    }
                }
            }

            // Yield
            $nodes = $xpath->query('.//*[@itemprop="yield"]', $microdata);
            if ($nodes->length) {
                $line = trim($nodes->item(0)->nodeValue);
                $line = preg_replace('/\s+/', ' ', $line);
                $recipe->yield = RecipeParser_Text::formatYield($line);
            }

            // Ingredients 
            $nodes = null;
            
            // (data-vocabulary)
            if (!$nodes || !$nodes->length) {
                $nodes = $xpath->query('.//*[@itemprop="ingredient"]', $microdata);
            }
            if (!$nodes || !$nodes->length) { // non-standard
                $nodes = $xpath->query('.//*[@id="ingredients"]//li', $microdata);
            }
            if (!$nodes || !$nodes->length) { // non-standard
                $nodes = $xpath->query('.//*[@class="ingredients"]//li', $microdata);
            }

            foreach ($nodes as $node) {
                $value = $node->nodeValue;
                $value = RecipeParser_Text::formatAsOneLine($value);
                if (empty($value)) {
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
                $nodes = $xpath->query('.//*[@itemprop="instructions"]//li', $microdata);
                if ($nodes->length) {
                    RecipeParser_Text::parseInstructionsFromNodes($nodes, $recipe);
                    $found = true;
                }
            }

            // Some sites will use an "instruction" class for each line.
            if (!$found) {
                $nodes = $xpath->query('.//*[@itemprop="instruction"]//*[contains(concat(" ", normalize-space(@class), " "), " instruction ")]', $microdata);
                if ($nodes->length) {
                    RecipeParser_Text::parseInstructionsFromNodes($nodes, $recipe);
                    $found = true;
                }
            }

            // Either multiple instrutions nodes, or one node with a blob of text.
            if (!$found) {
                $nodes = $xpath->query('.//*[@itemprop="instructions"]', $microdata);
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
            $nodes = $xpath->query('.//*[@itemprop="photo"]', $microdata);
            if ($nodes->length) {
                if ($nodes->item(0)->hasAttribute('src')) {
                    $photo_url = $nodes->item(0)->getAttribute('src');
                } else if ($nodes->item(0)->hasAttribute('content')) {
                    $photo_url = $nodes->item(0)->getAttribute('content');
                }
            }
            if (!$photo_url) {
                // for <img> as sub-node of class="photo"
                $nodes = $xpath->query('.//*[@itemprop="photo"]//img', $microdata);
                if ($nodes->length) {
                    $photo_url = $nodes->item(0)->getAttribute('src');
                }
            }
            if ($photo_url) {
                $recipe->photo_url = RecipeParser_Text::formatPhotoUrl($photo_url, $url);
            }

            // Credits
            $nodes = $xpath->query('.//*[@itemprop="author"]', $microdata);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $recipe->credits = RecipeParser_Text::formatCredits($line);
            }

        }

        return $recipe;
    }

}

?>
