<?php

require_once './bootstrap.php';

class RecipeParser_Parser_BbcgoodfoodcomTest extends PHPUnit_Framework_TestCase {

    public function test_chicken_herb_rosti() {
        $path_orig = "data/bbcgoodfood_com_chicken_herb_r_246_sti_topped_pies_s_bbc_curl.html";
        $url = "http://www.bbcgoodfood.com/recipes/527632/chicken-and-herb-rstitopped-pies";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Chicken & herb rösti-topped pies', $recipe->title);
        $this->assertEquals('olive magazine', $recipe->credits);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(1, $recipe->time['cook']);  // This is wrong, but it's what is reported in the recipe
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.bbcgoodfood.com/recipes/527632/images/527632_MEDIUM.jpg', 
                            $recipe->photo_url);
    }

    public function test_lemon_poppyseed() {
        $path_orig = "data/bbcgoodfood_com_lemon_poppyseed_cupcakes_s_bbc_good_curl.html";
        $url = "http://www.bbcgoodfood.com/recipes/470636/lemon-and-poppyseed-cupcakes";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Lemon & poppyseed cupcakes', $recipe->title);
        $this->assertEquals('Sarah Cook', $recipe->credits);

        $this->assertEquals(40, $recipe->time['prep']);
        $this->assertEquals(22, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Icing', $recipe->ingredients[1]['name']);
        $this->assertEquals(5, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.bbcgoodfood.com/recipes/470636/images/470636_MEDIUM.jpg', 
                            $recipe->photo_url);
    }

    public function test_ultimate_chocolate_cake() {
        $path_orig = "data/bbcgoodfood_com_ultimate_chocolate_cake_s_bbc_good_curl.html";
        $url = "http://www.bbcgoodfood.com/recipes/3092/ultimate-chocolate-cake";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Ultimate chocolate cake', $recipe->title);
        $this->assertEquals('Angela Nilsen', $recipe->credits);

        $this->assertEquals(40, $recipe->time['prep']);
        $this->assertEquals(90, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Ganache', $recipe->ingredients[1]['name']);
        $this->assertEquals(3, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.bbcgoodfood.com/recipes/3092/images/3092_MEDIUM.jpg',
                            $recipe->photo_url);
    }

    public function test_slow_cooked_chinese_beef() {
        $path_orig = "data/bbcgoodfood_com_slow_cooked_chinese_beef_s_bbc_good_curl.html";
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
