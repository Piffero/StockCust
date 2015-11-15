<div class="app-content ng-scope">
       <div class="app-content-body fade-in-up ng-scope" ui-view="">
            
            <div class="ng-scope" ui-view="">
                <div class="bg-light lter b-b wrapper-md ng-scope">
                    <h1 class="m-n font-thin h3"><?php if(isset($wrapper)){ echo $wrapper; } else { echo "Ferraz Fernandes"; }?></h1>
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

    

<?php $this->load->view('partial/scripts'); ?>
<?php $this->load->view('partial/footer'); ?>