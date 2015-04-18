<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_MarthastewartcomTest extends PHPUnit_Framework_TestCase {

    public function test_emeril_chorizo_burgers() {
        $path = "data/marthastewart_com_emerils_pork_and_chorizo_burgers_with_curl.html";
        $url = "http://www.marthastewart.com/284367/emerils-pork-and-chorizo-burgers-with-gr?czone=food%2Fbest-grilling-recipes%2Fgrilling-recipes";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(30, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertRegExp('/You can shape the burgers/', $recipe->notes);

        $this->assertEquals(12, count($recipe->ingredients[0]['list']));
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertStringEndsWith('med104695_0609_spicy_burger_vert.jpg?itok=im3kHyO6', $recipe->photo_url);
    }

    public function test_cajun_shrimp() {
        $path = "data/marthastewart_com_sauteed_cajun_shrimp_martha_stewart_curl.html";
        $url = "http://www.marthastewart.com/255277/sauteed-cajun-shrimp?czone=food%2Fdinner-tonight-center%2Fdinner-tonight-main-courses";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(20, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(25, $recipe->time['total']);
        $this->assertEquals('4 servings', $recipe->yield);

        $this->assertRegExp('/^$/', $recipe->notes);
    
        $this->assertEquals(8, count($recipe->ingredients[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
    }

    public function test_strawberry_tart() {
        $path = "data/marthastewart_com_strawberry_tart_martha_stewart_curl.html";
        $url = "http://www.marthastewart.com/340929/strawberry-tart?czone=food%2Fproduce-guide-cnt%2Fspring-produce-recipes";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(150, $recipe->time['total']);
        $this->assertEquals('8 servings', $recipe->yield);

        $this->assertRegExp('/Gently cut around to remove only the base/', $recipe->notes);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals(4, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Crust', $recipe->ingredients[0]['name']);
        $this->assertEquals(4, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Filling', $recipe->ingredients[1]['name']);
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(6, count($recipe->instructions[0]['list']));
        $this->assertRegExp('/^Make the crust: Preheat oven/', $recipe->instructions[0]['list'][0]);
    }

}
