<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class SparkpeoplecomTest extends TestCase {

    public function test_carrot_pumpkin_bars() {
        $path = "data/recipes_sparkpeople_com_carrot_pumpkin_bars_by_andrewmom_sparks_curl.html";
        $url = "http://recipes.sparkpeople.com/recipe-detail.asp?recipe=157762";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Carrot Pumpkin Bars', $recipe->title);
        $this->assertEquals('Great for the fall holidays or just for a snack!', $recipe->description);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(25, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('24 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Filling', $recipe->ingredients[0]['name']);
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Cream cheese topping', $recipe->ingredients[1]['name']);
        $this->assertEquals(3, count($recipe->ingredients[1]['list']));

        $this->assertEquals(3, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
        $this->assertEquals('Prepare filling', $recipe->instructions[1]['name']);
        $this->assertEquals(2, count($recipe->instructions[1]['list']));
        $this->assertEquals('Prepare cream cheese topping', $recipe->instructions[2]['name']);
        $this->assertEquals(4, count($recipe->instructions[2]['list']));

        $this->assertRegExp('|.*sparkpeople.com/nw/3/3/l336775191.jpg$|', 
                            $recipe->photo_url);
    }

    public function test_mini_cheesecakes() {
        $path = "data/recipes_sparkpeople_com_mini_cheesecakes_by_sparks_curl.html";
        $url = "http://recipes.sparkpeople.com/recipe-detail.asp?recipe=63";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Mini Cheesecakes', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertRegExp('|.*sparkpeople.com/nw/4/2/l420032142.jpg$|', 
                            $recipe->photo_url);
    }

    public function test_graham_cracker_cheesycake() {
        $path = "data/recipes_sparkpeople_com_no_bake_graham_cracker_cheesycake_by_curl.html";
        $url = "http://recipes.sparkpeople.com/recipe-detail.asp?recipe=384073";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('No Bake Graham Cracker Cheesycake', $recipe->title);

        $this->assertEquals(10, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('20 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(2, count($recipe->instructions[0]['list']));
    }

    public function test_skillet_lasagna() {
        $path = "data/recipes_sparkpeople_com_skillet_lasagna_by_veggiekitty_sparks_curl.html";
        $url = "http://recipes.sparkpeople.com/recipe-detail.asp?recipe=20856";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Skillet Lasagna', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('6 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(11, count($recipe->instructions[0]['list']));
    }


}
