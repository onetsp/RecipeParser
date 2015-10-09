<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_BonappetitecomTest extends PHPUnit_Framework_TestCase {

    public function test_beet_and_fennel_soup() {
        $path = "data/bonappetit_com_beet_and_fennel_soup_with_kefir_curl.html";
        $url = "http://www.bonappetit.com/recipes/quick-recipes/2011/01/beet_and_fennel_soup_with_kefir";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Beet and Fennel Soup with Kefir", $recipe->title);
        $this->assertEquals("4 servings", $recipe->yield);
        $this->assertEquals("30", $recipe->time['prep']);
        $this->assertEquals("50", $recipe->time['total']);

        $this->assertEquals(9, count($recipe->ingredients[0]['list']));
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertEquals(
            'http://www.bonappetit.com/wp-content/uploads/2009/11/mare_beet_and_fennel_soup_with_kefir_v.jpg',
            $recipe->photo_url);
    }

    public function test_chia_hot_chocolate() {
        $path = "data/bonappetit_com_chai_spiced_hot_chocolate_bon_app_curl.html";
        $url = "http://www.bonappetit.com/recipes/quick-recipes/2010/02/chai_spiced_hot_chocolate";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Chai-Spiced Hot Chocolate", $recipe->title);
        $this->assertEquals("6 servings", $recipe->yield);
        $this->assertEquals("15", $recipe->time['prep']);
        $this->assertEquals("25", $recipe->time['total']);

        #$this->assertEquals(10, count($recipe->ingredients[0]['list']));  // Where did the ingredients go in this recipe?!?
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
    }

    public function test_flourless_chocolate_cake() {
        $path = "data/bonappetit_com_flourless_chocolate_cake_with_caramel_sauce_curl.html";
        $url = "http://www.bonappetit.com/recipes/2002/10/flourless_chocolate_cake_with_caramel_sauce";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Flourless Chocolate Cake with Caramel Sauce", $recipe->title);
        $this->assertEquals("10 to 12 servings", $recipe->yield);

        $this->assertEquals(5, count($recipe->ingredients[0]['list']));
        $this->assertEquals(6, count($recipe->ingredients[1]['list']));
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
        $this->assertEquals(2, count($recipe->instructions[1]['list']));
    }

    public function test_sunday_roast_chicken() {
        $path = "data/bonappetit_com_special_sunday_roast_chicken_bon_app_curl.html";
        $url = "http://www.bonappetit.com/recipes/2009/02/special_sunday_roast_chicken";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Special Sunday Roast Chicken", $recipe->title);
        $this->assertEquals("4 servings", $recipe->yield);

        $this->assertEquals(14, count($recipe->ingredients[0]['list']));
        $this->assertEquals(4, count($recipe->instructions[0]['list']));
    }

    public function test_yogurt_granola() {
        $path = "data/bonappetit_com_yogurt_with_granola_tropical_fruit_and_curl.html";
        $url = "http://www.bonappetit.com/recipes/quick-recipes/2008/07/yogurt_with_granola";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        // Should not have "Hungry for more" phrase in instructions
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
    }

    public function test_harvest_crisp() {
        $path = "data/bonappetit_com_harvest_pear_crisp_with_candied_ginger_curl.html";
        $url = "http://www.bonappetit.com/recipes/2009/11/harvest_pear_crisp_with_candied_ginger";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        // Watch for &ndash; in serving size
        $this->assertEquals("8-10 servings", $recipe->yield);
    }

}
