RecipeParser

A PHP library for parsing recipe data from HTML.




How to Contribute to RecipeParser
=================================

The two biggest needs are *writing new recipe parsers* and *fixing broken parsers* (HTML structure frequently changes on recipe sites, leaving our parsing queries out-of-date).



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
  ...snip...
```

Confirm that the downloaded files are stored under the same file names as the original test files. The file names are derived from the `<title>` found within the HTML file. If the format of the title has changed, it's likely that the new test files will be mismatched.

The easiest way to determine this is to use `git status` and see if the test files are listed as "modified" or "untracked." If they are untracked, you'll need to (1) rename the test files within the unit test, and then (2) `git rm` the old test files.















