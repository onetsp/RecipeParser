<?php

class RecipeParser_Parser_12tomatoescom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        // ---- OVERRIDES

        $myxpath->singleNodeLookup('//h3//strong', null, "title", $recipe);
        $myxpath->singleNodeLookup('//meta[@property="og:image"]', "content", "photo_url", $recipe);

        // Yield
        $nodes = $xpath->query('//div[@class="post-body"]//p');
        foreach ($nodes as $node) {
            $line = trim($node->nodeValue);
            if (strpos($line, "Yield") === 0) {
                $line = RecipeParser_Text::formatYield($line);
                $recipe->yield = $line;
                break;
            }
        }

        $myxpath->simpleIngredientsListLookup('//div[@class="post-body"]//ul/li', $recipe);
        $myxpath->simpleInstructionsListLookup('//div[@class="post-body"]//ol/li', $recipe);

        return $recipe;
    }

}
