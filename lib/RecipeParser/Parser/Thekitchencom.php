<?php

class RecipeParser_Parser_Thekitchencom {

    static public function parse(DOMDocument $doc, $url) {
        $recipe = new RecipeParser_Recipe();
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR THEKITCHEN.COM

        // Title
        $nodes = $xpath->query('//*[@id="recipe"]/h3');
        if ($nodes->length) {
            $line = RecipeParser_Text::formatTitle($nodes->item(0)->nodeValue);
            $recipe->title = $line;
        }

        // Instructions and Ingredients
        $nodes = $xpath->query('//*[@id="recipe"]/*');

        $blob = "";
        $found_servings = false;
        foreach ($nodes as $node) {

            // Skip title
            if ($node->nodeName == "h3") {
                continue;
            }

            // Get servings
            $line = $node->nodeValue;
            if (strpos($line, "Serves")) {
                if (preg_match("/.*(Serves.+)$/m", $line, $m)) {
                    $line = $m[1];
                    $recipe->yield = RecipeParser_Text::formatYield($line);
                    continue;
                }
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
                        if ($line == "•") {
                            continue;
                        }
                        $blob .= $line . "\n\n";
                        break;
                }
            }

        }
        RecipeParser_Text::parseIngredientsAndInstructionsFromBlob($blob, $recipe);

        // Photo
        $nodes = $xpath->query('//meta[@property="og:image"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->getAttribute("content");
            $recipe->photo_url = $line;
        }

        return $recipe;
    }

}

