<?php

class ZaphpaTestScopedMiddleware extends Zaphpa\BaseMiddleware {
  function preprocess(&$router) {
    $router->addRoute(array(
      'path'   => '/foo',
      'GET'    => array('TestController', 'getTestJsonResponse'),
      'POST'   => array('TestController', 'getTestJsonResponse'),
      'PUT'    => array('TestController', 'getTestJsonResponse'),
      'DELETE' => array('TestController', 'getTestJsonResponse'),
    ));

    $router->addRoute(array(
      'path'   => '/foo/bar',
      'GET'    => array('TestController', 'getTestJsonResponse'),
      'POST'   => array('TestController', 'getTestJsonResponse'),
      'PUT'    => array('TestController', 'getTestJsonResponse'),
      'DELETE' => array('TestController', 'getTestJsonResponse'),
    ));
  }
  
  function prerender(&$buffer) {
    $dc = json_decode($buffer[0]);
    $dc->scopeModification = "success";
    $buffer[0] = json_encode($dc);    
  }
}