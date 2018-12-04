<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class MicrodataSchemaTest extends TestCase {

    public function test_schema_recipe() {
        $path = "data/schema_spec.html";
        $url = "http://schema.example.com/schema-spec";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Mom's World Famous Banana Bread", $recipe->title);

        $this->assertRegexp("/^This classic .* banana bread.$/s", $recipe->description);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(60, $recipe->time['cook']);
        $this->assertEquals(75, $recipe->time['total']);
        $this->assertEquals('1 loaf', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(3, count($recipe->ingredients[0]['list']));
        $this->assertEquals("3/4 cup of sugar", $recipe->ingredients[0]['list'][2]);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://schema.example.com/images/bananabread.jpg', 
                            $recipe->photo_url);
    }

    public function test_schema_spec_class_instruction() {
        $path = "data/schema_spec_class_instruction.html";
        $url = "http://schema.example.com/schema-spec";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertRegexp("/^Heat oven .* flour.$/", $recipe->instructions[0]['list'][0]);
        $this->assertRegexp("/^Place both .* hour.$/", $recipe->instructions[0]['list'][2]);
    }

    public function test_schema_spec_recipeinstructions_li() {
        $path = "data/schema_spec_recipeinstructions_li.html";
        $url = "http://schema.example.com/schema-spec";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(12, count($recipe->instructions[0]['list']));
        $this->assertRegexp("/^Cream the butter and peanut butter together.$/", $recipe->instructions[0]['list'][0]);
        $this->assertRegexp("/^Beat in the vanilla and sugar.$/", $recipe->instructions[0]['list'][1]);
    }

    public function test_schema_spec_yield_as_content() {
        $path = "data/schema_spec_yield_as_content.html";
        $url = "http://schema.example.com/schema-spec";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('12 servings', $recipe->yield);
    }

    public function test_bettycrocker_banana_cake_with_fudge() {
        $path = "data/bettycrocker_com_banana_cake_with_fudge_frosting_curl.html";
        $url = "http://www.bettycrocker.com/recipes/banana-cake-with-fudge-frosting/ec14f90a-4ed3-4ef7-8f69-d9d5aadcebc3";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Banana Cake with Fudge Frosting', $recipe->title);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(110, $recipe->time['total']);
        $this->assertEquals('15 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(17, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        $this->assertEquals('2 cups Gold Medal® all-purpose flour', $recipe->ingredients[0]['list'][0]);
        $this->assertRegExp('/^Heat oven to 350ºF./', $recipe->instructions[0]['list'][0]);

        $this->assertEquals('http://s3.amazonaws.com/gmi-digital-library/b8515a9f-42f2-4136-8280-b50244f334f4.jpg',
                            $recipe->photo_url);
    }

    public function test_bettycrocker_blueberry_banana_oat_bread() {
        $path = "data/bettycrocker_com_blueberry_banana_oat_bread_curl.html";
        $url = "http://www.bettycrocker.com/recipes/blueberry-banana-oat-bread/886c41eb-a229-4ab5-ac74-41b68c4ce0ac";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Blueberry-Banana-Oat Bread', $recipe->title);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(205, $recipe->time['total']);
        $this->assertEquals('1 loaf (16 slices)', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(7, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(3, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://s3.amazonaws.com/gmi-digital-library/fcf45a1a-78f7-4237-b64c-fa04d9570641.jpg',
                            $recipe->photo_url);
    }

    public function test_bettycrocker_coffee_cake_with_caramel() {
        $path = "data/bettycrocker_com_coffee_toffee_cake_with_caramel_frosting_curl.html";
        $url = "http://www.bettycrocker.com/recipes/coffee-toffee-cake-with-caramel-frosting/557b8338-f603-4cc4-95e0-ec44089964bd";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Coffee-Toffee Cake with Caramel Frosting', $recipe->title);

        $this->assertEquals(15, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(120, $recipe->time['total']);
        $this->assertEquals('15 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://s3.amazonaws.com/gmi-digital-library/605b6c2e-b017-4aae-8db0-14f64b203642.jpg',
                            $recipe->photo_url);
    }

    public function test_wholefoods_mushroom_kugel() {
        $path = "data/wholefoodsmarket_com_mushroom_kale_noodle_kugel_whole_foods_curl.html";
        $url = "http://www.wholefoodsmarket.com/recipes/3112";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Mushroom Kale Noodle Kugel', $recipe->title);

        //$this->assertEquals('8 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));
        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals(1, count($recipe->instructions[0]['list']));

        $this->assertEquals('http://d1kg9jbrq423rq.cloudfront.net/sites/default/files/3112.jpg',
                            $recipe->photo_url);
    }

}

