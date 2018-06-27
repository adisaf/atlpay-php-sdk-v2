<?php
namespace ATLPay;
use ATLPay\ATLPay;
class Request{
	
	public $httpStatus			=	NULL;
	public $responseBody		=	NULL;
	public $isCurlError			=	false;
	public $curlErrorCode		=	NULL;
	public $curlErrorMessage	=	NULL;
	public $errorMessage		=	NULL;
	
	//Function to send request to ATLPay Servers, Returns NULL
	public function sendPayload($method, $endPoint, $payload=[], $headers = [], $debug = false){
		$headers	+=	[
			'X-API-Key:'.ATLPay::getSecretKey()
		];
		$curlHandle = curl_init(); 
		curl_setopt($curlHandle, CURLOPT_URL, $endPoint); 
		curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($curlHandle, CURLOPT_TIMEOUT, ATLPay::getTimeout()); 
		curl_setopt($curlHandle, CURLOPT_SSLVERSION , ATLPay::getSSLVersion()); 
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true); 
		if(ATLPay::getSSLVerifyStatus() === true){
			curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, true);
		}else{
			curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
		}
		if($method == "POST"){
			curl_setopt($curlHandle, CURLOPT_POST, true); 
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($payload));
		}
		if($debug === true){
			curl_setopt($curlHandle, CURLOPT_VERBOSE, true);
			$verbose = fopen('php://temp', 'w+');
			curl_setopt($curlHandle, CURLOPT_STDERR, $verbose);
		}
		$this->responseBody	=	curl_exec($curlHandle); 
		if($debug === true){
			rewind($verbose);
			$verboseLog = stream_get_contents($verbose);
			echo "Verbose Information:\n<pre>", htmlspecialchars($verboseLog), "</pre>";
		}
		if($this->responseBody === false){
			$this->curlErrorCode	=	curl_errno($curlHandle);
			$this->curlErrorMessage	=	curl_error($curlHandle);
		}else{
			$this->httpStatus = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
		}
		curl_close($curlHandle); 
	}
}
?>