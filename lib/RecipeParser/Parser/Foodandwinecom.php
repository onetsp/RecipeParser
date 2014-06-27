<?php

class RecipeParser_Parser_Foodandwinecom {

    static public function parse($html, $url) {

        $recipe = new RecipeParser_Recipe();

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Title
        $nodes = $xpath->query('//h1[@itemprop="name"]');
        if ($nodes->length) {
            $value = trim($nodes->item(0)->nodeValue);
            $recipe->title = $value;
        }

        // Times and yield
        // <meta content="PT3H30M" itemprop="totalTime">
        $nodes = $xpath->query('//meta[@itemprop="totalTime"]');
        if ($nodes->length) {
            if ($value = $nodes->item(0)->getAttribute('content')) {
                $value = RecipeParser_Text::iso8601ToMinutes($value);
                $recipe->time['total'] = $value;
            }
        }

        $nodes = $xpath->query('//*[@itemprop="recipeYield"]');
        if ($nodes->length) {
            $value = $nodes->item(0)->nodeValue;
            $recipe->yield = RecipeParser_Text::formatYield($value);
        }

        // Ingredients
        $nodes = $xpath->query('//div[@id = "ingredients"]/*');
        foreach ($nodes as $node) {

            if ($node->nodeName == 'h2') {
                $value = trim($node->nodeValue);
                $value = RecipeParser_Text::formatSectionName($value);
                if ($value != "Ingredients") {
                    $recipe->addIngredientsSection($value);
                }

            } else if ($node->nodeName == 'ol') {
                $subnodes = $xpath->query('./li/span', $node);
                foreach ($subnodes as $subnode) {
                    $value = trim($subnode->nodeValue);
                    $recipe->appendIngredient($value);
                }
            }
        }


        // Instructions
        $nodes = $xpath->query('//div[@id = "directions"]/ol/li');
        foreach ($nodes as $node) {
            $value = trim($node->nodeValue);
            $value = RecipeParser_Text::stripLeadingNumbers($value);
            
            $parts = self::splitDirections($value);
            if ($parts['section']) {
                $parts['section'] = RecipeParser_Text::formatSectionName($parts['section']);
                $recipe->addInstructionsSection($parts['section']);
            }
            $recipe->appendInstruction($parts['direction']);
        }

        // Notes
        $nodes = $xpath->query('//div[@id = "directions"]/div[@id = "endnotes"]');
        if ($nodes->length) {
            $value = trim($nodes->item(0)->nodeValue);
            $recipe->notes = $value;
        }

        // Photo
        $nodes = $xpath->query('//img[@itemprop="image"]');
        if ($nodes && $nodes->item(0)) {
            $photo_url = $nodes->item(0)->getAttribute('src');
            if (strpos($photo_url, 'default-recipe-image.gif') === false
                && strpos($photo_url, 'placeholder.gif') === false)
            {
                $recipe->photo_url = RecipeParser_Text::formatPhotoUrl($photo_url, $url);
            }
        }

        return $recipe;
    }

    static public function splitDirections($str) {
        $section = array();
        $direction = array();

        $words = explode(' ', $str);
        $done_title = false;
        foreach ($words as $word) {
            if ($done_title || $word != strtoupper($word)) {
                $done_title = true;
                $direction[] = $word;
            } else {
                $section[] = $word;
            }
        }

        return array('section' => implode(' ', $section),
                     'direction' => implode(' ', $direction));
    }

}

?>
