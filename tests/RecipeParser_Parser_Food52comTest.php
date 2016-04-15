<?php

require_once "../bootstrap.php";

class RecipeParser_Parser_Food52comTest extends PHPUnit_Framework_TestCase {

    public function test_20123_banana_cake_with_penuche_frosting() {
        $path = "data/food52_com_banana_cake_with_penuche_frosting_on_curl.html";
        $url  = "http://food52.com/recipes/20123-banana-cake-with-penuche-frosting";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Banana Cake with Penuche Frosting', $recipe->title);
        $this->assertEquals('Food52 (Lindsay-Jean Hard)', $recipe->credits);
        $this->assertEquals('1 2-layer cake', $recipe->yield);

        $this->assertRegExp('/2014-0318_WC_banana-cake-panocha-frosting-012.jpg/',
                            $recipe->photo_url);

        $this->assertRegExp('/^The correct name of this frosting.*denser crumb.$/s', $recipe->notes);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(14, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(8, count($recipe->instructions[0]['list']));
    }

    public function test_33370_brown_butter_apple_tart() {
        $path = "data/food52_com_brown_butter_apple_tart_on_food_curl.html";
        $url  = "http://food52.com/recipes/33370-brown-butter-apple-tart";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Brown Butter Apple Tart', $recipe->title);
        $this->assertEquals('Food52 (Phyllis Grant)', $recipe->credits);
        $this->assertEquals('6 servings', $recipe->yield);

        $this->assertRegExp('/^This is inspired by .* a tangy green salad.$/s', $recipe->notes);

        $this->assertRegExp('/appletgalette3.jpg/', $recipe->photo_url);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(8, count($recipe->instructions[0]['list']));
    }

    public function test_33952_challah_bread_pudding_with_raspberries() {
        $path = "data/food52_com_challah_bread_pudding_with_raspberries_on_curl.html";
        $url  = "http://food52.com/recipes/33952-challah-bread-pudding-with-raspberries";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Challah Bread Pudding with Raspberries', $recipe->title);
        $this->assertEquals('Food52 (Kendra Vaculin)', $recipe->credits);
        $this->assertEquals('5 to 6 servings', $recipe->yield);

        $this->assertRegExp('/2015-0217_raspberry-challah-bread-pudding_bobbi-lin-3321.jpg/', $recipe->photo_url);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));
    }

}
