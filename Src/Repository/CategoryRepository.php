<?php

namespace App\Repository;

use App\Core\Database;
use App\Models\Category;


class CategoryRepository
{
    public static function where($column, $value): ?Category
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM category WHERE $column = :$column";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":$column", $value);
        $stmt->execute();
        if (!$stmt->rowCount()) {
            return null;
        }
        $category = $stmt->fetchObject(Category::class);
        return $category;
    }

    public static function first(): ?Category
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM category LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $category = $stmt->fetchObject(Category::class);
        return $category;
    }

    public function findAll(): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM category');
        $stmt->execute();
        $categoryData = $stmt->fetchAll();
        $category = [];
        foreach ($categoryData as $data) {
            $category[] = $this->hydrate($data);
        }
        return $category;
    }

    public function findById(int $id): ?Category
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM category WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }
        return $this->hydrate($data);
    }

    private function hydrate(array $data): Category
    {
        return new Category(
            $data['id'],
            $data['name'],
            $data['slug']
        );
    }
}
