<?php

class RecipeParser_Parser_MicrodataJsonLd {

    static public function parse(DOMDocument $doc, $url) {
        $recipe = new RecipeParser_Recipe();
        $xpath = new DOMXPath($doc);
        
        $jsonScripts = $xpath->query('//script[@type="application/ld+json"]');
        $json = trim( $jsonScripts->item(0)->nodeValue );
        $json = RecipeParser_Text::cleanJson($json);
        $data = json_decode( $json );
        
        // Bail JSON-LD not marked up properly
        if (!$data || !property_exists($data, "@context") || stripos($data->{'@context'}, "schema.org") === false ) {
            return $recipe;
        }
        // Bail if no recipe in the markup
        if (!property_exists($data, "@type") || $data->{'@type'} != 'Recipe') {
            return $recipe;
        }
        
        // Parse elements:
        
        // Title
        if (property_exists($data, "name")) {
            $name = $data->name;
            $recipe->title = RecipeParser_Text::formatTitle($name);
        }
    
        // Summary
        if (property_exists($data, "description")) {
            $summary = $data->description;
            $recipe->description = RecipeParser_Text::formatAsParagraphs($summary);;
        }
    
        // Times
        if (property_exists($data, "prepTime")) {
            $prepTime = $data->prepTime;
            $recipe->time['prep'] = RecipeParser_Text::formatISO_8601($prepTime);;
        }
        if (property_exists($data, "cookTime")) {
            $cookTime = $data->cookTime;
            $recipe->time['cook'] = RecipeParser_Text::formatISO_8601($cookTime);;
        }
        if (property_exists($data, "totalTime")) {
            $totalTime = $data->totalTime;
            $recipe->time['total'] = RecipeParser_Text::formatISO_8601($totalTime);;
        }
    
        // Yield
        if (property_exists($data, "recipeYield")) {
            $recipeYield = $data->recipeYield;
            $recipe->yield = RecipeParser_Text::formatAsParagraphs($recipeYield);;
        }
    
        // Ingredients
        $ingredients = property_exists($data, "recipeIngredient") ? $data->recipeIngredient : false;
        if (!$ingredients) {
            $ingredients = property_exists($data, "ingredients") ? $data->ingredients : [];
        }
        
        foreach ($ingredients as $ingredient) {
            $ingredient = RecipeParser_Text::formatAsOneLine($ingredient);
            if (empty($ingredient)) {
                continue;
            }
            if (strlen($ingredient) > 150) {
                // probably a mistake, like a run-on of existing ingredients?
                continue;
            }
            if (RecipeParser_Text::matchSectionName($ingredient)) {
                $ingredient = RecipeParser_Text::formatSectionName($ingredient);
                $recipe->addIngredientsSection($ingredient);
            } else {
                $recipe->appendIngredient($ingredient);
            }
        }
    
        // Photo
        if (property_exists($data, "image")) {
            $photo_url = $data->image;
            if (is_array($photo_url)) {
                $first_element = array_values($photo_url)[0];
                if ($first_element) {
                    if (property_exists($first_element, "url")) {
                        $photo_url = $first_element->url;
                    }
                }
            }
            if (is_object($photo_url)) {
                $photo_url = $data->image->url;
            }
            $recipe->photo_url = RecipeParser_Text::relativeToAbsolute($photo_url, $url);
        }
    
        // Credits
        if (property_exists($data, "author")) {
            if (property_exists($data->author, "name")) {
                $recipe->credits = RecipeParser_Text::formatCredits($data->author->name);
            } else {
                $recipe->credits = RecipeParser_Text::formatCredits($data->author);
            }
        }
        
        return $recipe;
    }

}
