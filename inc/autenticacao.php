<?phprequire_once('EnterpriseWS.php');
class Autenticacao{var $user;var $pass;
	function ValidaLogin()	{		unset($_SESSION['user']);
		$xml = "<sgsm:ValidaUsuarioParceiro>				<sgsm:cpfcnpj>$this->user</sgsm:cpfcnpj>				<sgsm:senha>$this->pass</sgsm:senha>			</sgsm:ValidaUsuarioParceiro>";		$wss = new EnterpriseWS();		$response = $wss->ValidaParceiroNOSHOW($xml);		$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));		$response = simplexml_load_string($response);
		if(isset($response->ValidaUsuarioParceiroResponse->ValidaUsuarioParceiroResult->CodigoRetorno)			&& strval($response->ValidaUsuarioParceiroResponse->ValidaUsuarioParceiroResult->CodigoRetorno)=="0")		{			$_SESSION['tmp']->iduser = strval($response->ValidaUsuarioParceiroResponse->ValidaUsuarioParceiroResult->Usuario->ID);			$_SESSION['tmp']->nome = strval($response->ValidaUsuarioParceiroResponse->ValidaUsuarioParceiroResult->Usuario->NomeExibir);			$_SESSION['tmp']->email = strval($response->ValidaUsuarioParceiroResponse->ValidaUsuarioParceiroResult->Usuario->Email);			$_SESSION['tmp']->idempresa = strval($response->ValidaUsuarioParceiroResponse->ValidaUsuarioParceiroResult->Usuario->IDEmpresaComercializadora);			$_SESSION['tmp']->loginname = $this->user;			$_SESSION['tmp']->password = $this->pass;			return $_SESSION['tmp'];		} else {			return null;		}	}}
?>