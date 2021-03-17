<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator\Tests;

use Fedek6\TldMailValidator\TldMailValidator;
use PHPUnit\Framework\TestCase;

final class TldMailValidatorTest extends TestCase
{
    const TLDS_FILE = __DIR__ . '/../data/tlds-alpha-by-domain.txt';

    /** @var Fedek6\TldMailValidator\TldMailValidator $tldValidator  */
    private $tldMailValidator;

    protected function setUp(): void
    {
        $this->tldMailValidator = new TldMailValidator(self::TLDS_FILE);
    }

    protected function tearDown(): void
    {
        $this->tldMailValidator = NULL;
    }

    public function testValidateProperEmail(): void 
    {
        $this->assertTrue($this->tldMailValidator->validate('jan@wp.pl'));
    }

    public function testValidateInvalidTldEmail(): void 
    {
        $this->assertFalse($this->tldMailValidator->validate('jan@wp.plfdsfsdfdsf'));
    }

    public function testValidateInvalidEmail(): void 
    {
        $this->assertFalse($this->tldMailValidator->validate('thisisnotanemail'));
    }

    public function testValidateMixedCaseProperEmail(): void 
    {
        $this->assertTrue($this->tldMailValidator->validate('Jan@wP.pL'));
    }

    public function testValidateDottedProperEmail(): void 
    {
        $this->assertTrue($this->tldMailValidator->validate('jan.wlodarski@onet.pl'));
    }

    public function testValidateComplexProperEmail(): void 
    {
        $this->assertTrue($this->tldMailValidator->validate('jan.wlodarski@onet.com.pl'));
        $this->assertTrue($this->tldMailValidator->validate('jan.wlodarski@mnw.art.pl'));
        $this->assertTrue($this->tldMailValidator->validate('wlodekciechan@serwer.mil.gov.pl'));
    }
}
