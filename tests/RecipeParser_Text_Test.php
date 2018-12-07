<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class TextTest extends TestCase {

    public function test_source_and_url_do_not_match() {
        $source = "From Mom";
        $this->assertEquals('Mom', RecipeParser_Text::formatCredits($source));
    }
    public function test_recipe_courtesy() {
        $source = "Recipe courtesy of Bobby Flay";
        $this->assertEquals('Bobby Flay', RecipeParser_Text::formatCredits($source));
    }
    public function test_recipe_courtesy_of() {
        $source = "Recipe courtesy Bobby Flay";
        $this->assertEquals('Bobby Flay', RecipeParser_Text::formatCredits($source));
    }
    public function test_recipe_by() {
        $source = "Recipe by Bobby Flay";
        $this->assertEquals('Bobby Flay', RecipeParser_Text::formatCredits($source));
    }

    public function test_format_title_simple() {
        $this->assertEquals("Bananas Foster",
                            RecipeParser_Text::formatTitle(" recipe for Bananas Foster"));
        $this->assertEquals("Bananas Foster",
                            RecipeParser_Text::formatTitle(" Bananas Foster Recipe "));
    }
    public function test_format_title_sponsored_recipe() {
        $this->assertEquals("Crazy Bananas",
                            RecipeParser_Text::formatTitle("Sponsored recipe: Crazy Bananas"));
    }
    public function test_format_title_foodnetwork() {
        $this->assertEquals("Pan-Seared Rib-Eye",
                            RecipeParser_Text::formatTitle("Pan-Seared Rib-Eye Recipe : Alton Brown : Recipes : Food Network"));
        $this->assertEquals("Roasted Cauliflower Lasagna",
                            RecipeParser_Text::formatTitle("Roasted Cauliflower Lasagna : Food Network"));
        $this->assertEquals("50 Stuffing Recipes",
                            RecipeParser_Text::formatTitle("50 Stuffing Recipes : Recipes and Cooking : Food Network"));
    }
    public function test_format_title_recipes_at_epicurious() {
        $this->assertEquals("Ziti with Roasted Zucchini",
                            RecipeParser_Text::formatTitle("Ziti with Roasted Zucchini Recipe at Epicurious.com"));
    }
    public function test_format_title_recipes_section_name_with_separator() {
        $this->assertEquals("Cream Cheese Squares",
                            RecipeParser_Text::formatTitle("Top incredible recipes : Cream Cheese Squares"));
    }
    public function test_format_title_pipe_separator_then_site_name() {
        $this->assertEquals("breakfast apple granola crisp",
                            RecipeParser_Text::formatTitle("breakfast apple granola crisp | smitten kitchen"));
        $this->assertEquals("Pumpkin Bread",
                            RecipeParser_Text::formatTitle("Pumpkin Bread | The Cookin Chicks"));
        $this->assertEquals("Marbled Banana Bread",
                            RecipeParser_Text::formatTitle("Marbled Banana Bread | Post Punk Kitchen | Vegan Baking & Vegan Cooking"));
        $this->assertEquals("Easy Tip For Cutting and Peeling Winter Squash",
                            RecipeParser_Text::formatTitle("Easy Tip For Cutting and Peeling Winter Squash | Skinnytaste"));
        $this->assertEquals("Special Sunday Roast Chicken",
                            RecipeParser_Text::formatTitle("Special Sunday Roast Chicken  Recipe | Bon Appetit"));
    }
    public function test_format_title_pipe_separator_with_site_name_and_recipes_category() {
        $this->markTestSkipped("Infrequent case, skipping functionality.");
        $this->assertEquals("Turkey And Black Bean Enchiladas",
                            RecipeParser_Text::formatTitle("Turkey And Black Bean Enchiladas Recipes | Taste of Home"));
    }
    public function test_format_title_dash_separator_then_site_name() {
        $this->assertEquals("Southern Fried Cabbage",
                            RecipeParser_Text::formatTitle("Southern Fried Cabbage - Aunt Bee's Recipes"));
        $this->assertEquals("Tater Tot Casserole",
                            RecipeParser_Text::formatTitle("Tater Tot Casserole Recipe - Penny Pincher Jenny"));
        $this->assertEquals("Lazy Girl's Ravioli Lasagna",
                            RecipeParser_Text::formatTitle("Lazy Girl's Ravioli Lasagna - Iowa Girl Eats"));
        $this->assertEquals("Roasted Brussels Sprouts and Apples",
                            RecipeParser_Text::formatTitle("Roasted Brussels Sprouts and Apples - Holiday Sides - Cooking Light"));
    }
    public function test_format_title_colon_separator_then_site_name() {
        $this->assertEquals("Chicken and Cheese Quesadilla Pie",
                            RecipeParser_Text::formatTitle("Chicken and Cheese Quesadilla Pie Recipe : Cooking.com Recipes"));
    }
    public function test_format_title_keep_as_is() {
        $this->assertEquals("South Your Mouth: Sticky Chicken",
                            RecipeParser_Text::formatTitle("South Your Mouth: Sticky Chicken"));
        $this->assertEquals("BRUNCH: Crock Pot French Toast Revisited",
                            RecipeParser_Text::formatTitle("BRUNCH: Crock Pot French Toast Revisited"));
    }

    public function test_format_one_line() {
        $str = "\tThis classic recipe comes\n \n\tfrom my mom -- \n the walnuts add a nice\ntexture to the bread.\n";
        $test = "This classic recipe comes from my mom -- the walnuts add a nice texture to the bread.";

        $this->assertEquals($test, RecipeParser_Text::formatAsOneLine($str));
    }

    public function test_format_paragraphs() {
        $str = "\tThis is the\n\tfirst paragraph.  \n\t \nThis is \nthe second. \r\n \n \n And this is\r\nthe third.  ";
        $test = "This is the first paragraph.\n\nThis is the second.\n\nAnd this is the third.";

        $this->assertEquals($test, RecipeParser_Text::formatAsParagraphs($str));
    }

    public function test_format_yield() {
        $this->assertEquals('1 serving', RecipeParser_Text::formatYield('Serves 1'));
        $this->assertEquals('8 servings', RecipeParser_Text::formatYield('Serves 8.'));
        $this->assertEquals('8-12 servings', RecipeParser_Text::formatYield('Serves 8-12.'));
        $this->assertEquals('8 portions', RecipeParser_Text::formatYield('Makes 8 portions.'));
        $this->assertEquals('10 cupcakes', RecipeParser_Text::formatYield('Makes about 10 cupcakes'));
        $this->assertEquals('1 portion', RecipeParser_Text::formatYield('One portion.'));
        $this->assertEquals('12 servings', RecipeParser_Text::formatYield('Twelve servings'));
        $this->assertEquals('8 to 10 servings', RecipeParser_Text::formatYield('Serves 8 to 10'));
        $this->assertEquals('10', RecipeParser_Text::formatYield('Makes 10'));
        $this->assertEquals('10 servings', RecipeParser_Text::formatYield('servings 10'));
        $this->assertEquals('8 to 10 servings', RecipeParser_Text::formatYield('servings 8 to 10'));
        $this->assertEquals('8-10 servings', RecipeParser_Text::formatYield('servings 8 - 10'));
        $this->assertEquals('8-10 servings', RecipeParser_Text::formatYield('servings 8-10'));
        $this->assertEquals('8 servings', RecipeParser_Text::formatYield('Yield: 8 Servings'));
        $this->assertEquals('8 servings', RecipeParser_Text::formatYield('Yield 8 Servings'));
        $this->assertEquals('8 servings', RecipeParser_Text::formatYield('Servings: 8 Servings'));
        $this->assertEquals('8 servings', RecipeParser_Text::formatYield('Servings 8 Servings'));
        $this->assertEquals('10 to 12 servings', RecipeParser_Text::formatYield('servings: 10 to 12'));
        $this->assertEquals('8-10 servings', RecipeParser_Text::formatYield('8–10 servings')); // mdash
        $this->assertEquals('12 servings (serving size: 1 cup)', RecipeParser_Text::formatYield('12 servings (serving size: 1 cup)'));
        $this->assertEquals('6 servings (serving size: about 1 cup)', RecipeParser_Text::formatYield('Serves 6 (serving size: about 1 cup)'));
        $this->assertEquals('4-12 servings', RecipeParser_Text::formatYield('YIELDS 4 -12 Servings'));
    }

    public function test_parse_list_from_blob_numeric_bullets_single_lines() {
        $str = "
        1. This is item one.
        2. This is line two
        3. The third item. 
        ";
        $list = RecipeParser_Text::parseListFromBlob($str);
        $this->assertEquals(3, count($list));
        $this->assertEquals("This is item one.", $list[0]);
        $this->assertEquals("This is line two", $list[1]);
        $this->assertEquals("The third item.", $list[2]);
    }

    public function test_parse_list_from_blob_ignore_numeric_bullets() {
        $str = "
        2 teaspoons ground cinnamon
        1 1/4 teaspoon salt
        1/2 teaspoon freshly grated nutmeg
        ";
        $list = RecipeParser_Text::parseListFromBlob($str, RecipeParser_Text::IGNORE_LEADING_NUMBERS);
        $this->assertEquals(3, count($list));
        $this->assertEquals("2 teaspoons ground cinnamon", $list[0]);
        $this->assertEquals("1 1/4 teaspoon salt", $list[1]);
        $this->assertEquals("1/2 teaspoon freshly grated nutmeg", $list[2]);
    }

    public function test_parse_list_from_blob_numeric_bullets_multiline() {
        $str = "
        1. this is item one.
        2. Item two spans
        multple lines
        3. The third item has a dot after the leading number. 
        4. The fourth line
        spans multiple
        lines.
        ";
        $list = RecipeParser_Text::parseListFromBlob($str);
        $this->assertEquals(4, count($list));
        $this->assertEquals("this is item one.", $list[0]);
        $this->assertEquals("Item two spans multple lines", $list[1]);
        $this->assertEquals("The third item has a dot after the leading number.", $list[2]);
        $this->assertEquals("The fourth line spans multiple lines.", $list[3]);
    }

    public function test_parse_list_from_blob_mixed_bullets() {
        $str = "
        * Asterisk for bullet.
        - dash for bullet.
        - dash for bullet
        with multiple lines.
        - last one
        ";
        $list = RecipeParser_Text::parseListFromBlob($str);
        $this->assertEquals(4, count($list));
        $this->assertEquals("Asterisk for bullet.", $list[0]);
        $this->assertEquals("dash for bullet.", $list[1]);
        $this->assertEquals("dash for bullet with multiple lines.", $list[2]);
        $this->assertEquals("last one", $list[3]);
    }

    public function test_parse_list_from_blob_no_bullets() {
        $str = "
                Heat oven to 400.
            Grease muffin pan or line with paper cups.
            Mix topping ingredients together, cutting in the butter with a pastry cutter or a fork until its crumbly. Set aside.
            Toss blueberries with 1 tablespoon of flour to coat.
            Mix dry ingredients in a large bowl. Combine the creme fraiche, milk, oil, egg and extract together. Pour the wet mixture into the dry, stir, add the lemon juice and continue to mix until the dough comes together. Fold in the blueberries.
            Spoon the batter into the prepared muffin tin, sprinkle with topping and bake in preheated oven for 20-25 minutes or until a tooth pick comes out clean.
        ";
        $list = RecipeParser_Text::parseListFromBlob($str);
        $this->assertEquals(6, count($list));
        $this->assertRegExp("/^Heat.*400.$/", $list[0]);
        $this->assertRegExp("/^Grease.*cups.$/", $list[1]);
        $this->assertRegExp("/^Mix.*aside.$/", $list[2]);
        $this->assertRegExp("/^Toss.*coat.$/", $list[3]);
        $this->assertRegExp("/^Mix.*blueberries.$/", $list[4]);
        $this->assertRegExp("/^Spoon.*clean.$/", $list[5]);
    }

    public function test_parse_list_from_blob_run_together_numeric_bullets_and_text() {
        $str = "1. Preheat oven to 350° F. Line muffin tins with 12 foil cupcake papers. Place a vanilla wafer in the bottom of each cupcake paper.2. In mixing bowl, beat cream cheese and fat-free cream cheese until smooth. Add sugar and vanilla and mix well. Add eggs and beat until smooth.3. Pour cheesecake mixture into muffin tins. Bake for 20 minutes or until centers are almost set. Cool. Refrigerate 2 hours or overnight.4. Decorate cheesecake tops with cherry pie filling.Makes 12 cheesecakes/servings.";
        $list = RecipeParser_Text::parseListFromBlob($str);
        $this->assertEquals(5, count($list));
        $this->assertRegExp("/^Preheat.*paper.$/", $list[0]);
        $this->assertRegExp("/^In mixing bowl/", $list[1]);
        $this->assertRegExp("/^Makes 12.*servings.$/", $list[4]);
    }

    public function test_match_section_name_all_dashes() {
        $this->assertTrue(RecipeParser_Text::matchSectionName("---"));
    }
    public function test_match_section_name_wrapped_dashes() {
        $this->assertTrue(RecipeParser_Text::matchSectionName("---This---"));
    }
    public function test_match_section_name_wrapped_equals() {
        $this->assertTrue(RecipeParser_Text::matchSectionName("==That=="));
    }

    public function test_format_section_name_ignore_all_dashes() {
        $this->assertEquals("", RecipeParser_Text::formatSectionName("---"));
    }

    public function test_format_section_name_strip_dashes() {
        $this->assertEquals("Cake", RecipeParser_Text::formatSectionName("---Cake---"));
    }

    public function test_format_section_name() {
        // Pass through as original
        $this->assertEquals("Cake", RecipeParser_Text::formatSectionName("Cake"));

        // Title case single word, strip colon, trim whitespace.
        $this->assertEquals("Cake", RecipeParser_Text::formatSectionName(" CAKE: "));

        // Remove leading "for".
        $this->assertEquals("Cake", RecipeParser_Text::formatSectionName("For Cake"));

        // Remove leading "for the".
        $this->assertEquals("Cake", RecipeParser_Text::formatSectionName("For the cake"));

        // Upper-case only the first word (until we have a better way of doing this).
        $this->assertEquals("Cake frosting", RecipeParser_Text::formatSectionName("Cake Frosting"));
    }

    public function test_strip_leading_number_with_dot() {
        $this->assertEquals("This is the line.", RecipeParser_Text::stripLeadingNumbers("10 This is the line."));
    }
    public function test_strip_leading_number_without_dot() {
        $this->assertEquals("This is the line.", RecipeParser_Text::stripLeadingNumbers("10. This is the line."));
    }
    public function test_strip_leading_number_with_dot_and_paren() {
        $this->assertEquals("This is the line.", RecipeParser_Text::stripLeadingNumbers("10.) This is the line."));
    }
    public function test_strip_leading_number_with_paren() {
        $this->assertEquals("This is the line.", RecipeParser_Text::stripLeadingNumbers("10) This is the line."));
    }
    public function test_strip_leading_number_no_content() {
        $this->assertEquals("", RecipeParser_Text::stripLeadingNumbers("10."));
    }
    public function test_strip_leading_number_steps() {
        $this->assertEquals("Make the crust", RecipeParser_Text::stripLeadingNumbers("Step 1 Make the crust"));
    }
    public function test_strip_leading_number_steps_colon() {
        $this->assertEquals("Beat the eggs", RecipeParser_Text::stripLeadingNumbers("Step 2: Beat the eggs"));
    }

    public function test_iso8601_minutes() {
        $this->assertEquals(120, RecipeParser_Text::iso8601ToMinutes('PT2H0M'));
        $this->assertEquals(30, RecipeParser_Text::iso8601ToMinutes('PT0,5H'));
        $this->assertEquals(34500, RecipeParser_Text::iso8601ToMinutes('P23DT23H'));
        $this->assertEquals(262974, RecipeParser_Text::iso8601ToMinutes('P0.5Y'));
        $this->assertEquals(751, RecipeParser_Text::iso8601ToMinutes('PT12H30M44S'));
        $this->assertEquals(30, RecipeParser_Text::iso8601ToMinutes('T30M'));
    }

    public function test_rel2abs_abs_path() {
        $this->assertEquals(
            'http://www.bonappetit.com/images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
            RecipeParser_Text::relativeToAbsolute(
                '/images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
                'http://www.bonappetit.com/recipes/2011/06/fathers-day-pork-chops'
            )
        );
    }
    public function test_rel2abs_abs_url() {
        $this->assertEquals(
            'http://www.bonappetit.com/images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
            RecipeParser_Text::relativeToAbsolute(
                'http://www.bonappetit.com/images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
                'http://example.com/some/path/to/fathers-day-pork-chops'
            )
        );
    }

    public function test_rel2abs_rel_path() {
        $this->assertEquals(
            'http://www.bbcgoodfood.com/recipes/96613/images/96613_MEDIUM.jpg',
            RecipeParser_Text::relativeToAbsolute(
                'images/96613_MEDIUM.jpg',
                'http://www.bbcgoodfood.com/recipes/96613/slowcooked-chinese-beef'
            )
        );
    }

    public function test_rel2abs_dot_leading_rel_path() {
        $this->assertEquals(
            'http://www.bbcgoodfood.com/recipes/96613/images/96613_MEDIUM.jpg',
            RecipeParser_Text::relativeToAbsolute(
                './images/96613_MEDIUM.jpg',
                'http://www.bbcgoodfood.com/recipes/96613/slowcooked-chinese-beef'
            )
        );
    }

    public function test_rel2abs_backtrace_rel_path() {
        $this->assertEquals(
            'http://www.bonappetit.com/images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
            RecipeParser_Text::relativeToAbsolute(
                '../../../images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
                'http://www.bonappetit.com/recipes/2011/06/fathers-day-pork-chops'
            )
        );
    }

    public function test_rel2abs_allrecipes_print() {
        $this->assertEquals(
            "http://allrecipes.com/Recipe/Pickled-Beets/Detail.aspx",
            RecipeParser_Text::relativeToAbsolute(
                "../../Recipe/Pickled-Beets/Detail.aspx",
                "http://allrecipes.com/Recipe-Tools/Print/Recipe.aspx?recipeID=38109&origin=detail&servings=60&metric=false"
            )
        );
    }

    public function test_rel2abs_schemeless_https_url() {
        $this->assertEquals(
            "https://images.food52.com/iCTCn3NaUPeL90-DSC_1615.jpg",
            RecipeParser_Text::relativeToAbsolute(
                "//images.food52.com/iCTCn3NaUPeL90-DSC_1615.jpg",
                "https://food52.com/shop/products/4056-to-go-casserole-carrier-set-of-2"
            )
        );
    }

    public function test_filename_from_title() {
        $this->assertEquals("chocolate_macaroons_with_chocolate_or_caramel",
            RecipeParser_Text::formatFilenameFromTitle("Chocolate Macaroons with Chocolate or Caramel Filling - Bon Appétit"));
        $this->assertEquals("grilled_scallions_food_network_kitchens_food",
            RecipeParser_Text::formatFilenameFromTitle("Grilled Scallions Recipe : Food Network Kitchens : Recipes : Food Network"));
    }

}
