    
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

	    	
	    	if ( $("#iBC").length ){ 
    	    	bc = document.getElementById('iBC').value;
    	    	document.getElementById('iBC_'+id).innerHTML = bc; 
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

	    	if ( $("#iNome").length ){ 
         	    var nome = document.getElementById('iNome').value; 
         	} else { 
                var nome = document.getElementById('iNome_'+id).value; 
            }

     	    if ( $("#iBC").length ){ 
         	    var bc = document.getElementById('iBC').value; 
         	} else { 
             	var bc = document.getElementById('iBC_'+id).value; 
            }
     	   
     	    var url = "<?php echo site_url('param/unit_med/save')?>";
      	  
	    	$.post(url, {unidade_nome:nome, unidade_sigla:bc}, function(data,status){	    		  	
	    		    document.getElementById('alert_content').innerHTML = data;	    		    	              
	    		    document.getElementById('iNome').value = '';		            
	    		    document.getElementById('iBC').value = '';	
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

	    	 var url = "<?php echo site_url('param/unit_med/delete')?>";

	            $.post(url,{
	            	regra_ids:z            	
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
        	 var url = "<?php echo site_url('param/unit_med/set_page_table')?>";

	            $.post(url,{
	            	unidade_page:page            	
	            },function(data,status){		            
		            document.getElementById('scope').innerHTML = data;
	            });	 
        }


        function active_item(id)
        {   
        	var url = "<?php echo site_url('param/unit_med/status')?>";
        	         
        	if (document.getElementById("active_"+id).getAttribute('class') == 'active'){
        	    $.post(url,{unidade_id:id,unidade_deletado:'1'},function(data,status){		            
	            	document.getElementById("active_"+id).setAttribute('class', '');
	            	document.getElementById("active_"+id).innerHTML = '<i class="fa fa-times text-danger text-active"></i>';
	            	document.getElementById('alert_content').innerHTML = data;  
	            });    
        	} else {        	   	
        	   	$.post(url,{unidade_id:id,unidade_deletado:'0'},function(data,status){		            
        	   		document.getElementById("active_"+id).setAttribute('class', 'active');
        	   		document.getElementById("active_"+id).innerHTML = '<i class="fa fa-check text-success text-active"></i>';
        	   		document.getElementById('alert_content').innerHTML = data;    
	            });
        	}

    	    
        }
    </script>
        
        