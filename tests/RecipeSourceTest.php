<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . '/../bootstrap.php';

class RecipeSourceTest extends TestCase {

    public function test_existing_source_with_www() {
        $this->assertEquals('The New York Times', RecipeSource::getSourceNameByUrl('http://www.nytimes.com/2011/03/30/dining/30braiserex1.html'));
    }

    public function test_existing_source_without_www() {
        $this->assertEquals('BBC Good Food', RecipeSource::getSourceNameByUrl('http://bbcgoodfood.com/recipes/3092/ultimate-chocolate-cake'));
    }

    public function test_missing_source() {
        $this->assertEquals(null, RecipeSource::getSourceNameByUrl('http://www.example.com/recipe/test'));
    }

}

