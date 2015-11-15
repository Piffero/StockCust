<?php

if (!defined('BASEPATH'))
    exit('Sem permis&#227;o para acesso direto aos roteiros');

class Permission extends CI_Controller {

    /**
     * Ã�ndice para este controlador.
     *
     * Mapas para o seguinte URL
     * http://example.com/index.php/home
     * - ou -
     * http://example.com/index.php/home/index
     * - ou -
     * Uma vez que este controlador Ã© definido como o controlador de padrÃ£o no
     * config/routes.php, Ã© exibido com http://example.com/
     *
     * Assim, quaisquer outros mÃ©todos pÃºblicos nÃ£o prefixado
     * serÃ¡ mapeado para /index.php/home/<method_name>
     * 
     * @see http://codeigniter.com/permission_guide/general/urls.html
     */
    public function index($user_id = null) 
    {
        if ($this->DB_User->is_logged_in())
	    {
	        $info_user = $this->DB_User->get_logged_in_user_info();
	        $info_user_permissions = $this->DB_Permission->get_permission($info_user->usuario_id, 'seguranca_permissao');
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
    	$data['wrapper'] = 'Quadro de Permiss��es';
    	$obj = new stdClass();
    	
    	$obj->table_manager = new stdClass();
    	$obj->table_manager->result = $this->DB_User->get_all($sta,$end);
    	 
    	$obj_user[0] = "Lista de Usu�rio";
    	foreach ($obj->table_manager->result->result() as $user)
    	{
    	    $obj_user[$user->usuario_id] = $user->usuario_nome;
    	}
    	 
    	$obj->actCtr_option = $obj_user;
    	 
    
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
    
    	
    	
    	
    	
    	
    	$data['panel_default'] = manager_permission($obj);
    
    	return $data;
    }
    
    public function setApp_aside()
    {
    	$data['li_4'] = true;
    	$data['li_42'] = true;
    	return $data;
    }

    public function get_from_script() {
        $script = $this->load->file(APPPATH . 'hooks/js_permission.php');
        return $script;
    }


   
    public function set_permission()
    {        
        $user_id = $this->input->post('user_id');        
        $default_data = $this->DB_Permission->get_permission(1);
                
        foreach ($default_data->result() as $default_field){
            $user_data[$default_field->modulo_descricao] =  $this->DB_Permission->get_permission($user_id, $default_field->modulo_descricao);
        }        
        
        echo mt_permission_data_row($user_data);        
    }
    
    
    public function save()
    {
        $success = false;
        
        // obtem valores a serem trabalhados
        $db_user_id = $this->input->post('db_user_id');
        $permission_list = $this->input->post('permition_list');       
        $user_data = $this->DB_Permission->get_permission($this->input->post('db_user_id'));
        

        // obtem linhas para exclui as permissões deste usuário
        foreach ($user_data->result() as $field) {
            $delete_data[] = $field->permissao_id;
        }  
        
        if((isset($delete_data)) && (count($delete_data)) > 0){
            $this->DB_Permission->delete($delete_data);
        }
        
        // monta array para inserção de dados
        for ($i = 0; $i < count($permission_list); $i++) {
            if($permission_list[$i] != '' || $permission_list[$i] != null)
            {
                $new_data = array(
                    'modulo_descricao' => $permission_list[$i],
                    'db_usuario_id' => $db_user_id,
                    'permissao_checked' => 1,
                    'permissao_original' => 0
                );
                
               $success = $this->DB_Permission->save($new_data, -1);
            }
        }
        
        if($success)
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

/* End of file home.php */
/* Location: ./application/controllers/home.php */