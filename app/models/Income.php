<?php
namespace App\Models;

use App\Core\Model;

class Income extends Model {
    public function create($amount, $date, $description, $userId, $categoryId = null) {
        if (empty($amount) || empty($date) || empty($userId)) {
            return false;
        }
        
        $sql = "INSERT INTO incomes (amount_in, date_in, description_in, user_id, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->executeQuery($sql, [$amount, $date, $description, $userId, $categoryId]);
        return $stmt !== false;
    }

    public function getAll($userId, $limit = null) {
        $sql = "SELECT i.*, c.name_cat as category_name 
                FROM incomes i 
                LEFT JOIN categories c ON i.category_id = c.id_cat 
                WHERE i.user_id = ? 
                ORDER BY i.date_in DESC, i.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $stmt = $this->executeQuery($sql, [$userId]);
        return $stmt->fetchAll();
    }

    public function getById($id, $userId = null) {
        $sql = "SELECT i.*, c.name_cat as category_name 
                FROM incomes i 
                LEFT JOIN categories c ON i.category_id = c.id_cat 
                WHERE i.id_in = ?";
        
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND i.user_id = ?";
            $params[] = $userId;
        }
        
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetch();
    }

    public function update($id, $amount, $date, $description, $categoryId, $userId = null) {
        $sql = "UPDATE incomes SET amount_in = ?, date_in = ?, description_in = ?, category_id = ? WHERE id_in = ?";
        $params = [$amount, $date, $description, $categoryId, $id];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        $stmt = $this->executeQuery($sql, $params);
        return $stmt !== false;
    }

    public function delete($id, $userId = null) {
        $sql = "DELETE FROM incomes WHERE id_in = ?";
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        $stmt = $this->executeQuery($sql, $params);
        return $stmt !== false;
    }

    public function getTotal($userId, $month = null, $year = null) {
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
        
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getMonthlyTotal($userId, $year = null) {
        $year = $year ?? date('Y');
        $sql = "SELECT EXTRACT(MONTH FROM date_in) as month, SUM(amount_in) as total 
                FROM incomes 
                WHERE user_id = ? AND EXTRACT(YEAR FROM date_in) = ? 
                GROUP BY EXTRACT(MONTH FROM date_in) 
                ORDER BY month";
        
        $stmt = $this->executeQuery($sql, [$userId, $year]);
        return $stmt->fetchAll();
    }
}