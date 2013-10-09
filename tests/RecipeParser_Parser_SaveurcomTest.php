<?php

require_once "../bootstrap.php";

class RecipeParser_Parser_SaveurcomTest extends PHPUnit_Framework_TestCase {

    public function test_Mitzis_Chicken_Fingers() {

        $path = "data/saveur_com_mitzi_chicken_fingers_saveur_com_curl.html";
        $url  = "http://www.saveur.com/article/Recipes/Mitzis-Chicken-Fingers";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Mitzi\'s Chicken Fingers', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        #$this->assertEquals('6 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Dipping sauce', $recipe->ingredients[0]['name']);
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Chicken fingers', $recipe->ingredients[1]['name']);
        $this->assertEquals(11, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www2.worldpub.net/images/saveurmag/7-chicken_fingers_400.jpg',
                            $recipe->photo_url);

    }

    public function test_Ghirardellis_Chocolate_Chip_Bundt_Cake() {

        $path = "data/saveur_com_sponsored_ghirardelli_s_chocolate_chip_bundt_cake_curl.html";
        $url  = "http://www.saveur.com/article/Recipes/sponsored-Recipe-Ghirardellis-Chocolate-Chip-Bundt-Cake";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Ghirardelli\'s Chocolate Chip Bundt Cake', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        #$this->assertEquals('8-10 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^Add some chocolate to the holiday table/', $recipe->description);

        $this->assertEquals('http://www2.worldpub.net/images/saveurmag/7-Ghirardelli_Bundt_Cake.jpg',
                            $recipe->photo_url);

    }

    public function test_Strawberry_Loaf_Bread() {

        $path = "data/saveur_com_strawberry_loaf_bread_saveur_com_curl.html";
        $url  = "http://www.saveur.com/article/Recipes/Strawberry-Loaf-Bread";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Strawberry Bread', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        #$this->assertEquals('2 loaves', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(10, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^Saveur kitchen assistant Farideh Sadeghin grew/', $recipe->description);

        $this->assertEquals('http://www2.worldpub.net/images/saveurmag/103-recipe_strawberry-loaf-bread_800x1200.jpg',
                            $recipe->photo_url);

    }

    public function test_Strawberry_Tart() {

        $path = "data/saveur_com_strawberry_tart_saveur_com_curl.html";
        $url  = "http://www.saveur.com/article/Recipes/Strawberry-Tart";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Strawberry Tart', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        #$this->assertEquals('6 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(8, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://www2.worldpub.net/images/SAV/125-06_Strawberry_tart_250.jpg',
                            $recipe->photo_url);

    }

}
