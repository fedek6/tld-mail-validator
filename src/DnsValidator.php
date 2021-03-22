<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator;

use Exception;

/**
 * Simple dns validator.
 * 
 * @version 1.0.0
 * @author Konrad Fedorczyk <contact@realhe.ro>
 * @package Fedek6\TldMailValidator
 */
class DnsValidator
{   
    // A list of longest working domains for testing purposes.
    const TEST_DOMAINS = [
        'symbolics.com',
        'intel.com',
        'ibm.com',
        'northrop.com',
        'nec.com',
        'berkeley.edu',
        'mit.edu',
    ];

    /** @var array $cache */
    private $cache = [];

    /**
     * Constructor.
     * 
     * @return void 
     * @throws Exception 
     */
    public function __construct()
    {
        if ($this->checkConnection($this->getRandomDomain()) === false)
            throw new Exception('There is no internet connection available');
    }

    /**
     * Checks if any MX record exists.
     * 
     * @param string $domain 
     * @return bool 
     */
    public function checkDomainMx(string $domain): bool
    {
        if ($this->checkIfInCache($domain)) {
            return true;
        }

        $result = dns_get_record($domain, DNS_MX);
        $status = !empty($result);

        if($status === true) {
            $this->addToCache($domain);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if domain is in cache.
     * 
     * @param string $domain 
     * @return bool 
     */
    private function checkIfInCache(string $domain): bool
    {
        return in_array(strtolower($domain), $this->cache);
    }

    /**
     * Add domain to cache.
     * 
     * @param string $domain 
     * @return void 
     */
    private function addToCache(string $domain)
    {
        $this->cache[] = strtolower($domain);
    }

    /**
     * Check if there is an internet connection.
     * 
     * @param string $host
     * @return bool 
     */
    public function checkConnection(string $host): bool
    {
        $status = false;
        $connected = @fsockopen($host, 80);

        if ($connected){
            $status = true;
            fclose($connected);
        }

        return $status; 
    }

    /**
     * Get random domain.
     * 
     * @return string 
     */
    public function getRandomDomain(): string 
    {
        return self::TEST_DOMAINS[array_rand(self::TEST_DOMAINS)];
    }
}
