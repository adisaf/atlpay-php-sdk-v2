<?php
session_start();
require_once("../vendor/autoload.php");
require_once("functions.php");
\ATLPay\ATLPay::setSecretKey('29341b455857e4081dded5a14ec598d54676aa75');
$token	=	new \ATLPay\Token();
$token->getToken("1397560a-fc96-4a75-8635-f6c88f3f99ca");
if($token->isError()){
	debug($token);
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