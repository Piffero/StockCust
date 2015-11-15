    
    <script>
        function showMethodAppend()
        {
        	document.getElementById('rowMethodData').removeAttribute("style");
        	
        	document.getElementById('btn_save').removeAttribute('disabled');
	    	document.getElementById('btn_save').setAttribute('class', 'btn btn-sm btn-success');
	    	
	    	document.getElementById('btn_cancel').removeAttribute('disabled');
	    	document.getElementById('btn_cancel').setAttribute('class', 'btn btn-sm btn-danger');            
        }
        
        function showMethodCancel()
        {
        	document.getElementById('rowMethodData').setAttribute('style', 'display: none;');
        	
        	document.getElementById('btn_save').setAttribute('disabled', 'disabled');
	    	document.getElementById('btn_save').setAttribute('class', 'btn btn-sm btn-default');
	    	
	    	document.getElementById('btn_cancel').setAttribute('disabled', 'disabled');
	    	document.getElementById('btn_cancel').setAttribute('class', 'btn btn-sm btn-default');

	    	var x = document.getElementsByTagName("input");
            var c = new Array();
            var z = new Array();
            var i = 0;

            a = 0;

            for (i=0; i<=x.length-1; i++){
	            if(x[i].type == "checkbox"){
		            c[a] = x[i]
		            a++
	            }
            }

            
	    	var checked = false;

	    	for (i=0; i<=c.length-1; i++){
	    	    if(c[i].checked==true){    	    	 
	    	    	   c[i].checked = false    	    	    	   	    
	    	    }
	    	}



	    	if ( $("#iCodigo").length ){ 
    	    	codigo = document.getElementById('iCodigo').value;
    	    	document.getElementById('iCodigo_'+id).innerHTML = codigo; 
    	    }

	    	if( $("#iNome").length ){
        	    nome = document.getElementById('iNome').value;
        	    document.getElementById('iNome_'+id).innerHTML = nome;
    	    }

	    	
	    	if ( $("#iRegra").length ){ 
    	    	unid = document.getElementById('iRegra').value;
    	    	document.getElementById('iRegra_'+id).innerHTML = unid; 
    	    }
	    	
        }
        
        function showMethodSave(id)
	    {
        	document.getElementById('rowMethodData').setAttribute('style', 'display: none;');

        	document.getElementById('btn_save').setAttribute('disabled', 'disabled');
	    	document.getElementById('btn_save').setAttribute('class', 'pull-right btn btn-default');
	    	document.getElementById('btn_save').setAttribute('onclick','showMethodSave('+id+');');
	    	
	    	document.getElementById('btn_cancel').setAttribute('disabled', 'disabled');
	    	document.getElementById('btn_cancel').setAttribute('class', 'pull-right btn btn-default');

	    	if( $("#iNome").length ){
	    	    var nome = document.getElementById('iNome').value;	    
   	        }

	    	
	    	if ( $("#iRegra").length ){ 
	    		var regra = document.getElementById('iRegra').value;
   	        }
   	        
     	    var url = "<?php echo site_url('product/fillings/save')?>/"+id;
      	  
	    	$.post(url, {recheio_nome:nome, recheio_regra:regra}, 
	    	    function(data,status){	    		   
	    		    document.getElementById('alert_content').innerHTML = data;	    		   
	    		    sleep(2000);    		   	    
		    	});
	    }


        function showMethodSaveItem(id, e)
	    {
        	var url = "<?php echo site_url('product/fillings/save_item')?>/"+id;
        	
	    	if( $("#input_item_materia_"+id).length ){
	    	    var materia = document.getElementById('input_item_materia_'+id).value;	
   	        }
	    	
	    	if ( $("#input_item_qtde_"+id).length ){ 
	    		var qtde = document.getElementById('input_item_qtde_'+id).value;
   	        }

	    	var keycode;
        	if (window.event) keycode = window.event.keyCode;
        	else if (e) keycode = e.which;
               if(keycode == 13){
            	   $.post(url, {db_materia_id:materia, r_items_qtde:qtde}, 
           	    	    function(data,status){	    		
          	    	       document.getElementById('alert_content').innerHTML = data;	    		   
           	    		   sleep(2000);    		   	    
           		    	});
               }

            
	    }

	    
        function sleep(milliseconds) {            
        	setTimeout(function () {window.location.reload()}, milliseconds);      	        
        }
        
        function showMethodDelete()
        {
            
        	var x = document.getElementsByTagName("input");
            var c = new Array();
            var z = new Array();
            var i = 0;

            a = 0;

            for (i=0; i<=x.length-1; i++){
	            if(x[i].type == "checkbox"){
		            c[a] = x[i]
		            a++
	            }
            }

	    	var checked = false;

	    	for (i=0; i<=c.length-1; i++){
	    	    if(c[i].checked==true){        	    	      
	    	    	   z[i] = c[i].id;
	    	    	   c[i].checked = false    	    	    	   	    
	    	    }
	    	}
	    	
	    	 var url = "<?php echo site_url('product/fillings/delete')?>";

	            $.post(url,{
	            	materia_ids:z
	            },function(data,status){		            
		            document.getElementById('alert_content').innerHTML = data;
		            sleep(2000);	                        	                
	            });	            
        }


        function showMethodPDF()
        {
            
        	var x = document.getElementsByTagName("input");
            var c = new Array();
            var z = new Array();
            var i = 0;

            a = 0;

            for (i=0; i<=x.length-1; i++){
	            if(x[i].type == "checkbox"){
		            c[a] = x[i]
		            a++
	            }
            }

	    	var checked = false;

	    	for (i=0; i<=c.length-1; i++){
	    	    if(c[i].checked==true){        	    	      
	    	    	   z[i] = c[i].id;
	    	    	   c[i].checked = false    	    	    	   	    
	    	    }
	    	}

	    	
        }

        
        function btnApplication()
        {            
            var actCtrl = document.getElementById('actCtrl').value 
            
            if(actCtrl == '0') {
				showMethodAppend();
            }

            if(actCtrl == '1') {
            	showMethodDelete();
            }

            if(actCtrl == '2') {
            	showMethodPDF();
            }
            
        }


        function page_go(page)
        {
        	 var url = "<?php echo site_url('product/fillings/set_page_table')?>";

	            $.post(url,{
	            	recheio_page:page            	
	            },function(data,status){		            
		            document.getElementById('scope').innerHTML = data;
	            });	 
        }


        function active_item(id)
        {   
        	var url = "<?php echo site_url('product/fillings/status')?>";        	
        	if (document.getElementById("active_"+id).getAttribute('class') == 'active'){
        	    $.post(url,{recheio_id:id,recheio_deletado:'1'},function(data,status){		            
	            	document.getElementById("active_"+id).setAttribute('class', '');
	            	document.getElementById("active_"+id).innerHTML = '<i class="fa fa-times text-danger text-active"></i>';
	            	document.getElementById('alert_content').innerHTML = data;  
	            });    
        	} else {        	   	
        	   	$.post(url,{recheio_id:id,recheio_deletado:'0'},function(data,status){		            
        	   		document.getElementById("active_"+id).setAttribute('class', 'active');
        	   		document.getElementById("active_"+id).innerHTML = '<i class="fa fa-check text-success text-active"></i>';
        	   		document.getElementById('alert_content').innerHTML = data;    
	            });
        	}
        }

        function active_subitem(id)
        {
        	if (document.getElementById("open_"+id).getAttribute('class') == 'active'){
        		document.getElementById("open_"+id).setAttribute('class', '');
        		document.getElementById("open_"+id).innerHTML = '<i class="fa fa-plus text-success text-active"></i>';
        		document.getElementById("rowMethodSubData_"+id).setAttribute('style', 'display: none;');        		
        	} else {
        		document.getElementById("open_"+id).setAttribute('class', 'active');
        		document.getElementById("open_"+id).innerHTML = '<i class="fa fa-minus text-danger text-active"></i>';
        		document.getElementById("rowMethodSubData_"+id).removeAttribute('style');        		
        	}
        }



        function delete_subitem(id)
        {
            var url = "<?php echo site_url('product/fillings/delete_items')?>/"+id;
            
            $.post(url,{subitem_id:id},function(data,status){		            
    	   		document.getElementById('alert_content').innerHTML = data; 
    	   		sleep(2000);   
            });
        }

        function setFocus(e) {
        	var keycode;
        	if (window.event) keycode = window.event.keyCode;
        	else if (e) keycode = e.which;
               if(keycode == 13){
            	   document.form_subitem.input_item_qtde.focus();
               }               
        }


        function showMethodEdit(id) {
			$qtde = document.getElementById('r_items_qtde_'+id).innerHTML;
			document.getElementById('r_items_qtde_'+id).innerHTML = '<input id="i_qtde" type="text" value='+$qtde+' onkeypress="if (event.keyCode==13){ update_item(this.value,'+id+');return false;}">';			
        }


        function update_item(qtde, id)
        {
        	document.getElementById('r_items_qtde_'+id).innerHTML = qtde;			
			var url = "<?php echo site_url('product/fillings/update_item')?>";

			$.post(url,{r_items_id:id, r_items_qtde:qtde},function(data,status){
				document.getElementById('alert_content').innerHTML = data;
	            sleep(2000);
			});
        }   


        function showMethodEdit(id)
        {
			var nome = document.getElementById('recheio_recheio_nome_'+id).innerHTML;
			var regra= document.getElementById('recheio_recheio_regra_'+id).innerHTML;

			document.getElementById('recheio_recheio_nome_'+id).innerHTML = '<input type="text" id="URecheio_Nome" onkeypress="if (event.keyCode==13){ update_recheio(this.value,'+id+');return false;}" value="'+nome+'">';
			document.getElementById('recheio_recheio_regra_'+id).innerHTML = '<input type="text" id="URecheio_Regra" onkeypress="if (event.keyCode==13){ update_recheio(this.value,'+id+');return false;}" value="'+regra+'">';
			
        }


        function update_recheio(valor, id)
        {
        	if( $("#URecheio_Nome").length ){
	    	    var nome = document.getElementById('URecheio_Nome').value;	    
   	        }

	    	
	    	if ( $("#URecheio_Regra").length ){ 
	    		var regra = document.getElementById('URecheio_Regra').value;
   	        }

	    	var url = "<?php echo site_url('product/fillings/save')?>/"+id;

	    	$.post(url, {recheio_nome:nome, recheio_regra:regra}, 
       	    	    function(data,status){	    		
      	    	       document.getElementById('alert_content').innerHTML = data;	    		   
       	    		   sleep(2000);    		   	    
       		    	});
        }
    </script>
        
        