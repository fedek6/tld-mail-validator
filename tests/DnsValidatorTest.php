<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator\Tests;

use Fedek6\TldMailValidator\DnsValidator;
use PHPUnit\Framework\TestCase;
use Fedek6\TldMailValidator\Tests\PhpUnitUtil;

final class DnsValidatorTest extends TestCase 
{
    /** @var \Fedek6\TldMailValidator\DnsValidator $dnsValidator  */
    private $dnsValidator;

    protected function setUp(): void
    {
        $this->dnsValidator = new DnsValidator();
    }

    protected function tearDown(): void
    {
        $this->dnsValidator = NULL;
    }

    public function testProperCheckDomain(): void
    {
        $result = $this->dnsValidator->checkDomainMx('onet.pl');
        $this->assertTrue($result);
    }

    public function testInvalidCheckDomain(): void
    {
        $result = $this->dnsValidator->checkDomainMx('onet989dha8sh8ds.pl');
        $this->assertFalse($result);
    }

    public function testProperGovDomain(): void
    {
        $result = $this->dnsValidator->checkDomainMx('state.gov');
        $this->assertTrue($result);
    }

    public function testInvalidGovDomain(): void
    {
        $result = $this->dnsValidator->checkDomainMx('stateofsa988.gov');
        $this->assertFalse($result);
    }

    public function testValidConnection(): void
    {
        $result = $this->dnsValidator->checkConnection(DnsValidator::TEST_DOMAINS[0]);
        $this->assertTrue($result);
    }

    public function testInvalidConnection(): void
    {
        $result = $this->dnsValidator->checkConnection('stateofsa988.gov');
        $this->assertFalse($result);
    }

    public function testDomainCacheProper(): void 
    {
        $this->dnsValidator->checkDomainMx('onet.pl');
        $result = PhpUnitUtil::callMethod($this->dnsValidator, 'checkIfInCache', ['onet.pl']);
        $this->assertTrue($result);
    }

    public function testDomainCacheInvalid(): void 
    {
        $this->dnsValidator->checkDomainMx('onetda322434234.pl');
        $result = PhpUnitUtil::callMethod($this->dnsValidator, 'checkIfInCache', ['onetda322434234.pl']);
        $this->assertFalse($result);
    }

    public function testDomainCacheNonExistent(): void 
    {
        $result = PhpUnitUtil::callMethod($this->dnsValidator, 'checkIfInCache', ['onet.pl']);
        $this->assertFalse($result);
    }
}
