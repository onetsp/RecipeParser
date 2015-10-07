<?php

class RecipeParser_Parser_Aboutcom {

    static public function parse(DOMDocument $doc, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($doc, $url);
        $xpath = new DOMXPath($doc);

        // OVERRIDES FOR ABOUT.COM

        // Title
        $nodes = $xpath->query('//*[@itemprop="headline name"]');
        if ($nodes->length) {
            $value = trim($nodes->item(0)->nodeValue);
            $recipe->title = RecipeParser_Text::formatTitle($value);
        }

        // Credits
        $nodes = $xpath->query('//*[@itemprop="author"]//*[@itemprop="name"]');
        if ($nodes->length) {
            $line = $nodes->item(0)->nodeValue;
            $recipe->credits = RecipeParser_Text::formatCredits($line . ", About.com");
        }

        // Ingredients 
        $recipe->resetIngredients();
        $nodes = $xpath->query('//*[@itemprop="ingredients"]');
        foreach ($nodes as $node) {
            $value = $node->nodeValue;
            $value = RecipeParser_Text::formatAsOneLine($value);
            if (RecipeParser_Text::matchSectionName($value) 
                || $node->childNodes->item(0)->nodeName == "strong"
                || $node->childNodes->item(0)->nodeName == "b"
            ) {
                $value = RecipeParser_Text::formatSectionName($value);
                $recipe->addIngredientsSection($value);
            } else {
                $recipe->appendIngredient($value);
            }
        }


        // Instructions
        $recipe->resetInstructions();

        $nodes = $xpath->query('//div[@itemprop="recipeInstructions"]');
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
                if (strtolower($line) == "preparation") {
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
