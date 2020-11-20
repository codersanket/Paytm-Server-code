<?php
/*
* import checksum generation utility
* You can get this utility from https://developer.paytm.com/docs/checksum/
*/
require("PaytmChecksum.php");

$mid=$_POST["mid"];
$orderId=$_POST["ORDERID"];
$amount=$_POST["amount"];
$key=$_POST["mkey"];
$custId=$_POST["custId"];
$url=$_POST["url"];
$websiteName=$_POST["web"];


// $mid="zlsjBq52825503339453";


$paytmParams = array();

$paytmParams["body"] = array(
"requestType" => "Payment",
"mid" =>$mid,
"websiteName" =>$websiteName,
"orderId"  =>$orderId,
"callbackurl" =>"https://merchant.com/callback",
"txnAmount" => array(
"value"=>$amount,
"currency" => "INR"
 ),
"userInfo" => array(
"custId" =>$custId
 )
);


$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"],JSON_UNESCAPED_SLASHES),$key);
$paytmParams["head"] = array(
"signature" => $checksum 
);

$post_data =json_encode($paytmParams,JSON_UNESCAPED_SLASHES);

/* for Staging */



 $url = "$url/theia/api/v1/initiateTransaction?mid=$mid&orderId=$orderId";

$ch =curl_init($url);

curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
$resp=curl_exec($ch);
print($resp);



?>