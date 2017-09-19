<?php

class RecipeParser_Parser_Bonappetitcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Fix cooking time (busted markup for "schema"
        if (!$recipe->time['total']) {
            $nodes = $xpath->query('//*[@itemprop="totalTime"]');
            foreach ($nodes as $node) {
                $line = trim($node->nodeValue);
                if (strpos($line, "TOTAL") === 0) {
                    $line = RecipeParser_Times::toMinutes($line);
                    $recipe->time['total'] = $line;
                }
            }
        }

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//div[@class="ingredientsGroup"]/*');
        foreach ($nodes as $node) {
            if ($node->nodeName == 'h3') {
                $line = RecipeParser_Text::formatSectionName($node->nodeValue);
                $recipe->addIngredientsSection($line);
                continue;
            } else if ($node->nodeName == 'ul') {
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
        $nodes = $xpath->query('//div[@class="preparation__group"]/*');
        foreach ($nodes as $node) {

            // <h3> contains section name.
            if ($node->nodeName == 'h3') {
                $line = RecipeParser_Text::formatSectionName($node->nodeValue);
                if (!empty($line)) {
                    $recipe->addInstructionsSection($line);
                }
                continue;
            }

            // Each step is in a nested p
            if ($node->nodeName == 'div') {
                $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
                if ($line) {
                    $recipe->appendInstruction($line);
                }
                continue;
            }
        }

        return $recipe;
    }

}
