<?php

class RecipeParser_Parser_Epicuriouscom {

    public function parse($html, $url) {
        $recipe = RecipeParser_Parser_Microformat::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);


        // OVERRIDES for epicurious

        // Get larger images
        if ($recipe->photo_url) {
            $recipe->photo_url = preg_replace('/(\d+)_\d+(\.jpg)$/', '$1$2', $recipe->photo_url);
        }

        // Description
        $nodes = $xpath->query('//div[@id = "recipeIntroText"]');
        if ($nodes->length) {
            $line = trim($nodes->item(0)->nodeValue);
            $recipe->description = $line;
        }

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//div[@id = "ingredients"]/*');
        foreach ($nodes as $node) {

            // <strong> contains ingredient section names
            if ($node->nodeName == 'strong') {
                $line = RecipeParser_Text::formatSectionName($node->nodeValue);
                $recipe->addIngredientsSection($line);
                continue;
            }

            // Extract ingredients from inside of <ul class="ingredientsList">
            if ($node->nodeName == 'ul') {
                // Child nodes should all be <li>
                $ing_nodes = $node->childNodes;
                foreach ($ing_nodes as $ing_node) {
                    if ($ing_node->nodeName == 'li') {
                        $line = trim($ing_node->nodeValue);
                        $recipe->appendIngredient($line);
                    }
                }
            }
        }

        // Instructions
        $recipe->resetInstructions();
        $node_list = $xpath->query('//p[@class = "instruction"]');
        foreach ($node_list as $node) {
            $line = trim($node->nodeValue);
            if (preg_match("/^(.*)\:\s*\n(.*)/", $line, $m)) {
                $name = RecipeParser_Text::formatSectionName($m[1]);
                $recipe->addInstructionsSection($name);

                $line = $m[2];
            }
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->appendInstruction($line);
        }

        return $recipe;
    }

}

?>
