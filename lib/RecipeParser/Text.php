<?php

class RecipeParser_Text {

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
        if (preg_match("/^\d+\.?\)?\s+(.+)$/", $str, $m)) {
            $str = trim($m[1]);
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
        if (preg_match("/^\d+:?$/", $str)) {
            return false;
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
        $str = self::stripLeadingNumbers($str);

        // Strip trailing punctuation
        $str = preg_replace('/(.*)\:\W*$/', "$1", $str);

        // Strip leading "for" and "the" (as well as "for the").
        $str = preg_replace('/^for\s+(.*)$/', "$1", $str);
        $str = preg_replace('/^the\s+(.*)$/', "$1", $str);

        $str = ucfirst($str);
        return $str;
    }

    /**
     * Clean up recipe credits
     *
     * @param string Source name
     * @return string
     */
    static public function formatCredits($source) {
        $source = self::formatAsOneLine($source);

        // Remove extraneous prefixes
        $source = preg_replace("/recipe (by|courtesy of|courtesy)\s+/i", "", $source);
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
        $title = preg_replace("/\s+recipe$/i", "", $title);
        $title = preg_replace("/^Recipe\s+for\s+/i", "", $title);
        return $title;
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

        $uses_makes = (strpos($str, "makes") === 0);

        // Remove leading "Yield:" or "Servings:"
        $str = preg_replace("/(yield|servings|serves|makes)\:?\s+/", "", $str);

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
            $recipe->appendInstruction($line);
        }
    }

    public static function parseListFromBlob($str) {
        $list = array();

        // Replace leading digits or bullets with newlines
        $str = preg_replace("/(^|\n)\s*(\d+\.*|\*|\-)\s/", "\n\n", $str);

        // Split on sentences that are jammed together (which often occurs)
        // when parsing text content from DOM nodes.
        $str = preg_replace("/(\.)([A-Z])/", "$1\n\n$2", $str);

        $lines = explode("\n\n", $str);
        foreach ($lines as $line) {
            $line = self::formatAsOneLine($line);
            if ($line) {
                $list[] = $line;
            }
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


    public static function formatPhotoUrl($photo_url, $recipe_url) {
        if (strpos($photo_url, './') === 0) {
            $photo_url = substr($photo_url, 2);
        }

        if (preg_match('/^https?:\/\//i', $photo_url)) {
            return $photo_url;
        } else if (strpos($photo_url, '/') === 0) {
            $parts = parse_url($recipe_url);
            if ($parts !== false) {
                $abs_url = $parts['scheme'] . '://' . $parts['host'] . $photo_url;
                return $abs_url;
            }

        } else if (strpos($photo_url, '..') === 0) {
            // Not implemented.

        } else {
            $photo_url = substr($recipe_url, 0, strrpos($recipe_url, '/')) . '/' . $photo_url;
        }

        return $photo_url;
    }

}
