<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_BravotvcomTest extends PHPUnit_Framework_TestCase {

    public function test_cleanup_times() {
        $time = RecipeParser_Parser_Bravotvcom::cleanupTime(' 2 Hours ');
        $this->assertEquals('2 hours', $time);

        $time = RecipeParser_Parser_Bravotvcom::cleanupTime(' Under 2 Hours ');
        $this->assertEquals('2 hours', $time);

        $time = RecipeParser_Parser_Bravotvcom::cleanupTime(' Two  Hours ');
        $this->assertEquals('2 hours', $time);
    }

    public function test_cornbread_topped_chili() {
        $path = "data/bravotv_com_cornbread_topped_chilli_con_carne_finder_curl.html";
        $url = "http://www.bravotv.com/foodies/recipes/cornbread-topped-chilli-con-carne";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Cornbread-Topped Chilli Con Carne', $recipe->title);
        $this->assertEquals('240', $recipe->time['total']);
        $this->assertEquals('Serves up to 20', $recipe->yield);
        $this->assertRegExp('/^There are few more welcome sights/', $recipe->description);
        $this->assertRegExp('/^.*this is especially good cold the next morning.*$/', $recipe->notes);

        $this->assertEquals(3, count($recipe->ingredients));
        $this->assertEquals(15, count($recipe->ingredients[0]['list']));
        $this->assertEquals(10, count($recipe->ingredients[1]['list']));
        $this->assertEquals(7, count($recipe->ingredients[2]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(10, count($recipe->instructions[0]['list']));

        $this->assertEquals(
            'http://www.cookstr.com/photos/recipes/17/original/recipe-17.jpg',
            $recipe->photo_url);
    }

    public function test_lamb_scotch_egg() {
        $path = "data/bravotv_com_ground_lamb_scotch_egg_sweet_potato_curl.html";
        $url = "http://www.bravotv.com/foodies/recipes/ground-lamb-scotch-egg-sweet-potato-fries-and-tomato-tartnbsp";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Ground Lamb Scotch Egg, Sweet Potato Fries and Tomato Tart', $recipe->title);
        $this->assertEquals('240', $recipe->time['total']);
        $this->assertEquals('', $recipe->yield);
        $this->assertEquals('', $recipe->description);

        $this->assertEquals(5, count($recipe->ingredients));
        $this->assertEquals(14, count($recipe->ingredients[0]['list']));
        $this->assertEquals(7, count($recipe->ingredients[1]['list']));
        $this->assertEquals(3, count($recipe->ingredients[2]['list']));
        $this->assertEquals(6, count($recipe->ingredients[3]['list']));
        $this->assertEquals(5, count($recipe->ingredients[4]['list']));
        $this->assertEquals(5, count($recipe->instructions));
        $this->assertEquals(2, count($recipe->instructions[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[1]['list']));
        $this->assertEquals(1, count($recipe->instructions[2]['list']));
        $this->assertEquals(1, count($recipe->instructions[3]['list']));
        $this->assertEquals(2, count($recipe->instructions[4]['list']));
        $this->assertEquals('Ground lamb scotch egg', $recipe->instructions[0]['name']);
        $this->assertEquals('Tart dough', $recipe->instructions[4]['name']);
    }

    public function test_grouper_with_gnocci() {
        $path = "data/bravotv_com_roast_grouper_with_gnocchi_peas_bacon_curl.html";
        $url = "http://www.bravotv.com/foodies/recipes/roast-grouper-with-gnocchi-peas-bacon-and-parsnip";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Roast Grouper with Gnocchi, Peas, Bacon, and Parsnip', $recipe->title);
        $this->assertEquals('120', $recipe->time['total']);
        $this->assertEquals('', $recipe->yield);

        $this->assertEquals(3, count($recipe->ingredients));
        $this->assertEquals(3, count($recipe->ingredients[0]['list']));
        $this->assertEquals(6, count($recipe->ingredients[1]['list']));
        $this->assertEquals(8, count($recipe->ingredients[2]['list']));
        $this->assertEquals(3, count($recipe->instructions));
        $this->assertEquals(2, count($recipe->instructions[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[1]['list']));
        $this->assertEquals(2, count($recipe->instructions[2]['list']));
    }

    public function test_pork_chop() {
        $path = "data/bravotv_com_roasted_pork_chop_with_rosemary_thyme_curl.html";
        $url = "http://www.bravotv.com/foodies/recipes/roasted-pork-chop-with-rosemary-thyme-amp-garlic";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Roasted Pork Chop with Rosemary, Thyme & Garlic', $recipe->title);
        $this->assertEquals('45', $recipe->time['prep']);
        $this->assertEquals('60', $recipe->time['total']);
        $this->assertEquals('6 Servings', $recipe->yield);
        $this->assertEquals('', $recipe->description);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(13, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(5, count($recipe->instructions[0]['list']));
        $this->assertRegExp('/^Sear the pork/', $recipe->instructions[0]['list'][0]);
    }

    public function test_colorado_sirloin() {
        $path = "data/bravotv_com_seared_colorado_sirloin_chanterelle_and_ruby_curl.html";
        $url = "http://www.bravotv.com/foodies/recipes/seared-colorado-sirloin-chanterelle-and-ruby-chard";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Seared Colorado Sirloin, Chanterelle and Ruby Chard', $recipe->title);
        $this->assertEquals('120', $recipe->time['prep']);
        $this->assertEquals('120', $recipe->time['total']);
        $this->assertEquals('6 Servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));
        $this->assertEquals(2, count($recipe->instructions));
        $this->assertEquals(5, count($recipe->instructions[0]['list']));
        $this->assertEquals(1, count($recipe->instructions[1]['list']));
    }

    public function test_sous_vide_chicken() {
        $path = "data/bravotv_com_sous_vide_chicken_mushrooms_yams_lobster_curl.html";
        $url = "http://www.bravotv.com/foodies/recipes/sous-vide-chicken-mushrooms-yams-lobster-sauce-amp-lobster-hash";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        // Only testing for instruction and ingredient sections
        $this->assertEquals(7, count($recipe->ingredients));
        $this->assertEquals(8, count($recipe->instructions));
    }

}
