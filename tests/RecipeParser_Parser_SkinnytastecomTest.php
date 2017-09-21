<?php

require_once "../bootstrap.php";

class RecipeParser_Parser_Skinnytastecom_Test extends PHPUnit_Framework_TestCase {

    public function test_homemade_skinny_chocolate_cake() {
        $path = "data/skinnytaste_com_homemade_skinny_chocolate_cake_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2012/02/homemade-skinny-chocolate-cake.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Homemade Skinny Chocolate Cake', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(8, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^http:\/\/www.skinnyt.*\.jpg$/', $recipe->photo_url);
    }

    public function test_pink_lemonade_confetti_cupcakes() {

        $path = "data/skinnytaste_com_pink_lemonade_confetti_cupcakes_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2011/07/pink-lemonade-confetti-cupcakes.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Pink Lemonade Confetti Cupcakes', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(8, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^http:\/\/www.skinnyt.*\.jpg$/', $recipe->photo_url);
    }

    public function test_red_white_and_blueberry_trifle() {

        $path = "data/skinnytaste_com_red_white_and_blueberry_trifle_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2011/06/red-white-and-blueberry-trifle.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Red, White and Blueberry Trifle', $recipe->title);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(3, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Cream filling', $recipe->ingredients[1]['name']);
        $this->assertEquals(4, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^http:\/\/www.skinnyt.*\.jpg$/', $recipe->photo_url);
    }

    public function test_shrimp_salad_on_cucumber_slices() {

        $path = "data/skinnytaste_com_shrimp_salad_on_cucumber_slices_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2010/08/shrimp-salad-on-cucumber-slices.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Shrimp Salad on Cucumber Slices', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(1, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^http:\/\/www.skinnyt.*\.jpg$/', $recipe->photo_url);
    }

    public function test_skinny_coconut_cupcakes() {

        $path = "data/skinnytaste_com_skinny_coconut_cupcakes_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2012/03/skinny-coconut-cupcakes.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Skinny Coconut Cupcakes', $recipe->title);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Frosting', $recipe->ingredients[0]['name']);
        $this->assertEquals(3, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Cupcakes', $recipe->ingredients[1]['name']);
        $this->assertEquals(7, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^http:\/\/www.skinnyt.*\.jpg$/', $recipe->photo_url);
    }

    public function test_skinny_baked_jalapeno_poppers() {
        $path = "data/skinnytaste_com_skinny_baked_jalape_o_poppers_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/skinny-baked-jalapeno-poppers/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Skinny Baked Jalapeño Poppers', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(10, count($recipe->instructions[0]['list']));
    }


    public function test_turkey_chili_taco_soup() {
        // This test file has a format that uses Schema/Recipe.
        $path = "data/skinnytaste_com_turkey_chili_taco_soup_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/turkey-chili-taco-soup/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Turkey Chili Taco Soup', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^http:\/\/www.skinnyt.*\.jpg$/', $recipe->photo_url);
    }

}
