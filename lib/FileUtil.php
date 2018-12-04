<?php

class FileUtil {

    public static function tempFilenameFromUrl($url) {
        $hostname = parse_url($url, PHP_URL_HOST);
        $hostname = str_replace(".", "_", $hostname);
        $basename = "onetsp_{$hostname}_" . substr(md5($url), 0, 8);
        $filename = sys_get_temp_dir() . "/" . $basename;
        return $filename;
    }
    
    public static function downloadPage($url) {
        $user_agent = "Onetsp-RecipeParser/0.1 (+https://github.com/onetsp/RecipeParser)";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }

    public static function downloadRecipeWithCache($url, $strip_script_tags=true) {
        $cache_ttl = 86400 * 3;

        // Target filename
        $filename = FileUtil::tempFilenameFromUrl($url);
        if (!$strip_script_tags) {
            $filename .= "_noscript";
        }

        // Only fetch 1x per day
        if (file_exists($filename)
            && filesize($filename) > 0
            && (time() - filemtime($filename) < $cache_ttl)
        ) {
            Log::notice("Found file in cache: $filename");
            $html = file_get_contents($filename);

        } else {
            // Fetch and cleanup the HTML
            Log::notice("Downloading recipe from url: $url");

            $html = FileUtil::downloadPage($url);
            $html = RecipeParser_Text::forceUTF8($html);
            $html = RecipeParser_Text::cleanupClippedRecipeHtml($html, $strip_script_tags);

            // Append some notes to the HTML
            $comments = RecipeParser_Text::getRecipeMetadataComment($url, "curl");
            $html = $comments . "\n\n" . $html;

            Log::notice("Saving recipe to file $filename");
            file_put_contents($filename, $html);
        }

        return $html;
    }

}
