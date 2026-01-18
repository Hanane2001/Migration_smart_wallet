<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Dashboard;

class DashboardController extends Controller {
    private $incomeModel;
    private $expenseModel;
    private $dashboardModel;

    public function __construct() {
        $this->checkAuth();
        $this->incomeModel = new Income();
        $this->expenseModel = new Expense();
        $this->dashboardModel = new Dashboard();
    }

    public function index() {
        $userId = $this->getCurrentUserId();
        
        $data = ['totalIncome' => $this->incomeModel->getTotal($userId), 'totalExpense' => $this->expenseModel->getTotal($userId), 'balance' => $this->dashboardModel->getBalance($userId), 'monthStats' => $this->dashboardModel->getCurrentMonthStats($userId), 'recentTransactions' => $this->dashboardModel->getRecentTransactions($userId), 'chartData' => $this->dashboardModel->getChartData($userId)];

        $this->view('dashboard/index', $data);
    }
}
?>