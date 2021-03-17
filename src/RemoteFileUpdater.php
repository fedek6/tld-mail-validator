<?php

namespace Fedek6\TldMailValidator;

use Exception;

/**
 * Simple file updater
 * for remote file contents.
 * 
 * @version 1.0.0
 * @author Konrad Fedorczyk <contact@realhe.ro>
 * @package Fedek6\TldMailValidator
 */
class RemoteFileUpdater
{
    /**
     * Dispatch update.
     * 
     * @param string $filePath 
     * @param string $url 
     * @param int $days 
     * @return void 
     * @throws Exception 
     */
    public function dispatchUpdate(string $filePath, string $url, int $days): bool
    {   
        $fileTimestamp = @filemtime($filePath);
        
        if (
            !$fileTimestamp 
            || $this->shouldWeUpdate($fileTimestamp, $days)
        ) {
           return  $this->update($filePath, $url);
        } else {
            return false;
        }
    }

    /**
     * Should we update file?
     * 
     * @param int $timestamp 
     * @param int $days 
     * @return bool 
     */
    private function shouldWeUpdate(int $timestamp, int $days): bool
    {
        if ($this->timestampAgeInDays($timestamp) > $days) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Calculate difference from now.
     * 
     * @param int $timestamp
     * @return int 
     */
    private function timestampAgeInDays(int $timestamp): int 
    {
        return (time() - $timestamp) / 3600 / 24;
    }

    /**
     * Update file using url.
     * 
     * @param string $filePath 
     * @param string $url 
     * @return bool 
     * @throws Exception 
     */
    private function update(string $filePath, string $url): bool
    {
        if (function_exists('curl_version') === false) {
            throw new \Exception('Curl is not installed');
        }
    
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_TIMEOUT, 50);
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($conn, CURLOPT_FAILONERROR, true);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($conn);

        // Check if curl error
        if (!curl_errno($conn)) {
            if (!empty($data)) {
                $fp = @fopen($filePath, 'w');

                if (!$fp) {
                    throw new \Exception("File does not exists or it's not writeable");
                }
                fwrite($fp, $data);

                fclose($fp);
            } else {
                throw new \Exception('Empty curl response');
            }

            return true;
        } else {
            throw new \Exception('curl error: ' . curl_error($conn));
        }
    }
}
