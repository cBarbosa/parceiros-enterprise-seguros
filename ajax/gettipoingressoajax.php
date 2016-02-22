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

// Parâmetros
$evento	= $_POST["idevento"];

$obj = new EnterpriseWS();
$obj->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);

$xml = "<sgsm:GetTiposIngressoByIDEvento>
			<sgsm:IDEvento>$evento</sgsm:IDEvento>
		</sgsm:GetTiposIngressoByIDEvento>";

try {
	$response = $obj->GetTiposIngressoByIDEventoNOSHOW($xml);
	$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
	$response = simplexml_load_string($response);

	if(isset($response->GetTiposIngressoByIDEventoResponse->GetTiposIngressoByIDEventoResult->CodigoRetorno)
		&& strval($response->GetTiposIngressoByIDEventoResponse->GetTiposIngressoByIDEventoResult->CodigoRetorno)=="0")
	{
		$retorno = new TipoIngressoVO();
		$arrayConfigs = $response->GetTiposIngressoByIDEventoResponse->GetTiposIngressoByIDEventoResult->ConfiguracaoEvento;
		$i = 0;
		foreach ($arrayConfigs as $cfgs)
		{
			if($_SESSION['user']->idempresa != strval($cfgs->IDEmpresaComercializadora))
			{
				continue;
			}

			$arr = array("id" => strval($cfgs->TipoIngresso->ID),
				"percentual" => strval($cfgs->PercentualPremio),
				"descricao" => strval($cfgs->TipoIngresso->Descricao)
			);
			$retorno->arrs[++$i] = $arr;
		}
		if($i>0){
			$retorno->codigo = 0;
			$retorno->mensagem = 'Consulta realizada com sucesso.';
		} else {
			$retorno->codigo = 99;
			$retorno->mensagem = 'Erro consultando tipos de ingresso.';
			$retorno->arrs = null;
		}
	} else {
		$retorno = new TipoIngressoVO();
		$retorno->codigo = 99;
		$retorno->mensagem = 'Erro consultando tipos de ingresso.';
		$retorno->arrs = null;
	}
}catch (Exception $ex) {
	echo json_encode(false);
}
echo json_encode($retorno);

class TipoIngressoVO
{
	var $codigo;
	var $mensagem;

	var $arrs;
}
?>