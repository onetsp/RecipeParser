<?php

class RecipeParser_Redirect {

    /**
     * Inspect HTML for signs that it redirects or links to another (canonical) recipe file.
     *
     * @param string HTML
     * @param string URL of HTML page
     * @return mixed URL for redirect, or null
     */
    static public function inspectHtmlForRedirect($html, $url) {

        if (strpos($url, "http://www.yummly.com/recipe/external/") !== false) {
            return self::searchYummlyIFrame($html);
        }
        if (strpos($url, "http://www.yummly.com/recipe/") !== false) {
            return self::searchYummly($html);
        }

        ## TODO
        ## Test for print/mobile url pattern here
        ## (1st pass should search for canonical URL)
        ## <link rel="canonical" href="http://www.bonappetit.com/recipe/chai-spiced-hot-chocolate-2" />

        return null;
    }

    static public function getXPath($html) {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        return new DOMXPath($doc);
    }

    static public function searchYummlyIFrame($html) {
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

    static public function searchYummly($html) {
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
