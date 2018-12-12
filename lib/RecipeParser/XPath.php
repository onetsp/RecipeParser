<?php

class RecipeParser_XPath {

    private $xpath;

	/**
     * Create a new xpath object from HTML content.
     *
     * @param string HTML content
     */
    public function __construct($html) {
        libxml_use_internal_errors(true);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        $this->xpath = new DOMXPath($doc); 
    }

	public function getXPath() {
		return $this->xpath;
	}

    /**
     * @param string XPath query
     * @param string Node attribute name (or null for node value)
     * @param string Key name in recipe struct (yield, title, )
     * @param reference $recipe struct
     */
    public function singleNodeLookup($query, $attribute=null, $key, &$recipe) {
        $nodes = $this->xpath->query($query);

        if ($nodes->length) {
			
			if ($attribute) {
				$value = $nodes->item(0)->getAttribute($attribute);
			} else {
				$value = $nodes->item(0)->nodeValue;
			}

            switch ($key) {
                case "title":
                    $value = RecipeParser_Text::formatTitle($value);
                    $recipe->title = $value;
                    break;
                case "description":
                    $value = RecipeParser_Text::formatAsOneLine($value);
                    $recipe->description = $value;
                    break;
                case "yield":
                    $value = RecipeParser_Text::formatYield($value);
                    $recipe->yield = $value;
                    break;

                case "time_prep":
					$mins = RecipeParser_Text::iso8601ToMinutes($value);
					if (!$mins) {
						$mins = RecipeParser_Times::toMinutes($value);
					}
					$recipe->time["prep"] = $mins;
					break;
                case "time_cook":
					$mins = RecipeParser_Text::iso8601ToMinutes($value);
					if (!$mins) {
						$mins = RecipeParser_Times::toMinutes($value);
					}
					$recipe->time["cook"] = $mins;
					break;
                case "time_total":
					$mins = RecipeParser_Text::iso8601ToMinutes($value);
					if (!$mins) {
						$mins = RecipeParser_Times::toMinutes($value);
					}
					$recipe->time["total"] = $mins;
					break;

                case "credits":
                    $value = RecipeParser_Text::formatCredits($value);
                    $recipe->credits = $value;
                    break;
                case "photo_url":
                    $recipe->photo_url = $value;
                    break;
            }
        }
	}

    /**
     * Extract all ingredients from a simple list lookup where each ingredient and section name
     * is contained at the same level of DOM nodes. E.g. they are all contained in <li>s within 
     * a single matching <ul>.
     *
     * @param string XPath query
     * @param reference $recipe struct
     */
    public function simpleIngredientsListLookup($query, &$recipe) {
        $nodes = $this->xpath->query($query);

        $recipe->resetIngredients();
        foreach ($nodes as $node) {
            $value = RecipeParser_Text::formatAsOneLine($node->nodeValue);
            if (RecipeParser_Text::matchSectionName($value)) {
                $value = RecipeParser_Text::formatSectionName($value);
                $recipe->addIngredientsSection($value);
            } else {
                $recipe->appendIngredient($value);
            }
        }
	}

    /**
     * Extract all instructions from a simple list lookup where each step and section name
     * is contained at the same level of DOM nodes. E.g. they are all contained in <li>s within 
     * a single matching <ul>.
     *
     * @param string XPath query
     * @param reference $recipe struct
     */
    public function simpleInstructionsListLookup($query, &$recipe) {
        $nodes = $this->xpath->query($query);

        $recipe->resetInstructions();
        foreach ($nodes as $node) {
            $value = RecipeParser_Text::formatAsOneLine($node->nodeValue);
            $value = RecipeParser_Text::stripLeadingNumbers($value);
			if (RecipeParser_Text::matchSectionName($value)) {
				$value = RecipeParser_Text::formatSectionName($value);
				$recipe->addInstructionsSection($value);
			} else {
				$recipe->appendInstruction($value);
			}
        }
    }

}
