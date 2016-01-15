<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_RealsimplecomTest extends PHPUnit_Framework_TestCase {

    public function test_paprika_spiced_pork_chops() {
        $path = "data/realsimple_com_paprika_spiced_pork_chops_with_spinach_curl.html";
        $url = "http://www.realsimple.com/food-recipes/browse-all-recipes/paprika-spiced-pork-chops-recipe-00000000029765/index.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Paprika-Spiced Pork Chops With Spinach Sauté', $recipe->title);
        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(25, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals('1 tablespoon fresh lemon juice', $recipe->ingredients[0]['list'][7]);
        $this->assertEquals(8, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertRegExp("/^http.*images\/1003\/dinner-paprika-pork_300.jpg.*$/",
            $recipe->photo_url);
    }

    public function test_yellow_cake() {
        $path = "data/realsimple_com_yellow_cake_with_vanilla_frosting_and_curl.html";
        $url = "http://www.realsimple.com/food-recipes/browse-all-recipes/yellow-cake-vanilla-frosting-00000000057748/index.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Yellow Cake With Vanilla Frosting and White Chocolate Chips', $recipe->title);
        $this->assertEquals(60, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(150, $recipe->time['total']);
        $this->assertEquals('12 servings', $recipe->yield);
        
        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals('1 recipe Vanilla Frosting', $recipe->ingredients[0]['list'][9]);
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(5, count($recipe->instructions[0]['list']));
        $this->assertRegExp("/^Transfer one of the cooled cakes/", $recipe->instructions[0]['list'][4]);
    }

}
