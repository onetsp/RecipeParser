<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class EatingwellcomTest extends TestCase {

    public function test_apple_cranberry_cake() {
        $path = TestUtils::getDataPath("eatingwell_com_apple_cranberry_upside_down_cake_curl.html");
        $url = "http://www.eatingwell.com/recipes/apple_cranberry_upside_down_cake.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Apple-Cranberry Upside-Down Cake', $recipe->title);
        $this->assertRegExp("/^Rows of sweet apple slices/", $recipe->description);
        $this->assertEquals('EatingWell', $recipe->credits);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(75, $recipe->time['total']);
        $this->assertEquals('9 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(7, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/no-recipe-image.jpg/', $recipe->photo_url);
    }

    public function test_chocolate_pumpkin_bundt() {
        $path = TestUtils::getDataPath("eatingwell_com_glazed_chocolate_pumpkin_bundt_cake_curl.html");
        $url = "http://www.eatingwell.com/recipes/glazed_chocolate_pumpkin_bundt_cake.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Glazed Chocolate-Pumpkin Bundt Cake', $recipe->title);
        $this->assertRegExp("/^You don\'t have to have pumpkin/", $recipe->description);
        $this->assertEquals('EatingWell', $recipe->credits);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(210, $recipe->time['total']);
        $this->assertEquals('16 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(19, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/standard\/recipes\/DS5533.JPG/', $recipe->photo_url);
    }

    public function test_scandinavian_pickled_herring_bites() {
        $path = TestUtils::getDataPath("eatingwell_com_scandinavian_pickled_herring_bites_curl.html");
        $url = "http://www.eatingwell.com/recipes/pickled_herring_bites.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Scandinavian Pickled Herring Bites', $recipe->title);
        $this->assertRegExp("/^This Scandinavian.*served on\.$/", $recipe->description);
        $this->assertEquals('EatingWell', $recipe->credits);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(30, $recipe->time['total']);
        $this->assertEquals('40 pieces', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/standard\/recipes\/AP7435.JPG/', $recipe->photo_url);
    }

}
