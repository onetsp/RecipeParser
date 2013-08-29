<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_SimplyrecipescomTest extends PHPUnit_Framework_TestCase {

    public function test_apple_cranberry_pie() {
        $path = "data/simplyrecipes_com_apple_cranberry_pie_simply_s_curl.html";
        $url = "http://simplyrecipes.com/recipes/apple_cranberry_pie/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Apple Cranberry Pie', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        #$this->assertEquals('8 servings', $recipe->yield);   // Not marked up

        $this->assertEquals(3, count($recipe->ingredients));
        $this->assertEquals('Crust ingredients', $recipe->ingredients[0]['name']);
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Filling ingredients', $recipe->ingredients[1]['name']);
        $this->assertEquals(8, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Egg wash', $recipe->ingredients[2]['name']);
        $this->assertEquals(2, count($recipe->ingredients[2]['list']));
        $this->assertEquals(1, count($recipe->instructions));

        // Servings mixed in with last line of instructions. Leaving as-is for now.
        $this->assertEquals(9, count($recipe->instructions[0]['list']));

        $this->assertEquals(
            'http://www.simplyrecipes.com/wp-content/uploads/2005/11/apple-cranberry-pie.jpg',
            $recipe->photo_url);
    }

    public function test_chicken_curry_salad() {
        $path = "data/simplyrecipes_com_chicken_curry_salad_simply_s_curl.html";
        $url = "http://simplyrecipes.com/recipes/chicken_curry_salad/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Chicken Curry Salad', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(10, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

    }

    public function test_chile_casserole() {
        $path = "data/simplyrecipes_com_chile_relleno_casserole_simply_s_curl.html";
        $url = "http://simplyrecipes.com/recipes/chile_relleno_casserole/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Chile Relleno Casserole', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(13, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(8, count($recipe->instructions[0]['list']));
        $this->assertRegExp('/^Preheat broiler/', $recipe->instructions[0]['list'][0]);

        $this->assertRegExp("/Feel free to play around with the stuffing/", $recipe->notes);
    }

    public function test_grilled_steak() {
        $path = "data/simplyrecipes_com_grilled_tri_tip_steak_with_bell_pepper_curl.html";
        $url = "http://simplyrecipes.com/recipes/grilled_tri-tip_steak_with_bell_pepper_salsa/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        


        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Grilled Tri-Tip Steak with Bell Pepper Salsa', $recipe->title);

        $this->assertEquals(5, $recipe->time['prep']);
        $this->assertEquals(30, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('6 servings', $recipe->yield);

        $this->assertEquals(3, count($recipe->ingredients));
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->ingredients[1]['list']));
        $this->assertEquals(8, count($recipe->ingredients[2]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(7, count($recipe->instructions[0]['list']));
        $this->assertRegExp('/^Prepare the marinade in a large bowl/', $recipe->instructions[0]['list'][0]);

        $this->assertRegExp("/If you don't have a grill/", $recipe->notes);
    }

}

?>
