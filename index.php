<?php

declare(strict_types=1);

use Controllers\ApiController;
use Controllers\AuthController;
use Controllers\DashboardController;
use Controllers\DonationController;
use Core\Csrf;
use Core\Database;
use Core\Session;
use Models\Donation;
use Models\User;

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/BaseModel.php';
require_once __DIR__ . '/core/Session.php';
require_once __DIR__ . '/core/Csrf.php';
require_once __DIR__ . '/core/Mailer.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Donation.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/DonationController.php';
require_once __DIR__ . '/controllers/ApiController.php';

$config = require __DIR__ . '/config/app.php';
Session::start();

$base = rtrim((string) ($config['base_url'] ?? ''), '/');
if ($base === '') {
    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $detectedBase = str_replace('\\', '/', dirname($scriptName));
    $base = ($detectedBase === '/' || $detectedBase === '.' || $detectedBase === '\\') ? '' : '/' . trim($detectedBase, '/');
}
$config['base_url'] = $base;

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if ($base !== '' && str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
    $uri = $uri === '' ? '/' : $uri;
}

if ($uri === '/index.php') {
    header('Location: ' . ($base === '' ? '/' : $base . '/'), true, 301);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$factory = static function () use ($config): array {
    $db = Database::connection($config['db']);
    $userModel = new User($db);
    $donationModel = new Donation($db);

    return [$userModel, $donationModel];
};

switch ([$method, $uri]) {
    case ['GET', '/']:
        $csrf = Csrf::token();
        $title = 'Home';
        require __DIR__ . '/views/home.php';
        break;
    case ['GET', '/login']:
        $csrf = Csrf::token();
        $title = 'Login';
        require __DIR__ . '/views/auth/login.php';
        break;
    case ['POST', '/login']:
        [$userModel] = $factory();
        (new AuthController($config, $userModel))->login();
        break;
    case ['GET', '/register']:
        $csrf = Csrf::token();
        $title = 'Register';
        require __DIR__ . '/views/auth/register.php';
        break;
    case ['POST', '/register']:
        [$userModel] = $factory();
        (new AuthController($config, $userModel))->register();
        break;
    case ['POST', '/logout']:
        [$userModel] = $factory();
        (new AuthController($config, $userModel))->logout();
        break;
    case ['GET', '/dashboard']:
        [$userModel, $donationModel] = $factory();
        (new DashboardController($config, $donationModel, $userModel))->home();
        break;
    case ['POST', '/donations/submit']:
        [$userModel, $donationModel] = $factory();
        (new DonationController($config, $donationModel, $userModel))->submit();
        break;
    case ['POST', '/donations/admin-assign']:
        [$userModel, $donationModel] = $factory();
        (new DonationController($config, $donationModel, $userModel))->adminAssignApprove();
        break;
    case ['POST', '/donations/orphanage-decision']:
        [$userModel, $donationModel] = $factory();
        (new DonationController($config, $donationModel, $userModel))->orphanageDecision();
        break;
    default:
        if (str_starts_with($uri, '/api/users')) {
            [$userModel, $donationModel] = $factory();
            (new ApiController($config, $userModel, $donationModel))->users($method);
        }
        if (str_starts_with($uri, '/api/donations')) {
            [$userModel, $donationModel] = $factory();
            (new ApiController($config, $userModel, $donationModel))->donations($method);
        }
        if (str_starts_with($uri, '/api/orphanage/actions')) {
            [$userModel, $donationModel] = $factory();
            (new ApiController($config, $userModel, $donationModel))->orphanageAction($method);
        }

        http_response_code(404);
        echo 'Not Found';
}
