<?php
namespace App\Models;

use App\Core\Model;

class Expense extends Model {
    public function create(float $amount, DateTime $date, string $description, int $userId, ?int $categoryId = null): bool{
        if (empty($amount) || empty($date) || empty($userId)) {
            return false;
        }
        
        $sql = "INSERT INTO expenses (amount_ex, date_ex, description_ex, user_id, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$amount, $date, $description, $userId, $categoryId]);
        return $stmt !== false;
    }

    public function getAll(int $userId, ?int $limit = null): array {
        $sql = "SELECT e.*, c.name_cat as category_name 
                FROM expenses e 
                LEFT JOIN categories c ON e.category_id = c.id_cat 
                WHERE e.user_id = ? 
                ORDER BY e.date_ex DESC, e.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id, ?int $userId = null): array {
        $sql = "SELECT e.*, c.name_cat as category_name 
                FROM expenses e 
                LEFT JOIN categories c ON e.category_id = c.id_cat 
                WHERE e.id_ex = ?";
        
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND e.user_id = ?";
            $params[] = $userId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function update(int $id, float $amount, DateTime $date, string $description, int $categoryId, ?int $userId = null): bool {
        $sql = "UPDATE expenses SET amount_ex = ?, date_ex = ?, description_ex = ?, category_id = ? WHERE id_ex = ?";
        $params = [$amount, $date, $description, $categoryId, $id];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt !== false;
    }

    public function delete(int $id, ?int $userId = null): bool {
        $sql = "DELETE FROM expenses WHERE id_ex = ?";
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt !== false;
    }

    public function getTotal(int $userId, ?int $month = null, ?int $year = null): float {
        $sql = "SELECT SUM(amount_ex) as total FROM expenses WHERE user_id = ?";
        $params = [$userId];
        
        if ($month && $year) {
            $sql .= " AND EXTRACT(MONTH FROM date_ex) = ? AND EXTRACT(YEAR FROM date_ex) = ?";
            $params[] = $month;
            $params[] = $year;
        } elseif ($month) {
            $sql .= " AND EXTRACT(MONTH FROM date_ex) = ? AND EXTRACT(YEAR FROM date_ex) = EXTRACT(YEAR FROM CURRENT_DATE)";
            $params[] = $month;
        } elseif ($year) {
            $sql .= " AND EXTRACT(YEAR FROM date_ex) = ?";
            $params[] = $year;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($sql, $params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getMonthlyTotal(int $userId, ?int $year = null): array {
        $year = $year ?? date('Y');
        $sql = "SELECT EXTRACT(MONTH FROM date_ex) as month, SUM(amount_ex) as total 
                FROM expenses 
                WHERE user_id = ? AND EXTRACT(YEAR FROM date_ex) = ? 
                GROUP BY EXTRACT(MONTH FROM date_ex) 
                ORDER BY month";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $year]);
        return $stmt->fetchAll();
    }

    public function getCategoryTotal(int $userId, ?int $month = null, ?int $year = null): array {
        $sql = "SELECT c.name_cat, SUM(e.amount_ex) as total 
                FROM expenses e 
                LEFT JOIN categories c ON e.category_id = c.id_cat 
                WHERE e.user_id = ?";
        
        $params = [$userId];
        
        if ($month && $year) {
            $sql .= " AND EXTRACT(MONTH FROM e.date_ex) = ? AND EXTRACT(YEAR FROM e.date_ex) = ?";
            $params[] = $month;
            $params[] = $year;
        }
        
        $sql .= " GROUP BY e.category_id, c.name_cat ORDER BY total DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($sql, $params);
        return $stmt->fetchAll();
    }
}