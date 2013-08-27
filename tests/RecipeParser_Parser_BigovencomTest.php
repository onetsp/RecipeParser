<?php

require_once './bootstrap.php';

class RecipeParser_Parser_BigovencomTest extends PHPUnit_Framework_TestCase {

    public function test_bigoven_user_submitted() {
        $path = "data/clipped/bigoven_com_banana_bread_bigoven_curl.html";
        $url = "http://www.bigoven.com/recipe/334322/Banana-Bread";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        


        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Banana Bread", $recipe->title);
        
        $this->assertEquals(180, $recipe->time['total']);
        
        $this->assertEquals('20 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(4, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));
    }

}

?>
