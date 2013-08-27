<?php

class RecipeParser_Parser_Tasteofhomecom {

    public function parse($html, $url) {
        $recipe = RecipeParser_Parser_Microformat::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // "ingredient" is mis-used in Taste of Home's HTML, and is duplicated as
        // the first ingredient.
        if (strpos($recipe->ingredients[0]['list'][0], "Ingredients ") === 0) {
            array_shift($recipe->ingredients[0]['list']);
        }

        // Try to strip editors note from the last line of the directions.
        $i = count($recipe->instructions) - 1;
        $j = count($recipe->instructions[$i]['list']) - 1;
        $parts = preg_split("/\s+Editor's Note:\s+/", $recipe->instructions[$i]['list'][$j]);
        if (count($parts) == 2) {
            $recipe->instructions[$i]['list'][$j] = $parts[0];
            $recipe->notes = $parts[1];
        }
        $recipe->instructions[$i]['list'][$j] = 
            preg_replace("/\s+Yield: .*$/", "", $recipe->instructions[$i]['list'][$j]);

        return $recipe;
    }

}

?>
