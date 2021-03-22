# TLD e-mail validator

[![Build Status](https://travis-ci.com/fedek6/tld-mail-validator.svg?token=Fpapy2MXqrwtfsLgfThp&branch=main)](https://travis-ci.com/fedek6/tld-mail-validator)

PHP e-mail address validator using list of all known [TLDs](https://data.iana.org/TLD/tlds-alpha-by-domain.txt) and simple domain MX record check. With ability to automatically update top-level domains list.

## Usage example

```php
use Fedek6\TldMailValidator\TldMailValidator;

/** @var string $tldsFilePath  */
$tldsFilePath = __DIR__ . '/../data/tlds-alpha-by-domain.txt';

/** @var array $addresses */
$addresses = require __DIR__ . '/addresses.php';

/** @var \Fedek6\TldMailValidator\TldMailValidator $validator */
$validator = new TldMailValidator($tldsFilePath);

if ($validator->validate('janbrzechwa@mail.ru')) {
    echo 'This is email is OK. and has proper actual tld.';
} else {
    echo 'Something\'s wrong with this address';
}
```

If you want to test also MX record of a domain (slower), pass second argument to the constructor:

```php
/** @var \Fedek6\TldMailValidator\TldMailValidator $validator */
$validator = new TldMailValidator($tldsFilePath, TldMailValidator::TEST_ALL);
```

## Testing

```bash
composer test
```

Or: 

```bash
./vendor/bin/phpunit --testdox --verbose --colors tests
```

### Test one file

```bash
vendor/bin/phpunit --testdox --verbose --colors tests/RemoteFileUpdaterTest.php
```

### Dry run before deploy

```bash
php -f examples/mx.php
php -f examples/simple.php
```
