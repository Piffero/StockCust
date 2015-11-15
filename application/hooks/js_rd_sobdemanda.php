 
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


    function active_subitem(id)
    {        
    	if (document.getElementById("open_"+id).getAttribute('class') == 'active'){
    		document.getElementById("open_"+id).setAttribute('class', '');
    		document.getElementById("open_"+id).innerHTML = '<i class="fa fa-plus text-success text-active"></i>';
    		document.getElementById("showDistictMateria_"+id).setAttribute('style', 'display: none;');        		
    	} else {
    		document.getElementById("open_"+id).setAttribute('class', 'active');
    		document.getElementById("open_"+id).innerHTML = '<i class="fa fa-minus text-danger text-active"></i>';
    		document.getElementById("showDistictMateria_"+id).removeAttribute('style');        		
    	}
    }

    function outinput_pdf()
    {

    	var x = document.getElementsByTagName("input");
        var c = new Array();
        var d = new Array();
        var z = new Array();
        var i = 0;
        var j = 0;

        a = 0;
        b = 0;
        y = 0;

        for (i=0; i<=x.length-1; i++){
            if(x[i].type == "checkbox"){
	            c[a] = x[i]
	            a++
            }

            if(x[i].type == "text"){
                d[b] = x[i]
                b++
            }
        }

       
     	var checked = false;

     	for (i=0; i<=c.length-1; i++){
     	    if(c[i].checked==true){ 
 	    	      if(i != 0){
 	 	    	      //alert('empanada id: '+c[i].id);
 	 	    	      for (j=0; j<d.length; j++){
 	 	 	    	      
 	 	 	    	      var retorno = d[j].id.split("_");
 	 	 	    	      if(c[i].id == retorno[0])
 	 	 	    	      {
 	 	 	    	    	  //alert('Valor Item: '+d[j].value);
 	 	 	    	    	  z[y] = c[i].id+":"+retorno[1]+":"+d[j].value;
 	 	 	    	    	  y++;
 	 	 	    	      } 	 	 	    	      
 	 	    	      }      	    	      
 	    	    	  
 	    	      }
 	    	      c[i].checked = false    	    	    	   	    
     	    }
     	}
     	
    	var url = "<?php echo site_url('rdreport/rd_sobdemanda/set_post')?>";
        
         $.post(url,{num_pacotes:z},function(data,status){			       
	        var myWindow = window.open("<?php echo site_url('rdreport/rd_sobdemanda/outinput_pdf')?>", "_blank");	 	   		
         });
        

    }
     
</script>