<?php

class ZaphpaTestMiddleware extends Zaphpa\BaseMiddleware {
  function preprocess(&$router) {
    $router->addRoute(array(
          'path'     => '/middlewaretest/{mid}',
          'GET'      => array('TestController', 'getTestJsonResponse'),
    ));
  }
  
  function preroute(&$req, &$res) {
    // you get to customize behavior depending on the pattern being matched in the current request
    if (self::$context['pattern'] == '/middlewaretest/{mid}') {
      $req->params['bogus'] = "foo";
    }
  }
  
  function prerender(&$buffer) {
      if (self::$context['pattern'] == '/middlewaretest/{mid}') {
        $dc = json_decode($buffer[0]);
        $dc->version = "2.0";
        $buffer[0] = json_encode($dc);
      }
  }  
}