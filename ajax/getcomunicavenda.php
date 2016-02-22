<?php
session_start();
header('Content-type: text/json');
header('Content-type: application/json');

require_once('../inc/EnterpriseWS.php'); 

if(!isset($_SESSION['user']))
{
	$json_str = array("CodRetorno"=> "99",
				"Mensagem"=>"Erro acessando o serviço");
	echo json_encode($json_str);
	exit;
}
$cpf			= $_POST["val-cpfcliente"];
$certificado	= $_POST["val-certificado"];

$xml = "<sgsm:GetComunicacaoVendaDireta>
			<sgsm:cpf>$cpf</sgsm:cpf>
			<sgsm:certificado>$certificado</sgsm:certificado>
		</sgsm:GetComunicacaoVendaDireta>";

$obj = new EnterpriseWS();
$obj->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);

try {
	$response = $obj->GetComunicacaoVendaDiretaNOSHOW($xml);
	//var_dump($response);exit;
	
	$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
	$response = simplexml_load_string($response);
	if(isset($response->GetComunicacaoVendaDiretaResponse->GetComunicacaoVendaDiretaResult->CodigoRetorno))
	{
		$json_str = array("CodRetorno"=>strval($response->GetComunicacaoVendaDiretaResponse->GetComunicacaoVendaDiretaResult->CodigoRetorno),
										"Mensagem"=>strval($response->GetComunicacaoVendaDiretaResponse->GetComunicacaoVendaDiretaResult->MensagemAmigavel));
		if(strval($response->GetComunicacaoVendaDiretaResponse->GetComunicacaoVendaDiretaResult->CodigoRetorno)!="0")
		{
			$json_str =array("CodRetorno"=> "99",
									"Mensagem"=>strval($response->GetComunicacaoVendaDiretaResponse->GetComunicacaoVendaDiretaResult->MensagemAmigavel));
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