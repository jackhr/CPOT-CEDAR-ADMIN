<?php

namespace App\Models;

use App\Config\Database;
use Error;
use Exception;
use PDO;

class Model
{
    protected $con;
    protected $table_name;
    protected $primary_key;

    public function __construct(PDO $connection = null)
    {
        if ($connection) {
            $this->con = $connection;
        } else {
            $database = new Database();
            $this->con = $database->getConnection();
        }
    }

    // Method to fetch all records
    public function readAll($override_query = "", $index_by = null)
    {
        $query = strlen($override_query) ? $override_query : "SELECT * FROM {$this->table_name}";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($index_by === null) return $results;

        if (!isset($results[0][$index_by])) {
            throw new Exception("Column '{$index_by}' does not exist in the result set.");
        }

        $indexed_results = [];
        foreach ($results as $row) {
            $indexed_results[$row[$index_by]] = $row;
        }

        return $indexed_results;
    }

    public function destroy($id) {
        try {
            $query = "DELETE FROM {$this->table_name} WHERE {$this->primary_key} = :{$this->primary_key}";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(":{$this->primary_key}", $id);
            return $stmt->execute();
        } catch(Exception $e) {
            throw new Error($e->getMessage());
        }
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = :{$this->primary_key}";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(":{$this->primary_key}", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function DBRaw($query)
    {
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
