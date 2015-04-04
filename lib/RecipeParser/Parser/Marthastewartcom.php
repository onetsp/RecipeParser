<?php

class RecipeParser_Parser_Marthastewartcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Yield
        $nodes = $xpath->query('//li[@class="credit"]');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            if (stripos($line, "servings") !== false) {
                $line = preg_replace("/servings\:?.*(\d+)/i", "$1", $line);
                $line = RecipeParser_Text::formatYield($line);
                $recipe->yield = $line;
            }
        }

        // Description
        $nodes = $xpath->query('//*[@itemprop="page-dek"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $recipe->description = $line;
        }

        // Notes
        $line = "";
        $nodes = $xpath->query('//*[@class="note-text"]');
        foreach ($nodes as $node) {
            $line .= trim($node->nodeValue) . "\n\n";
        }
        $line = rtrim($line);
        $recipe->notes = $line;

        // Ingredients
        $recipe->resetIngredients();
        $sections = $xpath->query('//*[@class="components-group"]');
        if ($sections->length) {

            // Sections
            foreach ($sections as $section_node) {
                $section_nodes = $xpath->query('.//*[@class="components-group-header"]', $section_node);
                if ($section_nodes->length) {
                    $line = $section_nodes->item(0)->nodeValue;
                    $line = RecipeParser_Text::formatSectionName($line);
                    if (!empty($line)) {
                        $recipe->addIngredientsSection($line);
                    }
                }
                $ing_nodes = $xpath->query('.//*[@class="components-item"]', $section_node); 
                if ($ing_nodes->length) {
                    foreach ($ing_nodes as $node) {
                        $line = $node->nodeValue;
                        $line = RecipeParser_Text::formatAsOneLine($line);
                        $recipe->appendIngredient($line);
                    }
                }
            }
        }

        // Instructions
        $recipe->resetInstructions();
        $nodes = $xpath->query('//*[@class="directions-item"]');
        foreach ($nodes as $node) {
            $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
            $recipe->appendInstruction($line);
        }

        // Photo URL
        $nodes = $xpath->query('//img[@itemprop="image"]');
        if ($nodes->length) {
            $photo_url = $nodes->item(0)->getAttribute("data-original");
            $recipe->photo_url = RecipeParser_Text::formatPhotoUrl($photo_url, $url);
        }

        return $recipe;
    }

}

