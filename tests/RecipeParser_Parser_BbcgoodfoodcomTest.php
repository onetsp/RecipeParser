<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_BbcgoodfoodcomTest extends PHPUnit_Framework_TestCase {

    public function test_chicken_herb_rosti() {
        $path_orig = "data/bbcgoodfood_com_chicken_amp_herb_r_sti_topped_pies_curl.html";
        $url = "http://www.bbcgoodfood.com/recipes/527632/chicken-and-herb-rstitopped-pies";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Chicken & herb rösti-topped pies', $recipe->title);
        $this->assertEquals('olive', $recipe->credits);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(1, $recipe->time['cook']);
        $this->assertEquals(75, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.bbcgoodfood.com/sites/bbcgoodfood.com/files/recipe_images/recipe-image-legacy-id--283473_12.jpg', 
                            $recipe->photo_url);
    }

    public function test_lemon_poppyseed() {
        $path_orig = "data/bbcgoodfood_com_lemon_amp_poppyseed_cupcakes_curl.html";
        $url = "http://www.bbcgoodfood.com/recipes/470636/lemon-and-poppyseed-cupcakes";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Lemon & poppyseed cupcakes', $recipe->title);
        $this->assertEquals('Sarah Cook', $recipe->credits);

        $this->assertEquals(40, $recipe->time['prep']);
        $this->assertEquals(22, $recipe->time['cook']);
        $this->assertEquals(60, $recipe->time['total']);
        $this->assertEquals('12', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Icing', $recipe->ingredients[1]['name']);
        $this->assertEquals(5, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.bbcgoodfood.com/sites/bbcgoodfood.com/files/recipe_images/recipe-image-legacy-id--1273612_8.jpg',
                            $recipe->photo_url);
    }

    public function test_ultimate_chocolate_cake() {
        $path_orig = "data/bbcgoodfood_com_ultimate_chocolate_cake_curl.html";
        $url = "http://www.bbcgoodfood.com/recipes/3092/ultimate-chocolate-cake";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Ultimate chocolate cake', $recipe->title);
        $this->assertEquals('Angela Nilsen', $recipe->credits);

        $this->assertEquals(40, $recipe->time['prep']);
        $this->assertEquals(90, $recipe->time['cook']);
        $this->assertEquals(130, $recipe->time['total']);
        $this->assertEquals('cuts into 14 slices', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Ganache', $recipe->ingredients[1]['name']);
        $this->assertEquals(3, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.bbcgoodfood.com/sites/bbcgoodfood.com/files/recipe_images/recipe-image-legacy-id--1043451_11.jpg',
                            $recipe->photo_url);
    }

    public function test_slow_cooked_chinese_beef() {
        $path_orig = "data/bbcgoodfood_com_slow_cooked_chinese_beef_curl.html";
        $url = "http://www.bbcgoodfood.com/recipes/96613/slowcooked-chinese-beef";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Slow-cooked Chinese beef', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(150, $recipe->time['total']);
    }

}

?>
