<?php
namespace ATLPay\Model;
use ATLPay\Model\ATLPayError;
use ATLPay\Model\TokenModel;
class ChargeModel extends ATLPayError{
	private $id						=	NULL;
	private $amount					=	NULL;
	private $amountRefunded			=	NULL;
	private $currency				=	NULL;
	private $description			=	NULL;
	private $status					=	NULL;	
	private $threedSecure			=	NULL;
	private $authStatus				=	NULL;
	private $reason					=	NULL;
	private $atlPayResponse			=	NULL;
	private $orderNumber			=	NULL;
	private $fees					=	NULL;
	private $tokenObject			=	NULL;
	private $mode					=	NULL;
	
	//Default Constructor for ChargeModel, Input ATLPay Response
	public function __construct($atlPayResponse = NULL){
		if($atlPayResponse){
			$this->atlPayResponse	=	$atlPayResponse;
			$this->read();
		}
		$this->tokenObject			=	new TokenModel();
	}
	
	//Setter Function for ATLPay Response
	public function setResponse($atlPayResponse){
		$this->atlPayResponse	=	$atlPayResponse;
	}
	
	//Getter Function for TokenObject
	public function token(){
		return $this->tokenObject;
	}
	
	//Read Function for reading ATLPay Response
	public function read(){
		if($this->atlPayResponse->isCurlError){
			throw new ATLPayException("cURL Error(".$this->atlPayResponse->curlErrorCode.") : ".$this->atlPayResponse->curlErrorMessage);
		}
		if($this->atlPayResponse->httpStatus == 200){
			$responseObject			=	json_decode($this->atlPayResponse->responseBody);
			if($responseObject->type == "charge"){
				$this->id					=	$responseObject->id;
				$this->amount				=	$responseObject->amount;
				$this->currency				=	mb_strtoupper($responseObject->currency);
				$this->description			=	$responseObject->description;
				$this->status				=	mb_strtoupper($responseObject->status);
				$this->reason				=	@$responseObject->reason;
				$this->orderNumber			=	@$responseObject->txn_reference;
				$this->fees					=	@$responseObject->fees;
				$this->mode					=	$responseObject->mode;
				if(isset($responseObject->threedSecure)){
					$this->threedSecure	=	$responseObject->threedSecure;
				}
				$this->tokenObject->setToken($responseObject->token);
			}else if($responseObject->type == "refund"){
				$this->id					=	$responseObject->id;
				$this->amount				=	$responseObject->amount;
				$this->currency				=	mb_strtoupper($responseObject->currency);
				$this->fees					=	$responseObject->fee_refunded;
				$this->mode					=	$responseObject->mode;
			}
		}else{
			$this->unfold($this->atlPayResponse->responseBody, $this->atlPayResponse->httpStatus);
		}
	}
	
	//Getter function for ATLPay Charge ID, Returns String Charge ID
	public function getId(){
		return $this->id;
	}
	
	//Getter function for Order Amount, Returns Float
	public function getAmount(){
		return $this->amount / 100;
	}
	
	//Getter function for ATLPay Fees, Returns Float
	public function getFees(){
		return $this->fees / 100;
	}
	
	//Getter function for Order Currency, Returns Char [2], ISO ALPHA-3 Code of Currency eg. EUR
	public function getCurrency(){
		return $this->currency;
	}
	
	//Getter function for Order Number, Returns String
	public function getOrderNumber(){
		return $this->orderNumber;
	}
	
	//Getter function for Order Description, Returns String
	public function getDescription(){
		return $this->description;
	}
	
	//Getter function for Reason for Payment Failure, Returns String
	public function getReason(){
		return $this->reason;
	}
	
	//Getter function for Getting Transaction Mode ("test" or "live")
	public function getMode(){
		return $this->mode;
	}
	
	//Getter function for Getting Charge Status
	public function getStatus(){
		return $this->status;
	}
	
	//Getter function for Getting 3D Redirection URL
	public function getRedirectUrl(){
		return (isset($this->threedSecure->redirect) ? $this->threedSecure->redirect : NULL);
	}
	
	//Getter function for Getting 3D Redirection Status ("PENDING", "COMPLETED")
	public function get3DRedirectStatus(){
		return (isset($this->threedSecure->status) ? $this->threedSecure->status : NULL);
	}
	
	//Getter function for Getting Return URL where Customer will be redirected after completion of 3D Authentication
	public function get3DReturnUrl(){
		return (isset($this->threedSecure->returnUrl) ? $this->threedSecure->returnUrl : NULL);
	}
	
	//Checks whether charge was successful or not
	public function isSuccess(){
		return mb_strtoupper($this->status) == "CHARGE_SUCCESS";
	}
	
}

?>