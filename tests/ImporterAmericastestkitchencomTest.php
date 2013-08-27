<?php

require_once './bootstrap.php';

class ImporterAmericastestkitchencomTest extends PHPUnit_Framework_TestCase {

    public function test_chicken_pot_pie() {
        $path = "data/clipped/americastestkitchen_com_chicken_pot_pie_with_savory_crumble_topping_america_s_test_kitchen_s_chrome_12_0_orig.html";
        $url = "http://www.americastestkitchen.com/recipes/detail.php?docid=25876";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Chicken Pot Pie With Savory Crumble Topping', $recipe->title);
        $this->assertEquals($url, $recipe->url);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('6 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Filling', $recipe->ingredients[0]['name']);
        $this->assertEquals(16, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Crumble topping', $recipe->ingredients[1]['name']);
        $this->assertEquals(8, count($recipe->ingredients[1]['list']));

        $this->assertEquals("1 medium onion, chopped fine (about 1 cup)", $recipe->ingredients[0]['list'][3]);

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(6, count($recipe->instructions[0]['list']));
        $this->assertRegExp("/^FOR THE CHICKEN: Bring chicken and broth to simmer/",
                            $recipe->instructions[0]['list'][0]);

        //TODO $this->assertRegExp('/^This recipe relies .* the heavy cream.$/', $recipe->notes);

        $this->assertEquals('http://sfs.americastestkitchen.com/images/document/CVR_SFS_chick_pot_pie_crumble_004_article.jpg', $recipe->photo_url);
    }

    public function test_pecan_bread_pudding() {
        $path = "data/clipped/americastestkitchen_com_pecan_bread_pudding_with_bourbon_and_orange_america_s_test_kitchen_s_chrome_12_0_orig.html";
        $url = "http://www.americastestkitchen.com/recipes/detail.php?docid=23314";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Pecan Bread Pudding with Bourbon and Orange', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('8 to 10 servings', $recipe->yield);

        $this->assertEquals(1, count($recipe->ingredients));
        $this->assertEquals('', $recipe->ingredients[0]['name']);
        $this->assertEquals(14, count($recipe->ingredients[0]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        //TODO $this->assertRegExp('/Challah is an egg-enriched bread/', $recipe->notes);
    }

    public function test_apple_crisp() {
        $path = "data/clipped/americastestkitchen_com_skillet_apple_crisp_with_vanilla_cardamom_and_pistachios_america_s_test_kitchen_s_chrome_12_0_orig.html";
        $url = "http://www.americastestkitchen.com/recipes/detail.php?docid=26039&extcode=M**ASCA00";

        $recipe = Importer::parse(file_get_contents($path), $url);
        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals('Skillet Apple Crisp with Vanilla, Cardamom, and Pistachios', $recipe->title);

        $this->assertEquals(0, $recipe->time['prep']);
        $this->assertEquals(0, $recipe->time['cook']);
        $this->assertEquals(0, $recipe->time['total']);
        $this->assertEquals('6 to 8 servings', $recipe->yield);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals('Topping', $recipe->ingredients[0]['name']);
        $this->assertEquals(9, count($recipe->ingredients[0]['list']));
        $this->assertEquals('Filling', $recipe->ingredients[1]['name']);
        $this->assertEquals(7, count($recipe->ingredients[1]['list']));

        $this->assertEquals(1, count($recipe->instructions));
        $this->assertEquals('', $recipe->instructions[0]['name']);
        $this->assertEquals(4, count($recipe->instructions[0]['list']));

        //TODO $this->assertRegExp('/If your skillet is not ovensafe, prepare the recipe through/', $recipe->notes);
    }

}

?>
