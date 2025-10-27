<?php

class ProductRecipe {
    private $id;
    private $recipe_id;
    private $ingredient_id;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getRecipeId() {
        return $this->recipe_id;
    }

    public function setRecipeId($recipe_id) {
        $this->recipe_id = $recipe_id;
    }

    public function getIngredientId() {
        return $this->ingredient_id;
    }

    public function setIngredientId($ingredient_id) {
        $this->ingredient_id = $ingredient_id;
    }
}
