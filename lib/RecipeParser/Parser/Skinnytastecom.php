<?php

class RecipeParser_Parser_Skinnytastecom {

    static public function parse(DOMDocument $doc, $url) {
        $recipe = new RecipeParser_Recipe();
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR SKINNYTASTE.COM

        // Title
        $nodes = $xpath->query('//title');
        if ($nodes->length) {
            $value = $nodes->item(0)->nodeValue;
            $value = substr($value, 0, strpos($value, "|"));
            $value = RecipeParser_Text::formatTitle($value);
            $recipe->title = $value;
        }

        // Ingredients and Instructions
        $blob = "";
        $separator = "\n---\n";
        $nodes = $xpath->query('//*[@class="post-body entry-content"]');

        foreach ($nodes->item(0)->childNodes as $node) {
            $value = trim($node->nodeValue);

            // Make sure not to pick up recommendations that follow directions.
            if (preg_match("/if you like.*you might also like/i", $value)) {
                break;
            }

            // Look for "ingredients header"
            if (strpos($value, "Ingredients") !== false) {
                $blob .= $value . $separator;
                continue;
            }

            // Combine text into the blob.
            switch($node->nodeName) {
                case "ul":
                    $blob .= "\n" . $value . $separator;
                    break;

                case "#text":
                case "span":
                case "strong":
                case "b":
                case "em":
                case "i":
                case "a":
                    $blob .= $value . " ";
                    break;

                case "br":
                    $blob .= "\n";
                    break;

                case "div":
                case "p":
                    if ($value == "•") {
                        continue;
                    }
                    $blob .= $value . "\n\n";
                    break;
            }
        }

        // Split up sections, and drop the first (which is all filler)
        $sections = explode($separator, $blob);
        array_shift($sections);

        // Parse ingredients
        while (count($sections) >= 2) {
            $value = array_shift($sections);
            $value = trim($value);
            $parts = explode("\n\n", $value);
            if (count($parts) > 1) {
                $value = RecipeParser_Text::formatSectionName(array_shift($parts));
                $recipe->addIngredientsSection($value);
            }
            // Split ingredients
            $parts = explode("\n", $parts[0]);
            foreach ($parts as $value) {
                $value = RecipeParser_Text::formatAsOneLine($value);
                $recipe->appendIngredient($value);
            }
        }

        // Parse instructions
        $value = array_shift($sections);
        RecipeParser_Text::parseInstructionsFromBlob($value, $recipe);

        // Photo
        $nodes = $xpath->query('//*[@class="post-body entry-content"]//img');
        if ($nodes->length) {
            $value = $nodes->item(0)->getAttribute("src");
            $recipe->photo_url = $value;
        }

        return $recipe;
    }

}

