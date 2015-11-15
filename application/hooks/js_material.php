    
    <script>
        function showMethodAppend()
        {
        	document.getElementById('rowMethodData').removeAttribute("style");
        	
        	document.getElementById('btn_save').removeAttribute('disabled');
	    	document.getElementById('btn_save').setAttribute('class', 'btn btn-sm btn-success');
	    	
	    	document.getElementById('btn_cancel').removeAttribute('disabled');
	    	document.getElementById('btn_cancel').setAttribute('class', 'btn btn-sm btn-danger');            
        }



        function showMethodEdit(id)
        {
        	var materia_id = document.getElementById('col_materia_id_'+id).innerHTML;
        	var materia_nome = document.getElementById('col_materia_nome_'+id).innerHTML;
        	var materia_unid = document.getElementById('col_materia_unid_'+id).innerHTML;
        	var materia_valor = document.getElementById('col_materia_valor_'+id).innerHTML;
        	var materia_fator = document.getElementById('col_materia_fator_'+id).innerHTML;

        	document.getElementById('col_materia_id_'+id).innerHTML = '<a class="active" href="javascript:edit_item('+id+');"><i class="fa fa-save text-success"></i></a>&emsp;<a class="active" href="javascript:cancel_item('+id+');"><i class="fa fa-undo text-danger"></i></a>&emsp;'+materia_id;
        	document.getElementById('col_materia_nome_'+id).innerHTML = '<input type="text" id="materia_nome_'+id+'" value="'+materia_nome+'">';
        	document.getElementById('col_materia_unid_'+id).innerHTML = '<input type="text" id="materia_unid_'+id+'" value="'+materia_unid+'">';
        	document.getElementById('col_materia_valor_'+id).innerHTML = '<input type="text" id="materia_valor_'+id+'" value="'+materia_valor+'">';
        	document.getElementById('col_materia_fator_'+id).innerHTML = '<input type="text" id="materia_fator_'+id+'" value="'+materia_fator+'">';
        	
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

	    	
	    	if ( $("#iUN").length ){ 
    	    	unid = document.getElementById('iUN').value;
    	    	document.getElementById('iUN_'+id).innerHTML = unid; 
    	    }

	    	if ( $("#iValue").length ){ 
    	    	value = document.getElementById('iValue').value;
    	    	document.getElementById('iValue_'+id).innerHTML = value; 
    	    }

	    	if ( $("#iFC").length ){ 
    	    	FC = document.getElementById('iFC').value;
    	    	document.getElementById('iFC_'+id).innerHTML = FC; 
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

	    	
	    	if ( $("#iUN").length ){ 
	    		var unid = document.getElementById('iUN').value;
   	        }

	    	if ( $("#iValue").length ){ 
	    		var value = document.getElementById('iValue').value;
	    	}

	    	if ( $("#iFC").length ){ 
	    		var FC = document.getElementById('iFC').value;
	    	}
	   	    
     	    var url = "<?php echo site_url('product/raw_material/save')?>";
     	   
	    	$.post(url, {materia_nome:nome, materia_unid:unid, materia_valor:value, materia_fator:FC}, 
	    	    function(data,status){	    		   
	    		    document.getElementById('alert_content').innerHTML = data;	    		   
	    		    sleep(2000);    		   	    
		    	});
	    	
	    	 
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

	    	 var url = "<?php echo site_url('product/raw_material/delete')?>";

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
        	 var url = "<?php echo site_url('product/raw_material/set_page_table')?>";

	            $.post(url,{
	            	materia_page:page            	
	            },function(data,status){		            
		            document.getElementById('scope').innerHTML = data;
	            });	 
        }

        function active_item(id)
        {   
        	var url = "<?php echo site_url('product/raw_material/status')?>";
        	         
        	if (document.getElementById("active_"+id).getAttribute('class') == 'active'){
        	    $.post(url,{materia_id:id,materia_deletado:'1'},function(data,status){		            
	            	document.getElementById("active_"+id).setAttribute('class', '');
	            	document.getElementById("active_"+id).innerHTML = '<i class="fa fa-times text-danger text-active"></i>';
	            	document.getElementById('alert_content').innerHTML = data;  
	            });    
        	} else {        	   	
        	   	$.post(url,{materia_id:id,materia_deletado:'0'},function(data,status){		            
        	   		document.getElementById("active_"+id).setAttribute('class', 'active');
        	   		document.getElementById("active_"+id).innerHTML = '<i class="fa fa-check text-success text-active"></i>';
        	   		document.getElementById('alert_content').innerHTML = data;    
	            });
        	}

    	    
        }


        
        function edit_item(id){
            
        	if( $("#materia_nome_"+id).length ){
	    	    var nome = document.getElementById('materia_nome_'+id).value;	    
   	        }

	    	
	    	if ( $("#materia_unid_"+id).length ){ 
	    		var unid = document.getElementById('materia_unid_'+id).value;
   	        }

	    	if ( $("#materia_valor_"+id).length ){ 
	    		var value = document.getElementById('materia_valor_'+id).value;
	    	}

	    	if ( $("#materia_fator_"+id).length ){ 
	    		var FC = document.getElementById('materia_fator_'+id).value;
	    	}
	   	    
     	    var url = "<?php echo site_url('product/raw_material/save')?>/"+id;
     	   
	    	$.post(url, {materia_nome:nome, materia_unid:unid, materia_valor:value, materia_fator:FC}, 
	    	    function(data,status){	    		   
	    		    document.getElementById('alert_content').innerHTML = data;	    		   
	    		    sleep(2000);    		   	    
		    	});
        }

        function cancel_item(id){
            
        	var materia_id = id;
        	var materia_nome = document.getElementById('materia_nome_'+id).value;
        	var materia_unid = document.getElementById('materia_unid_'+id).value;
        	var materia_valor = document.getElementById('materia_valor_'+id).value;
        	var materia_fator = document.getElementById('materia_fator_'+id).value;

        	document.getElementById('col_materia_id_'+id).innerHTML = materia_id;
        	document.getElementById('col_materia_nome_'+id).innerHTML = materia_nome;
        	document.getElementById('col_materia_unid_'+id).innerHTML = materia_unid;
        	document.getElementById('col_materia_valor_'+id).innerHTML = materia_valor;
        	document.getElementById('col_materia_fator_'+id).innerHTML = materia_fator;
        }
        
    </script>
        
        