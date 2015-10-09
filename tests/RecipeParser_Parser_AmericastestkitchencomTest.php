<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_AmericastestkitchencomTest extends PHPUnit_Framework_TestCase {

    public function test_simple_atk_parser() {
        $path = "data/americastestkitchen_com_thick_cut_sweet_potato_fries_america_s_test_kitchen_clipped.html";
        $url = "http://www.americastestkitchen.com/recipes/7775-thick-cut-sweet-potato-fries";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
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
