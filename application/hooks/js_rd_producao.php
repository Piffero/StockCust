    
    <script> 
     
        function checkall()
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

	    	for (i=1; i<=c.length-1; i++){
	    	    if(c[0].checked==true){    	    	 
	    	    	   c[i].checked = true;    	    	    	   	    
	    	    } else if(c[0].checked==false){
		    	    c[i].checked = false;
	    	    }
	    	}

        } 


        function outinput_pdf()
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
		    	      if(i != 0){       	    	      
		    	    	  z[i] = c[i].id+":"+document.getElementById('qtde_'+c[i].id).value;
		    	      }	    	    	   
		    	      c[i].checked = false    	    	    	   	    
	    	    }
	    	}


	    	var url = "<?php echo site_url('rdreport/rd_producao/set_post')?>";
	        
	        $.post(url,{num_pacotes:z},function(data,status){			       
		        var myWindow = window.open("<?php echo site_url('rdreport/rd_producao/outinput_pdf')?>", "_blank");	 	   		
	        });
	        

        }
    </script>