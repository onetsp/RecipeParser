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
     * @param string Key name in recipe struct (yield, title, )
     * @param reference $recipe struct
     */
    public function singleNodeLookup($query, $key, &$recipe) {

        $nodes = $this->xpath->query($query);
        if ($nodes->length) {
            $value = $nodes->item(0)->nodeValue;

            switch ($key) {
                case "yield":
                    $value = RecipeParser_Text::formatYield($value);
                    $recipe->yield = $value;
                    break;
                case "title":
                    $value = RecipeParser_Text::formatTitle($value);
                    $recipe->title = $value;
                    break;
            }
        }

    }

}
