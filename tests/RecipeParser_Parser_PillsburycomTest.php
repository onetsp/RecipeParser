<?php

require_once './bootstrap.php';

class RecipeParser_Parser_PillsburycomTest extends PHPUnit_Framework_TestCase {

    public function test_banana_crumb_cake() {
        $path_orig = "data/pillsbury_com_banana_crumb_cake_from_pillsbury_com_curl.html";
        $url = "http://www.pillsbury.com/recipes/banana-crumb-cake/0ddbc221-0c55-47ea-aaab-7439b4aac4a6/";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Banana Crumb Cake', $recipe->title);

        $this->assertEquals(10, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(80, $recipe->time['total']);
        $this->assertEquals('9 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Cake', $recipe->ingredients[0]['name']);
        $this->assertEquals(5, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Crumb topping', $recipe->ingredients[1]['name']);
        $this->assertEquals(4, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertRegExp("/^Heat oven to 375°F./", $recipe->instructions[0]['list'][0]);

        $this->assertEquals('http://s3.amazonaws.com/gmi-digital-library/c7eefddc-754e-49c9-9b6f-4cd4261108b8.jpg', 
                            $recipe->photo_url);
    }

    public function test_cookie_pizza() {
        $path_orig = "data/pillsbury_com_rocky_road_cookie_pizza_cookie_dough_curl.html";
        $url = "http://www.pillsbury.com/recipes/rocky-road-cookie-pizza-cookie-dough-tub/8e79226f-937e-49c3-84a8-5bd55fa94d00/";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Rocky Road Cookie Pizza (cookie dough tub)', $recipe->title);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(125, $recipe->time['total']);
        $this->assertEquals('16 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(5, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^Experiment with different nuts and toppings to get the flavor combo you love most!$/', $recipe->notes);

        $this->assertEquals('http://s3.amazonaws.com/gmi-digital-library/d52a626f-07ae-4d82-a98f-7b20ec759ee6.jpg', 
                            $recipe->photo_url);
    }

    public function test_white_wedding_cake() {
        $path_orig = "data/pillsbury_com_white_wedding_cake_with_raspberry_filling_curl.html";
        $url = "http://www.pillsbury.com/recipes/white-wedding-cake-with-raspberry-filling/552ae7a5-c451-43fa-9ddc-12b24dbce825/";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('White Wedding Cake with Raspberry Filling', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('72 servings', $recipe->yield);

        $this->assertEquals(4, count($recipe->ingredients));
        $this->assertEquals('Cake', $recipe->ingredients[0]['name']);
        $this->assertEquals(4, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Frosting', $recipe->ingredients[1]['name']);
        $this->assertEquals(6, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Filling', $recipe->ingredients[2]['name']);
        $this->assertEquals(1, count($recipe->ingredients[2]['list']));
        $this->assertEquals('Supplies needed', $recipe->ingredients[3]['name']);
        $this->assertEquals(6, count($recipe->ingredients[3]['list']));

        $this->assertEquals('1 3/4 cups raspberry filling*', $recipe->ingredients[2]['list'][0]);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(15, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/The raspberry filling can be purchased/', $recipe->notes);

        $this->assertEquals('http://s3.amazonaws.com/gmi-digital-library/134125f3-252e-4a64-b3a3-42e26f8e108f.jpg', 
                            $recipe->photo_url);
    }

}

?>
