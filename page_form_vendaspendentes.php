<?php
session_start();
require_once 'inc/config.php';
require_once 'inc/EnterpriseWS.php';

if(!isset($_SESSION['user']))
{
	header('location: index.php');
}

$template['header_link'] = 'PENDENTES DE DOCUMENTAÇÃO';

if(isset($_POST['data-inicio']))
{
	$dataInicio	= date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data-inicio'])));
	$dataFim	= date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data-fim'])));
	$evento		= $_POST["cmbo-evento"];
}else {
	$dataInicio = date('Y-m-d', strtotime("-90 days"));	$_POST['data-inicio'] = date('d/m/Y', strtotime("-90 days"));
	$dataFim = date('Y-m-d');	$_POST['data-fim'] = date('d/m/Y');
	$evento = 0;
}

include 'inc/template_start.php';
include 'inc/page_head.php';

$arrayCertificados = null;
$descricaoErro = null;

$obj = new EnterpriseWS();
$obj->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);
$response = $obj->ListaEventosByOperadoraNOSHOW("<sgsm:ListaEventosByOperadora/>");
$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
$response = simplexml_load_string($response);

if(isset($response->ListaEventosByOperadoraResponse))	{
	$arrayEventos = $response->ListaEventosByOperadoraResponse->ListaEventosByOperadoraResult->EventoModel;
} else {
	$arrayEventos = null;
}
unset($obj);

if(isset($dataInicio) && isset($dataFim))
{
	$obj1 = new EnterpriseWS();
	$obj1->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);

	$xml = "<sgsm:GetCertificadosPendentes>
				<sgsm:DataInicio>{$dataInicio}T00:00:00</sgsm:DataInicio>
				<sgsm:DataFim>{$dataFim}T00:00:00</sgsm:DataFim>";
	if($evento!='' && $evento!='0')
		$xml .= "<sgsm:IDEvento>$evento</sgsm:IDEvento>";
	$xml .= "</sgsm:GetCertificadosPendentes>";

	$response = $obj1->GetCertificadosPendentesNOSHOW("$xml");
	$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
	$response = simplexml_load_string($response);
	if(isset($response->GetCertificadosPendentesResponse->GetCertificadosPendentesResult->VendaDiretaHistoricoRetorno)
		&& strval($response->GetCertificadosPendentesResponse->GetCertificadosPendentesResult->VendaDiretaHistoricoRetorno->CodigoRetorno)=="0")
	{
		$arrayCertificados = $response->GetCertificadosPendentesResponse->GetCertificadosPendentesResult->VendaDiretaHistoricoRetorno->ListaHistorico;
	} else {
		$descricaoErro = utf8_decode(strval($response->GetCertificadosPendentesResponse->GetCertificadosPendentesResult->VendaDiretaHistoricoRetorno->MensagemAmigavel));
	}
	unset($obj1);
}
//var_dump($arrayCertificados);exit;
?>

<!-- Page content -->
<div id="page-content">
    <!-- Table Styles Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Relatório Certificados Pendentes de Envio à Corretora</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li>Home</li>
                        <li>Controle</li>
                        <li><a href="page_form_vendasefetuadas.php">Certificados Pendentes</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Table Styles Header -->
	
	<!-- Danger Alert -->
	<div class="alert alert-danger alert-dismissable" id="div_erro">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h4><strong>Resultado da consulta</strong></h4>
		<p>Nenhum registro informado para o filtro selecionado!</p>
		<p><?//=$descricaoErro;?></p>
	</div>
	<!-- END Danger Alert -->

    <!-- Datatables Block -->
    <!-- Datatables is initialized in js/pages/uiTables.js -->
    <div class="block full">
        <div class="block-title">
            <h2>Resultado no período <?php if(isset($_POST['data-inicio'])) echo("[de {$_POST['data-inicio']} até {$_POST['data-fim']}]");?></h2>
        </div>
		 <!-- Inline Form Block -->
            <div class="block full">
                <!-- Inline Form Title -->
				<!--
                <div class="block-title">
                    <h2>Inline Form</h2>
                </div>
				-->
                <!-- END Inline Form Title -->

                <!-- Inline Form Content -->
                <form action="page_form_vendaspendentes.php" method="post" class="form-inline" >
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="data-inicio">período</label>
                        <div class="col-md-9">
                            <div class="input-group input-daterange" data-date-format="dd/mm/yyyy">
                                <input type="text" id="data-inicio" name="data-inicio" class="form-control" placeholder="De..." <?php if(isset($_POST['data-inicio'])) echo("value='{$_POST['data-inicio']}'");?>>
                                <span class="input-group-addon"><i class="fa fa-chevron-right"></i></span>
                                <input type="text" id="data-fim" name="data-fim" class="form-control" placeholder="Até..." <?php if(isset($_POST['data-fim'])) echo("value='{$_POST['data-fim']}'");?>>
                            </div>
                        </div>
                    </div>
                    
					<div class="form-group">
                        <label class="col-md-3 control-label" for="cmbo-evento">Evento</label>
                        <div class="col-md-9">
                            <select id="cmbo-evento" name="cmbo-evento" class="form-control" size="1">
                                <option value="0">Todos Eventos</option>
                                <?php
								if($arrayEventos != NULL)
								{
									foreach ($arrayEventos as $item) {
									?>
										<option value="<?=$item->Codigo;?>" <?php if(isset($evento) && $evento == $item->Codigo) echo(" SELECTED ") ?>><?=date("d/m/Y", strtotime($item->Data));?> - <?=utf8_decode($item->Nome);?></option>
									<?php
									}
								}
								?>
                            </select>
                        </div>
                    </div>
					<div class="form-group">
                        <button type="submit" class="btn btn-effect-ripple btn-primary">Filtrar</button>
                        <!--<button type="reset" class="btn btn-effect-ripple btn-danger">Reset</button>-->
                    </div>
                </form>
                <!-- END Inline Form Content -->
            </div>
            <!-- END Inline Form Block -->
        <div class="table-responsive">
            <table id="example-datatable" class="table table-striped table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">Certificado</th>
						<th>Data Venda</th>
                        <th>CPF Cliente</th>
                        <th>Evento</th>
						<th>Data Evento</th>
                        <th class="text-center" style="width: 35px;"><i class="fa fa-flash"></i></th>
                    </tr>
                </thead>
                <tbody>
					<?php
					$labels['0']['class'] = "label-success";
                    $labels['0']['text'] = "Regular";
                    $labels['1']['class'] = "label-info";
                    $labels['1']['text'] = "Aguardando";
                    $labels['2']['class'] = "label-danger";
                    $labels['2']['text'] = "Cancelado";
                    $labels['3']['class'] = "label-warning";
                    $labels['3']['text'] = "Pendente";
					if($descricaoErro=='' && $arrayCertificados != null)
					{
						foreach ($arrayCertificados as $obj) {
						?>
						<tr>
							<td class="text-center"><?php echo $obj->DadosProposta->NumeroProposta; ?></td>
							<td><?php echo date("d/m/Y h:i", strtotime($obj->DadosProposta->DataAdesao)); ?></td>
							<td><strong><?php echo $obj->DadosProposta->Cliente->Contato->CPF; ?></strong></td>
							<td><?php echo utf8_decode($obj->DadosProposta->Evento->Nome); ?></td>
							<td><?php echo date("d/m/Y", strtotime($obj->DadosProposta->Evento->Data)); ?></td>
							<?php
							$rand = (strval($obj->DataValidacao)!='') ? 0 : 3;
							?>
							<td>
								<span class="label<?php echo ($labels[$rand]['class']) ? " " . $labels[$rand]['class'] : ""; ?>"><?php echo $labels[$rand]['text'] ?></span>
							</td>
						</tr>
						<?php
						}
						?>
					<?php
					}
					?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END Datatables Block -->
</div>
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/noshow_formsVendasEfetuadas.js"></script>
<script>$(function(){ UiTables.init(); });</script>
<?php
if($descricaoErro!='')
{
	echo('<script>$(function(){$("#div_erro").show();});</script>');
}
?>
<?php include 'inc/template_end.php'; ?>