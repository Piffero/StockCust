<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Raw_Material extends CI_Controller {

    
    /**
     * Index Page for this controller.
     */
    public function index()
	{	    
	    if ($this->DB_User->is_logged_in())
	    {
	        $info_user = $this->DB_User->get_logged_in_user_info();
	        $info_user_permissions = $this->DB_Permission->get_permission($info_user->usuario_id, 'produto_material');
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
        $data['wrapper'] = 'Tabela de Matéria Prima';
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
        $obj->table_manager->showFields =  5;
        $obj->table_manager->fields = $this->DB_Raw_Material->get_fields();
        $obj->table_manager->result = $this->DB_Raw_Material->get_all($sta,$end);
             
        $obj->table_manager->datalist = $this->DB_Unid_Medida->get_all(0, 1000);
        
        $obj->showStart = 0; $obj->showEnd = 5; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();
        
        $data['panel_default'] = manager_materia($obj);
        
        return $data;
    }
    
    public function setApp_aside()
    {
        $data['li_0'] = true;  
        $data['li_03'] = true;      
        return $data;
    }
    
    public function get_from_script()
    {
        $script = $this->load->file(APPPATH . 'hooks/js_material.php');
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
        $obj->table_manager->showFields =  5;
        $obj->table_manager->fields = $this->DB_Raw_Material->get_fields();
        $obj->table_manager->result = $this->DB_Raw_Material->get_all($sta,$end);        
        $obj->table_manager->datalist = $this->DB_Unid_Medida->get_all(0, 1000);
        
        $obj->showStart = 0; $obj->showEnd = 5; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();
        
        echo manager_materia($obj);        
    }
    
    
    //---------------------------------------------------------------------------------
    
    
    public function save($unidade_id=-1)
    {   

        $arr_VL = explode(",", $this->input->post('materia_valor'));        
        $arr_FC = explode(",", $this->input->post('materia_fator'));
        
        if(key_exists(1, $arr_VL)){ $value = $arr_VL[0].".".$arr_VL[1]; } else { $value = $arr_VL[0]; }        
        if(key_exists(1, $arr_FC)){ $FC = $arr_FC[0].".".$arr_FC[1]; } else { $FC = $arr_FC[0]; }               
        
        $material_data = array(
            'materia_nome'  => $this->input->post('materia_nome'),
            'materia_unid'  => $this->input->post('materia_unid'),
            'materia_valor' => $value,
            'materia_fator'    => $FC
        );
        
        
       
        if($this->DB_Raw_Material->save($material_data, $unidade_id))
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
    
    
    public function delete()
    {               
        $materia_ids = $this->input->post('materia_ids');        
        if($this->DB_Raw_Material->delete($materia_ids))
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
    
    
    public function status()
    {   
        $regra_data = array('materia_deletado' => $this->input->post('materia_deletado'));             
        if($this->DB_Raw_Material->save($regra_data, $this->input->post('materia_id')))
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