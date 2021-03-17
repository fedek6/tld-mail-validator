<?php

require __DIR__ . '/../vendor/autoload.php';

use Fedek6\TldMailValidator\TldMailValidator;

/** @var string $tldsFilePath  */
$tldsFilePath = __DIR__ . '/../data/tlds-alpha-by-domain.txt';

/** @var array $addresses */
$addresses = require __DIR__ . '/addresses.php';

/** @var \Fedek6\TldMailValidator\TldMailValidator $validator */
$validator = new TldMailValidator($tldsFilePath);

foreach ($addresses as $address) {
    echo "Address {$address} is " . ($validator->validate($address) ? 'valid' : 'invalid') . "\n";
}
