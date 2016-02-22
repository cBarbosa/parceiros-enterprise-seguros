<?php
session_start();
require_once('../inc/EnterpriseWS.php');

if(!isset($_SESSION['user']))
{
	echo false;
	exit;
}

$cpf			= $_GET["val-cpfcliente"];
$certificado	= $_GET["val-certificado"];

if(!isset($_GET["val-cpfcliente"]) || !isset($_GET["val-certificado"]))
{
	header("Content-Type: text/html; charset=utf-8");
	echo("<ul>");
	echo("<li>Cod 99</li>");
	echo("<li>Não foi possível gerar o documento de boas vindas.</li>");
	echo("</ul>");
	exit;
}

$xml = "<sgsm:GetBoasVindasPDFByCertificado>
			<sgsm:cpf>$cpf</sgsm:cpf>
			<sgsm:certificado>$certificado</sgsm:certificado>
		</sgsm:GetBoasVindasPDFByCertificado>";

$obj = new EnterpriseWS();
$obj->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);

try {
	$response = $obj->GetBoasVindasPDFByCertificadoNOSHOW($xml);
	$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
	$response = simplexml_load_string($response);
	//var_dump(strval($response->GetBoasVindasPDFByCertificadoResponse));exit;
	if(isset($response->GetBoasVindasPDFByCertificadoResponse->GetBoasVindasPDFByCertificadoResult->CodigoRetorno))
	{
		if(strval($response->GetBoasVindasPDFByCertificadoResponse->GetBoasVindasPDFByCertificadoResult->CodigoRetorno)=="0")
		{
			header("Content-type: application/pdf");
			header("Content-Disposition: attachment; filename=boasvindas_$certificado.pdf");
			$base64 = strval($response->GetBoasVindasPDFByCertificadoResponse->GetBoasVindasPDFByCertificadoResult->Documento);
			$binary = base64_decode($base64);
			file_put_contents("boasvindas_$certificado.pdf", $binary);
			echo $binary;
		} else {
			header("Content-Type: text/html; charset=utf-8");
			echo("<ul>");
			echo("<li>COD: ".strval($response->GetBoasVindasPDFByCertificadoResponse->GetBoasVindasPDFByCertificadoResult->CodigoRetorno)."</li>");
			echo("<li>".strval($response->GetBoasVindasPDFByCertificadoResponse->GetBoasVindasPDFByCertificadoResult->MensagemAmigavel)."</li>");
			echo("</ul>");
		}
	} else {
		header("Content-Type: text/html; charset=utf-8");
		echo("<ul>");
		echo("<li>Cod 99</li>");
		echo("<li>Não foi possível gerar o arquivo de boas vindas.</li>");
		echo("</ul>");
	}
}  catch (Exception $e) {
	header("Content-Type: text/html; charset=utf-8");
	echo "Exceção: ",  $e->getMessage(), "\n";
}
?>