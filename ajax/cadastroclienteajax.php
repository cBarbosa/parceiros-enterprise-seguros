<?php
session_start();
require_once('../inc/EnterpriseWS.php');
header('Content-type: text/json');
header('Content-type: application/json');

if(!isset($_SESSION['user']))
{
	echo json_encode(false);
	exit;
}
//var_dump($_POST);exit;

// Dados Pessoais
$email			= $_POST["val-mail"];
$nome			= $_POST["val-nome"];
$cpfCliente		= $_POST["val-cpfcliente"];
$datanascimento = explode('/', $_POST["val-nascimento"]);
$datanascimento = $datanascimento[2] .'-'. $datanascimento[1] .'-'. $datanascimento[0] ."T00:00:00";
$estCivil		= $_POST["val-estadocivil"];
$sexo			= $_POST["val-sexo"];
$rg				= $_POST["val-documento"];
$expeditor		= $_POST["val-expeditor"];
$ufexpeditor	= $_POST["val-ufexpeditor"];
if(!isset($_POST["val-dataexpedicao"]))
{
	if($_POST["val-dataexpedicao"]!='')
	{
		$dataexpedicao	= explode('/', $_POST["val-dataexpedicao"]);
		$dataexpedicao	= $dataexpedicao[2] .'-'. $dataexpedicao[1] .'-'. $dataexpedicao[0] ."T00:00:00";
	} else
		$dataexpedicao = '';
} else 
	$dataexpedicao = '';

// Dados de endere�o
$logradouro		= $_POST["val-logradouro"];
$complemento	= $_POST["val-logradouro2"];
$numero			= $_POST["val-logradouron"];
$bairro			= $_POST["val-logradourob"];
$cidade			= $_POST["val-logradouroc"];
$cep			= $_POST["val-cep"];
$uf				= $_POST["val-uf"];

$xml = "<sgsm:CadastroClienteContato>
         <sgsm:cliente>";

if($email!="")
	$xml .= "<sgsm:Email>$email</sgsm:Email>";
$xml .= "<sgsm:Nome>$nome</sgsm:Nome>
            <sgsm:CPF>$cpfCliente</sgsm:CPF>
            <sgsm:EstadoCivil>$estCivil</sgsm:EstadoCivil>
            <sgsm:Sexo>$sexo</sgsm:Sexo>
            <sgsm:DataNascimento>$datanascimento</sgsm:DataNascimento>";
if($rg != "")
	$xml .= "<sgsm:NumeroRG>$rg</sgsm:NumeroRG>";
if($expeditor != "")
	$xml .= "<sgsm:OrgaoExpedidor>$expeditor</sgsm:OrgaoExpedidor>";
if($dataexpedicao != "")
	$xml .= "<sgsm:DataExpedicao>$dataexpedicao</sgsm:DataExpedicao>";
if($ufexpeditor != "")
	$xml .= "<sgsm:IDUFOrgaoExpedidor>$ufexpeditor</sgsm:IDUFOrgaoExpedidor>";
$xml .= "<sgsm:TipoEndereco>2</sgsm:TipoEndereco>
            <sgsm:Logradouro>$logradouro</sgsm:Logradouro>
            <sgsm:Bairro>$bairro</sgsm:Bairro>
            <sgsm:Cidade>$cidade</sgsm:Cidade>
            <sgsm:CEP>$cep</sgsm:CEP>
            <sgsm:Numero>$numero</sgsm:Numero>
            <sgsm:Complemento>$complemento</sgsm:Complemento>
            <sgsm:IDUF>$uf</sgsm:IDUF>
         </sgsm:cliente>
      </sgsm:CadastroClienteContato>";
//var_dump($xml);exit;

$obj = new EnterpriseWS();
$obj->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);

try {
	$response = $obj->CadastroClienteContatoNOSHOW($xml);
	$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
	$response = simplexml_load_string($response);
//var_dump($response);exit;
	if(isset($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->CodigoRetorno)
		&& strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->CodigoRetorno)=="0")
	{
		$retorno = new ClienteVO();
		$retorno->nome = strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->Cliente->Nome);
		$retorno->cpf = strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->Cliente->CPF);
		$retorno->email = strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->Cliente->Email);

		$retorno->estadocivil = strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->DadosCadastrais->EstadoCivil);
		$retorno->sexo = strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->DadosCadastrais->Sexo);
		$retorno->datanascimento = strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->DadosCadastrais->DataNascimento);
		if($retorno->datanascimento!="")
		{
			$var = explode("T", $retorno->datanascimento);
			$var = explode("-", $var[0]);
			$retorno->datanascimento = $var[2].'/'. $var[1] .'/'. $var[0];
		}
		$retorno->rg = strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->DadosCadastrais->NumeroRG);
		$retorno->orgaoexpeditor = strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->DadosCadastrais->OrgaoExpedidor);
		$retorno->idufrg = strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->DadosCadastrais->IDUFOrgaoExpedidor);
		$retorno->dataexpedicaorg = strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->DadosCadastrais->DataExpedicao);
		if($retorno->dataexpedicaorg!="")
		{
			$var = explode("T", $retorno->dataexpedicaorg);
			$var = explode("-", $var[0]);
			$retorno->dataexpedicaorg = $var[2].'/'. $var[1] .'/'. $var[0];
		}

		$arrayEnderecos = $response->CadastroClienteContatoResponse->CadastroClienteContatoResult->ListaEndereco;
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
					"Mensagem" => strval($response->CadastroClienteContatoResponse->CadastroClienteContatoResult->MensagemAmigavel),
					"cliente" => $retorno);
		echo json_encode($json_ret);
	} else {
		$json_str =	array("CodRetorno"=> "99",
					"Mensagem" => "Erro acessando o servi�o");
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