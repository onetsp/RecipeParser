<?php

class RecipeParser_Parser_Epicuriouscom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR EPICURIOUS.COM

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

        return $recipe;
    }

}
