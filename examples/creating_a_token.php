<?php
session_start();
require_once("../vendor/autoload.php");
require_once("functions.php");
\ATLPay\ATLPay::setSecretKey('29341b455857e4081dded5a14ec598d54676aa75');
$token	=	new \ATLPay\Token('5555 5555 5555 4444', 12, 2020, '123', "DEMO USER", '203.163.244.165', session_id(), 'user@example.com');
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
	echo "Token ID : <strong>".$tokenId."</strong><br />";
	echo "Card Brand : <strong>".$cardBrand."</strong><br />";
	echo "Card Issuer Country : <strong>".$cardIssuerCountry."</strong><br />";
	echo "Card Funding Type : <strong>".$cardFundingType."</strong><br />";
	echo "Card Last 4 Digits : <strong>".$cardLast4."</strong><br />";
	echo "Three D Redirect : <strong>".$threeDRedirectStatus."</strong><br />";
	echo "Mode : <strong>".$transactionMode."</strong>";
}
?>