<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class AllrecipesTest extends TestCase {

    public function test_apple_pumpkin_muffins() {
        $path = TestUtils::getDataPath("allrecipes_com_apple_pumpkin_muffins.html");
        $url = "http://allrecipes.com/recipe/42273/apple-pumpkin-muffins/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Apple Pumpkin Muffins", $recipe->title);
        $this->assertEquals("18 servings", $recipe->yield);
        $this->assertEquals($url, $recipe->url);

        // Two sections in this recipe
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));
        $this->assertEquals(4, count($recipe->ingredients[1]['list']));
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertEquals(
            'https://images.media-allrecipes.com/userphotos/250x250/742085.jpg',
            $recipe->photo_url
        );
    }

    public function test_spiced_pumpkin_seeds() {
        $path = TestUtils::getDataPath("allrecipes_com_spiced_pumpkin_seeds.html");
        $url = "http://allrecipes.com/Recipe/Spiced-Pumpkin-Seeds/Detail.aspx";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Spiced Pumpkin Seeds", $recipe->title);
        $this->assertEquals("8 servings", $recipe->yield);
        $this->assertEquals(10, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(70, $recipe->time['total']);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(5, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

    }

    public function test_carrot_cake() {
        $path = TestUtils::getDataPath("allrecipes_com_carrot_cake_viii.html");
        $url = "http://allrecipes.com/Recipe/Carrot-Cake-VIII/Detail.aspx";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Carrot Cake VIII", $recipe->title);
        $this->assertEquals("12 servings", $recipe->yield);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(19, count($recipe->ingredients[0]['list']));
        $this->assertEquals(7, count($recipe->instructions[0]['list']));
        $this->assertEquals(
            'https://images.media-allrecipes.com/userphotos/250x250/50409.jpg',
            $recipe->photo_url
        );
    }

    public function test_potato_bacon_cheese_frittata() {
        $path = TestUtils::getDataPath("allrecipes_com_potato_bacon_cheese_frittata.html");
        $url = "http://allrecipes.com/customrecipe/62636839/potato-bacon-cheese-frittata/detail.aspx";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Potato, Bacon Cheese Frittata", $recipe->title);
        //$this->assertEquals("6 servings", $recipe->yield);
        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(30, $recipe->time['cook']);
        $this->assertEquals(60, $recipe->time['total']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));
        $this->assertEquals(8, count($recipe->instructions[0]['list']));
    }

}
