<?php

class RecipeParser_Parser_Cookingnytimescom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Ingredients
        $nodes = $xpath->query('//*[@class="recipe-ingredients-wrap"]/*');
        foreach ($nodes as $node) {
            if ($node->nodeName == "h4") {
                $value = trim($node->nodeValue);
                $value = RecipeParser_Text::formatSectionName($value);
                $recipe->addIngredientsSection($value);
            } else if ($node->nodeName == "ul") {
                foreach ($node->childNodes as $child) {
                    $value = RecipeParser_Text::formatAsOneLine($child->nodeValue);
                    if (stripos($value, "Nutritional Information") === false) {
                        $recipe->appendIngredient($value);
                    }
                }
            }
        }

        // Notes
        if (!$recipe->notes) {
            $nodes = $xpath->query('//*[@class="recipe-note-description"]');
            if ($nodes->length) {
                $value = trim($nodes->item(0)->nodeValue);
                $value = preg_replace("/^Notes?:?\s*/i", '', $value);
                $recipe->notes = trim($value);
            }
        }

        return $recipe;
    }

}
