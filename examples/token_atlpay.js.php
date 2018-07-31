<?php 
session_start();
require_once("../vendor/autoload.php");
require_once("functions.php");
if(isset($_POST) && !empty($_POST) && @$_GET["_cmd"] == "processPayment"){
	\ATLPay\ATLPay::setSecretKey('YOUR_SECRET_KEY');
	$token	=	new \ATLPay\Token();
	$token->getToken($_POST["atlpayToken"]);
	if($token->isError()){
		if(in_array($token->httpCode, [500, 502, 503, 504])){
			die("Something went wrong on ATLPay's end. (These are rare.)");
		}else if($token->httpCode == 400){
			die($token->message);
		}else if($token->httpCode == 401){
			die("Check your API Key");
		}else if($token->httpCode == 402){
			die("You may encounter this error if you're not using TLS_1_2");
		}else if($token->httpCode == 403){
			die("Check your API Key");
		}else if($token->httpCode == 404){
			die("Token Not Found.");
		}
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
		echo "Mode : <strong>".$transactionMode."</strong><br />";
		echo "Now Create the charge and redirect the user for 3D Authentication or Capture the charge";
		exit;
	}
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ATLPay JS</title>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://www.atlpay.com/js/ATLPay.js"></script>
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<style>
body{
	font-family: 'Roboto', sans-serif;
	font-size:13px;
	letter-spacing: 0.75px;
}
img.atl-powered {
    max-width: 80px;
}
img.card-brand{
	max-width:60px;
}
.card-brands{
	margin-top:15px;
}
.card-brands{
	text-align:center;
}
.card-addon{
	background-color:#FFF;
	border-left:none;
	padding: 2px 10px;
}
.card-addon img{
	max-width:48px;
}
.form-control{
	height:40px;
}
#payment-form .panel-body{
	padding:25px 30px;
}
.note{
	font-size:12px;
}
.card-number{
	position:relative;
	width:100%;
	display:block;
}
.card-number::after{
	content: "";
    position: absolute;
    display: inline-block;
    top: 7px;
    right: 10px;
    width: 42px;
    height: 28px;
}
.card-number.visa::after{
	background:url('img/visa.svg');
	background-size: contain;
}
.card-number.mastercard::after{
	background:url('img/mastercard.svg');
	background-size: contain;
}
.card-number.maestro::after{
	background:url('img/maestro.svg');
	background-size: contain;
}
.error{
	font-size:12px;
	margin-top:5px;
	text-align:justify;
	color:#F00;
}
</style>
</head>

<body>
<div class="container">
  <div class="row"> 
    <div class="col-xs-12 col-md-4 col-sm-4 col-md-offset-4 col-sm-offset-4"> 
      <div class="form-group">&nbsp;</div>
      <div class="form-group">&nbsp;</div>
      <div class="form-group">&nbsp;</div>
      <form role="form" id="cart-form" method="POST" action="token_atlpay.js.php?_cmd=processPayment" autocomplete="off">
      </form>
      <form role="form" id="payment-form" method="POST" action="javascript:void(0)" autocomplete="off">
      <div class="panel panel-default">
        <div class="panel-body">          
            <div class="form-group">
              <label class="control-label">CARD NUMBER<span class="text-danger">*</span></label>
              <div class="card-number">
                  <input 
                    type="text"
                    class="form-control"
                    placeholder="XXXX XXXX XXXX XXXX"
                    id="cc-number"
                    required autofocus
                    autocomplete="cc-number"
                  />
              </div>
              <div class="error"></div>
            </div>
            <div class="row">
              <div class="col-xs-6 col-md-6">
                <div class="form-group">
                  <label class="control-label">
                  	<span class="hidden-xs">EXPIRY</span>
                    <span class="visible-xs-inline">EXP</span> DATE<span class="text-danger">*</span>
                  </label>
                  <input 
                    type="text" 
                    class="form-control" 
                    placeholder="MM/YYYY"
                    id="cc-exp"
                    autocomplete="cc-exp"
                    maxlength="7"
                    required
                  />
                  <div class="error"></div>
                </div>
              </div>
              <div class="col-xs-6 col-md-6 pull-right">
                <div class="form-group">
                  <label class="control-label">CVC<span class="text-danger">*</span></label>
                  <input 
                    type="password" 
                    class="form-control"
                    placeholder="CVC"
                    id="cc-cvc"
                    autocomplete="cc-cvc"
                    maxlength="4"
                    required
                   />
                   <div class="error"></div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label">NAME ON CARD<span class="text-danger">*</span></label>
              <input 
              	type="text"
                class="form-control" 
                id="cc-holder" 
                autocomplete="cc-holder"
                required
              />
              <div class="error"></div>
            </div>
            <p class="text-center note">This charge will appear as <strong>ATL Pay + 44 (0) 02 3137 8850</strong> on your card statement.</p>
            <button id="pay-btn" class="btn btn-success btn-block" type="submit"><i class="fa fa-lock"></i> Pay Securely</button>
            <div class="card-brands form-group">
            	<img src="img/visa.svg" class="card-brand" />
                <img src="img/mastercard.svg" class="card-brand" />
                <img src="img/maestro.svg" class="card-brand" />
                <img src="img/atlpay_powered.png" class="atl-powered" />
            </div>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
<script>

var atlPay	=	new ATLPay;
atlPay.setPublicKey("ATLPay Public KEY HERE");

var jqDate = document.getElementById('cc-exp');
$(jqDate).on('keyup keydown', function(e){
	var value_exp = $(jqDate).val();
	var v = value_exp.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
	var matches = v.match(/\d{2,4}/g);
	if(e.which !== 8) {
		var numChars = value_exp.length;
		if(numChars === 2){
			var thisVal = value_exp;
			thisVal += '/';
			$(jqDate).val(thisVal);
		}
		if (numChars === 7)
			return false;
	}
});

document.getElementById('cc-number').oninput = function() {
	this.value = atlPay.formatCard(this.value);
	var cardType = atlPay.lookupCardType(this.value);
	if (cardType != "unknown") {
		$(".card-number").addClass(cardType);
	} else {
		$(".card-number").removeClass("visa mastercard maestro");
	}
}

function atlPayResponseHandler(response, statusCode) {
	$("#cc-number").parents(".form-group").eq(0).find(".error").html("");
	$("#cc-exp").parents(".form-group").eq(0).find(".error").html("");
	$("#cc-cvc").parents(".form-group").eq(0).find(".error").html("");
	$("#cc-holder").parents(".form-group").eq(0).find(".error").html("");
	
	$("#cc-number").parents(".form-group").eq(0).removeClass("has-error");
	$("#cc-exp").parents(".form-group").eq(0).removeClass("has-error");
	$("#cc-cvc").parents(".form-group").eq(0).removeClass("has-error");
	$("#cc-holder").parents(".form-group").eq(0).removeClass("has-error");
	if(statusCode == 200){
		var paymentForm	=	document.getElementById("cart-form");
		var tokenElement	=	document.createElement("input");
		tokenElement.type = "hidden";
		tokenElement.name	=	"atlpayToken";
		tokenElement.value	=	response.id;
		paymentForm.append(tokenElement);
		paymentForm.submit();
	}else{
		if(typeof response.error != 'undefined'){
			if(statusCode == 400){
				if(typeof response.error != "undefined"){
					if(response.error.parameter == "card.number"){
						$("#cc-number").parents(".form-group").eq(0).addClass("has-error");
						$("#cc-number").parents(".form-group").eq(0).find(".error").html(response.error.message);
					}else if(response.error.parameter == "card.exp_month"){
						$("#cc-exp").parents(".form-group").eq(0).addClass("has-error");
						$("#cc-exp").parents(".form-group").eq(0).find(".error").html(response.error.message);
					}else if(response.error.parameter == "card.exp_year"){
						$("#cc-exp").parents(".form-group").eq(0).addClass("has-error");
						$("#cc-exp").parents(".form-group").eq(0).find(".error").html(response.error.message)
					}else if(response.error.parameter == "card.cvc"){
						$("#cc-cvc").parents(".form-group").eq(0).addClass("has-error");
						$("#cc-cvc").parents(".form-group").eq(0).find(".error").html(response.error.message)
					}else if(response.error.parameter == "card.name"){
						$("#cc-holder").parents(".form-group").eq(0).addClass("has-error");
						$("#cc-holder").parents(".form-group").eq(0).find(".error").html(response.error.message)
					}
				}
			}else{
				
			}
		}
		document.getElementById("pay-btn").removeAttribute("disabled");
		document.getElementById("pay-btn").innerHTML = '<i class="fa fa-lock"></i> Pay Securely';
	}
	
}

document.getElementById("payment-form").addEventListener("submit", function(e){
	e.preventDefault();
	e.stopPropagation();
	document.getElementById("pay-btn").innerHTML	=	'<i class="fa fa-spin fa-spinner"></i> Processing Securely...';
	document.getElementById("pay-btn").setAttribute("disabled", "disabled");	
	var exp_month = $('#cc-exp').val();
	var exp_month_calc = exp_month.substring(0, 2);
	var exp_year = $('#cc-exp').val();
	var exp_year_calc = exp_year.substring(3);
	atlPay.createToken({
		card : {
			'number' : document.getElementById("cc-number").value,
			'exp_month' : exp_month_calc,
			'exp_year' : exp_year_calc,
			'cvc' : document.getElementById("cc-cvc").value,
			'name' : document.getElementById("cc-holder").value
		},
		shopper : {
			'ip' : '<?php echo $_SERVER['REMOTE_ADDR']; ?>',
			'session_id' : '<?php echo session_id(); ?>',
			'email' : '<?php echo 'customer@email.com'; ?>'
		}
	}, atlPayResponseHandler);
});

</script>
</body>
</html>