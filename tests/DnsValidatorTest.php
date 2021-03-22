<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator\Tests;

use Fedek6\TldMailValidator\DnsValidator;
use PHPUnit\Framework\TestCase;
use Fedek6\TldMailValidator\Tests\PhpUnitUtil;

final class DnsValidatorTest extends TestCase 
{
    public function testProperCheckDomain(): void
    {
        $result = DnsValidator::checkDomainMx('onet.pl');
        $this->assertTrue($result);
    }

    public function testInvalidCheckDomain(): void
    {
        $result = DnsValidator::checkDomainMx('onet989dha8sh8ds.pl');
        $this->assertFalse($result);
    }

    public function testProperGovDomain(): void
    {
        $result = DnsValidator::checkDomainMx('state.gov');
        $this->assertTrue($result);
    }

    public function testInvalidGovDomain(): void
    {
        $result = DnsValidator::checkDomainMx('stateofsa988.gov');
        $this->assertFalse($result);
    }
}
