<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator\Tests;

use Fedek6\TldMailValidator\RemoteFileUpdater;
use PHPUnit\Framework\TestCase;
use Fedek6\TldMailValidator\Tests\PhpUnitUtil;

final class RemoteFileUpdaterTest extends TestCase 
{
    const TLDS_FILE = __DIR__ . '/../data/tlds-alpha-by-domain.txt';
    
    /** @var string DATA_URL Url for downloading actual tlds. */
    const DATA_URL = 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt';

    public function testTimestampInDaysAgeCalc(): void 
    {
        $mock = $this->getMockBuilder('\Fedek6\TldMailValidator\RemoteFileUpdater')
            ->disableOriginalConstructor()
            ->getMock();

        $result = PhpUnitUtil::callMethod($mock, 'timestampAgeInDays', [1614621191]);

        var_dump($result);

        $this->assertTrue(is_int($result));
    }

    public function testUpdateProperUrl(): void
    {
        $mock = $this->getMockBuilder('\Fedek6\TldMailValidator\RemoteFileUpdater')
            ->disableOriginalConstructor()
            ->getMock();

        $result = PhpUnitUtil::callMethod($mock, 'update', [self::TLDS_FILE, self::DATA_URL]);
        $this->assertTrue($result);
    }

    public function testUpdateBogusUrl(): void
    {
        $this->expectException(\Exception::class);

        $mock = $this->getMockBuilder('\Fedek6\TldMailValidator\RemoteFileUpdater')
            ->disableOriginalConstructor()
            ->getMock();

        PhpUnitUtil::callMethod($mock, 'update', [self::TLDS_FILE, 'http://duipa2123.pl/666.txt']);
    } 

    public function testUpdateBadFile(): void
    {
        $this->expectException(\Exception::class);
        
        $mock = $this->getMockBuilder('\Fedek6\TldMailValidator\RemoteFileUpdater')
            ->disableOriginalConstructor()
            ->getMock();

        $result = PhpUnitUtil::callMethod($mock, 'update', ['this/file_does_not_exist.txt', self::DATA_URL]);
    }

    public function testUpdateGoodNonExistentFile(): void
    {
        $filePath = __DIR__ . '/../data/text.txt';
        
        $mock = $this->getMockBuilder('\Fedek6\TldMailValidator\RemoteFileUpdater')
            ->disableOriginalConstructor()
            ->getMock();

        $result = PhpUnitUtil::callMethod($mock, 'update', [$filePath, self::DATA_URL]);

        $this->assertTrue($result);

        unlink($filePath);
    }

    public function testShouldWeUpdate60days(): void 
    {
        $dt = new \DateTime();
        $dt->modify('-60 day');

        $mock = $this->getMockBuilder('\Fedek6\TldMailValidator\RemoteFileUpdater')
            ->disableOriginalConstructor()
            ->getMock();

        $result = PhpUnitUtil::callMethod($mock, 'shouldWeUpdate', [$dt->getTimestamp(), 30]);

        $this->assertTrue($result);
    }

    public function testShouldWeUpdate15days(): void 
    {
        $dt = new \DateTime();
        $dt->modify('-15 day');

        $mock = $this->getMockBuilder('\Fedek6\TldMailValidator\RemoteFileUpdater')
            ->disableOriginalConstructor()
            ->getMock();

        $result = PhpUnitUtil::callMethod($mock, 'shouldWeUpdate', [$dt->getTimestamp(), 30]);

        $this->assertFalse($result);
    }
}
