
    <div class="app-aside hidden-xs bg-black">
        <div class="aside-wrap ng-scope">
            <div class="navi-wrap">
                            
                <nav class="navi ng-scope" ui-nav="">
                    <ul class="nav ng-scope">
                    
                        <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                            <span class="ng-scope">Navigation</span>
                        </li>
                        
                        <li class="<?php if(isset($li_0)){ echo "active"; }?>">
                            <a class="auto" href="">
                                <span class="pull-right text-muted"><i class="fa fa-fw fa-angle-right text"></i></span>
                                <i class="glyphicon glyphicon-stats icon text-primary-dker"></i>
                                <span class="font-bold ng-scope">Matéria Prima</span>
                            </a>
                            <ul class="nav nav-sub dk">
                                <li class="nav-sub-header">
                                    <a href=""><span class="ng-scope">Matéria Prima</span></a>
                                </li>
                                <li class="<?php if(isset($li_01)){ echo "active"; }?>" ui-sref-active="active">
                                    <a href="<?php echo site_url("product/end_product");?>"><span>Empanada</span></a>                                        
                                </li>
                                <li class="<?php if(isset($li_02)){ echo "active"; }?>" ui-sref-active="active">
                                    <a href="<?php echo site_url("product/fillings");?>"><span>Massas / Recheios</span></a>
                                </li>
                                <li class="<?php if(isset($li_03)){ echo "active"; }?>" ui-sref-active="active">
                                    <a href="<?php echo site_url("product/raw_material");?>"><span>Matéria Prima</span></a>
                                </li>
                            </ul>
                        </li>
                        <!-- 
                        <li class="<?php if(isset($li_1)){ echo "active"; }?>">
                            <a class="auto" href="">
                                <span class="pull-right text-muted">
                                    <i class="fa fa-fw fa-angle-right text"></i>
                                </span>
                                <i class="glyphicon glyphicon-th-large icon text-success"></i>
                                <span class="font-bold">Apps</span>                            
                            </a>
                            <ul class="nav nav-sub dk">
                                <li class="nav-sub-header"><a href=""><span>Apps</span></a></li>
                                <li ui-sref-active="active"><a ui-sref="apps.note" href="#/apps/note"><span>Criar Receita</span></a></li>                                
                            </ul>
                        </li>
                        -->
                        <li class="line dk"></li>
                        
                        <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                            <span class="ng-scope">Parametros / Sistema</span>
                        </li>
                        
                        <li class="<?php if(isset($li_2)){ echo "active"; }?>">
                            <a class="auto" href="<?php echo site_url('param/base_sum');?>">
                                <i class="glyphicon glyphicon-edit"></i>
                                <span>Regras</span>
                            </a>                            
                        </li>
                        
                        <li class="<?php if(isset($li_3)){ echo "active"; }?>">
                            <a class="auto" href="<?php echo site_url('param/unit_med')?>">                                                                
                                <i class="icon-calculator"></i>
                                <span>Unid. Medida</span>
                            </a>                            
                        </li>
                        
                        
                        <li class="line dk"></li>
                        
                        <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                            <span class="ng-scope">Segurança / Sistema</span>
                        </li>
                        
                        <li class="<?php if(isset($li_4)){ echo "active"; }?>">
                            <a class="auto" href="">
                                <span class="pull-right text-muted"><i class="fa fa-fw fa-angle-right text"></i></span>                                
                                <i class="fa fa-key"></i>
                                <span>Segurança</span>
                            </a>
                            <ul class="nav nav-sub dk">
                                <li class="nav-sub-header"><a href=""><span>Segurança</span></a></li>
                                <li class="<?php if(isset($li_41)){ echo "active"; }?>" ui-sref-active="active"><a href="<?php echo site_url('security/user');?>"><span>Cadastro Usuário</span></a></li>
                                <li class="<?php if(isset($li_42)){ echo "active"; }?>" ui-sref-active="active"><a href="<?php echo site_url('security/permission');?>"><span>Definir Permissões</span></a></li>                                
                            </ul>
                        </li>
                                                
                    </ul>
                </nav>
                
                
                
                <div class="wrapper m-t">
                    <div class="text-center-folded">
                        <span class="pull-right pull-none-folded">60%</span>
                        <span class="hidden-folded ng-scope">Usuários Logados</span>
                    </div>
                    <div class="progress-xxs m-t-sm dk progress ng-isolate-scope" type="info" value="60">
                        <div class="progress-bar progress-bar-info" ng-transclude="" aria-valuetext="60%" ng-style="{width: percent + '%'}" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" ng-class="type && 'progress-bar-' + type" style="width: 60%;"></div>
                    </div>
                    <div class="text-center-folded">
                        <span class="pull-right pull-none-folded">2%</span>
                        <span class="hidden-folded ng-scope">Empanadas </span>
                    </div>
                    <div class="progress-xxs m-t-sm dk progress ng-isolate-scope" type="primary" value="2">
                        <div class="progress-bar progress-bar-primary" ng-transclude="" aria-valuetext="2%" ng-style="{width: percent + '%'}" aria-valuemax="100" aria-valuemin="0" aria-valuenow="2" role="progressbar" ng-class="type && 'progress-bar-' + type" style="width: 2%;"></div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>