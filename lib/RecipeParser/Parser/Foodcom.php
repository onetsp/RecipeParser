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
        if (strpos($recipe->photo_url, "FDC_Logo_vertical.png") !== false
            || strpos($recipe->photo_url, "FDC_share-logo.png") !== false) 
        {
            $recipe->photo_url = '';
        }
        if ($recipe->photo_url) {
            $recipe->photo_url = str_replace("/thumbs/", "/large/", $recipe->photo_url);
        }

        // Title
        if (!$recipe->title) {
            $node_list = $xpath->query('//header//h1');
            if ($node_list->length) {
                $value = RecipeParser_Text::formatTitle($node_list->item(0)->nodeValue);
                $recipe->title = $value;
            }
        }

        // Times
        $searches = array('prep-time' => 'prep',
                          'cook-time' => 'cook',
                          'total-time' => 'total');
        foreach ($searches as $class_name => $time_key) {
            $nodes = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " ' . $class_name . ' ")]');


            if ($nodes->length) {
                $value = RecipeParser_Text::formatAsOneLine($nodes->item(0)->nodeValue);
                $value = trim(preg_replace("/(COOK|PREP|READY IN)/", "", $value));
                $value = RecipeParser_Times::toMinutes($value);
                if ($value) {
                    $recipe->time[$time_key] = $value;
                }
            }
        }

        // Yield
        $yield = '';
        $nodes = $xpath->query('//*[@class="yield"]');

        // Find as 'yield'
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $line = RecipeParser_Text::formatYield($line);
            $recipe->yield = $line;

        // Or as number of 'servings'
        } else {
            $nodes = $xpath->query('//*[@class="servings"]//*[@class="value"]');
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $line = RecipeParser_Text::formatYield($line);
                $recipe->yield = $line;
            }
        }

        // Ingredients
        if (!count($recipe->ingredients[0]["list"])) {
            $node_list = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " ingredients ")]//ul/li');
            foreach ($node_list as $node) {
                $line = trim(strip_tags($node->nodeValue));
                if ($node->firstChild->tagName === 'h4') {
                    $recipe->addIngredientsSection(ucfirst(strtolower($node->firstChild->nodeValue)));
                } else if ($line) {
                    $recipe->appendIngredient($line);
                }
            }
        }

        // Instructions
        if (!count($recipe->instructions[0]["list"])) {
            $nodes = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " directions ")]//ol/li');
            foreach ($nodes as $node) {
                $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
                if (preg_match("/^(.+):$/", $line, $m)) {
                    $recipe->addInstructionsSection(ucfirst(strtolower($m[1])));
                } else if ($line && $line != 'Submit a Correction') {
                    $recipe->appendInstruction($line);
                }
            }
        }

        // Source
        if (!$recipe->source) {
            $recipe->source = "Food.com";
        }

        // Categories
        if (!count($recipe->categories)) {
            $nodes = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " related-recipes ")]//dd/a/span');
            foreach ($nodes as $node) {
                $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
                $recipe->appendCategory($line);
            }
        }

        // Photo
        if (!$recipe->photo_url) {
            $nodes = $xpath->query('//li[@data-slide-position="1"]//div/div/img');
            if ($nodes->length) {
                $photo_url = $nodes->item(0)->getAttribute('data-src');
                $photo_url = str_replace('-articleInline.jpg', '-popup.jpg', $photo_url);
                $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
            }
        }

        // Photo Backup
        if (!$recipe->photo_url) {
            $nodes = $xpath->query('//div[@class="slideshow_content"]//div/div/img');
            if ($nodes->length) {
                $photo_url = $nodes->item(0)->getAttribute('data-src');
                $photo_url = str_replace('-articleInline.jpg', '-popup.jpg', $photo_url);
                $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
            }
        }

        // Credit
        if (!$recipe->credits) {
            $nodes = $xpath->query('//header//section//span');
            if ($nodes->length) {
                $recipe->credits = $nodes->item(1)->textContent;
            }
        }

        return $recipe;
    }

}
