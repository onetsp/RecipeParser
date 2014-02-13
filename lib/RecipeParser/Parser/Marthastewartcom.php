<?php

class RecipeParser_Parser_Marthastewartcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Ingredients
        $recipe->resetIngredients();
        $sections = $xpath->query('//*[@id="ingredients"]//*[@class="group"]');
        if ($sections->length) {

            // Sections
            foreach ($sections as $section_node) {
                $section_nodes = $xpath->query('.//h3', $section_node);
                if ($section_nodes->length) {
                    $line = $section_nodes->item(0)->nodeValue;
                    $line = RecipeParser_Text::formatSectionName($line);
                    if (!empty($line)) {
                        $recipe->addIngredientsSection($line);
                    }
                }
                $ing_nodes = $xpath->query('.//li', $section_node); 
                if ($ing_nodes->length) {
                    foreach ($ing_nodes as $node) {
                        $line = $node->nodeValue;
                        $line = RecipeParser_Text::formatAsOneLine($line);
                        $recipe->appendIngredient($line);
                    }
                }
            }
        }

        // Notes
        $nodes = $xpath->query('.//*[@class = "body-c note-text"]');
        if ($nodes->length) {
            $value = $nodes->item(0)->nodeValue;
            $value = trim(str_replace("Cook's Note", '', $value));
            $recipe->notes = $value;
        }

        return $recipe;
    }

}

