<?php

require_once '../vendor/autoload.php';

require_once(__DIR__ . '/TestController.class.php');
require_once(__DIR__ . '/ZaphpaTestMiddleware.class.php');
require_once(__DIR__ . '/ZaphpaTestScopedMiddleware.class.php');

$router = new \Zaphpa\Router();

$router->attach('\ZaphpaTestMiddleware');
$router->attach('\Zaphpa\Middleware\AutoDocumentator', '/testapidocs');

$router->attach('\Zaphpa\Middleware\MethodOverride');

$router
  ->attach('\Zaphpa\Middleware\CORS', '*')
  ->restrict('preroute', '*', '/users');

$router
  ->attach('\ZaphpaTestScopedMiddleware')
  ->restrict('prerender', '*', '/foo')
  ->restrict('prerender', array('PUT'), '/foo/bar');

$router->addRoute(array(
  'path' => '/users',
  'GET'  => array('\TestController', 'getTestJsonResponse'),
  'PUT'  => array('\TestController', 'getTestJsonResponse'),
  'POST' => array('\TestController', 'getTestJsonResponse'),
));

$router->addRoute(array(
  'path'     => '/users/{id}',
  'handlers' => array(
    'id'       => \Zaphpa\Constants::PATTERN_DIGIT,
  ),
  'GET'      => array('\TestController', 'getTestJsonResponse'),
  'POST'     => array('\TestController', 'getTestJsonResponse'),
  'PATCH'    => array('\TestController', 'getTestJsonResponse'),
));

$router->addRoute(array(
  'path'     => '/v2/times/{dt}/episodes',
  'GET'      => array('\TestController', 'getTestJsonResponse'),
));

$router->addRoute(array(
  'path'     => '/tags/{id}',
  'handlers' => array(
    'id'       => \Zaphpa\Constants::PATTERN_ALPHA,
  ),
  'GET'      => array('\TestController', 'getTestJsonResponse'),
));

$router->addRoute(array(
  'path'     => '/users/{user_id}/books/{book_id}',
  'handlers' => array(
    'user_id'  => \Zaphpa\Constants::PATTERN_NUM,
    'book_id'  => \Zaphpa\Constants::PATTERN_ALPHA,
  ),
  'GET'      => array('\TestController', 'getTestJsonResponse'),
));

$router->addRoute(array(
  'path'     => '/query_var_test',
  'GET'      => array('\TestController', 'getQueryVarTestJsonResponse'),
));


try {
  $router->route();
} catch (\Zaphpa\Exceptions\InvalidPathException $ex) {
  header('Content-Type: application/json;', true, 404);
  die(json_encode(array('error' => 'not found')));
}
