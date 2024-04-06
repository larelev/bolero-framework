<?php
namespace Funbox\Framework;

define('BASE_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
define('FUNBOX_ROOT', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
define('LIB_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('VAR_PATH', BASE_PATH . 'var' . DIRECTORY_SEPARATOR);
define('APP_PATH', BASE_PATH . 'app' . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', BASE_PATH . 'config' . DIRECTORY_SEPARATOR);
define('PLUGINS_PATH', LIB_PATH . 'Plugins' . DIRECTORY_SEPARATOR);
define('SERVICES_PATH', CONFIG_PATH . 'services.php');
define('MIDDLEWARES', CONFIG_PATH . 'middlewares.php');
define('DATABASE_URL', BASE_PATH . 'var' . DIRECTORY_SEPARATOR . 'migrations.sqlite');
define('MIGRATIONS_PATH', 'migrations' . DIRECTORY_SEPARATOR);
define('APP_VIEWS_PATH', APP_PATH . 'views' . DIRECTORY_SEPARATOR);

require_once BASE_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

Caching\Cache::prepare();
Logger\Cache::prepare();
Routing\Cache::prepare();
