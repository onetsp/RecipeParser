<?php

require_once '../bootstrap.php';

class RecipeParser_Parser__Cookingchanneltvcom_Test extends PHPUnit_Framework_TestCase {

    public function test_chocolate_cake() {
        $path_orig = "data/cookingchanneltv_com_chocolate_chocolate_cake_cooking_channel_curl.html";
        $url = "http://www.cookingchanneltv.com/recipes/emeril-lagasse/chocolate-chocolate-cake.html";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Chocolate, Chocolate Cake', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('16 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(1, count($recipe->instructions[0]['list']));

        $this->assertEquals('', 
                            $recipe->photo_url);
    }

    public function test_pork_tenderloin() {
        $path_orig = "data/cookingchanneltv_com_stuffed_pork_tenderloin_cooking_channel_curl.html";
        $url = "http://www.cookingchanneltv.com/recipes/pork-tenderloin.html";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Pork Tenderloin', $recipe->title);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(70, $recipe->time['cook']);
        $this->assertEquals(90, $recipe->time['total']);
        $this->assertEquals('4 to 6 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(1, count($recipe->instructions[0]['list']));

        $this->assertStringEndsWith("cq5dam.web.616.462.jpeg", $recipe->photo_url);
    }

    public function test_chocolate_croissant() {
        $path_orig = "data/cookingchanneltv_com_chocolate_croissant_bread_pudding_with_bourbon_curl.html";
        $url = "http://www.cookingchanneltv.com/recipes/michael-chiarello/chocolate-croissant-bread-pudding-with-bourbon-ice-cream-sauce.html";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Chocolate Croissant Bread Pudding with Bourbon Ice Cream Sauce', $recipe->title);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(45, $recipe->time['cook']);
        $this->assertEquals(65, $recipe->time['total']);
        $this->assertEquals('6 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));

        $this->assertStringEndsWith('cq5dam.web.616.462.jpeg', $recipe->photo_url);
    }

    public function test_cream_puffs() {
        $path_orig = "data/cookingchanneltv_com_chocolate_cream_puffs_cooking_channel_curl.html";
        $url = "http://www.cookingchanneltv.com/recipes/chocolate-cream-puffs.html";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Chocolate Cream Puffs', $recipe->title);

        $this->assertEquals(60, $recipe->time['prep']);
        $this->assertEquals(25, $recipe->time['cook']);
        $this->assertEquals(85, $recipe->time['total']);
        $this->assertEquals('36 cream puffs', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(10, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(6, count($recipe->instructions[0]['list']));

        $this->assertStringEndsWith('cq5dam.web.616.462.jpeg', $recipe->photo_url);
    }

}
