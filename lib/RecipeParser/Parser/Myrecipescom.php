<?php

class RecipeParser_Parser_Myrecipescom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);
        
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Title missing
        $nodes = $xpath->query('//meta[@property="og:title"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->getAttribute("content");
            $line = RecipeParser_Text::formatTitle($line);
            $recipe->title = $line;
        }

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//*[@class="field-ingredients"]');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            if (RecipeParser_Text::matchSectionName($line)) {
                $line = RecipeParser_Text::formatSectionName($line);
                $recipe->addIngredientsSection($line);
            } else {
                $recipe->appendIngredient($line);
            }
        }

        $recipe->resetInstructions();
        $nodes = $xpath->query('//*[@itemprop="recipeInstructions"]//p');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            $line = RecipeParser_Text::formatAsOneLine($line);
            $line = RecipeParser_Text::stripLeadingNumbers($line);
            $recipe->appendInstruction($line);
        }

        // Credits
        $nodes = $xpath->query('//*[@itemprop="author"]/*[@class="field-sponsor"]');
        if ($nodes->length) {
            $line = trim($nodes->item(0)->nodeValue);
            $recipe->credits = $line;
        }

        // Times
        $searches = array('prep' => 'prep: ',
                          'cook' => 'cook: ',
                          'total' => 'total: ');

        $nodes = $xpath->query('//*[@class="recipe-time-info"]');
        foreach ($nodes as $node) {
            $line = trim(strtolower($node->nodeValue));
            foreach ($searches as $key=>$value) {
                if (strpos($line, $value) === 0) {
                    $line = str_replace($value, "", $line);
                    $recipe->time[$key] = RecipeParser_Times::toMinutes($line);
                }
            }
        }

        return $recipe;
    }

}
