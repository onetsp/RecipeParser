<?php

require_once "../bootstrap.php";

class RecipeParser_Parser_Yummlycom_Test extends PHPUnit_Framework_TestCase {

    public function test_Egg_in_a_Frame__Toad_in_a_Hole__568587_columns_4() {

        $path = "data/yummly_com_egg_in_a_frame_toad_in_curl.html";
        $url  = "http://www.yummly.com/recipe/Egg-in-a-Frame-_Toad-in-a-Hole_-568587?columns=4";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Egg in a Frame (Toad in a Hole)', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('2 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(4, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(7, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://lh5.ggpht.com/x9_iD9lMRInLHcEH0K2AsihW3XcbYTYyti8zXflSCpBi9QXm0Q8_rr11EU-8JISEjR9d7w0YjPhpaeLCcRKw4A=s730',
                            $recipe->photo_url);

    }

    public function test_Maples_Inn_Blueberry_Stuffed_French_Toast_568585_columns_4() {

        $path = "data/yummly_com_maples_inn_blueberry_stuffed_french_toast_curl.html";
        $url  = "http://www.yummly.com/recipe/Maples-Inn-Blueberry-Stuffed-French-Toast-568585?columns=4";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Maples Inn Blueberry Stuffed French Toast', $recipe->title);
        $this->assertEquals('', $recipe->credits);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('12 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(6, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(10, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://lh5.ggpht.com/fDaxA-6HoE2G6YW5SzKIvCOqtJpCWEWouN5vP9Zh0Wm0IAoGOevhGABaH7_2_rMArIDFPyYFuEHf_VqnCV5FUw=s230-c',
                            $recipe->photo_url);

    }

}
