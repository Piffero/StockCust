<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unit_Med extends CI_Controller {

    
    /**
     * Index Page for this controller.
     */
    public function index()
	{	
	    if ($this->DB_User->is_logged_in())
	    {
	        $info_user = $this->DB_User->get_logged_in_user_info();
	        $info_user_permissions = $this->DB_Permission->get_permission($info_user->usuario_id, 'parametros_regras');
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
    public function get_content($sta=0, $end=5)
    {
        $data['wrapper'] = 'Tabela de Unidades / Unidades de Medida';
        $obj = new stdClass();
        
        $obj->actCtr_option = array(
            '0' => 'Adicionar Registro',
            '1' => 'Excluir Selecionado',
            '2' => 'Exportação para PDF'
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
        $obj->table_manager->fields = $this->DB_Unid_Medida->get_fields();
        $obj->table_manager->result = $this->DB_Unid_Medida->get_all($sta,$end);     

        $obj->showStart = 0; $obj->showEnd = 5; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();
        
        $data['panel_default'] = manager_panel($obj);
        
        return $data;
    }
    
    public function setApp_aside()
    {
        $data['li_3'] = true;        
        return $data;
    }
    
    public function get_from_script()
    {
        $script = $this->load->file(APPPATH . 'hooks/js_unit_med.php');
        return $script;
    }  
    
    public function set_page_table()
    {
        
        $end = 5 * $this->input->post('unidade_page');
        $sta = $end - 5;  

        $obj = new stdClass();
        
        $obj->actCtr_option = array(
            '0' => 'Adicionar Registro',
            '1' => 'Excluir Selecionado',
            '2' => 'Exportação para PDF'
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
        $obj->table_manager->fields = $this->DB_Unid_Medida->get_fields();
        $obj->table_manager->result = $this->DB_Unid_Medida->get_all($sta,$end);
        
        $obj->showStart = 0; $obj->showEnd = 5; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();
        
        echo manager_panel($obj);        
    }
    
    
    //---------------------------------------------------------------------------------
    
    
    public function save($unidade_id=-1)
    {             
        if($this->DB_Unid_Medida->save($this->input->post(), $unidade_id))
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
        $regra_ids = $this->input->post('unidade_ids');        
        if($this->DB_Unid_Medida->delete($regra_ids))
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
        $regra_data = array('unidade_deletado' => $this->input->post('unidade_deletado'));             
        if($this->DB_Unid_Medida->save($regra_data, $this->input->post('unidade_id')))
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

/* End of file unit_med.php */
/* Location: ./application/controllers/param/unit_med.php */