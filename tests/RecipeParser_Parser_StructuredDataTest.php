<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class StructuredDataTest extends TestCase {

    public function test_read_json_single_step_instructions() {
        $path = TestUtils::getDataPath("structured_data_almond_cheddar.html");
        $url = "http://www.example.com/recipes/almond-cheddar.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Almond Cheddar Appetizers', $recipe->title, "Title.");
        $this->assertEquals($url, $recipe->url, "Url.");
        $this->assertEquals("Taste of Home", $recipe->credits);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(10, $recipe->time['cook']);
        $this->assertEquals(25, $recipe->time['total']);

        $this->assertEquals("about 4 dozen", $recipe->yield);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions[0]['list']));
        $this->assertRegExp('/^In a bowl.*until bubbly\.$/', $recipe->instructions[0]['list'][0]);
    }

    public function test_read_json_multi_step_instructions() {
        $path = TestUtils::getDataPath("structured_data_multi_step_almond_cheddar.html");
        $url = "http://www.example.com/recipes/almond-cheddar.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(3, count($recipe->instructions));
        $this->assertEquals(2, count($recipe->instructions[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[1]['list']));
        $this->assertEquals(2, count($recipe->instructions[2]['list']));

        $this->assertEquals("", $recipe->instructions[0]['name']);
        $this->assertEquals("Add the filling", $recipe->instructions[1]['name']);
        $this->assertEquals("Bake", $recipe->instructions[2]['name']);
    }

    public function test_read_json_multi_schema_objects() {
        $path = TestUtils::getDataPath("structured_data_multiple_objects.html");
        $url = "http://www.example.com/recipes/almond-cheddar.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Almond Cheddar Appetizers', $recipe->title, "Title.");
    }

    public function test_read_image_in_json() {
        $path = TestUtils::getDataPath("structured_data_multiple_objects.html");
        $url = "http://www.example.com/recipes/almond-cheddar.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('https://cdn-image.myrecipes.com/sites/default/files/image.jpg', $recipe->photo_url, "Photo URL.");
    }
}
