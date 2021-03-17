<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator\Tests;

use Fedek6\TldMailValidator\TldValidator;
use PHPUnit\Framework\TestCase;
use Fedek6\TldMailValidator\Tests\PhpUnitUtil;

final class TldValidatorTest extends TestCase
{
    private $testFilePath = __DIR__ . '/../data/tlds-alpha-by-domain.txt';

    /** @var Fedek6\TldMailValidator\TldValidator $tldValidator  */
    private $tldValidator;

    protected function setUp(): void
    {
        $this->tldValidator = new TldValidator;
    }

    protected function tearDown(): void
    {
        $this->tldValidator = NULL;
    }

    public function testNonExistentFileLoadTlds(): void
    {
        $filePath = __DIR__ . '/../data/test';

        $this->assertEquals(true, $this->tldValidator->loadTlds($filePath));
        unlink($filePath);
    }

    public function testProperFileLoadTlds(): void
    {
        $this->assertEquals(true, $this->tldValidator->loadTlds($this->testFilePath));
    }

    public function testCleanup(): void
    {
        $result = PhpUnitUtil::callMethod($this->tldValidator, 'cleanup', [['row 1', 'row 2', '']]);
        $this->assertCount(1, $result);
    }

    public function testCheckIfHasDomains(): void
    {
        $this->tldValidator->loadTlds($this->testFilePath);
        $result = $this->tldValidator->getTlds();

        $this->assertTrue(in_array('press', $result, true));
        $this->assertTrue(in_array('pl', $result, true));
        $this->assertTrue(in_array('gb', $result, true));
        $this->assertTrue(in_array('ru', $result, true));
    }

    public function testIfHasEmptyValues(): void 
    {
        $this->tldValidator->loadTlds($this->testFilePath);
        $result = $this->tldValidator->getTlds();

        $this->assertFalse(in_array('', $result, true));
    }

    public function testRetunTldsWithoutLoad(): void 
    {
        $this->assertTrue(empty($this->tldValidator->getTlds()));
    }

    public function testProperCheckTld(): void
    {
        $this->tldValidator->loadTlds($this->testFilePath);
        $this->assertTrue($this->tldValidator->checkTld('press'));
    }

    public function testMixedCaseCheckTld(): void
    {
        $this->tldValidator->loadTlds($this->testFilePath);
        $this->assertTrue($this->tldValidator->checkTld('PreSS'));
    }

    public function testInvalidCheckTld(): void
    {
        $this->tldValidator->loadTlds($this->testFilePath);
        $this->assertFalse($this->tldValidator->checkTld('gutami'));
    }

    public function testExtractTldFromProperDomain(): void
    {
        $this->tldValidator->loadTlds($this->testFilePath);
        $result = PhpUnitUtil::callMethod($this->tldValidator, 'extractTld', ['oko.press']);
        $this->assertEquals('press', $result);
    }

    public function testExtractTldFromComplexDomain(): void
    {
        $this->tldValidator->loadTlds($this->testFilePath);
        $result = PhpUnitUtil::callMethod($this->tldValidator, 'extractTld', ['wp.com.pl']);
        $this->assertEquals('pl', $result);
    }

    public function testExtractTldFromBadDomain(): void
    {
        $this->expectException(\Exception::class);
        $this->tldValidator->loadTlds($this->testFilePath);
        $result = PhpUnitUtil::callMethod($this->tldValidator, 'extractTld', ['press']);
    }
}
