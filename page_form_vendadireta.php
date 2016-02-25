<?php
session_start();
require_once 'inc/config.php';
require_once 'inc/EnterpriseWS.php';

if(!isset($_SESSION['user']))
{
	header('location: index.php');
}

$template['header_link'] = 'VENDA DIRETA';

include 'inc/template_start.php';
include 'inc/page_head.php';

$obj1 = new EnterpriseWS();
$obj1->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);

$response = $obj1->ListaEventosNOSHOW("<sgsm:ListaEventos/>");
$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
$response = simplexml_load_string($response);

if(isset($response->ListaEventosResponse->ListaEventosResult))
{
	$arrayEventos = $response->ListaEventosResponse->ListaEventosResult->EventoModel;
} else {
	$arrayEventos = null;
}
unset($obj1);

$obj2 = new EnterpriseWS();
$obj2->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);
$response = $obj2->ListaUFsNOSHOW("<sgsm:ListUFs/>");
$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
$response = simplexml_load_string($response);
if(isset($response->ListUFsResponse->ListUFsResult))
{
	$arrayUfs = $response->ListUFsResponse->ListUFsResult->UFModel;
} else {
	$arrayUfs = null;
}
unset($obj2);
?>

<!-- Page content -->
<div id="page-content">
    <!-- Wizard Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1><?php echo htmlentities("Formul?rio de Venda", ENT_COMPAT,'ISO-8859-1', true); ?></h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li>Home</li>
						<li>Opera??es</li>
                        <li><a href="page_form_vendadireta.php">Venda</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Wizard Header -->
	
	<!-- Success Alert -->
	<div class="alert alert-success alert-dismissable" id="div_sucesso">
		<!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
		<h4><strong>Venda Efetivada com sucesso.</strong></h4>
		<p><strong>Numero do Certificado:</strong> <span id="texto_certificado"></span></p>
		<p><strong>Valor do Pr?mio:</strong> <span id="texto_premio"></span></p>
		<p><strong>T?tulo:</strong> <span id="texto_sorteio"></span></p>
		<p><strong>S?rie:</strong> <span id="texto_serie"></span></p>
		<p><strong>N?mero do Ingresso:</strong> <span id="texto_ingresso"></span></p>
		<p align="center"><a href="#modal-fade" class="btn btn-effect-ripple btn-primary" data-toggle="modal">Op??es de Comunica??o</a></p>
		
	</div>
	<!-- END Success Alert -->

	<!-- Danger Alert -->
	<div class="alert alert-danger alert-dismissable" id="div_erro">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h4><strong>Erro Efetuando a venda</strong></h4>
		<p>Opps, Um erro ocorreu ao tentar realizar a venda!</p>
	</div>
	<!-- END Danger Alert -->

    <!-- Wizards Content -->
    <!-- Form Wizards are initialized in js/pages/formsWizard.js -->
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            <!-- Clickable Wizard Block -->
            <div class="block">
                <!-- Clickable Wizard Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a href="javascript:void(0)" class="btn btn-effect-ripple btn-default" data-toggle="tooltip" title="Configuracoes"><i class="fa fa-cog"></i></a>
                    </div>
                    <h2>Seguro Prote??o de Ingresso</h2>
                </div>
                <!-- END Clickable Wizard Title -->

                <!-- Clickable Wizard Content -->
                <form id="clickable-wizard" action="ajax/vendaajax.php" method="post" class="form-horizontal form-bordered">
                    <!-- First Step -->
                    <div id="clickable-first" class="step">
                        <!-- Step Info -->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <ul class="nav nav-pills nav-justified clickable-steps">
                                    <li class="active"><a href="javascript:void(0)" data-gotostep="clickable-first"><i class="fa fa-user"></i> <strong>Cliente</strong></a></li>
                                    <li><a href="javascript:void(0)" data-gotostep="clickable-second"><i class="fa fa-pencil-square-o"></i> <strong>Endere?o</strong></a></li>
                                    <li><a href="javascript:void(0)" data-gotostep="clickable-third"><i class="fa fa-check"></i> <strong>Evento</strong></a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- END Step Info -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="val-cpfcliente">CPF</label>
                            <div class="col-md-6">
								<div class="input-group">
									<input type="text" id="val-cpfcliente" name="val-cpfcliente" class="form-control" placeholder="CPF do Cliente ..">
									<span class="input-group-btn">
										<button type="button" class="btn btn-effect-ripple btn-primary" id="btn-pesquisar"><i class="fa fa-search"></i>Pesquisar</button>
									</span>
								</div>
							</div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-nome">Nome</label>
                            <div class="col-md-6">
                                <input type="text" id="val-nome" name="val-nome" class="form-control" placeholder="Nome do cliente.." />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="val-mail">Email</label>
                            <div class="col-md-6">
                                <input type="text" id="val-mail" name="val-mail" class="form-control" placeholder="Digite o email do cliente.." />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="val-sexo">Sexo</label>
                            <div class="col-md-6">
                                <select id="val-sexo" name="val-sexo" class="form-control">
                                <option value="">Selecione</option>
                                <option value="1">Maculino</option>
                                <option value="2">Feminino</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="val-estadocivil">Estado Civil</label>
                            <div class="col-md-6">
                                <select id="val-estadocivil" name="val-estadocivil" class="form-control">
                                <option value="">Selecione</option>
                                <option value="1">Solteiro</option>
                                <option value="2">Casado</option>
								<option value="3">Viuvo</option>
								<option value="4">Outros</option>
								<option value="5">Divorciado</option>
								<option value="6">Separado Judicialmente</option>
                            </select>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-nascimento">Data de Nascimento</label>
                            <div class="col-md-6">
                                <input type="text" id="val-nascimento" name="val-nascimento" class="form-control" placeholder="Data de nascimento.." />
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-documento">Documento RG</label>
                            <div class="col-md-6">
                                <input type="text" id="val-documento" name="val-documento" class="form-control" placeholder="RG do cliente.." />
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-expeditor">Org?o Expeditor</label>
                            <div class="col-md-6">
                                <input type="text" id="val-expeditor" name="val-expeditor" class="form-control" placeholder="Orgao expeditor .." />
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-ufexpeditor">UF Org?o Expeditor</label>
                            <div class="col-md-6">
                                <select id="val-ufexpeditor" name="val-ufexpeditor" class="form-control">
									<option value="">Selecione</option>
									<?php
									foreach ($arrayUfs as $obj) {
									?>
										<option value="<?=$obj->ID;?>"><?=utf8_decode($obj->Descricao);?></option>
									<?php
									}
									?>
								</select>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-dataexpedicao">Data de Expedi??o</label>
                            <div class="col-md-6">
                                <input type="text" id="val-dataexpedicao" name="val-dataexpedicao" class="form-control" placeholder="Data de expedicao.." />
                            </div>
                        </div>
                    </div>
                    <!-- END First Step -->

                    <!-- Second Step -->
                    <div id="clickable-second" class="step">
                        <!-- Step Info -->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <ul class="nav nav-pills nav-justified clickable-steps">
                                    <li><a href="javascript:void(0)" class="text-muted" data-gotostep="clickable-first"><i class="fa fa-user"></i> <del><strong>Cliente</strong></del></a></li>
                                    <li class="active"><a href="javascript:void(0)" data-gotostep="clickable-second"><i class="fa fa-pencil-square-o"></i> <strong>Endere?o</strong></a></li>
                                    <li><a href="javascript:void(0)" data-gotostep="clickable-third"><i class="fa fa-check"></i> <strong>Evento</strong></a></li>
                                </ul>
                            </div>
                        </div>
						
                        <!-- END Step Info -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="val-cep">CEP</label>
                            <div class="col-md-6">
                                <input type="text" id="val-cep" name="val-cep" class="form-control" placeholder="CEP.. ex: 72000000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="val-logradouro">Logradouro</label>
                            <div class="col-md-6">
                                <input type="text" id="val-logradouro" name="val-logradouro" class="form-control" placeholder="Logradouro ..">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="val-logradouron">N?mero</label>
                            <div class="col-md-6">
                                <input type="text" id="val-logradouron" name="val-logradouron" class="form-control" placeholder="Numero ..">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="val-logradouro2">Complemento</label>
                            <div class="col-md-6">
                                <input type="text" id="val-logradouro2" name="val-logradouro2" class="form-control" placeholder="Complemento ..">
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-logradouroc">Cidade</label>
                            <div class="col-md-6">
                                <input type="text" id="val-logradouroc" name="val-logradouroc" class="form-control" placeholder="Cidade ..">
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-logradourob">Bairro</label>
                            <div class="col-md-6">
                                <input type="text" id="val-logradourob" name="val-logradourob" class="form-control" placeholder="Bairro ..">
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-uf">Estado</label>
                            <div class="col-md-6">
                                <select id="val-uf" name="val-uf" class="form-control">
									<option value="">Selecione</option>
									<?php
									foreach ($arrayUfs as $obj) {
									?>
										<option value="<?=$obj->ID;?>"><?=utf8_decode($obj->Descricao);?></option>
									<?php
									}
									?>
								</select>
                            </div>
                        </div>
                    </div>
                    <!-- END Second Step -->

                    <!-- Third Step -->
                    <div id="clickable-third" class="step">
                        <!-- Step Info -->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <ul class="nav nav-pills nav-justified clickable-steps">
                                    <li><a href="javascript:void(0)" class="text-muted" data-gotostep="clickable-first"><i class="fa fa-user"></i> <del><strong>Cliente</strong></del></a></li>
                                    <li><a href="javascript:void(0)" class="text-muted" data-gotostep="clickable-second"><i class="fa fa-pencil-square-o"></i> <del><strong>Endere?o</strong></del></a></li>
                                    <li class="active"><a href="javascript:void(0)" data-gotostep="clickable-third"><i class="fa fa-check"></i> <strong>Evento</strong></a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- END Step Info -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="val-evento">Evento</label>
                            <div class="col-md-6">
                                <select id="val-evento" name="val-evento" class="form-control">
									<option value="">Selecione</option>
									<?php
									foreach ($arrayEventos as $obj) {
									?>
										<option value="<?=$obj->Codigo;?>"><?=utf8_decode($obj->Nome);?></option>
									<?php
									}
									?>
								</select>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-vingresso">Valor do Ingresso</label>
                            <div class="col-md-6">
                                <input id="val-vingresso" name="val-vingresso" class="form-control" placeholder="Valor do ingresso" />
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-ingresso">N?mero do Ingresso</label>
                            <div class="col-md-6">
                                <input id="val-ingresso" name="val-ingresso" class="form-control" placeholder="Numero do ingresso" />
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-4 control-label" for="val-tipoingresso">Tipo Ingresso</label>
                            <div class="col-md-6">
                                <select id="val-tipoingresso" name="val-tipoingresso" class="form-control">
								</select>
                            </div>
                        </div>
						<div class="form-group">
                            <span class="col-md-4 control-label" >Valor do Pr?mio: R$</span>
							<span class="col-md-6 control-label" id="lbl_vlrpremiocalc">0,00</span>
                        </div>
                    </div>
                    <!-- END Third Step -->

                    <!-- Form Buttons -->
                    <div class="form-group form-actions">
                        <div class="col-md-8 col-md-offset-4">
							<input type="reset" value="resetar" class="btn btn-effect-ripple btn-danger" id="back" />
							<input type="submit" value="submeter" class="btn btn-effect-ripple btn-primary" id="next" />
                        </div>
                    </div>
                    <!-- END Form Buttons -->
                </form>
                <!-- END Clickable Wizard Content -->
            </div>
            <!-- END Clickable Wizard Block -->

        </div>
    </div>
    <!-- END Wizards Content -->

    <!-- Terms Modal -->
    <div id="modal-terms" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center"><strong>Terms and Conditions</strong></h3>
                </div>
                <div class="modal-body">
                    <h4 class="page-header">1. <strong>General</strong></h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta. Integer fermentum tincidunt auctor.</p>
                    <h4 class="page-header">2. <strong>Account</strong></h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta. Integer fermentum tincidunt auctor.</p>
                    <h4 class="page-header">3. <strong>Service</strong></h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta. Integer fermentum tincidunt auctor.</p>
                    <h4 class="page-header">4. <strong>Payments</strong></h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta. Integer fermentum tincidunt auctor.</p>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="button" class="btn btn-effect-ripple btn-primary" data-dismiss="modal">I've read them!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Terms Modal -->
	
	<!-- Regular Fade1 -->
	<div id="modal-fade1" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title"><strong><?php echo htmlentities("Endere?os", ENT_COMPAT,'ISO-8859-1', true); ?></strong></h3>
				</div>
				<div class="modal-body">
					<form id="form-endereco" action="ajax/comunicavenda.php" method="post" class="form-horizontal form-bordered">
						<input type="hidden" id="val-cpf-reenv" name="val-cpf-reenv" />
						<input type="hidden" id="val-certificado-reenv" name="val-certificado-reenv" />
						<h4>Escolha o endereco da lista abaixo.</h4>
						<div class="form-group">
							<div class="col-md-12">
								<select id="mtp_endereco" name="mtp_endereco" class="form-control" size="10" multiple>
								</select>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-effect-ripple btn-primary" id="btn-seleciona-end">Selecionar</button>
					<button type="button" class="btn btn-effect-ripple btn-danger" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- END Regular Fade1 -->
	
	<!-- Regular Fade2 -->
	<div id="modal-fade" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
					<h3 class="modal-title"><strong>COMPRA DO SEGURO REALIZADA COM SUCESSO</strong></h3>
				</div>
				<div class="modal-body">
					<form id="form-validation-reenv" action="ajax/comunicavenda.php" method="post" class="form-horizontal form-bordered">
						<input type="hidden" id="val-cpf-reenv" name="val-cpf-reenv" />
						<input type="hidden" id="val-certificado-reenv" name="val-certificado-reenv" />
						<input type="hidden" id="val-mail-reenv" name="val-mail-reenv" />
						<BR/>
						<p align="center">
							<a href="#" class="btn btn-effect-ripple btn-primary" id="btn-visualiza">Imprimir o Certificado</a>
						</p>
						<h4>Comunica??o de boas vindas</h4>
								<!--<a href="#" class="btn btn-effect-ripple btn-primary" id="btn-boasvindas">Comunica??o de Boas Vindas</a>-->
								<div class="form-group">
									<label class="col-md-3 control-label">Escolha</label>
									<div class="col-md-9">
										<div class="radio">
											<label for="example-radio1">
												<input type="radio" id="example-radio1" name="example-radios" value="option1"> Enviar por email
											</label>
										</div>
										<div class="radio">
											<label for="example-radio2">
												<input type="radio" id="example-radio2" name="example-radios" value="option2"> Imprimir
											</label>
										</div>
									</div>
								</div>
					</form>
				</div>
				<div class="modal-footer" style="text-align:center">
					<button type="button" class="btn btn-effect-ripple btn-primary" id="btn-comunica-cliente">Comunica Cliente</button>
					<button type="button" class="btn btn-effect-ripple btn-danger" data-dismiss="modal">Fechar</button>
					<!--
					<button type="button" class="btn btn-effect-ripple btn-primary" id="btn-envia-email">Enviar por Email</button>
					<button type="button" class="btn btn-effect-ripple btn-primary" id="btn-visualiza">Visualizar</button>
					<button type="button" class="btn btn-effect-ripple btn-danger" data-dismiss="modal">Fechar</button>
					-->
				</div>
			</div>
		</div>
	</div>
	<!-- END Regular Fade2 -->
</div>
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script type="text/javascript" src="js/vendor/jquery.mask.min.js"></script>
<script src="js/pages/noshow_formsVendaDireta.js"></script>
<script>$(function(){ FormsWizard.init(); });</script>
<script>$(function() { FormValidationReenvia.init(); });</script>
<?php
if($arrayEventos==NULL || $arrayUfs == NULL)
{
	echo('<script>$(function(){$("#div_erro").show();});</script>');
	echo('<script>$(function(){$(".block").hide();});</script>');
}
?>
<?php include 'inc/template_end.php'; ?>