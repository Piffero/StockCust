<?php $this->load->view('partial/header'); ?>

<div class="app-content ng-scope">
       <div class="app-content-body fade-in-up ng-scope" ui-view="">
            
            <div class="ng-scope" ui-view="">
                <div class="bg-light lter b-b wrapper-md ng-scope">
                    <h1 class="m-n font-thin h3">Materia Prima <?php if(isset($title)) {echo $title;}?></h1>
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
	function clear_data()
	{
		var url = "<?php echo site_url('rdreport/rd_new_sobdemanda/truncate_material'); ?>";
		truncate = 1;
		$.post(url,{action:truncate},function(data,status){
			document.getElementById('alert_content').innerHTML = data;				
			window.setTimeout(function(){
				window.location.reload(true)
			}, 2000 );
							
												
		});
	}


	function insert_data()
	{
		var url = "<?php echo site_url('rdreport/rd_new_sobdemanda/insert_material'); ?>";
		var codigo = document.getElementById('iCodigo').value;
		var qtde = document.getElementById('iQtde').value; 
		
		$.post(url,{code:codigo, amount:qtde},function(data,status){
			document.getElementById('alert_content').innerHTML = data;				
			window.setTimeout(function(){
				window.location.reload(true)
			}, 2000 );
							
												
		});
	}

	

	function delete_data(id)
	{
		var url = "<?php echo site_url('rdreport/rd_new_sobdemanda/delete_material'); ?>";
		
		$.post(url,{code:id},function(data,status){
			document.getElementById('alert_content').innerHTML = data;				
			window.setTimeout(function(){
				window.location.reload(true)
			}, 2000 );
							
												
		});
	}


	function update_data(id)
	{
		var qtde = document.getElementById('field_'+id).innerHTML;
		document.getElementById('field_'+id).innerHTML = '<input id="input_'+id+'" value="'+qtde+'" onkeypress="update_set(event, '+id+')">';			
	}


	function update_set(e, id){		
		 if(e.keyCode === 13){
			 var url = "<?php echo site_url('rdreport/rd_new_sobdemanda/update_material');?>";
			 var qtde = document.getElementById('input_'+id).value;
			 $.post(url,{code:id , value:qtde},function(data,status){
					document.getElementById('alert_content').innerHTML = data;				
					window.setTimeout(function(){
						window.location.reload(true)
					}, 2000 );
	        });
		 }
	}
</script>

<?php $this->load->view('partial/footer'); ?>