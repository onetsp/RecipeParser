<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class BhgcomTest extends TestCase {

    public function test_eggnog_cheesecake() {
        $path_orig = "data/bhg_com_bhgs_newest_eggnog_cheesecake_with_candied_curl.html";
        $url = "http://www.bhg.com/recipe/cheesecake/eggnog-cheesecake-with-candied-kumquats/";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Eggnog Cheesecake with Candied Kumquats', $recipe->title);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(12, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        
        $this->assertEquals('12 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(13, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Candied kumquats', $recipe->ingredients[1]['name']);
        $this->assertEquals(3, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(8, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://images.meredith.com/content/dam/bhg/Images/recipe/36/R142956.jpg.rendition.largest.ss.jpg', 
                            $recipe->photo_url);
    }

    public function test_tomatillo_chicken_soup() {
        $path_orig = "data/bhg_com_bhgs_newest_tomatillo_chicken_soup_curl.html";
        $url = "http://www.bhg.com/recipe/chicken/tomatillo-chicken-soup/";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Tomatillo Chicken Soup', $recipe->title);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(420, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);

        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://images.meredith.com/content/dam/bhg/Images/recipe/32/R155066.jpg.rendition.largest.ss.jpg',
                            $recipe->photo_url);
    }

}
