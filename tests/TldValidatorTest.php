<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator\Tests;

use Fedek6\TldMailValidator\TldValidator;
use PHPUnit\Framework\TestCase;
use Fedek6\TldMailValidator\Tests\PhpUnitUtil;

use function PHPUnit\Framework\assertCount;

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

    public function testWrongFileLoadTlds(): void
    {
        $this->expectException(\Exception::class);
        $this->tldValidator->loadTlds('test');
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
}
