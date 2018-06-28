# ATLPay PHP SDK for APIv2
PHP Wrapper for ATLPay API Version 2

[ATLPay APIv2 Documentation](http://atlpay-php-sdk-v2.readthedocs.io)

Table of Contents
=================
  * [Requirements](#requirements)
  * [Installation and Usage](#installation-and-usage)
  * [Dependencies](#dependencies)
  * [Getting Started](#getting-started)
  * [Error Handling](#error-handling)
  * [Creating a Token](#creating-a-token)
  * [Retrieving a Token](#retrieving-a-token)
  * [Creating a Charge](#creating-a-charge)
  * [Retrieving a Charge](#retrieving-a-charge)
  * [Cancelling a Charge](#cancelling-an-authorized-charge)
  * [Capturing a Charge](#capturing-a-charge)
  * [Creating a Refund](#creating-a-refund)
  * [Custom Request Timeouts](#custom-request-timeouts)
  * [SSL / TLS compatibility issues](#ssl--tls-compatibility-issues)
  * [Test Cards](#test-cards)
  
  

## Requirements

PHP 5.5.28 and later.


## Installation and Usage

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
$token->createToken('5555 5555 5555 4444', 12, 2020, '009', 'CARD_HOLDER_NAME' '192.168.1.1', 'USER SESSION ID', 'user@example.com');
if($token->isError()){
	//Error Happened, See error handling section for more details
}else{
	// Everything went well
}
```

## Error Handling

## Creating a Token

```php

\ATLPay\ATLPay::setSecretKey('PLACE_YOUR_SECRET_KEY_HERE');
$token	=	new \ATLPay\Token('5555 5555 5555 4444', 12, 2020, '009', '192.168.1.1', 'USER SESSION ID', 'user@example.com');
$token->createToken();
if($token->isError()){
 	// Error Happened, See error handling section for more details
}else{
 	$tokenId	=	$token->getId();
	$cardBrand	=	$token->getCardBrand();
	$cardIssuerCountry	=	$token->getCardCountry();
	$cardFundingType	=	$token->getFundingType();
	$cardLast4	=	$token->getLast4Digits();
	$threeDRedirectStatus	=	$token->getRedirectStatus();
	$transactionMode	=	$token->getMode();
	// See Model/TokenModel.php for more Getter Methods
}
```

## Retrieving a Token

```php
\ATLPay\ATLPay::setSecretKey('PLACE_YOUR_SECRET_KEY_HERE');
$token	=	new \ATLPay\Token();
$token->getToken($tokenId);
if($token->isError()){
 	// Error Happened, See error handling section for more details
}else{
 	$tokenId	=	$token->getId();
	$cardBrand	=	$token->getCardBrand();
	$cardIssuerCountry	=	$token->getCardCountry();
	$cardFundingType	=	$token->getFundingType();
	$cardLast4	=	$token->getLast4Digits();
	$threeDRedirectStatus	=	$token->getRedirectStatus();
	$transactionMode	=	$token->getMode();
	// See Model/TokenModel.php for more Getter Methods
}
```

## Creating a Charge

```php

\ATLPay\ATLPay::setSecretKey('PLACE_YOUR_SECRET_KEY_HERE');
$charge	=	new \ATLPay\Charge(ATLPAY_TOKEN_ID, 50.00, EUR, ORDER_NUMBER, ORDER_DESCRIPTION, 'https://www.your-return-url.com');
//Here https://www.your-return-url.com is used as placeholder replace it with your url.
//After 3D Authorization is completed User will be redirected back this url and you can proceed with
//capturing or cancelling the charge. Refer to Handling 3DS or 3-D Security for more details 
$charge->initialize();
if($charge->isError()){
 	// Error Happened, See error handling section for more details
}else{
 	$chargeId	=	$charge->getId();
	$chargeCurrency	=	$charge->getCurrency();
	$chargeAmount	=	$charge->getAmount();
	$atlpayFees	=	$charge->getFees();	
	$token	=	$charge->token();
	// See Model/TokenModel.php for more Getter Methods
	$threeDRedirectUrl	=	$charge->getRedirectUrl();
	$threeDRedirectResult	=	$charge->get3DRedirectStatus();
	$transactionMode	=	$charge->getMode();
	// See Model/ChargeModel.php for more Getter Methods
	if( $token->getRedirectStatus() == "REQUIRED" ){
		header("Location:".$threeDRedirectUrl);
		exit;
	}else{
		//Capture the charge directly, See Capturing a Charge
	}	
}
```

## Retrieving a Charge

```php
\ATLPay\ATLPay::setSecretKey('PLACE_YOUR_SECRET_KEY_HERE');
$charge	=	new \ATLPay\Charge();
$charge->get($apChargeId);
if($charge->isError()){
 	// Error Happened, See error handling section for more details
}else{
 	$chargeId	=	$charge->getId();
	$chargeCurrency	=	$charge->getCurrency();
	$chargeAmount	=	$charge->getAmount();
	$chargeStatus	=	$charge->getStatus();
	if($charge->isSuccess()){
		$atlpayFees	=	$charge->getFees();	
	}else{
		$failureReason	=	$charge->getReason();
	}	
	$token	=	$charge->token();
	// See Model/TokenModel.php for more Getter Methods
	$threeDRedirectUrl	=	$charge->getRedirectUrl();
	$threeDRedirectResult	=	$charge->get3DRedirectStatus();
	$transactionMode	=	$charge->getMode();
	// See Model/ChargeModel.php for more Getter Methods	
}
```

## Cancelling an Authorized Charge

```php
\ATLPay\ATLPay::setSecretKey('PLACE_YOUR_SECRET_KEY_HERE');
$charge	=	new \ATLPay\Charge();
$charge->cancel($apChargeId);
if($charge->isError()){
 	// Error Happened, See error handling section for more details
}else{
	$chargeStatus	=	$charge->getStatus(); //CHARGE_FAILED
	$failureReason	=	$charge->getReason(); //CANCEL_USING_API
	$transactionMode	=	$charge->getMode();
	// See Model/ChargeModel.php for more Getter Methods	
}
```

## Capturing a Charge

```php
\ATLPay\ATLPay::setSecretKey('PLACE_YOUR_SECRET_KEY_HERE');
$charge	=	new \ATLPay\Charge();
$charge->get($apChargeId);
if($charge->isError()){
 	// Error Happened, See error handling section for more details
}else{
 	$threeDRedirectUrl	=	$charge->getRedirectUrl();
	$threeDRedirectResult	=	$charge->get3DRedirectStatus();
	if($threeDRedirectResult == "CHARGEABLE"){
		$charge->capture();
		if($charge->isError()){
			// Error Happened, See error handling section for more details
		}else{
			$chargeStatus	=	$charge->getStatus();
			if($charge->isSuccess()){
				$atlpayFees	=	$charge->getFees();	
			}else{
				$failureReason	=	$charge->getReason();
			}	
			$transactionMode	=	$charge->getMode();
		}
	}else{
		header("Location:".$threeDRedirectUrl);
		exit;
	}	
		
}
```

## Creating a Refund
ATLPay lets you do partial and full refunds. It also allows you to process multiple partial refunds.

A) Creating partial refund

```php
\ATLPay\ATLPay::setSecretKey('PLACE_YOUR_SECRET_KEY_HERE');
$refund	=	new \ATLPay\Charge();
$refund->refund($apChargeId, $amountToRefund);
if($refund->isError()){
 	// Error Happened, See error handling section for more details
}else{
	$refundId		=	$refund->getId();
	$refundAmount	=	$refund->getAmount();
	$refundFees	=	$refund->getFees();
	$transactionMode	=	$refund->getMode();
}
```

B) Creating full refund

```php
\ATLPay\ATLPay::setSecretKey('PLACE_YOUR_SECRET_KEY_HERE');
$charge	=	new \ATLPay\Charge();
$charge->refund($apChargeId);
if($refund->isError()){
 	// Error Happened, See error handling section for more details
}else{
	$refundId		=	$refund->getId();
	$refundAmount	=	$refund->getAmount();
	$refundFees	=	$refund->getFees();
	$transactionMode	=	$refund->getMode();
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

## Test Cards

Following test cards can be used for testing ATLPay API

Card Number | Brand | Funding Type | Issuer Country
--- | --- | --- | ---
5555 5555 5555 4444 | MasterCard | Credit | GB
5454 5454 5454 5454 | MasterCard | Debit | FR
5555 5555 5555 4443 | MasterCard | Credit | GB
5454 5454 5454 5453 | MasterCard | Debit | GB
6759 6498 2643 8450 | Maestro | Debit | GB
4444 3333 2222 1111 | Visa | Credit | FR
4462 0300 0000 0000 | Visa | Debit | IN
4444 3333 2222 1112 | Visa | Credit | FR
4462 0300 0000 0001 | Visa | Debit | FR