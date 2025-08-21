<?php

namespace App\Middleware;

use App\Helpers\GeneralHelper;

class AuthMiddleware
{
    public function handle()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            // Redirect to login page if not logged in
            session_destroy();
            header('Location: /');
            exit();
        } else if (getenv("UNDER_CONSTRUCTION") === "1") {
            header('Location: /under-construction'); // hotfix
            exit();
        }

        return true; // If user is logged in, continue with the request
    }
}
