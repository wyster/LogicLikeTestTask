<?php declare(strict_types=1);

namespace App;

use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Cache\Storage\StorageInterface;

/**
 * @author Ilya Zelenin <wyster@make.im>
 */
class UserStorage
{
    /**
     * @var Filesystem
     */
    protected $storage;
    /**
     * @var UserEntity
     */
    protected $userData;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
        $this->fetch();
    }

    /**
     * @return StorageInterface
     */
    protected function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    public function update()
    {
        $this->getStorage()->setItem(Helper::create()->getIp(), json_encode($this->getEntity()));
    }

    public function getEntity(): UserEntity
    {
        $this->fetch();
        return $this->userData;
    }

    protected function fetch()
    {
        if ($this->userData !== null) {
            return;
        }
        $this->userData = new UserEntity();

        $item = $this->getStorage()->getItem(Helper::create()->getIp());
        if ($item !== null) {
            $rows = json_decode($item, true);
            $this->userData = new UserEntity($rows);
        }
    }
}