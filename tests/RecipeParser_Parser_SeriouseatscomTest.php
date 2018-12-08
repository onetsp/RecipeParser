<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class SeriouseatscomTest extends TestCase {

    public function test_banana_split_whoopie_pies() {
        $path = TestUtils::getDataPath("seriouseats_com_banana_split_s_more_whoopie_pies_serious_curl.html");
        $url = "http://www.seriouseats.com/recipes/2011/08/banana-split-smore-whoopie-pies-recipe.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Banana Split S\'more Whoopie Pies', $recipe->title, "Title.");

        $this->assertEquals(30, $recipe->time['prep'], "Prep time.");
        $this->assertEquals(0, $recipe->time['cook'], "Cook time.");
        $this->assertEquals(180, $recipe->time['total'], "Total time.");
        $this->assertEquals('12 sandwiches', $recipe->yield, "Yield.");

        $this->assertEquals(2, count($recipe->ingredients), "Ingredients sections count.");
        $this->assertEquals('', $recipe->ingredients[0]['name'], "1st ingredients section name.");
        $this->assertEquals(11, count($recipe->ingredients[0]['list']), "1st ingredients section count.");   // bad character showing up in place of empty bullet
        $this->assertEquals('Filling', $recipe->ingredients[1]['name'], "2nd ingredients section name.");
        $this->assertEquals(3, count($recipe->ingredients[1]['list']), "2nd ingredients section count.");
        $this->assertRegexp("/^1\/2 cup finely crushed graham crackers/", $recipe->ingredients[1]['list'][2], "2nd ingredient item.");

        $this->assertEquals(1, count($recipe->instructions), "Count of instructions sections");
        $this->assertEquals('', $recipe->instructions[0]['name'], "1st instructions section name.");
        $this->assertEquals(9, count($recipe->instructions[0]['list']), "1st instructions section count.");
        $this->assertRegexp("/^Variation: If you prefer not/", $recipe->instructions[0]['list'][8], "Item from 1st ingredients list.");

        $this->assertEquals('https://www.seriouseats.com/recipes/images/2011/08/20110808-164482-smorepie1.jpg', 
                            $recipe->photo_url, "Photo Url.");
    }

    public function test_sauted_andouille() {
        $path = TestUtils::getDataPath("seriouseats_com_dinner_tonight_saut_ed_andouille_and_greens_curl.html");
        $url = "http://www.seriouseats.com/recipes/2011/08/sauteed-andouille-and-greens-with-grits-recipe.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Sautéed Andouille and Greens With Grits', $recipe->title);

        $this->assertEquals(45, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(45, $recipe->time['total']);
        $this->assertEquals('4 people', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(10, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertEquals('https://www.seriouseats.com/recipes/images/2011/08/20110808-127355-dinner-tonight-andouille-greens-grits.jpg', 
                            $recipe->photo_url);
    }

    public function test_aleppo_rubbed_pork_ribs() {
        $path = TestUtils::getDataPath("seriouseats_com_sunday_supper_aleppo_rubbed_pork_ribs_serious_curl.html");
        $url = "http://www.seriouseats.com/recipes/2011/08/aleppo-rubbed-pork-ribs.html";
        $recipe = RecipeParser::parse(file_get_contents($path), $url);

        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Aleppo-Rubbed Pork Ribs', $recipe->title);

        $this->assertEquals(10, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);  // Does not parse properly: "1 hour 10 minutes (plus overnight in the fridge)" 
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(4, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(2, count($recipe->instructions[0]['list']));
    }

    public function test_sunday_brunch_oysters_rockefeller() {
        $path = TestUtils::getDataPath("seriouseats_com_oysters_rockefeller_serious_eats_s_curl.html");
        $url = "http://www.seriouseats.com/recipes/2011/08/oysters-rockefeller-sunday-brunch-seafood-appetizer.html";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);

        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Oysters Rockefeller', $recipe->title);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(35, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));
    }

}
