<?php

class RecipeParser_Parser_Thekitchencom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Instructions and Ingredients
        $recipe->resetIngredients();
        $recipe->resetInstructions();
        $nodes = $xpath->query('//*[@id="recipe"]/*');

        $blob = "";
        $found_servings = false;
        foreach ($nodes as $node) {
            $line = trim($node->nodeValue);

            // Stop at the recipe notes section
            if (preg_match("/^Recipe Notes/", $line)) {
                break;
            }
            // Skip the title node
            if ($node->nodeName == "h3") {
                continue;
            }
            // Don't collect the yield or adapted notes
            if (preg_match("/^(Serves |Adapted from )/", $line)) {
                continue;
            }
            // stop collecting text at print/nutrition nodes
            if (preg_match("/^Print Recipe/", $line)) {
                break;
            }

            // Add child nodes to blob
            foreach ($node->childNodes as $child) {
                $line = trim($child->nodeValue);


                switch($child->nodeName) {
                    case "strong":
                        $blob .= $line . " ";
                        break;

                    case "em":
                        if (strpos($line, ":") === false) {
                            $line .= ":";
                        }
                        $blob .= $line . "\n\n";
                        break;

                    case "#text":
                    case "div":
                    case "span":
                    case "p":
                        $blob .= $line . "\n\n";
                        break;
                }
            }

        }
        RecipeParser_Text::parseIngredientsAndInstructionsFromBlob($blob, $recipe);

        return $recipe;
    }

}

