<?php

class FileUtil {

    public static function tempFilenameFromUrl($url, $tmpdir=null) {
        if (!$tmpdir) {
            $tmpdir = sys_get_temp_dir();
        }
        $hostname = parse_url($url, PHP_URL_HOST);
        $hostname = str_replace(".", "_", $hostname);
        $basename = "onetsp_{$hostname}_" . substr(md5($url), 0, 10);
        $filename = $tmpdir . "/" . $basename;
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }

    public static function downloadRecipeWithCache($url, $tmpdir=null) {
        $cache_ttl = 86400 * 3;

        // Target filename
        $filename = FileUtil::tempFilenameFromUrl($url, $tmpdir);

        // Only fetch 1x per day
        if (file_exists($filename)
            && filesize($filename) > 0
            && (time() - filemtime($filename) < $cache_ttl)
        ) {
            $filesize = filesize($filename);
            Log::notice("Found file in cache: $filename, size is $filesize", __CLASS__);
            $html = file_get_contents($filename);

        } else {
            // Fetch and cleanup the HTML
            Log::notice("Downloading file from url: $url", __CLASS__);

            $html = FileUtil::downloadPage($url);
            $html = RecipeParser_Text::forceUTF8($html);
            $html = RecipeParser_Text::cleanupClippedRecipeHtml($html);

            // Append some notes to the HTML
            $comments = RecipeParser_Text::getRecipeMetadataComment($url, "curl");
            $html = $comments . "\n\n" . $html;

            file_put_contents($filename, $html);
            $filesize = filesize($filename);
            Log::notice("Saved file to $filename, size is $filesize", __CLASS__);
        }

        return $html;
    }

}
