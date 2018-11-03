<?php declare(strict_types=1);

namespace App;

/**
 * @author Ilya Zelenin <wyster@make.im>
 */
class Logger
{
    private $handle;

    public function __construct()
    {
        $this->handle = fopen('php://stdout', 'w');
    }

    /**
     * @param string $message
     */
    public function info(string $message)
    {
        fwrite($this->handle, $message . PHP_EOL);
    }
}