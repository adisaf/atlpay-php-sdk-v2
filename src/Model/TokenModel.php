<?php
namespace ATLPay\Model;
use ATLPay\Model\ATLPayError;
class TokenModel extends ATLPayError{
	
	private $id				=	NULL;
		
	private $brand			=	NULL;
	private $country		=	NULL;
	private $cardType		=	NULL;
	private $fundingType	=	NULL;
	private $last4Digits	=	NULL;
	private $redirectStatus	=	NULL;
	
	private $addressLine1	=	NULL;
	private $addressLine2	=	NULL;
	private $city			=	NULL;
	private $state			=	NULL;
	private $zipCode		=	NULL;
	private $addressCountry	=	NULL;
	
	private $mode			=	NULL;
	
	private	$atlpayResponse	=	NULL;
	private $error			=	NULL;
	
	//Default Constructor for TokenModel, Input ATLPay Response
	public function __construct($atlpayResponse = NULL){
		if($atlpayResponse){
			$this->atlpayResponse	=	$atlpayResponse;
			$this->read();
		}
	}
	
	//Setter Function for ATLPay Response
	public function setResponse($atlpayResponse){
		$this->atlpayResponse	=	$atlpayResponse;
	}
	
	//Setter Function for Token Data
	public function setToken($tokenObject){
		$this->id				=	$tokenObject->id;
		$this->brand			=	mb_strtoupper($tokenObject->card->brand);
		$this->country			=	mb_strtoupper($tokenObject->card->country);
		$this->fundingType		=	mb_strtoupper($tokenObject->card->fundingType);
		$this->last4Digits		=	$tokenObject->card->last4Digits;
		$this->cardType			=	$tokenObject->card->type;
		$this->redirectStatus	=	$tokenObject->card->authorization;
		
		$this->addressLine1		=	$tokenObject->card->addressLine1;
		$this->addressLine2		=	$tokenObject->card->addressLine2;
		$this->city				=	$tokenObject->card->addressCity;
		$this->state			=	$tokenObject->card->addressState;
		$this->zipCode			=	$tokenObject->card->addressZip;
		$this->addressCountry	=	mb_strtoupper($tokenObject->card->addressCountry);
	}
	
	//Read Function for reading ATLPay Response
	public function read(){
		if($this->atlpayResponse->isCurlError){
			throw new ATLPayException("cURL Error(".$this->atlpayResponse->curlErrorCode.") : ".$this->atlpayResponse->curlErrorMessage);
		}
		if($this->atlpayResponse->httpStatus == 200){
			$responseObject			=	json_decode($this->atlpayResponse->responseBody);
			$this->id				=	$responseObject->id;
			$this->brand			=	mb_strtoupper($responseObject->card->brand);
			$this->country			=	mb_strtoupper($responseObject->card->country);
			$this->fundingType		=	mb_strtoupper($responseObject->card->fundingType);
			$this->last4Digits		=	$responseObject->card->last4Digits;
			$this->cardType			=	$responseObject->card->type;
			$this->redirectStatus	=	$responseObject->card->authorization;
			$this->mode				=	$responseObject->mode;
			
			$this->addressLine1		=	$responseObject->card->addressLine1;
			$this->addressLine2		=	$responseObject->card->addressLine2;
			$this->city				=	$responseObject->card->addressCity;
			$this->state			=	$responseObject->card->addressState;
			$this->zipCode			=	$responseObject->card->addressZip;
			$this->addressCountry	=	mb_strtoupper($responseObject->card->addressCountry);
		}else{
			$this->unfold($this->atlpayResponse->responseBody, $this->atlpayResponse->httpStatus);
		}
	}
	
	//Getter function for ATLPay Token ID, Returns String Token ID
	public function getId(){
		return $this->id;
	}
	
	//Getter function for Card Brand (Visa, MasterCard etc.), Returns String Card Brand
	public function getCardBrand(){
		return ($this->brand);
	}
	
	//Getter function for Card Country, Returns ISO Alpha Country Code 2 eg. GB for United Kingdom
	public function getCardCountry(){
		return ($this->country);
	}
	
	//Getter function for Getting Funding Type of Card ("CREDIT" or "DEBIT"), Returns String Card Type
	public function getFundingType(){
		return ($this->fundingType);
	}
	
	//Getter function for Getting last 4 digits of Card Number, Returns String
	public function getLast4Digits(){
		return (string)$this->last4Digits;
	}
	
	//Getter function for Getting Redirect Status ("REQUIRED", "NOT_AVAILABLE"), Returns String
	public function getRedirectStatus(){
		return $this->redirectStatus;
	}
	
	//Getter function for Getting Transaction Mode ("test" or "live")
	public function getMode(){
		return $this->mode;
	}
	
	public function getAddressLine1(){
		return $this->addressLine1;
	}
	
	public function getAddressLine2(){
		return $this->addressLine2;
	}
	
	public function getCity(){
		return $this->city;
	}
	
	public function getState(){
		return $this->state;
	}
	
	public function getZipCode(){
		return $this->zipCode;
	}
	
	public function getAddressCountry(){
		return $this->addressCountry;
	}
	
}

?>