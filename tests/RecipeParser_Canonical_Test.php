<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class CanonicalTest extends TestCase {

    /**
     * @group network
     */
    public function test_m_dot_allrecipes() {
        $url       = "http://m.allrecipes.com/recipe/70343/slow-cooker-chicken-taco-soup/";
        $canonical = "https://www.allrecipes.com/recipe/70343/slow-cooker-chicken-taco-soup/";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_allrecipes_print_format() {
        $url       = "http://allrecipes.com/recipe/38109/pickled-beets/print/?recipeType=Recipe&servings=60";
        $canonical = "https://www.allrecipes.com/recipe/38109/pickled-beets/";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_allrecipes_kitchenview() {
        $url       = "http://allrecipes.com/recipe/kats-sausage-turnovers/kitchenview.aspx";
        $canonical = "http://allrecipes.com/recipe/kats-sausage-turnovers/detail.aspx";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_allrecipes_kitchenview_and_personalview() {
        $url       = "http://allrecipes.com/personalrecipe/64384170/chicken-and-veggies-with-rice/kitchenview.aspx";
        $canonical = "http://allrecipes.com/personalrecipe/64384170/chicken-and-veggies-with-rice/detail.aspx";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_yummyly_iframe() {
        $url       = "http://www.yummly.com/recipe/Roasted-Chicken-Tacos-Martha-Stewart-191942";
        $canonical = "http://www.marthastewart.com/315717/roasted-chicken-tacos";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_foodnetwork_com_print_view() {
        $url       = "http://www.foodnetwork.com/recipes/giada-de-laurentiis/grilled-lamb-chops-recipe.print.html";
        $canonical = "http://www.foodnetwork.com/recipes/giada-de-laurentiis/grilled-lamb-chops-recipe.html";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_epicurious_com_print_view() {
        $url       = "http://www.epicurious.com/recipes/food/printerfriendly/celery-spiked-guacamole-with-chiles-51214860";
        $canonical = "http://www.epicurious.com/recipes/food/views/celery-spiked-guacamole-with-chiles-51214860";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_epicurious_com_ingredients_feature() {
        $url       = "http://www.epicurious.com/ingredients/how-to-eat-sweet-potatoes-for-every-meal-even-dessert-gallery/4";
        $canonical = "http://www.epicurious.com/recipes/food/views/pannelet-cookies-with-sweet-potato-and-coconut";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_epicurious_com_recipe_reviews() {
        $url       = "http://www.epicurious.com/recipes/food/reviews/marie-helenes-apple-cake-361150";
        $canonical = "http://www.epicurious.com/recipes/food/views/marie-helenes-apple-cake-361150";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_epicurious_com_howtocook() {
        $url       = "http://www.epicurious.com/archive/howtocook/dishes/classic-recipes-cinnamon-rolls";
        $canonical = "http://www.epicurious.com/recipes/food/views/cinnamon-rolls-with-icing-51160400";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_epicurious_com_recipes_menus() {
        $url       = "http://www.epicurious.com/recipes-menus/how-to-make-the-nomad-buttermilk-fried-chicken-fingers-article";
        $canonical = "http://www.epicurious.com/recipes/food/views/buttermilk-fried-chicken-fingers-51258410";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_myrecipes_com_mobile_view() {
        $url       = "http://www.myrecipes.com/m/recipe/chicken-chickpea-tagine";
        $canonical = "http://www.myrecipes.com/recipe/chicken-chickpea-tagine";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_myrecipes_com_print_view() {
        $url       = "http://www.myrecipes.com/recipe/hummingbird-cake-0/print/";
        $canonical = "http://www.myrecipes.com/recipe/hummingbird-cake-0";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_myrecipes_com_quick_and_easy_video() {
        $url       = "http://www.myrecipes.com/quick-and-easy/dinner-tonight/how-to-make-kung-pao-chicken";
        $canonical = "https://www.myrecipes.com/recipe/kung-pao-chicken";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_myrecipes_com_howto_videos() {
        $url       = "http://www.myrecipes.com/how-to/video/breakfast-enchiladas";
        $canonical = "https://www.myrecipes.com/recipe/breakfast-enchiladas";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_food52_print_views() {
        $url       = "http://food52.com/recipes/print/17101";
        $canonical = "http://food52.com/recipes/17101";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

    /**
     * @group network
     */
    public function test_ziplist_souschef() {
        $url       = "http://www.ziplist.com/souschef?url=http%3A%2F%2Fwww.melskitchencafe.com%2F2014%2F03%2Fgreen-chile-chicken-enchilada-stack.html";
        $canonical = "http://www.melskitchencafe.com/2014/03/green-chile-chicken-enchilada-stack.html";
        $html = FileUtil::downloadRecipeWithCache($url);
        $this->assertEquals($canonical, RecipeParser_Canonical::getCanonicalUrl($html, $url));
    }

}
