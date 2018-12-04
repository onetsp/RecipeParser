<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class MyrecipescomTest extends TestCase {

    public function test_berry_galette_with_cornmeal() {
        $path = TestUtils::getDataPath("myrecipes_com_blueberry_and_blackberry_galette_with_cornmeal_curl.html");
        $url = "http://www.myrecipes.com/recipe/blueberry-blackberry-galette-with-cornmeal-crust-10000001816371/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Blueberry and Blackberry Galette with Cornmeal Crust", $recipe->title);
        #$this->assertRegExp("/^The rich, buttery pastry dough/", $recipe->description);
        $this->assertEquals("10 servings (serving size: 1 wedge)", $recipe->yield);
        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals("Pastry", $recipe->ingredients[0]['name']);
        $this->assertEquals("Filling", $recipe->ingredients[1]['name']);
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));
        $this->assertEquals("1/3 cup granulated sugar", $recipe->ingredients[0]['list'][1]);
        $this->assertEquals(8, count($recipe->ingredients[1]['list']));
        $this->assertEquals(5, count($recipe->instructions[0]['list']));
        $this->assertRegExp("/^To prepare pastry/", $recipe->instructions[0]['list'][0]);
        $this->assertEquals("Cooking Light", $recipe->credits);

        $this->assertRegExp(
            "/image\/app\/blueberry-galette-ck-1816371-xl.jpg/",
            $recipe->photo_url);
    }

    public function test_clam_chowder() {
        $path = TestUtils::getDataPath("myrecipes_com_simple_clam_chowder_my_com_curl.html");
        $url = "http://www.myrecipes.com/recipe/simple-clam-chowder-10000001696572/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Simple Clam Chowder", $recipe->title);
        $this->assertEquals("12 servings (serving size: 1 cup)", $recipe->yield);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
    }

    public function test_king_ranch_chicken() {
        $path = TestUtils::getDataPath("myrecipes_com_king_ranch_chicken_casserole_my_com_curl.html");
        $url = "http://www.myrecipes.com/recipe/king-ranch-chicken-casserole-10000001704091/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("King Ranch Chicken Casserole", $recipe->title);
        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(139, $recipe->time['cook']);
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));
        $this->assertEquals(9, count($recipe->instructions[0]['list']));
        $this->assertEquals("Southern Living", $recipe->credits);
    }

    public function test_lemon_chicken_piccata() {
        $path = TestUtils::getDataPath("myrecipes_com_charred_lemon_chicken_piccata_my_com_curl.html");
        $url = "http://www.myrecipes.com/recipe/charred-lemon-chicken-piccata";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Charred Lemon Chicken Piccata", $recipe->title);
        $this->assertEquals("4 servings (serving size: 1 chicken breast half and about 3 tablespoons sauce)", $recipe->yield);
        $this->assertEquals(37, $recipe->time['total']);
        $this->assertEquals(17, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertEquals("Cooking Light", $recipe->credits);
    }

    public function test_broccoli_cheese_soup() {
        $path = TestUtils::getDataPath("myrecipes_com_creamy_broccoli_cheese_soup_my_com_curl.html");
        $url = "http://www.myrecipes.com/recipe/creamy-broccoli-cheese-soup";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Creamy Broccoli-Cheese Soup", $recipe->title);
        $this->assertEquals("6 servings (serving size: about 1 cup)", $recipe->yield);
        $this->assertEquals(45, $recipe->time['total']);
        $this->assertEquals(10, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertEquals("Cooking Light", $recipe->credits);
    }

    public function test_potato_roti_curry() {
        $path = TestUtils::getDataPath("myrecipes_com_potato_roti_curry_my_com_curl.html");
        $url = "http://www.myrecipes.com/recipe/potato-roti-curry";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Potato Roti Curry", $recipe->title);
        $this->assertEquals("6 servings (serving size: about 1 cup)", $recipe->yield);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals(15, count($recipe->ingredients[0]['list']));
        $this->assertEquals(2, count($recipe->instructions[0]['list']));
        $this->assertEquals("Cooking Light", $recipe->credits);
    }

    public function test_potato_salad() {
        $path = TestUtils::getDataPath("myrecipes_com_light_and_fresh_potato_salad_my_curl.html");
        $url = "http://www.myrecipes.com/recipe/light-fresh-potato-salad";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Light and Fresh Potato Salad", $recipe->title);
        $this->assertEquals("12 servings (serving size: 3/4 cup)", $recipe->yield);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals("Dressing", $recipe->ingredients[0]['name']);
        $this->assertEquals(4, count($recipe->ingredients[0]['list']));
        $this->assertEquals("Salad", $recipe->ingredients[1]['name']);
        $this->assertEquals(8, count($recipe->ingredients[1]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertEquals("To prepare dressing, combine first 4 ingredients in a large bowl; stir with a whisk.", 
            $recipe->instructions[0]['list'][0]);
        $this->assertEquals("Cooking Light", $recipe->credits);

    }

}
