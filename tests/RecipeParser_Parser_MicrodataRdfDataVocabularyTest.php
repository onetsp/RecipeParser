<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class MicrodataRdfDataVocabularyTest extends TestCase {

    public function test_rdf_datavocabulary_recipe() {
        $path = TestUtils::getDataPath("rdf_datavocabulary_spec.html");
        $url = "http://rdf.data-vocabulary.example.com/recipes/spec";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Grandma's Holiday Apple Pie", $recipe->title);

        $this->assertRegexp("/^This is my .* dash of nutmeg.$/s", $recipe->description);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(90, $recipe->time['total']);
        $this->assertEquals('1 9" pie (8 servings)', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(2, count($recipe->ingredients[0]['list']));
        $this->assertEquals("Thinly-sliced apples: 6 cups", $recipe->ingredients[0]['list'][0]);
        $this->assertEquals("White sugar: 3/4 cup", $recipe->ingredients[0]['list'][1]);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertEquals("Cut and peel apples.", $recipe->instructions[0]['list'][0]);
        $this->assertEquals("Mix sugar and cinnamon. Use additional sugar for tart apples.",
            $recipe->instructions[0]['list'][1]);
        $this->assertEquals("Bake pie in oven.", $recipe->instructions[0]['list'][2]);

        $this->assertEquals('http://rdf.data-vocabulary.example.com/recipes/apple-pie.jpg', 
                            $recipe->photo_url);
    }

    public function test_rdf_datavocabulary_spec_property_instruction() {
        $path = TestUtils::getDataPath("rdf_datavocabulary_spec_property_instruction.html");
        $url = "http://rdf.data-vocabulary.example.com/recipes/spec";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));
        $this->assertRegexp("/^Preheat oven .* spray.$/", $recipe->instructions[0]['list'][0]);
        $this->assertRegexp("/^Bake the cake on/", $recipe->instructions[0]['list'][5]);
    }

    public function test_rdf_datavocabulary_spec_sub_nodes() {
        $path = TestUtils::getDataPath("rdf_datavocabulary_spec_sub_nodes.html");
        $url = "http://rdf.data-vocabulary.example.com/recipes/spec";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));
        $this->assertRegExp("/^Put the cooked and cooled rice/", $recipe->instructions[0]['list'][3]);
    }
}
