<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Expense;
use App\Models\Category;

class ExpenseController extends Controller{
    private $expenseModel;
    private $categoryModel;
    public function __construct(){
        $this->checkAuth();
        $this->expenseModel = new Expense();
        $this->categoryModel = new Category();
    }
    
    public function index(): void {
        $userId = $this->getCurrentUserId();
        $data = ['expenses' => $this->expenseModel->getAll($userId), 'categories' => $this->categoryModel->getByType('expense', $userId)];
        $this->view('expenses/index', $data);
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->getCurrentUserId();
            $amount = $_POST['amount'] ?? 0;
            $date = $_POST['date'] ?? '';
            $description = $_POST['description'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;
            $success = $this->expenseModel->create($amount, $date, $description, $userId, $categoryId);
            if ($success) {
                $this->redirect('expense?message=added');
            } else {
                $this->redirect('expense?error=insert_failed');
            }
        }
    }

    public function edit(int $id): void {
        $userId = $this->getCurrentUserId();
        $data = ['expense' => $this->expenseModel->getById($id, $userId), 'categories' => $this->categoryModel->getByType('expense', $userId)];
        if (!$data['expense']) {
            $this->redirect('expense?error=not_found');
            return;
        }
        $this->view('expenses/edit', $data);
    }

    public function update(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->getCurrentUserId();
            $amount = $_POST['amount'] ?? 0;
            $date = $_POST['date'] ?? '';
            $description = $_POST['description'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;
            $success = $this->expenseModel->update($id, $amount, $date, $description, $categoryId, $userId);
            if ($success) {
                $this->redirect('expense?message=updated');
            } else {
                $this->redirect('expense/edit/' . $id . '?error=update_failed');
            }
        }
    }

    public function delete(int $id): void {
        $userId = $this->getCurrentUserId();
        $success = $this->expenseModel->delete($id, $userId);
        if ($success) {
            $this->redirect('expense?message=deleted');
        } else {
            $this->redirect('expense?error=delete_failed');
        }
    }
}
?>