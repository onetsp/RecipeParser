<?php

class RecipeParser_Parser_Simplyrecipescom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//div[@id = "recipe-ingredients"]/*');
        foreach ($nodes as $node) {

            if ($node->nodeName == 'p') {
                $value = trim($node->nodeValue);

                // Older recipes will have ingredients jumbled into a single <p>
                // rather than using 'ingredients' classes. If the node value looks
                // like multiple lines, treat it like a section header followed by
                // section ingredients.
                $lines = explode("\n", $value);
                if (count($lines) > 1) {
                    for ($i = 0; $i < count($lines); $i++) {
                        $line = trim($lines[$i]);
                        if ($i == 0) {
                            $line = RecipeParser_Text::formatSectionName($line);
                            $recipe->addIngredientsSection($line);
                        } else {
                            $line = trim($line);
                            $recipe->appendIngredient($line);
                        }
                    }

                // Otherwise, we're dealing with a normal section for hrecipe, and 
                // ingredients for the section will follow as <ul> elements.
                } else {
                    $value = RecipeParser_Text::formatSectionName($value);
                    $recipe->addIngredientsSection($value);
                }
            } else if ($node->nodeName == 'ul') {
                $subnodes = $xpath->query('./li[@class = "ingredient"]', $node);
                foreach ($subnodes as $subnode) {
                    $value = trim($subnode->nodeValue);
                    $recipe->appendIngredient($value);
                }
            }
        }

        // Notes
        $nodes = $xpath->query('//*[@id="recipe-intronote"]');
        if ($nodes->length) {
            $value = $nodes->item(0)->nodeValue;
            $recipe->notes = RecipeParser_Text::formatAsParagraphs($value);
        }

        // Photo URL to replace og:image 
        $nodes = $xpath->query('//img[@itemprop="image"]');
        if ($nodes->length) {
            $photo_url = $nodes->item(0)->getAttribute("src");
            $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
        }

        return $recipe;
    }

}
