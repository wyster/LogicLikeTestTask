<?php declare(strict_types=1);

namespace App;

use Closure;
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Cache\Storage\StorageInterface;

/**
 * @author Ilya Zelenin <wyster@make.im>
 */
class Protector
{
    /**
     * @var UserStorage
     */
    protected $userData;
    /**
     * @var array
     */
    protected $config;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param UserStorage $userData
     * @param array $config
     * @param Logger $logger
     */
    public function __construct(UserStorage $userData, array $config, Logger $logger)
    {
        $this->userData = $userData;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @return UserStorage
     */
    protected function getUserData(): UserStorage
    {
        return $this->userData;
    }

    /**
     * @return array
     */
    protected function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return bool
     */
    public function isAllowAccess(): bool
    {
        if ($this->getConfig()['debug']) {
            $this->logger->info('Request from ip: ' . Helper::create()->getIp());
        }
        $userData = $this->getUserData()->getEntity();
        if ($userData->isBlocked()) {
            if ($this->banExpireAt()->getTimestamp() > time()) {
                $this->updateUserData();
                return false;
            }
            $userData->blockedAt = null;
            $userData->repeatedRequests = 0;
            $this->updateUserData();
            return true;
        }

        $lastAccess = (new \DateTime($userData->lastAccess));
        $lastAccess->add(new \DateInterval($this->getConfig()['requestsLimitForInterval']));
        if ($lastAccess->getTimestamp() > time()) {
            $userData->repeatedRequests++;
        } else {
            $userData->repeatedRequests = 0;
        }

        if ($userData->repeatedRequests >= $this->getConfig()['requestsLimit']) {
            $userData->blockedAt = date('Y-m-d H:i:s');
            $this->updateUserData();
            return false;
        }

        $this->updateUserData();

        return true;
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function banExpireAt(): \DateTime
    {
        if ($this->getUserData()->getEntity()->blockedAt === null) {
            throw new \Exception('User is not banned');
        }
        $blockExpireAt = new \DateTime($this->getUserData()->getEntity()->blockedAt);
        $blockExpireAt->add(new \DateInterval($this->getConfig()['blockForInterval']));

        return $blockExpireAt;
    }

    protected function updateUserData()
    {
        $this->getUserData()->getEntity()->lastAccess = date('Y-m-d H:i:s');
        if ($this->getConfig()['debug']) {
            $this->logger->info(json_encode($this->getUserData()->getEntity()));
        }
        $this->getUserData()->update();
    }
}