<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_NytimescomRecipesTest extends PHPUnit_Framework_TestCase {

    public function test_recipes_blackberry_jam_cake() {
        $path = "data/nytimes_com_blackberry_jam_cake_with_caramel_icing_curl.html";
        $url = "http://www.nytimes.com/recipes/7801/Blackberry-Jam-Cake-With-Caramel-Icing.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Blackberry Jam Cake With Caramel Icing', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(120, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('12 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Cake', $recipe->ingredients[0]['name']);
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Icing', $recipe->ingredients[1]['name']);
        $this->assertEquals(5, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));
    }

    public function test_recipes_fudge_frosting() {
        $path = "data/nytimes_com_fudge_frosting_nyt_cooking_curl.html";
        $url = "http://www.nytimes.com/recipes/9953/Fudge-Frosting.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Fudge Frosting', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(15, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^Another way to make this .* perfectly smooth and glossy./', $recipe->notes);
    }

    public function test_recipes_lemon_cake() {
        $path = "data/nytimes_com_lemon_cake_with_coconut_icing_nyt_curl.html";
        $url = "http://www.nytimes.com/recipes/7800/Lemon-Cake-With-Coconut-Icing.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Lemon Cake With Coconut Icing', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('12 servings', $recipe->yield);

        $this->assertEquals(3, count($recipe->ingredients));
        $this->assertEquals('Cake', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Lemon curd', $recipe->ingredients[1]['name']);
        $this->assertEquals(7, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Icing', $recipe->ingredients[2]['name']);
        $this->assertEquals(4, count($recipe->ingredients[2]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(7, count($recipe->instructions[0]['list']));
    }

    public function test_recipes_service_cake_victory_icing() {
        $path = "data/nytimes_com_service_cake_with_victory_icing_nyt_curl.html";
        $url = "http://www.nytimes.com/recipes/7357/Service-Cake-With-Victory-Icing.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Service Cake With Victory Icing', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('9 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Cake', $recipe->ingredients[0]['name']);
        $this->assertEquals(10, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Icing', $recipe->ingredients[1]['name']);
        $this->assertEquals(5, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
    }

#    public function test_dining_lentils_and_rice() {
#        $path = "data/nytimes_com_lentils_and_rice_with_or_without_curl.html";
#        $url = "http://www.nytimes.com/2011/01/02/weekinreview/02recipes-2.html";
#
#        $recipe = RecipeParser::parse(file_get_contents($path), $url);
#        if (isset($_SERVER['VERBOSE'])) print_r($recipe);
#
#        $this->assertEquals(0, $recipe->time['prep']);
#        $this->assertEquals(0, $recipe->time['cook']);
#        $this->assertEquals(45, $recipe->time['total']);
#        $this->assertEquals('4 to 6 servings', $recipe->yield);
#
#        $this->assertEquals('Lentils and Rice With or Without Pork', $recipe->title);
#
#        $this->assertEquals(1, count($recipe->ingredients));
#        $this->assertEquals('', $recipe->ingredients[0]['name']);
#        $this->assertEquals(11, count($recipe->ingredients[0]['list']));
#        $this->assertEquals(1, count($recipe->instructions));
#        $this->assertEquals(3, count($recipe->instructions[0]['list']));
#
#        $this->assertRegExp('/^Use any grain instead.*or stock if you have it\.$/s', $recipe->notes);
#    }

}

