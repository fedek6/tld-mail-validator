<?php

declare(strict_types=1);

namespace Fedek6\TldMailValidator;

use Fedek6\TldMailValidator\TldValidator;

final class TldMailValidator
{
    const TLDS_FILE = '../data/tlds-alpha-by-domain.txt';

    /** @var array $knownTlds */
    private $knownTlds;

    public function __construct()
    {
        
    }
}
