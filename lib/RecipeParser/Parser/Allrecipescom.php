<?php

class RecipeParser_Parser_Allrecipescom {

    static public function parse($html, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // --- Allrecipes allows for custom recipes that use a different
        // --- template than their standard content. This template is not currently
        // --- using schema.org/Recipe. So we'll look for fields that need to be
        // --- overridden.

        // Title
        if (!$recipe->title) {
            $node_list = $xpath->query('//h1[@itemprop="name"]');
            if ($node_list->length) {
                $value = RecipeParser_Text::formatTitle($node_list->item(0)->nodeValue);
                $recipe->title = $value;
            }
        }

        // Yield
        if (!$recipe->yield) {
            $node_list = $xpath->query('//div[@class = "servings-form"]//span[@class = "yield yieldform"]');
            if ($node_list->length) {
                $value = $node_list->item(0)->nodeValue;
                $recipe->yield = $value;
            }
        }

        // Times
        $searches = array('liPrep' => 'prep',
                          'liCook' => 'cook',
                          'liTotal' => 'total');
        foreach ($searches as $id_name => $time_key) {
            $nodes = $xpath->query('.//*[@id="' . $id_name . '"]');
            if ($nodes->length) {
                $value = RecipeParser_Text::formatAsOneLine($nodes->item(0)->nodeValue);
                $value = trim(preg_replace("/(COOK|PREP|READY IN)/", "", $value));
                $value = RecipeParser_Times::toMinutes($value);
                if ($value) {
                    $recipe->time[$time_key] = $value;
                }
            }
        }

        // Ingredients
        if (!count($recipe->ingredients[0]["list"])) {
            $node_list = $xpath->query('//li[contains(concat(" ", normalize-space(@class), " "), " ingredient ")]');
            foreach ($node_list as $node) {
                $line = trim(strip_tags($node->nodeValue));
                if (preg_match("/^(.+):$/", $line, $m)) {
                    $recipe->addIngredientsSection(ucfirst(strtolower($m[1])));
                } else if ($line) {
                    $recipe->appendIngredient($line);
                }
            }
        }

        // Instructions
        if (!count($recipe->instructions[0]["list"])) {
            $nodes = $xpath->query('//div[@class="directions"]//ol/li');
            foreach ($nodes as $node) {
                $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
                if (preg_match("/^(.+):$/", $line, $m)) {
                    $recipe->addInstructionsSection(ucfirst(strtolower($m[1])));
                } else if ($line) {
                    $recipe->appendInstruction($line);
                }
            }
        }

        // Look for useless line at end of instructions
        $i = count($recipe->instructions) - 1;
        $j = count($recipe->instructions[$i]['list']) - 1;
        if ($j >= 0 && strpos($recipe->instructions[$i]['list'][$j], "All done!") === 0) {
            unset($recipe->instructions[$i]['list'][$j]);
        }

        // Photo URL
        // Get larger images
        if ($recipe->photo_url) {
            $recipe->photo_url = str_replace('/userphoto/small/', '/userphoto/big/', $recipe->photo_url);
            $recipe->photo_url = str_replace('/userphotos/140x140/', '/userphotos/250x250/', $recipe->photo_url);
        }

        return $recipe;
    }

}
