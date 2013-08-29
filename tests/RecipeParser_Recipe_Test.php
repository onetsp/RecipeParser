<?php

require_once '../bootstrap.php';

class RecipeParser_Recipe_Test extends PHPUnit_Framework_TestCase {

	public function testSingleSection() {
        $r = new RecipeParser_Recipe();
        $r->appendIngredient('1 lb Spaghetti');
        $r->appendIngredient('Vegetables');
        $r->appendInstruction('Boil water');
        $r->appendInstruction('Cook pasta');
        $r->appendInstruction('Saute vegetables, mix with pasta.');

        $this->assertEquals(2, count($r->ingredients[0]['list']));
        $this->assertEquals(3, count($r->instructions[0]['list']));
    }

	public function testThreeSection(){
        $r = new RecipeParser_Recipe();
        $r->addIngredientsSection('Pasta');
        $r->appendIngredient('1 lb Spaghetti');
        $r->addIngredientsSection('Vegetables');
        $r->appendIngredient('1 1/2 C Zucchini, quartered and cut up');
        $r->appendIngredient('1 C Asparagus');
        $r->appendIngredient('1/2 C peas');
        $r->addIngredientsSection('Cream Sauce');
        $r->appendIngredient('2 T Unsalted butter');
        $r->appendIngredient('1/4 C Chicken broth');
        $r->appendIngredient('1/4 C Heavy cream');

        $r->addInstructionsSection('Pasta');
        $r->appendInstruction('Cook pasta according to directions on box.');
        $r->addInstructionsSection('Vegetables');
        $r->appendInstruction('Prep all vegetables and saute with some olive oil.');
        $r->appendInstruction('Set aside.');
        $r->addInstructionsSection('Cream sauce');
        $r->appendInstruction('Melt butter over medium-low heat');
        $r->appendInstruction('Stir in chicken broth, followed by cream');

        $this->assertEquals(3, count($r->ingredients));
        $this->assertEquals(1, count($r->ingredients[0]['list']));
        $this->assertEquals(3, count($r->ingredients[1]['list']));
        $this->assertEquals(3, count($r->ingredients[2]['list']));
        $this->assertEquals(3, count($r->instructions));
        $this->assertEquals(1, count($r->instructions[0]['list']));
        $this->assertEquals(2, count($r->instructions[1]['list']));
        $this->assertEquals(2, count($r->instructions[2]['list']));
    }

    public function testMultipleEmptySections() {
        $r = new RecipeParser_Recipe();

        // Empty values for ingredients or instructions should be ingored.
        $r->addIngredientsSection('Pasta');
        $r->appendIngredient('1 lb Spaghetti');
        $r->addIngredientsSection('');
        $r->addIngredientsSection(' ');
        $r->appendIngredient('1 C Water');

        $r->addInstructionsSection('');
        $r->addInstructionsSection(' ');
        $r->addInstructionsSection('  ');
        $r->appendInstruction('Heat water in large pot.');

        $this->assertEquals(2, count($r->ingredients));
        $this->assertEquals(1, count($r->instructions));
    }

}

?>
