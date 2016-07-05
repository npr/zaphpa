<?php

namespace Zaphpa\Middleware;

class MethodOverride extends \Zaphpa\BaseMiddleware {
  
  function preprocess(&$router) {
    if (!empty($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) &&
        \Zaphpa\Router::getRequestMethod() == "POST") {
      $_SERVER['REQUEST_METHOD'] = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
    }
  }
}