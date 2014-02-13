<?php

class RecipeParser_Parser_Americastestkitchencom {

    static public function parse($html, $url) {

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);

        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        $recipe = new RecipeParser_Recipe();

        // Title
        $nodes = $xpath->query('//div[@id="detail_content"]/h1');
        if ($nodes->length) {
            $recipe->title = trim($nodes->item(0)->nodeValue);
        }

        // Yield and Times
        $nodes = $xpath->query('//p[@id="yield"]');
        if ($nodes->length) {
            $line = trim($nodes->item(0)->nodeValue);
            $recipe->yield = RecipeParser_Text::formatYield($line);
        }

        // Notes, instructions, and ingredients are not very well structured.
        $found_ingredients = false;
        $found_instructions = false;

        $nodes = $xpath->query('//div[@id="detail_content"]/*');
        foreach ($nodes as $node) {

            // Notes -- Weird, but this is the only <p> that doesn't have attributes
            // on the tag.
            if ($node->nodeName == 'p') {
                if (!$node->hasAttributes()) {
                    $recipe->notes = trim($node->nodeValue);
                    continue;
                }
            }

            // Ingredients/ingredients markers
            if ($node->nodeName == 'h5') {
                $line = strtolower(trim($node->nodeValue));
                if ($line == 'ingredients') {
                    $found_ingredients = true;
                    continue;
                } else if ($line == 'instructions') {
                    $found_instructions = true;
                }
            }

            // Ingredients
            if ($found_ingredients && !$found_instructions) {

                if ($node->nodeName == 'h6') {
                    $line = trim($node->nodeValue);
                    $line = RecipeParser_Text::formatSectionName($line);
                    $recipe->addIngredientsSection($line);

                } else if ($node->nodeName == 'ul') {
                    $sub_nodes = $node->childNodes;
                    foreach ($sub_nodes as $sub) {
                        $line = trim($sub->nodeValue);

                        // Add spaces between quantities and units
                        $line = preg_replace('/(\d+)([A-Za-z]+)/', "$1 $2", $line);

                        // Remove spaces before commas (not sure why this happens in their HTML)
                        $line = str_replace(' ,', ',', $line);

                        // Condense multiple spaces
                        $line = str_replace('  ', ' ', $line);

                        $recipe->appendIngredient($line);
                    }
                }

            }

            // Instructions
            if ($found_instructions) {
                if ($node->nodeName == 'ul') {
                    $sub_nodes = $node->childNodes;
                    foreach ($sub_nodes as $sub) {
                        $line = trim($sub->nodeValue);
                        $line = RecipeParser_Text::stripLeadingNumbers($line);
                        $recipe->appendInstruction($line);
                    }
                }
            }

        }

        // Photo
        $nodes = $xpath->query('//img[@class="detail"]');
        if ($nodes->length) {
            $photo_url = $nodes->item(0)->getAttribute('src');
            $recipe->photo_url = RecipeParser_Text::formatPhotoUrl($photo_url, $url);
        }

        return $recipe;
    }

}
