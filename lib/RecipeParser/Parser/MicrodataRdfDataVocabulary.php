<?php

class RecipeParser_Parser_MicrodataRdfDataVocabulary {

    static public function parse(DOMDocument $doc, $url) {
        $recipe = new RecipeParser_Recipe();
        $xpath = new DOMXPath($doc);

        // Title
        $nodes = $xpath->query('//*[@property="v:name"]');
        if ($nodes->length) {
            $recipe->title = trim($nodes->item(0)->nodeValue);
        }

        // Summary
        $nodes = $xpath->query('//*[@property="v:summary"]');
        if ($nodes->length) {
            $value = trim($nodes->item(0)->nodeValue);
            $recipe->description = $value;
        }

        // Times
        $searches = array('v:prepTime' => 'prep',
                          'v:cookTime' => 'cook',
                          'v:totalTime' => 'total');
        foreach ($searches as $itemprop => $time_key) {
            $nodes = $xpath->query('//*[@property="' . $itemprop . '"]');
            if ($nodes->length) {
                if ($value = $nodes->item(0)->getAttribute('content')) {
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
        $nodes = $xpath->query('//*[@property="v:yield"]');
        if ($nodes->length) {
            $line = trim($nodes->item(0)->nodeValue);
            $line = preg_replace('/\s+/', ' ', $line);
            $recipe->yield = RecipeParser_Text::formatYield($line);
        }

        // Ingredients 
        $nodes = null;
        
        // (data-vocabulary)
        $nodes = $xpath->query('//*[@rel="v:ingredient"]');
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

        // Some sites will use an "instruction" class for each line.
        if (!$found) {
            $nodes = $xpath->query('//*[@property="v:instructions"]//*[@property="v:instruction"]');
            if ($nodes->length) {
                RecipeParser_Text::parseInstructionsFromNodes($nodes, $recipe);
                $found = true;
            }
        }

        // Look for markup that uses <li>, <p> or other tags for each instruction.
        $search_sub_nodes = array("p", "li");
        while (!$found && $tag = array_pop($search_sub_nodes)) {
            $nodes = $xpath->query('//*[@property="v:instructions"]//' . $tag);
            if ($nodes->length) {
                RecipeParser_Text::parseInstructionsFromNodes($nodes, $recipe);
                $found = true;
            }
        }

        // Either multiple instrutions nodes, or one node with a blob of text.
        if (!$found) {
            $nodes = $xpath->query('//*[@property="v:instructions"]');
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
        $nodes = $xpath->query('//*[@rel="v:photo"]');
        if ($nodes->length) {
            $photo_url = $nodes->item(0)->getAttribute('src');
        }

        if (!$photo_url) {
            // for <img> as sub-node of rel="v:photo"
            $nodes = $xpath->query('//*[@rel="v:photo"]//img');
            if ($nodes->length) {
                $photo_url = $nodes->item(0)->getAttribute('src');
            }
        }
        if ($photo_url) {
            $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
        }

        // Credits
        $nodes = $xpath->query('//*[@property="v:author"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $recipe->credits = RecipeParser_Text::formatCredits($line);
        }

        return $recipe;
    }

}

?>
