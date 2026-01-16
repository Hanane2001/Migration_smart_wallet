<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Income;
use App\Models\Category;

class IncomeController extends Controller{
    private $incomeModel;
    private $categoryModel;
    public function __construct(){
        $this->checkAuth();
        $this->incomeModel = new Income();
        $this->categoryModel = new Category();
    }
    
    public function index(): void{
        $userId = $this->getCurrentUserId();
        $data = ['incomes' => $this->incomeModel->getAll($userId),
        'categories' => $this->categoryModel->getByType('income', $userId)];
        $this->view('incomes/index', $data);
    }

    public function create(): void{
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $userId = $this->getCurrentUserId();
            $amount = $_POST['amount'];
            $date = $_POST['date'];
            $description = $_POST['description'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;
            $success = $this->incomeModel->create($amount, $date, $description, $userId, $categoryId);
            if($success){
                $this->redirect('income?message=added');
            }else{
                $this->redirect('income?error=insert_failed');
            }
        }
    }

    public function edit(int $id): void{
        $userId = $this->getCurrentUserId();
        $data = ['income' => $this->incomeModel->getById($id, $userId),
        'categories' => $this->categoryModel->getByType('income', $userId)];
        if(!$data['income']){
            $this->redirect('income?error=not_found');
        }
        $this->view('incomes/edit', $data);
    }

    public function update(int $id): void{
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $userId = $this->getCurrentUserId();
            $amount = $_POST['amount'];
            $date = $_POST['date'];
            $description = $_POST['description'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;
            $success = $this->incomeModel->update($id, $amount, $date, $description, $categoryId, $userId);
            if($success){
                $this->redirect('income?message=updated');
            }else{
                $this->redirect('income/edit/'.$id.'?error=update_failed');
            }
        }
    }

    public function delete(int $id): void{
        $userId = $this->getCurrentUserId();
        $success = $this->incomeModel->delete($id, $userId);
        if($success){
            $this->redirect('income?message=deleted');
        }else{
            $this->redirect('income?error=delete_failed');
        }
    }
}
?>