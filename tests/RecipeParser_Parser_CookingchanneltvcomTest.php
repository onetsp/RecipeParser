<?php

require_once './bootstrap.php';

class RecipeParser_Parser__Cookingchanneltvcom_Test extends PHPUnit_Framework_TestCase {

    public function test_chocolate_cake() {
        $path_orig = "data/clipped/cookingchanneltv_com_chocolate_chocolate_cake_s_cooking_channel_curl.html";
        $url = "http://www.cookingchanneltv.com/recipes/emeril-lagasse/chocolate-chocolate-cake.html";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Chocolate, Chocolate Cake', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('16 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(1, count($recipe->instructions[0]['list']));

        $this->assertEquals('', 
                            $recipe->photo_url);
    }

    public function test_old_fashioned_chocolate_cake() {
        $path_orig = "data/clipped/cookingchanneltv_com_old_fashioned_chocolate_cake_s_cooking_channel_curl.html";
        $url = "http://www.cookingchanneltv.com/recipes/old-fashioned-chocolate-cake.html";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Old-Fashioned Chocolate Cake', $recipe->title);

        $this->assertEquals(25, $recipe->time['prep']);
        $this->assertEquals(35, $recipe->time['cook']);
        $this->assertEquals(120, $recipe->time['total']);
        $this->assertEquals('approximately 8 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Cake', $recipe->ingredients[0]['name']);
        $this->assertEquals(10, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Frosting', $recipe->ingredients[1]['name']);
        $this->assertEquals(7, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(12, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://cook.sndimg.com/content/dam/images/cook/fullset/2012/9/14/3/nl0106_cake2.jpg/jcr:content/renditions/cq5dam.web.266.200.jpeg', 
                            $recipe->photo_url);
    }

    public function test_pork_tenderloin() {
        $path_orig = "data/clipped/cookingchanneltv_com_pork_tenderloin_s_cooking_channel_curl.html";
        $url = "http://www.cookingchanneltv.com/recipes/pork-tenderloin.html";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Pork Tenderloin', $recipe->title);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(70, $recipe->time['cook']);
        $this->assertEquals(90, $recipe->time['total']);
        $this->assertEquals('4 to 6 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://cook.sndimg.com/content/dam/images/cook/fullset/2012/6/23/0/bq0325_pork_tenderloin1.jpg/jcr:content/renditions/cq5dam.web.266.200.jpeg', 
                            $recipe->photo_url);
    }

}

?>
