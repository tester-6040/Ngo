<?php

declare(strict_types=1);

use Controllers\ApiController;
use Core\Database;
use Core\Session;
use Models\Donation;
use Models\User;

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Donation.php';
require_once __DIR__ . '/../controllers/ApiController.php';

$config = require __DIR__ . '/../config/app.php';
Session::start();
$db = Database::connection($config['db']);

return new ApiController($config, new User($db), new Donation($db));
