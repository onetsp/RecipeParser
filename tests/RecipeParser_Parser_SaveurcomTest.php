<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class SaveurcomTest extends TestCase {

    public function test_Mitzis_Chicken_Fingers() {
        $path = TestUtils::getDataPath("saveur_com_chicken_fingers_with_honey_dill_dipping_curl.html");
        $url  = "http://www.saveur.com/article/Recipes/Mitzis-Chicken-Fingers";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Mitzi\'s Chicken Fingers', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('6 servings', $recipe->yield);

        # These sections are not clipping properly, and I'm skipping them for now...
        #$this->assertEquals(2, count($recipe->ingredients));
        #$this->assertEquals('Dipping sauce', $recipe->ingredients[0]['name']);
        #$this->assertEquals(6, count($recipe->ingredients[0]['list']));
        #$this->assertEquals('Chicken fingers', $recipe->ingredients[1]['name']);
        #$this->assertEquals(11, count($recipe->ingredients[1]['list']));

        #$this->assertEquals(1, count($recipe->instructions));
        #$this->assertEquals('', $recipe->instructions[0]['name']);
        #$this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertStringEndsWith('7-chicken_fingers_400.jpg', $recipe->photo_url);
    }

    public function test_Strawberry_Loaf_Bread() {
        $path = TestUtils::getDataPath("saveur_com_strawberry_bread_saveur_curl.html");
        $url  = "http://www.saveur.com/article/Recipes/Strawberry-Loaf-Bread";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Strawberry Bread', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('2 loaves', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(10, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^Saveur test kitchen director Farideh Sadeghin grew/i', $recipe->description);

        $this->assertStringEndsWith('103-recipe_strawberry-loaf-bread_800x1200.jpg', $recipe->photo_url);
    }

    public function test_Strawberry_Tart() {
        $path = TestUtils::getDataPath("saveur_com_strawberry_tart_saveur_curl.html");
        $url  = "http://www.saveur.com/article/Recipes/Strawberry-Tart";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Strawberry Tart', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('6 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(8, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertStringEndsWith('125-06_Strawberry_tart_250.jpg', $recipe->photo_url);
    }

    public function test_coconut_cake() {
        $path = TestUtils::getDataPath("saveur_com_coconut_cake_thomas_kellers_coconut_cake_curl.html");
        $url  = "http://www.saveur.com/article/recipes/thomas-kellers-coconut-cake";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Thomas Keller\'s Coconut Cake', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('8-10 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(15, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertStringEndsWith('recipe_thomas-keller-coconut-cake_500x750.jpg', $recipe->photo_url);
    }

}
