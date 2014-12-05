<?php

class RecipeParser_Parser_Myrecipescom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($html, $url);
        
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Photo URL, use larger version found on MyRecipes
        $recipe->photo_url = str_replace('-l.jpg', '-x.jpg', $recipe->photo_url);

        // Credits
        $nodes = $xpath->query('//*[@class="link-list"]/h4');
        if ($nodes->length) {
            $line = trim($nodes->item(0)->nodeValue);
            if (strpos($line, "More from") === 0) {
                $line = str_replace("More from ", "", $line);
                $recipe->credits = $line;
            }
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
