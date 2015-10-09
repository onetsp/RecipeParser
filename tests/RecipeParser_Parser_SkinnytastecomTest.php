<?php

require_once "../bootstrap.php";

class RecipeParser_Parser_Skinnytastecom_Test extends PHPUnit_Framework_TestCase {

    public function test_baked_empanadas() {

        $path = "data/skinnytaste_com_baked_empanadas_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2009/01/baked-empanadas-3-pts.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Baked Empanadas', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(5, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(4, count($recipe->instructions[0]['list']));
        $this->assertRegExp("/^Preheat the oven .* Pam baking spray.$/", $recipe->instructions[0]['list'][0]);

        $this->assertEquals('http://lh4.ggpht.com/_BizpeaUzxq8/SWkWMnyshBI/AAAAAAAAAxI/hCn08q7eSKs/s800/empanadas.jpg',
                            $recipe->photo_url);
    }

    public function test_homemade_skinny_chocolate_cake() {

        $path = "data/skinnytaste_com_homemade_skinny_chocolate_cake_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2012/02/homemade-skinny-chocolate-cake.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Homemade Skinny Chocolate Cake', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(8, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://3.bp.blogspot.com/-YL9tAK_oWRU/TzLdZifv-aI/AAAAAAAAFYE/3Nl-KOvULDA/s1600/low-fat-homemade-chocolate-cake.jpg',
                            $recipe->photo_url);
    }

    public function test_pink_lemonade_confetti_cupcakes() {

        $path = "data/skinnytaste_com_pink_lemonade_confetti_cupcakes_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2011/07/pink-lemonade-confetti-cupcakes.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Pink Lemonade Confetti Cupcakes', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(8, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://1.bp.blogspot.com/-W4z49QgMETQ/Th3ViNBfefI/AAAAAAAADSA/JinhzlvjvhY/s1600/Pink-lemonade-confetti-cupcakes.jpg',
                            $recipe->photo_url);
    }

    public function test_red_white_and_blueberry_trifle() {

        $path = "data/skinnytaste_com_red_white_and_blueberry_trifle_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2011/06/red-white-and-blueberry-trifle.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Red, White and Blueberry Trifle', $recipe->title);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(3, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Cream filling', $recipe->ingredients[1]['name']);
        $this->assertEquals(4, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://2.bp.blogspot.com/-xd-XTdWiekY/Tf_TpmezbnI/AAAAAAAADOE/HY9fV4zS4jY/s1600/red-white-and-blueberry-trifle.jpg',
                            $recipe->photo_url);
    }

    public function test_shrimp_salad_on_cucumber_slices() {

        $path = "data/skinnytaste_com_shrimp_salad_on_cucumber_slices_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2010/08/shrimp-salad-on-cucumber-slices.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Shrimp Salad on Cucumber Slices', $recipe->title);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(1, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://lh3.ggpht.com/_BizpeaUzxq8/TG7mp3hxbQI/AAAAAAAACP4/En1fMaycM34/s800/shrimp-salad-on-cucumbers.jpg',
                            $recipe->photo_url);
    }

    public function test_skinny_coconut_cupcakes() {

        $path = "data/skinnytaste_com_skinny_coconut_cupcakes_skinnytaste_curl.html";
        $url  = "http://www.skinnytaste.com/2012/03/skinny-coconut-cupcakes.html";

        $doc = RecipeParser_Text::getDomDocument(file_get_contents($path));
        $recipe = RecipeParser::parse($doc, $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Skinny Coconut Cupcakes', $recipe->title);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Frosting', $recipe->ingredients[0]['name']);
        $this->assertEquals(3, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Cupcakes', $recipe->ingredients[1]['name']);
        $this->assertEquals(7, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://2.bp.blogspot.com/-SqpSueplHvc/T29ShWrAdWI/AAAAAAAAFws/pwRse56Avd4/s1600/Skinny-coconut-cupcakes.jpg',
                            $recipe->photo_url);
    }

}
