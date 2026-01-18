<?php
namespace App\Core;

use App\Config\config;

class Model {
    protected $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    protected function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            die("Query error: " . $e->getMessage());
        }
    }
}
?>