<?php

use Symfony\Component\HttpFoundation\Request;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__);
define('ENV', 'dev'); // dev|prod
define('CACHING', true);

ini_set('display_errors', true);

include_once 'vendor/autoload.php';

/*
 * STEAM'S KEY
 */
if (!defined('STEAM_KEY')) {
    die("STEAM_KEY is not defined");
}

$request = Request::createFromGlobals();

define('APP_DIR', $request->getBasePath());
define('BASE_URL', $request->getUri());

switch($request->query->get('a')) {
    case 'search':
        print \App\Searcher::searchAction();
        break;
    default:
        print \App\Searcher::indexAction();
}