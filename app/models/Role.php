<?php

namespace App\Models;

use Exception;
use PDO;

class Role extends Model
{
    public $role_id;
    public $role_name;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = "roles";
        $this->primary_key = "role_id";
    }

    public function update()
    {
        $query = "UPDATE {$this->table_name} SET role_name = :role_name WHERE role_id = :role_id";
        $stmt = $this->con->prepare($query);

        // Sanitize input
        $this->role_name = htmlspecialchars($this->role_name);
        $this->role_id = htmlspecialchars($this->role_id);

        // Bind parameters
        $stmt->bindParam(":role_name", $this->role_name);
        $stmt->bindParam(":role_id", $this->role_id, PDO::PARAM_INT);

        // Execute the query
        return $stmt->execute();
    }


    // Method to create a new role
    public function create()
    {
        $query = "INSERT INTO {$this->table_name} SET role_name=:role_name";
        $stmt = $this->con->prepare($query);

        // Sanitize input
        $this->role_name = htmlspecialchars(strip_tags($this->role_name));

        // Bind parameters
        $stmt->bindParam(":role_name", $this->role_name);

        // Execute the query
        return $stmt->execute();
    }

    // Method to find a role by username
    public function findByRoleName($role_name)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE role_name = :role_name";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(":role_name", $role_name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function Users($role_id = null)
    {
        $role_id_to_use = $role_id ?? $this->role_id;

        // Check if role_id is valid
        if (!isset($role_id_to_use)) {
            throw new Exception("Role ID is not set. Please provide a valid role ID.");
        }

        $query = "SELECT * FROM users WHERE role_id = :role_id";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(":role_id", $role_id_to_use, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete()
    {
        $query = "DELETE FROM {$this->table_name} WHERE role_id = :role_id";
        $stmt = $this->con->prepare($query);

        // Sanitize input
        $this->role_id = htmlspecialchars($this->role_id);

        // Bind parameters
        $stmt->bindParam(":role_id", $this->role_id, PDO::PARAM_INT);

        // Execute the query
        return $stmt->execute();
    }
}
