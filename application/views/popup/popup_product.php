<?php $this->load->view('partial/header'); ?>

<div class="app-content ng-scope">
       <div class="app-content-body fade-in-up ng-scope" ui-view="">
            
            <div class="ng-scope" ui-view="">
                <div class="bg-light lter b-b wrapper-md ng-scope">
                    <h1 class="m-n font-thin h3">Preços de Venda <?php if(isset($title)) {echo $title;}?></h1>
                </div>
                
                <div id="alert_content"><?php if(isset($alert)) { echo $alert; }?></div>
                
                <div id="scope" class="wrapper-md ng-scope">
                    <?php if(isset($panel_default)) { echo $panel_default; }?>                    
                </div>
            </div>
            
            <div class="app-footer wrapper b-t bg-light ng-scope">
                <span class="pull-right ng-binding">1.3.3 <a class="m-l-sm text-muted" ui-scroll="app" href=""><i class="fa fa-long-arrow-up"></i></a></span>© 2014 Copyright.        
            </div>
    </div>
</div>

<script src="<?php echo $this->config->base_url();?>assets/src/vendor/jquery/jquery.min.js"></script>

<script>

   function popup_sales_reboot()
   {
	   
	  var valor_margem     = <?php if(isset($sales_info->empanada_margem)){print_r($sales_info->empanada_margem);}else{ echo 40;}?>;
	  var valor_unidade    = <?php if(isset($sales_info->empanada_cu)){echo $sales_info->empanada_cu;}else{ echo 0;}?>;
	  var valor_pacote     = <?php if(isset($sales_info->empanada_cp)){echo $sales_info->empanada_cp;}else{ echo 0;}?>;
	  var valor_caixa      = <?php if(isset($sales_info->empanada_cc)){echo $sales_info->empanada_cc;}else{ echo 0;}?>;

	  document.getElementById('iMargem').value = valor_margem;
	  document.getElementById('iCUnita').value = valor_unidade;
	  document.getElementById('iCPacot').value = valor_pacote;
	  document.getElementById('iCCaixa').value = valor_caixa;	  
   }


   function popup_sales_proced()
   {
	   var custo_unidade = <?php if(isset($custo_unidade)){echo $custo_unidade;}else{ echo 0;}?>;

	   var get_margem     = <?php if(isset($sales_info->empanada_margem)){print_r($sales_info->empanada_margem);}else{ echo 40;}?>;
	   var get_unidade    = <?php if(isset($sales_info->empanada_cu)){echo $sales_info->empanada_cu;}else{ echo 0;}?>;
	   var get_pacote     = <?php if(isset($sales_info->empanada_cp)){echo $sales_info->empanada_cp;}else{ echo 0;}?>;
	   var get_caixa      = <?php if(isset($sales_info->empanada_cc)){echo $sales_info->empanada_cc;}else{ echo 0;}?>;
	   
	   
	   var set_margem  = document.getElementById('iMargem').value;
	   var set_unidade = document.getElementById('iCUnita').value;
	   var set_pacote  = document.getElementById('iCPacot').value;
	   var set_caixa   = document.getElementById('iCCaixa').value;

	   if (get_margem != set_margem ) {

		   var y1 = parseFloat(set_margem - (set_margem * 2))
		   var x1 = parseFloat(y1 + 100);
		   var x2 = parseFloat(x1/100);
		   var soma = parseFloat(custo_unidade / x2);
		   set_unidade = soma;
		   set_pacote  = (set_unidade * 10);
		   set_caixa   = (set_pacote * 6); 		      
		   
	   } else if(get_unidade != set_unidade){
		   	   
		   var x1 = parseFloat(get_unidade / set_unidade); 		   		   
		   set_margem = (100 -(x1 * 100));		   
		   set_pacote  = (set_unidade * 10);
		   set_caixa   = (set_pacote * 6);
		   		   
	   } else if(get_pacote != set_pacote){
		   		   
		   var x1 = parseFloat(get_pacote / set_pacote); 		   		   
		   set_margem = (100 -(x1 * 100));
		   set_unidade = (set_pacote / 10);	
		   set_caixa   = (set_pacote * 6);
		   
	   } else if(get_caixa != set_caixa){

		   var x1 = parseFloat(get_caixa / set_caixa); 		   
		   set_pacote = parseFloat(set_caixa / 6);
		   set_unidade = parseFloat(set_pacote / 10);		   
		   set_margem = (100 -(x1 * 100));
		   	
	   } else {
		   alert('Não a mudanças de valores');
	   }

	      document.getElementById('iMargem').value = parseFloat(set_margem).toFixed(2);
		  document.getElementById('iCUnita').value = parseFloat(set_unidade).toFixed(2);
		  document.getElementById('iCPacot').value = parseFloat(set_pacote).toFixed(2);
		  document.getElementById('iCCaixa').value = parseFloat(set_caixa).toFixed(2);
		
   }
   
   
   function popup_sales_submit(id)
   {	  
	   var set_margem  = document.getElementById('iMargem').value;

	   var url = "<?php echo site_url('product/end_product/save')?>/"+id;
       
       $.post(url,{empanada_margem:set_margem},function(data,status){		            
	   		document.getElementById('alert_content').innerHTML = data; 
	   		window.opener.location.reload();
	   		window.close();   
       });
   }
           
</script>

<?php $this->load->view('partial/footer'); ?>