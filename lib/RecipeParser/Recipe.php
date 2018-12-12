<?php

class RecipeParser_Recipe {

    public $title = '';
    public $description = '';
    public $credits = '';
    public $notes = '';
    public $yield = '';
    public $source = '';
    public $url = '';
    public $categories = array();
    public $photo_url = '';
    public $_parser = '';

    // These times are all stored as minutes.
    public $time = array(
        'prep' => 0,
        'cook' => 0,
        'total' => 0
    );

    // Ingredients and instructions are lists of sections, each section
    // contains a name and a list of ingredients.
    public $ingredients;
    public $instructions;

    public function __construct() {
        $this->resetIngredients();
        $this->resetInstructions();
    }

    public function getArray() {
        $arr = array(
            'title' => $this->title,
            'description' => $this->description,
            'credits' => $this->credits,
            'notes' => $this->notes,
            'yield' => $this->yield,
            'source' => $this->source,
            'url' => $this->url,
            'categories' => $this->categories,
            'photo_url' => $this->photo_url,
            'time' => $this->time,
            'ingredients' => $this->ingredients,
            'instructions' => $this->instructions,
            '_parser' => $this->_parser,
        );
        
        return $arr;
    }

    public function getJson() {
        return json_encode($this->getArray());
    }

    public function resetIngredients() {
        $this->ingredients = array(array("name" => "", "list" => array()));
    }

    public function resetInstructions() {
        $this->instructions = array(array("name" => "", "list" => array()));
    }

    public function addIngredientsSection($name="") {
        if (!$name) {
            return;
        }

        // When adding a new section, make sure the previous section was used.
        $index = count($this->ingredients);
        if ($index > 0
            && empty($this->ingredients[$index - 1]['name'])
            && !count($this->ingredients[$index - 1]['list'])
        ) {
            $index--;
        }
        $name = trim($name);
        $this->ingredients[$index] = array('name' => $name, 'list' => array());
    }

    public function addInstructionsSection($name="") {
        if (!$name) {
            return;
        }

        // When adding a new section, make sure the previous section was used.
        $index = count($this->instructions);
        if ($index > 0
            && empty($this->instructions[$index - 1]['name'])
            && !count($this->instructions[$index - 1]['list'])
        ) {
            $index--;
        }
        $name = trim($name);
        $this->instructions[$index] = array('name' => $name, 'list' => array());
    }

    public function appendIngredient($str) {
        if (!empty($str)) {
            $this->ingredients[count($this->ingredients)-1]['list'][] = $str;
        }
    }

    public function appendInstruction($str) {
        if (!empty($str) && !self::isIgnoredInstruction($str)) {
            $this->instructions[count($this->instructions)-1]['list'][] = $str;
        }
    }

    public function isIgnoredInstruction($str) {
        if (preg_match("/^Reprinted with permission/i", $str)) {
            return true;
        } else if (preg_match("/per serving\:.*calories/i", $str)) {
            return true;
        }
        return false;
    }

    public function appendCategory($str) {
        if (!empty($str)) {
            $this->categories[count($this->categories)] = $str;
        }
    }

}
