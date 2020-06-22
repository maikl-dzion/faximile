<?php

// die('index');

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', E_ALL);
 
//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: *');
//header('Access-Control-Allow-Headers: *');
//header('Content-Type: text/html; charset=utf-8');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
header('Content-Type: text/html; charset=utf-8');

header('X-Requested-With: XMLHttpRequest');
header("Accept:application/json");

define('LARAVEL_START', microtime(true));

apiListFormat();

// mail( '89062486209@vtext.com', '', 'Testing' );


// lg($_POST);
// require __DIR__.'/openssl_test.php';

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);


/////////////////////////
///
///
function lg($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die;
}

function apiListFormat() {

    $method     = $_SERVER['REQUEST_METHOD'];
    $requestUri = $_SERVER['REQUEST_URI'];

    $file = 'api-list.txt';
    $newUrl = $method .'--'. $requestUri;
    $aliUriArray  = file($file);

    foreach ($aliUriArray as $key => $value) {
        if(trim($value) == trim($newUrl))
            return true;
    }

    $current = file_get_contents($file);
    $current .= "$newUrl\n";
    file_put_contents($file, $current);
}
