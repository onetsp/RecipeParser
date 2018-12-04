<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class MicrodataDataVocabularyTest extends TestCase {

    public function test_datavocabulary_recipe() {
        $path = "data/datavocabulary_spec.html";
        $url = "http://data-vocabulary.example.com/recipes/spec";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Grandma's Holiday Apple Pie", $recipe->title);
        $this->assertEquals("Carol Smith", $recipe->credits);

        $this->assertRegexp("/^This is my .* dash of nutmeg.$/s", $recipe->description);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(90, $recipe->time['total']);
        $this->assertEquals('1 9" pie (8 servings)', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(4, count($recipe->ingredients[0]['list']));
        $this->assertEquals("Thinly-sliced apples: 6 cups", $recipe->ingredients[0]['list'][0]);
        $this->assertEquals("White sugar: 3/4 cup", $recipe->ingredients[0]['list'][1]);
        $this->assertEquals("1 cup flour", $recipe->ingredients[0]['list'][2]);
        $this->assertEquals("1/2 cup apples, diced", $recipe->ingredients[0]['list'][3]);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertEquals("Cut and peel apples.", $recipe->instructions[0]['list'][0]);
        $this->assertEquals("Mix sugar and cinnamon. Use additional sugar for tart apples.",
            $recipe->instructions[0]['list'][1]);
        $this->assertEquals("Bake pie in oven.", $recipe->instructions[0]['list'][2]);

        $this->assertEquals('http://data-vocabulary.example.com/recipes/apple-pie.jpg', 
                            $recipe->photo_url);
    }

    public function test_schema_spec_class_instruction() {
        $path = "data/datavocabulary_spec_class_instruction.html";

        $recipe = RecipeParser::parse(file_get_contents($path));
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertRegexp("/^Heat oven .* flour.$/", $recipe->instructions[0]['list'][0]);
        $this->assertRegexp("/^Place both .* hour.$/", $recipe->instructions[0]['list'][2]);
    }

    public function test_datavocabulary_spec_instructions_li() {
        $path = "data/datavocabulary_spec_instructions_li.html";

        $recipe = RecipeParser::parse(file_get_contents($path));
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertRegexp("/^Preheat the oven .* cloves intact.$/", $recipe->instructions[0]['list'][0]);
        $this->assertRegexp("/^Meanwhile, in a .* soaking liquid.$/", $recipe->instructions[0]['list'][1]);
        $this->assertRegexp("/^In a large .* to a bowl.$/", $recipe->instructions[0]['list'][2]);
    }

}
