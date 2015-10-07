<?php

class RecipeParser_Parser_Kingarthurflourcom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR KINGARTHURFLOUR.COM

        // Ingredients
        $recipe->resetIngredients();
        $nodes = $xpath->query('//*[@id="v_ingredients"]//*[@id="IngredientSet"]');
        foreach ($nodes as $node) {

            $children = $xpath->query('.//*[@id="IngredientHeading"]', $node);
            if ($children->length) {
                $line = $children->item(0)->nodeValue;
                $line = RecipeParser_Text::formatSectionName($line);
                $recipe->addIngredientsSection($line);
            }

            $children = $xpath->query('.//*[@id="IngredientLine"]', $node);
            foreach ($children as $child) {
                $line = $child->nodeValue;
                $line = RecipeParser_Text::formatAsOneLine($line);
                $recipe->appendIngredient($line);
            }

        }

        // Instructions
        $recipe->resetInstructions();

        $str = "";
        $nodes = $xpath->query('//*[@itemprop="instructions"]');
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
                } else if ($child->nodeName == "b") {
                    $str .= "SECTION:" . $child->nodeValue; 
                } else {
                    $line = $child->nodeValue;
                    if (preg_match("/\S/", $line)) {
                        $str .= $line;
                    }
                }
            }
            $lines = explode("<br>", $str);
            foreach ($lines as $line) {
                if (strpos($line, "SECTION:") === 0) {
                    $line = substr($line, 8);
                    $line = RecipeParser_Text::formatSectionName($line);
                    $recipe->addInstructionsSection($line);
                } else {
                    $line = RecipeParser_Text::formatAsOneLine($line);
                    $line = RecipeParser_Text::stripLeadingNumbers($line);
                    if (stripos($line, "yield:") === 0) {
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
