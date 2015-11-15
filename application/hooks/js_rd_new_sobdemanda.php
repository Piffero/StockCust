
<script>
	function popup_material(){
		var url = "<?php echo site_url('rdreport/rd_new_sobdemanda/set_material');?>";
		var myWindow = window.open(url, "Materia-Prima", "width=760, height=400, scrollbars=yes");
	}

	function outinput_pdf(){
		var url = "<?php echo site_url('rdreport/rd_new_sobdemanda/set_post'); ?>";
		var textarea = document.getElementById('nestable2-output').value;	
						
		$.post(url,{json:textarea},function(data,status){
			var myWindow = window.open("<?php echo site_url('rdreport/rd_new_sobdemanda/outinput_pdf')?>", "_blank");		
												
		});
	}
	
</script>