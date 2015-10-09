<?php

class RecipeParser_Parser_Cookscom {

    static public function parse(DOMDocument $doc, $url) {
        $recipe = new RecipeParser_Recipe();
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR COOKS.COM

        // Title
        $node_list = $doc->getElementsByTagName('title');
        if ($node_list->length) {
            $value = $node_list->item(0)->nodeValue;
            $value = trim(str_replace("Cooks.com - Recipe - ", "", $value));
            $value = trim(str_replace(" - Recipe - Cooks.com", "", $value));
            $recipe->title = $value;
        }


        // This node contains all ingredients, section titles, and instructions
        $node_list = $xpath->query('//table[@class="hrecipe"]//td/div');
        foreach ($node_list as $node) {

            // Can determine each piece of content by the "style" attributes.
            $style = $node->getAttribute("style");

            // Ingredients found in a div, black text
            if (stripos($style, "color: BLACK;") !== false) {
                $ing_nodes = $xpath->query('./span[@class = "ingredient"]', $node);
                foreach ($ing_nodes as $ing_node) {
                    $recipe->appendIngredient($ing_node->nodeValue);
                }

            // Instructions node
            } else if ($node->getAttribute('class') == "instructions") {
                foreach ($node->childNodes as $child) {
                    $line = $child->nodeValue;
                    $line = RecipeParser_Text::formatAsOneLine($line);
                    $recipe->appendInstruction($line);
                }

            // Section title
            } else if ($node->getAttribute("class") == "section") {
                $title = RecipeParser_Text::formatSectionName($node->nodeValue);
                $recipe->addIngredientsSection($title);
                if (count($recipe->instructions) > 0) {
                    $recipe->addInstructionsSection($title);
                }
            }

        }

        return $recipe;
    }

}

?>
