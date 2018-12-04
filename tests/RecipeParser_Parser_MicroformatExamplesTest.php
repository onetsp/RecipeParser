<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class MicroformatExamplesTest extends TestCase {

    public function test_cookingchanneltv() {
        $path = TestUtils::getDataPath("cookingchanneltv_com_matt_s_lemon_blueberry_muffins_s_cooking_curl.html");
        $url = "http://www.cookingchanneltv.com/recipes/matts-lemon-blueberry-muffins-recipe/index.html";

        $recipe = RecipeParser::parse(file_get_contents($path));
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Matt's Lemon Blueberry Muffins", $recipe->title);
        
        $this->assertEquals(18, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));
    }

}
