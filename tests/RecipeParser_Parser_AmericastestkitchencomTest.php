<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class AmericastestkitchencomTest extends TestCase {

    public function test_simple_atk_parser() {
        $path = "data/americastestkitchen_com_thick_cut_sweet_potato_fries_america_s_test_kitchen_clipped.html";
        $url = "http://www.americastestkitchen.com/recipes/7775-thick-cut-sweet-potato-fries";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertGreaterThan(5, strlen($recipe->title));
        $this->assertEquals($url, $recipe->url);

        #$this->assertEquals(0, $recipe->time['prep']);
        #$this->assertEquals(0, $recipe->time['cook']);
        #$this->assertEquals(0, $recipe->time['total']);
        #$this->assertEquals('', $recipe->yield);

        $this->assertGreaterThan(0, count($recipe->ingredients));
        $this->assertGreaterThan(0, count($recipe->ingredients[0]['list']));

        $this->assertGreaterThan(0, count($recipe->instructions));
        $this->assertGreaterThan(0, count($recipe->instructions[0]['list']));

        $this->assertRegexp('/^http.+cloudfront.+.jpg$/', $recipe->photo_url);
    }

}
