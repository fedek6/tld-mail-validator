<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator;

use Fedek6\TldMailValidator\RemoteFileUpdater;
use Exception;

/**
 * Simple tld validator with automatic update.
 * 
 * @version 1.0.0
 * @author Konrad Fedorczyk <contact@realhe.ro>
 * @package Fedek6\TldMailValidator
 */
final class TldValidator
{
    /** @var string DATA_URL Url for downloading actual tlds. */
    const DATA_URL = 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt';

    /** @var integer MAX_FILE_AGE Maximum tlds file age (in days). */
    const MAX_FILE_AGE = 30;

    /** @var array $tlds */
    private $tlds = [];

    /**
     * Load tlds from text file.
     * 
     * @param string $path 
     * @return bool 
     * @throws Exception 
     */
    public function loadTlds(string $path): bool
    {
        $updater = new RemoteFileUpdater();
        $status = $updater->dispatchUpdate($path, self::DATA_URL, self::MAX_FILE_AGE);

        if ($status || file_exists($path)) {
            $tlds = file($path, FILE_IGNORE_NEW_LINES);
            $this->tlds = $this->cleanup($tlds);
        } else {
            throw new \Exception("File '{$path}' does not exist");
        }

        return true;
    }

    /**
     * Check domain if has proper tld.
     * 
     * @param string $domain 
     * @return bool 
     * @throws Exception 
     */
    public function checkDomain(string $domain): bool
    {
        $tld = $this->extractTld($domain);
        return $this->checkTld($tld);
    }

    /**
     * Check if tld exists.
     * 
     * @param string $tld 
     * @return bool 
     */
    public function checkTld(string $tld): bool
    {
        $tld = strtolower($tld);
        return in_array($tld, $this->tlds, true);
    }

    /**
     * Return array with imported tlds.
     * 
     * @return array 
     */
    public function getTlds(): array
    {
        return $this->tlds;
    }

    /**
     * Extract tld from domain name.
     * 
     * @param string $domain 
     * @return string 
     * @throws Exception
     */
    private function extractTld(string $domain): string
    {
        $domain = strtolower($domain);
        $parts = explode(".", $domain);

        if (count($parts) < 2) {
            throw new \Exception('Unable to extract tld from domain');
        }

        return end($parts);
    }

    /**
     * Remove first line and empty rows.
     * 
     * @param array $tlds 
     * @return array 
     * @throws Exception 
     */
    private function cleanup(array $tlds): array
    {
        if (count($tlds) > 2) {
            array_shift($tlds);
            $tlds = array_filter($tlds, fn ($value) => $value !== '');
            $tlds = array_map('strtolower', $tlds);
        } else {
            throw new \Exception("Tlds array is empty");
        }

        return $tlds;
    }
}
