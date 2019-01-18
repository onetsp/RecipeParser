<?php

class RecipeParser_Parser_StructuredData {

    public static function parse($html, $url) {
        $recipe = RecipeParser_Parser_General::parse($html, $url);
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        $nodes = $xpath->query('//script[@type="application/ld+json"]');

        foreach ($nodes as $node) {
            $value = $node->nodeValue;
            $json = json_decode($value, true);
            if (!$json) {
                Log::warning("JSON could not be decoded: " . json_last_error_msg(), __CLASS__);
                continue;
            }

            #echo "\n\nJSON:\n" . print_r($json, true) . "\n";

            // The returned json may be a single recipe, have multiple recipes, or may not 
            // include a recipe at all. Find the right level array in the json object...
            $json = self::getRecipeArray($json);
            if (!$json) {
                continue;
            }

            #echo "\n\nJSON:\n" . print_r($json, true) . "\n";

            // Pick up a title if the general parser didn't find a good one.
            if (strpos($recipe->title, "Recipe from http") !== false) {
                if (isset($json['name'])) {
                    $recipe->title = RecipeParser_Text::formatTitle($json['name']);
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
                        // Each element of "recipeInstructions is a single value (i.e. an instruction)
                        if (!is_array($section)) {
                            $value = $section;
                            $value = strip_tags($value);
                            $value = RecipeParser_Text::stripLeadingNumbers($value);
                            $value = RecipeParser_Text::formatAsOneLine($value);
                            $recipe->appendInstruction( html_entity_decode($value) );
                        
                        // Otherwise, each section contains names and/or elements.
                        } else {

                            if (!empty($section['name'])) {
                                $value = $section['name'];
                                $value = strip_tags($value);
                                $value = RecipeParser_Text::formatSectionName($value);
                                $recipe->addInstructionsSection( html_entity_decode($value) );
                            }
                            if (isset($section['itemListElement']) && is_array($section['itemListElement'])) {
                                foreach ($section['itemListElement'] as $item) {
                                    $value = $item;

                                    // Item may be
                                    // Array
                                    // (
                                    //    [@type] => HowToStep
                                    //    [text] => Heat oven to 425&deg;F.
                                    // )
                                    if (is_array($value) && isset($value['text'])) {
                                        $value = $value['text'];
                                    }
                                    $value = strip_tags($value);
                                    $value = RecipeParser_Text::stripLeadingNumbers($value);
                                    $value = RecipeParser_Text::formatAsOneLine($value);
                                    $recipe->appendInstruction( html_entity_decode($value) );
                                }
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

            break;
        }

        return $recipe;
    }



    public static function getRecipeArray($arr) {
        if (self::isAssoc($arr)) {
            if (self::isSchemaRecipe($arr)) {
                return $arr;
            } else {
                return false;
            }

        } else {
            foreach ($arr as $item) {
                if (self::isSchemaRecipe($item)) {
                    $arr = $item;
                    return $arr;
                }
            }
        }

        return false;
    }

    public static function isAssoc($arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function isSchemaRecipe($arr) {
        if (isset($arr['@context']) && stripos($arr['@context'], 'schema.org') !== false) {
            if (isset($arr['@type']) && strtolower($arr['@type']) == 'recipe') {
                return true;
            }
        }
        return false;
    }

}
