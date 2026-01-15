<?php
namespace App\Models;

use App\Core\Model;

class Category extends Model {
    public function create(string $name, string $type, ?int $userId = null): bool {
        $sql = "INSERT INTO categories (name_cat, type_cat, user_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $type, $userId]);
        return $stmt !== false;
    }

    public function getAll(?string $type = null, ?int $userId = null): array {
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
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): array {
        $sql = "SELECT * FROM categories WHERE id_cat = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByType(?string $type, ?int $userId = null): array {
        $sql = "SELECT * FROM categories WHERE type_cat = ? AND (user_id IS NULL";
        $params = [$type];
        
        if ($userId) {
            $sql .= " OR user_id = ?";
            $params[] = $userId;
        }
        
        $sql .= ") ORDER BY name_cat";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function update(int $id, string $name, string $type): bool {
        $sql = "UPDATE categories SET name_cat = ?, type_cat = ? WHERE id_cat = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $type, $id]);
        return $stmt !== false;
    }

    public function delete(int $id, ?int $userId = null): bool {
        $sql = "DELETE FROM categories WHERE id_cat = ?";
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND (user_id = ? OR user_id IS NULL)";
            $params[] = $userId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt !== false;
    }
}