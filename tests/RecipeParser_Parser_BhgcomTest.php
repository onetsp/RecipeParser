<?php

require_once '../bootstrap.php';

class RecipeParser_Parser_BhgcomTest extends PHPUnit_Framework_TestCase {

    public function test_eggnog_cheesecake() {
        $path_orig = "data/bhg_com_bhgs_newest_eggnog_cheesecake_with_candied_curl.html";
        $url = "http://www.bhg.com/recipe/cheesecake/eggnog-cheesecake-with-candied-kumquats/";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Eggnog Cheesecake with Candied Kumquats', $recipe->title);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        
        $this->assertEquals('12 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));

        $this->assertEquals(2, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(5, count($recipe->instructions[0]['list']));
        $this->assertEquals(3, count($recipe->instructions[1]['list']));

        $this->assertRegExp('/Cheesecake filling may be prepared/', $recipe->notes);

        $this->assertEquals('http://images.meredith.com/bhg/images/recipe/550_R142956.jpg', 
                            $recipe->photo_url);
    }

    public function test_tomatillo_chicken_soup() {
        $path_orig = "data/bhg_com_bhgs_newest_tomatillo_chicken_soup_curl.html";
        $url = "http://www.bhg.com/recipe/chicken/tomatillo-chicken-soup/";

        $recipe = RecipeParser::parse(file_get_contents($path_orig), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Tomatillo Chicken Soup', $recipe->title);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);

        $this->assertEquals('4 to 6 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(2, count($recipe->instructions[0]['list']));

        $this->assertRegExp('/Because hot chile peppers/', $recipe->notes);

        $this->assertEquals('http://images.meredith.com/bhg/images/recipe/550_R155066.jpg',
                            $recipe->photo_url);
    }

}

?>
