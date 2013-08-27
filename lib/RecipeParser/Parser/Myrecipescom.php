<?php

class RecipeParser_Parser_Myrecipescom {

    public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataDataVocabulary::parse($html, $url);
        
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($doc);

        // Photo URL, use larger version found on MyRecipes
        $recipe->photo_url = str_replace('-l.jpg', '-x.jpg', $recipe->photo_url);

        // Ingredients
        $recipe->resetIngredients();

        $nodes = $xpath->query('//div[@class="recipeDetails"]/ul');
        foreach ($nodes->item(0)->childNodes as $li) {
            if ($li->nodeName == 'li') {

                $text = RecipeParser_Text::FormatAsOneLine($li->nodeValue);

                if ($li->getAttribute('itemprop') == 'ingredient') {
                    $text = trim(str_replace('$Click to see savings', '', $text));
                    $recipe->appendIngredient($text);
                } else {
                    $text = RecipeParser_Text::formatSectionName($text);
                    $recipe->addIngredientsSection($text);
                }
            }
        }

        // Credits
        $nodes = $xpath->query('//*[@itemprop="author"]');
        if ($nodes->length) {
            $line = trim($nodes->item(0)->nodeValue);
            $recipe->credits = $line;
        }

        return $recipe;
    }

}

?>
