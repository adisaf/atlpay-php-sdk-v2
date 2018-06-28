<?php
session_start();
require_once("../vendor/autoload.php");
require_once("functions.php");
\ATLPay\ATLPay::setSecretKey('29341b455857e4081dded5a14ec598d54676aa75');
$refund	=	new \ATLPay\Charge();
$refund->refund("J2018062881756"); // FUll Refund
//$refund->refund("J2018062881756", 10); // Partial Refund
if($refund->isError()){
	debug($refund);
}else{
 	$refundId		=	$refund->getId();
	$refundAmount	=	$refund->getAmount();
	$refundFees	=	$refund->getFees();
	$transactionMode	=	$refund->getMode();
	echo "Refund ID : <strong>".$refundId."</strong><br />";
	echo "Refund Amount : <strong>".$refundAmount."</strong><br />";
	echo "Refunded Fees : <strong>".$refundFees."</strong><br />";
	echo "Mode : <strong>".$transactionMode."</strong><br />";
}
?>