<?php

declare(strict_types=1);
require_once __DIR__ . '/ingredientes.php';
require_once __DIR__ . '/../DBConnection.php';
require_once __DIR__ . '/../IDbAccess.php';


class IngredientesDao implements IDbAccess
{
    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM ingredients");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Ingredient'
            );
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?Ingredient
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM ingredients WHERE ID = :id");
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'Ingredient'
            );
            $stmt->execute(['id' => $id]);
            $contact = $stmt->fetch();
            if ($contact)
                return $contact;
        }
        return null;
    }

    public static function insert($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO ingredients (ID, fName, packaging, cantidad, fechaElab, fechaCad, warehouse, costCurrency, costPrice, saleCurrency, salePrice, codeContents, image, unidad, merma, atr_name_tienda, atr_valores_tienda, descripcion_corta, expira_dias, clasificacion_ing)
   VALUES (null, :fName, :packaging, :cantidad, :fechaElab, :fechaCad, :warehouse, :costCurrency, :costPrice, :saleCurrency, :salePrice, :codeContents, :image, :unidad, :merma, :atr_name_tienda, :atr_valores_tienda, :descripcion_corta, :expira_dias, :clasificacion_ing)");
            $stmt->execute([
                'fName' => $object->getFName(),
                'packaging' => $object->getPackaging(),
                'cantidad' => $object->getCantidad(),
                'fechaElab' => $object->getFechaElab(),
                'fechaCad' => $object->getFechaCad(),
                'warehouse' => $object->getWarehouse(),
                'costCurrency' => $object->getCostCurrency(),
                'costPrice' => $object->getCostPrice(),
                'saleCurrency' => $object->getSaleCurrency(),
                'salePrice' => $object->getSalePrice(),
                'codeContents' => $object->getCodeContents(),
                'image' => $object->getImage(),
                'unidad' => $object->getUnidad(),
                'merma' => $object->getMerma(),
                'atr_name_tienda' => $object->getAtrNameTienda(),
                'atr_valores_tienda' => $object->getAtrValoresTienda(),
                'descripcion_corta' => $object->getDescripcionCorta(),
                'expira_dias' => $object->getCaducidad(),
                'clasificacion_ing' => $object->getClasificacionIng()
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function delete($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM ingredients WHERE ID=:id");
            $stmt->execute(['id' => $object->getID()]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE ingredients SET fName=:fName, packaging=:packaging, cantidad=:cantidad, fechaElab=:fechaElab, fechaCad=:fechaCad, warehouse=:warehouse, costCurrency=:costCurrency, costPrice=:costPrice, saleCurrency=:saleCurrency, salePrice=:salePrice, codeContents=:codeContents, image=:image, unidad=:unidad, peso=:peso, atr_name_tienda=:atr_name_tienda, atr_valores_tienda=:atr_valores_tienda, descripcion_corta=:descripcion_corta, expira_dias=:expira_dias, clasificacion_ing=:clasificacion_ing WHERE ID=:id");
            $stmt->execute([
                'id' => $object->getID(),
                'fName' => $object->getFName(),
                'packaging' => $object->getPackaging(),
                'cantidad' => $object->getCantidad(),
                'fechaElab' => $object->getFechaElab(),
                'fechaCad' => $object->getFechaCad(),
                'warehouse' => $object->getWarehouse(),
                'costCurrency' => $object->getCostCurrency(),
                'costPrice' => $object->getCostPrice(),
                'saleCurrency' => $object->getSaleCurrency(),
                'salePrice' => $object->getSalePrice(),
                'codeContents' => $object->getCodeContents(),
                'image' => $object->getImage(),
                'unidad' => $object->getUnidad(),
                'peso' => $object->getPeso(),
                'atr_name_tienda' => $object->getAtrNameTienda(),
                'atr_valores_tienda' => $object->getAtrValoresTienda(),
                'descripcion_corta' => $object->getDescripcionCorta(),
                'expira_dias' => $object->getCaducidad(),
                'clasificacion_ing' => $object->getClasificacionIng()
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    // método para obtener el nombre de un ingrediente por ID
    public static function getNombreById($id): ?string
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT fName FROM ingredients WHERE ID = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && isset($result['fName'])) {
                return $result['fName'];
            }
        }
        return null;
    }

    public static function getIngredientesByIds(array $ids): array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn) && !empty($ids)) {
            // Convertimos el array de IDs en una lista separada por comas para la consulta IN
            $placeholders = explode(',', $ids[0]);


            $stmt = $conn->prepare("SELECT * FROM ingredients WHERE ID IN ($placeholders)");
            $stmt->execute($ids);
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Ingredient'); // Devuelve un array de objetos Ingredient
        }
        return []; // Retorna un array vacío si no hay conexión o no hay IDs
    }



    public static function deleteWithDependencies(int $id): bool
    {
        $conn = DBConnection::connectDB();

        try {
            // Iniciar una transacción para asegurar la consistencia
            $conn->beginTransaction();

            // Eliminar relaciones en otras tablas ANTES de eliminar el ingrediente
            $stmt = $conn->prepare("DELETE FROM ingredientesalergenos WHERE id_ingrediente = :id");
            $stmt->execute(['id' => $id]);

            $stmt = $conn->prepare("DELETE FROM receta_ingrediente WHERE ingrediente = :id");
            $stmt->execute(['id' => $id]);

            $stmt = $conn->prepare("DELETE FROM almaceningredientes WHERE ingrediente_id = :id");
            $stmt->execute(['id' => $id]);

            $stmt = $conn->prepare("DELETE FROM stock_ing_kitchen WHERE ingredient_id = :id");
            $stmt->execute(['id' => $id]);

            // Finalmente, eliminar el ingrediente
            $stmt = $conn->prepare("DELETE FROM ingredients WHERE ID = :id");
            $stmt->execute(['id' => $id]);

            // Confirmar la transacción
            $conn->commit();
            return true;
        } catch (Exception $e) {
            // Si hay un error, hacer rollback para evitar datos inconsistentes
            $conn->rollBack();
            throw new Exception("Error al eliminar el ingrediente: " . $e->getMessage());
        }
    }



    public static function nameExists(string $name): bool
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM ingredients WHERE fName = :name");
            $stmt->execute(['name' => $name]);
            $count = $stmt->fetchColumn();

            return $count > 0;
        }
        return false;
    }
}
