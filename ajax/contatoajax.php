<?php
session_start();
header('Content-type: text/json');
header('Content-type: application/json');

require_once('../inc/EnterpriseWS.php'); 

if(!isset($_SESSION['user']))
{
	echo json_encode(false);
	exit;
}

$evento			= $_POST["val-evento"];
$valor			= str_replace(",", ".", $_POST["val-vingresso"]);
$valor			= strpos($valor, ".") > 0 ? $valor : $valor.".0";
$ingresso		= $_POST["val-ingresso"];

// Parâmetros
$cpfCliente			= $_POST["val-cpfcliente"];

$xml = "<sgsm:GetContatoByCPF>
			<sgsm:CGCCPF>$cpfCliente</sgsm:CGCCPF>
		</sgsm:GetContatoByCPF>";

$obj = new EnterpriseWS();
$obj->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);

try {
	$response = $obj->GetContatoByCPFNOSHOW($xml);
	//var_dump($response);exit;
	
	$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
	$response = simplexml_load_string($response);
	if(isset($response->GetContatoByCPFResponse->GetContatoByCPFResult->GetContatoByCPFRetorno->CodRetorno))
	{
		$json_str =array("CodRetorno"=>strval($response->GetContatoByCPFResponse->GetContatoByCPFResult->GetContatoByCPFRetorno->CodRetorno),
										"Mensagem"=>strval($response->GetContatoByCPFResponse->GetContatoByCPFResult->RetornoOperacao->DescricaoRetorno),
										"NumeroIngresso"=>strval($response->GetContatoByCPFResponse->GetContatoByCPFResult->RetornoOperacao->NumeroIngresso),
										"NumeroSorte"=>strval($response->GetContatoByCPFResponse->GetContatoByCPFResult->RetornoOperacao->NumeroSorte),
										"NumeroProposta"=>strval($response->GetContatoByCPFResponse->GetContatoByCPFResult->RetornoOperacao->NumeroProposta)
									);
		if(strval($response->GetContatoByCPFResponse->GetContatoByCPFResult->RetornoOperacao->CodRetorno)!="0")
		{
			$json_str =array("CodRetorno"=> "99",
									"Mensagem"=>"Erro acessando o serviço");
		}
	} else {
		$json_str =array("CodRetorno"=> "99",
									"Mensagem"=>"Erro acessando o serviço");
	}
	$jobj = json_encode($json_str);
	echo "$jobj";
}  catch (Exception $e) {
	echo "Exceção pega: ",  $e->getMessage(), "\n";
}
?>