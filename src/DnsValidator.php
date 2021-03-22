<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator;

/**
 * Simple dns validator.
 * 
 * @version 1.0.0
 * @author Konrad Fedorczyk <contact@realhe.ro>
 * @package Fedek6\TldMailValidator
 */
abstract class DnsValidator
{   
    /**
     * Checks if any MX record exists.
     * 
     * @param string $domain 
     * @return bool 
     */
    public static function checkDomainMx(string $domain): bool
    {
        $result = dns_get_record($domain, DNS_MX);
        return !empty($result);
    }
}
