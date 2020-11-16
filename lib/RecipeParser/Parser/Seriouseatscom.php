<?php

class RecipeParser_Parser_Seriouseatscom {

    static public function parse($html, $url) {
        $recipe = RecipeParser_Parser_General::parse($html, $url);
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();

        // Remove recipe title intros -- e.g. "Sunday Dinner: Pork Ribs" changes to "Pork Ribs"
        if (strpos($recipe->title, ": ") !== false) {
            $recipe->title = preg_replace("/^[^:]+: (.+)/", "$1", $recipe->title);
        }

        $myxpath->singleNodeLookup('//span[@class="info yield"]', null, "yield", $recipe);

        // Description
        $str = "";
        $nodes = $xpath->query('//*[@class="headnote"]');
        foreach ($nodes as $node) {
            if ($str) {
                $str .= "\n\n";
            }
            $str = $node->nodeValue;
            $str = RecipeParser_Text::formatAsOneLine($str);
        }
        $recipe->description = $str;

        // Times
        $nodes = $xpath->query('.//*[@class="recipe-about"]/li');
        foreach ($nodes as $node) {
            $line = RecipeParser_Text::formatAsOneLine($node->nodeValue);
            if (preg_match("/active time\:\s*(.*)$/i", $line, $m)) {
                $recipe->time['prep'] = RecipeParser_Times::toMinutes($line);
            } else if (preg_match("/total time\:\s*(.*)$/i", $line, $m)) {
                $recipe->time['total'] = RecipeParser_Times::toMinutes($line);
            }
        }

        // Ingredients
        $nodes = $xpath->query('//li[@class="ingredient"]');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            if (RecipeParser_Text::matchSectionName($line)) {
                $recipe->addIngredientsSection(RecipeParser_Text::formatSectionName($line));
            } else {
                $recipe->appendIngredient(RecipeParser_Text::formatAsOneLine($line));
            }
        }

        // Instructions
        $nodes = $xpath->query('//li[@class="recipe-procedure"]/div[@class="recipe-procedure-text"]');
        foreach ($nodes as $node) {
            $line = $node->nodeValue;
            if (RecipeParser_Text::matchSectionName($line)) {
                $recipe->addInstructionsSection(RecipeParser_Text::formatSectionName($line));
            } else {
                $recipe->appendInstruction(RecipeParser_Text::formatAsOneLine($line));
            }
        }

        return $recipe;
    }

}
