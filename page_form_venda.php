<?php
session_start();
require_once 'inc/config.php';
require_once 'inc/EnterpriseWS.php';

if(!isset($_SESSION['user']))
{
	header('location: index.php');
}

include 'inc/template_start.php';
$template['header_link'] = 'VENDA DIRETA';
include 'inc/page_head.php';

$obj = new EnterpriseWS();
$obj->setEnterpriseWSUser($_SESSION['user']->loginname, $_SESSION['user']->password);

$xml = "<sgsm:ListaEventos/>";
$response = $obj->ListaEventosNOSHOW($xml);

$response = str_replace("</soap:Body>","",str_replace("<soap:Body>","",$response));
$response = simplexml_load_string($response);
$arrayEventos = $response->ListaEventosResponse->ListaEventosResult->EventoModel;

//$arrayEventos = null;
?>
<!-- Page content -->
<div id="page-content">
    <!-- Validation Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1><?php echo htmlentities("Formulário de Venda", ENT_COMPAT,'ISO-8859-1', true); ?></h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li>Home</li>
                        <li><a href="page_form_venda.php">Venda</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Validation Header -->

	<!-- Success Alert -->
	<div class="alert alert-success alert-dismissable" id="div_sucesso">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h4><strong>Venda Efetivada com sucesso.</strong></h4>
		<p><strong>Numero do Certificado:</strong> <span id="texto_certificado"></span></p>
		<p><strong>Numero do Sorteio:</strong> <span id="texto_sorteio"></span></p>
		<p><strong>Numero do Ingresso:</strong> <span id="texto_ingresso"></span></p>
	</div>
	<!-- END Success Alert -->

	<!-- Danger Alert -->
	<div class="alert alert-danger alert-dismissable" id="div_erro">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h4><strong>Erro Efetuando a venda</strong></h4>
		<p>Opps, Um erro ocorreu ao tentar realizar a venda!</p>
	</div>
	<!-- END Danger Alert -->

    <!-- Form Validation Content -->
    <div class="row">
	
		<div id="div_loading">
			<i class="fa fa-asterisk fa-2x fa-spin text-primary"></i>
		</div>

		<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3" id="div_row">

            <!-- Form Validation Block -->
            <div class="block">
                <!-- Form Validation Title -->
                <div class="block-title">
                    <h2>Seguro NOSHOW</h2>
                </div>
                <!-- END Form Validation Title -->
                <!-- Form Validation Form -->
                <form id="form-validation" action="ajax/vendaajax.php" method="post" class="form-horizontal form-bordered">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="val-cpfcliente">CPF <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" id="val-cpfcliente" name="val-cpfcliente" class="form-control" placeholder="CPF do cliente..">
                        </div>
						<i class="gi gi-search"></i>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-nome">Nome <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" id="val-nome" name="val-nome" class="form-control" placeholder="Nome do cliente..">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="val-mail">Email</label>
                        <div class="col-md-6">
                            <input type="text" id="val-mail" name="val-mail" class="form-control" placeholder="Email valido..">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-sexo">Sexo<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <select id="val-sexo" name="val-sexo" class="form-control">
                                <option value="">Selecione</option>
                                <option value="1">Maculino</option>
                                <option value="2">Feminino</option>
                            </select>
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-estadocivil">Estado Civil<span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <select id="val-estadocivil" name="val-estadocivil" class="form-control">
                                <option value="">Selecione</option>
                                <option value="1">Solteiro</option>
                                <option value="2">Casado</option>
								<option value="2">Viuvo</option>
                            </select>
                        </div>
                    </div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="val-nascimento">Data de Nascimento<span class="text-danger">*</span></label>
						<div class="col-md-6">
                            <input type="text" id="val-nascimento" name="val-nascimento" class="form-control" data-date-format="dd/mm/yyyy" placeholder="dd/mm/aaaa">
                        </div>
					</div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-cep">CEP <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" id="val-cep" name="val-cep" class="form-control" placeholder="CEP.. ex: 72000000">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-uf">Estado <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <select id="val-uf" name="val-uf" class="form-control">
                                <option value="">Selecione</option><!-- Required for data-placeholder attribute to work with Chosen plugin -->
                                <option value="DF">Distrito Federal</option>
								<option value="GO">Goias</option>
                            </select>
                        </div>
					</div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-logradouro">Logradouro <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" id="val-logradouro" name="val-logradouro" class="form-control" placeholder="Logradouro..">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-logradouro2">Complemento</label>
                        <div class="col-md-6">
                            <input type="text" id="val-logradouro2" name="val-logradouro2" class="form-control" placeholder="Complemento..">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-logradouron">Numero <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" id="val-logradouron" name="val-logradouron" class="form-control" placeholder="Numero..">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-logradouroc">Cidade <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" id="val-logradouroc" name="val-logradouroc" class="form-control" placeholder="Cidade..">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-logradourob">Bairro <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" id="val-logradourob" name="val-logradourob" class="form-control" placeholder="Bairro..">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-evento">Evento <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <select id="val-evento" name="val-evento" class="form-control">
                                <option>Selecione</option><!-- Required for data-placeholder attribute to work with Chosen plugin -->
								<?php
								foreach ($arrayEventos as $obj) {
								?>
									<option value="<?=$obj->Codigo;?>"><?=$obj->Nome;?></option>
								<?php
								}
								?>
                            </select>
                        </div>
					</div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-ingresso">Ingresso <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" id="val-ingresso" name="val-ingresso" class="form-control" placeholder="Numero ingresso..">
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-md-3 control-label" for="val-vingresso">Valor <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" id="val-vingresso" name="val-vingresso" class="form-control" placeholder="Valor do ingresso.. ex: 100.00">
                        </div>
                    </div>
					<!--
                    <div class="form-group">
                        <label class="col-md-3 control-label"><a href="#modal-terms" data-toggle="modal">Certificado por email?</a> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <label class="switch switch-primary" for="val-terms">
                                <input type="checkbox" id="val-terms" name="val-terms" value="1" checked>
                                <span data-toggle="tooltip" title="Enviar certificado por email"></span>
                            </label>
                        </div>
                    </div>
					-->
                    <div class="form-group form-actions">
                        <div class="col-md-8 col-md-offset-3">
                            <button type="submit" class="btn btn-effect-ripple btn-primary">Enviar</button>
                            <button type="reset" class="btn btn-effect-ripple btn-danger">Cancelar</button>
                        </div>
                    </div>
                </form>
                <!-- END Form Validation Form -->

                <!-- Terms Modal -->
                <div id="modal-terms" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title text-center"><strong>Termos e Condicoes</strong></h3>
                            </div>
                            <div class="modal-body">
                                <h4 class="page-header">1. <strong>Geral</strong></h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta. Integer fermentum tincidunt auctor.</p>
                                <h4 class="page-header">2. <strong>Conta</strong></h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta. Integer fermentum tincidunt auctor.</p>
                                <h4 class="page-header">3. <strong>Servico</strong></h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta. Integer fermentum tincidunt auctor.</p>
                                <h4 class="page-header">4. <strong>Pagamentos</strong></h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultrices, justo vel imperdiet gravida, urna ligula hendrerit nibh, ac cursus nibh sapien in purus. Mauris tincidunt tincidunt turpis in porta. Integer fermentum tincidunt auctor.</p>
                            </div>
                            <div class="modal-footer">
                                <div class="text-center">
                                    <button type="button" class="btn btn-effect-ripple btn-primary" data-dismiss="modal">Li e aceito!</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Terms Modal -->

            </div>
            <!-- END Form Validation Block -->
        </div>
    </div>
    <!-- END Form Validation Content -->
</div>

				<!-- Regular Fade -->
                <div id="modal-fade" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h3 class="modal-title"><strong><?php echo htmlentities("Confirmação", ENT_COMPAT,'ISO-8859-1', true); ?></strong></h3>
							</div>
							<div class="modal-body">
								<form id="form-validation-reenv" action="ajax/comunicavenda.php" method="post" class="form-horizontal form-bordered">
									<input type="hidden" id="val-cpf-reenv" name="val-cpf-reenv" />
									<input type="hidden" id="val-certificado-reenv" name="val-certificado-reenv" />
									<h4>Venda realizada com sucesso.</h4>
									<p>Abaixo voce pode realizar as seguintes opcoes:
										<ul>
											<li>Visualizar o certificado;</li>
											<li>Enviar por email.</li>
										</ul>
									</p>
									<div class="form-group">
										<label class="col-md-3 control-label" for="val-mail-reenv">Email <span class="text-danger">*</span></label>
										<div class="col-md-6">
											<input type="text" id="val-mail-reenv" name="val-mail-reenv" class="form-control" placeholder="Email valido..">
										</div>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-effect-ripple btn-primary" id="btn-envia-email">Enviar por Email</button>
								<button type="button" class="btn btn-effect-ripple btn-primary" id="btn-visualiza">Visualizar</button>
								<button type="button" class="btn btn-effect-ripple btn-danger" data-dismiss="modal">Fechar</button>
							</div>
						</div>
					</div>
                </div>
                <!-- END Regular Fade -->
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/noshow_formsVenda.js"></script>
<script type="text/javascript" src="js/vendor/jquery.mask.min.js"></script>
<script>$(function() { FormValidationVenda.init(); });</script>
<script>$(function() { FormValidationReenvia.init(); });</script>
<?php include 'inc/template_end.php'; ?>