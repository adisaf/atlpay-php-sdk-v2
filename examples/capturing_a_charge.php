<?php
session_start();
require_once("../vendor/autoload.php");
require_once("functions.php");
\ATLPay\ATLPay::setSecretKey('29341b455857e4081dded5a14ec598d54676aa75');
\ATLPay\ATLPay::setSecretKey('29341b455857e4081dded5a14ec598d54676aa75');
$charge	=	new \ATLPay\Charge();
$charge->get("I2018062895690");
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
 	$chargeId	=	$charge->getId();
	$chargeCurrency	=	$charge->getCurrency();
	$chargeAmount	=	$charge->getAmount();
	$atlpayFees	=	$charge->getFees();	
	$token	=	$charge->token();
	$tokenId	=	$token->getId();
	$cardBrand	=	$token->getCardBrand();
	$cardIssuerCountry	=	$token->getCardCountry();
	$cardFundingType	=	$token->getFundingType();
	$cardLast4	=	$token->getLast4Digits();
	$threeDRedirectStatus	=	$token->getRedirectStatus();
	$threeDRedirectUrl	=	$charge->getRedirectUrl();
	$threeDRedirectResult	=	$charge->get3DRedirectStatus();
	$chargeStatus			=	$charge->getStatus();
	$transactionMode	=	$charge->getMode();
	echo "Token ID : <strong>".$tokenId."</strong><br />";
	echo "Card Brand : <strong>".$cardBrand."</strong><br />";
	echo "Card Issuer Country : <strong>".$cardIssuerCountry."</strong><br />";
	echo "Card Funding Type : <strong>".$cardFundingType."</strong><br />";
	echo "Card Last 4 Digits : <strong>".$cardLast4."</strong><br />";
	echo "Three D Redirect : <strong>".$threeDRedirectStatus."</strong><br />";
	echo "Mode : <strong>".$transactionMode."</strong><br />";
	echo "Charge ID : <strong>".$chargeId."</strong><br />";
	echo "Charge Status : <strong>".$chargeStatus."</strong><br />";
	echo "3-D Redirect Url : <strong>".$threeDRedirectUrl."</strong><br />";
	echo "3-D Redirect Result : <strong>".$threeDRedirectResult."</strong><br />";
	if($threeDRedirectResult == "CHARGEABLE"){
		echo "------------------------------------------------------------<br />";
		echo "Capturing the charge...<br />";
		echo "------------------------------------------------------------<br />";
		$charge->capture();
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
			$chargeStatus	=	$charge->getStatus();
			if($charge->isSuccess()){
				$atlpayFees	=	$charge->getFees();	
			}else{
				$failureReason	=	$charge->getReason();
			}	
			$transactionMode	=	$charge->getMode();
		}
		echo "Charge Status : <strong>".$chargeStatus."</strong><br />";
		if($charge->isSuccess()){
			echo "ATLPay Fees : <strong>".(float)@$atlpayFees."</strong><br />";
		}else{
			echo "Failure Reason : <strong>".(@$failureReason ?: "Unknown")."</strong><br />";
		}
		echo "Mode : <strong>".$transactionMode."</strong><br />";
	}else if($threeDRedirectResult == "PENDING"){
		header("Location:".$threeDRedirectUrl);
		exit;
	}else{
		echo "Charge Status : <strong>".$chargeStatus."</strong> is not capturable.<br />";
	}
}