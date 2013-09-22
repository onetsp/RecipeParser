RecipeParser
=================================

A PHP library for parsing recipe data from HTML.


Usage
------------------------------

Put this library somewhere you can load it from your PHP code. Call `RecipeParser::parse()`, passing in the contents of an HTML file that includes a recipe and, optionally, the URL of the original page, which helps to identify specific parsers to use. The return value is a PHP object containing the recipe's data.

```
$recipe = RecipeParser::parse($html, $url);
print_r($recipe);
```
Output:
```
RecipeStruct Object
(
    [title] => Chai-Spiced Hot Chocolate
    [description] => 
    [notes] => 
    [yield] => 6 servings
    [source] => Bon Appétit
    [url] => http://www.bonappetit.com/recipes/quick-recipes/2010/02/chai_spiced_hot_chocolate
    [categories] => Array
        (
        )

    [photo_url] => http://www.bonappetit.com/wp-content/uploads/2011/01/mare_chai_spiced_hot_chocolate_h1.jpg
    [status] => recipe
    [time] => Array
        (
            [prep] => 15
            [cook] => 0
            [total] => 25
        )

    [ingredients] => Array
        (
            [0] => Array
                (
                    [name] => 
                    [list] => Array
                        (
                            [0] => 4 cups low-fat (1%) milk
                            [1] => 3/4 cup bittersweet chocolate chips
                            [2] => 10 cardamom pods, coarsely cracked
                            [3] => 1/2 teaspoon whole allspice, cracked
                            [4] => 2 cinnamon sticks, broken in half
                            [5] => 1/2 teaspoon freshly ground black pepper
                            [6] => 5 tablespoons (packed) golden brown sugar, divided
                            [7] => 6 quarter-size slices fresh ginger plus 1/2 teaspoon grated peeled fresh ginger
                            [8] => 1 teaspoon vanilla extract, divided
                            [9] => 1/2 cup chilled whipping cream
                        )

                )

        )

    [instructions] => Array
        (
            [0] => Array
                (
                    [name] => 
                    [list] => Array
                        (
                            [0] => Combine first 6 ingredients, 4 tablespoons brown sugar, and ginger slices in medium saucepan. Bring almost to simmer, whisking frequently. Remove from heat; cover and steep 10 minutes. Mix in 1/2 teaspoon vanilla.
                            [1] => Meanwhile, whisk cream, remaining 1 tablespoon brown sugar, grated ginger, and remaining 1/2 teaspoon vanilla in medium bowl to peaks.
                            [2] => Strain hot chocolate. Ladle into 6 mugs. Top each with dollop of ginger cream.
                        )

                )

        )

    [credits] => 
)
```

Additionally, a command-line script is available that demonstrates the library's usage:

```
$ ./bin/parse_recipe tests/data/bonappetit_com_special_sunday_roast_chicken_curl.html 
```

or 

```
$ ./bin/parse_recipe http://www.cooks.com/recipe/3k38r484/baked-ziti.html
```


Introduction
------------------------------

### The Recipe Parsers

The majority of this library is made up of classes that are used for extracting, or scraping, structured recipe data from largely unstructured HTML pages. From a user perspective, `RecipeParser::parse()` is the method you'll mostly be interested in. From a developer (contributor) perspective, you'll find the parsing routines in class files in `lib/RecipeParser/Parsers/`.

The parsing routines are primarily made up of XPath queries for nodes in the HTML DOM. Many recipes found on the Internet are going to be coming from large catalogs on publisher sites, rendered in relatively infrequently changing HTML templates. Each string we parse from HTML into a structured recipe object is typically (1) located in the DOM with an XPath query, then (2) cleaned up using various regular expressions and string replacements (see `RecipeParser_Text` class).

We get a decent return from writing a parser that is specific to a web site knowing that the format of their HTML templates is unlikely to change significantly over the course of a few months, sometimes years. There is a lot of upkeep to maintain these parsers. The increasing use of the hRecipe microformat, microdata (data-vocabulary.org, schema.org, RDFa) has made the task of parsing recipes across many sites somewhat easier, though not perfect. Google's use of [Rich Snippets ](https://support.google.com/webmasters/answer/173379?hl=en) has been a carrot for many web publishers to adopt one of these (relatively) structured formats.

A few "generalized" parsers are available in the library that are either invoked automatically by `RecipeParser::parse()`, based on signals within the HTML content, or used within a custom parser as a starting point. For an example of this, see the first few lines of the `parse()` method within `RecipeParser/Parser/Bonappetitcom.php`.

 - MicrodataDataVocabulary.php
 - MicrodataRdfDataVocabulary.php
 - MicrodataSchema.php
 - Microformat.php

Which parser is used?

The parsing algorithm has to determine which parser will be used for any given recipe file. Customized parsers are selected by pattern matching against the URL of the recipe. Every customized parser is registered in `lib/RecipeParser/Parsers/parsers.ini` and have a corresponding URL pattern. When no custom pattern is matched, the `RecipeParser` class will search for strings within the HTML file that will indicate the use of microformats or microdata to markup the recipe.

Many of the customized parsers will rely heavily on the generalized parsers as starting points for collecting data about a recipe, and then will override, or fill in the gaps, with additional XPath queries and parsing. For example:

```
class RecipeParser_Parser_Bhgcom {

    public function parse($html, $url) {
        $recipe = RecipeParser_Parser_MicrodataSchema::parse($html, $url);

        ...snip...

        // Notes -- Collect any tips/notes that appear at the end of the recipe instructions.
        $notes = array();

        $nodes = $xpath->query('//*[@class="recipeTips"]//li');
        foreach ($nodes as $node) {
            $value = RecipeParser_Text::FormatAsOneLine($node->nodeValue);
            $value = preg_replace("/^(Tip|Note)\s*(.*)$/", "$2", $value);
            $notes[] = $value;
        }
```


Contributing to RecipeParser
=================================

The two biggest needs are **writing new recipe parsers** and **fixing broken parsers** (HTML structure frequently changes on recipe sites, leaving our parsing queries out-of-date).

Dependencies
------------------------------

- PHPUnit



Writing a New Recipe Parser
------------------------------

Many recipes from sites that make use of microformats or microdata can be parsed by the generalized parsers included with RecipeParser. Custom parsers, however, still need to be written for most sites and these, unfortunately, tend to break over time as site owners make changes to their HTML templates.

You can write new parsers for any site you're interested in, or find requested parsers in the [issues list](https://github.com/onetsp/RecipeParser/issues).

### 1. Gather sample HTML files for recipes

To make the most resilient parser for a particular site, you should find a few recipes from the site that have variations in their metadata and format. For example, some with times and yields, and some without. Some sites include section dividers in the ingredients and instructions lists, which is a good thing to test for. Recipes for chocolate cake with icings tend to make good tests.

The `fetch_parser_test_file` script in `bin` will download a recipe and save it locally. The script will also store some metadata (including URL) of the recipe source in an HTML comment at the top of the file.

```
$ ./fetch_parser_test_file http://www.elanaspantry.com/cranberry-coconut-power-bars/

Writing data file to /path/to/RecipeParser/tests/data
-rw-r--r--  1 mbrittain  staff  100412 Sep  8 21:49 elanaspantry_com_cranberry_coconut_power_bars_paleo_power_bars_curl.html
```

### 2. Generate boilerplate unit tests

Running the `import_test_boilerplate` script will generate the boilerplate for a set of unit tests for the new recipe files. The boilerplate code is echoed to stdout, so you should redirect the content to a new test file, as seen here:

```
$ ./bin/import_test_boilerplate tests/data/elanaspantry_com_* > tests/RecipeParser_Parser_ElanaspantrycomTest.php
```

Update the name of the PHPUnit test class in the unit test file. Look for the string "INSERTCLASSNAME" and replace it.

### 3. Write test assertions for each recipe

The boilerplate contains empty test assertions for most of the fields we care about. Title, ingredients, and instructions are really the only fields we need to have a usable parser. Not all fields will exist in the recipe, and you can feel free to delete assertions for fields that cannot be populated from the HTML of the recipe. But our goal is to extract the most out of each recipe.

### 4. Write the RecipeParser_Parser_* class.

Hard to say much more here than read a few of the existing parsers and try to follow them as examples.

### 5. Add to list of registered parsers

```
diff --git a/lib/RecipeParser/Parser/parsers.ini b/lib/RecipeParser/Parser/parsers.ini

   +[Elana's Pantry]
   +pattern = "elanaspantry.com"
   +parser = "Elanaspantrycom"
   +
```

### 6. Verify all tests are passing

Yes, run *all* of the tests.

### 7. Wrap up

Commit your changes and submit a pull request to have your changes merged with `onetsp/RecipeParser`.


Fixing a Broken Parser
------------------------------

If you've found a particular recipe parser no longer works the first thing to do would be to open an issue on the project so we can track these. You might also be reading this if you're fixing a parser that has been listed in an open issue.

### 1. Update test recipe files

The `fetch_all_test_files` script in `bin` will download new copies of each test file listed within the appropriate unit test file. This process will also take care of some character encoding and escaping that should be done prior to running the parsing routines, so avoid downloading these files manually.

```
$ cd bin
./fetch_all_test_files ../tests/RecipeParser_Parser_BonappetitcomTest.php 

  Writing data file to /path/to/RecipeParser/tests/data
  -rw-r--r--  1 mbrittain  staff   60734 Aug 31 22:22 bonappetit_com_beet_and_fennel_soup_with_kefir_curl.html
  Writing data file to /path/to/RecipeParser/tests/data
  -rw-r--r--  1 mbrittain  staff   60011 Aug 31 22:22 bonappetit_com_chai_spiced_hot_chocolate_curl.html
```

Confirm that the downloaded files are stored under the same file names as the original test files. The file names are derived from the `<title>` found within the HTML file. If the format of the title has changed, it's likely that the new test files will be mismatched.

The easiest way to determine this is to use `git status` and see if the test files are listed as "modified" or "untracked." If they are untracked, you'll need to (1) change the file names referenced within the unit test, and (2) `git rm` the old test files.

```
diff --git a/tests/RecipeParser_Parser_BonappetitcomTest.php b/tests/RecipeParser_Parser_BonappetitcomTest.php
index 5a247b8..b0a8a58 100644

     public function test_chia_hot_chocolate() {
-        $path = "data/bonappetit_com_chai_spiced_hot_chocolate_bon_app_tit_curl.html";
+        $path = "data/bonappetit_com_chai_spiced_hot_chocolate_curl.html";
         $url = "http://www.bonappetit.com/recipes/quick-recipes/2010/02/chai_spiced_hot_chocolate";
```


### 2. Identify Failing Tests for the Parser

Run the unit tests for the parser to figure out which assertions are failing.

```
$ phpunit RecipeParser_Parser_BonappetitcomTest.php
```

You can see more of what is going on in the unit test by setting `VERBOSE=1` on the command line. This is probably the easiest way to diagnose major issues in the parser, for example, if the entire instruction list has gone missing.

```
$ VERBOSE=1 phpunit RecipeParser_Parser_BonappetitcomTest.php
```

### 3. Fix the Parser Code

The parser files are found in `/lib/RecipeParser/Parser`. 

Most parsers begin to fail because the site owner (e.g. FoodNetwork.com) has changed the structure of their HTML templates for recipe pages. Most fixes, therefore, will be limited to updating an xpath query to point at a new selection of nodes in the HTML document.

If a publisher has modified their HTML template to use a microformat or microdata layout, you may need to update the parser to use a new (or a different) generalized parser. When this happens, try to rely on the results of the generalized parser as much as possible—as long as that doesn't require writing code in the generalized parser that is overly specific to any one publisher's markup.

*Nobody implements microdata or microformats properly, so I'll admit that what goes into generalized parses versus site-specific parsers is a bit subjective.*

### 4. Confirm All Unit Tests

You'll be testing a specific parser along the way as you fix it. Before committing any changes to parsers, be sure to also verify that all parser tests are passing, since some parsers are dependent on some shared code.

### 5. Wrap-up

Commit your changes and submit a pull request to have your changes merged with `onetsp/RecipeParser`.




Questions?
------------------------------

This documentation is likely incomplete. You can reach me for questions and help at mike@onetsp.com (but, please be patient if I'm slow to respond).







