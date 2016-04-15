<?php

class RecipeParser_Parser_Epicuriouscom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);


        // OVERRIDES for epicurious

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//li[@class="ingredient-group"]');
        foreach ($nodes as $node) {
            $subs = $node->childNodes;
            foreach ($subs as $sub) {

                // <strong> contains ingredient section names
                if ($sub->nodeName == 'strong') {
                    $line = RecipeParser_Text::formatSectionName($sub->nodeValue);
                    $recipe->addIngredientsSection($line);
                    continue;
                }

                // Extract ingredients from inside of <ul class="ingredients">
                if ($sub->nodeName == 'ul') {
                    // Child nodes should all be <li>
                    $ing_nodes = $sub->childNodes;
                    foreach ($ing_nodes as $ing_node) {
                        if ($ing_node->nodeName == 'li') {
                            $line = trim($ing_node->nodeValue);
                            $recipe->appendIngredient($line);
                        }
                    }
                }
            }
        }

        // Instructions
        $recipe->resetInstructions();
        $nodes = $xpath->query('//li[@class="preparation-group"]');
        foreach ($nodes as $node) {
            $subs = $node->childNodes;
            foreach ($subs as $sub) {

                // <strong> contains section names
                if ($sub->nodeName == 'strong') {
                    $line = RecipeParser_Text::formatSectionName($sub->nodeValue);
                    $recipe->addInstructionsSection($line);
                    continue;
                }

                // Extract from inside of <ul class="preparation">
                if ($sub->nodeName == 'ol') {
                    // Child nodes should all be <li>
                    $prep_nodes = $sub->childNodes;
                    foreach ($prep_nodes as $prep_node) {
                        if ($prep_node->nodeName == 'li') {
                            $line = trim($prep_node->nodeValue);
                            $recipe->appendInstruction($line);
                        }
                    }
                }

            }
        }

        // Times
        $nodes = $xpath->query('//dd[@class="active-time"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Times::toMinutes($line);
            $recipe->time["prep"] = $line;
        }
        $nodes = $xpath->query('//dd[@class="total-time"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Times::toMinutes($line);
            $recipe->time["total"] = $line;
        }

        return $recipe;
    }

}
