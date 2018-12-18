<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class CookingnytimescomTest extends TestCase {

    public function test_dining_olive_oil_cake() {
        $path = TestUtils::getDataPath("cooking_nytimes_com_blood_orange_olive_oil_cake.html");
        $url = "http://cooking.nytimes.com/recipes/1012443-blood-orange-olive-oil-cake";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(80, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('8 to 10 servings', $recipe->yield);

        $this->assertEquals('Blood Orange Olive Oil Cake', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^To make a honey-blood orange compote/', $recipe->notes);
    }

    public function test_dining_sauerkraut_and_pork() {
        $path = TestUtils::getDataPath("cooking_nytimes_com_braised_sauerkraut_with_lots_of_pork.html");
        $url = "http://cooking.nytimes.com/recipes/1013471-braised-sauerkraut-with-lots-of-pork";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(180, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('6 to 8 servings', $recipe->yield);

        $this->assertEquals('Braised Sauerkraut With Lots of Pork', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(18, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(5, count($recipe->instructions[0]['list']));
    }

    public function test_dining_clay_pot_pork() {
        $path = TestUtils::getDataPath("cooking_nytimes_com_clay_pot_pork.html");
        $url = "http://cooking.nytimes.com/recipes/1014149-clay-pot-pork";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(75, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals('Clay Pot Pork', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(5, count($recipe->instructions[0]['list']));
    }

    public function test_dining_rhubarb_upside_down_cake() {
        $path = TestUtils::getDataPath("cooking_nytimes_com_rhubarb_upside_down_cake.html");
        $url = "http://cooking.nytimes.com/recipes/1013611-rhubarb-upside-down-cake";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(105, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals('Rhubarb Upside-Down Cake', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(13, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(7, count($recipe->instructions[0]['list']));

        $this->assertRegExp(
            '/https.*\/rhubarb-still-superJumbo.jpg/',
            $recipe->photo_url);
    }

    public function test_dining_yellow_layer_cake() {
        $path = TestUtils::getDataPath("cooking_nytimes_com_yellow_layer_cake_with_chocolate_frosting.html");
        $url = "http://cooking.nytimes.com/recipes/1016162-yellow-layer-cake-with-chocolate-frosting";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(105, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('1 9-inch cake', $recipe->yield);

        $this->assertEquals('Yellow Layer Cake With Chocolate Frosting', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(20, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(6, count($recipe->instructions[0]['list']));
    }

}

