<?php declare(strict_types=1);

namespace App;

use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Cache\Storage\StorageInterface;

/**
 * @author Ilya Zelenin <wyster@make.im>
 */
class UserEntity implements \JsonSerializable
{
    /**
     * @var int
     */
    public $repeatedRequests = 0;
    /**
     * @var null|string
     */
    public $blockedAt;
    /**
     * @var string
     */
    public $lastAccess;

    /**
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->lastAccess = date('Y-m-d H:i:s');
        if ($items) {
            foreach ($items as $k => $v) {
                if (property_exists($this, $k)) {
                    $this->{$k} = $v;
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blockedAt !== null;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}