<?php

class RecipeParser_Parser_Pillsburycom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR PILLSBURY.COM

        // Times
        $nodes = $xpath->query('//*[@class="recipePartAttributes recipePartPrimaryAttributes"]//li');
        if ($nodes->length) {
            foreach ($nodes as $node) {
                if (trim($node->childNodes->item(1)->nodeValue) == "Prep Time") {
                    $line = trim($node->childNodes->item(3)->nodeValue);
                    $recipe->time['prep'] = RecipeParser_Times::toMinutes($line);
                    continue;
                }
                if (trim($node->childNodes->item(1)->nodeValue) == "Total Time") {
                    $line = trim($node->childNodes->item(3)->nodeValue);
                    $recipe->time['total'] = RecipeParser_Times::toMinutes($line);
                    continue;
                }
            }
        }

        // Yield
        $nodes = $xpath->query('//*[@class="recipePartAttributes recipePartSecondaryAttributes"]//li');
        if ($nodes->length) {
            foreach ($nodes as $node) {
                if (trim($node->childNodes->item(1)->nodeValue) == "Servings") {
                    $line = trim($node->childNodes->item(3)->nodeValue);
                    $recipe->yield = RecipeParser_Text::formatYield($line);
                }
            }
        }

        // Ingredients
        $recipe->resetIngredients();           

        $groups = $xpath->query('//*[@class="recipePartIngredientGroup"]');
        foreach ($groups as $group) {
            $nodes = $xpath->query('.//h2', $group);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $line = RecipeParser_Text::formatSectionName($line);
                $recipe->addIngredientsSection($line);
            }

            $nodes = $xpath->query('.//*[@itemprop="ingredients"]', $group);
            foreach ($nodes as $node) {
                $line = $node->nodeValue;
                $line = RecipeParser_Text::formatAsOneLine($line);
                $recipe->appendIngredient($line);
            }

        }

        // Notes / footnotes
        $notes = array();
        $nodes = $xpath->query('//div[@class="recipePartTipsInfo"]');
        foreach ($nodes as $node) {
            $line = trim($node->nodeValue);
            $notes[] = $line;
        }
        $recipe->notes = implode("\n\n", $notes);
        $recipe->notes = RecipeParser_Text::formatAsParagraphs($recipe->notes);

        // Fix description
        $recipe->description = trim(preg_replace("/Servings \# \d+/", "", $recipe->description));

        return $recipe;
    }

}

?>
