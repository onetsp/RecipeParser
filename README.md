RecipeParser
=================================

A PHP library for parsing recipe data from HTML.




Parser Files
------------------------------

- Layout
- Specifics vs. Generic parsers
- Parsers.ini

How it Works
------------------------------

- xpath
- text parsing
- Recipe structure

- microformats and microdata

Many of the top recipe publishers are tending to adopt [microformats, microdata, and RDFa](https://support.google.com/webmasters/answer/173379?hl=en) to identify specific properties of recipes in their templates. There are four generalized parsers that will aid in gathering most of the properties for each of these formats.

MicrodataDataVocabulary.php
MicrodataRdfDataVocabulary.php
MicrodataSchema.php
Microformat.php


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


Fixing a Broken Parser
------------------------------

If you've found a particular recipe parser no longer works the first thing to do would be to open an issue on the project so we can track these. You might also be reading this if you're fixing a parser that has been listed in an open issue.

### 1. Update test recipe files

The `fetch_all_test_files` script in `bin` will download new copies of each test file listed within the appropriate unit test file. This process will also take care of some character encoding and escaping that should be done prior to running the parsing routines, so avoid downloading these files manually.

```
$ cd bin
./fetch_all_test_files ../tests/RecipeParser_Parser_BonappetitcomTest.php 

  Writing data file to /Users/mbrittain/Repos/onetsp/RecipeParser/tests/data
  -rw-r--r--  1 mbrittain  staff   60734 Aug 31 22:22 bonappetit_com_beet_and_fennel_soup_with_kefir_curl.html
  Writing data file to /Users/mbrittain/Repos/onetsp/RecipeParser/tests/data
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













