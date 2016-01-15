<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_RecipecomTest extends PHPUnit_Framework_TestCase {

    public function test_chicken_tortilla_casserole() {
        $path = "data/recipe_com_quot_healthified_quot_chicken_tortilla_casserole_curl.html";
        $url = "http://www.recipe.com/healthified-chicken-tortilla-casserole/";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('"Healthified" Chicken Tortilla Casserole', $recipe->title);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(75, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.recipe.com/images/healthified-chicken-tortilla-casserole-6e9ac8fb-ff8d-4234-bfcb-c748c5918672-ss.jpg', 
                            $recipe->photo_url);
    }

    public function test_hamburger_secret_sauce() {
        $path = "data/recipe_com_hot_dog_hamburger_secret_sauce_com_curl.html";
        $url = "http://www.recipe.com/hot-dog-hamburger-secret-sauce/";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Hot Dog-Hamburger Secret Sauce', $recipe->title);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);  // was '60' but has disappeared from the test recipe
        $this->assertEquals('5 cups', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(10, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertRegexp("/fitted with a shredding blade/", $recipe->instructions[0]['list'][0]);

        $this->assertEquals('http://www.recipe.com/images/hot-dog-hamburger-secret-sauce-R123504-ss.jpg', 
                            $recipe->photo_url);
    }

    public function test_one_bowl_chocolate_cake() {
        $path = "data/recipe_com_one_bowl_chocolate_cake_com_curl.html";
        $url = "http://www.recipe.com/one-bowl-chocolate-cake/";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('One-Bowl Chocolate Cake', $recipe->title);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);  // was '110' but has disappeared from the test recipe
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));  // strange "null" entry showed up.

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.recipe.com/images/one-bowl-chocolate-cake-R073295-ss.jpg', 
                            $recipe->photo_url);
    }

}
