<?php
session_start();
require_once("../vendor/autoload.php");
require_once("functions.php");
\ATLPay\ATLPay::setSecretKey('29341b455857e4081dded5a14ec598d54676aa75');
$charge	=	new \ATLPay\Charge();
$charge->cancel("V2018062846680");
if($charge->isError()){
	debug($charge);
}else{
	$chargeStatus	=	$charge->getStatus(); //CHARGE_FAILED
	$failureReason	=	$charge->getReason(); //CANCEL_USING_API
	$transactionMode	=	$charge->getMode();
	echo "Charge Status : <strong>".$chargeStatus."</strong><br />";
	echo "Failure Reason : <strong>".$failureReason."</strong><br />";
	echo "Mode : <strong>".$transactionMode."</strong><br />";
}