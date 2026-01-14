<?php
namespace App\Models;

use App\Core\Model;

class Expense extends Model {
    public function create($amount, $date, $description, $userId, $categoryId = null) {
        if (empty($amount) || empty($date) || empty($userId)) {
            return false;
        }
        
        $sql = "INSERT INTO expenses (amount_ex, date_ex, description_ex, user_id, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->executeQuery($sql, [$amount, $date, $description, $userId, $categoryId]);
        return $stmt !== false;
    }

    public function getAll($userId, $limit = null) {
        $sql = "SELECT e.*, c.name_cat as category_name 
                FROM expenses e 
                LEFT JOIN categories c ON e.category_id = c.id_cat 
                WHERE e.user_id = ? 
                ORDER BY e.date_ex DESC, e.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $stmt = $this->executeQuery($sql, [$userId]);
        return $stmt->fetchAll();
    }

    public function getById($id, $userId = null) {
        $sql = "SELECT e.*, c.name_cat as category_name 
                FROM expenses e 
                LEFT JOIN categories c ON e.category_id = c.id_cat 
                WHERE e.id_ex = ?";
        
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND e.user_id = ?";
            $params[] = $userId;
        }
        
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetch();
    }

    public function update($id, $amount, $date, $description, $categoryId, $userId = null) {
        $sql = "UPDATE expenses SET amount_ex = ?, date_ex = ?, description_ex = ?, category_id = ? WHERE id_ex = ?";
        $params = [$amount, $date, $description, $categoryId, $id];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        $stmt = $this->executeQuery($sql, $params);
        return $stmt !== false;
    }

    public function delete($id, $userId = null) {
        $sql = "DELETE FROM expenses WHERE id_ex = ?";
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        $stmt = $this->executeQuery($sql, $params);
        return $stmt !== false;
    }

    public function getTotal($userId, $month = null, $year = null) {
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
        
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getMonthlyTotal($userId, $year = null) {
        $year = $year ?? date('Y');
        $sql = "SELECT EXTRACT(MONTH FROM date_ex) as month, SUM(amount_ex) as total 
                FROM expenses 
                WHERE user_id = ? AND EXTRACT(YEAR FROM date_ex) = ? 
                GROUP BY EXTRACT(MONTH FROM date_ex) 
                ORDER BY month";
        
        $stmt = $this->executeQuery($sql, [$userId, $year]);
        return $stmt->fetchAll();
    }

    public function getCategoryTotal($userId, $month = null, $year = null) {
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
        
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->fetchAll();
    }
}