<?php

class RecipeParser_Parser_Foodnetworkcom {

    static public function parse($html, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Ingredients -- standard sections for ingredients and instructions.
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

        // Didn't find standard ingredients section?
        if (!$nodes->length) {

            // This recipe format doesn't include ingredients, and instructions are in a different location.
            // This could potentially be generalized to MicrodataSchema, but didn't look straightforward.
            $recipe->resetInstructions();
            $nodes = $xpath->query('//div[@class="col18 directions"]/p');
            foreach ($nodes as $node) {
                $line = $node->nodeValue;
                $line = RecipeParser_Text::formatAsOneLine($line);
                $recipe->appendInstruction($line);
            }

        }

        // Instructions -- Clean up instructions, delete last instruction from each section if it looks like a photo credit.
        for ($i = 0; $i < count($recipe->instructions); $i++) {
            if (count($recipe->instructions[$i]['list'])) {
                $j = count($recipe->instructions[$i]['list']) - 1;
                if (preg_match("/photographs? by/i", $recipe->instructions[$i]['list'][$j])) {
                    array_pop($recipe->instructions[$i]['list']);
                }
            }
        }

        return $recipe;
    }

}
