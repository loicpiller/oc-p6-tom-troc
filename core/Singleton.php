<?php

namespace MVC\Core;

use Exception;

abstract class Singleton
{
    private static array $instances = [];
    protected function __construct() { }
    protected function __clone() { }

    /**
     * @throws Exception
     */
    final public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    /**
     * Returns the unique instance of the subclass.
     * If the instance does not exist, it is created.
     */
    public static function getInstance()
    {
        $subclass = static::class;

        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }

        return self::$instances[$subclass];
    }
}