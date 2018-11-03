<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * @author Ilya Zelenin <wyster@make.im>
 */
class ProtectorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testBlock()
    {
        $config = [
            'debug' => false,
            'requestsLimit' => 5,
            'requestsLimitForInterval' => 'PT5M',
            'blockForInterval' => 'PT10M'
        ];
        $userEntity = new \App\UserEntity();
        $userEntity->lastAccess = date('Y-m-d H:i:s');
        $userEntity->repeatedRequests = 4;
        $protector = $this->buildProtector($userEntity, $config);
        //$this->assertFalse($protector->isAllowAccess());
        //$this->assertInstanceOf(DateTime::class, $protector->banExpireAt());

        $userEntity = new \App\UserEntity();
        $userEntity->repeatedRequests = 3;
        $protector = $this->buildProtector($userEntity, $config);
        $this->assertTrue($protector->isAllowAccess());
        $this->assertSame(4, $userEntity->repeatedRequests);
    }

    public function testIsAllowAccess()
    {
        $config = [
            'debug' => false,
            'requestsLimit' => 5,
            'requestsLimitForInterval' => 'PT5M',
            'blockForInterval' => 'PT10M'
        ];
        $userEntity = new \App\UserEntity();
        $protector = $this->buildProtector($userEntity, $config);
        $this->assertTrue($protector->isAllowAccess());

        $userEntity = new \App\UserEntity();
        $userEntity->blockedAt = date('Y-m-d H:i:s', time() - 11 * 60);
        $protector = $this->buildProtector($userEntity, $config);
        $this->assertTrue($protector->isAllowAccess());

        $userEntity = new \App\UserEntity();
        $userEntity->blockedAt = date('Y-m-d H:i:s', time() - 5 * 60);
        $protector = $this->buildProtector($userEntity, $config);
        $this->assertFalse($protector->isAllowAccess());
        $this->assertInstanceOf(DateTime::class, $protector->banExpireAt());
    }

    /**
     * @expectedException Exception
     */
    public function testBanExpireAt()
    {
        $userEntity = new \App\UserEntity();
        $userData = $this->getMockBuilder(\App\UserStorage::class)->disableOriginalConstructor()->getMock();
        $userData->method('getEntity')->willReturn($userEntity);
        $protector = $this->buildProtector($userEntity);
        $protector->banExpireAt();
    }

    /**
     * @param \App\UserEntity $userEntity
     * @param array $config
     * @return \App\Protector
     */
    protected function buildProtector(\App\UserEntity $userEntity, array $config = []): \App\Protector
    {
        $logger = new \App\Logger();
        $userData = $this->getMockBuilder(\App\UserStorage::class)->disableOriginalConstructor()->getMock();
        $userData->method('getEntity')->willReturn($userEntity);
        return new \App\Protector($userData, $config, $logger);
    }
}
