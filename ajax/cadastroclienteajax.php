<?php
if(!isset($_SESSION['user']))
$obj = new EnterpriseWS();
try {
	$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
?>