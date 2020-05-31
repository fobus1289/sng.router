<?php

if (! function_exists('http_method_exist')) {
    /**
     * @return bool
     */
    function http_method_exist() : bool
    {
        foreach ($_REQUEST as $k => $v) {
            if (strtolower($k) == '_method') {
                return true;
            }
        }
        return false;
    }
}

if (! function_exists('request_method')) {
    /**
     * @return string
     */
    function request_method()
    {
        foreach ($_REQUEST as $k => $v) {
            if (strtolower($k) == '_method') {
                return strtolower($_REQUEST[$k]);
            }
        }
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}

if (! function_exists('str_or')) {
    /**
     * @param $name
     * @param $methodRouter
     * @param $args
     */
    function str_or_func($name,&$methodRouter,$args)
    {

        if ($args[1] instanceof Closure) {
            $methodRouter[$name][$args[0]] = [
               'isfunc'=>true,
               'action' => $args[1]
            ];
            return;
        }

        $methodRouter[$name][$args[0]] = [
            'isfunc'=>false,
            'action' => $args[1]
        ];

    }

}

if (! function_exists('request_uri')) {
    /**
     * @param $methods
     * @return Closure|string[]|null
     */
    function request_uri($methods)
    {
        $count = count(explode('/',$_SERVER['REQUEST_URI']));
        foreach ($methods as $k => $v) {
            if (strpos($k,$_SERVER['REQUEST_URI']) !== false &&
                $count === count(explode('/',$k))
            ) {
                return get_action_class($v);
            }
        }
        return null;
    }
}

if (! function_exists('get_action_class')) {

    function get_action_class($arr)
    {
        if ($arr['isfunc'] === true) {
            return $arr['action'];
        }

        return explode('@',$arr['action']);
    }

}

if (! function_exists('request')) {
    function request()
    {
        return $_SERVER['REQUEST_URI'];
    }
}
