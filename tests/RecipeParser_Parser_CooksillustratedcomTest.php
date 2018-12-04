<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class CooksillustratedcomTest extends TestCase {

    public function test_key_lime_bars() {
        $this->markTestSkipped("Need updated clipper files for Cooks Illustrated");

        $path = "data/cooksillustrated_com_key_lime_bars_cooks_illustrated_chrome_12_0_orig.html";
        $url = "http://www.cooksillustrated.com/recipes/detail.asp?docid=7683";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Key Lime Bars', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('16 2-inch bars', $recipe->yield);

        $this->assertEquals(3, count($recipe->ingredients));
        $this->assertEquals('Crust', $recipe->ingredients[0]['name']);
        $this->assertEquals(4, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Filling', $recipe->ingredients[1]['name']);
        $this->assertEquals(6, count($recipe->ingredients[1]['list']));
        $this->assertEquals('Garnish (optional)', $recipe->ingredients[2]['name']);
        $this->assertEquals(1, count($recipe->ingredients[2]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/^If you cannot find .* two.$/', $recipe->notes);

        $this->assertEquals('http://media.cooksillustrated.com/images/document/recipe/ja06_keylimebars_article.jpg',
                            $recipe->photo_url);
    }

    public function test_perfect_chocolate_chip() {
        $this->markTestSkipped("Need updated clipper files for Cooks Illustrated");

        $path = "data/cooksillustrated_com_perfect_chocolate_chip_cookies_cooks_illustrated_chrome_12_0_orig.html";
        $url = "http://www.cooksillustrated.com/recipes/detail.asp?docid=19364";

        $recipe = RecipeParser::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Perfect Chocolate Chip Cookies', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('16 cookies', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(11, count($recipe->ingredients[0]['list']));

        $this->assertEquals('3/4 cup chopped pecans or walnuts, toasted (optional)', $recipe->ingredients[0]['list'][10]);
        $this->assertEquals('14 tablespoons unsalted butter (1 3/4 sticks)', $recipe->ingredients[0]['list'][2]);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));
    }

}
