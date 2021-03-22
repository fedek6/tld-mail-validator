<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator;

use Exception;
use Fedek6\TldMailValidator\TldValidator;
use Fedek6\TldMailValidator\DnsValidator;

/**
 * Tld mail validator.
 * 
 * @version 1.0.1
 * @author Konrad Fedorczyk <contact@realhe.ro>
 * @package Fedek6\TldMailValidator
 */
class TldMailValidator
{   
    // Which tests should be run
    const TEST_TLD = 1;
    const TEST_ALL = 2;

    /** @var \Fedek6\TldMailValidator\TldValidator $tldValidator  */
    private $tldValidator;

    /** @var \Fedek6\TldMailValidator\DnsValidator|null $dnsValidator  */
    private $dnsValidator;

    /** @var int $operationMode */
    private $operationMode;

    /**
     * Constructor.
     * 
     * @return void 
     * @throws Exception 
     */
    public function __construct(string $storageFilePath, int $testFlag = self::TEST_TLD)
    {
        $this->tldValidator = new TldValidator();
        $this->tldValidator->loadTlds($storageFilePath);
        $this->operationMode = $testFlag;

        // If nedded create instances of other validators.
        if ($this->operationMode === self::TEST_ALL) {
            $this->dnsValidator = new DnsValidator();
        }
    }

    /**
     * Validate email.
     * 
     * @param string $email 
     * @return bool 
     * @throws Exception 
     */
    public function validate(string $email): bool
    {
        // Let's check if this email is valid.
        $email = filter_var($email, FILTER_VALIDATE_EMAIL, ['flags' => FILTER_NULL_ON_FAILURE]);

        if (is_null($email)) {
            return false;
        }

        $emailParts = explode('@', $email);
        $domain = end($emailParts);

        if ($this->tldValidator->checkDomain($domain) === false) {
            return false;
        }

        // Other tests
        if (
            !is_null($this->dnsValidator) 
            && $this->dnsValidator->checkDomainMx($domain) === false
        ) {
            return false;
        }

        return true;
    }
}
