<?php

class RecipeParser_Parser_Food52com {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR FOOD52.COM

        // Credits
        if ($recipe->credits) {
            $recipe->credits = "Food52 (" . $recipe->credits . ")";
        } else {
            $recipe->credits = "Food52";
        }

        // Notes
        $line = "";
        $nodes = $xpath->query('.//span[@class="recipe-note"]');
        if ($nodes->length) {
            $nodes = $nodes->item(0)->childNodes;  // go through 'childNodes' to get #text nodes

            foreach ($nodes as $node) {
                switch ($node->nodeName) {
                    case "br":
                        $line .= "\n";
                        break;

                    case "#text":
                    case "span":
                    case "strong":
                    case "b":
                    case "em":
                    case "i":
                    case "a":
                        $line .= $node->nodeValue . " ";
                        break;
                }
            }
        }
        $line = preg_replace("/^Author Notes:\s*/", "", $line);
        $recipe->notes = RecipeParser_Text::formatAsParagraphs($line);

        return $recipe;
    }

}
