<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fillings extends CI_Controller {

    
    /**
     * Index Page for this controller.
     */
    public function index()
	{	    
	    if ($this->DB_User->is_logged_in())
	    {
	        $info_user = $this->DB_User->get_logged_in_user_info();
	        $info_user_permissions = $this->DB_Permission->get_permission($info_user->usuario_id, 'produto_recheio');
	        $info_all_permissions =  $this->DB_Permission->get_permission($info_user->usuario_id);
	         
	        $num_linhas = $info_all_permissions->num_rows();
	        $margem = ($num_linhas * 100) / 10;
	         
	        if($info_user_permissions->permissao_checked == 1)
	        { 
        	    $app_header['user_info'] = $info_user;
        	    $app_header['mrg'] = $margem;
        	    
        	    $data = $this->get_content();
        	    $aside = $this->setApp_aside();
        	    
        	    $this->load->view('blocks/app_header', $app_header);
	            $this->load->view('blocks/app_aside', $aside);
		        $this->load->view('content', $data);
		        $this->get_from_script();
	        }
	        else
	        {
	            redirect('home');
	        }
	     }
	     else
	     {
	         redirect('home');
	     }
	}
    
    /**
     * Obtem as informações que serão aplicados no app-content do layout
     * @return array;
     */
    public function get_content($sta=0, $end=1000)
    {
        $data['wrapper'] = 'Tabela de Massas e Recheios';
        $obj = new stdClass();
        
        $obj->actCtr_option = array(
            '0' => 'Adicionar Registro',
            '1' => 'Excluir Selecionado',            
//            '2' => 'Exportação para PDF'            
        ); 
        
        $obj->btn_default = array(
            'id'        => 'btn_application',
            'name'      => 'btn_aplicar',
            'class'     => 'btn btn-sm btn-default',
            'type'      => 'button',
            'onclick'  => 'btnApplication();',
            'content'   => 'Aplicar'
        ); 
        
        $obj->btn_success = array(
            'id' => 'btn_save',
            'name' => 'btn_save',
            'class' => 'btn btn-sm btn-defaut',
            'type' => 'button',
            'disabled' => '',
            'onclick' => 'showMethodSave(-1);',
            'content' => '<i class="fa fa-check"></i>'
        );
        
        $obj->btn_danger = array(
            'id' => 'btn_cancel',
            'name' => 'btn_cancel',
            'class' => 'btn btn-sm btn-defaut',
            'type' => 'button',
            'disabled' => '',
            'onclick' => 'showMethodCancel();',
            'content' => '<i class="fa fa-times"></i>'
        );
        
        $obj->table_manager = new stdClass();
        $obj->table_manager->showFields =  3;
        $obj->table_manager->fields = $this->DB_Fillings->get_fields();
        $obj->table_manager->result = $this->DB_Fillings->get_all($sta,$end);
        
        // Aqui vou modificar objeto para retornar a Quantidade, C.Unitario, C.Total/Kg, C.Total e Rendimento
            
            foreach ($obj->table_manager->result->result() as $field) {

                $field->recheio_qtde        = 0;
                $field->recheio_cunit       = 0;
                $field->recheio_ctotalKg    = 0;
                $field->recheio_ctotal      = 0;
                $field->recheio_rendto      = 0;
                
                $field->materia  = $this->DB_Fillings->get_items($field->recheio_id);

               foreach ($field->materia->result() as $field_item)
               {

                   $materia_info = $this->DB_Raw_Material->get_info($field_item->db_materia_id);
                   $field_item->r_items_unid = $materia_info->materia_unid;
                   $field_item->r_items_nome = $materia_info->materia_nome;
                   $field_item->r_items_custo = (($materia_info->materia_valor * $materia_info->materia_fator) * $field_item->r_items_qtde); 

                   $field->recheio_qtde += $field_item->r_items_qtde;
                   $field->recheio_ctotal += $field_item->r_items_custo;
               }

               @$field->recheio_cunit =  $field->recheio_ctotal / ($field->recheio_qtde / $field->recheio_regra);  
               @$field->recheio_ctotalKg = (1 / $field->recheio_regra) * $field->recheio_cunit;
			   $valueFloat = number_format(($field->recheio_qtde / $field->recheio_regra), 2, '.', '');
			   $arr = explode('.',$valueFloat);
               @$field->recheio_rendto = $arr[0];

            }
                        
        // Fim da modifica��o do objeto
                                
        $obj->table_manager->materia  = '';       
        $obj->table_manager->datalist = $this->DB_Regras_Base->get_all(0, 1000);



        $obj->table_manager->subdatalist = $this->DB_Raw_Material->get_all_active(0, 1000);
        $obj->showStart = 0; $obj->showEnd = 5; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();
        
        $data['panel_default'] = manager_recheio($obj);
        

        
        return $data;
    }
    
    public function setApp_aside()
    {
        $data['li_0'] = true;  
        $data['li_02'] = true;      
        return $data;
    }
    
    public function get_from_script()
    {
        $script = $this->load->file(APPPATH . 'hooks/js_fillings.php');
        return $script;
    }  
    
    public function set_page_table()
    {
        
        $sta=0; $end=1000;

        $obj = new stdClass();
        
        $obj->actCtr_option = array(
            '0' => 'Adicionar Registro',
            '1' => 'Excluir Selecionado',
//            '2' => 'Exportação para PDF'
        );
        
        $obj->btn_default = array(
            'id'        => 'btn_application',
            'name'      => 'btn_aplicar',
            'class'     => 'btn btn-sm btn-default',
            'type'      => 'button',
            'onclick'  => 'btnApplication();',
            'content'   => 'Aplicar'
        );
        
        $obj->btn_success = array(
            'id' => 'btn_save',
            'name' => 'btn_save',
            'class' => 'btn btn-sm btn-defaut',
            'type' => 'button',
            'disabled' => '',
            'onclick' => 'showMethodSave(-1);',
            'content' => '<i class="fa fa-check"></i>'
        );
        
        $obj->btn_danger = array(
            'id' => 'btn_cancel',
            'name' => 'btn_cancel',
            'class' => 'btn btn-sm btn-defaut',
            'type' => 'button',
            'disabled' => '',
            'onclick' => 'showMethodCancel();',
            'content' => '<i class="fa fa-times"></i>'
        );
        
        $obj->table_manager = new stdClass();
        $obj->table_manager->showFields =  3;
        $obj->table_manager->fields = $this->DB_Fillings->get_fields();
        $obj->table_manager->result = $this->DB_Fillings->get_all($sta,$end);
        
        // Aqui vou modificar objeto para retornar a Quantidade, C.Unitário, C.Total/Kg, C.Total e Rendimento
        
        foreach ($obj->table_manager->result->result() as $field) {
        
            $field->recheio_qtde        = 0;
            $field->recheio_cunit       = 0;
            $field->recheio_ctotalKg    = 0;
            $field->recheio_ctotal      = 0;
            $field->recheio_rendto      = 0;
        
            $field->materia  = $this->DB_Fillings->get_items($field->recheio_id);
        
            foreach ($field->materia->result() as $field_item)
            {
        
                $materia_info = $this->DB_Raw_Material->get_info($field_item->db_materia_id);
                $field_item->r_items_unid = $materia_info->materia_unid;
                $field_item->r_items_nome = $materia_info->materia_nome;
                $field_item->r_items_custo = (($materia_info->materia_valor * $materia_info->materia_fator) * $field_item->r_items_qtde);
        
                $field->recheio_qtde += $field_item->r_items_qtde;
                $field->recheio_ctotal += $field_item->r_items_custo;
            }
        
            @$field->recheio_cunit =  $field->recheio_ctotal / ($field->recheio_qtde / $field->recheio_regra);
            @$field->recheio_ctotalKg = (1 / $field->recheio_regra) * $field->recheio_cunit;			
            $valueFloat = number_format(($field->recheio_qtde / $field->recheio_regra), 2, '.', '');
			$arr = explode('.',$valueFloat);
            @$field->recheio_rendto = $arr[0];
        
        }
        
        // Fim da modificação do objeto
        
        $obj->table_manager->materia  = '';
        $obj->table_manager->datalist = $this->DB_Regras_Base->get_all(0, 1000);
        $obj->table_manager->subdatalist = $this->DB_Raw_Material->get_all(0, 1000);
        $obj->showStart = 0; $obj->showEnd = 5; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();
                
        echo manager_recheio($obj);        
    }
    
    
    //---------------------------------------------------------------------------------
    
    
    public function save($recheio_id=-1)
    {          
       
        if($this->DB_Fillings->save($this->input->post(), $recheio_id))
        {
            $html ='<div class="alert ng-isolate-scope alert-success alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
            $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
            $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Sucesso! O registro foi processado com êxito.</span></div>';
            $html .='</div>';
            echo $html;
        }
        else 
        {
            $html ='<div class="alert ng-isolate-scope alert-danger alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
            $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
            $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Erro! Não pode processar o registro.</span></div>';
            $html .='</div>';
            echo $html;
        }
        
    }
    
    
    public function save_item($recheio_id=null)
    {
        if(!$recheio_id == null)
        {            
            
            $item_data = array(
                'db_recheio_id' => $recheio_id,
                'db_materia_id' => $this->input->post('db_materia_id'),
                'r_items_qtde'  => $this->input->post('r_items_qtde')
            );

            if($this->DB_Fillings->save_item($item_data))
            {
                $html ='<div class="alert ng-isolate-scope alert-success alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
                $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
                $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Sucesso! O registro foi processado com êxito.</span></div>';
                $html .='</div>';
                echo $html;
            }
            else 
            {
                $html ='<div class="alert ng-isolate-scope alert-danger alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
                $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
                $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Erro! Não pode processar o registro.</span></div>';
                $html .='</div>';
                echo $html;
            }
            
        }
    }
    
    
    
    public function update_item()
    {
    	
    	$item_id = $this->input->post('r_items_id');    	
    	if($item_id != null)
    	{
    		
    		$item_data = array(    				
    				'r_items_qtde'  => $this->input->post('r_items_qtde')
    		);
    		
    		if($this->DB_Fillings->update_item($item_data, $item_id))
    		{
    			$html ='<div class="alert ng-isolate-scope alert-success alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
    			$html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
    			$html .='<div ng-transclude=""><span class="ng-binding ng-scope">Sucesso! O registro foi processado com êxito.</span></div>';
    			$html .='</div>';
    			echo $html;
    		}
    		else
    		{
    			$html ='<div class="alert ng-isolate-scope alert-danger alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
    			$html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
    			$html .='<div ng-transclude=""><span class="ng-binding ng-scope">Erro! Não pode processar o registro.</span></div>';
    			$html .='</div>';
    			echo $html;
    		}    
    	}
    }
    
    public function delete()
    {               
        $materia_ids = $this->input->post('materia_ids');           
        if($this->DB_Fillings->delete($materia_ids))
        {
            $html ='<div class="alert ng-isolate-scope alert-success alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
            $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
            $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Sucesso! O registro foi processado com êxito.</span></div>';
            $html .='</div>';
            echo $html;
        }
        else
        {
            $html ='<div class="alert ng-isolate-scope alert-danger alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
            $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
            $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Erro! Não pode processar o registro.</span></div>';
            $html .='</div>';
            echo $html;
        }
    }
    
    
    public function delete_items($r_items_id)
    {
        if($this->DB_Fillings->delete_items($r_items_id))
        {
            $html ='<div class="alert ng-isolate-scope alert-success alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
            $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
            $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Sucesso! O sub-registro foi excluido com êxito.</span></div>';
            $html .='</div>';
            echo $html;
        }
        else
        {
            $html ='<div class="alert ng-isolate-scope alert-danger alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
            $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
            $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Erro! Não pode processar a exclusão do sub-registro.</span></div>';
            $html .='</div>';
            echo $html;
        }
    }
    
    public function status()
    {           
        $recheio_data = array('recheio_deletado' => $this->input->post('recheio_deletado'));                 
        if($this->DB_Fillings->save($recheio_data, $this->input->post('recheio_id')))
        {
            $html ='<div class="alert ng-isolate-scope alert-success alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
            $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
            $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Sucesso! O registro foi processado com êxito.</span></div>';
            $html .='</div>';
            echo $html;
        }
        else
        {
            $html ='<div class="alert ng-isolate-scope alert-danger alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
            $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
            $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Erro! Não pode processar o registro.</span></div>';
            $html .='</div>';
            echo $html;
        }            
    }
}

/* End of file raw_material.php */
/* Location: ./application/controllers/param/raw_material.php */