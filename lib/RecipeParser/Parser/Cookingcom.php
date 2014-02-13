<?php

class RecipeParser_Parser_Cookingcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataRdfDataVocabulary::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//*[@class="ingredient"]');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->appendIngredient($line);
        }

        return $recipe;
    }

}
