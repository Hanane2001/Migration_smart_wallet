<?php
namespace App\Models;

use App\Core\Model;

class Dashboard extends Model {
    public function getBalance(int $userId): float {
        $incomeSql = "SELECT COALESCE(SUM(amount_in), 0) as total FROM incomes WHERE user_id = ?";
        $incomeStmt = $this->executeQuery($incomeSql, [$userId]);
        $totalIncome = $incomeStmt->fetch()['total'];
        
        $expenseSql = "SELECT COALESCE(SUM(amount_ex), 0) as total FROM expenses WHERE user_id = ?";
        $expenseStmt = $this->executeQuery($expenseSql, [$userId]);
        $totalExpense = $expenseStmt->fetch()['total'];
        return $totalIncome - $totalExpense;
    }

    public function getCurrentMonthStats(int $userId): array {
        $month = date('m');
        $year = date('Y');
        
        $incomeSql = "SELECT COALESCE(SUM(amount_in), 0) as total 
                     FROM incomes 
                     WHERE user_id = ? 
                     AND EXTRACT(MONTH FROM date_in) = ? 
                     AND EXTRACT(YEAR FROM date_in) = ?";
        $incomeStmt = $this->executeQuery($incomeSql, [$userId, $month, $year]);
        $monthIncome = $incomeStmt->fetch()['total'];
        
        $expenseSql = "SELECT COALESCE(SUM(amount_ex), 0) as total 
                      FROM expenses 
                      WHERE user_id = ? 
                      AND EXTRACT(MONTH FROM date_ex) = ? 
                      AND EXTRACT(YEAR FROM date_ex) = ?";
        $expenseStmt = $this->executeQuery($expenseSql, [$userId, $month, $year]);
        $monthExpense = $expenseStmt->fetch()['total'];
        
        return ['income' => $monthIncome, 'expense' => $monthExpense, 'balance' => $monthIncome - $monthExpense];
    }

    public function getRecentTransactions(int $userId, int $limit = 5): array {
        $incomeSql = "SELECT i.*, c.name_cat as category_name 
                     FROM incomes i 
                     LEFT JOIN categories c ON i.category_id = c.id_cat 
                     WHERE i.user_id = ? 
                     ORDER BY i.date_in DESC, i.created_at DESC 
                     LIMIT ?";
        $incomeStmt = $this->executeQuery($incomeSql, [$userId, $limit]);
        $recentIncomes = $incomeStmt->fetchAll();
        
        $expenseSql = "SELECT e.*, c.name_cat as category_name 
                      FROM expenses e 
                      LEFT JOIN categories c ON e.category_id = c.id_cat 
                      WHERE e.user_id = ? 
                      ORDER BY e.date_ex DESC, e.created_at DESC 
                      LIMIT ?";
        $expenseStmt = $this->executeQuery($expenseSql, [$userId, $limit]);
        $recentExpenses = $expenseStmt->fetchAll();
        
        $transactions = [];
        
        foreach ($recentIncomes as $income) {
            $transactions[] = ['type' => 'income', 'date' => $income['date_in'], 'description' => $income['description_in'], 'amount' => $income['amount_in'], 'category' => $income['category_name'] ?? 'Uncategorized'];
        }
        
        foreach ($recentExpenses as $expense) {
            $transactions[] = ['type' => 'expense', 'date' => $expense['date_ex'], 'description' => $expense['description_ex'], 'amount' => $expense['amount_ex'], 'category' => $expense['category_name'] ?? 'Uncategorized'];
        }

        usort($transactions, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return array_slice($transactions, 0, $limit);
    }

    public function getChartData(int $userId, ?int $year = null): array {
        $year = $year ?? date('Y');
        
        $incomeSql = "SELECT EXTRACT(MONTH FROM date_in) as month, COALESCE(SUM(amount_in), 0) as total 
                     FROM incomes 
                     WHERE user_id = ? AND EXTRACT(YEAR FROM date_in) = ? 
                     GROUP BY EXTRACT(MONTH FROM date_in) 
                     ORDER BY month";
        $incomeStmt = $this->executeQuery($incomeSql, [$userId, $year]);
        $monthlyIncomes = $incomeStmt->fetchAll();
        
        $expenseSql = "SELECT EXTRACT(MONTH FROM date_ex) as month, COALESCE(SUM(amount_ex), 0) as total 
                      FROM expenses 
                      WHERE user_id = ? AND EXTRACT(YEAR FROM date_ex) = ? 
                      GROUP BY EXTRACT(MONTH FROM date_ex) 
                      ORDER BY month";
        $expenseStmt = $this->executeQuery($expenseSql, [$userId, $year]);
        $monthlyExpenses = $expenseStmt->fetchAll();

        $data = ['labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 'income' => array_fill(0, 12, 0), 'expense' => array_fill(0, 12, 0)];
        
        foreach ($monthlyIncomes as $item) {
            $data['income'][$item['month'] - 1] = floatval($item['total']);
        }
        
        foreach ($monthlyExpenses as $item) {
            $data['expense'][$item['month'] - 1] = floatval($item['total']);
        }
        
        return $data;
    }
}
?>