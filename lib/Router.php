<?php

namespace Sng\Router;

/**
 * Class Router
 * @package App\core
 * @method void get(string $uri, \Closure|string $action)
 * @method void post(string $uri, \Closure|string $action)
 * @method void put(string $uri, \Closure|string $action)
 * @method void delete(string $uri, \Closure|string $action)
 */
final class Router
{

    private function __construct()
    {
    }

    private static $methods = [];

    private static $isOkay = [
        'post',
        'get',
        'put',
        'delete',
    ];

    /**
     * @throws \Exception
     */
    public static function init(): void
    {
        static::parserUri();
    }

    /**
     * @param $name
     * @param $arguments
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, self::$isOkay) && count($arguments) > 0) {
            str_or_func($name, self::$methods, $arguments);
        } else {
            throw new \Exception("method");
        }
    }

    /**
     * @throws \Exception
     */
    private static function parserUri()
    {
        $re = request_uri(self::$methods[request_method()]);

        if (!is_null($re)) {
            static::returnThen(static::funcOrClass($re));
        } else {
            throw new \Exception("method");
        }

    }

    private static function funcOrClass($fc)
    {

        if ($fc instanceof \Closure) {
            return $fc();
        }

        $class = "App\controller\\" . ucfirst($fc[0]);
        $method = $fc[1];
        return (new $class())->$method();
    }

    private static function returnThen($method)
    {
        if (!is_null($method)) {
            echo $method;
        }
    }

}