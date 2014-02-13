<?php

class RecipeParser_Parser_Seriouseatscom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_Microformat::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        $hrecipe = $xpath->query('//*[@class="hrecipe"]');
        if ($hrecipe->length) {
            $hrecipe = $hrecipe->item(0);

            // Title is not marked up with class="fn"
            $nodes = $xpath->query('.//h1', $hrecipe);
            if ($nodes->length) {
                $value = $nodes->item(0)->nodeValue;
                $recipe->title = RecipeParser_Text::formatTitle($value);
            }

            // Yield -- Class names are conflated
            $nodes = $xpath->query('.//*[@class="info yield"]', $hrecipe);
            if ($nodes->length) {
                $line = $nodes->item(0)->nodeValue;
                $recipe->yield = RecipeParser_Text::formatYield($line);
            }

            // Prep Times -- Class names are conflated
            $nodes = $xpath->query('.//*[@class="info preptime"]', $hrecipe);
            if ($nodes->length) {
                $value = $nodes->item(0)->nodeValue;
                $recipe->time['prep'] = RecipeParser_Times::toMinutes($value);
            }

            // Total Time / Duration -- Class names are conflated
            $nodes = $xpath->query('.//*[@class="info duration"]', $hrecipe);
            if ($nodes->length) {
                $value = $nodes->item(0)->nodeValue;
                $recipe->time['total'] = RecipeParser_Times::toMinutes($value);
            }
        }

        // Photo
        $nodes = $xpath->query('//section[@class="content-unit"]/img');
        if ($nodes->length) {
            $photo_url = $nodes->item(0)->getAttribute('src');
        }
        if ($photo_url) {
            $recipe->photo_url = RecipeParser_Text::formatPhotoUrl($photo_url, $url);
        }

        // Remove recipe title intros -- e.g. "Sunday Dinner: Pork Ribs" changes to "Pork Ribs"
        if (strpos($recipe->title, ": ") !== false) {
            $recipe->title = preg_replace("/^[^:]+: (.+)/", "$1", $recipe->title);
        }

        return $recipe;
    }

}

?>
