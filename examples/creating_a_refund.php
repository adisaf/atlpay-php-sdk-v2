<?php
session_start();
require_once("../vendor/autoload.php");
require_once("functions.php");
\ATLPay\ATLPay::setSecretKey('29341b455857e4081dded5a14ec598d54676aa75');
$refund	=	new \ATLPay\Charge();
$refund->refund("J2018062881756"); // FUll Refund
//$refund->refund("J2018062881756", 10); // Partial Refund
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
	echo "Refund ID : <strong>".$refundId."</strong><br />";
	echo "Refund Amount : <strong>".$refundAmount."</strong><br />";
	echo "Refunded Fees : <strong>".$refundFees."</strong><br />";
	echo "Mode : <strong>".$transactionMode."</strong><br />";
}
?>