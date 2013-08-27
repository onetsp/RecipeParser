<?php

class RecipeParser_Parser_Nytimescom {

    public function parse($html, $url) {

        $recipe = new RecipeParser_Recipe();

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);
        
        $hrecipe = $xpath->query('//div[@class="hrecipe"]');
        if ($hrecipe->length) {

            //
            // "RECIPES" SECTION
            //

            $hrecipe = $hrecipe->item(0);

            // Title
            $nodes = $xpath->query('./h1', $hrecipe);
            if ($nodes->length) {
                $value = trim($nodes->item(0)->nodeValue);
                $recipe->title = $value;
            }

            // Time
            $nodes = $xpath->query('//div[@id = "article"]//p');
            foreach ($nodes as $node) {
                $text = trim($node->nodeValue);
                if (preg_match('/^Time:? (.+)/', $text, $m)) {
                    $str = trim($m[1]);
                    $str = preg_replace('/About (.+)/', '$1', $str);
                    $str = preg_replace('/(.+) plus.*/', '$1', $str);
                    $recipe->time['total'] = Times::toMinutes($str);
                }
            }

            // Yield
            $nodes = $xpath->query('.//*[@class = "yield hmeasure"]', $hrecipe);
            if ($nodes->length) {
                $value = trim($nodes->item(0)->nodeValue);
                $recipe->yield = RecipeParser_Text::formatYield($value);
            }

            // Ingredients
            $nodes = $xpath->query('./div[@class = "ingredientsGroup"]/*', $hrecipe);
            foreach ($nodes as $node) {
                if ($node->nodeName == 'h3') {
                    $value = trim($node->nodeValue);
                    if (!preg_match('/^Ingredients:?$/i', $value)) {
                        $recipe->addIngredientsSection(RecipeParser_Text::formatSectionName($value));
                    }
                } else {
                    foreach ($node->childNodes as $child) {
                        $value = trim($child->nodeValue);
                        $recipe->appendIngredient($value);
                    }
                }
            }

            // Instructions
            $nodes = $xpath->query('.//dl[@class = "preparationSteps"]/dd', $hrecipe);
            foreach ($nodes as $node) {
                $recipe->appendInstruction(trim($node->nodeValue));
            }

            // Notes
            if (!$recipe->notes) {
                $nodes = $xpath->query('.//div[@class = "yieldNotesGroup"]//*[@class = "note"]', $hrecipe);
                if ($nodes->length) {
                    $value = trim($nodes->item(0)->nodeValue);
                    $value = preg_replace("/^Notes?:?\s+/i", '', $value);
                    $recipe->notes = trim($value);
                }
            }


        } else {
            //
            // DINING SECTION RECIPES
            //

            // Title
            $nodes = $xpath->query('//div[@id = "article"]//h1');
            if ($nodes->length) {
                $value = trim($nodes->item(0)->nodeValue);
                $recipe->title = $value;
            }

            // Time and Yield
            $nodes = $xpath->query('//div[@id = "article"]//p');
            foreach ($nodes as $node) {
                $text = trim($node->nodeValue);
                if (preg_match('/^Yield:? (.+)/', $text, $m)) {
                    $recipe->yield = RecipeParser_Text::formatYield($m[1]);
                
                } else if (preg_match('/^Time:? (.+)/', $text, $m)) {
                    $str = trim($m[1]);
                    $str = preg_replace('/About (.+)/', '$1', $str);
                    $str = preg_replace('/(.+) plus.*/', '$1', $str);
                    $recipe->time['total'] = Times::toMinutes($str);
                }
            }

            // Ingredients
            $nodes = $xpath->query('//div[@class="recipeIngredientsList"]/p');
            foreach ($nodes as $node) {
                $line = trim($node->nodeValue);

                // Section names
                if ($line && $line == strtoupper($line)) {
                    $line = RecipeParser_Text::formatSectionName($line);
                    $recipe->addIngredientsSection($line);
                    continue;
                }
                $recipe->appendIngredient($line);
            }

            // Instructions and notes
            $nodes = $xpath->query('//div[@class="articleBody"]//p');
            if (!$nodes->length) {
                $nodes = $xpath->query('//div[@id="articleBody"]//p');
            }

            $notes = '';
            $in_notes_section = false;
            foreach ($nodes as $node) {
                $line = trim($node->nodeValue);

                // Skip some of the useless lines
                if (preg_match('/^(Adapted from|Time|Yield)/i', $line)) {
                    continue;
                }

                // Instructions start with line numbers
                if (!$in_notes_section && preg_match('/^\d+\./', $line)) {
                    $line = RecipeParser_Text::stripLeadingNumbers($line);
                    $recipe->appendInstruction($line);
                    continue;
                }

                // Look for lines that start the notes section.
                $note = '';
                if (preg_match('/^Notes?:?(.*)/i', $line, $m)) {
                    $in_notes_section = true;
                    $note = trim($m[1]);
                } else if ($in_notes_section) {
                    $note = $line;
                }
                if ($note) {
                    $notes .= $note . "\n\n";
                }
            }
            if ($notes) {
                $notes = str_replace("  ", " ", $notes);  // Some unnecessary spaces
                $notes = trim($notes);
                $recipe->notes = $notes;
            }

            // Photo
            $nodes = $xpath->query('//div[@class="image"]//img');
            if ($nodes->length) {
                $photo_url = $nodes->item(0)->getAttribute('src');
                $photo_url = str_replace('-articleInline.jpg', '-popup.jpg', $photo_url);
                $recipe->photo_url = RecipeParser_Text::formatPhotoUrl($photo_url, $url);
            }
        
        }

        return $recipe;
    }

}

?>
