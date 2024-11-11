<?php
require_once __DIR__ . '/../app/core/bootstrap.php';

use App\Controllers\RoleController;
use App\Controllers\UserController;
use App\Core\ControllerFactory;
use App\Middleware\AuthMiddleware;
use App\Core\Router;
use App\Helpers\GeneralHelper;
use App\Middleware\AdminMiddleware;

$helper = new GeneralHelper();
$router = new Router($helper);

$router->group('/', function (Router $router) {
    $router->get('', function () use ($router) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user'])) {
            $router->redirect("/dashboard");
        } else {
            require_once __DIR__ . '/../app/views/admin/login.php';
        }
    });

    $router->post('login', function () {
        $email = htmlspecialchars(strip_tags($_POST['email']));
        $password = htmlspecialchars(strip_tags($_POST['password']));

        $userController = ControllerFactory::create(UserController::class);
        $userController->login($email, $password);
    });
});

$router->group('/', function (Router $router) {
    $router->get('dashboard', [UserController::class, 'index']);
    $router->get('logout', [UserController::class, 'logout']);

    $router->group('users', function (Router $router) {
        $router->get('', [UserController::class, 'listUsers']);
        $router->post('', [UserController::class, 'create']);
        $router->put('/{id}', [UserController::class, 'update']);
        $router->delete('/{id}', [UserController::class, 'delete']);
    }, [AdminMiddleware::class]);

    $router->group('roles', function (Router $router) {
        $router->get('', [RoleController::class, 'listRoles']);
        $router->post('', [RoleController::class, 'create']);
        $router->put('/{id}', [RoleController::class, 'update']);
        $router->delete('/{id}', [RoleController::class, 'delete']);
    }, [AdminMiddleware::class]);
}, [AuthMiddleware::class]);

// Dispatch the request to the appropriate route
$router->dispatch();
