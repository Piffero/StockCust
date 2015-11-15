    
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

	    	
	    	if ( $("#iNomeUsuario").length ){ 
	    		var nomeUsuario = document.getElementById('iNomeUsuario').value;
   	        }

	    	if ( $("#iSenha").length ){ 
	    		var senha = document.getElementById('iSenha').value;
		    }
	   	    
     	    var url = "<?php echo site_url('security/user/save')?>";
     	   
	    	$.post(url, {user_nome:nome, user_nome_usuario:nomeUsuario, user_senha:senha}, 
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

	    	 var url = "<?php echo site_url('security/user/delete')?>";

	            $.post(url,{
	            	user_ids:z            	
	            },function(data,status){		            
		            document.getElementById('alert_content').innerHTML = data;
		            sleep(2000);	                        	                
	            });	            
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
            
        }


        function page_go(page)
        {
        	 var url = "<?php echo site_url('security/user/set_page_table')?>";

	            $.post(url,{
	            	user_page:page            	
	            },function(data,status){		            
		            document.getElementById('scope').innerHTML = data;
	            });	 
        }


        function active_item(id)
        {   
        	var url = "<?php echo site_url('security/user/status')?>";
        	         
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

        function search(object, event)
	    {
    	    
    	    if(event.keyCode == 13)
        	{
    	    	var URL = "<?php echo site_url('security/user/search');?>";
            	var busca = object.value;
            	            	
                  $.post(URL,{search:busca},function(data,status){                                                           
                      document.getElementById('scope').innerHTML = data;
       	    	  });      	    	
    	    }
    	    
	    }
    </script>
        
        