<?php

class RecipeParser_Parser_Foodnetworkcom {

    public function parse($html, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//div[@class = "body-text"]/*');
        foreach ($nodes as $node) {

            // Extract ingredients from <ul> <li>.
            if ($node->nodeName == 'ul') {
                $ing_nodes = $node->childNodes;
                foreach ($ing_nodes as $ing_node) {
                    // Find <li> with itemprop="ingredients" for each ingredient.
                    if ($ing_node->nodeName == 'li' && $ing_node->getAttribute("itemprop") == "ingredients") {

                        $line = trim($ing_node->nodeValue);
                        // Lines in all caps might actually be section names.
                        if ($line == strtoupper($line)) {
                            $line = RecipeParser_Text::formatSectionName($line);
                            $recipe->addIngredientsSection($line);
                        } else if (preg_match("/^Copyright /", $line)) {
                            continue;
                        } else {
                            $line = RecipeParser_Text::formatAsOneLine($line);
                            $recipe->appendIngredient($line);
                        }

                    }
                }
                continue;
            }

            // <h3> contains ingredient section names
            if ($node->nodeName == 'h3') {
                $line = RecipeParser_Text::formatSectionName($node->nodeValue);
                $recipe->addIngredientsSection($line);
                continue;
            }
        }

        // Instructions
        $recipe->resetInstructions();
        $node_list = $xpath->query('//div[@itemprop = "recipeInstructions"]/p');
        foreach ($node_list as $node) {
            $line = trim($node->nodeValue);
            if (preg_match("/^(Photographs? by|Per serving: Calories)/", $line)) {
                continue;
            } else if (preg_match("/^(Cook's )Note:(.*)/", $line, $m)) {
                $recipe->notes .= $m[2];
            } else {
                $recipe->appendInstruction($line);
            }
        }


        // Replace photo -- Two different ways photos are marked up on Food Network.
        $photo_url = "";
        $recipe->photo_url = "";

        $nodes = $xpath->query('//*[@id="recipe-image"]');
        if ($nodes->length) {
            $photo_url = $nodes->item(0)->getAttribute('href');
        }
        if (!$photo_url) {
            $nodes = $xpath->query('//img[@id="recipe-player-th"]');
            if ($nodes->length) {
                $photo_url = $nodes->item(0)->getAttribute('src');
            }
        }
        if ($photo_url) {
            $recipe->photo_url = RecipeParser_Text::formatPhotoUrl($photo_url, $url);
            $recipe->photo_url = str_replace('_med.jpg', '_lg.jpg', $recipe->photo_url);
        }

        return $recipe;
    }

}

?>
