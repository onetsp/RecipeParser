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
            return self::searchYummlyIFrame($html, $url);
        }
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

        // Epicurious "Ingredients" features
        if (strpos($url, "epicurious.com/ingredients/") !== false) {
            // Need HTML page with <script> tags preserved!
            $strip_script_tags = false;
            $html = FileUtil::downloadRecipeWithCache($url, $strip_script_tags);
            if (preg_match("/\"pageValue\" ?\: ?\"slide(\d+)\"/", $html, $m)) {
                $slide = $m[1];
                $xpath = self::getXPath($html);

                // Iterate over <figcaption> looking for the right "slide #/10" node that contains an HREF
                $nodes = $xpath->query('//figcaption');
                foreach ($nodes as $node) {
                    if ($subs = $xpath->query('./*[@class="count"]', $node)) {
                        $str = $node->nodeValue;
                        if (strpos($str, $slide."/") !== false) {
                            $subs = $xpath->query('.//h3/a', $node);
                            if ($subs->length) {
                                $href = $subs->item(0)->getAttribute("href");
                                $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                                return $url;
                            }
                        }
                    }
                }
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
            $nodes = $xpath->query('//*[@class="item-list"]//a');
            foreach ($nodes as $node) {
                $line = trim($node->nodeValue);
                if (strpos($line, "Get the Recipe:") !== false) {
                    $href = $node->getAttribute("href");
                    $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                    return $url;
                }
            }

            // Calendar day link (for quick-and-easy)
            $nodes = $xpath->query('//*[@class="calendar-day-text-recipe-headline-link"]/a');
            if ($nodes->length) {
                $href = $nodes->item(0)->getAttribute("href");
                $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                return $url;
            }
        }

        // Saveur gallery pages
        if (strpos($url, "www.saveur.com/gallery/") !== false) {
            $xpath = self::getXPath($html);
            // Slide # comes from "image" query param, and range starts at 0.
            if (preg_match("/image=(\d+)/", $url, $m)) {
                $slide = $m[1];
            } else {
                $slide = 0;
            }
            $nodes = $xpath->query('//div[@class="gallery-slider"]//div[@class="content"]');
            if ($nodes->length) {
                $slide_node = $nodes->item($slide);
                $subs = $xpath->query('.//a', $slide_node);
                if ($subs->length) {
                    $href = $subs->item(0)->getAttribute("href");
                    $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                    return $url;
                }
            }
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

    public static function searchYummlyIFrame($html, $url) {
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

    public static function searchYummly($html, $url) {
        $xpath = self::getXPath($html);
        $nodes = $xpath->query('//*[@id="source-full-directions"]');
        if ($nodes->length) {
            if ($href = $nodes->item(0)->getAttribute("href")) {
                $url = RecipeParser_Text::relativeToAbsolute($href, $url);
                return $url;
            }
        }
        return null;
    }

}
