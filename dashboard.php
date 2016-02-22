<?php require_once 'inc/config.php';
session_start();

if(!isset($_SESSION['user']))
	header('location: ./?message=Usuario não validado');

$template['header_link'] = 'Home';
include 'inc/template_start.php';
include 'inc/page_head.php';
?>
<!-- Page content -->
<div id="page-content">
    <!-- First Row -->
    <div class="row">
        <!-- Simple Stats Widgets -->
        <div class="col-sm-6 col-lg-3">
            <a href="javascript:void(0)" class="widget">
                <div class="widget-content widget-content-mini themed-background-success text-light-op">
                    <i class="fa fa-clock-o"></i> <strong>Hoje</strong>
                </div>
                <div class="widget-content text-right clearfix">
                    <div class="widget-icon pull-left">
                        <i class="gi gi-user text-muted"></i>
                    </div>
                    <h2 class="widget-heading h3 text-success">
                        <i class="fa fa-plus"></i> <strong><span data-toggle="counter" data-to="5"></span></strong>
                    </h2>
                    <span class="text-muted">CLIENTES</span>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="javascript:void(0)" class="widget">
                <div class="widget-content widget-content-mini themed-background-warning text-light-op">
                    <i class="fa fa-clock-o"></i> <strong><?php echo htmlentities("Último Mês", ENT_COMPAT,'ISO-8859-1', true); ?></strong>
                </div>
                <div class="widget-content text-right clearfix">
                    <div class="widget-icon pull-left">
                        <i class="gi gi-briefcase text-muted"></i>
                    </div>
                    <h2 class="widget-heading h3 text-warning">
                        <i class="fa fa-plus"></i> <strong><span data-toggle="counter" data-to="75"></span></strong>
                    </h2>
                    <span class="text-muted">INGRESSOS</span>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="javascript:void(0)" class="widget">
                <div class="widget-content widget-content-mini themed-background-danger text-light-op">
                    <i class="fa fa-clock-o"></i> <strong><?php echo htmlentities("Último Mês", ENT_COMPAT,'ISO-8859-1', true); ?></strong>
                </div>
                <div class="widget-content text-right clearfix">
                    <div class="widget-icon pull-left">
                        <i class="gi gi-wallet text-muted"></i>
                    </div>
                    <h2 class="widget-heading h3 text-danger">
                        <i class="fa fa-dollar"></i> <strong><span data-toggle="counter" data-to="5820"></span></strong>
                    </h2>
                    <span class="text-muted">GANHOS</span>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="javascript:void(0)" class="widget">
                <div class="widget-content widget-content-mini themed-background text-light-op">
                    <i class="fa fa-clock-o"></i> <strong><?php echo htmlentities("Último Mês", ENT_COMPAT,'ISO-8859-1', true); ?></strong>
                </div>
                <div class="widget-content text-right clearfix">
                    <div class="widget-icon pull-left">
                        <i class="gi gi-cardio text-muted"></i>
                    </div>
                    <h2 class="widget-heading h3">
                        <strong><span data-toggle="counter" data-to="7"></span></strong>
                    </h2>
                    <span class="text-muted">ENDOSSO</span>
                </div>
            </a>
        </div>
        <!-- END Simple Stats Widgets -->
    </div>
    <!-- END First Row -->

    <!-- Second Row -->
    <div class="row">
        <div class="col-sm-6 col-lg-8">
            <!-- Chart Widget -->
            <div class="widget">
                <div class="widget-content widget-content-mini themed-background-dark text-light-op">
                    <span class="pull-right text-muted">2014</span>
                    <i class="fa fa-fw fa-database"></i> <strong><?php echo htmlentities("Último Comparativo", ENT_COMPAT,'ISO-8859-1', true); ?></strong>
                </div>
                <div class="widget-content themed-background-muted">
                    <!-- Flot Charts (initialized in js/pages/readyDashboard.js), for more examples you can check out http://www.flotcharts.org/ -->
                    <div id="chart-classic-dash" style="height: 393px;"></div>
                </div>
                <div class="widget-content">
                    <div class="row text-center">
                        <div class="col-xs-4">
                            <h3 class="widget-heading"><i class="gi gi-wallet text-primary push-bit"></i> <br><small>41 mil Ganhos</small></h3>
                        </div>
                        <div class="col-xs-4">
                            <h3 class="widget-heading"><i class="gi gi-cardio text-black push-bit"></i> <br><small>17 mil Vendas</small></h3>
                        </div>
                        <div class="col-xs-4">
                            <h3 class="widget-heading"><i class="gi gi-life_preserver text-muted push-bit"></i> <br><small>245 Chamados</small></h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Chart Widget -->
        </div>
		
        <div class="col-sm-6 col-lg-4">
            <!-- Stats User Widget -->
			<!--
            <a href="javascript:void(0)" class="widget">
                <div class="widget-content widget-content-mini themed-background-dark text-light-op">
                    <i class="fa fa-fw fa-trophy"></i> <strong>Creator of the day</strong>
                </div>
                <div class="widget-content text-center">
                    <img src="img/placeholders/avatars/avatar13@2x.jpg" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar-2x">
                    <h2 class="widget-heading h3 text-muted">Anna Wigren</h2>
                </div>
                <div class="widget-content themed-background-muted text-dark text-center">
                    <strong>Logo Designer</strong>, Sweden
                </div>
                <div class="widget-content">
                    <div class="row text-center">
                        <div class="col-xs-4">
                            <h3 class="widget-heading"><i class="gi gi-share_alt text-success push-bit"></i> <br><small>58.6k Followers</small></h3>
                        </div>
                        <div class="col-xs-4">
                            <h3 class="widget-heading"><i class="gi gi-briefcase text-info push-bit"></i> <br><small>35 Projects</small></h3>
                        </div>
                        <div class="col-xs-4">
                            <h3 class="widget-heading"><i class="gi gi-heart_empty text-danger push-bit"></i> <br><small>5.3k Likes</small></h3>
                        </div>
                    </div>
                </div>
            </a>
			-->
            <!-- END Stats User Widget -->

            <!-- Mini Widgets Row -->
            <div class="row">
                <div class="col-xs-6">
                    <a href="javascript:void(0)" class="widget text-center">
                        <div class="widget-content themed-background-info text-light-op text-center">
                            <div class="widget-icon center-block push">
                                <i class="fa fa-facebook"></i>
                            </div>
                            <strong>98 Curtir</strong>
                        </div>
                    </a>
                </div>
                <div class="col-xs-6">
                    <a href="javascript:void(0)" class="widget">
                        <div class="widget-content themed-background-danger text-light-op text-center">
                            <div class="widget-icon center-block push">
                                <i class="fa fa-database"></i>
                            </div>
                            <strong>3 Servidores</strong>
                        </div>
                    </a>
                </div>
            </div>
            <!-- END Mini Widgets Row -->
			
			<!-- Mini Stats Widgets -->
                <div class="col-xs-12">
                    <a href="javascript:void(0)" class="widget text-center">
                        <div class="widget-content text-center clearfix">
                            <div class="widget-icon themed-background-warning pull-right">
                                <i class="gi gi-warning_sign fa-fw text-light"></i>
                            </div>
                            <img src="img/placeholders/avatars/avatar9.jpg" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar pull-left">
                            <h2 class="widget-heading h3 text-warning">
                                <i class="fa fa-plus"></i> <strong><span data-toggle="counter" data-to="26"></span></strong>
                            </h2>
                            <span class="text-muted">Requisições</span>
                        </div>
                    </a>
                </div>
                <div class="col-xs-6">
                    <a href="javascript:void(0)" class="widget text-center">
                        <div class="widget-content">
                            <i class="fa fa-3x fa-cloud-download text-success"></i>
                        </div>
                        <div class="widget-content themed-background-muted text-dark">
                            <strong>35k</strong> Condições gerais
                        </div>
                    </a>
                </div>
                <div class="col-xs-6">
                    <a href="javascript:void(0)" class="widget text-center">
                        <div class="widget-content">
                            <i class="fa fa-3x fa-bookmark text-danger"></i>
                        </div>
                        <div class="widget-content themed-background-muted text-dark">
                            <strong>760</strong> Termos de uso
                        </div>
                    </a>
                </div>
				<!--
                <div class="col-xs-6">
                    <a href="javascript:void(0)" class="widget text-center">
                        <div class="widget-content">
                            <i class="fa fa-3x fa-twitter"></i>
                        </div>
                        <div class="widget-content themed-background-muted text-dark">
                            <strong>35</strong> Seguidores
                        </div>
                    </a>
                </div>
                <div class="col-xs-6">
                    <a href="javascript:void(0)" class="widget text-center">
                        <div class="widget-content">
                            <i class="fa fa-3x fa-wordpress text-dark"></i>
                        </div>
                        <div class="widget-content themed-background-muted text-dark">
                            <strong>15</strong> Temas
                        </div>
                    </a>
                </div>
				-->
                <!-- END Mini Stats Widgets -->
        </div>
    </div>
    <!-- END Second Row -->

    <!-- Third Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Project Widget -->
                    <div class="widget">
                        <div class="widget-content widget-content-mini themed-background-dark text-light-op">
                            <span class="pull-right text-muted">60%</span>
                            <i class="fa fa-fw fa-check"></i><?php echo htmlentities("Meta de venda", ENT_COMPAT,'ISO-8859-1', true); ?>
                        </div>
                        <a href="javascript:void(0)" class="widget-content text-right clearfix">
                            <img src="img/placeholders/avatars/avatar6.jpg" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar pull-left">
                            <h2 class="widget-heading h3 text-muted">Jogo Botafogo x Coritiba</h2>
                            <div class="progress progress-striped progress-mini active">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%"></div>
                            </div>
                        </a>
                        <a href="javascript:void(0)" class="widget-content text-right clearfix">
                            <img src="img/placeholders/avatars/avatar2.jpg" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar pull-left">
                            <h2 class="widget-heading h3 text-muted">Show Ivete Sangalo</h2>
                            <div class="progress progress-striped progress-mini active">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                            </div>
                        </a>
                        <a href="javascript:void(0)" class="widget-content text-right clearfix">
                            <img src="img/placeholders/avatars/avatar11.jpg" alt="avatar" class="img-circle img-thumbnail img-thumbnail-avatar pull-left">
                            <h2 class="widget-heading h3 text-muted">Funeral da Seleção Brasileira</h2>
                            <div class="progress progress-striped progress-mini active">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100" style="width: 55%"></div>
                            </div>
                        </a>
                        <div class="widget-content themed-background-muted text-dark">
                            <div class="row text-center">
                                <div class="col-xs-6">
                                    <i class="fa fa-check-circle-o"></i> No Prazo
                                </div>
                                <div class="col-xs-6">
                                    <i class="fa fa-clock-o"></i> 17 Dias
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Project Widget -->
                </div>
				<!--
                <div class="col-sm-6">
                    <!-- Image Widget ->
					
                    <div class="widget">
                        <div class="widget-content widget-content-mini themed-background-dark text-light-op">
                            <span class="pull-right text-muted">Featured</span>
                            <i class="fa fa-fw fa-pencil"></i> <strong>Story</strong>
                        </div>
                        <div class="widget-image">
                            <img src="img/placeholders/photos/photo9.jpg" alt="image">
                            <div class="widget-image-content">
                                <h2 class="widget-heading"><a href="javascript:void(0)" class="text-light"><strong>The autumn trip that changed my life</strong></a></h2>
                                <h3 class="widget-heading text-light-op h4">It changed the way I think</h3>
                            </div>
                            <i class="gi gi-airplane"></i>
                        </div>
                        <div class="widget-content text-dark">
                            <div class="row text-center">
                                <div class="col-xs-4">
                                    <i class="fa fa-heart-o"></i> 9.5k
                                </div>
                                <div class="col-xs-4">
                                    <i class="fa fa-eye"></i> 76k
                                </div>
                                <div class="col-xs-4">
                                    <i class="fa fa-share-alt"></i> 7.2k
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Image Widget ->
                </div>
				-->
            </div>
        </div>
        
    </div>
    <!-- END Third Row -->
</div>
<!-- END Page Content -->

<?php include 'inc/page_footer.php'; ?>
<?php include 'inc/template_scripts.php'; ?>

<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/readyDashboard.js"></script>
<script>$(function(){ ReadyDashboard.init(); });</script>

<?php include 'inc/template_end.php'; ?>