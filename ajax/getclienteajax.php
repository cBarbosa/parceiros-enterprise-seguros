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

// Par�metros
$cpf	= $_POST["cpfcliente"];

$obj = new EnterpriseWS();
$obj->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);

$xml = "<sgsm:GetClienteCadastro>
			<sgsm:cpfcnpj>$cpf</sgsm:cpfcnpj>
		</sgsm:GetClienteCadastro>";
try {
	$response = $obj->GetClienteCadastroNOSHOW($xml);
	$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
	$response = simplexml_load_string($response);
var_dump($response);
exit;
	if(isset($response->GetClienteCadastroResponse->GetClienteCadastroResult->CodigoRetorno)
		&& strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->CodigoRetorno)=="0")
	{
		$retorno = new ClienteVO();
		$retorno->nome = strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->Cliente->Nome);
		$retorno->cpf = strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->Cliente->CPF);
		$retorno->email = strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->Cliente->Email);

		$retorno->estadocivil = strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->DadosCadastrais->EstadoCivil);
		$retorno->sexo = strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->DadosCadastrais->Sexo);
		$retorno->datanascimento = strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->DadosCadastrais->DataNascimento);
		if($retorno->datanascimento!="")
		{
			$var = explode("T", $retorno->datanascimento);
			$var = explode("-", $var[0]);
			$retorno->datanascimento = $var[2].'/'. $var[1] .'/'. $var[0];
		}
		$retorno->rg = strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->DadosCadastrais->NumeroRG);
		$retorno->orgaoexpeditor = strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->DadosCadastrais->OrgaoExpedidor);
		$retorno->idufrg = strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->DadosCadastrais->IDUFOrgaoExpedidor);
		$retorno->dataexpedicaorg = strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->DadosCadastrais->DataExpedicao);
		if($retorno->dataexpedicaorg!="")
		{
			$var = explode("T", $retorno->dataexpedicaorg);
			$var = explode("-", $var[0]);
			$retorno->dataexpedicaorg = $var[2].'/'. $var[1] .'/'. $var[0];
		}

		$arrayEnderecos = $response->GetClienteCadastroResponse->GetClienteCadastroResult->ListaEndereco;
		$i = 0;

		foreach ($arrayEnderecos as $end)
		{
			$arr = array("tipo" => strval($end->TipoEndereco),
				"cep" => strval($end->CEP),
				"logradouro" => strval($end->Logradouro),
				"complemento" => strval($end->Complemento),
				"numero" => strval($end->Numero),
				"cidade" => strval($end->Cidade),
				"bairro" => strval($end->Bairro),
				"iduf" => strval($end->IDUF)
			);
			$retorno->ends[++$i] = $arr;
		}
		$json_ret =	array("CodRetorno"=> "0",
					"Mensagem" => strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->MensagemAmigavel),
					"cliente" => $retorno);
		echo json_encode($json_ret);
	} else {
		$json_str =	array("CodRetorno"=> "99",
					"Mensagem" => strval($response->GetClienteCadastroResponse->GetClienteCadastroResult->MensagemAmigavel));
		echo json_encode($json_str);
	}
} catch (Exception $ex) {
	echo json_encode(false);
}

class ClienteVO
{
	var $nome;
	var $cpf;
	var $email;
	
	var $estadocivil;
	var $sexo;
	var $datanascimento;
	var $rg;
	var $orgaoexpeditor;
	var $dataexpedicaorg;
	
	var $ends;
}
?>