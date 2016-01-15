<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_AboutcomTest extends PHPUnit_Framework_TestCase {

    public function test_baking_biscotti_cookie() {
        $path = "data/baking_about_com_cinnamon_walnut_biscotti_curl.html";
        $url = "http://baking.about.com/od/cookies/r/cinnamonwalnutbiscotti.htm";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Cinnamon Walnut Biscotti", $recipe->title);

        $this->assertEquals(25, $recipe->time["prep"]);
        $this->assertEquals(40, $recipe->time["cook"]);
        $this->assertEquals(65, $recipe->time["total"]);
        $this->assertEquals("Carroll Pellegrinelli, About.com", $recipe->credits);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        
        # Muddled with some additional links mixed in with instructions
        #$this->assertEquals(3, count($recipe->instructions[0]['list']));
    }

    public function test_baking_chocolate_cake() {
        $path = "data/baking_about_com_ultimate_chocolate_cake_for_ultimate_chocolate_curl.html";
        $url = "http://baking.about.com/od/valentines/r/ultimatechoccak.htm";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Ultimate Chocolate Cake", $recipe->title);
        $this->assertEquals(60, $recipe->time["prep"]);
        $this->assertEquals(50, $recipe->time["cook"]);
        $this->assertEquals(110, $recipe->time["total"]);
        $this->assertEquals("Carroll Pellegrinelli, About.com", $recipe->credits);

        $this->assertEquals(3, count($recipe->ingredients));
        $this->assertEquals(2, count($recipe->ingredients[0]['list']));
        $this->assertEquals(11, count($recipe->ingredients[1]['list']));
        $this->assertEquals(4, count($recipe->ingredients[2]['list']));
        $this->assertEquals("", $recipe->ingredients[0]['name']);
        $this->assertEquals("Cake", $recipe->ingredients[1]['name']);
        $this->assertEquals("Granche", $recipe->ingredients[2]['name']);

        $this->assertEquals(4, count($recipe->instructions));
        $this->assertEquals(1, count($recipe->instructions[0]['list']));
        $this->assertEquals(1, count($recipe->instructions[1]['list']));
        $this->assertEquals(5, count($recipe->instructions[2]['list']));
        $this->assertEquals(3, count($recipe->instructions[3]['list']));
        $this->assertEquals("Hazelnut butter", $recipe->instructions[1]['name']);
        $this->assertEquals("Cake", $recipe->instructions[2]['name']);
        $this->assertEquals("Ganache", $recipe->instructions[3]['name']);
    }

    public function test_frenchfood_cassoulet() {
        $path = "data/frenchfood_about_com_chicken_and_sausage_cassoulet_curl.html";
        $url = "http://frenchfood.about.com/od/maindishes/r/cassoulet.htm";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Chicken and Sausage Cassoulet", $recipe->title);
        $this->assertEquals(25, $recipe->time["prep"]);
        $this->assertEquals(240, $recipe->time["cook"]);
        $this->assertEquals(265, $recipe->time["total"]);
        $this->assertEquals("Rebecca Franklin, About.com", $recipe->credits);

        $this->assertEquals(21, count($recipe->ingredients[0]['list']));
        $this->assertEquals(6, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://0.tqn.com/y/frenchfood/1/S/f/M/-/-/85652680.jpg', $recipe->photo_url);
    }

    public function test_southernfood_cinnamon_pound_cake() {
        $path = "data/southernfood_about_com_cinnamon_pound_cake_curl.html";
        $url = "http://southernfood.about.com/od/spicecakerecipes/r/bln255.htm";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Cinnamon Pound Cake", $recipe->title);
        $this->assertEquals(0, $recipe->time["prep"]);
        $this->assertEquals(60, $recipe->time["cook"]);
        $this->assertEquals(60, $recipe->time["total"]);
        $this->assertEquals("Diana Rattray, About.com", $recipe->credits);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals(5, count($recipe->ingredients[0]['list']));
        $this->assertEquals(2, count($recipe->ingredients[1]['list']));
        $this->assertEquals("Cinnamon-sugar", $recipe->ingredients[1]['name']);
        $this->assertEquals(1, count($recipe->instructions));

        $this->assertEquals(3, count($recipe->instructions[0]['list']));
    }

}
