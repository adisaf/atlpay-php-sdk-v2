<?php 
namespace ATLPay;
use ATLPay\ATLPay;
use ATLPay\Model\ChargeModel;
use ATLPay\Request;
class Charge extends ChargeModel{
	//Order Data
	private $amount					=	NULL;
	private $currency				=	NULL;
	private $token					=	NULL;
	private	$orderNumber			=	NULL;
	private $description			=	NULL;
	private $returnUrl				=	NULL;
	
	public function __construct(
		$token = NULL, 
		$amount = NULL, 
		$currency = NULL, 
		$orderNumber = NULL, 
		$description = NULL, 
		$returnUrl = NULL
	){
		$this->token		=	$token;
		$this->amount		=	$amount;
		$this->currency		=	$currency;
		$this->orderNumber	=	$orderNumber;
		$this->description	=	$description;
		$this->returnUrl	=	$returnUrl;
	}
	
	//Setter Function for Order Total Amount
	public function setAmount($amount){
		$this->amount	=	(float)$amount * 100;
	}
	
	//Setter Function for Order Currency
	public function setCurrency($currency){
		$this->currency	=	$currency;
	}
	
	//Setter Function for Card Token
	public function setToken($token){
		$this->token	=	$token;
	}
	
	//Setter Function for Order Number
	public function setOrderNumber($orderNumber){
		$this->orderNumber	=	$orderNumber;
	}
	
	//Setter Function for Order Description
	public function setDescription($description){
		$this->description	=	$description;
	}
	
	//Setter Function for Redirect URL after 3D Security Processing
	public function setReturnUrl($returnUrl){
		$this->returnUrl	=	$returnUrl;
	}
	
	//Initilize Order Function, Returns Model/ChargeModel on Success or Model/ATLPayError on Failure
	public function initialize(){
		$endPoint							=	ATLPay::$endPoint.'/charges';
		$payload							=	[];
		$payload["token"]					=	$this->token;
		$payload["amount"]					=	$this->amount;
		$payload["currency"]				=	$this->currency;
		$payload["description"]				=	$this->description;
		$payload["txn_reference"]			=	$this->orderNumber;
		$payload["return_url"]				=	$this->returnUrl;
		$request	=	new Request();
		$request->sendPayload("POST", $endPoint, $payload, [], false);
		$this->setResponse($request);
		$this->read();		
	}
	
	//Capture Order Function, Returns Model/ChargeModel on Success or Model/ATLPayError on Failure
	public function capture($id = NULL){
		$endPoint							=	ATLPay::$endPoint.'/charges/capture/'.($id ?: $this->getId());
		$payload							=	[];		
		$request	=	new Request();
		$request->sendPayload("POST", $endPoint, $payload, [], false);
		$this->setResponse($request);
		$this->read();
	}
	
	//Refund Order Function, Returns Model/ChargeModel on Success or Model/ATLPayError on Failure
	public function refund($id = NULL, $amount = NULL){
		$endPoint							=	ATLPay::$endPoint.'/charges/refund/'.($id ?: $this->getId());
		$payload							=	[];		
		if($amount){
			$payload["amount"]				=	$amount;		
		}else if($this->amount){
			$payload["amount"]				=	$this->amount;		
		}		
		$request	=	new Request();
		$request->sendPayload("POST", $endPoint, $payload, [], false);
		$this->setResponse($request);
		$this->read();
	}
	
	//Cancel Order Function, Returns Model/ChargeModel on Success or Model/ATLPayError on Failure
	public function cancel($id = NULL){
		$endPoint							=	ATLPay::$endPoint.'/charges/cancel/'.($id ?: $this->getId());
		$payload							=	[];		
		$request	=	new Request();
		$request->sendPayload("POST", $endPoint, $payload, [], false);
		$this->setResponse($request);
		$this->read();
	}
	
	//Retrive Order Function, Returns Model/ChargeModel on Success or Model/ATLPayError on Failure
	public function get($id = NULL){
		$endPoint							=	ATLPay::$endPoint.'/charges/'.($id ?: $this->getId());
		$payload							=	[];		
		$request	=	new Request();
		$request->sendPayload("GET", $endPoint, $payload, [], false);
		$this->setResponse($request);
		$this->read();
	}
	
}
?>