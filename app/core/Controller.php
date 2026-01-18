<?php
namespace App\Core;

class Controller {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function view($view, $data = []) {
        extract($data);
        require_once '../app/views/' . $view . '.php';
    }

    protected function redirect($path) {
        header('Location: ' . BASE_URL . $path);
        exit();
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function checkAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
            exit();
        }
        return $_SESSION['user_id'];
    }

    protected function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
}
?>