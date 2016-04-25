<?php

class RecipeParser_Parser_Realsimplecom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        $recipe->resetIngredients();
        $nodes = $xpath->query('//*[@class="ingredient-list"]/li');
        foreach ($nodes as $node) {
            $line = "";
            foreach ($node->childNodes as $child) {
                $str = trim($child->nodeValue);
                if (stripos($str, "check") === 0) {
                    continue;
                }
                $line .= $str . " ";
            }
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->appendIngredient($line);
        }

        return $recipe;
    }

}
