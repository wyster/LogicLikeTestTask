<?php declare(strict_types=1);

namespace App;
/**
 * @author Ilya Zelenin <wyster@make.im>
 */
class Helper
{
    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return PHP_SAPI === 'cli' ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'];
    }
}