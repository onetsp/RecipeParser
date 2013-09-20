<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_FoodnetworkcomTest extends PHPUnit_Framework_TestCase {

    public function test_steak_fajita_chili() {
        $path = "data/foodnetwork_com_aaron_mccargo_jrs_steak_fajita_chili_curl.html";
        $url = "http://www.foodnetwork.com/recipes/aaron-mccargo-jr/aaron-mccargo-jrs-steak-fajita-chili-recipe/index.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Aaron McCargo, Jr.'s Steak Fajita Chili", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals('6 to 8 servings', $recipe->yield);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals(
            'http://img.foodnetwork.com/FOOD/2009/12/13/FNM_010110-He-Made-She-Made-001_s4x3_lg.jpg',
            $recipe->photo_url);
    }

    public function test_chocolate_cake() {
        $path = "data/foodnetwork_com_beattys_chocolate_cake_ina_gartens_food_curl.html";
        $url = "http://www.foodnetwork.com/recipes/ina-garten/beattys-chocolate-cake-recipe/index.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Beatty's Chocolate Cake", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(35, $recipe->time['cook']);
        $this->assertEquals(95, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(19, count($recipe->ingredients[0]['list']));
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

    }

    public function test_braised_short_ribs() {
        $path = "data/foodnetwork_com_braised_short_ribs_with_mushrooms_food_curl.html";
        $url = "http://www.foodnetwork.com/recipes/food-network-kitchens/braised-short-ribs-with-mushrooms-recipe/index.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Braised Short Ribs with Mushrooms", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(63, $recipe->time['prep']);
        $this->assertEquals(197, $recipe->time['cook']);
        $this->assertEquals(260, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);
        $this->assertEquals(15, count($recipe->ingredients[0]['list']));
        $this->assertEquals(5, count($recipe->instructions[0]['list']));
    }

    public function test_emeril_chocolate_cake() {
        $path = "data/foodnetwork_com_chocolate_cake_emeril_lagasses_food_network_curl.html";
        $url = "http://www.foodnetwork.com/recipes/emeril-lagasse/chocolate-cake-recipe/index.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Chocolate Cake", $recipe->title);

        // This is somewhat of a bogus test. The instructions sections don't end up
        // delimited by section names properly. Maybe improve on this later?
        $this->assertEquals(5, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Chocolate genoise', $recipe->ingredients[0]['name']);
        $this->assertEquals(4, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Chocolate buttercream', $recipe->ingredients[1]['name']);
        $this->assertEquals(5, count($recipe->ingredients[2]['list']));
        $this->assertEquals('Chocolate designs and cake assembly', $recipe->ingredients[2]['name']);
    }

    public function test_big_blue_burgers() {
        $path = "data/foodnetwork_com_big_blue_burgers_rachael_rays_food_curl.html";
        $url = "http://www.foodnetwork.com/recipes/rachael-ray/big-blue-burgers-recipe/index.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Big Blue Burgers", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(10, $recipe->time['prep']);
        $this->assertEquals(14, $recipe->time['cook']);
        $this->assertEquals(24, $recipe->time['total']);
        $this->assertEquals('4 burgers', $recipe->yield);
        $this->assertEquals(8, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Toppings', $recipe->ingredients[1]['name']);
        $this->assertEquals(3, count($recipe->ingredients[1]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
    }

    public function test_six_layer_cake() {
        $path = "data/foodnetwork_com_six_layer_chocolate_cake_paula_deens_curl.html";
        $url = "http://www.foodnetwork.com/recipes/paula-deen/six-layer-chocolate-cake-recipe/index.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        // Testing only for format of multi-step ingredients
        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Ecake', $recipe->ingredients[0]['name']);
        $this->assertEquals(8, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Frosting', $recipe->ingredients[1]['name']);
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(4, count($recipe->instructions[0]['list']));
    }

    public function test_cream_scones() {
        $path = "data/foodnetwork_com_cream_scones_with_currants_food_network_curl.html";
        $url = "http://www.foodnetwork.com/recipes/food-network-kitchens/cream-scones-with-currants-recipe/index.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('8 scones', $recipe->yield);

        $this->assertEquals(9, count($recipe->ingredients[0]['list']));
        $this->assertEquals(4, count($recipe->instructions[0]['list']));
    }


    public function test_roasted_pepper_pasta() {
        $path = "data/foodnetwork_com_roasted_pepper_pasta_salad_food_network_curl.html";
        $url = "http://www.foodnetwork.com/recipes/food-network-kitchens/roasted-pepper-pasta-salad-recipe/index.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(5, count($recipe->instructions[0]['list']));
        $this->assertRegExp('/^Squeeze the garlic from/', $recipe->instructions[0]['list'][3]);
    }

}

?>
