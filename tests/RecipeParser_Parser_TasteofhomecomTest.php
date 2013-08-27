<?php

require_once './bootstrap.php';

class RecipeParser_Parser_TasteofhomecomTest extends PHPUnit_Framework_TestCase {

    public function test_cheddar_appetizers() {
        $path = "data/clipped/tasteofhome_com_almond_cheddar_appetizers_curl.html";
        $url = "http://www.tasteofhome.com/Recipes/Almond-Cheddar-Appetizers";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        


        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Almond Cheddar Appetizers', $recipe->title);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(10, $recipe->time['cook']);
        $this->assertEquals(25, $recipe->time['total']);
        $this->assertEquals('16 servings', $recipe->yield);

        $this->assertRegExp('/Unbaked appetizers may be frozen/', $recipe->notes);

        $this->assertEquals(7, count($recipe->ingredients[0]['list']));
        $this->assertEquals(2, count($recipe->instructions[0]['list']));
        $this->assertRegExp('/or until bubbly.$/', $recipe->instructions[0]['list'][1]);

        $this->assertEquals(
            'http://hostedmedia.reimanpub.com/TOH/Images/Photos/37/exps7647_RDHE10671C9.jpg',
            $recipe->photo_url);
    }

    public function test_artichoke_chicken() {
        $path = "data/clipped/tasteofhome_com_artichoke_chicken_curl.html";
        $url = "http://www.tasteofhome.com/Recipes/Artichoke-Chicken";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        


        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Artichoke Chicken', $recipe->title);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(50, $recipe->time['cook']);
        $this->assertEquals(65, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertRegExp('/Sprinkle with parsley.$/', $recipe->instructions[0]['list'][2]);
    }

    public function test_lemon_berry_shortcake() {
        $path = "data/clipped/tasteofhome_com_lemon_berry_shortcake_curl.html";
        $url = "http://www.tasteofhome.com/Recipes/Lemon-Berry-Shortcake";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        


        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Lemon-Berry Shortcake', $recipe->title);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(20, $recipe->time['cook']);
        $this->assertEquals(50, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));
        $this->assertEquals(4, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Topping', $recipe->ingredients[1]['name']);
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
    }

}

?>
