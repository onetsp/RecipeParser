<?php

class RecipeParser_Parser_Elanaspantrycom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microformat stuff we can find.
        $recipe = RecipeParser_Parser_Microformat::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR ELANASPANTRY.COM

        if (!$recipe->title) {
            $nodes = $xpath->query('//div[@class="box"]/strong');
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $line = RecipeParser_Text::formatTitle($line);
                $recipe->title = $line;
            }
        }

        if (!$recipe->yield) {
            $nodes = $xpath->query('//div[@class="box"]/div');
            foreach ($nodes as $node) {
                $line = trim($node->nodeValue);
                if (stripos($line, "makes") === 0) {
                    $line = RecipeParser_Text::formatYield($line);
                    $recipe->yield = $line;
                    break;
                }
            }
        }

        if (!count($recipe->ingredients[0]["list"])) {
            $nodes = $xpath->query('//div[@class="box"]');
            if ($nodes->length) {
                $nodes = $nodes->item(0)->childNodes;

                $str = "";
                foreach ($nodes as $node) {
                    if (in_array($node->nodeName, array("#text", "a", "br"))) {
                        if ($node->nodeName == "br") {
                            $str .= "<br>";
                        } else {
                            $line = $node->nodeValue;
                            $str .= $line;
                        }
                    }
                }
                $lines = explode("<br>", $str);
                foreach ($lines as $line) {
                    $line = RecipeParser_Text::formatAsOneLine($line);
                    $recipe->appendIngredient($line);
                }
            }
        }

        if (!count($recipe->instructions[0]["list"])) {
            $nodes = $xpath->query('//div[@class="box"]/ol/li');
            foreach ($nodes as $node) {
                $line = $node->nodeValue;
                $line = RecipeParser_Text::formatAsOneLine($line);
                $recipe->appendInstruction($line);
            }
        }

        if (!$recipe->photo_url) {
            $nodes = $xpath->query('//meta[@property="og:image"]');
            foreach ($nodes as $node) {
                $line = $node->getAttribute("content");
                if (strpos($line, "wp-content") !== false) {
                    $recipe->photo_url = $line;
                    break;
                }
            }
        }

        return $recipe;
    }

}
