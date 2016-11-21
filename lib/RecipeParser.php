<?php

class RecipeParser {

    static private $registered_parsers = null;
    static private $parsers_ini_file_relpath = "RecipeParser/Parser/parsers.ini";

    const SCHEMA_SPEC              = "MicrodataSchema";
    const DATA_VOCABULARY_SPEC     = "MicrodataDataVocabulary";
    const RDF_DATA_VOCABULARY_SPEC = "MicrodataRdfDataVocabulary";
    const MICROFORMAT_SPEC         = "Microformat";
    const MICROFORMAT_V2_SPEC      = "MicroformatV2";
    const JSON_LD                  = "MicrodataJsonLd";

    /**
     * Load registered parsers from ini file.
     */
    static public function registerParsers() {
        if (!self::$registered_parsers) {
            $parsers_ini_file = dirname(__FILE__) . "/" . self::$parsers_ini_file_relpath;
            self::$registered_parsers = parse_ini_file($parsers_ini_file, true);
        }
    }

    /**
     * Return the "parser" attribute for the registered custom parser
     * that matches the given URL.
     *
     * @param string URL
     * @return string
     */
    static public function getMatchingParser($url) {
        self::registerParsers();

        // Extract hostname from URL.
        $hostname = strtolower(parse_url($url, PHP_URL_HOST));
        if (stripos($hostname, 'www.') === 0) {
            $hostname = substr($hostname, 4);
        }

        // Try to match hostname to a registered parser.
        foreach (self::$registered_parsers as $name => $parser) {
            $pattern = $parser['pattern'];
            if (strpos($pattern, '/') === 0) {   // pattern is regex
                if (preg_match($pattern, $hostname)) {
                    return $parser['parser'];
                }
            } else {   // pattern is string match.
                if ($hostname == $pattern) {
                    return $parser['parser'];
                }
            }
        }

        return null;
    }

    /**
     * Search HTML for a microdata type (data-vocabulary or schema) and
     * return an import parser name.
     *
     * @param Reference to HTML
     * @return string Name of matching parser (or null)
     */
    static public function matchMarkupFormat(&$html) {
        if (stripos($html, "ld+json") !== false && preg_match('/"@type"\s*:\s*"Recipe"/i', $html) !== false) {
            return self::JSON_LD;
        }
        else if (stripos($html, "schema.org/Recipe") !== false) {
            return self::SCHEMA_SPEC;
        }
        else if (stripos($html, "//data-vocabulary.org/Recipe") !== false) {
            return self::DATA_VOCABULARY_SPEC;
        }
        else if (stripos($html, "//rdf.data-vocabulary.org/") !== false && stripos($html, 'typeof="v:Recipe"') !== false) {
            return self::RDF_DATA_VOCABULARY_SPEC;
        }
        else if (stripos($html, "hrecipe") !== false && strpos($html, "fn") !== false) {
            return self::MICROFORMAT_SPEC;
        }
        else if (stripos($html, "h-recipe") !== false && strpos($html, "p-name") !== false) {
            return self::MICROFORMAT_V2_SPEC;
        }
        else {
            return null;
        }
    }

    /**
     * Parse recipe data from an HTML document, returning a data structure that
     * contains structured data about the recipe.
     * @param DomDocument $doc
     * @param string $url
     *
     * @throws NoMatchingParserException
     * @return object RecipeParser_Recipe
     */
    static public function parse(DOMDocument $doc, $url=null) {
        $html = $doc->saveHTML();
        $parser = null;

        // Search for a registered parser that matches the URL.
        if (!$parser && $url) {
            $parser = self::getMatchingParser($url);
        }

        // Search for a microdata parser (data-vocabulary.org or schema.org) based
        // upon patterns in the HTML contents.
        if (!$parser) {
            $parser = self::matchMarkupFormat($html);
        }

        // If we haven't found a matching parser, bail out.
        if (!$parser) {
            throw new NoMatchingParserException("No matching parser found for URL: $url");
        }

        // Initialize the right parser and run it.
        $classname = 'RecipeParser_Parser_' . $parser;
        $recipe = $classname::parse($doc, $url);
        $recipe->url = $url;
        
        return $recipe;
	}

}
