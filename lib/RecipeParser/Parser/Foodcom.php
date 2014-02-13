<?php

class RecipeParser_Parser_Foodcom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Photo -- skip logo if it was used in place of photo
        if (strpos($recipe->photo_url, "FDC_Logo_vertical.png") !== false) {
            $recipe->photo_url = '';
        }
        if ($recipe->photo_url) {
            $recipe->photo_url = str_replace("/thumbs/", "/large/", $recipe->photo_url);
        }

        // Yield
        $yield = '';
        $nodes = $xpath->query('//option[@class="select-title"]');
        if ($nodes->length) {
            $yield .= trim($nodes->item(0)->nodeValue);
        }
        $nodes = $xpath->query('//p[@class="yieldUnits-txt"]');
        if ($nodes->length) {
            $value = trim($nodes->item(0)->nodeValue);
            $yield .= ' ' . (($value) ? $value : 'servings');
        }
        $recipe->yield = trim($yield);


        // Ingredients (custom because of duplicate class attributes for "ingredients")
        $recipe->resetIngredients();

        $nodes = $xpath->query('//div[@class = "pod ingredients"]/*');
        foreach ($nodes as $node) {
            # <h3> contains ingredient section names
            if ($node->nodeName == 'h3') {
                $recipe->addIngredientsSection(ucfirst(trim(strtolower($node->nodeValue))));
            }
            # Extract ingredients from <ul> <li>.
            if ($node->nodeName == 'ul') {
                $ing_nodes = $node->childNodes;
                foreach ($ing_nodes as $ing_node) {
                    // Find <li> with class="ingredient" for each ingredient.
                    if ($ing_node->nodeName == 'li') {
                        $line = RecipeParser_Text::FormatAsOneLine($ing_node->nodeValue);
                        $recipe->appendIngredient($line);
                    }
                }
            }
        }

        return $recipe;
    }

}
