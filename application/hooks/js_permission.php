                    <script type="text/javascript">

                    function showMethodAppend(id)
                    {	
                                                
                    	document.getElementById('btn_save').removeAttribute('disabled');
            	    	document.getElementById('btn_save').setAttribute('class', 'btn btn-sm btn-success');
            	    	
            	    	document.getElementById('btn_cancel').removeAttribute('disabled');
            	    	document.getElementById('btn_cancel').setAttribute('class', 'btn btn-sm btn-danger');

            	    	var url = "<?php echo site_url('security/permission/set_permission');?>";
            	    	
            	    	$.post(url,{user_id:id}, function(data, status){                	    	
            	    		document.getElementById('body_permission').innerHTML = data;
            	    	});
                    }

                    
                    function showMethodCancel()
                    {
                    	document.getElementById('btn_save').setAttribute('disabled', 'disabled');
            	    	document.getElementById('btn_save').setAttribute('class', 'btn btn-sm btn-default');
            	    	
            	    	document.getElementById('btn_cancel').setAttribute('disabled', 'disabled');
            	    	document.getElementById('btn_cancel').setAttribute('class', 'btn btn-sm btn-default');

            	    	document.getElementById('body_permission').innerHTML = '';
                    }

                             
                    function showMethodSave(id)
            	    {
                    	var url = "<?php echo site_url('security/permission/save')?>";

            	    	var x = document.getElementsByTagName("input");
                        var c = new Array();
                        var z = new Array();
                        var i = 0;

                        
                        
                        a = 0;

                        for (i=0; i<=x.length-1; i++){
            	            if(x[i].type == "checkbox"){
            		            c[a] = x[i];
            		            a++
            	            }
                        }

                        
                        for (i=0; i<=c.length-1; i++){
            	    	    if(c[i].checked==true){        	    	      
            	    	    	   z[i] = c[i].value;
            	    	    }
            	    	}


            	        var user_id = document.getElementById('actCtrl').value;

            	    	
            	    	$.post(url, {db_user_id:user_id, permition_list:z}, 
            	    	    function(data,status){	    		   
            	    		    document.getElementById('alert_content').innerHTML = data;
            	    		    sleep(2000);    		   	    
            		    	});
            	    	 
            	    }

                    function sleep(milliseconds) {            
                    	setTimeout(function () {window.location.reload()}, milliseconds);      	        
                    }
                    
                    function btnApplication()
                    {            
                        var actCtrl = document.getElementById('actCtrl').value 
                        showMethodAppend(actCtrl);
                        
                        //document.getElementById('btn_application').setAttribute('onclick', 'showMethodAppend('+actCtrl+')');
                    }
                
</script>