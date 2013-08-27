<?php

require_once './bootstrap.php';

class RecipeParser_Test extends PHPUnit_Framework_TestCase {

    function test_match_schema_markup() {
        $html = file_get_contents("data/schema_spec.html");
        $type = RecipeParser::matchMarkupFormat($html);
        $this->assertEquals(RecipeParser::SCHEMA_SPEC, $type);


print_r(get_included_files());
    }

    function test_match_datavocabulary_markup() {
        $html = file_get_contents("data/datavocabulary_spec.html");
        $type = RecipeParser::matchMarkupFormat($html);
        $this->assertEquals(RecipeParser::DATA_VOCABULARY_SPEC, $type);
    }

    function test_match_microformat_markup() {
        $html = file_get_contents("data/microformat_spec.html");
        $type = RecipeParser::matchMarkupFormat($html);
        $this->assertEquals(RecipeParser::MICROFORMAT_SPEC, $type);
    }

    function test_null_match_markup() {
        $html = file_get_contents("data/no_semantic_markup.html");
        $type = RecipeParser::matchMarkupFormat($html);
        $this->assertEquals(null, $type);
    }

}

?>
