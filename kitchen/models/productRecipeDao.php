<?php
declare(strict_types=1);
require_once __DIR__ . '/productRecipe.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';

class ProductRecipeDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM product_recipe");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'ProductRecipe'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?ProductRecipe
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM product_recipe WHERE id = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'ProductRecipe'
            );
            $stmt->execute(['id' => $id]);
            $productRecipe = $stmt->fetch();
            if ($productRecipe)
                return $productRecipe;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO product_recipe (recipe_id, ingredient_id) VALUES (:recipeId, :ingredientId)");
            $stmt->execute([
                'recipeId' => $object->getRecipeId(),
                'ingredientId' => $object->getIngredientId()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM product_recipe WHERE id=:id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE product_recipe SET recipe_id=:recipeId, ingredient_id=:ingredientId WHERE id=:id");
            $stmt->execute([
                'id' => $object->getId(),
                'recipeId' => $object->getRecipeId(),
                'ingredientId' => $object->getIngredientId()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }
}
