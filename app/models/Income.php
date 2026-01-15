<?php
namespace App\Models;

use App\Core\Model;

class Income extends Model {
    public function create(float $amount, DateTime $date, string $description, int $userId, ?int $categoryId = null) {
        if (empty($amount) || empty($date) || empty($userId)) {
            return false;
        }
        
        $sql = "INSERT INTO incomes (amount_in, date_in, description_in, user_id, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$amount, $date, $description, $userId, $categoryId]);
        return $stmt !== false;
    }

    public function getAll(int $userId, ?int $limit = null): array {
        $sql = "SELECT i.*, c.name_cat as category_name 
                FROM incomes i 
                LEFT JOIN categories c ON i.category_id = c.id_cat 
                WHERE i.user_id = ? 
                ORDER BY i.date_in DESC, i.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id, ?int $userId = null): array {
        $sql = "SELECT i.*, c.name_cat as category_name 
                FROM incomes i 
                LEFT JOIN categories c ON i.category_id = c.id_cat 
                WHERE i.id_in = ?";
        
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND i.user_id = ?";
            $params[] = $userId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function update(int $id, float $amount, DateTime $date, string $description, int $categoryId, ?int $userId = null) {
        $sql = "UPDATE incomes SET amount_in = ?, date_in = ?, description_in = ?, category_id = ? WHERE id_in = ?";
        $params = [$amount, $date, $description, $categoryId, $id];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt !== false;
    }

    public function delete(int $id, ?int $userId = null) {
        $sql = "DELETE FROM incomes WHERE id_in = ?";
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt !== false;
    }

    public function getTotal(int $userId, ?int $month = null, ?int $year = null) {
        $sql = "SELECT SUM(amount_in) as total FROM incomes WHERE user_id = ?";
        $params = [$userId];
        
        if ($month && $year) {
            $sql .= " AND EXTRACT(MONTH FROM date_in) = ? AND EXTRACT(YEAR FROM date_in) = ?";
            $params[] = $month;
            $params[] = $year;
        } elseif ($month) {
            $sql .= " AND EXTRACT(MONTH FROM date_in) = ? AND EXTRACT(YEAR FROM date_in) = EXTRACT(YEAR FROM CURRENT_DATE)";
            $params[] = $month;
        } elseif ($year) {
            $sql .= " AND EXTRACT(YEAR FROM date_in) = ?";
            $params[] = $year;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getMonthlyTotal(int $userId, ?int $year = null): array {
        $year = $year ?? date('Y');
        $sql = "SELECT EXTRACT(MONTH FROM date_in) as month, SUM(amount_in) as total 
                FROM incomes 
                WHERE user_id = ? AND EXTRACT(YEAR FROM date_in) = ? 
                GROUP BY EXTRACT(MONTH FROM date_in) 
                ORDER BY month";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $year]);
        return $stmt->fetchAll();
    }
}