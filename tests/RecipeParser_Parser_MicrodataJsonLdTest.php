<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_MicrodataDataVocabularyTest extends PHPUnit_Framework_TestCase {

    public function test_jsonld_sweet_paul() {
        $path = "data/sweetpaulmag_com_tomato_tart_with_basil_oil_and_curl.html";
        $url = "http://www.sweetpaulmag.com/food/tomato-tart-with-basil-oil-and-almond-amp-pepper-crust";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Tomato Tart with Basil Oil and Almond &amp; Pepper Crust", $recipe->title);
        $this->assertEquals("Sweet Paul", $recipe->credits);
        $this->assertEquals("", $recipe->description);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('Makes 1 Tart', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(15, count($recipe->ingredients[0]['list']));
        $this->assertEquals("1 cup all&shy;purpose flour", $recipe->ingredients[0]['list'][1]);
        $this->assertEquals("1/4 cup almond flour", $recipe->ingredients[0]['list'][2]);
        $this->assertEquals("1/2 teaspoon salt", $recipe->ingredients[0]['list'][3]);

        $this->assertEquals('http://www.sweetpaulmag.com/1EatContent/images/2014/4625980_281271_tomtart1.jpg', 
                            $recipe->photo_url);
    }
    
    public function test_jsonld_sweet_life() {
        $path = "data/gracessweetlife_com_cannoli_and_cannoli_filling_graces_sweet_curl.html";
        $url = "http://gracessweetlife.com/2010/06/cannoli-siciliani-the-ultimate-italian-pastry/";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Cannoli Siciliani &ndash; The Ultimate Italian Pastry", $recipe->title);
        $this->assertEquals("Grace Massa Langlois", $recipe->credits);
        $this->assertRegexp("/^Prepare one .* creamy ricotta filling.$/s", $recipe->description);

        $this->assertEquals(138, $recipe->time['prep']);
        $this->assertEquals(45, $recipe->time['cook']);
        $this->assertEquals(183, $recipe->time['total']);
        $this->assertEquals('28 pastries', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(13, count($recipe->ingredients[0]['list']));
        $this->assertEquals("167 g (1 1/3 cups) plain (all-purpose) flour", $recipe->ingredients[0]['list'][0]);
        $this->assertEquals("1/4 teaspoon salt", $recipe->ingredients[0]['list'][1]);
        $this->assertEquals("14 g (1 tablespoon) granulated sugar", $recipe->ingredients[0]['list'][2]);

        $this->assertEquals('http://gracessweetlife.com/wp-content/uploads/2010/06/ccc3-e1381295500919.jpg', 
                            $recipe->photo_url);
    }
}

?>
