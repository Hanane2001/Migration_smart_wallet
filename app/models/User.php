<?php
namespace App\Models;

use App\Core\Model;

class User extends Model {
    public function register($fullName, $email, $password, $confirmPassword) {
        $errors = [];

        if (empty($fullName)) $errors[] = "Full name is required";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
        if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
        if ($password !== $confirmPassword) $errors[] = "Passwords do not match";
        
        if (empty($errors)) {
            $sql = "SELECT id_user FROM users WHERE email = ?";
            $stmt = $this->executeQuery($sql, [$email]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "Email already exists";
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->executeQuery($sql, [$fullName, $email, $hashedPassword]);
        
        if ($stmt) {
            $_SESSION['user_id'] = $this->db->lastInsertId();
            $_SESSION['user_name'] = $fullName;
            $_SESSION['user_email'] = $email;
            
            return true;
        }
        
        return false;
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            $_SESSION['errors'] = ["Email and password are required"];
            return false;
        }
        
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->executeQuery($sql, [$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            
            return true;
        } else {
            $_SESSION['errors'] = ["Invalid email or password"];
            return false;
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}