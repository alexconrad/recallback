<?php
declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use EasyMysql\Config;
use EasyMysql\EasyMysql;
use EasyMysql\Enum\MysqlDriverEnum;
use EasyMysql\Exceptions\DuplicateEntryException;
use EasyMysql\Exceptions\EasyMysqlQueryException;
use Recallback\Controllers\CallbackController;
use Recallback\Controllers\WebController;

error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../defines.php';

$action = $_GET['a'] ?? null;
try {
    $container = \Recallback\Common\Common::getDiContainer();
    $container->get(CallbackController::class)->index();
} catch (\EasyMysql\Exceptions\EasyMysqlQueryException $e) {
    http_response_code(500);
    echo '<pre>';
    echo $e->getMessage();
    echo "\n";
    echo $e->getQuery();
    echo "\n";
    echo print_r($e->getBinds(), true);
    echo '</pre>';
}
 catch (\Exception $e) {
    http_response_code(500);
    echo '<pre>';
    echo $e->getMessage();
    echo '</pre>';
}


