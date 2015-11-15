<!DOCTYPE html>
<html lang="br" data-ng-app="app">
<head>
  <meta charset="utf-8" />
  <title>Ferraz Fernandes</title>
  <meta name="description" content="app, web app, responsive, responsive layout, admin, admin panel, admin dashboard, flat, flat ui, ui kit, AngularJS, ui route, charts, widgets, components" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="<?php echo $this->config->base_url();?>assets/src/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $this->config->base_url();?>assets/src/css/animate.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $this->config->base_url();?>assets/src/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $this->config->base_url();?>assets/src/css/simple-line-icons.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $this->config->base_url();?>assets/src/css/font.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $this->config->base_url();?>assets/src/css/app.css" type="text/css" />
  
  <?php if(isset($header)){ echo $header; }?>
  
</head>
<body ng-controller="AppCtrl">
    <div class="app" id="app" ng-class="{'app-header-fixed':app.settings.headerFixed, 'app-aside-fixed':app.settings.asideFixed, 'app-aside-folded':app.settings.asideFolded, 'app-aside-dock':app.settings.asideDock, 'container':app.settings.container}" ui-view>
