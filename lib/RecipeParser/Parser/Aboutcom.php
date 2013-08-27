<?php

class RecipeParser_Parser_Aboutcom {

    public function parse($html, $url) {

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");

        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        $recipe = RecipeParser_Parser_Microformat::parse($html, $url);

        // OVERRIDES FOR ABOUT.COM

        // Cook times
        $node_list = $xpath->query('//div[@id = "articlebody"]/h3');
        foreach ($node_list as $node) {
            $line = $node->nodeValue;
            $line = preg_replace('/[\s\"]+/', ' ', $line);
            $line = trim($line);

            if (preg_match("/prep time\:(.+)/i", $line, $m)) {
                $recipe->time['prep'] = Times::toMinutes($m[1]);
            } else if (preg_match("/cook time\:(.+)/i", $line, $m)) {
                $recipe->time['cook'] = Times::toMinutes($m[1]);
            }
            // Total time is provided as part of microformat markup for About.com
        }


        // Instructions
        $recipe->resetInstructions();

        $nodes = $xpath->query('//div[@class = "instructions"]');
        foreach ($nodes as $node) {

            $text = trim($node->nodeValue);
            $lines = preg_split("/[\n\r]+/", $text);

            for ($i = count($lines) - 1; $i >= 0; $i--) {
                $lines[$i] = trim($lines[$i]);

                // Remove ends of lines that have the word "recipes" squashed up against
                // another word, which seems to happen with long lists of related
                // recipe links.
                // Remove lines that have the phrase "Xxxxx Recipes and More".
                // Remove lines that have the phrase "Xxxxx Recipes | Xxxxx".
                // Remove mentions of newsletters.

                $lines[$i] = preg_replace("/(.*)recipes\w/i", "$1", $lines[$i]);
                $lines[$i] = preg_replace("/(.*)More .* Recipes.*/", "$1", $lines[$i]);
                $lines[$i] = preg_replace("/(.*)Recipes and More.*/", "$1", $lines[$i]);
                $lines[$i] = preg_replace("/(.*)Recipes \| .*/", "$1", $lines[$i]);
                $lines[$i] = preg_replace("/(.*)Recipe Newsletter.*/", "$1", $lines[$i]);

                // Look for a line in the instructions that looks like a yield.
                if (strpos($lines[$i], "Makes ") === 0) {
                    $recipe->yield = substr($lines[$i], 6);
                    $lines[$i] = '';
                    continue;
                }
            }

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                // Match section names that read something like "---For the cake: Raise the oven temperature..."
                if (preg_match("/^(?:-{2,})?For the (.+)\: (.*)$/i", $line, $m)) {
                    $section = $m[1];
                    $section = RecipeParser_Text::formatSectionName($section);
                    $recipe->addInstructionsSection($section);

                    // Reset the value of $line, without the section name.
                    $line = ucfirst($m[2]);
                }

                $recipe->appendInstruction($line);
            }
        }

        return $recipe;
    }

}

?>
