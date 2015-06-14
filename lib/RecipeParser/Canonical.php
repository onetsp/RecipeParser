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
        if (strpos($url, "allrecipes.com/Recipe-Tools/Print/") !== false) {
            $xpath = self::getXPath($html);
            $nodes = $xpath->query('//*[@class="backtorecipe"]//a');
            if ($nodes->length) {
                $href = $nodes->item(0)->getAttribute("href");
                $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                return $url;
            }
        }

        // m.allrecipes.com
        if (strpos($url, "m.allrecipes.com/recipe/") !== false) {
            return self::getUrlFromOgUrl($html, $url);
        }

        // Foodnetwork.com videos
        if (strpos($url, "www.foodnetwork.com/videos/") !== false) {
            $xpath = self::getXPath($html);
            $nodes = $xpath->query('//*[@class="title-wrap"]//a[@class="btn vip"]');
            if ($nodes->length) {
                $href = $nodes->item(0)->getAttribute("href");
                $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                return $url;
            }
        }
    
        // Yummly
        if (strpos($url, "http://www.yummly.com/recipe/external/") !== false) {
            return self::searchYummlyIFrame($html);
        }
        if (strpos($url, "http://www.yummly.com/recipe/") !== false) {
            return self::searchYummly($html);
        }

        // Foodnetwork.com print view
        if (strpos($url, "www.foodnetwork.com") !== false && strpos($url, ".print.html") !== false) {
            return str_replace(".print.html", ".html", $url);
        }

        // Epicurious print view
        if (strpos($url, "epicurious.com") !== false && strpos($url, "printerfriendly") !== false) {
            return str_replace("printerfriendly", "views", $url);
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

    public static function searchYummlyIFrame($html) {
        $xpath = self::getXPath($html);
        $nodes = $xpath->query('//iframe[@id="yFrame"]');
        if ($nodes->length) {
            $url = $nodes->item(0)->getAttribute("src");
            if ($url) {
                return $url;
            }
        }
        return null;
    }

    public static function searchYummly($html) {
        $xpath = self::getXPath($html);
        $nodes = $xpath->query('//button[@id="source-full-directions"]');
        if ($nodes->length) {
            $url = $nodes->item(0)->getAttribute("link");
            if ($url) {
                if (strpos($url, "/") === 0) {
                    $url = "http://www.yummly.com" . $url;
                }
                return $url;
            }
        }
        return null;
    }

}
