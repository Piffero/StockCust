<?php if(isset($header)){ $this->load->view('partial/header', $header); }else{$this->load->view('partial/header');} ?>

    <div class="app-header navbar ng-scope">
        
        <div class="navbar-header bg-black">
            <button class="pull-right visible-xs dk" data-target=".navbar-collapse" ui-toggle-class="show"></button>
            <button class="pull-right visible-xs" ui-scroll="app" data-target=".app-aside" ui-toggle-class="off-screen"></button>
            <!-- brand -->
            <a class="navbar-brand text-lt" href="#/">                
                Ferraz<span class="hidden-folded m-l-xs ng-binding">Fernandes</span>
            </a>
            <!--  / brand -->
        </div>
        
        
        <div class="collapse pos-rlt navbar-collapse box-shadow bg-white-only">
            
            <!--  buttons -->
            <div class="nav navbar-nav hidden-xs">
                <a class="btn no-shadow navbar-btn" ng-click="app.settings.asideFolded = !app.settings.asideFolded" href=""><i class="fa fa-dedent fa-fw"></i></a>
                <a class="btn no-shadow navbar-btn" href="<?php echo site_url('security/user');?>"><i class="icon-user fa-fw"></i></a>
            </div>
            <!--  / buttons -->
            
            <!--  link and dropdown -->
            <ul class="nav navbar-nav hidden-sm">
            
                <li class="dropdown pos-stc" dropdown="">
                    <a class="dropdown-toggle" dropdown-toggle="" href="" aria-haspopup="true" aria-expanded="false">
                        <span>Relatórios</span>
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu wrapper w-full bg-white">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="m-l-xs m-t-xs m-b-xs font-bold">
                                    Relatório Sintético <span class="label label-sm bg-primary">PDF</span>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <ul class="list-unstyled l-h-2x">
                                            <li><a href="<?php echo site_url('rdreport/rd_end_product'); ?>" target="_black"><i class="fa fa-fw fa-angle-right text-muted m-r-xs"></i> Empanadas</a></li>
                                            <li><a href="<?php echo site_url('rdreport/rd_fillings'); ?>" target="_black"><i class="fa fa-fw fa-angle-right text-muted m-r-xs"></i> Massas / Recheio</a></li>
                                            <li><a href="<?php echo site_url('rdreport/rd_raw_material'); ?>" target="_black"><i class="fa fa-fw fa-angle-right text-muted m-r-xs"></i> Materia Prima</a></li>
                                            <li><a href="<?php echo site_url('rdreport/rd_hierarchy'); ?>" target="_black"><i class="fa fa-fw fa-angle-right text-muted m-r-xs"></i> Hierárquia Completa</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-6">
                                        <ul class="list-unstyled l-h-2x">
                                            <li><a href="<?php echo site_url('rdreport/rd_regras'); ?>" target="_black"><i class="fa fa-fw fa-angle-right text-muted m-r-xs"></i> Regras</a></li>                                            
                                            <li><a href="<?php echo site_url('rdreport/rd_usuarios'); ?>" target="_black"><i class="fa fa-fw fa-angle-right text-muted m-r-xs"></i> Usuários</a></li>
                                            <li><a href="<?php echo site_url('rdreport/rd_permission'); ?>" target="_black"><i class="fa fa-fw fa-angle-right text-muted m-r-xs"></i> Permissão</a></li>
                                            <li><a href="<?php echo site_url('rdreport/rd_unid_medida'); ?>" target="_black"><i class="fa fa-fw fa-angle-right text-muted m-r-xs"></i> Unidade Medida</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 b-l b-light">
                                <div class="m-l-xs m-t-xs m-b-xs font-bold">
                                    Analise de Empanadas <span class="label label-sm bg-primary">PDF</span></div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <ul class="list-unstyled l-h-2x">
                                            <li><a href="<?php echo site_url('rdreport/rd_new_sobdemanda'); ?>"><i class="fa fa-fw fa-angle-right text-muted m-r-xs"></i> Sob Demanda</a></li>
                                            <li><a href="">&ensp;</a></li>
                                            <li><a href="">&ensp;</a></li>
                                            <li><a href="">&ensp;</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-6">
                                        <ul class="list-unstyled l-h-2x">
                                            <li><a href="<?php echo site_url('rdreport/rd_producao'); ?>"><i class="fa fa-fw fa-angle-right text-muted m-r-xs"></i> Produção</a></li>
                                            <li><a href="">&ensp;</a></li>
                                            <li><a href="">&ensp;</a></li>
                                            <li><a href="">&ensp;</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
<!--                             <div class="col-sm-4 b-l b-light">
                                <div class="m-l-xs m-t-xs m-b-sm font-bold">Relatório Análitico</div>
                                <div class="text-center">
                                    <div class="inline">
                                        <div class="easyPieChart" ui-options="{ percent: 65, … animate: 2000 }" ui-jq="easyPieChart" style="width: 100px; height: 100px; line-height: 100px;">
                                            <canvas height="100" width="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </li>
                
            </ul>
            <!-- / link and dropdown -->
            
             
            <!-- nabar right -->
            <ul class="nav navbar-nav navbar-right">
                <!--             
                <li class="dropdown" dropdown="">
                    <a class="dropdown-toggle" dropdown-toggle="" href="" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-bell fa-fw"></i>
                        <span class="visible-xs-inline">Notifications</span>
                        <span class="badge badge-sm up bg-danger pull-right-xs">2</span>
                    </a>
                    <div class="dropdown-menu w-xl animated fadeInUp">                    
                        <div class="panel bg-white">
                            <div class="panel-heading b-light bg-light">
                                <strong>You have <span> 2 </span> notifications</strong>
                            </div>
                            <div class="list-group">
                                <a class="media list-group-item" href="">                                    
                                    <span class="media-body block m-b-none">Use awesome animate.css</span>
                                    <br><br>
                                    <small class="text-muted">10 minutes ago</small>
                                </a>
                                <a class="media list-group-item" href="">                                    
                                    <span class="media-body block m-b-none">1.0 initial released</span>
                                    <br><br>
                                    <small class="text-muted">1 hour ago</small>
                                </a>
                            </div>
                            <div class="panel-footer text-sm">
                                <a class="pull-right" href=""><i class="fa fa-cog"></i></a>
                                <a data-toggle="class:show animated fadeInRight" href="#notes">See all the notifications</a>
                            </div>
                        </div>                    
                    </div>
                </li>
                 -->
                <li class="dropdown" dropdown="">
                    <a class="dropdown-toggle clear" dropdown-toggle="" href="" aria-haspopup="true" aria-expanded="false">
                        <span class="thumb-sm avatar pull-right m-t-n-sm m-b-n-sm m-l-sm" style="top: 25px;;">                            
                            <i class="on md b-white bottom"></i>
                        </span>
                        <span class="hidden-sm hidden-md"><?php echo $user_info->usuario_nome; ?></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight w">                        
                        <li>
                            <a href="<?php echo site_url('security/permission');?>">
                                <span class="badge bg-danger pull-right"><?php echo $mrg; ?>%</span>
                                <span>Permissões</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('security/user');?>">Perfil</a>
                        </li>
                        <li>
                            <a ui-sref="app.docs" href="#/app/docs">
                                <span class="label bg-info pull-right">new</span> Ajuda
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo site_url('home/logout');?>">Sair do Sistema</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- / navbar right -->

        </div>
        
        
    </div>
     