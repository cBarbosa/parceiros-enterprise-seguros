<?php
if(!isset($_SESSION['user']))
if(isset($_POST["val-tipoingresso"]))
$obj = new EnterpriseWS();
try {
	$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
?>