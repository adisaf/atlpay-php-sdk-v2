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

## Getting Started
ATLPay APIv2 is synchronized API and provides instant confirmation thus it does not require notification url. Any status returned by API should be considered final. Simple usage looks like:

```php
\ATLPay\ATLPay::setSecretKey('PLACE_YOUR_SECRET_KEY_HERE');
$token	=	new \ATLPay\Token();
$token->createToken('5555 5555 5555 4444', 12, 2020, '009', '192.168.1.1', 'USER SESSION ID', 'user@example.com');
if($token->isError()){
	// Error handling
}else{
	// Ok Handling
}
```

## Custom Request Timeouts
NOTE: We do not recommend decreasing the timeout for non-read-only calls (e.g. charge creation), since even if you locally timeout, the request on ATLPay's side can still complete.
```php
\ATLPay\ATLPay::setTimeout(15);
```

## SSL / TLS compatibility issues
ATLPay's API now requires that all connections use TLS 1.2. Some systems (most notably some older CentOS and RHEL versions) are capable of using TLS 1.2 but will use TLS 1.0 or 1.1 by default. In this case, you'd get an BAD_REQUEST error with the following error message: "ATLPay no longer supports API requests made with TLS 1.0. Please initiate HTTPS connections with TLS 1.2 or later.

The recommended course of action is to upgrade your cURL and OpenSSL packages so that TLS 1.2 is used by default, but if that is not possible, you might be able to solve the issue by setting the `CURLOPT_SSLVERSION` option to either `CURL_SSLVERSION_TLSv1` or `CURL_SSLVERSION_TLSv1_2`:

```php
\ATLPay\ATLPay::setSSLVersion(CURL_SSLVERSION_TLSv1_2);
```