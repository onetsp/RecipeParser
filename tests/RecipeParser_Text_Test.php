<?php

require_once './bootstrap.php';

class RecipeParser_Text_Test extends PHPUnit_Framework_TestCase {

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

    public function test_title_starts_recipe_for() {
        $this->assertEquals("Bananas Foster",
                            RecipeParser_Text::formatTitle(" recipe for Bananas Foster"));
    }
    public function test_title_ends_recipe() {
        $this->assertEquals("Bananas Foster",
                            RecipeParser_Text::formatTitle(" Bananas Foster Recipe "));
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
        $this->assertEquals('1 portion', RecipeParser_Text::formatYield('One portion.'));
        $this->assertEquals('12 servings', RecipeParser_Text::formatYield('Twelve servings'));
        $this->assertEquals('8 to 10 servings', RecipeParser_Text::formatYield('Serves 8 to 10'));
        $this->assertEquals('10', RecipeParser_Text::formatYield('Makes 10'));
        $this->assertEquals('10 servings', RecipeParser_Text::formatYield('servings 10'));
        $this->assertEquals('8 to 10 servings', RecipeParser_Text::formatYield('servings 8 to 10'));
        $this->assertEquals('8 - 10 servings', RecipeParser_Text::formatYield('servings 8 - 10'));
        $this->assertEquals('8-10 servings', RecipeParser_Text::formatYield('servings 8-10'));
        $this->assertEquals('8 servings', RecipeParser_Text::formatYield('Yield: 8 Servings'));
        $this->assertEquals('8 servings', RecipeParser_Text::formatYield('Yield 8 Servings'));
        $this->assertEquals('8 servings', RecipeParser_Text::formatYield('Servings: 8 Servings'));
        $this->assertEquals('8 servings', RecipeParser_Text::formatYield('Servings 8 Servings'));
        $this->assertEquals('10 to 12 servings', RecipeParser_Text::formatYield('servings: 10 to 12'));
        $this->assertEquals('8-10 servings', RecipeParser_Text::formatYield('8–10 servings')); // mdash
    }

    public function test_parse_list_from_blob() {

        $str = "
        1. this is item one.
        2. Item two spans
        multple lines
        3 The third item has no dot after the leading number. 
        * The fourth is an asterisk

        - And this is the fifth.
        ";
        $list = RecipeParser_Text::parseListFromBlob($str);
        $this->assertEquals(5, count($list));
        $this->assertEquals("this is item one.", $list[0]);
        $this->assertEquals("Item two spans multple lines", $list[1]);
        $this->assertEquals("The third item has no dot after the leading number.", $list[2]);
        $this->assertEquals("The fourth is an asterisk", $list[3]);
        $this->assertEquals("And this is the fifth.", $list[4]);
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

    public function test_iso8601_minutes() {
        $this->assertEquals(120, RecipeParser_Text::iso8601ToMinutes('PT2H0M'));
        $this->assertEquals(30, RecipeParser_Text::iso8601ToMinutes('PT0,5H'));
        $this->assertEquals(34500, RecipeParser_Text::iso8601ToMinutes('P23DT23H'));
        $this->assertEquals(262974, RecipeParser_Text::iso8601ToMinutes('P0.5Y'));
        $this->assertEquals(751, RecipeParser_Text::iso8601ToMinutes('PT12H30M44S'));
        $this->assertEquals(30, RecipeParser_Text::iso8601ToMinutes('T30M'));
    }

    public function test_format_photo_url_abs_path() {
        $this->assertEquals(
            'http://www.bonappetit.com/images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
            RecipeParser_Text::formatPhotoUrl(
                '/images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
                'http://www.bonappetit.com/recipes/2011/06/fathers-day-pork-chops'
            )
        );
    }
    public function test_format_photo_url_abs_url() {
        $this->assertEquals(
            'http://www.bonappetit.com/images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
            RecipeParser_Text::formatPhotoUrl(
                'http://www.bonappetit.com/images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
                'http://example.com/some/path/to/fathers-day-pork-chops'
            )
        );
    }

    public function test_format_photo_url_rel_path() {
        $this->assertEquals(
            'http://www.bbcgoodfood.com/recipes/96613/images/96613_MEDIUM.jpg',
            RecipeParser_Text::formatPhotoUrl(
                'images/96613_MEDIUM.jpg',
                'http://www.bbcgoodfood.com/recipes/96613/slowcooked-chinese-beef'
            )
        );
    }

    public function test_format_photo_url_dot_leading_rel_path() {
        $this->assertEquals(
            'http://www.bbcgoodfood.com/recipes/96613/images/96613_MEDIUM.jpg',
            RecipeParser_Text::formatPhotoUrl(
                './images/96613_MEDIUM.jpg',
                'http://www.bbcgoodfood.com/recipes/96613/slowcooked-chinese-beef'
            )
        );
    }

    public function test_format_photo_url_backtrace_rel_path() {
        $this->markTestSkipped(
            'Have not written code to resolve backtraced relative photo URLs.'
        );
        $this->assertEquals(
            'http://www.bonappetit.com/images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
            RecipeParser_Text::formatPhotoUrl(
                '../../../images/magazine/2011/06/mare-fathers-day-pork-chops-h.jpg',
                'http://www.bonappetit.com/recipes/2011/06/fathers-day-pork-chops'
            )
        );
    }

}

?>
