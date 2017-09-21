<?php

class RecipeParser_Text {

    const IGNORE_LEADING_NUMBERS = "IGNORE_LEADING_NUMBERS";

    static public $ignored_section_names = array("directions", "preparation");

    /**
     * Get string for HTML comment that can be added to RecipeParser test files.
     *
     * @return string
     */
    static public function getRecipeMetadataComment($url, $user_agent) {
        $time = date('r');
        return
"<!--
ONETSP_URL: $url
ONETSP_USER_AGENT: $user_agent
ONETSP_TIME: $time
-->";
    }

    /**
     * Return the URL of a clipped recipe file from our metadata comment that was added
     * to the HTML content.
     * 
     * @param string HTML of recipe file
     * @return string URL
     */
    static public function getRecipeUrlFromMetadata($html) {
        $url = null;
        if (preg_match("/^ONETSP_URL: (.*)$/m", $html, $m)) {
            $url = $m[1];
        }
        return $url;
    }

    /**
     * Ensure that a string is in UTF-8. If it is encoded in ISO-8859-1, try
     * to encode as UFT-8.
     *
     * @param string
     * @return string
     */
    static public function forceUTF8($str) {
        $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8, ISO-8859-1');
        return $str;
    }

    /**
     * Strip selected HTML tag from content. E.g. Remove entire <script> tags
     * and their contents.
     *
     * @param string Tag to be removed
     * @param string HTML
     * @return string Modified HTML 
     */
    static public function stripTagAndContents($tagname, $html) {
        $pattern = '/<' . $tagname . '[^>]*>.*?<\/' . $tagname .'>/is';
        $replacement = '<!-- STRIPPED ' . strtoupper($tagname) . ' TAG -->';
        $html = preg_replace($pattern, $replacement, $html);
        return $html;
    }

    /**
     * Cleanup for clipped HTML prior to parsing with RecipeParser.
     *
     * @param string HTML
     * @param bool Strip <script> tags from HTML (default)
     * @return string HTML
     */
    static public function cleanupClippedRecipeHtml($html, $strip_script_tags=true) {
        $html = preg_replace('/(\r\n|\r)/', "\n", $html);            // Normalize line breaks
        $html = str_replace('&nbsp;', ' ', $html);                   // get rid of non-breaking space (html code)
        $html = str_replace('&#160;', ' ', $html);                   // get rid of non-breaking space (numeric)
        $html = preg_replace('/\xC2\xA0/', ' ', $html);              // get rid of non-breaking space (UTF-8)
        $html = preg_replace('/[\x{0096}-\x{0097}]/u', '-', $html);  // ndash, mdash (bonappetit)

        // Strip out script tags so they don't accidentally get executed if we ever display
        // clipped content to end-users.
        if ($strip_script_tags) {
            $html = RecipeParser_Text::stripTagAndContents('script', $html);
        }

        return $html;
    }

    /**
     * Remove extraneous whitespace and line breaks.
     *
     * @param string
     * @return string
     */
    public static function formatAsOneLine($str) {
        $str = preg_replace('/\s+/', ' ', $str);   // squash multi-spaces
        $str = preg_replace('/\s+,/', ',', $str);  // fix hanging commas (don't recall where this was needed)
        $str = trim($str);
        return $str;
    }

    /**
     * Normalize paragraphs to use double line-breaks, removing other whitespace and form-feeds.
     *
     * @param string
     * @return string
     */
    public static function formatAsParagraphs($str) {
        $str = str_replace("\r", "", $str);             // drop form-feeds
        $str = preg_replace("/\s*\n\s*\n\s*/", "<p>", $str);  // use <p> tag to replace multiple newlines
        $str = preg_replace("/\s+/", " ", $str);        // squash multi spaces
        $str = str_replace("<p>", "\n\n", $str);        // <p> back to newlines
        $str = trim($str);
        return $str;
    }

    /**
     * Strip numeric bullets from given string.
     *
     * @param string
     * @return string
     */
    public static function stripLeadingNumbers($str) {
        // Leading number without any following content. E.g. "1. ", "1) "
        if (preg_match("/^\d+\.?\)?$/", $str)) {
            return "";
        }
        if (preg_match("/^(step\s+)?\d+[\:\.]?\)?\s+(.+)$/i", $str, $m)) {
            $str = trim($m[2]);
        }
        return $str;
    }

    /**
     * Try to determine if a given string looks like it might be a section name.
     *
     * @param string
     * @return bool
     */
    public static function matchSectionName($str) {
        // Should unit test these
        // Max line length for section name?

        $str = trim($str);

        // Bare numbers are not section names
        if (preg_match("/^\d+(\.|:)?$/", $str)) {
            return false;
        }

        // Look for generic, single-word section names
        $lower = strtolower($str);
        if (in_array($lower, self::$ignored_section_names)) {
            return true;
        }

        // Look for strings wrapped in equals or dashes, e.g. "---Cake---"
        if (preg_match('/^[-=]+(.*?)[-=]+$/', $str)) {
            return true;
        }

        // Assume all caps, or ending with ':' is a section title.
        return ($str == strtoupper($str) || preg_match("/^[^\,\.]+:$/", $str));
    }

    /**
     * Cleanup text used for sections within ingredients and instructions lists.
     *
     * @param string
     * @return string
     */
    public static function formatSectionName($str) {
        $str = strtolower(trim($str));
        $str = self::formatAsOneLine($str);
        $str = self::stripLeadingNumbers($str);

        // Strip trailing punctuation
        $str = preg_replace('/(.*)\:\W*$/', "$1", $str);

        // Strip leading "for" and "the" (as well as "for the").
        $str = preg_replace('/^for\s+(.*)$/', "$1", $str);
        $str = preg_replace('/^the\s+(.*)$/', "$1", $str);

        // Strip leading and trailing dashes and equals
        $str = preg_replace('/^[-=]+(.*?)[-=]+$/', "$1", $str);

        // Wipe out some generic section headers.
        if (in_array($str, self::$ignored_section_names)) {
            $str = "";
        }

        // All dashes or equals should be stripped, e.g. "---" and "==="
        if (preg_match("/^[-=]+$/", $str)) {
            $str = "";
        }

        $str = ucfirst($str);
        return $str;
    }

    /**
     * Clean up recipe credits
     *
     * @param string Credit attribution for recipe
     * @return string
     */
    static public function formatCredits($source) {
        $source = self::formatAsOneLine($source);

        // Remove extraneous prefixes
        $source = preg_replace("/recipe (from|by|courtesy of|courtesy)\s+/i", "", $source);
        $source = preg_replace("/^from\s+/i", "", $source);

        return $source;
    }

    /**
     * Normalize some words and phrases within titles
     *
     * @param string
     * @return string
     */
    static public function formatTitle($title) {
        $title = self::formatAsOneLine($title);
        $title = preg_replace("/Sponsored recipe:?/i", "", $title);
        $title = preg_replace("/\s+recipe$/i", "", $title);
        $title = preg_replace("/^Recipe\s+for\s+/i", "", $title);
        return trim($title);
    }

    /**
     * Normalize yield to "xx servings"
     */
    static public function formatYield($str) {
        $str = self::formatAsOneLine($str);
        $str = rtrim($str, '.');
        $str = strtolower($str);
        $str = str_replace('–', '-', $str);
        $str = self::convertWordsToNumbers($str);

        // Convert "serves ## into ## servings"
        $str = preg_replace("/^serves (\d+ to \d+)(.*)$/i", "$1 servings$2", $str);
        $str = preg_replace("/^serves ([\d\-]+)(.*)$/i", "$1 servings$2", $str);
        $str = preg_replace("/^1 servings(.*)$/", "1 serving$1", $str);

        $uses_makes = (strpos($str, "makes") === 0);

        // Remove leading "Yield:" or "Servings:"
        $str = preg_replace("/^(yields?|servings|serves|makes about|makes)\:?\s+/", "", $str);

        // Condense spaces around hyphens
        $str = preg_replace("/(\d+)\s*-\s*(\d+)/", "$1-$2", $str);

        if (!$uses_makes) {
            if ($str == "1") {
                $str .= " serving";
            } else if (preg_match("/\d$/", $str)) {
                $str .= " servings";
            }
        }

        return $str;
    }

    private static function convertWordsToNumbers($str) {
        $numbers = array(
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9,
            'ten' => 10,
            'eleven' => 11,
            'twelve' => 12,
            'thirteen' => 13,
            'fourteen' => 14,
            'fifteen' => 15,
            'sixteen' => 16,
            'seventeen' => 17,
            'eighteen' => 18,
            'nineteen' => 19,
            'twenty' => 20,
        );
        foreach ($numbers as $numstr => $digit) {
            if (strpos($str, $numstr) !== false) {
                $str = preg_replace("/\b$numstr\b/", $digit, $str);
            }
        }
        return $str;
    }

    public static function parseInstructionsFromNodes($nodes, &$recipe) {
        $last = '';
        foreach ($nodes as $node) {
            $value = $node->nodeValue;
            $value = self::formatAsOneLine($value);

            // Skip duplicate lines (can happen in places where xpath matches two instances
            // of a line because of duplicate class names).
            if ($value == $last) {
                continue;
            }

            if (self::matchSectionName($value)) {
                $value = self::formatSectionName($value);
                $recipe->addInstructionsSection($value);
            } else {
                $value = self::stripLeadingNumbers($value);
                $recipe->appendInstruction($value);
            }
            $last = $value;
        }
    }

    public static function parseInstructionsFromBlob($str, &$recipe) {
        $lines = self::parseListFromBlob($str);
        foreach ($lines as $line) {
            if (self::matchSectionName($line)) {
                $line = self::formatSectionName($line);
                $recipe->addInstructionsSection($line);
            } else {
                $recipe->appendInstruction($line);
            }
        }
    }

    public static function parseIngredientsAndInstructionsFromBlob($str, &$recipe) {
        $lines = self::parseListFromBlob($str, self::IGNORE_LEADING_NUMBERS);

        $finished_ingredients = false;
        while ($line = array_shift($lines)) {

            if (!$finished_ingredients) {

                if (self::matchSectionName($line)) {
                    $line = self::formatSectionName($line);
                    $recipe->addIngredientsSection($line);
                } else {
                    // Does this line look like an ingredient?
                    if (preg_match("/^\d/", $line)
                        || stripos("to taste", $line) !== false
                        || stripos("optional", $line) !== false
                        || strlen($line) < 40  // this is questionable
                    ) {
                        // Found ingredient
                        $line = self::formatAsOneLine($line);
                        $recipe->appendIngredient($line);
                    
                    } else {
                        // Looks like next line is part of ingredients
                        $finished_ingredients = true;
                        array_unshift($lines, $line);
                    }
                }

            } else {
                // Instructions
                if (self::matchSectionName($line)) {
                    $line = self::formatSectionName($line);
                    $recipe->addInstructionsSection($line);
                } else {
                    $recipe->appendInstruction($line);
                }
            }
        }

    }

    public static function parseListFromBlob($str, $opt=null) {
        $list = array();

        if (isset($_SERVER['VERBOSE'])) {
            echo "[" . __FUNCTION__ . "] STR:\n$str\n";
        }
        
        // Replace leading digits or bullets with <br>s
        $pattern = '/(^|\n)\s*(\d+\.*|\*|\-)\s/';
        if ($opt == self::IGNORE_LEADING_NUMBERS) {
            $pattern = str_replace("\d+", "", $pattern);
        }
        $str = preg_replace($pattern, "<br>", $str);

        // Use <br> to split on sentences that are jammed together (which often occurs)
        // when parsing text content from DOM nodes.
        $str = preg_replace("/(\.)([A-Z0-9])/", "$1<br>$2", $str);

        // Transform multiple newlines to <br> tags
        $str = preg_replace("/\n{2,}/", "<br>", $str);

        // If no <br>s have been added, we may assume that we should split on newlines.
        if (strpos($str, "<br>") === false) {
            $str = str_replace("\n", "<br>", $str);
        }

        // Split lines into a list.
        $lines = explode("<br>", $str);
        foreach ($lines as $line) {
            $line = self::formatAsOneLine($line);
            if ($opt != self::IGNORE_LEADING_NUMBERS) {
                $line = self::stripLeadingNumbers($line);
            }
            if ($line) {
                $list[] = $line;
            }
        }

        if (isset($_SERVER['VERBOSE'])) {
            echo "[" . __FUNCTION__ . "] LIST:\n" . print_r($list, true) . "\n";
        }

        return $list;
    }

    /**
     * THIS SHOULD BE CONSIDERED AN INCOMPLETE IMPLEMENTATION!
     *
     * @param string
     * @return int Minutes
     */
    public static function iso8601ToMinutes($str) {
        $sec = 0;

        if (strpos($str, 'P') === 0) {
            $str = substr($str, 1);
        }
        if (strpos($str, 'T') === false) {
            $p = $str;
            $t = '';
        } else {
            list($p, $t) = explode('T', $str);
        }

        // Period
        if (preg_match_all('/([\d\,\.]+)([YMD])/', $p, $m)) {
            for ($i = 0; $i < count($m[1]); $i++) {
                $val = (float) str_replace(',', '.', $m[1][$i]);
                if ($m[2][$i] == 'Y') {
                    $sec += $val * 31556926;
                } else if ($m[2][$i] == 'M') {
                    $sec += $val * 2629744;
                } else if ($m[2][$i] == 'D') {
                    $sec += $val * 86400;
                }
            }
        }

        // Time
        if (preg_match_all('/([\d\,\.]+)([HMS])/', $t, $m)) {
            for ($i = 0; $i < count($m[1]); $i++) {
                $val = (float) str_replace(',', '.', $m[1][$i]);
                if ($m[2][$i] == 'H') {
                    $sec += $val * 3600;
                } else if ($m[2][$i] == 'M') {
                    $sec += $val * 60;
                } else if ($m[2][$i] == 'S') {
                    $sec += $val;
                }
            }
        }

        $minutes = (int) round($sec / 60);
        return $minutes;
    }
    
    
    public static function mixedTimeToMinutes($str) {
        $sec = 0;

        // Time
        if (preg_match_all('/([\d\,\.]+)(\s*)([HMS])/i', $str, $m)) {
            for ($i = 0; $i < count($m[0]); $i++) {
                $val = str_replace(',', '.', $m[0][$i]);
                
                if (preg_match('/H/i', $val)) {
                    $sec += (float) $val * 3600;
                } else if (preg_match('/M/i', $val)) {
                    $sec +=  (float) $val * 60;
                } else if (preg_match('/S/i', $val)) {
                    $sec +=  (float) $val;
                }
            }
        }

        $minutes = (int) round($sec / 60);
        return $minutes;
    }

    public static function relativeToAbsolute($rel, $base) {
        // return if already absolute URL
        if (parse_url($rel, PHP_URL_SCHEME) != "") {
            return $rel;
        }
        
        // Handle scheme-less URLs (e.g. "//img.food52.com/path/to/image.jpg")
        if (parse_url($rel, PHP_URL_HOST) != "" && parse_url($rel, PHP_URL_SCHEME) == "") {
            $base_scheme = parse_url($base, PHP_URL_SCHEME);
            if (!empty($base_scheme)) {
                $rel = $base_scheme . ":" . $rel;
                return $rel;
            }
        }

        // queries and anchors
        if ($rel[0]=='#' || $rel[0]=='?') {
            return $base.$rel;
        }

        // parse base URL and convert to local variables: $scheme, $host, $path
        extract(parse_url($base));

        // remove non-directory element from path 
        $path = preg_replace('#/[^/]*$#', '', $path);

        // destroy path if relative url points to root
        if ($rel[0] == '/') {
            $path = '';
        }

        // dirty absolute URL
        $abs = "$host$path/$rel";

        // replace '//' or '/./' or '/foo/../' with '/'
        $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        for ($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}

        return $scheme.'://'.$abs;
    }


    /**
     * Convert <title> from recipe file into string that can be used for a local filename.
     *
     * @param string
     * @return string
     *
     */
    public static function formatFilenameFromTitle($title) {
        $title_strip_terms = array(
            "recipe from",
            "recipes",
            "recipe",
        );

        $title = strtolower($title);
        foreach ($title_strip_terms as $strip) {
            $title = str_replace($strip, '', $title);
        }

        // Split title on " - " or " | " or " : ".
        #$parts = preg_split("/[\-\-\|\:]/", $title);
        #$title = $parts[0];

        $title = preg_replace('/[^A-Za-z]+/', '_', $title);

        $title = preg_replace("/\s+\W\s+/", "_", $title); // remove single non-word chars
        $title = preg_replace("/\s+/", "_", $title);      // normalize spaces
        $title = str_replace("_s_", "s_", $title);        // common issue of stranded _s_ for possessives
        $title = preg_replace("/_s$/", "s", $title);      // common issue of stranded _s_ for possessives
        $title = str_replace("__", "_", $title);
        $title = trim($title, '_');

        $parts = explode("_", $title);
        while (count($parts) > 6) {
            array_pop($parts);
        }
        $title = implode("_", $parts);

        return $title;
    }


    public static function getMetaProperty($xpath, $property) {
        $nodes = $xpath->query('//meta[@property="' . $property . '"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->getAttribute("content");
            return $line;
        }
        return "";
    }

}
