<?php

class RecipeParser_Recipe {

    public $title = '';
    public $description = '';
    public $notes = '';
    public $yield = '';
    public $source = '';
    public $url = '';
    public $categories = array();
    public $photo_url = '';

    // These times are all stored as minutes.
    public $time = array(
        'prep' => 0,
        'cook' => 0,
        'total' => 0
    );

    // Ingredients and instructions are lists of sections, each section
    // contains a name and a list of ingredients.
    public $ingredients = array();
    public $instructions = array();

    public function resetIngredients() {
        $this->ingredients = array();
    }

    public function resetInstructions() {
        $this->instructions = array();
    }

    public function addIngredientsSection($name = '') {
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

    public function addInstructionsSection($name = '') {
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
            if (!count($this->ingredients)) {
                $this->addIngredientsSection();
            }
            $this->ingredients[count($this->ingredients)-1]['list'][] = $str;
        }
    }

    public function appendInstruction($str) {
        if (!empty($str)) {
            if (!count($this->instructions)) {
                $this->addInstructionsSection();
            }
            $this->instructions[count($this->instructions)-1]['list'][] = $str;
        }
    }

    public function getIngredientsMarkdown() {
        $list = array();
        foreach ($this->ingredients as $sec) {
            if ($sec['name']) {
                $list[] = '# ' . $sec['name'];
            }
            $list = array_merge($list, $sec['list']);
        }
        $string = implode("\n", $list);
        $string = Input::cleanupIngredients($string);
        return $string;
    }

    public function getInstructionsMarkdown() {
        $list = array();
        foreach ($this->instructions as $sec) {
            if ($sec['name']) {
                $list[] = '# ' . $sec['name'];
            }
            $list = array_merge($list, $sec['list']);
        }
        $string = implode("\n\n", $list);
        $string = Input::cleanupInstructions($string);
        return $string;
    }

    public function toArray() {
        $arr = array();
        $arr['title'] = $this->title;
        $arr['description'] = $this->description;
        $arr['notes'] = $this->notes;
        $arr['source_name'] = $this->source;
        $arr['source_url'] = $this->url;
        $arr['yield'] = $this->yield;
        $arr['prep_minutes'] = $this->time['prep'];
        $arr['cook_minutes'] = $this->time['cook'];
        $arr['total_minutes'] = $this->time['total'];
        $arr['ingredients'] = $this->getIngredientsMarkdown();
        $arr['instructions'] = $this->getInstructionsMarkdown();
        return $arr;
    }

}

