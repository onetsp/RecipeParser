<?php

class RecipeParser_Parser_Foodnetworkcom {

    static public function parse($html, $url) {
        // Get all of the standard microdata stuff we can find.
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        // Turn off libxml errors to prevent mismatched tag warnings.
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Ingredients
        $recipe->resetIngredients();
        // $nodes = $xpath->query('//div[@class="bd"]//ul');
        $nodes = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " ingredients ")]//ul/li');
        foreach ($nodes as $node) {
            if ($node->getAttribute("itemprop") == "ingredients") {
                // This is an actual ingredient (not a subtitle)
                $line = trim($node->nodeValue);
                // Ingredient lines
                if (stripos($line, "copyright") !== false) {
                    continue;
                } else if (stripos($line, "recipe follows") !== false) {
                    continue;
                } else {
                    $line = RecipeParser_Text::formatAsOneLine($line);
                    $recipe->appendIngredient($line);
                }
            }
            if ($node->getAttribute("class") == "subtitle") {
                // Section titles might be all uppercase ingredients
                $line = trim($node->nodeValue);
                $line = RecipeParser_Text::formatSectionName($line);
                $recipe->addIngredientsSection($line);
            }

        }

        // Instructions
        $recipe->resetInstructions();
        $nodes = $xpath->query('//*[@itemprop="recipeInstructions"]/*');
        foreach ($nodes as $node) {
            if ($node->nodeName == "span") {
                $line = RecipeParser_Text::formatSectionName($node->nodeValue);
                $recipe->addInstructionsSection($line);
            } else if ($node->nodeName == "ul") {
                foreach($node->childNodes as $subnode) {

                    $line = RecipeParser_Text::formatAsOneLine($subnode->nodeValue);

                    if (stripos($line, "recipe courtesy") === 0) {
                        continue;
                    }
                    if (strtolower($line) == "from food network kitchens") {
                        continue;
                    }
                    if (stripos($line, "Photograph") === 0) {
                        continue;
                    }
                    if (preg_match("/Copyright/", $line)) {
                        continue;
                    }
                    if (preg_match("/From Food Network Kitchen/", $line)) {
                        continue;
                    }
                    if (preg_match("/Special equipment/", $line)) {
                        continue;
                    }

                    $recipe->appendInstruction($line);
                }
            }

        }

        // See if we've captured a chef's photo, and delete it (if so).
        if ($recipe->photo_url) {
            $nodes = $xpath->query('//a[@itemprop="url"]/img[@itemprop="image"]');
            if ($nodes->length > 0) {
                $url = $nodes->item(0)->getAttribute("src");
                if ($recipe->photo_url == $url) {
                    $recipe->photo_url = "";
                }
            }
        }
        // Don't save default foodnetwork image.
        if (preg_match("/FN-Facebook-DefaultOGImage/", $recipe->photo_url)) {
            $recipe->photo_url = "";
        }

        // Description
        if (!$recipe->description) {
            $nodes = $xpath->query('//p[contains(concat(" ", normalize-space(@class), " "), " quotation ")]//q');
            foreach ($nodes as $node) {
                $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
                $recipe->description = $line;
            }
        }

        // Categories
        if (!count($recipe->categories)) {
            $nodes = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " categories ")]//ul/li/a');
            foreach ($nodes as $node) {
                $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
                $recipe->appendCategory($line);
            }
        }

        // Source
        if (!$recipe->source) {
            $recipe->source = "Food Network Kitchen";
        }

        return $recipe;
    }

}
