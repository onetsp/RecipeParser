<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class OneOhOneCookbookscomTest extends TestCase {

    public function test_glissade_chocolate_pudding_recipe() {
        $path = TestUtils::getDataPath("101cookbooks_com_glissade_chocolate_pudding_cookbooks_curl.html");
        $url  = "http://www.101cookbooks.com/archives/glissade-chocolate-pudding-recipe.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Glissade Chocolate Pudding', $recipe->title);

        $this->assertEquals(3, $recipe->time['prep']);
        $this->assertEquals(5, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('2-4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^Use the best quality chocolate/', $recipe->description);

        $this->assertEquals('http://www.101cookbooks.com/mt-static/images/food/slippery_chocolate_pudding_recipe.jpg',
                            $recipe->photo_url);
    }

    public function test_lemony_olive_oil_banana_bread_recipe() {
        $path = TestUtils::getDataPath("101cookbooks_com_lemony_olive_oil_banana_bread_cookbooks_curl.html");
        $url  = "http://www.101cookbooks.com/archives/lemony-olive-oil-banana-bread-recipe.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Lemony Olive Oil Banana Bread', $recipe->title);

        $this->assertEquals(10, $recipe->time['prep']);
        $this->assertEquals(50, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);

        // this gets mixed in with instructions.
        //$this->assertEquals('10 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Glaze', $recipe->ingredients[1]['name']);
        $this->assertEquals(3, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^Melissa\'s recipe instructs/', $recipe->description);

        $this->assertEquals('http://www.101cookbooks.com/mt-static/images/food/lemon_banana_bread_recipe.jpg',
                            $recipe->photo_url);
    }

    public function test_roasted_squash_chile_and_mozzarella_salad_recipe() {

        $path = TestUtils::getDataPath("101cookbooks_com_roasted_squash_chile_and_mozzarella_salad_curl.html");
        $url  = "http://www.101cookbooks.com/archives/roasted-squash-chile-and-mozzarella-salad-recipe.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Roasted Squash, Chile, and Mozzarella Salad', $recipe->title);

        $this->assertEquals(5, $recipe->time['prep']);
        $this->assertEquals(25, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.101cookbooks.com/mt-static/images/food/kinfolk_squash_salad_recipe.jpg',
                            $recipe->photo_url);
    }

    public function test_tofu_amaranth_salad_recipe() {
        $path = TestUtils::getDataPath("101cookbooks_com_tofu_amaranth_salad_cookbooks_curl.html");
        $url  = "http://www.101cookbooks.com/archives/tofu-amaranth-salad-recipe.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Tofu Amaranth Salad', $recipe->title);

        $this->assertEquals(10, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(1, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www.101cookbooks.com/mt-static/images/food/tofu_amaranth_salad_recipe.jpg',
                            $recipe->photo_url);
    }

}
