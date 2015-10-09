<?php

require_once "../bootstrap.php";

class RecipeParser_Parser_Foodnetworkcouk_Test extends PHPUnit_Framework_TestCase {

    public function test_herb_marinated_pork_tenderloins() {
        $path = "data/foodnetwork_co_uk_herb_marinated_pork_fillet_by_ina_curl.html";
        $url  = "http://www.foodnetwork.co.uk/recipes/herb-marinated-pork-tenderloins.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Herb-marinated Pork Fillet', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(10, $recipe->time['prep']);
        $this->assertEquals(15, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('6 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/.*fnstatic.co.uk.*jpg.*/', $recipe->photo_url);
    }

    public function test_jonathans_pepperpot() {
        $path = "data/foodnetwork_co_uk_jonathans_pepperpot_by_jonathan_phang_food_curl.html";
        $url  = "http://www.foodnetwork.co.uk/recipes/jonathans-pepperpot.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Jonathan\'s Pepperpot', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(130, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/.*fnstatic.co.uk.*jpg.*/', $recipe->photo_url);
    }

    public function test_lemon_yoghurt_cake() {
        $path = "data/foodnetwork_co_uk_lemon_yoghurt_cake_by_ina_garten_curl.html";
        $url  = "http://www.foodnetwork.co.uk/recipes/lemon-yoghurt-cake.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Lemon Yoghurt Cake', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(50, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Cake', $recipe->ingredients[0]['name']);
        $this->assertEquals(10, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Glaze', $recipe->ingredients[1]['name']);
        $this->assertEquals(2, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/.*fnstatic.co.uk.*jpg.*/', $recipe->photo_url);
    }

}
