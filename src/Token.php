<?php
namespace ATLPay;
use ATLPay\ATLPay;
use ATLPay\Request;
use ATLPay\Model\TokenModel;
class Token extends TokenModel{
	//Card Data
	private $cardNumber;
	private $expMonth;
	private $expYear;
	private $cvc;
	private $name;
	
	//Shopper Browsing Data
	private $ipAddress;
	private $sessionId;
	private $emailAddress;
		
	//Billing Address Data
	private $addressLine1;
	private $addressLine2;
	private $city;
	private $state;
	private $zipcode;
	private $country;
	
	public function __construct(
		$cardNumber = NULL, 
		$expMonth = NULL, 
		$expYear = NULL, 
		$cvc = NULL, 
		$name = NULL, 
		$ipAddress = NULL, 
		$sessionId = NULL, 
		$emailAddress = NULL,
		$addressLine1 = NULL, 
		$addressLine2 = NULL, 
		$city = NULL, 
		$state = NULL, 
		$zipcode = NULL, 
		$country = NULL
	){
		$this->cardNumber	=	$cardNumber;
		$this->expMonth		=	$expMonth;
		$this->expYear		=	$expYear;
		$this->cvc			=	$cvc;
		$this->name			=	$name;
		
		$this->ipAddress	=	$ipAddress;
		$this->sessionId	=	$sessionId;
		$this->emailAddress	=	$emailAddress;
		
		$this->addressLine1	=	$addressLine1;
		$this->addressLine2	=	$addressLine2;
		$this->city			=	$city;
		$this->state		=	$state;
		$this->zipcode		=	$zipcode;
		$this->country		=	$country;
	}
	
	//Setter Function for Card Number
	public function setCardNumber($cardNumber){
		$this->cardNumber	=	$cardNumber;
	}
	
	//Setter Function for Card Expiry Month
	public function setExpMonth($expMonth){
		$this->expMonth		=	$expMonth;
	}
	
	//Setter Function for Card Expiry Year
	public function setExpYear($expYear){
		$this->expYear		=	$expYear;
	}
	
	//Setter Function for Card CVC
	public function setCvc($cvc){
		$this->cvc			=	$cvc;
	}
	
	//Setter Function for Cardholder's Name
	public function setName($name){
		$this->name			=	$name;
	}
	
	//Setter Function for Billing Address Line 1
	public function setAddressLine1($addressLine1){
		$this->addressLine1	=	$addressLine1;
	}
	
	//Setter Function for Billing Address Line 2	
	public function setAddressLine2($addressLine2){
		$this->addressLine2	=	$addressLine2;
	}
	
	//Setter Function for Billing City
	public function setCity($city){
		$this->city			=	$city;
	}
	
	//Setter Function for Billing State/Region/County
	public function setState($state){
		$this->state		=	$state;
	}
	
	public function setZipcode($zipcode){
		$this->zipcode		=	$zipcode;
	}
	
	//Setter Function for Billing Postal/Zip code
	public function setCountry($country){
		$this->country		=	$country;
	}
	
	//Create Token Function, Returns Model/TokenModel on Success or Model/ATLPayError on Failure
	public function createToken(){
		$payload							=	[];
		
		$payload["card"]					=	[];
		$payload["card"]["number"]			=	$this->cardNumber;
		$payload["card"]["exp_month"]		=	$this->expMonth;
		$payload["card"]["exp_year"]		=	$this->expYear;
		$payload["card"]["cvc"]				=	$this->cvc;
		$payload["card"]["name"]			=	$this->name;
		
		$payload["shopper"]["ip"]			=	$this->ipAddress;
		$payload["shopper"]["session_id"]	=	$this->sessionId;
		$payload["shopper"]["email"]		=	$this->emailAddress;
		
		$payload["address"]["address_line1"]	=	@$this->addressLine1;
		$payload["address"]["address_line2"]	=	@$this->addressLine2;
		$payload["address"]["city"]				=	@$this->city;
		$payload["address"]["state"]			=	@$this->state;
		$payload["address"]["country"]			=	@$this->country;
		$payload["address"]["zipcode"]		=	@$this->zipcode;
		
		
		$endPoint							=	ATLPay::$endPoint.'/tokens';
		$request							=	new Request();
		$request->sendPayload("POST", $endPoint, $payload, [], false);
		$this->setResponse($request);
		$this->read();
	}
	
	//Retrive Token Function, Returns Model/TokenModel on Success or Model/ATLPayError on Failure
	public function getToken($id){
		$tokenEndpoint						=	ATLPay::$endPoint.'/tokens/'.$id;
		$request							=	new Request();
		$request->sendPayload("GET", $tokenEndpoint, [], [], false);
		$this->setResponse($request);
		$this->read();
	}
	
}
?>