# ATLPay PHP SDK for APIv2
ATLPay Payment Gateway Integration PHP SDK using Version 2 API
## Requirements

PHP 5.5.28 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require atlpay/php-sdk-v2
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):

```php
require_once('vendor/autoload.php');
```



## Dependencies

The bindings require the following extension in order to work properly:

- [`ext_curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer
- [`ext_json`](https://secure.php.net/manual/en/book.json.php)
- [`ext_mbstring`](https://secure.php.net/manual/en/book.mbstring.php) (Multibyte String)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.