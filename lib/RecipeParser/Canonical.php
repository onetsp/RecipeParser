<?php

class RecipeParser_Canonical {

    /**
     * Inspect HTML for signs that it redirects or links to another (canonical) recipe file.
     *
     * @param string HTML
     * @param string URL of HTML page
     * @return mixed URL for redirect, or null
     */
    public static function getCanonicalUrl($html, $url) {

        // Allrecipes.com kitchenview
        if (preg_match("/allrecipes.com.+\/kitchenview.aspx$/", $url)) {
            $url = str_replace("kitchenview.aspx", "detail.aspx", $url);
            return $url;
        }

        // Allrecipes.com print versions
        if (strpos($url, "allrecipes.com/") !== false && strpos($url, "/print/") !== false) {
            return self::getUrlFromOgUrl($html, $url);
        }

        // m.allrecipes.com
        if (strpos($url, "m.allrecipes.com/recipe/") !== false) {
            return self::getUrlFromOgUrl($html, $url);
        }

        // Yummly
        if (strpos($url, "http://www.yummly.com/recipe/") !== false) {
            return self::searchYummly($html, $url);
        }

        // Foodnetwork.com print view
        if (strpos($url, "www.foodnetwork.com") !== false && strpos($url, ".print.html") !== false) {
            return str_replace(".print.html", ".html", $url);
        }

        // Epicurious print view
        if (strpos($url, "epicurious.com") !== false && strpos($url, "printerfriendly") !== false) {
            return str_replace("printerfriendly", "views", $url);
        }

        // Epicurious recipe review view (this looks like a set of tabs on the recipe page).
        if (strpos($url, "www.epicurious.com/recipes/food/reviews/") !== false) {
            return str_replace("recipes/food/reviews/", "recipes/food/views/", $url);
        }

        // Epicurious "Ingredients" features
        if (strpos($url, "epicurious.com/ingredients/") !== false) {
            // Need HTML page with <script> tags preserved!
            $html = FileUtil::downloadRecipeWithCache($url);

            $slide_number = preg_replace("/.*\/(\d+)$/", "$1", $url);
            if ($slide_number) {
                $xpath = self::getXPath($html);
                $nodes = $xpath->query('//article[@id="slide_' . $slide_number . '"]//a');
                foreach ($nodes as $node) {
                    $line = trim($node->nodeValue);
                    if (preg_match("/^View Recipe/i", $line)) {
                        $href = $node->getAttribute("href");
                        $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                        return $url;
                    }
                }
            }
        }

        // Epicurious "howtocook" section
        if (strpos($url, "epicurious.com/archive/howtocook/") !== false) {
            $xpath = self::getXPath($html);
            $nodes = $xpath->query('//article//li[@class="nosep"]/a');
            if ($nodes->length) {
                $href = $nodes->item(0)->getAttribute("href");
                $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                return $url;
            }
        }

        // Epicurious "Recipe Menus" section
        if (strpos($url, "epicurious.com/recipes-menus/") !== false) {
            $xpath = self::getXPath($html);
            $nodes = $xpath->query('//*[@class="recipe-related"]//*[@class="related"]/a');
            if ($nodes->length) {
                $href = $nodes->item(0)->getAttribute("href");
                $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                return $url;
            }

        }

        // Food52 print views
        if (strpos($url, "food52.com/recipes/print/") !== false) {
            return str_replace("/recipes/print/", "/recipes/", $url);
        }

        // Myrecipes.com mobile views
        if (strpos($url, "www.myrecipes.com/m/recipe/") !== false) {
            return str_replace("/m/recipe/", "/recipe/", $url);
        }
        // Myrecipes.com print views
        if (strpos($url, "www.myrecipes.com") !== false && strpos($url, "/print") !== false) {
            return preg_replace("/^(.*)\/print\/?$/", "$1", $url);
        }
        // Myrecipes.com quick and easy and how-to videos
        if (strpos($url, "myrecipes.com/how-to/video/") !== false || strpos($url, "myrecipes.com/quick-and-easy/") !== false) {
            $xpath = self::getXPath($html);

            // "Get the Recipe" link?
            $nodes = $xpath->query('//*[@class="inner-container"]//a');
            foreach ($nodes as $node) {
                $line = trim($node->nodeValue);
                if (strpos($line, "Get the Recipe:") !== false) {
                    $href = $node->getAttribute("href");
                    $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                    return $url;
                }
            }
        }

        // Ziplist souschef links
        if (strpos($url, "ziplist.com/souschef")) {
            $query = parse_url($url, PHP_URL_QUERY);
            $query = preg_replace("/^url=(.+)$/", "$1", $query);
            $url = urldecode($query);
            return $url;
        }

        return null;
    }

    public static function getXPath($html) {
        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        return new DOMXPath($doc);
    }

    public static function getUrlFromOgUrl($html, $url) {
        $xpath = self::getXPath($html);
        $nodes = $xpath->query('//meta[@property="og:url"]');
        if ($nodes->length) {
            $url = $nodes->item(0)->getAttribute("content");
            return $url;
        }
        return null;
    }

    public static function searchYummly($html, $url) {
        $xpath = self::getXPath($html);
        $nodes = $xpath->query('//*[@class="recipe-show-full-directions btn-inline wrapper"]');
        if ($nodes->length) {
            if ($href = $nodes->item(0)->getAttribute("href")) {
                $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                $url = preg_replace("/^(.+)\?.+$/", "$1", $url);
                return $url;
            }
        }
        return null;
    }

}
