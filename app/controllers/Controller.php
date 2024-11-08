<?php

namespace App\Controllers;

class Controller
{
    public function view(string $path, array $data = [])
    {
        extract($data);
        require_once __DIR__ . "/../views/" . $path;
    }
}
