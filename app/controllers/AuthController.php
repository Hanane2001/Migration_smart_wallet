<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller{
    private $userModel;
    public function __construct(){
        $this->userModel = new User();
    }

    public function login(){
        if($this->isLoggedIn()){
            $this->redirect('dashboard');
            $data = [];
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                if($this->userModel->login($email, $password)){
                    $this->redirect('dashboard');
                }else{
                    $data['error'] = "Invalid email or password"; 
                }
            }
            $this->view('auth/login', $data);
        }
    }

    public function register() {
        if($this->isLoggedIn()){
            $this->redirect('dashboard');
        }
        $data = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $fullName = $_POST['fullName'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';
            if($this->userModel->register($fullName, $email, $password, $confirmPassword)){
                $this->redirect('auth/login?message=registered');
            }else{
                $data['error'] = $_SESSION['errors'] ?? []; 
                unset($_SESSION['errors']);
            }
        }
        $this->view('auth/register', $data);
    }

    public function logout(){
        $this->userModel->logout();
        $this->redirect('auth/login?message=logout');
    }
}
?>