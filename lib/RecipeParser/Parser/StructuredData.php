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

            // Is the top-level an assoc array, or is this a list of recipes or list of other
            // Schema objects?
            if (!self::isAssoc($json)) {
                foreach ($json as $item) {
                    if (self::isSchemaRecipe($item)) {
                        $json = $item;
                        break;
                    }
                }
            } else {
                if (!self::isSchemaRecipe($json)) {
                    // This is an assoc array but doesn't look like a Recipe.
                    return;
                }
            }

            if (isset($json['prepTime'])) {
                $recipe->time['prep'] = RecipeParser_Text::iso8601ToMinutes($json['prepTime']);
            }
            if (isset($json['cookTime'])) {
                $recipe->time['cook'] = RecipeParser_Text::iso8601ToMinutes($json['cookTime']);
            }
            if (isset($json['totalTime'])) {
                $recipe->time['total'] = RecipeParser_Text::iso8601ToMinutes($json['totalTime']);
            }

            if (isset($json['recipeYield'])) {
                $value = RecipeParser_Text::formatYield($json['recipeYield']);
                $recipe->yield = html_entity_decode($value);
            }

            if (isset($json['author']) && isset($json['author']['name'])) {
                $value = $json['author']['name'];
                $recipe->credits = html_entity_decode($value);
            }

            if (isset($json['recipeIngredient']) && is_array($json['recipeIngredient'])) {
                foreach ($json['recipeIngredient'] as $value) {
                    $value = strip_tags($value);
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

            if (isset($json['recipeInstructions'])) {
                // Single-line of text for instructions?
                if (!is_array($json['recipeInstructions'])) {
                    $value = $json['recipeInstructions'];
                    $value = strip_tags($value);
                    $value = RecipeParser_Text::formatAsOneLine($value);
                    $recipe->appendInstruction( html_entity_decode($value) );
                
                // Multi-line instructions and sections
                } else if (is_array($json['recipeInstructions'])) {
                    foreach ($json['recipeInstructions'] as $section) {
                        if (!empty($section['name'])) {
                            $value = $section['name'];
                            $value = strip_tags($value);
                            $value = RecipeParser_Text::formatSectionName($value);
                            $recipe->addInstructionsSection( html_entity_decode($value) );
                        }
                        if (isset($section['itemListElement']) && is_array($section['itemListElement'])) {
                            foreach ($section['itemListElement'] as $item) {
                                $value = $item;
                                $value = strip_tags($value);
                                $value = RecipeParser_Text::stripLeadingNumbers($value);
                                $value = RecipeParser_Text::formatAsOneLine($value);
                                $recipe->appendInstruction( html_entity_decode($value) );
                            }
                        }
                    }
                }
            }

            if (!$recipe->photo_url) {
                if (isset($json['image'])) {
                    if (isset($json['image']['url'])) {
                        $photo_url = $json['image']['url'];
                        if (preg_match("/\.(jpeg|jpg)/i", $photo_url)) {
                            $photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
                            $recipe->photo_url = $photo_url;
                        }
                    }
                }
            }
        }

        return $recipe;
    }

    static public function isAssoc($arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    static public function isSchemaRecipe($arr) {
        if (isset($arr['@context']) && stripos($arr['@context'], '//schema.org') !== false) {
            if (isset($arr['@type']) && strtolower($arr['@type']) == 'recipe') {
                return true;
            }
        }
        return false;
    }

}
