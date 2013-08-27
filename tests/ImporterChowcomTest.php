<?php

require_once './bootstrap.php';

class ImporterChowcomTest extends PHPUnit_Framework_TestCase {

    public function test_herb_omelets() {
        $path = "data/clipped/chow_com_herb_omelets_chow_curl.html";
        $url = "http://www.chow.com/recipes/28801-herb-omelets";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Herb Omelets', $recipe->title);

        $this->assertEquals('4 thin 8-inch omelets', $recipe->yield);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));
        $this->assertEquals('1/3 cup crème fraîche or sour cream', $recipe->ingredients[0]['list'][6]);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^These thin-as-crêpe omelets/', $recipe->description);

        $this->assertEquals('http://search.chow.com/thumbnail/480/0/www.chowstatic.com/assets/2010/09/28801_herbed_omlettes_2_620.jpg', $recipe->photo_url);
    }

    public function test_hungarian_chocolate_torte() {
        $path = "data/clipped/chow_com_hungarian_chocolate_walnut_torte_chow_curl.html";
        $url = "http://www.chow.com/recipes/29536-hungarian-chocolate-walnut-torte";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Hungarian Chocolate-Walnut Torte', $recipe->title);

        $this->assertEquals('12 servings', $recipe->yield);
        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals(9, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://search.chow.com/thumbnail/480/0/www.chowstatic.com/assets/2011/03/29536_hungarian_chocolate_torte_620.jpg', $recipe->photo_url);
    }

    public function test_red_velvet_cake() {
        $path = "data/clipped/chow_com_red_velvet_cake_chow_curl.html";
        $url = "http://www.chow.com/recipes/29310-red-velvet-cake";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Red Velvet Cake', $recipe->title);

        $this->assertEquals('1 (9-inch) layer cake, or 12 servings', $recipe->yield);
        $this->assertEquals(17, count($recipe->ingredients[0]['list']));
        $this->assertEquals(9, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/This moist cocoa cake .*$/s', $recipe->description);

        $this->assertEquals('http://search.chow.com/thumbnail/480/0/www.chowstatic.com/assets/2011/02/29310_red_velvet_cake_3_620.jpg', $recipe->photo_url);
    }

    public function test_roasted_sardines() {
        $path = "data/clipped/chow_com_roasted_sardines_with_smashed_potatoes_chow_curl.html";
        $url = "http://www.chow.com/recipes/29617-roasted-sardines-with-smashed-potatoes";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Roasted Sardines with Smashed Potatoes', $recipe->title);

        $this->assertEquals('4 servings', $recipe->yield);
        $this->assertEquals(17, count($recipe->ingredients[0]['list']));
        $this->assertEquals(9, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/Fresh sardines are full of healthy omega-3/', $recipe->description);

        $this->assertEquals('http://search.chow.com/thumbnail/480/0/www.chowstatic.com/assets/2011/05/29617_roasted_sardines_potatoes_2_620.jpg', $recipe->photo_url);
    }

    public function test_white_peach_sangria() {
        $path = "data/clipped/chow_com_white_peach_sangr_a_chow_curl.html";
        $url = "http://www.chow.com/recipes/29663-white-peach-sangria";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('White Peach Sangría', $recipe->title);

        $this->assertEquals('6 servings', $recipe->yield);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^.* no getting around .*$/s', $recipe->description);

        $this->assertEquals('http://search.chow.com/thumbnail/480/0/www.chowstatic.com/assets/2011/05/29663_white_peach_sangria_620.jpg', $recipe->photo_url);
    }

}

?>
