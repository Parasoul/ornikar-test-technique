<?php

namespace App\Helper;

trait SingletonTrait
{
    /**
     * @var $this
     */
    protected static $instance = null;

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function clearInstance(): void
    {
        self::$instance = null;
    }
}
