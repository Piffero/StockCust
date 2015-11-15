

  <!-- jQuery -->
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/jquery/jquery.min.js"></script>

  <!-- Angular -->
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular.js"></script>
  
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-animate/angular-animate.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-cookies/angular-cookies.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-resource/angular-resource.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-sanitize/angular-sanitize.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-touch/angular-touch.js"></script>
  <!-- Vendor -->
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-ui-router/angular-ui-router.js"></script> 
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/ngstorage/ngStorage.js"></script>

  <!-- bootstrap -->
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-bootstrap/ui-bootstrap-tpls.js"></script>
  <!-- lazyload -->
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/oclazyload/ocLazyLoad.js"></script>
  <!-- translate -->
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-translate/angular-translate.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-translate/loader-static-files.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-translate/storage-cookie.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/vendor/angular/angular-translate/storage-local.js"></script>

  <!-- App -->
  <script src="<?php echo $this->config->base_url();?>assets/src/js/app.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/config.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/config.lazyload.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/config.router.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/main.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/services/ui-load.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/filters/fromNow.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/directives/setnganimate.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/directives/ui-butterbar.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/directives/ui-focus.js"></script>
  
  <script src="<?php echo $this->config->base_url();?>assets/src/js/directives/ui-jq.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/directives/ui-module.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/directives/ui-nav.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/directives/ui-scroll.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/directives/ui-shift.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/directives/ui-toggleclass.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/directives/ui-validate.js"></script>
  <script src="<?php echo $this->config->base_url();?>assets/src/js/controllers/bootstrap.js"></script>
  
  
  <!-- Lazy loading -->
 
  <script src="<?php echo base_url();?>assets/src/js/jquery.nestable.js"></script>
  <script>

	$(document).ready(function()
	{
	
	    var updateOutput = function(e)
	    {
	        var list   = e.length ? e : $(e.target),
	            output = list.data('output');
	        if (window.JSON) {
	            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
	        } else {
	            output.val('JSON browser support required for this demo.');
	        }
	    };
	
	    // activate Nestable for list 1
	    $('#nestable').nestable({
	        group: 1
	    })
	    .on('change', updateOutput);
	
	    // activate Nestable for list 2
	    $('#nestable2').nestable({
	        group: 1
	    })
	    .on('change', updateOutput);
	
	    // output initial serialised data
	    updateOutput($('#nestable').data('output', $('#nestable-output')));
	    updateOutput($('#nestable2').data('output', $('#nestable2-output')));
	
	    $('#nestable-menu').on('click', function(e)
	    {
	        var target = $(e.target),
	            action = target.data('action');
	        if (action === 'expand-all') {
	            $('.dd').nestable('expandAll');
	        }
	        if (action === 'collapse-all') {
	            $('.dd').nestable('collapseAll');
	        }
	    });
	
	    $('#nestable3').nestable();
	
	});
  </script>
  