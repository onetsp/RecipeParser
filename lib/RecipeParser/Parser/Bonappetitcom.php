<?php

class RecipeParser_Parser_Bonappetitcom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR BONAPPETIT.COM

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " ingredient-set ")]/*');
        foreach ($nodes as $node) {
            // <h3> contains section name.
            if ($node->nodeName == 'h3') {
                $line = RecipeParser_Text::formatSectionName($node->nodeValue);
                if ($line == "Ingredients") {
                    continue;
                }
                $recipe->addIngredientsSection($line);
                continue;
            }

            // Extract ingredients as the node value of each <ul> -> <li> elements.
            if ($node->nodeName == 'ul') {
                $ing_nodes = $node->childNodes;
                foreach ($ing_nodes as $ing_node) {
                    if ($ing_node->nodeName == 'li') {
                        $line = trim($ing_node->nodeValue);
                        $line = RecipeParser_Text::formatAsOneLine($line);
                        if ($line) {
                            $recipe->appendIngredient($line);
                        }
                    }
                }
                continue;
            }

        }

        // Instructions
        $recipe->resetInstructions();
        $nodes = $xpath->query('//div[@class="prep-steps"]/*');
        foreach ($nodes as $node) {

            // <h3> contains section name.
            if ($node->nodeName == 'h3') {
                $line = RecipeParser_Text::formatSectionName($node->nodeValue);
                if ($line == "Preparation") {
                    continue;
                }
                if (!empty($line)) {
                    $recipe->addInstructionsSection($line);
                }
                continue;
            }

            // Extract each step as the node value of <ul> -> <li> elements.
            if ($node->nodeName == 'ul') {
                $inst_nodes = $node->childNodes;
                foreach ($inst_nodes as $inst_node) {
                    if ($inst_node->nodeName == 'li') {
                        $line = trim($inst_node->nodeValue);
                        if (preg_match("/(Hungry|Thirsty) for more\?/i", $line)) {
                            continue;
                        } else if (!empty($line)) {
                            $recipe->appendInstruction($line);
                        }
                    }   
                }   
                continue;
            }
        }

        return $recipe;
    }

}
