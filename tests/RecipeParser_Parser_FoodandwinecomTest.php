<?php
require_once '../bootstrap.php';
class RecipeParser_Parser_FoodandwinecomTest extends PHPUnit_Framework_TestCase {
    public function test_carrot_sheet_cake() {
        $path = "data/foodandwine_com_carrot_sheet_cake_with_cream_cheese_curl.html";
        $url = "http://www.foodandwine.com/recipes/carrot-sheet-cake-with-cream-cheese-frosting";
        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);
        $this->assertEquals('Carrot Sheet Cake with Cream Cheese Frosting', $recipe->title);
        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(100, $recipe->time['total']);
        $this->assertEquals('1 9-by-13-inch sheet cake', $recipe->yield);
        $this->assertRegExp('/refrigerated for up to 2 days/', $recipe->notes);
        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(21, count($recipe->ingredients[0]['list']));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals('1 cup chopped walnuts', $recipe->ingredients[0]['list'][0]);
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));
        $this->assertEquals('http://cdn-image.foodandwine.com/sites/default/files/styles/200x250/public/original-201202-r-carrot-sheet-cake-with-cream-cheese-frosting.jpg?itok=46guokTW',
            $recipe->photo_url);
    }
    // public function test_chicken_mushroom_saute() {
//         $path = "data/foodandwine_com_chicken_wild_mushroom_and_roasted_garlic_curl.html";
//         $url = "http://www.foodandwine.com/recipes/chicken-wild-mushroom-and-roasted-garlic-saute";
//         $recipe = RecipeParser::parse(file_get_contents($path), $url);
//         if (isset($_SERVER['VERBOSE'])) print_r($recipe);
//         $this->assertEquals('Chicken, Wild Mushroom and Roasted-Garlic Sauté', $recipe->title);
//         $this->assertEquals(0, $recipe->time['prep']);
//         $this->assertEquals(0, $recipe->time['cook']);
//         $this->assertEquals(105, $recipe->time['total']);
//         $this->assertEquals('4 servings', $recipe->yield);
//         $this->assertEquals('', $recipe->notes);
//
//         $this->assertEquals(1, count($recipe->ingredients));
//         $this->assertEquals('', $recipe->ingredients[0]['name']);
//         $this->assertEquals(14, count($recipe->ingredients[0]['list']));
//         $this->assertEquals(1, count($recipe->instructions));
//         $this->assertEquals(5, count($recipe->instructions[0]['list']));
//         $this->assertEquals(
//             'http://www.foodandwine.com/images/sys/200902-r-chick-mushroom.jpg',
//             $recipe->photo_url);
//     }
//     public function test_hanger_steak_with_shallots_and_mushrooms() {
//         $path = "data/foodandwine_com_hanger_steak_with_shallots_and_mushrooms_curl.html";
//         $url = "http://www.foodandwine.com/recipes/hanger-steak-with-shallots-and-mushrooms";
//         $recipe = RecipeParser::parse(file_get_contents($path), $url);
//         if (isset($_SERVER['VERBOSE'])) print_r($recipe);
//         $this->assertEquals("", $recipe->photo_url);  // Don't clip F&W thumbnail when photo is missing.
//     }
//     public function test_cocoa_carrot_cake() {
//         $path = "data/foodandwine_com_cocoa_carrot_cake_with_cocoa_crumble_curl.html";
//         $url = "http://www.foodandwine.com/recipes/cocoa-carrot-cake-with-cocoa-crumble";
//         $recipe = RecipeParser::parse(file_get_contents($path), $url);
//         if (isset($_SERVER['VERBOSE'])) print_r($recipe);
//         $this->assertEquals('Cocoa-Carrot Cake with Cocoa Crumble', $recipe->title);
//         $this->assertEquals(0, $recipe->time['prep']);
//         $this->assertEquals(0, $recipe->time['cook']);
//         $this->assertEquals(210, $recipe->time['total']);
//         $this->assertEquals('2 loaves', $recipe->yield);
//         $this->assertRegExp('/Look for almond flour/', $recipe->notes);
//
//         $this->assertEquals(2, count($recipe->ingredients));
//         $this->assertEquals('Crumble', $recipe->ingredients[0]['name']);
//         $this->assertEquals(6, count($recipe->ingredients[0]['list']));
//         $this->assertEquals('Cake', $recipe->ingredients[1]['name']);
//         $this->assertEquals(13, count($recipe->ingredients[1]['list']));
//         $this->assertEquals(1, count($recipe->instructions));
//         $this->assertEquals('', $recipe->instructions[0]['name']);
//         $this->assertEquals(4, count($recipe->instructions[0]['list']));
//     }
}