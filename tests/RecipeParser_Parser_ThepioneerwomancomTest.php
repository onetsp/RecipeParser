<?php

require_once './bootstrap.php';

class RecipeParser_Parser_ThepioneerwomancomTest extends PHPUnit_Framework_TestCase {

    public function test_petite_vanilla_bean_scones() {
        $path = "data/thepioneerwoman_com_petite_vanilla_bean_scones_the_pioneer_curl.html";
        $url = "http://thepioneerwoman.com/cooking/2010/04/petite-vanilla-bean-scones/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Petite Vanilla Bean Scones', $recipe->title);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(20, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('12 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Scones', $recipe->ingredients[0]['name']);
        $this->assertEquals(8, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Glaze', $recipe->ingredients[1]['name']);
        $this->assertEquals(4, count($recipe->ingredients[1]['list']));

        $this->assertEquals(2, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(8, count($recipe->instructions[0]['list']));
        $this->assertEquals('Vanilla glaze', $recipe->instructions[1]['name']);
        $this->assertEquals(2, count($recipe->instructions[1]['list']));

        $this->assertEquals('http://tastykitchen.com/recipes/files/2010/04/4495331454_fcb27f08ce_o-420x280.jpg', 
                            $recipe->photo_url);
    }


    public function test_ravioli_three_ways() {
        $path = "data/thepioneerwoman_com_ravioli_three_ways_the_pioneer_woman_curl.html";
        $url = "http://thepioneerwoman.com/cooking/2011/09/ravioli-three-ways/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Ravioli, Three Ways', $recipe->title);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(5, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(10, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://tastykitchen.com/recipes/files/2011/09/TPW_0600-420x280.jpg', 
                            $recipe->photo_url);
    }

    public function test_sweet_cinnamon_scones() {
        $path = "data/thepioneerwoman_com_sweet_cinnamon_scones_the_pioneer_woman_curl.html";
        $url = "http://thepioneerwoman.com/cooking/2011/03/sweet-cinnamon-scones/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Sweet Cinnamon Scones', $recipe->title);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(25, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(13, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://tastykitchen.com/recipes/files/2011/03/5525020534_a195290297_o-420x280.jpg', 
                            $recipe->photo_url);
    }

}

?>
