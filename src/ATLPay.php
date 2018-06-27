<?php
namespace ATLPay;
class ATLPay {
	//ATLPay APIv2 Endpoint
	public static $endPoint		=	"https://api.atlpay.com/v2";
	//ATLPay APIv2 Secret Key, You can find it in your account
	private static $secretKey;
	//Enable/Disable SSL Certificate Validation for cURL Request
	private static $verifySSL		=	false;
	
	public function __construct($secretKey = NULL){
		self::$secretKey	=	 $secretKey;
	}
	
	//Setter Function for Secret Key
	public static function setSecretKey($secretKey){
		self::$secretKey	=	$secretKey;
	}
	
	//Getter Function for Secret Key, Returns String
	public static function getSecretKey(){
		return self::$secretKey;
	}
	
	//Setter Function for SSL Verification
	public static function verifySSL($verifySSL){
		self::$verifySSL	=	$verifySSL;
	}
	
	//Getter Function for Secret Verification, Returns Boolean
	public static function getSSLVerifyStatus(){
		return self::$verifySSL;
	}
	
}