<?php

require_once './bootstrap.php';

class ImporterBbccoukTest extends PHPUnit_Framework_TestCase {

    public function test_baked_pappardelle() {
        $path = "data/clipped/bbc_co_uk_bbc_food_s_baked_pappardelle_with_curl.html";
        $url = "http://www.bbc.co.uk/food/recipes/baked_pappardelle_with_21046";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Baked pappardelle with pancetta and porcini', $recipe->title);
        $this->assertEquals('Bake pasta for a bubbling sharing-at-the-table dish. Can you resist the aroma of cheese, pancetta and porcini?',
                            $recipe->description);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('2 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(8, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(7, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://ichef.bbci.co.uk/food/ic/food_16x9_448/recipes/baked_pappardelle_with_21046_16x9.jpg', 
                            $recipe->photo_url);
    }

    public function test_cherry_chocolate_pavlova() {
        $path = "data/clipped/bbc_co_uk_bbc_food_s_cherry_chocolate_pavlova_curl.html";
        $url = "http://www.bbc.co.uk/food/recipes/cherry_chocolate_pavlova_94685";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Cherry chocolate pavlova', $recipe->title);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('4-6 servings', $recipe->yield);

        $this->assertEquals(4, count($recipe->ingredients));
        $this->assertEquals('Meringue', $recipe->ingredients[0]['name']);
        $this->assertEquals(5, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Topping', $recipe->ingredients[1]['name']);
        $this->assertEquals(7, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Meringue', $recipe->ingredients[2]['name']);
        $this->assertEquals(5, count($recipe->ingredients[2]['list']));
        $this->assertEquals('Topping', $recipe->ingredients[3]['name']);
        $this->assertEquals(7, count($recipe->ingredients[3]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(8, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://ichef.bbci.co.uk/food/ic/food_16x9_448/recipes/cherry_chocolate_pavlova_94685_16x9.jpg',
                            $recipe->photo_url);
    }

    public function test_saag_aloo() {
        $path = "data/clipped/bbc_co_uk_bbc_food_s_saag_aloo_with_curl.html";
        $url = "http://www.bbc.co.uk/food/recipes/saag_aloo_with_roasted_95304";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Saag aloo with roasted gobi curry', $recipe->title);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertEquals(3, count($recipe->ingredients));
        $this->assertEquals('Roasted cauliflower', $recipe->ingredients[0]['name']);
        $this->assertEquals(4, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Vegetable curry', $recipe->ingredients[1]['name']);
        $this->assertEquals(17, count($recipe->ingredients[1]['list']));
        $this->assertEquals('To serve', $recipe->ingredients[2]['name']);
        $this->assertEquals(1, count($recipe->ingredients[2]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(7, count($recipe->instructions[0]['list']));

        $this->assertEquals('', 
                            $recipe->photo_url);
    }

}

?>
