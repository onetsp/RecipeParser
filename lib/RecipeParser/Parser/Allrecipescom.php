<?php

class RecipeParser_Parser_Allrecipescom {

    public function parse($html, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Instructions -- This section is mis-named (should be itemprop=recipeInstructions)
        $node_list = $xpath->query('//div[@class = "directions"]//ol/li');
        foreach ($node_list as $node) {
            $line = trim(strip_tags($node->nodeValue));
            $recipe->appendInstruction($line);
        }

        // --- Allrecipes allows for custom recipes that use a different
        // --- template than their standard content. This template is not currently
        // --- using schema.org/Recipe. So we'll look for fields that need to be
        // --- overridden.

        // Title
        if (!$recipe->title) {
            $node_list = $xpath->query('//h1[@id = "itemTitle"]');
            if ($node_list->length) {
                $value = $node_list->item(0)->nodeValue;
                $value = trim($value);
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

        // Cook times
        if (!$recipe->time['prep']) {
            $node_list = $xpath->query('//h5[@id = "ctl00_CenterColumnPlaceHolder_recipe_h5Prep"]/span[last()]');
            if ($node_list->length) {
                $value = $node_list->item(0)->nodeValue;
                $recipe->time['prep'] = Times::toMinutes($value);
            }
        }
        if (!$recipe->time['cook']) {
            $node_list = $xpath->query('//h5[@id = "ctl00_CenterColumnPlaceHolder_recipe_h5Cook"]/span[last()]');
            if ($node_list->length) {
                $value = $node_list->item(0)->nodeValue;
                $recipe->time['cook'] = Times::toMinutes($value);
            }
        }
        if (!$recipe->time['total']) {
            $node_list = $xpath->query('//h5[@id = "ctl00_CenterColumnPlaceHolder_recipe_h5Ready"]/span[last()]');
            if ($node_list->length) {
                $value = $node_list->item(0)->nodeValue;
                $recipe->time['total'] = Times::toMinutes($value);
            }
        }

        // Ingredients
        if (!count($recipe->ingredients)) {
            $node_list = $xpath->query('//div[@class = "ingredients"]/ul/li');
            foreach ($node_list as $node) {
                $line = trim(strip_tags($node->nodeValue));
                if (preg_match("/^(.+):$/", $line, $m)) {
                    $recipe->addIngredientsSection(ucfirst(strtolower($m[1])));
                } else if ($line) {
                    $recipe->appendIngredient($line);
                }
            }
        }

        // Photo URL
        if (!$recipe->photo_url) {
            $nodes = $xpath->query('//img[@class = "rec-image photo"]');
            if ($nodes->length) {
                $url = $nodes->item(0)->getAttribute('src');
                $url = str_replace('/userphoto/small/', '/userphoto/big/', $url);
                $url = str_replace('/userphotos/140x140/', '/userphotos/250x250/', $url);
                $recipe->photo_url = $url;
            }
        }

        return $recipe;
    }

}

?>
