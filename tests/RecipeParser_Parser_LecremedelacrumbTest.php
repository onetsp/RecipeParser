<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_LecremedelacrumbTest extends PHPUnit_Framework_TestCase {

    public function test_petite_vanilla_bean_scones() {
        $path = "data/lecremedelacrumb_com_best_bbq_hot_dogs.html";
        $url = "http://www.lecremedelacrumb.com/2014/07/best-bbq-hot-dogs-with-avocado-grilled-onions.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

//        $this->assertEquals('Petite Vanilla Bean Scones', $recipe->title);

//        $this->assertEquals(20, $recipe->time['prep']);
//        $this->assertEquals(20, $recipe->time['cook']);
//        $this->assertEquals(0, $recipe->time['total']);
//        $this->assertEquals('12 servings', $recipe->yield);

//        $this->assertEquals(2, count($recipe->ingredients));
//        $this->assertEquals('Scones', $recipe->ingredients[0]['name']);
//        $this->assertEquals(8, count($recipe->ingredients[0]['list']));
//        $this->assertEquals('Glaze', $recipe->ingredients[1]['name']);
//        $this->assertEquals(4, count($recipe->ingredients[1]['list']));

//        $this->assertEquals(2, count($recipe->instructions));
//        $this->assertEquals('', $recipe->instructions[0]['name']);
//        $this->assertEquals(8, count($recipe->instructions[0]['list']));
//        $this->assertEquals('Vanilla glaze', $recipe->instructions[1]['name']);
//        $this->assertEquals(2, count($recipe->instructions[1]['list']));
//
//        $this->assertEquals('http://tastykitchen.com/recipes/files/2010/04/4495331454_fcb27f08ce_o-420x280.jpg',
//                            $recipe->photo_url);
    }

}
