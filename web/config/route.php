<?php

require "../vendor/autoload.php";

use App\Route\Route;
use App\Exceptions\WrongParamException;
use Firebase\JWT\SignatureInvalidException;

$router = new Route();
// Show All
$router->map('GET', '/news', 'News#index');
// Show News by ID
$router->map('GET', '/news/:news', 'News#show');
// Create News
$router->map('POST', '/news', 'News#store', true);
// Update News
$router->map('POST', '/news/:news', 'News#update', true);
// Remove News
$router->map('DELETE', '/news/:news', 'News#destroy', true);

try {
    $router->match();
} catch (WrongParamException | SignatureInvalidException $e) {
    echo json_encode(['status' => 'fail', 'message' =>  $e->getMessage()]);
}