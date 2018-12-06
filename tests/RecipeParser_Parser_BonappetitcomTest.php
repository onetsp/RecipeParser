<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class BonappetitecomTest extends TestCase {

    public function test_beet_and_fennel_soup() {
        $path = TestUtils::getDataPath("bonappetit_com_beet_and_fennel_soup_with_kefir_curl.html");
        $url = "http://www.bonappetit.com/recipes/quick-recipes/2011/01/beet_and_fennel_soup_with_kefir";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Beet and Fennel Soup with Kefir", $recipe->title, "Title");
        $this->assertEquals("4 servings", $recipe->yield, "Yield");
        $this->assertEquals("30", $recipe->time['prep'], "Prep time");
        $this->assertEquals("50", $recipe->time['total'], "Total time");

        $this->assertEquals(9, count($recipe->ingredients[0]['list']), "Ingredients list count.");
        $this->assertEquals(2, count($recipe->instructions[0]['list']), "Instructions list count.");

        $this->assertEquals(
            'https://assets.bonappetit.com/photos/57b02a1d1b33404414976142/16:9/w_1200,c_limit/mare_beet_and_fennel_soup_with_kefir_v.jpg',
            $recipe->photo_url, "Photo URL");
    }

    public function test_chia_hot_chocolate() {
        $path = TestUtils::getDataPath("bonappetit_com_chai_spiced_hot_chocolate_bon_appetit_curl.html");
        $url = "http://www.bonappetit.com/recipes/quick-recipes/2010/02/chai_spiced_hot_chocolate";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Chai-Spiced Hot Chocolate", $recipe->title, "Title.");
        $this->assertEquals("6 servings", $recipe->yield, "Yield.");
        $this->assertEquals("15", $recipe->time['prep'], "Prep time.");
        $this->assertEquals("25", $recipe->time['total'], "Total time.");

        $this->assertEquals(10, count($recipe->ingredients[0]['list']), "Ingredients list count.");
        $this->assertEquals(3, count($recipe->instructions[0]['list']), "Instructions list count.");
    }

    public function test_flourless_chocolate_cake() {
        $path = TestUtils::getDataPath("bonappetit_com_flourless_chocolate_cake_with_caramel_sauce_curl.html");
        $url = "http://www.bonappetit.com/recipes/2002/10/flourless_chocolate_cake_with_caramel_sauce";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Flourless Chocolate Cake with Caramel Sauce", $recipe->title, "Title.");
        $this->assertEquals("10 to 12 servings", $recipe->yield, "Yield.");

        $this->assertEquals(5, count($recipe->ingredients[0]['list']), "1st ingredients count.");
        $this->assertEquals(7, count($recipe->ingredients[1]['list']), "2nd ingredients count.");
        $this->assertEquals(1, count($recipe->instructions[0]['list']), "1st instructions count.");
        $this->assertEquals(2, count($recipe->instructions[1]['list']), "2nd instructions count.");
    }

    public function test_sunday_roast_chicken() {
        $path = TestUtils::getDataPath("bonappetit_com_special_sunday_roast_chicken_bon_appetit_curl.html");
        $url = "http://www.bonappetit.com/recipes/2009/02/special_sunday_roast_chicken";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Special Sunday Roast Chicken", $recipe->title);
        $this->assertEquals("4 servings", $recipe->yield);

        $this->assertEquals(14, count($recipe->ingredients[0]['list']));
        $this->assertEquals(4, count($recipe->instructions[0]['list']));
    }

    public function test_yogurt_granola() {
        $path = TestUtils::getDataPath("bonappetit_com_yogurt_with_granola_tropical_fruit_and_curl.html");
        $url = "http://www.bonappetit.com/recipes/quick-recipes/2008/07/yogurt_with_granola";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        // Should not have "Hungry for more" phrase in instructions
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
    }

    public function test_harvest_crisp() {
        $path = TestUtils::getDataPath("bonappetit_com_harvest_pear_crisp_with_candied_ginger_curl.html");
        $url = "http://www.bonappetit.com/recipes/2009/11/harvest_pear_crisp_with_candied_ginger";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        // Watch for &ndash; in serving size
        $this->assertEquals("8 to 10 servings", $recipe->yield);
    }

}
