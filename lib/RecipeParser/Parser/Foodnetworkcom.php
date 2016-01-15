<?php

class RecipeParser_Parser_Foodnetworkcom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR FOODNETWORK.COM

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//div[@class="col6 ingredients"]/*');
        foreach ($nodes as $node) {

            // Extract ingredients from <ul> <li>.
            if ($node->nodeName == 'ul') {
                $ing_nodes = $node->childNodes;
                foreach ($ing_nodes as $ing_node) {
                    // Find <li> with itemprop="ingredients" for each ingredient.
                    if ($ing_node->nodeName == 'li' && $ing_node->getAttribute("itemprop") == "ingredients") {

                        $line = trim($ing_node->nodeValue);
                        
                        // Section titles might be all uppercase ingredients
                        if ($line == strtoupper($line)) {
                            $line = RecipeParser_Text::formatSectionName($line);
                            $recipe->addIngredientsSection($line);
                            continue;
                        }

                        // Ingredient lines
                        if (stripos($line, "copyright") !== false) {
                            continue;
                        } else if (stripos($line, "recipe follows") !== false) {
                            continue;
                        } else {
                            $line = RecipeParser_Text::formatAsOneLine($line);
                            $recipe->appendIngredient($line);
                        }

                    // Section titles
                    } else if ($ing_node->nodeName == 'li' && $ing_node->getAttribute("class") == "subtitle") {
                        $line = trim($ing_node->nodeValue);
                        $line = RecipeParser_Text::formatSectionName($line);
                        $recipe->addIngredientsSection($line);
                    }
                }
                continue;
            }

        }

        // Instructions
        $recipe->resetInstructions();
        $nodes = $xpath->query('//*[@itemprop="recipeInstructions"]/*');
        foreach ($nodes as $node) {
            if ($node->nodeName == "span") {
                $line = RecipeParser_Text::formatSectionName($node->nodeValue);
                $recipe->addInstructionsSection($line);
            } else if ($node->nodeName == "p") {
                $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);

                if (stripos($line, "recipe courtesy") === 0) {
                    continue;
                }
                if (strtolower($line) == "from food network kitchens") {
                    continue;
                }
                if (stripos($line, "Photograph") === 0) {
                    continue;
                }
                if (preg_match("/^(Copyright )?\d{4}.*All Rights Reserved\.?$/", $line)) {
                    continue;
                }

                $recipe->appendInstruction($line);
            }

        }

        // See if we've captured a chef's photo, and delete it (if so).
        if ($recipe->photo_url) {
            $nodes = $xpath->query('//a[@itemprop="url"]/img[@itemprop="image"]');
            if ($nodes->length > 0) {
                $url = $nodes->item(0)->getAttribute("src");
                if ($recipe->photo_url == $url) {
                    $recipe->photo_url = "";
                }
            }
        }

        return $recipe;
    }

}
