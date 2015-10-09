<?php

class RecipeParser_Parser_Bhgcom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR BHG.COM

        // Notes -- Collect the non-standard cook times and baking temps,
        // and also any tips/notes that appear at the end of the recipe instructions.
        $notes = array();

        $nodes = $xpath->query('//*[@class="recipeTips"]//li');
        foreach ($nodes as $node) {
            $value = RecipeParser_Text::FormatAsOneLine($node->nodeValue);
            $value = preg_replace("/^(Tip|Note)\s*(.*)$/", "$2", $value);
            $notes[] = $value;
        }

        $nodes = $xpath->query('//*[@class="recipeInfo"]//*[@class="type"]');
        foreach ($nodes as $node) {
            $value = RecipeParser_Text::formatAsOneLine($node->nodeValue);
            if (strpos($value, "Makes:") !== false) {
                continue;
            }
            $notes[] = $value;
        }
        
        $recipe->notes = implode("\n\n", $notes);

        // Adjust Photo URL for larger dimensions
        $recipe->photo_url = preg_replace("/\/l_([^\/]+)/", "/550_$1", $recipe->photo_url);

        return $recipe;
    }

}
