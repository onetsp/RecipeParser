<?php

class RecipeParser_Parser_StructuredData {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_General::parse($html, $url);
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();


        $nodes = $xpath->query('//script[@type="application/ld+json"]');
        if ($nodes->length) {
            $value = $nodes->item(0)->nodeValue;
            $json = json_decode($value, true);

            if ($json['prepTime']) {
                $recipe->time['prep'] = RecipeParser_Text::iso8601ToMinutes($json['prepTime']);
            }
            if ($json['cookTime']) {
                $recipe->time['cook'] = RecipeParser_Text::iso8601ToMinutes($json['cookTime']);
            }
            if ($json['totalTime']) {
                $recipe->time['total'] = RecipeParser_Text::iso8601ToMinutes($json['totalTime']);
            }

            if ($json['recipeYield']) {
                $value = RecipeParser_Text::formatYield($json['recipeYield']);
                $recipe->yield = html_entity_decode($value);
            }

            if ($json['author'] && $json['author']['name']) {
                $value = $json['author']['name'];
                $recipe->credits = html_entity_decode($value);
            }

            if ($json['recipeIngredient']) {
                foreach ($json['recipeIngredient'] as $value) {
                    if (RecipeParser_Text::matchSectionName($value)) {
                        $value = RecipeParser_Text::formatSectionName($value);
                        $recipe->addIngredientsSection( html_entity_decode($value) );
                    } else {
                        $value = RecipeParser_Text::formatAsOneLine($value);
                        $recipe->appendIngredient( html_entity_decode($value) );
                    }
                }
            }
            
            //echo "\n----JSON---\n" . print_r($json, true) . "\n----END----\n";

            // Single-line of text for instructions?
            if ($json['recipeInstructions'] && !is_array($json['recipeInstructions'])) {
                $value = RecipeParser_Text::formatAsOneLine($json['recipeInstructions']);
                $recipe->appendInstruction( html_entity_decode($value) );
            
            // Multi-line instructions and sections
            } else if ($json['recipeInstructions'] && is_array($json['recipeInstructions'])) {
                foreach ($json['recipeInstructions'] as $section) {
                    if (!empty($section['name'])) {
                        $value = RecipeParser_Text::formatSectionName($section['name']);
                        $recipe->addInstructionsSection( html_entity_decode($value) );
                    }
                    if (isset($section['itemListElement']) && is_array($section['itemListElement'])) {
                        foreach ($section['itemListElement'] as $item) {
                            $value = RecipeParser_Text::stripLeadingNumbers($item['text']);
                            $value = RecipeParser_Text::formatAsOneLine($value);
                            $recipe->appendInstruction( html_entity_decode($value) );
                        }
                    }
                }
            }
        }

        return $recipe;
    }

}
