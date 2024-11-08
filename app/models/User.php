<?php

namespace App\Models;

use PDO;

class User extends Model
{
    public $user_id;
    public $username;
    public $email;
    public $password_hash;
    public $role_id;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = "users";
        $this->role_id = 3; // Default role is Employee
    }

    // Method to create a new user
    public function create()
    {
        $query = "INSERT INTO {$this->table_name} SET username = :username, email = :email, password_hash = :password_hash, role_id = :role_id";
        $stmt = $this->con->prepare($query);

        // Sanitize input
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password_hash = password_hash($this->password_hash, PASSWORD_DEFAULT);
        $this->role_id = htmlspecialchars($this->role_id);

        // Bind parameters
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password_hash", $this->password_hash);
        $stmt->bindParam(":role_id", $this->role_id, PDO::PARAM_INT);

        // Execute the query
        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE {$this->table_name} SET username = :username, email = :email, role_id = :role_id WHERE user_id = :user_id";
        $stmt = $this->con->prepare($query);

        // Sanitize input
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->user_id = htmlspecialchars($this->user_id);
        $this->role_id = htmlspecialchars($this->role_id);

        // Bind parameters
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);
        $stmt->bindParam(":role_id", $this->role_id, PDO::PARAM_INT);

        // Execute the query
        return $stmt->execute();
    }

    public function delete()
    {
        $query = "UPDATE {$this->table_name} SET deleted_at = CURRENT_TIMESTAMP WHERE user_id = :user_id";
        $stmt = $this->con->prepare($query);

        // Sanitize input
        $this->user_id = htmlspecialchars($this->user_id);

        // Bind parameters
        $stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);

        // Execute the query
        return $stmt->execute();
    }

    // Method to fetch all users
    public function readAll($with_password = false)
    {
        $suffix = $with_password ? "" : "_without_password";
        $where = $_SESSION['user']['role_id'] > 1 ? "WHERE deleted_at IS NULL" : "";
        $query = "SELECT * FROM {$this->table_name}$suffix u JOIN roles r ON r.role_id = u.role_id $where";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    // Method to find a user by ID
    public function findById($user_id, $with_password = false, $with_roles = true)
    {
        $suffix = $with_password ? "" : "_without_password";
        $query = "SELECT * FROM {$this->table_name}$suffix u ";
        if ($with_roles) $query .= "JOIN roles r ON r.role_id = u.role_id ";
        $query .= "WHERE u.user_id = :user_id";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    // Method to find a user by username
    public function findByEmail($email, $with_password = false, $with_roles = true)
    {
        $suffix = $with_password ? "" : "_without_password";
        $query = "SELECT * FROM {$this->table_name}$suffix u ";
        if ($with_roles) $query .= "JOIN roles r ON r.role_id = u.role_id ";
        $query .= "WHERE u.email = :email";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    // Method to find a user by username
    public function findByUsername($username, $with_password = false, $with_roles = true)
    {
        $suffix = $with_password ? "" : "_without_password";
        $query = "SELECT * FROM {$this->table_name}$suffix u ";
        if ($with_roles) $query .= "JOIN roles r ON r.role_id = u.role_id ";
        $query .= "WHERE u.username = :username";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
}
