<?php

require_once "../bootstrap.php";

class RecipeParser_Parser_12tomatoescomTest extends PHPUnit_Framework_TestCase {

    public function test_filling_and_fruity_treat_cream_cheese_cherry_coffee_cake() {

        $path = "data/12tomatoes_com_filling_and_fruity_treat_cream_cheese_curl.html";
        $url  = "http://12tomatoes.com/2015/04/filling-and-fruity-treat-cream-cheese-cherry-coffee-cake.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Cream Cheese Cherry Cake', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('1, 9-10-inch cake', $recipe->yield);  // strange formating, but it is what it is.

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(15, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(8, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://s3.amazonaws.com/studio-me/system/photos/photos/000/813/509/original/36292587_l.jpg',
                            $recipe->photo_url);

    }

    public function test_healthy_sugarfree_cookies_nobake_chocolate_oat_cookies() {

        $path = "data/12tomatoes_com_healthy_sugar_free_cookies_no_bake_curl.html";
        $url  = "http://12tomatoes.com/2015/04/healthy-sugarfree-cookies-nobake-chocolate-oat-cookies.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Sugar-Free Chocolate Oat Cookies', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('3 dozen', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://s3.amazonaws.com/studio-me/system/photos/photos/000/815/181/original/37427544_l.jpg',
                            $recipe->photo_url);

    }

    public function test_light_and_sweet_dessert_creamy_raspberry_cheesecake_bars() {

        $path = "data/12tomatoes_com_light_and_sweet_dessert_creamy_raspberry_curl.html";
        $url  = "http://12tomatoes.com/2015/04/light-and-sweet-dessert-creamy-raspberry-cheesecake-bars.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Raspberry Cheesecake Bars', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('2-3 dozen squares', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(10, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://s3.amazonaws.com/studio-me/system/photos/photos/000/813/291/original/19555409_l.jpg',
                            $recipe->photo_url);

    }

}
