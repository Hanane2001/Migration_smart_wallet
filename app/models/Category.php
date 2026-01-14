<?php
namespace App\Models;

use App\Core\Model;

class Category extends Model {
    public function create($name, $type, $userId = null) {
        $sql = "INSERT INTO categories (name_cat, type_cat, user_id) VALUES (?, ?, ?)";
        $stmt = $this->executeQuery($sql, [$name, $type, $userId]);
        return $stmt !== false;
    }

    public function getAll($type = null, $userId = null) {
        $sql = "SELECT * FROM categories WHERE user_id IS NULL";
        $params = [];
        
        if ($userId) {
            $sql .= " OR user_id = ?";
            $params[] = $userId;
        }
        
        if ($type) {
            $sql .= " AND type_cat = ?";
            $params[] = $type;
        }
        
        $sql .= " ORDER BY name_cat";
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM categories WHERE id_cat = ?";
        $stmt = $this->executeQuery($sql, [$id]);
        return $stmt->fetch();
    }

    public function getByType($type, $userId = null) {
        $sql = "SELECT * FROM categories WHERE type_cat = ? AND (user_id IS NULL";
        $params = [$type];
        
        if ($userId) {
            $sql .= " OR user_id = ?";
            $params[] = $userId;
        }
        
        $sql .= ") ORDER BY name_cat";
        
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetchAll();
    }

    public function update($id, $name, $type) {
        $sql = "UPDATE categories SET name_cat = ?, type_cat = ? WHERE id_cat = ?";
        $stmt = $this->executeQuery($sql, [$name, $type, $id]);
        return $stmt !== false;
    }

    public function delete($id, $userId = null) {
        $sql = "DELETE FROM categories WHERE id_cat = ?";
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND (user_id = ? OR user_id IS NULL)";
            $params[] = $userId;
        }
        
        $stmt = $this->executeQuery($sql, $params);
        return $stmt !== false;
    }
}