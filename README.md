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
	//Error Happened
}else{
	// Everything went well
}
```

## Error Handling

ATLPay uses conventional HTTP response codes to indicate the success or failure of an API request. In general: Codes in the `2xx` range indicate success. Codes in the `4xx` range indicate an error that failed given the information provided (e.g., a required parameter was omitted, a charge failed, etc.). Codes in the `5xx` range indicate an error with ATLPay's servers (these are rare).

Some `4xx` errors that could be handled programmatically (e.g., a card is declined) include an error code that briefly explains the error reported.

#### HTTP status code summary
HTTP Status Code | Description
--- | ---
200 | Everything worked as expected.
400 | The request was unacceptable, often due to missing a required parameter.
401 | No valid API key provided.
402 | The parameters were valid but the request failed.
403 | You are not authorized to perform this transaction.
404 | The requested resource doesn't exist.
500, 502, 503, 504 | Something went wrong on ATLPay's end. (These are rare.)

## Creating a Token

```php

\ATLPay\ATLPay::setSecretKey('PLACE_YOUR_SECRET_KEY_HERE');
$token	=	new \ATLPay\Token('5555 5555 5555 4444', 12, 2020, '009', '192.168.1.1', 'USER SESSION ID', 'user@example.com');
$token->createToken();
if($token->isError()){
 	if(in_array($token->httpCode, [500, 502, 503, 504])){
		die("Something went wrong on ATLPay's end. (These are rare.)");
	}else if($token->httpCode == 400){
		if(isset($token->param) && $token->param == "card.name"){
			die("Problem with Cardholder's name : (".$token->code.") ".$token->message);
		}else if(isset($token->param) && $token->param == "card.number"){
			die("Problem with Card Number : (".$token->code.") ".$token->message);
		}else if(isset($token->param) && $token->param == "card.exp_month"){
			die("Problem with Card Expiry Month : (".$token->code.") ".$token->message);
		}else if(isset($token->param) && $token->param == "card.exp_year"){
			die("Problem with Card Expiry Year : (".$token->code.") ".$token->message);
		}else if(isset($token->param) && $token->param == "card.cvc"){
			die("Problem with Card CVC : (".$token->code.") ".$token->message);
		}else if(isset($token->param) && $token->param == "shopper.ip"){
			die("Problem with Shopper IP Address : (".$token->code.") ".$token->message);
		}else if(isset($token->param) && $token->param == "shopper.session_id"){
			die("Problem with Shopper Session ID : (".$token->code.") ".$token->message);
		}else if(isset($token->param) && $token->param == "shopper.email"){
			die("Problem with Shopper E-Mail : (".$token->code.") ".$token->message);
		}else{
			die("Problem with ".$token->param." : (".$token->code.") ".$token->message);
		}
	}else if($token->httpCode == 401){
		die("Check your API Key");
	}else if($token->httpCode == 402){
		die("You may encounter this error if you're not using TLS_1_2");
	}else if($token->httpCode == 403){
		die("Check your API Key");
	}
	//Since we are creating token, this request shall not be ended httpCode 404 i.e. Not Found
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
 	if(in_array($token->httpCode, [500, 502, 503, 504])){
		die("Something went wrong on ATLPay's end. (These are rare.)");
	}else if($token->httpCode == 401){
		die("Check your API Key");
	}else if($token->httpCode == 402){
		die("You may encounter this error if you're not using TLS_1_2");
	}else if($token->httpCode == 403){
		die("Check your API Key");
	}else if($token->httpCode == 404){
		die("Token Not Found.");
	}
	//Since we are retrieving token, this request shall not be ended httpCode 402 i.e. BAD_REQUEST
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
 	if(in_array($charge->httpCode, [500, 502, 503, 504])){
		die("Something went wrong on ATLPay's end. (These are rare.)");
	}else if($charge->httpCode == 400){
		if(isset($charge->param) && $charge->param == "amount"){
			die("Problem with Charge Amount : (".$charge->code.") ".$charge->message);
		}else if(isset($charge->param) && $charge->param == "currency"){
			die("Problem with Charge Currency : (".$charge->code.") ".$charge->message);
		}else if(isset($charge->param) && $charge->param == "description"){
			die("Problem with Charge Description : (".$charge->code.") ".$charge->message);
		}else if(isset($charge->param) && $charge->param == "return_url"){
			die("Problem with Return URL : (".$charge->code.") ".$charge->message);
		}else{
			die("Problem with ".$charge->param." : (".$charge->code.") ".$charge->message);
		}
	}else if($charge->httpCode == 401){
		die("Check your API Key");
	}else if($charge->httpCode == 402){
		die("You may encounter this error if you're not using TLS_1_2");
	}else if($charge->httpCode == 403){
		die("Check your API Key");
	}
	//Since we are creating charge, this request shall not be ended httpCode 404 i.e. Not Found
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
 	if(in_array($charge->httpCode, [500, 502, 503, 504])){
		die("Something went wrong on ATLPay's end. (These are rare.)");
	}else if($charge->httpCode == 401){
		die("Check your API Key");
	}else if($charge->httpCode == 402){
		die("You may encounter this error if you're not using TLS_1_2");
	}else if($charge->httpCode == 403){
		die("Check your API Key");
	}else if($charge->httpCode == 404){
		die("Charge Not Found.");
	}
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
 	if(in_array($charge->httpCode, [500, 502, 503, 504])){
		die("Something went wrong on ATLPay's end. (These are rare.)");
	}else if($charge->httpCode == 400){
		die($charge->message);
	}else if($charge->httpCode == 401){
		die("Check your API Key");
	}else if($charge->httpCode == 402){
		die("You may encounter this error if you're not using TLS_1_2");
	}else if($charge->httpCode == 403){
		die("Check your API Key");
	}else if($charge->httpCode == 404){
		die("Charge Not Found.");
	}
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
 	if(in_array($charge->httpCode, [500, 502, 503, 504])){
		die("Something went wrong on ATLPay's end. (These are rare.)");
	}else if($charge->httpCode == 401){
		die("Check your API Key");
	}else if($charge->httpCode == 402){
		die("You may encounter this error if you're not using TLS_1_2");
	}else if($charge->httpCode == 403){
		die("Check your API Key");
	}else if($charge->httpCode == 404){
		die("Charge Not Found.");
	}
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
	}else if($threeDRedirectResult == "PENDING"){
		header("Location:".$threeDRedirectUrl);
		exit;
	}else{
		die("Charge Status : <strong>".$chargeStatus."</strong> is not capturable.");
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
 	if(in_array($refund->httpCode, [500, 502, 503, 504])){
		die("Something went wrong on ATLPay's end. (These are rare.)");
	}else if($refund->httpCode == 400){
		die($refund->message);
	}else if($refund->httpCode == 401){
		die("Check your API Key");
	}else if($refund->httpCode == 402){
		die("You may encounter this error if you're not using TLS_1_2");
	}else if($refund->httpCode == 403){
		die("Check your API Key");
	}else if($refund->httpCode == 404){
		die("Charge Not Found.");
	}
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
$refund	=	new \ATLPay\Charge();
$refund->refund($apChargeId);
if($refund->isError()){
 	if(in_array($refund->httpCode, [500, 502, 503, 504])){
		die("Something went wrong on ATLPay's end. (These are rare.)");
	}else if($refund->httpCode == 400){
		die($refund->message);
	}else if($refund->httpCode == 401){
		die("Check your API Key");
	}else if($refund->httpCode == 402){
		die("You may encounter this error if you're not using TLS_1_2");
	}else if($refund->httpCode == 403){
		die("Check your API Key");
	}else if($refund->httpCode == 404){
		die("Charge Not Found.");
	}
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