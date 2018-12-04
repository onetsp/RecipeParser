<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class CookscomTest extends TestCase {

    public function test_nilla_wafers() {
        $path = TestUtils::getDataPath("cooks_com_nilla_wafers_and_no_bake_jello_curl.html");
        $url = "http://www.cooks.com/rec/doc/0,2213,158182-232201,00.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Nilla Wafers And No-Bake Jello Cheesecake", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));

        // Expect that the line starting with "Submitted by:" has been stripped.
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
    }

    public function test_baklava() {
        $path = TestUtils::getDataPath("cooks_com_baklava_greek_version_cooks_com_curl.html");
        $url = "http://www.cooks.com/rec/view/0,1918,148188-224203,00.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Baklava - Greek Version", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));
        $this->assertEquals(5, count($recipe->ingredients[1]['list']));
        $this->assertEquals(8, count($recipe->instructions[0]['list']));
    }

    public function test_kathys_quiche() {
        $path = TestUtils::getDataPath("cooks_com_kathys_quiche_cooks_com_curl.html");
        $url = "http://www.cooks.com/rec/view/0,1826,148162-235202,00.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Kathy's Quiche", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(2, count($recipe->ingredients[0]['list']));
        $this->assertEquals(11, count($recipe->ingredients[1]['list']));
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
        $this->assertEquals(2, count($recipe->instructions[1]['list']));
    }

    public function test_lemon_crumb_bars() {
        $path = TestUtils::getDataPath("cooks_com_lemon_crumb_bars_cooks_com_curl.html");
        $url = "http://www.cooks.com/rec/view/0,1910,150169-234207,00.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);
        
        $this->assertEquals("Lemon Crumb Bars", $recipe->title);
        $this->assertEquals($url, $recipe->url);
        $this->assertEquals(4, count($recipe->ingredients[0]['list']));
        $this->assertEquals(4, count($recipe->ingredients[1]['list']));
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
        $this->assertEquals(1, count($recipe->instructions[1]['list']));
        $this->assertEquals(3, count($recipe->instructions[2]['list']));
    }

}
