<?php
class CategoryController extends Controller{
    private $categoryModel;
    public function __construct(){
        $this->checkAuth();
        $this->categoryModel = new Category();
    }

    public function index(): void{
        $userId = $this->getCurrentUserId();
        $data = ['categories' => $this->categoryModel->getByType(null, $userId)];
        $this->view('categories/index', $data);
    }

    public function create(): void{
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $userId = $this->getCurrentUserId();
            $name = $_POST['name'];
            $type = $_POST['type'];
            $success = $this->categoryModel->create($name, $type, $userId);
            if($success){
                $this->redirect('category?message=added');
            }else{
                $this->redirect('category?error=insert_failed');
            }
        }
    }

    public function delete(int $id): void{
        $userId = $this->getCurrentUserId();
        $success = $this->categoryModel->delete($id, $userId);
        if($success){
            $this->redirect('category?message=deleted');
        }else{
            $this->redirect('category?error=delete_failed');
        }
    }
}
?>