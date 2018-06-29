<?php
session_start();
require_once("../vendor/autoload.php");
require_once("functions.php");
\ATLPay\ATLPay::setSecretKey('YOUR_SECRET_KEY');
$charge	=	new \ATLPay\Charge();
$charge->cancel("I2018062895690");
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
	echo "Charge Status : <strong>".$chargeStatus."</strong><br />";
	echo "Failure Reason : <strong>".$failureReason."</strong><br />";
	echo "Mode : <strong>".$transactionMode."</strong><br />";
}