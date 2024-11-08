<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Model
{
    protected $con;
    protected $table_name;
    protected $primary_key;

    public function __construct()
    {
        $database = new Database();
        $this->con = $database->getConnection();
    }

    // Method to fetch all records
    public function readAll($override_query = "")
    {
        $query = strlen($override_query) ? $override_query : "SELECT * FROM {$this->table_name}";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = :{$this->primary_key}";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(":{$this->primary_key}", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
