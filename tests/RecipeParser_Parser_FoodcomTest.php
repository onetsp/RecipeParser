<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_FoodcomTest extends PHPUnit_Framework_TestCase {

    public function test_eyeball_cookies() {
        $path = "data/food_com_halloween_eyeball_cookies_food_com_143344_curl.html";
        $url = "http://www.food.com/recipe/halloween-eyeball-cookies-143344";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        


        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Halloween Eyeball Cookies", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals("40 cookies", $recipe->yield);
        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(20, $recipe->time['total']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));
        $this->assertEquals(12, count($recipe->instructions[0]['list']));

        $this->assertEquals(
            'http://food.sndimg.com/img/recipes/14/33/44/large/picVvJU8z.jpg',
            $recipe->photo_url);

    }

    public function test_braised_lamb() {
        $path = "data/food_com_braised_lamb_shanks_with_guinness_barley_curl.html";
        $url = "http://www.food.com/recipe/braised-lamb-shanks-with-guinness-barley-222337";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        


        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Braised Lamb Shanks With Guinness & Barley", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals("4 servings", $recipe->yield);
        $this->assertEquals(5, $recipe->time['prep']);
        $this->assertEquals(120, $recipe->time['cook']);
        $this->assertEquals(125, $recipe->time['total']);
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        $this->assertEquals(
            'http://food.sndimg.com/img/recipes/22/23/37/large/piclW3oYD.jpg',
            $recipe->photo_url);
    }

    public function test_carrot_cheesecake() {
        $path = "data/food_com_carrot_cheesecake_food_com_362026_curl.html";
        $url = "http://www.food.com/recipe/carrot-cheesecake-362026"; 

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        


        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Carrot Cheesecake", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals("1 cake", $recipe->yield);
        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(90, $recipe->time['total']);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Crust', $recipe->ingredients[0]['name']);
        $this->assertEquals(3, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Cheesecake', $recipe->ingredients[1]['name']);
        $this->assertEquals(12, count($recipe->ingredients[1]['list']));

        $this->assertEquals(2, count($recipe->instructions));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertEquals(4, count($recipe->instructions[1]['list']));

        $this->assertEquals(
            '',
            $recipe->photo_url);
    }

}

?>
