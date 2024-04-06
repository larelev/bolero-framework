<?php
namespace Bolero\Framework;

$document_root = isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR : '';

define('IS_WEB_APP', $document_root !== '');
define('IS_CLI_APP', !IS_WEB_APP);

if (IS_WEB_APP) {
    define('DOCUMENT_ROOT', $document_root);
    define('BASE_PATH', dirname(DOCUMENT_ROOT) . DIRECTORY_SEPARATOR);
} else {
    $site_root = (getcwd() ? getcwd() : __DIR__) . DIRECTORY_SEPARATOR;
    [$app_path] = get_included_files();
    $script_name = pathinfo($app_path, PATHINFO_BASENAME);
    $script_dir = pathinfo($app_path, PATHINFO_DIRNAME);
    $appName = pathinfo($script_name)['filename'];

    define('BASE_PATH', $script_dir . DIRECTORY_SEPARATOR);

}
define('FUNBOX_ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
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
