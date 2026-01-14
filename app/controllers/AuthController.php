<?php
class User{
    private int $id;
    private string $fullName;
    private string $email;
    private string $password;
    private PDO $pdo;

    public function __construct(string $fullName, string $email, string $password){
        $this->setFullName($fullName);
        $this->setEmail($email);
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }

    public function getId(): int{
        return $id;
    }
    public function getFullName(): string{
        return $fullName;
    }
    public function getEmail(): string{
        return $email;
    }
    public function setId(int $id){
        $this->id = $id;
    }
    public function setFullName(string $fullName){
        $this->fullName = $fullName;
    }
    public function setEmail(string $email){
        $this->email = $email;
    }

    public function login($email, $password){}

    public function signup(User $user, $confirmPass): bool{
        $errors = [];
        if(empty($user->fullName)){
            $errors[] = "Full name is required";
        }
        if(!filter_var($user->email, FILTER_VALIDATE_EMAIL)){
            $errors[] = "Invalid email format";
        }
        if(strlen($user->password) < 6){
            $errors[] = "Password must be at least 6 characters";
        }
        if($user->password !== $confirmPass){
            $errors[] = "Passwords do not match";
        }

        if(empty($errors)){
            $stmt = $this->pdo->prepare("SELECT idUser FROM users WHERE email = ?");
            $stmt->execute([$user->email]);
            if($stmt->rowCount() > 0){
                $errors[] = "Email already exists";
            }
        }

        if(!empty($errors)){
            $_SESSION['errors'] = $errors;
            return false;
        }
        $stmt = $this->pdo->prepare("INSERT INTO users (fullName, email, password) VALUES (?, ?, ?)");
        $res = $stmt->execute([$user->fullName, $user->emai, $$user->password]);
        if($res){
            $this->id = $this->pdo->lastInsertId();
            $this->fullName = $user->fullName;
            $this->email = $user->email;
            $_SESSION['user_id'] = $this->id;
            $_SESSION['user_name'] = $user->fullName;
            $_SESSION['user_email'] = $user->email;
            return true;
        }
        return false;
    }
}
?>