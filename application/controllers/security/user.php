<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    
    /**
     * Index Page for this controller.
     */
    public function index()
	{	    
	   if ($this->DB_User->is_logged_in())
	   {
	        $info_user = $this->DB_User->get_logged_in_user_info();
	        $info_user_permissions = $this->DB_Permission->get_permission($info_user->usuario_id, 'seguranca_usuarios');
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
	 * Obtem as informaÃ§Ãµes que serÃ£o aplicados no app-content do layout
	 * @return array;
	 */
	public function get_content($sta=0, $end=5)
	{
		$data['wrapper'] = 'Tabela de Usuários';
		$obj = new stdClass();
	
		$obj->actCtr_option = array(
				'0' => 'Adicionar Registro',
				'1' => 'Excluir Selecionado'
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
		$obj->table_manager->showFields =  4;
		$obj->table_manager->fields = $this->DB_User->get_fields();
		$obj->table_manager->result = $this->DB_User->get_all($sta,$end);
		 		
	
		$obj->showStart = $sta; $obj->showEnd = $end; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();
	
		$data['panel_default'] = manager_user($obj);
	
		return $data;
	}
	
	public function setApp_aside()
	{
		$data['li_4'] = true;
		$data['li_41'] = true;
		return $data;
	}
	
	public function get_from_script()
	{
		$script = $this->load->file(APPPATH . 'hooks/js_user.php');
		return $script;
	}
	
	public function set_page_table()
	{
	
		$end = 5 * $this->input->post('user_page');
		$sta = $end - 5;
	
		$obj = new stdClass();
	
		$obj->actCtr_option = array(
				'0' => 'Adicionar Registro',
				'1' => 'Excluir Selecionado'
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
		$obj->table_manager->fields = $this->DB_User->get_fields();
		$obj->table_manager->result = $this->DB_User->get_all($sta,$end);
		$obj->table_manager->datalist = $this->DB_Unid_Medida->get_all(0, 1000);
	
		$obj->showStart = 0; $obj->showEnd = 5; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();
	
		echo manager_user($obj);
	}
	
	
	//---------------------------------------------------------------------------------
	
	
	public function save($unidade_id=-1)
	{
	
		$user_data = array(
				'usuario_nome'  => $this->input->post('user_nome'),
				'usuario_nome_usuario'  => $this->input->post('user_nome_usuario'),
				'usuario_senha' => md5($this->input->post('user_senha'))
		);
	
		if($this->DB_User->save($user_data, $unidade_id))
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
		$user_ids = $this->input->post('user_ids');
		if($this->DB_User->delete($user_ids))
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
		$regra_data = array('user_deletado' => $this->input->post('user_deletado'));
		if($this->DB_User->save($regra_data, $this->input->post('user_id')))
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
	
	public function search()
	{             
		$search = $this->input->post('search');      
				
		$obj = new stdClass();
		
		$obj->actCtr_option = array(
				'0' => 'Adicionar Registro',
				'1' => 'Excluir Selecionado'
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
		$obj->table_manager->showFields =  4;
		$obj->table_manager->fields = $this->DB_User->get_fields();
		$obj->table_manager->result = $this->DB_User->search($search);
					
		
		$obj->showStart = 0; $obj->showEnd = 5; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();
		
//  		print_r($obj);
  		echo manager_user($obj);
	}
	
}