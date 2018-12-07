<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class GeneralTest extends TestCase {

    public function test_general_title() {
        $path = TestUtils::getDataPath("general_test.html");
        $url = "http://www.example.com/recipes/food/views/Ziti-with-Roasted-Zucchini-361191";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Ziti with Roasted Zucchini', $recipe->title, "Title.");
        $this->assertEquals($url, $recipe->url, "Url.");
    }

    public function test_open_graph() {
        $path = TestUtils::getDataPath("general_open_graph.html");
        $url = "http://www.example.com/recipes/pan-seared-rib-eye.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Pan-Seared Rib-Eye', $recipe->title, "Title.");
    }

    public function test_og_title_and_og_image() {
        $path = TestUtils::getDataPath("general_ogtitle_ogimage.html");
        $url = "http://www.somerecipes.com/recipes/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Chocolate Chip Cake", $recipe->title, "Title");
        $this->assertEquals("https://assets.somerecipes.com/photos/57b02a1d1b33404414976142/16:9/w_1200,c_limit/mare_beet_and_fennel_soup_with_kefir_v.jpg",
                            $recipe->photo_url, "Photo URL");
    }

    public function test_title_fallback() {
        $path = TestUtils::getDataPath("general_title_fallback.html");
        $url = "http://www.somerecipes.com/recipes/";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Best Chocolate Chip Cake", $recipe->title, "Title");
    }

}
