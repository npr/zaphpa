<?php

namespace Zaphpa;

abstract class BaseMiddleware {

    const ALL_METHODS = '*';

    public $scope = array();
    public static $context = array();
    public static $routes = array();


    /**
     *
     *  Restrict a Middleware hook to certain paths and HTTP methods.
     *
     *  No actual restriction takes place in this method.
     *  We simply place the $methods array into $this->scope, keyed by its $hook.
     *
     * @param $methods
     *  An array of HTTP methods that are allowed, or an '*' to match all methods.
     * @param $route
     *  Route to restrict the middleware to. Please note: this must be a route you added with addRoute(), not
     *  just any path.
     * @return $this
     */
    public function restrict($methods, $route) {
        $this->scope[$route] = $methods;
        return $this;
    }

    /**
     *  Determine whether the current route has any route restrictions for this BaseMiddleware.
     *
     *  BaseMiddleware must have self::$context['pattern'] and self::$context['http_method'] set.
     *  Furthermore $context['http_method'] can be an array (preflight uses that).
     *
     *  @return bool
     *    Whether the current route should run $hook.
     */
    public function shouldRun() {
        if (empty($this->scope)) return true; // no restrictions

        if (array_key_exists(self::$context['pattern'], $this->scope)) {
            $methods = $this->scope[self::$context['pattern']];

            if ($methods == self::ALL_METHODS) {
                return true;
            }

            if (!is_array($methods)) {
                return false;
            }

            if (!in_array(self::$context['http_method'], $methods)) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    /** Preprocess. This is where you'd add new routes **/
    public function preprocess(&$router) {}
    /** Preflight. This is where do things after routes are finalized but before processing starts **/
    public function preflight() {}
    /** Preroute. This is where you would alter request, or implement things like: security etc. **/
    public function preroute(&$req, &$res) {}
    /** This is your chance to override output. It can be called multiple times for each ->flush() invocation! **/
    public function prerender(&$buffer) {}

} // end Middleware