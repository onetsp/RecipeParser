<?php

require_once "../bootstrap.php";

class RecipeParser_Parser_ThedailymealcomTest extends PHPUnit_Framework_TestCase {
    public function test_roast_capon() {
        $path = "data/thedailymeal_com_roast_capon_the_daily_meal_curl.html";
        $url  = "http://www.thedailymeal.com/roast-capon";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Roast Capon', $recipe->title);
        $this->assertEquals('Anne Dolce', $recipe->credits);

        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(5, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('', $recipe->notes);
        $this->assertEquals('http://cdn-jpg.thedailymeal.net/sites/default/files/roastcapon-istock.jpg', $recipe->photo_url);
    }

    public function test_whiskey_30() {
        $path = "data/thedailymeal_com_whiskey_the_daily_meal_curl.html";
        $url  = "http://www.thedailymeal.com/whiskey-30";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Whiskey 3.0', $recipe->title);
        $this->assertEquals('akrishba', $recipe->credits);
        $this->assertEquals('', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
    }
}
