<?php
session_start();
require_once("../vendor/autoload.php");
require_once("functions.php");
\ATLPay\ATLPay::setSecretKey('29341b455857e4081dded5a14ec598d54676aa75');
$token	=	new \ATLPay\Token();
$token->getToken("1397560a-fc96-4a75-8635-f6c88f3f99ca");
if($token->isError()){
	if(in_array($token->httpCode, [500, 502, 503, 504])){
		die("Something went wrong on ATLPay's end. (These are rare.)");
	}else if($token->httpCode == 400){
		die($token->message);
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
	echo "Token ID : <strong>".$tokenId."</strong><br />";
	echo "Card Brand : <strong>".$cardBrand."</strong><br />";
	echo "Card Issuer Country : <strong>".$cardIssuerCountry."</strong><br />";
	echo "Card Funding Type : <strong>".$cardFundingType."</strong><br />";
	echo "Card Last 4 Digits : <strong>".$cardLast4."</strong><br />";
	echo "Three D Redirect : <strong>".$threeDRedirectStatus."</strong><br />";
	echo "Mode : <strong>".$transactionMode."</strong>";
}
?>