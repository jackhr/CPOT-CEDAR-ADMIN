<?php

namespace App\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\Role;

class RoleController extends Controller
{
    private $roleModel;
    private $helper;

    public function __construct(GeneralHelper $helper)
    {
        $this->roleModel = new Role();
        $this->helper = $helper;
    }

    public function listRoles()
    {
        $this->view("admin/roles/list.php", [
            "user" => $_SESSION['user'],
            "roles" => $this->roleModel->readAll(),
            "title" => "Roles"
        ]);
    }

    // Method to handle creating a user
    public function create()
    {
        $role_name = $_POST['role_name'];

        $new_role = [];
        $status = 200;
        $message = "";
        if ($this->roleModel->findByRoleName($role_name)) {
            $status = 409;
            $message = "There is already a role with that username.";
        }

        if ($status !== 200) {
            $this->helper->respondToClient($new_role, $status, $message);
        }

        $this->roleModel->role_name = $role_name;

        if ($this->roleModel->create()) {
            $message = "Role created successfully.";
            $new_role = $this->roleModel->findByRoleName($role_name);
        } else {
            $status = 409;
            $message = "Error creating role.";
        }

        $this->helper->respondToClient($new_role, $status, $message);
    }

    public function update($role_id)
    {
        $inputData = file_get_contents('php://input');
        $data = json_decode($inputData, true);

        $role_name = $data['role_name'];

        $role = $this->roleModel->findById($role_id);
        $role_with_same_name = $this->roleModel->findByRoleName($role_name);

        $status = 200;
        $message = "";

        if (!$role) {
            $status = 409;
            $message = "There is no role with this id.";
        } else if (
            $role_with_same_name !== false &&
            $role_with_same_name['role_name'] === $role_name
        ) {
            $status = 409;
            $message = "There is already a role with that name.";
        }

        if ($status !== 200) {
            $this->helper->respondToClient($role, $status, $message);
        }

        $this->roleModel->role_name = $role_name;
        $this->roleModel->role_id = $role_id;

        if ($this->roleModel->update()) {
            $message = "Role updated successfully.";
            $updated_role = $this->roleModel->findById($role_id);
        } else {
            $status = 409;
            $message = "Error updating role.";
        }

        $this->helper->respondToClient($updated_role, $status, $message);
    }

    public function delete($role_id)
    {
        $logged_in_user = $_SESSION['user'];

        $role_to_delete = $this->roleModel->findById($role_id);
        $users_with_this_role = $this->roleModel->Users($role_id);
        $status = 200;
        $message = "";

        if ($logged_in_user['role_id'] === (int)$role_id) {
            $status = 409;
            $message = "You cannot delete your own role.";
        } else if (isset($users_with_this_role) && count($users_with_this_role) > 0) {
            $status = 409;
            $message = "You cannot delete role that has associated users.";
        }

        if ($status !== 200) {
            $this->helper->respondToClient(null, $status, $message);
        }

        $this->roleModel->role_id = $role_id;

        if ($this->roleModel->delete()) {
            $message = "Role deleted successfully.";
            $role_to_delete = $this->roleModel->findById($role_id);
        } else {
            $status = 409;
            $message = "Error updating role.";
        }

        $this->helper->respondToClient($role_to_delete, $status, $message);
    }
}
