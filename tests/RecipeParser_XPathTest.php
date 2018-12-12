<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class XPathTest extends TestCase {

    function test_create_xpath() {
        $html = file_get_contents(TestUtils::getDataPath("xpath_simple.html"));
        $myxpath = new RecipeParser_XPath($html);
        $xpath = $myxpath->getXPath();
        $this->assertInternalType("object", $xpath);
        $this->assertInstanceOf("DOMXPath", $xpath);
    }

    function test_single_node_lookup_title() {
        $html = file_get_contents(TestUtils::getDataPath("xpath_simple.html"));
        $myxpath = new RecipeParser_XPath($html);
        $recipe = new RecipeParser_Recipe();

        $myxpath->singleNodeLookup('//h1[@class="fn"]', null, "title", $recipe);
        $this->assertEquals("Grandma's Holiday Apple Pie", $recipe->title);
    }

    function test_single_node_lookup_yield() {
        $html = file_get_contents(TestUtils::getDataPath("xpath_simple.html"));
        $myxpath = new RecipeParser_XPath($html);
        $recipe = new RecipeParser_Recipe();

        $myxpath->singleNodeLookup('//span[@class="yield"]', null, "yield", $recipe);
        $this->assertEquals("1 9\" pie (8 servings)", $recipe->yield);
    }

    function test_single_node_lookup_times_as_node() {
        $html = file_get_contents(TestUtils::getDataPath("general_test_time_nodes.html"));
        $myxpath = new RecipeParser_XPath($html);
        $recipe = new RecipeParser_Recipe();

        $myxpath->singleNodeLookup('//*[@class="active-time"]', null, "time_prep", $recipe);
        $myxpath->singleNodeLookup('//*[@class="cooking-time"]', null, "time_cook", $recipe);
        $myxpath->singleNodeLookup('//*[@class="total-time"]', null, "time_total", $recipe);

        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(45, $recipe->time['cook']);
        $this->assertEquals(75, $recipe->time['total']);
    }

    function test_single_node_lookup_times_as_itemprop() {
        $html = file_get_contents(TestUtils::getDataPath("general_test.html"));
        $myxpath = new RecipeParser_XPath($html);
        $recipe = new RecipeParser_Recipe();

        $myxpath->singleNodeLookup('//*[@itemprop="prepTime"]', "content", "time_prep", $recipe);
        $myxpath->singleNodeLookup('//*[@itemprop="cookTime"]', "content", "time_cook", $recipe);
        $myxpath->singleNodeLookup('//*[@itemprop="totalTime"]', "content", "time_total", $recipe);

        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(30, $recipe->time['prep']);
        $this->assertEquals(45, $recipe->time['cook']);
        $this->assertEquals(75, $recipe->time['total']);
    }

    function test_list_lookup_ingredients() {
        $html = file_get_contents(TestUtils::getDataPath("xpath_simple.html"));
        $myxpath = new RecipeParser_XPath($html);
        $recipe = new RecipeParser_Recipe();

        $myxpath->simpleIngredientsListLookup('//span[@class="ingredient"]', $recipe);

        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(2, count($recipe->ingredients));
        $this->assertEquals(3, count($recipe->ingredients[0]['list']));
        $this->assertEquals(2, count($recipe->ingredients[1]['list']));
        $this->assertEquals("", $recipe->ingredients[0]['name']);
        $this->assertEquals("Icing", $recipe->ingredients[1]['name']);
    }

    function test_list_lookup_instructions() {
        $html = file_get_contents(TestUtils::getDataPath("xpath_simple.html"));
        $myxpath = new RecipeParser_XPath($html);
        $recipe = new RecipeParser_Recipe();

        $myxpath->simpleInstructionsListLookup('//span[@class="instructions"]/p', $recipe);

        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals(2, count($recipe->instructions));
        $this->assertEquals(3, count($recipe->instructions[0]['list']));
        $this->assertEquals(1, count($recipe->instructions[1]['list']));
        $this->assertEquals("", $recipe->instructions[0]['name']);
        $this->assertEquals("Icing", $recipe->instructions[1]['name']);
    }

    function test_single_node_attribute_lookup() {
        $html = file_get_contents(TestUtils::getDataPath("general_ogtitle_ogimage.html"));
        $myxpath = new RecipeParser_XPath($html);
        $recipe = new RecipeParser_Recipe();

        $myxpath->singleNodeLookup('//meta[@property="og:title"]', "content", "title", $recipe);

        if (isset($_SERVER['VERBOSE'])) print_r($recipe);

        $this->assertEquals("Chocolate Chip Cake", $recipe->title);
    }

}

