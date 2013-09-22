<?php

class FileUtil {

    
    public static function downloadPage($url) {
        $user_agent = "Onetsp-RecipeParser/0.1 (+https://github.com/onetsp/RecipeParser)";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }

}
