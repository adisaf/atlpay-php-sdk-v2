<?php 
namespace ATLPay\Model;
class ATLPayError{
	public $type		=	NULL;
	public $code		=	NULL;
	public $message		=	NULL;
	public $param		=	NULL;
	private $isError	=	false;
	public  $httpCode	=	NULL;
	private $mode	=	NULL;
	
	//Function to unfold ATLPay Error Response	
	public function unfold($errorResponse, $httpCode = 500){
		$this->httpCode	=	$httpCode;
		$error	=	json_decode($errorResponse);
		if(NULL !== $error){
			$this->message	=	@$error->message;
			if(isset($error->type)){
				$this->type	=	$error->type;
			}
			if($httpCode == 400){
				if(isset($error->error)){
					if(isset($error->error->message)){
						$this->message	=	$error->error->message;
					}
					if(isset($error->error->parameter)){
						$this->param	=	$error->error->parameter;
					}
					if(isset($error->error->code)){
						$this->code	=	$error->error->code;
					}
				}
			}
		}else{
			//debug("Something Went Wrong Outside of ATLPay : ".$errorResponse);
		}
		$this->isError	=	true;
		return $this;
	}
	
	public function isError(){
		return $this->isError;
	}
	
}
?>