<?php
session_start();
require_once("../vendor/autoload.php");
require_once("functions.php");
\ATLPay\ATLPay::setSecretKey('29341b455857e4081dded5a14ec598d54676aa75');
$charge	=	new \ATLPay\Charge("98add6bb-4a8a-493a-8654-87431745d52d", 50.00, "GBP", "SDKT-0002", "Test Payment", 'http://127.0.0.1/atlpay-sdk-v2/examples/capturing_a_charge.php');
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
			die("Problem : ".$charge->message);
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
	$tokenId	=	$token->getId();
	$cardBrand	=	$token->getCardBrand();
	$cardIssuerCountry	=	$token->getCardCountry();
	$cardFundingType	=	$token->getFundingType();
	$cardLast4	=	$token->getLast4Digits();
	$threeDRedirectStatus	=	$token->getRedirectStatus();
	$threeDRedirectUrl	=	$charge->getRedirectUrl();
	$threeDRedirectResult	=	$charge->get3DRedirectStatus();
	$transactionMode	=	$charge->getMode();
	echo "Token ID : <strong>".$tokenId."</strong><br />";
	echo "Card Brand : <strong>".$cardBrand."</strong><br />";
	echo "Card Issuer Country : <strong>".$cardIssuerCountry."</strong><br />";
	echo "Card Funding Type : <strong>".$cardFundingType."</strong><br />";
	echo "Card Last 4 Digits : <strong>".$cardLast4."</strong><br />";
	echo "Three D Redirect : <strong>".$threeDRedirectStatus."</strong><br />";
	echo "Mode : <strong>".$transactionMode."</strong><br />";
	echo "Charge ID : <strong>".$chargeId."</strong><br />";
	echo "3-D Redirect Url : <strong>".$threeDRedirectUrl."</strong><br />";
	echo "3-D Redirect Result : <strong>".$threeDRedirectResult."</strong><br />";
	//if( $token->getRedirectStatus() == "REQUIRED" ){
	//	header("Location:".$threeDRedirectUrl);
	//	exit;
	//}else{
		//Capture the charge directly, See Capturing a Charge
	//}	
}
?>