<?php $this->load->view('partial/header'); ?>

<div class="container w-xxl w-auto-xs" ng-controller="SigninFormController">
          <a href class="navbar-brand block m-t">Ferras Fernandes</a>
          <div class="m-b-lg">
            <div class="wrapper text-center">
              <strong>Informe seus dados para entrar no sistema</strong>
            </div>
            <form name="form" class="form-validation" method="post">
              <div class="text-danger wrapper text-center" ng-show="authError">
                  <?php if(isset($alert_massager)){echo alert_massager;} ?>
              </div>
              <div class="list-group list-group-sm">
                <div class="list-group-item">
                  <input type="text" placeholder="Nome de Usuário" name="username" class="form-control no-border"required>
                </div>
                <div class="list-group-item">
                   <input type="password" placeholder="Senha de Acesso" name="password" class="form-control no-border" required>
                </div>
              </div>
              <button type="submit" class="btn btn-lg btn-primary btn-block" ng-click="login()">Entrar</button>              
            </form>
          </div>
          <div class="text-center" ng-include="'tpl/blocks/page_footer.html'"></div>
        </div>  
  </div>

  <?php $this->load->view('partial/footer'); ?>
  