<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_AllrecipesTest extends PHPUnit_Framework_TestCase
{

    public function test_apple_pumpkin_muffins()
    {
        $path = "data/allrecipes_com_pumpkin_apple_streusel_muffins_alls_com_curl.html";
        $url = "http://allrecipes.com/Recipe/Apple-Pumpkin-Muffins/Detail.aspx";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Pumpkin Apple Streusel Muffins", $recipe->title);
        $this->assertEquals("18 muffins", $recipe->yield);
        $this->assertEquals($url, $recipe->url);

        // Two sections in this recipe
        $this->assertEquals(13, count($recipe->ingredients[0]['list']));
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        $this->assertEquals(
            'http://images.media-allrecipes.com/userphotos/250x250/00/04/57/45762.jpg',
            $recipe->photo_url
        );
    }

    public function test_spiced_pumpkin_seeds()
    {
        $path = "data/allrecipes_com_spiced_pumpkin_seeds_alls_com_curl.html";
        $url = "http://allrecipes.com/Recipe/Spiced-Pumpkin-Seeds/Detail.aspx";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Spiced Pumpkin Seeds", $recipe->title);
        $this->assertEquals("2 cups", $recipe->yield);
        $this->assertEquals(10, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(70, $recipe->time['total']);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(5, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
    }

    public function test_carrot_cake()
    {
        $path = "data/allrecipes_com_carrot_cake_viii_alls_com_curl.html";
        $url = "http://allrecipes.com/Recipe/Carrot-Cake-VIII/Detail.aspx";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Carrot Cake VIII", $recipe->title);
        $this->assertEquals("1 - 10 inch bundt pan", $recipe->yield);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(19, count($recipe->ingredients[0]['list']));
        $this->assertEquals(7, count($recipe->instructions[0]['list']));
    }

    public function test_customrecipe_template()
    {
        $path = "data/allrecipes_com_potato_bacon_cheese_frittata_customized_by_curl.html";
        $url = "http://allrecipes.com/customrecipe/62636838/potato-bacon-cheese-frittata/detail.aspx";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Potato, Bacon Cheese Frittata", $recipe->title);
        $this->assertEquals("6 servings", $recipe->yield);
        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(30, $recipe->time['cook']);
        $this->assertEquals(60, $recipe->time['total']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));
        $this->assertEquals(8, count($recipe->instructions[0]['list']));
    }

}

?>
