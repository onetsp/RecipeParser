<?php

require_once '../bootstrap.php';

class Import_Kingarthurflourcom_Test extends PHPUnit_Framework_TestCase {

    public function test_cream_pie() {
        $path = "data/kingarthurflour_com_cream_pie.html";
        $url = "http://www.kingarthurflour.com/recipes/chocolate-cream-pie-recipe";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Chocolate Cream Pie', $recipe->title);

        $this->assertEquals(90, $recipe->time['prep']);
        $this->assertEquals(25, $recipe->time['cook']);
        $this->assertEquals(265, $recipe->time['total']);
        $this->assertEquals('1 9-inch deep dish pie, 12 servings', $recipe->yield);

        $this->assertEquals(3, count($recipe->ingredients));
        $this->assertEquals('Crust', $recipe->ingredients[0]['name']);
        $this->assertEquals(5, count($recipe->ingredients[0]['list']));
        $this->assertEquals('1 1/2 cups King Arthur Unbleached All-Purpose Flour', $recipe->ingredients[0]['list'][0]);
        $this->assertEquals('Filling', $recipe->ingredients[1]['name']);
        $this->assertEquals(10, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Topping', $recipe->ingredients[2]['name']);
        $this->assertEquals(3, count($recipe->ingredients[2]['list']));
        $this->assertEquals("1 cup heavy cream", $recipe->ingredients[2]['list'][0]);

        $this->assertEquals(5, count($recipe->instructions));
        $this->assertEquals('Crust', $recipe->instructions[0]['name']);
        $this->assertEquals(10, count($recipe->instructions[0]['list']));
        $this->assertEquals('Filling', $recipe->instructions[1]['name']);
        $this->assertEquals(8, count($recipe->instructions[1]['list']));
        $this->assertEquals('Place the heavy cream in a chilled mixing bowl.', $recipe->instructions[2]['list'][0]);

        $this->assertEquals('http://www.kingarthurflour.com/shop-img/1232984293355.jpg', 
                            $recipe->photo_url);
    }

    public function test_golden_vanilla_cake() {
        $path = "data/kingarthurflour_com_golden_vanilla_cake.html";
        $url = "http://www.kingarthurflour.com/recipes/golden-vanilla-cake-recipe";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Golden Vanilla Cake', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(8, count($recipe->ingredients[0]['list']));
        $this->assertEquals('2 cups sugar, Baker\'s Special Sugar preferred', $recipe->ingredients[0]['list'][0]);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(13, count($recipe->instructions[0]['list']));
    }

    public function test_pretzels() {
        $path = "data/kingarthurflour_com_pretzels.html";
        $url = "http://www.kingarthurflour.com/recipes/hot-buttered-soft-pretzels-recipe";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Hot Buttered Soft Pretzels', $recipe->title);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(9, $recipe->time['cook']);
        $this->assertEquals(81, $recipe->time['total']);
        $this->assertEquals('8 large pretzels', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Dough', $recipe->ingredients[0]['name']);
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Topping', $recipe->ingredients[1]['name']);
        $this->assertEquals(4, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(11, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^Pretzels are available crisp/', $recipe->description);

        $this->assertEquals('http://www.kingarthurflour.com/shop-img/1327354205705.jpg', 
                            $recipe->photo_url);
    }

}

?>
