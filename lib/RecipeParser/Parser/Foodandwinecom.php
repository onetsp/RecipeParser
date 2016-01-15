<?php

class RecipeParser_Parser_Foodandwinecom {

    static public function parse(DOMDocument $doc, $url) {
        $recipe = new RecipeParser_Recipe();
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR FOODANDWINE.COM

        // Title
        $nodes = $xpath->query('//h1[@itemprop="name"]');
        if ($nodes->length) {
            $value = trim($nodes->item(0)->nodeValue);
            $recipe->title = $value;
        }

        // Times and yield
        // <time datetime="PT35M" itemprop="prepTime">
        $nodes = $xpath->query('//time[@itemprop="prepTime"]');
        if ($nodes->length) {
            if ($value = $nodes->item(0)->textContent) {
                $value = RecipeParser_Text::mixedTimeToMinutes($value);
                $recipe->time['total'] = $value;
            }
        }

        $nodes = $xpath->query('//*[@itemprop="recipeYield"]');
        if ($nodes->length) {
            $value = $nodes->item(0)->nodeValue;
            $recipe->yield = RecipeParser_Text::formatYield($value);
        }

        // Ingredients
        $nodes = $xpath->query('//*[@itemprop="ingredients"]');
        foreach ($nodes as $node) {
            $value = trim($node->nodeValue);
            if ($value != "Ingredients") {
                $recipe->appendIngredient($value);
            }
        }


        // Instructions
        $nodes = $xpath->query('//span[@class = "steps-list__item__text"]');
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
        $nodes = $xpath->query('//div[@class = "recipe-notes__content"]/div/p');
        $notes = array();
        if ($nodes->length) {
            foreach ($nodes as $node) {
                $value = trim($node->nodeValue);
                array_push($notes, $value);
            }
            $recipe->notes = implode(' | ', $notes);
        }

        // Photo
        $nodes = $xpath->query('//img[@class = "recipe-carousel__recipe__img"]');
        if ($nodes && $nodes->item(1)) {
            $photo_url = $nodes->item(1)->getAttribute('src');
            if (strpos($photo_url, 'default-recipe-image.gif') === false
                && strpos($photo_url, 'placeholder.gif') === false)
            {
                $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
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
