<?php

require_once "../bootstrap.php";

class RecipeParser_Parser_Thekitchencom_Test extends PHPUnit_Framework_TestCase {

    public function test_recipe_olive_oil_and_whisky_carrot_cake_recipes_from_the_kitchn_195594() {
        $path = "data/thekitchn_com_olive_oil_and_whiskey_carrot_cake_curl.html";
        $url  = "http://www.thekitchn.com/recipe-olive-oil-and-whisky-carrot-cake-recipes-from-the-kitchn-195594";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Olive Oil and Whisky Carrot Cake', $recipe->title);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(14, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Cream cheese frosting', $recipe->ingredients[1]['name']);
        $this->assertEquals(5, count($recipe->ingredients[1]['list']));

        $this->assertEquals(3, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(7, count($recipe->instructions[0]['list']));
        $this->assertEquals('Cream cheese frosting', $recipe->instructions[1]['name']);
        $this->assertEquals(2, count($recipe->instructions[1]['list']));
        $this->assertEquals('To assemble the cake', $recipe->instructions[2]['name']);
        $this->assertEquals(2, count($recipe->instructions[2]['list']));

        $this->assertEquals('20-30 servings, depending on slice size', $recipe->yield);

        $this->assertRegExp('/pixstatic.com\/524c597d697ab0613c005d69\._w\.1500_s\.fit_\.jpg/',
                            $recipe->photo_url);
    }

    public function test_cozy_winter_recipe_onepot_past_135992() {
        $path = "data/thekitchn_com_one_pot_pasta_e_fagioli_italian_curl.html";
        $url  = "http://www.thekitchn.com/cozy-winter-recipe-onepot-past-135992";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('One-Pot Pasta e Fagioli (Italian Bean and Pasta Stew)', $recipe->title);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Beans', $recipe->ingredients[0]['name']);
        $this->assertEquals(3, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Soup', $recipe->ingredients[1]['name']);
        $this->assertEquals(11, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(12, count($recipe->instructions[0]['list']));

        $this->assertEquals('8 to 10 servings', $recipe->yield);

        $this->assertRegExp('/pixstatic\.com\/525c2633dbfa3f0d8200ab91\._w\.1500_s\.fit_\.jpg/',
                            $recipe->photo_url);
    }

    public function test_recipe_cherry_cobbler_recipes_from_the_kitchn_195824() {
        $path = "data/thekitchn_com_tart_cherry_crumble_from_the_kitchn_curl.html";
        $url  = "http://www.thekitchn.com/recipe-cherry-cobbler-recipes-from-the-kitchn-195824";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Tart Cherry Crumble', $recipe->title);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Cherries', $recipe->ingredients[0]['name']);
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Crumble', $recipe->ingredients[1]['name']);
        $this->assertEquals(6, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertEquals('6 servings', $recipe->yield);

        $this->assertRegExp('/pixstatic\.com\/5256c63f697ab06b79002f6e\._w\.1500_s\.fit_\.jpg/',
                            $recipe->photo_url);
    }

}
