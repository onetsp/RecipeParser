<?php

class RecipeParser_Parser_Chowcom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR CHOW.COM

        // Titles include "recipe"
        if (preg_match("/ Recipe( - CHOW.com)?$/", $recipe->title)) {
            $recipe->title = trim(preg_replace("/(.*) Recipe( - CHOW.com)?$/", "$1", $recipe->title));
        }

        // Strip leading numbers from instructions
        for ($i = 0; $i < count($recipe->instructions); $i++) {
            for ($j = 0; $j < count($recipe->instructions[$i]['list']); $j++) {
                $recipe->instructions[$i]['list'][$j] = preg_replace("/^\d+(\w.*)$/", "$1", $recipe->instructions[$i]['list'][$j]);
            }
        }

        // Ingredients (If none parsed)
        if (!count($recipe->ingredients[0]['list'])) {
            $nodes = $xpath->query('//*[@id="ingredients_list"]//li');
            foreach ($nodes as $node) {
                $line = $node->nodeValue;
                $line = RecipeParser_Text::formatAsOneLine($line);
                $recipe->appendIngredient($line);
            }
        }

        // Instructions (If none parsed)
        if (!count($recipe->instructions[0]['list'])) {
            $nodes = $xpath->query('//*[@itemprop="recipeInstructions"]');
            foreach ($nodes as $node) {
                $line = $node->nodeValue;
                $line = RecipeParser_Text::formatAsOneLine($line);
                $recipe->appendInstruction($line);
            }
        }

        // Cleanup description
        if ($recipe->description) {
            $recipe->description = preg_replace("/^(Read our review of|This (dish|recipe) was featured as part|See more recipes) .*$/m", "", $recipe->description);
            $recipe->description = preg_replace("/[\r\n]{3,}/", "\n\n", $recipe->description);
            $recipe->description = trim($recipe->description);
        }

        return $recipe;
    }

}
