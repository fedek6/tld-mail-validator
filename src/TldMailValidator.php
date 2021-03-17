<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator;

use Exception;
use Fedek6\TldMailValidator\TldValidator;

/**
 * Tld mail validator.
 * 
 * @version 1.0.0
 * @author Konrad Fedorczyk <contact@realhe.ro>
 * @package Fedek6\TldMailValidator
 */
class TldMailValidator
{
    /** @var \Fedek6\TldMailValidator\TldValidator $tldValidator  */
    private $tldValidator;

    /**
     * Constructor.
     * 
     * @return void 
     * @throws Exception 
     */
    public function __construct(string $storageFilePath)
    {
        $this->tldValidator = new TldValidator();
        $this->tldValidator->loadTlds($storageFilePath);
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

        return $this->tldValidator->checkDomain($domain);
    }
}
