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

    function test_single_node_lookup_yield() {
        $html = file_get_contents(TestUtils::getDataPath("xpath_simple.html"));
        $myxpath = new RecipeParser_XPath($html);
        $recipe = new RecipeParser_Recipe();

        $myxpath->singleNodeLookup('//span[@class="yield"]', "yield", $recipe);
        $this->assertEquals("1 9\" pie (8 servings)", $recipe->yield);
    }

    function test_single_node_lookup_title() {
        $html = file_get_contents(TestUtils::getDataPath("xpath_simple.html"));
        $myxpath = new RecipeParser_XPath($html);
        $recipe = new RecipeParser_Recipe();

        $myxpath->singleNodeLookup('//h1[@class="fn"]', "title", $recipe);
        $this->assertEquals("Grandma's Holiday Apple Pie", $recipe->title);

    }

}

