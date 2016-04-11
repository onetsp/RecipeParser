<?php

class RecipeParser_Parser_Saveurcom {

    static public function parse($html, $url) {
        $recipe = new RecipeParser_Recipe();
        
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Title
        $nodes = $xpath->query('//h1');
        if ($nodes->length) {
            $str = $nodes->item(0)->nodeValue;
            $recipe->title = RecipeParser_Text::formatTitle($str);
        }

        // Yield
        $nodes = $xpath->query('//span[@property="recipeYield"]');
        if ($nodes->length) {
            $str = $nodes->item(0)->nodeValue;
            $recipe->yield = RecipeParser_Text::formatYield($str);
        }

        // Image
        $photo_url = "";
        if (!$photo_url) {
            // try to find open graph url
            $nodes = $xpath->query('//meta[@property="og:image"]');
            if ($nodes->length) {
                $photo_url = $nodes->item(0)->getAttribute('content');
            }
        }
        $recipe->photo_url = $photo_url;

        // Ingredients
        $nodes = $xpath->query('//*[@property="ingredients"]');
        foreach ($nodes as $node) {
            $str = $node->nodeValue;
            $str = RecipeParser_Text::formatAsOneLine($str);
            $recipe->appendIngredient($str);
        }

        // Instructions
        $nodes = $xpath->query('//*[@property="recipeInstructions"]');
        foreach ($nodes as $node) {
            $str = $node->nodeValue;
            $str = RecipeParser_Text::formatAsOneLine($str);
            $recipe->appendInstruction($str);
        }

        // Description
        $str = "";
        $nodes = $xpath->query('//*[@class="field-body"]//p');
        foreach ($nodes as $node) {
            if ($str) {
                $str += "\n\n";
            }
            $str = $node->nodeValue;
            $str = RecipeParser_Text::formatAsOneLine($str);
        }
        $recipe->description = $str;

        return $recipe;
    }

}
