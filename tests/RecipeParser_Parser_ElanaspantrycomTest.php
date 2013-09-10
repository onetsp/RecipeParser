<?php

require_once "../bootstrap.php";

class RecipeParser_Parser_Elanaspantrycom_Test extends PHPUnit_Framework_TestCase {

    public function test_cranberry_coconut_power_bars() {

        $path = "data/elanaspantry_com_cranberry_coconut_power_bars_paleo_power_bars_curl.html";
        $url  = "http://www.elanaspantry.com/cranberry-coconut-power-bars/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Paleo Power Bars', $recipe->title);

        $this->assertEquals('25 bars', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.elanaspantry.com/blog/wp-content/uploads/2013/08/Cranberry-Coconut-Power-Bars-1876.jpg',
                            $recipe->photo_url);
    }

    public function test_coconut_cupcakes_key_lime_icing() {

        $path = "data/elanaspantry_com_gluten_free_and_nut_free_coconut_cupcakes_curl.html";
        $url  = "http://www.elanaspantry.com/coconut-cupcakes-key-lime-icing/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Coconut Cupcakes with Key Lime Icing', $recipe->title);
        $this->assertEquals('10 cupcakes', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals("3 eggs", $recipe->ingredients[0]['list'][0]);
        $this->assertEquals("½ teaspoon baking soda", $recipe->ingredients[0]['list'][5]);
        $this->assertEquals("½ cup unsweetened shredded coconut", $recipe->ingredients[0]['list'][6]);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(8, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.elanaspantry.com/blog/wp-content/uploads/2010/05/gluten-free-coconut-cupcakes-key-lime-frosting-DSC_5328.jpg',
                            $recipe->photo_url);
    }

    public function test_pumpkin_cranberry_upside_down_cake() {

        $path = "data/elanaspantry_com_pumpkin_cranberry_upside_down_cake_gluten_free_dessert_curl.html";
        $url  = "http://www.elanaspantry.com/pumpkin-cranberry-upside-down-cake/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Pumpkin Cranberry Upside-Down Cake', $recipe->title);

        $this->assertEquals('24 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(14, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(12, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.elanaspantry.com/blog/wp-content/uploads/2007/11/pumpkin_cranberry_upside-down_cake.jpg',
                            $recipe->photo_url);
    }

    public function test_paleo_samoas() {

        $path = "data/elanaspantry_com_paleo_samoas_gluten_free_girl_scout_samoa_cookies_curl.html";
        $url  = "http://www.elanaspantry.com/paleo-samoas/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Paleo Samoas', $recipe->title);
        $this->assertEquals('36 cookies', $recipe->yield);

        // Multi-section recipe is squeezed into one section. I'm not putting the effort in right now
        // to split these out.
        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(17, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.elanaspantry.com/blog/wp-content/uploads/2013/01/Samoras-2-0051.jpg',
                            $recipe->photo_url);

    }

}
