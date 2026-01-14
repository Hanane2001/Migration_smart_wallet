<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'postgres');
define('DB_PASS', 'hanane');
define('DB_NAME', 'smart_wallet');
define('DB_PORT', 5432);

class Database{
    private static $instance = null;
    private PDO $pdo;

    private function __construct(){
        try{
            $dsn = "pgsql:host=" . self::DB_HOST .";port=". self::DB_PORT . ";dbname=" . self::DB_NAME . ";options='--client_encoding=UTF8'";
            $this->pdo = new PDO($dsn, self::DB_USER, self::DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->exec("SET search_path TO hanan");
        }catch(PDOException $e){
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this->pdo;
    }
}

?>