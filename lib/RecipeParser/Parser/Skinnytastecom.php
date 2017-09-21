<?php

class RecipeParser_Parser_Skinnytastecom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Title
        if (!$recipe->title) {
            $line = RecipeParser_Text::getMetaProperty($xpath, "og:title");
            $line = RecipeParser_Text::formatTitle(preg_replace("/\| Skinnytaste/", "", $line));
            $recipe->title = $line;
        }

        // Photo
        if (!$recipe->photo_url) {
            $line = RecipeParser_Text::getMetaProperty($xpath, "og:image");
            $recipe->photo_url = $line;
        }

        // Did we get instructions from Schema.org?
        if (count($recipe->instructions[0]['list']) > 0) {
            return $recipe;
        }

        // Find collection of nodes that house entire article, ingredients, and instructions.
        $nodes = $xpath->query('//div[@class="post-title"]');
        if (!$nodes->length) {
            return $recipe; // bombed out
        }
        $nodes = $nodes->item(0)->parentNode->childNodes;

        // Iterate through nodes
        $found_ingredients = false;
        $found_instructions = false;
        foreach ($nodes as $node) {
            $line = trim($node->nodeValue);

            // Locate waypoints along the node traversal
            if (empty($line)) {
                continue;
            } else if ($line == "Ingredients:") {
                $found_ingredients = true;
                continue;
            } else if ($line == "Directions:") {
                $found_instructions = true;
                continue;
            } else if ($line == "Print This" || preg_match("/if you like.*you might also like/i", $line)) {
                break;
            } else if ($node->nodeName == "ul") {
                $found_ingredients = true;
                $found_instructions = false;
            } else if ($node->nodeName == "p" && $found_ingredients) {
                $found_instructions = true;
            }
            
            if (!$found_ingredients) {
                continue;
            }

            // Extract ingredients
            if ($found_ingredients && !$found_instructions) {
                if ($node->nodeName == "ul") {
                    // See if we've accidentally put any section names into the instructions list
                    if (count($recipe->instructions[0]['list'])) {
                        $str = array_shift($recipe->instructions[0]['list']);
                        $str = RecipeParser_Text::formatSectionName($str);
                        $recipe->addIngredientsSection($str);
                    }
                    // Get all of the <li> nodes as instructions
                    foreach ($node->childNodes as $n) {
                        $line = RecipeParser_Text::formatAsOneLine($n->nodeValue);
                        $recipe->appendIngredient($line);
                    }
                    continue;
                } else if (RecipeParser_Text::matchSectionName($line)) {
                    $line = RecipeParser_Text::formatSectionName($line);
                    $recipe->addIngredientsSection($line);
                    continue;
                }

            // Extract instructions
            } else if ($found_instructions) {
                $line = RecipeParser_Text::formatAsOneLine($line);
                $recipe->appendInstruction($line);
                continue;
            }


        }

        return $recipe;
    }

}

