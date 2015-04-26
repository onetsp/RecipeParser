<?php

class RecipeParser_Parser_Recipecom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Overrides for data that isn't captured by their implementation of Schema.org.

        // Instructions
        $recipe->resetInstructions();
        $nodes = $xpath->query('//*[@itemprop="recipeInstructions"]');
        foreach ($nodes as $node) {
            $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
            $recipe->appendInstruction($line);
        }

        return $recipe;
    }

}
