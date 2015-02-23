<?php

require_once '../bootstrap.php';

class RecipeParser_Redirect_Test extends PHPUnit_Framework_TestCase {

    function test_inspect_for_redirect_none() {
        $html = file_get_contents("data/bonappetit_com_chai_spiced_hot_chocolate_bon_app_curl.html");
        $url = "http://www.bonappetit.com/recipes/quick-recipes/2010/02/chai_spiced_hot_chocolate";
        $redirect_url = RecipeParser_Redirect::inspectHtmlForRedirect($html, $url);
        $this->assertEquals(null, $redirect_url);
    }

    function test_inspect_for_redirect_yummly() {
        $html = file_get_contents("data/yummly_com_roasted_chicken_tacos_yummly_curl.html");
        $url = "http://www.yummly.com/recipe/Roasted-Chicken-Tacos-Martha-Stewart-191942";
        $redirect_url = RecipeParser_Redirect::inspectHtmlForRedirect($html, $url);
        $this->assertEquals("http://www.yummly.com/recipe/external/Roasted-Chicken-Tacos-Martha-Stewart-191942", $redirect_url);
    }

    function test_inspect_for_redirect_yummly_iframe() {
        $html = file_get_contents("data/yummly_com_roasted_chicken_tacos_iframe_curl.html");
        $url = "http://www.yummly.com/recipe/external/Roasted-Chicken-Tacos-Martha-Stewart-191942";
        $redirect_url = RecipeParser_Redirect::inspectHtmlForRedirect($html, $url);
        $this->assertEquals("http://www.marthastewart.com/315717/roasted-chicken-tacos", $redirect_url);
    }

}
