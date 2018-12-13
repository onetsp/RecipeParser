<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class TasteofhomecomTest extends TestCase {

    public function test_cheddar_appetizers() {
        $path = TestUtils::getDataPath("tasteofhome_com_almond_cheddar_appetizers.html");
        $url = "http://www.tasteofhome.com/Recipes/Almond-Cheddar-Appetizers";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Almond Cheddar Appetizers', $recipe->title);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(10, $recipe->time['cook']);
        $this->assertEquals(25, $recipe->time['total']);
        $this->assertEquals('about 4 dozen', $recipe->yield);

        $this->assertEquals(7, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
        $this->assertRegExp('/or until bubbly./', $recipe->instructions[0]['list'][0]);

        $this->assertEquals(
            'https://www.tasteofhome.com/wp-content/uploads/2017/10/Almond-Cheddar-Appetizers_exps7647_MRR133247B07_31_1b_RMS-1.jpg',
            $recipe->photo_url);
    }

    public function test_artichoke_chicken() {
        $path = TestUtils::getDataPath("tasteofhome_com_artichoke_chicken.html");
        $url = "http://www.tasteofhome.com/Recipes/Artichoke-Chicken";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Artichoke Chicken', $recipe->title);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(50, $recipe->time['cook']);
        $this->assertEquals(65, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
        $this->assertRegExp('/^In a large skillet.*thermometer inserted in the chicken.*/', $recipe->instructions[0]['list'][0]);
    }

    public function test_lemon_berry_shortcake() {
        $path = TestUtils::getDataPath("tasteofhome_com_lemon_berry_shortcake.html");
        $url = "http://www.tasteofhome.com/Recipes/Lemon-Berry-Shortcake";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Lemon-Berry Shortcake', $recipe->title);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(20, $recipe->time['cook']);
        $this->assertEquals(50, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));
        $this->assertEquals(5, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Topping', $recipe->ingredients[1]['name']);
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
    }

}

