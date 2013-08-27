<?php

require_once './bootstrap.php';

class ImporterMicroformatExamplesTest extends PHPUnit_Framework_TestCase {

    public function test_cookingchanneltv() {
        $path = "data/clipped/cookingchanneltv_com_matt_s_lemon_blueberry_muffins_s_cooking_curl.html";
        $url = "http://www.cookingchanneltv.com/recipes/matts-lemon-blueberry-muffins-recipe/index.html";

        $recipe = Importer::parse(file_get_contents($path));
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Matt's Lemon Blueberry Muffins", $recipe->title);
        
        $this->assertEquals(18, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));
    }

}

?>
