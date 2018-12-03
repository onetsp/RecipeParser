<?php

class RecipeParser_Times {

    /**
     * Convert a time (string) to minutes. The string can be formatted in any
     * of the following (case-insensitive) formats:
     *
     *   10:30
     *   5 Hr 45 Min
     *   2 Hr
     *   30 Min
     *   4 Hours
     *   1 Hour
     *   30 Minutes
     *   30 Mins
     *   2 1/4 hours
     *   3 3/4 hrs
     *   2 days
     *   1h 30m
     *   15m
     *   1 hr - 1 hr 15 mins
     *   30-40 mins
     *   45 mm
     *   total: 30 mins   -> 30
     */
    static public function toMinutes($str) {
        $str = (string)$str;
        $days = 0;
        $hours = 0;
        $minutes = 0;

        // Expect time string to start with a digit
        $str = preg_replace("/^\D+(.*)$/", "$1", $str);

        // Normalize strings
        $str = trim(strtolower($str));
        $str = str_replace(',', ' ', $str);  // Strip commas
        $str = str_replace('  ', ' ', $str); // Multi-spacing

        // Convert ranges of times to a single time (the latter part of the range),
        // i.e. "1 hr - 1 hr 15 mins" will be replaced with "1 hr 15 mins".
        $str = preg_replace('/.+\-\s*(.+)/', "$1", $str);

        // Treat simple numeric value as minutes.
        if (preg_match("/^\d+$/", $str)) {
            $minutes = (int)$str;

        // Match HH:MM
        } else if (preg_match("/^(\d+)\:(\d{2})$/", $str, $m)) {
            $hours = (int)$m[1];
            $minutes = (int)$m[2];

        // Other cases...
        } else {
            $str = preg_replace("/\b(\d+)([dhm])/i", "$1 $2", $str); // Add space to "10hr" or "30m"
            $str = preg_replace("/\b(hours?|hrs?|h)\b/", "hrs", $str);
            $str = preg_replace("/\b(minutes?|mins?|m|mm)\b/", "mins", $str);
            $str = preg_replace("/\b(days?|d)\b/", "days", $str);

            // Replace fractions with decimals (e.g. 2 1/4 to 2.25)
            if (preg_match("/(\d+)\s(\d+)\/(\d+)/", $str, $m) && $m[3] > 0) {
                $decimal = (float)$m[1] + ((float)$m[2] / (float)$m[3]);
                $str = str_replace($m[0], (string)$decimal, $str);
            }

            // Match '## Day ## Hr ## Min'
            if (preg_match("/^((?<days>[\d\.]+) days)?\s?((?<hours>[\d\.]+) hrs)?\s?((?<minutes>[\d\.]+) mins)?$/", $str, $m)) {
                $days = isset($m['days']) ? (float)$m['days'] : 0;
                $hours = isset($m['hours']) ? (float)$m['hours'] : 0;
                $minutes = isset($m['minutes']) ? (float)$m['minutes'] : 0;
            }
        }

        $value = (int)($minutes + ($hours * 60) + ($days * 1440));
        return $value;
    }

}
