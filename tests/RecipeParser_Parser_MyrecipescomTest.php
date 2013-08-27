<?php

require_once './bootstrap.php';

class RecipeParser_Parser_MyrecipescomTest extends PHPUnit_Framework_TestCase {

    public function test_berry_galette_with_cornmeal() {
        $path = "data/myrecipes_com_blueberry_and_blackberry_galette_with_cornmeal_curl.html";
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

        $this->assertEquals(
            'http://img4-2.myrecipes.timeinc.net/i/recipes/ck/08/07/blueberry-galette-ck-1816371-x.jpg',
            $recipe->photo_url);
    }

    public function test_clam_chowder() {
        $path = "data/myrecipes_com_simple_clam_chowder_mys_com_curl.html";
        $url = "http://www.myrecipes.com/recipe/simple-clam-chowder-10000001696572/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Simple Clam Chowder", $recipe->title);
        $this->assertEquals("12 servings (serving size: 1 cup)", $recipe->yield);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
    }

    public function test_king_ranch_chicken() {
        $path = "data/myrecipes_com_king_ranch_chicken_casserole_mys_com_curl.html";
        $url = "http://www.myrecipes.com/recipe/king-ranch-chicken-casserole-10000001704091/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("King Ranch Chicken Casserole", $recipe->title);
        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(139, $recipe->time['cook']);
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));
        $this->assertEquals(9, count($recipe->instructions[0]['list']));
    }

}

?>
