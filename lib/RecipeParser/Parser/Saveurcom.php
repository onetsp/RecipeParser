<?php

class RecipeParser_Parser_Saveurcom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR SAVEUR.COM

        // Yield, Ingredients, Instructions
        $found_instructions = false;
        $found_ingredients = false;
        $nodes = $xpath->query('//*[@class="field field-name-body field-type-text-with-summary field-label-hidden"]//*[@class="field-item even"]');
        if ($nodes->length) {
            foreach ($nodes->item(0)->childNodes as $node) {
                $str = trim($node->nodeValue);

                // Yield
                if (!$recipe->yield && preg_match("/(makes|yields|serves|servings)/i", $str) && preg_match("/\d/", $str)) {
                    $recipe->yield = RecipeParser_Text::formatYield($str);
                    continue;
                }

                // Ingredients and Instructions
                if ($str == "INGREDIENTS") {
                    $found_ingredients = true;
                    continue;
                }
                if ($str == "INSTRUCTIONS") {
                    $found_instructions = true;
                    continue;
                }
                if (!$found_ingredients) {
                    continue;
                } else if (!$found_instructions) {
                    $str = RecipeParser_Text::formatAsOneLine($str);
                    $recipe->appendIngredient($str);
                } else {
                    $str = RecipeParser_Text::formatAsOneLine($str);
                    $str = RecipeParser_Text::stripLeadingNumbers($str);
                    $recipe->appendInstruction($str);
                }

            }
        }

        return $recipe;
    }

}
