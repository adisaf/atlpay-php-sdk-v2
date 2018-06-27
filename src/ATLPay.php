<?php
namespace ATLPay;
class ATLPay {
	//ATLPay APIv2 Endpoint
	public static $endPoint		=	"https://api.atlpay.com/v2";
	//ATLPay APIv2 Secret Key, You can find it in your account
	private static $secretKey;
	//Enable/Disable SSL Certificate Validation for cURL Request
	private static $verifySSL		=	false;
	//cURL timeout
	private static $timeout		=	30;
	//cURL ssl version to use
	private static $sslVersion		=	CURL_SSLVERSION_TLSv1_2;
	
	
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
	
	//Setter Function for cURL request timeout
	public static function setTimeout($timeout){
		if( is_numeric($timeout) ){
			$this->timeout	=	$timeout;
		}
	}
	
	//Getter Function for cURL request timeout
	public static function getTimeout(){
		return $this->timeout;
	}
	
	//Setter Function for SSL Version to use for cURL Request
	public static function setSSLVersion($sslVersion){
		if( is_numeric($timeout) ){
			$this->sslVersion	=	$sslVersion;
		}
	}
	
	//Getter Function for getting SSL Version being Used for cURL Requests, returns INT
	public static function getSSLVersion(){
		return $this->sslVersion;
	}
	
}