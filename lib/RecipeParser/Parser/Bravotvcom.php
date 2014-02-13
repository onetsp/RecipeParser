<?php

class RecipeParser_Parser_Bravotvcom {

    static public function parse($html, $url) {

        $recipe = new RecipeParser_Recipe();

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Title
        $nodes = $xpath->query('//h3[@class = "title"]');
        if ($nodes->length) {
            $value = trim($nodes->item(0)->nodeValue);
            $recipe->title = $value;
        }

        // Cook times
        $nodes = $xpath->query('//div[@class = "recipe-metadata"]/ul/li');
        foreach ($nodes as $node) {

            $sub_nodes = $node->childNodes;
            $key = null;
            $value = null;
            foreach ($sub_nodes as $sub_node) {
                if ($sub_node->nodeName == 'h5') {
                    $key = trim($sub_node->nodeValue);
                }
                if ($sub_node->nodeName == 'p') {
                    $value = trim($sub_node->nodeValue);
                }
            }
            // Inspect keys/values we've found.
            if ($key == 'Total Time:') {
                $value = self::cleanupTime($value);
                $recipe->time['total'] = RecipeParser_Times::toMinutes($value);
            }
            if ($key == 'Prep Time:') {
                $value = self::cleanupTime($value);
                $recipe->time['prep'] = RecipeParser_Times::toMinutes($value);
            }
        }


        $node_list = $xpath->query('//dd[@class = "preptime"]');
        if ($node_list->length) {
            $value = $node_list->item(0)->nodeValue;
            $recipe->time['prep'] = RecipeParser_Times::toMinutes($value);
        }
        $node_list = $xpath->query('//dd[@class = "cooktime"]');
        if ($node_list->length) {
            $value = $node_list->item(0)->nodeValue;
            $recipe->time['cook'] = RecipeParser_Times::toMinutes($value);
        }
        $node_list = $xpath->query('//dd[@class = "duration totaltime special"]');
        if ($node_list->length) {
            $value = $node_list->item(0)->nodeValue;
            $recipe->time['total'] = RecipeParser_Times::toMinutes($value);
        }

        // Ingredients, Yield, Description, Notes, etc.
        $nodes = $xpath->query('//div[@class = "recipe-body"]/*');
        $section_title = null;
        foreach ($nodes as $node) {

            // Section titles
            if ($node->nodeName == 'h4') {
                $value = $node->nodeValue;
                $value = trim(strtolower($value));
                $section_title = $value;
                continue;
            }

            $in_section = false;
            if ($node->nodeName == 'div') {

                // Ensure that we're in a <div class="section"> node.
                foreach ($node->attributes as $attr_name => $attr_node) {
                    if ($attr_name == 'class' && $attr_node->value == 'section') {
                        $in_section = true;
                    }
                }
                if (!$in_section) {
                    continue;
                }

                // Description should be first text, before any section titles.
                if (!$section_title) {
                    $value = $node->nodeValue;
                    $value = preg_replace("/^(Drink\:|Top Chef).*$/m", '', $value);
                    $value = str_replace("\n\n", "\n", $value);
                    $value = trim($value);
                    $recipe->description = $value;
                
                // Yield
                } else if ($section_title == 'yield') {
                    $value = trim($node->nodeValue);
                    $recipe->yield = $value;

                // Notes
                } else if ($section_title == 'notes') {
                    $value = trim($node->nodeValue);
                    $value = str_replace("\n\n", "\n", $value);
                    $recipe->notes = $value;
                
                // Ingredients
                } else if ($section_title == 'ingredients') {
                    $sub_nodes = $node->childNodes;
                    foreach ($sub_nodes as $sub_node) {
                        if ($sub_node->nodeName == 'h5') {
                            $value = RecipeParser_Text::formatSectionName($sub_node->nodeValue);
                            $recipe->addIngredientsSection($value);
                        } else if ($sub_node->nodeName == 'ul') {
                            $li_nodes = $sub_node->childNodes;
                            foreach ($li_nodes as $li_node) {
                                $value = trim($li_node->nodeValue);
                                $recipe->appendIngredient($value);
                            }
                        }
                    }
                
                // Instructions
                } else if ($section_title == 'directions') {
                    $sub_nodes = $node->childNodes;
                    foreach ($sub_nodes as $sub_node) {
                        $value = trim($sub_node->nodeValue);
                        // Section titles appear in all-caps.
                        if ($value && ($value == strtoupper($value) || preg_match('/:$/', $value))) {
                            $value = RecipeParser_Text::formatSectionName($value);
                            $recipe->addInstructionsSection($value);

                        } else {
                            $value = RecipeParser_Text::stripLeadingNumbers($value);
                            $recipe->appendInstruction($value);
                        }
                    }
                }
            }

        }

        // Source / Chef
        $nodes = $xpath->query('//div[@class = "recipe-sidebar"]/div/*');
        $section_title = null;
        $chef_name = null;
        $show_name = 'Bravo TV';
        foreach ($nodes as $node) {
            if ($node->nodeName == 'h4') {
                $value = trim($node->nodeValue);
                $section_title = strtolower($value);
                continue;
            }
            if ($node->nodeName == 'small') {
                if ($section_title == 'chef' || $section_title == 'author') {
                    $value = trim($node->nodeValue);
                    $chef_name = $value;
                    break;
                }
            }
        }
        $nodes = $xpath->query('//div[@class = "section"]/p[1]');
        if ($nodes->length) {
            $value = $nodes->item(0)->nodeValue;
            if (strpos($value, 'Top Chef Masters') !== false) {
                $show_name = 'Top Chef Masters';
            } else if (strpos($value, 'Top Chef') !== false) {
                $show_name = 'Top Chef';
            }
        }
        $recipe->credits = $chef_name . ', ' . $show_name;

        $nodes = $xpath->query('//div[@class = "recipe-header clearfix"]//img');
        if ($nodes->length) {
            $photo_url = $nodes->item(0)->getAttribute('src');
            $photo_url = str_replace('/medium/', '/original/', $photo_url);
            $recipe->photo_url = RecipeParser_Text::formatPhotoUrl($photo_url, $url);
        }

        return $recipe;
    }


    static public function cleanupTime($str) {
        $find = array('&nbsp;', 'under', 'one', 'two', 'three', 'four', 'five',
                      'six', 'seven', 'eight', 'nine', 'ten', '  ');
        $replace = array(' ', '', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ' ');

        $str = strtolower($str);
        $str = str_replace($find, $replace, $str);
        $str = trim($str);
        return $str;
    }

}
