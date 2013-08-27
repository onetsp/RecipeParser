<?php

class RecipeParser_Parser_Sparkpeoplecom {

    public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Yield
        $nodes = $xpath->query('//*[@class="prep_box"]');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            if (preg_match("/Number of Servings: (\d+)/", $line, $m)) {
                $recipe->yield = RecipeParser_Text::formatYield($m[1]);
            }
        }

        // Instructions
        $recipe->resetInstructions();

        $str = "";
        $nodes = $xpath->query('//*[@itemprop="recipeInstructions"]');
        if ($nodes->length) {
            $children = $nodes->item(0)->childNodes;

            // This is a piece of HTML that has <br> tags for breaks in each instruction.
            // Rather than just getting nodeValue, I want to preserve the <br> tags. So I'm
            // looking for them as nodes and appending them to the string. Any other nodes
            // (either #text or other, e.g. <a href="">) get passed along into the string as 
            // nodeValue.
            foreach ($children as $child) {
                if ($child->nodeName == "br") {
                    $str .= "<br>";
                } else {
                    $line = trim($child->nodeValue);
                    if (!empty($line)) {
                        $str .= $line;
                    }
                }
            }
            $lines = explode("<br>", $str);
            foreach ($lines as $line) {
                if (empty($line)) {
                    continue;
                } else if (RecipeParser_Text::matchSectionName($line)) {
                    $line = RecipeParser_Text::formatSectionName($line);
                    $recipe->addInstructionsSection($line);
                } else if (!empty($line)) {
                    $line = RecipeParser_Text::formatAsOneLine($line);
                    $line = RecipeParser_Text::stripLeadingNumbers($line);
                    if (stripos($line, "Recipe submitted by SparkPeople") === 0) {
                        continue;
                    }
                    if (stripos($line, "Number of Servings:") === 0) {
                        continue;
                    }
                    $recipe->appendInstruction($line);
                }
            }
        }

        return $recipe;
    }

}

?>
