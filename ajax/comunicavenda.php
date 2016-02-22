<?php
session_start();
header('Content-type: text/json');
header('Content-type: application/json');

require_once('../inc/EnterpriseWS.php'); 

if(!isset($_SESSION['user']))
{
	$json_str =array("CodRetorno"=> "99",
				"Mensagem"=>"Erro acessando o serviço");
	echo json_encode($json_str);
	exit;
}
$email			= $_POST["val-email"];
$cpf			= $_POST["val-cpfcliente"];
$certificado	= $_POST["val-certificado"];

$xml = "<sgsm:SendCertificadoByEmail>
			<sgsm:cpf>$cpf</sgsm:cpf>
			<sgsm:certificado>$certificado</sgsm:certificado>
			<sgsm:email>$email</sgsm:email>
		</sgsm:SendCertificadoByEmail>";

$obj = new EnterpriseWS();
$obj->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);

try {
	$response = $obj->SendCertificadoByEmailNOSHOW($xml);
	//var_dump($response);exit;
	
	$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
	$response = simplexml_load_string($response);
	if(isset($response->SendCertificadoByEmailResponse->SendCertificadoByEmailResult->CodigoRetorno))
	{
		$json_str = array("CodRetorno"=>strval($response->SendCertificadoByEmailResponse->SendCertificadoByEmailResult->CodigoRetorno),
										"Mensagem"=>strval($response->SendCertificadoByEmailResponse->SendCertificadoByEmailResult->MensagemAmigavel));
		if(strval($response->SendCertificadoByEmailResponse->SendCertificadoByEmailResult->CodigoRetorno)!="0")
		{
			$json_str =array("CodRetorno"=> "99",
									"Mensagem"=>strval($response->SendCertificadoByEmailResponse->SendCertificadoByEmailResult->MensagemAmigavel));
		}
	} else {
		$json_str =array("CodRetorno"=> "99",
									"Mensagem"=>"Erro acessando o serviço");
	}
	echo json_encode($json_str);
}  catch (Exception $e) {
	$json_str =array("CodRetorno"=> "99",
				"Mensagem"=> $e->getMessage());
	echo json_encode($json_str);
	exit;
}
?>