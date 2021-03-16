<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator;

use Exception;

final class TldValidator
{
    /** @var array $tlds */
    private $tlds;

    /**
     * Load tlds from text file.
     * 
     * @param string $path 
     * @return bool 
     * @throws Exception 
     */
    public function loadTlds(string $path): bool
    {
        if (file_exists($path)) {
            $tlds = file($path, FILE_IGNORE_NEW_LINES);
            $this->tlds = $this->cleanup($tlds);
        } else {
            throw new \Exception("File '{$path}' does not exist");
        }

        return true;
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
            $tlds = array_filter($tlds, fn($value) => $value !== '');
        } else {
            throw new \Exception("Tlds array is empty");
        }

        return $tlds;
    }
}
