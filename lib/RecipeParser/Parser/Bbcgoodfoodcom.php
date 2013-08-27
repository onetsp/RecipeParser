<?php

class RecipeParser_Parser_Bbcgoodfoodcom {

    public function parse($html, $url) {
        // Get all of the standard hrecipe stuff we can find.
        $recipe = RecipeParser_Parser_Microformat::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Strip some duplicated contents from DOM
        $nodes = $xpath->query("//div[@id='printSidebar']");
        foreach ($nodes as $node) {
            $node->parentNode->removeChild($node);
        }
        
        // Prep / Cook times
        $nodes = $xpath->query('//div[@id="prep"]/p');
        foreach ($nodes as $node) {
            $line = RecipeParser_Text::FormatAsOneLine($node->nodeValue);
            if (strpos($line, 'Prep') === 0) {
                $line = substr($line, 5);
                $recipe->time['prep'] = Times::toMinutes($line);

            } else if (strpos($line, 'Cook') === 0) {
                $line = substr($line, 5);
                $recipe->time['cook'] = Times::toMinutes($line);

            } else if (strpos($line, 'Ready in') === 0) {
                $line = substr($line, 9);
                $recipe->time['total'] = Times::toMinutes($line);
            }
        }

        // Ingredients
        $recipe->resetIngredients();           

        $nodes = $xpath->query('//*[@id="ingredients"]/*');
        foreach ($nodes as $node) {

            if ($node->nodeName == 'h4') {
                $line = $node->nodeValue;
                $line = RecipeParser_Text::formatSectionName($line);
                $recipe->addIngredientsSection($line);
            
            } else if ($node->nodeName == 'ul') {
                $subnodes = $xpath->query('./li', $node);
                foreach ($subnodes as $subnode) {
                    $line = $subnode->nodeValue;
                    $line = RecipeParser_Text::formatAsOneLine($line);

                    $recipe->appendIngredient($line);
                }
            }
        }

        // Instructions
        $recipe->resetInstructions();

        $nodes = $xpath->query('//div[@id="method"]//li');
        foreach ($nodes as $node) {
            $line = RecipeParser_Text::FormatAsOneLine($node->nodeValue);
            $recipe->appendInstruction($line);
        }

        return $recipe;
    }

}

?>
