<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

class End_Product extends CI_Controller {

    /**
     * Index Page for this controller.
     */
    public function index()
    {		
        if ($this->DB_User->is_logged_in())
	    {
	        $info_user = $this->DB_User->get_logged_in_user_info();
	        $info_user_permissions = $this->DB_Permission->get_permission($info_user->usuario_id, 'produto_empanadas');
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
        $data['wrapper'] = 'Tabela de Empanadas';
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
        $obj->table_manager->showFields =  4;
        $obj->table_manager->fields = $this->DB_End_Product->get_fields();
        $obj->table_manager->result = $this->DB_End_Product->get_all($sta,$end);
         
        // buscamos os dados sobre todas as empanadas do banco
        foreach ($obj->table_manager->result->result() as $fields) {
            
        	$fields->CUnitario = 0;
            $fields->CPacote = 0;
            $fields->CCaixa = 0;
            $fields->PVenda = 0;                       
            
            // buscamos os dados sobre o recheio atribuido a empanada em questão
            $fields->recheio = $this->DB_End_Product->get_items($fields->empanada_id);     
			
           
            
            foreach ($fields->recheio->result() as $subfields)
            {
                
                $subfields->e_items_recheio_qtde = 0;
                $subfields->e_items_recheio_ctotal = 0;
                
                // Agora que já sabemos qual recheio buscamos a informações do mesmo 
                // montando nosso abjeto em seguinda atravez do indese "e_items_recheio_<nome do campo>"
                $query_items = $this->DB_Fillings->get_info($subfields->db_recheio_id);
                $subfields->e_items_recheio_nome = $query_items->recheio_nome; 
                $subfields->e_items_recheio_regra = $query_items->recheio_regra;
                
                // para calcular oas informações de custos e rendimento.
                // buscamos os dados de materiais que compõem o recheio
                $query_materia = $this->DB_Fillings->get_items($query_items->recheio_id);
                foreach ($query_materia->result() as $field_item)
                {
                    // Aqui buscamos as informações sobre os materiais e seus fatores para o calculo
                    $materia_info = $this->DB_Raw_Material->get_info($field_item->db_materia_id);
                    $field_item->r_items_unid = $materia_info->materia_unid;
                    $field_item->r_items_nome = $materia_info->materia_nome;
                    $field_item->r_items_custo = (($materia_info->materia_valor * $materia_info->materia_fator) * $field_item->r_items_qtde);
                
                    // Passamos a somatoria as instancias de acordo com a quantidade e custo                    
                    $subfields->e_items_recheio_qtde += $field_item->r_items_qtde;
                    $subfields->e_items_recheio_ctotal += $field_item->r_items_custo;
                }
                 
                // Finalizando o calculo de acordo com a somatoria da quantidade e custo antes obtidos;
                $subfields->e_items_recheio_cunit =  ($subfields->e_items_recheio_ctotal / ($subfields->e_items_recheio_qtde / $query_items->recheio_regra) * $subfields->e_items_fcr);
                $subfields->e_items_recheio_ctotalKg = ((1 / $query_items->recheio_regra) * $subfields->e_items_recheio_cunit);
                $valueFloat = number_format(($subfields->e_items_recheio_qtde / $query_items->recheio_regra), 2, '.', '');
                $arr = explode('.',$valueFloat);
                $subfields->e_items_recheio_rendto = $arr[0];
                
                //$subfields->e_items_recheio_cunit = $subfields->e_items_recheio_ctotal / ($subfields->e_items_recheio_qtde / $query_items->recheio_regra);
                //$subfields->e_items_recheio_ctotalKg = (1 / $query_items->recheio_regra) * $subfields->e_items_recheio_cunit;
                //$subfields->e_items_recheio_rendto = number_format(($subfields->e_items_recheio_qtde / $query_items->recheio_regra), 0) ;

                
                // Calculo do objeto com resultado final pela quantidade informada na composição da empanda
                $subfields->e_items_recheio_CustoFinal = (($subfields->e_items_recheio_ctotal * $subfields->e_items_fcr) * $subfields->e_items_qtde);
                $rendeFinal = (($subfields->e_items_recheio_rendto * $subfields->e_items_qtde) / $subfields->e_items_fcr);
                $arr = explode(".", $rendeFinal);
                $subfields->e_items_recheio_RendiFinal = $arr[0];
                if($rendeFinal == null){echo 'null';}                
                
                 
                 $fields->CUnitario += $subfields->e_items_recheio_CustoFinal / $subfields->e_items_recheio_RendiFinal;
                 $fields->CPacote = $fields->CUnitario * 10;
                 $fields->CCaixa  = $fields->CPacote * 6;
                 $fields->PVenda   =  $fields->CCaixa + (($fields->CCaixa * $fields->empanada_margem) / 100);
                                  
            }
                        
            
            
            if($fields->recheio->num_rows() > 1)
            {
				$ItensQuest = $fields->recheio->result();
				$fields->e_items_recheio_RendiFinal  = $ItensQuest[0]->e_items_recheio_RendiFinal;
            }
            else
            {
            	$fields->e_items_recheio_RendiFinal = ($fields->recheio->num_rows() == 1 ? $subfields->e_items_recheio_RendiFinal : 0);
            }
            
            
        }
        
				
        // Fim da modificação do objeto    
        $obj->table_manager->materia  = '';
        $obj->table_manager->datalist = $this->DB_Regras_Base->get_all(0, 1000);
        $obj->table_manager->subdatalist = $this->DB_Fillings->get_all(0, 1000);
        $obj->showStart = 0; $obj->showEnd = 5; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();    
        $data['panel_default'] = manager_empanada($obj);
    
    
        return $data;
    }
    
    public function setApp_aside()
    {
        $data['li_0'] = true;
        $data['li_01'] = true;
        return $data;
    }
    
    public function get_from_script()
    {
        $script = $this->load->file(APPPATH . 'hooks/js_end_product.php');
        return $script;
    }
    
    public function set_page_table()
    {
    
        $sta=0; $end=1000;
    
        $obj = new stdClass();
    
        $obj->actCtr_option = array(
            '0' => 'Adicionar Registro',
            '1' => 'Excluir Selecionado',
//            '2' => 'Exporta��o para PDF'
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
        $obj->table_manager->fields = $this->DB_End_Product->get_fields();
        $obj->table_manager->result = $this->DB_End_Product->get_all($sta,$end);
        
        // buscamos os dados sobre todas as empanadas do banco
        foreach ($obj->table_manager->result->result() as $fields) {
            
            $fields->CUnitario = 0;
            $fields->CPacote = 0;
            $fields->CCaixa = 0;
            $fields->PVenda = 0;
            
            // buscamos os dados sobre o recheio atribuido a empanada em quest�o
            $fields->recheio = $this->DB_End_Product->get_items($fields->empanada_id);            
            foreach ($fields->recheio->result() as $subfields)
            {
                
                $subfields->e_items_recheio_qtde = 0;
                $subfields->e_items_recheio_ctotal = 0;
                
                // Agora que já sabemos qual recheio buscamos a informa��es do mesmo 
                // montando nosso abjeto em seguinda atravez do indese "e_items_recheio_<nome do campo>"
                $query_items = $this->DB_Fillings->get_info($subfields->db_recheio_id);
                $subfields->e_items_recheio_nome = $query_items->recheio_nome; 
                $subfields->e_items_recheio_regra = $query_items->recheio_regra;
                
                // para calcular oas informa��es de custos e rendimento.
                // buscamos os dados de materiais que comp�em o recheio
                $query_materia = $this->DB_Fillings->get_items($query_items->recheio_id);
                foreach ($query_materia->result() as $field_item)
                {
                    // Aqui buscamos as informa��es sobre os materiais e seus fatores para o calculo
                    $materia_info = $this->DB_Raw_Material->get_info($field_item->db_materia_id);
                    $field_item->r_items_unid = $materia_info->materia_unid;
                    $field_item->r_items_nome = $materia_info->materia_nome;
                    $field_item->r_items_custo = (($materia_info->materia_valor + $materia_info->materia_fator) * $field_item->r_items_qtde);
                
                    // Passamos a somatoria as instancias de acordo com a quantidade e custo                    
                    $subfields->e_items_recheio_qtde += $field_item->r_items_qtde;
                    $subfields->e_items_recheio_ctotal += $field_item->r_items_custo;
                }
                 
                // Finalizando o calculo de acordo com a somatoria da quantidade e custo antes obtidos;
                $subfields->e_items_recheio_cunit = $subfields->e_items_recheio_ctotal / ($subfields->e_items_recheio_qtde / $query_items->recheio_regra);
                $subfields->e_items_recheio_ctotalKg = (1 / $query_items->recheio_regra) * $subfields->e_items_recheio_cunit;
                $subfields->e_items_recheio_rendto = number_format(($subfields->e_items_recheio_qtde / $query_items->recheio_regra), 0) ;

                
                 // Calculo do objeto com resultado final pela quantidade informada na composição da empanda
                $subfields->e_items_recheio_CustoFinal = (($subfields->e_items_recheio_ctotal * $subfields->e_items_fcr) * $subfields->e_items_qtde);
                $rendeFinal = (($subfields->e_items_recheio_rendto * $subfields->e_items_qtde) / $subfields->e_items_fcr);
                $arr = explode(".", $rendeFinal);
                $subfields->e_items_recheio_RendiFinal = $arr[0];
                if($rendeFinal == null){echo 'null';}                
                
                
                 $fields->CUnitario += $subfields->e_items_recheio_cunit;
                 $fields->CPacote = $fields->CUnitario * 10;
                 $fields->CCaixa  = $fields->CPacote * 6;
                 
                 $fields->PVenda   =  $fields->CCaixa + (($fields->CCaixa * $fields->empanada_margem) / 100);
            }
            
            $fields->e_items_recheio_RendiFinal = ($fields->recheio->num_rows() == 1 ? $subfields->e_items_recheio_RendiFinal : 0);
            
            
        }
                   
        // Fim da modifica��o do objeto    
        $obj->table_manager->materia  = '';
        $obj->table_manager->datalist = $this->DB_Regras_Base->get_all(0, 1000);
        $obj->table_manager->subdatalist = $this->DB_Fillings->get_all_active(0, 1000);
        $obj->showStart = 0; $obj->showEnd = 5; $obj->showDefault = 10; $obj->showTotal = $obj->table_manager->result->num_rows();    
        
        echo manager_empanada($obj);
    }
    
    
    //---------------------------------------------------------------------------------
    
    
    public function save($empanada_id=-1)
    {         
        
        if($this->DB_End_Product->save($this->input->post(), $empanada_id))
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
    
    
    public function save_item($empanada_id=null)
    {        
        
        if(!$empanada_id == null)
        {
            
            $db_recheio_id = $this->input->post('db_materia_id');                                 
            $data_recheio  = $this->DB_Fillings->get_info($db_recheio_id);    
            $data_regras = $this->DB_Regras_Base->get_regra($data_recheio->recheio_regra);
          
            $item_data = array(
                'db_empanada_id' => $empanada_id,
                'db_recheio_id'  => $db_recheio_id,
                'e_items_qtde'   => $this->input->post('r_items_qtde'),
            	'e_items_fcr'	 => $this->input->post('r_items_fcr'),
                'db_regras_id'   => $data_regras->regras_id
            );
    
            
            if($data_regras->regras_deletado == '99')
            {
               
                    $html ='<div class="alert ng-isolate-scope alert-danger alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
                    $html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
                    $html .='<div ng-transclude=""><span class="ng-binding ng-scope">Erro! Não pode processar o registro a regra não foi encontrada nos parametros do sistema.</span></div>';
                    $html .='</div>';
                    echo $html;               
            }
            else 
            {
                
                if($this->DB_End_Product->save_item($item_data))
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
    }
    
    public function delete()
    {
        $materia_ids = $this->input->post('materia_ids');        
        if($this->DB_End_Product->delete($materia_ids))
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
        if($this->DB_End_Product->delete_items($r_items_id))
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
    
    
    public function sales_price($empanada_id)
    {
    	$r_item_custo = 0;
    	$r_item_qtde = 0;
    	
    	
        $obj = new stdClass();
        
        // buscamos os dados sobre a empanada em questão
        $obj = $this->DB_End_Product->get_info($empanada_id);
        
        // buscamos os dados sobre o recheio atribuido a empanada em questão
        $obj->recheio = $this->DB_End_Product->get_items($obj->empanada_id);
        
        // Iniciamos os valores em 0
        $obj->empanada_cu = 0;
        $obj->empanada_cp = 0;
        $obj->empanada_cc = 0;
        $obj->empanada_venda = 0;
        
        		
        foreach ($obj->recheio->result() as $subfields)
        {
        		$subfields->e_items_recheio_qtde = 0;
        		$subfields->e_items_recheio_ctotal = 0;
        
        		// Agora que já sabemos qual recheio buscamos a informações do mesmo
        		// montando nosso abjeto em seguinda atravez do indese "e_items_recheio_<nome do campo>"
        		$query_items = $this->DB_Fillings->get_info($subfields->db_recheio_id);
        		$subfields->e_items_recheio_nome = $query_items->recheio_nome;
        		$subfields->e_items_recheio_regra = $query_items->recheio_regra;
        
        		// para calcular oas informações de custos e rendimento.
        		// buscamos os dados de materiais que compõem o recheio
        		$query_materia = $this->DB_Fillings->get_items($query_items->recheio_id);
        		foreach ($query_materia->result() as $field_item)
        		{
        			// Aqui buscamos as informações sobre os materiais e seus fatores para o calculo
        			$materia_info = $this->DB_Raw_Material->get_info($field_item->db_materia_id);
        			$field_item->r_items_unid = $materia_info->materia_unid;
        			$field_item->r_items_nome = $materia_info->materia_nome;
        			$field_item->r_items_custo = (($materia_info->materia_valor * $materia_info->materia_fator) * $field_item->r_items_qtde);
        
        			// Passamos a somatoria as instancias de acordo com a quantidade e custo
        			$subfields->e_items_recheio_qtde += $field_item->r_items_qtde;
        			$subfields->e_items_recheio_ctotal += $field_item->r_items_custo;
        		}
        		 
        		// Finalizando o calculo de acordo com a somatoria da quantidade e custo antes obtidos;
        		$subfields->e_items_recheio_cunit =  ($subfields->e_items_recheio_ctotal / ($subfields->e_items_recheio_qtde / $query_items->recheio_regra) * $subfields->e_items_fcr);
        		$subfields->e_items_recheio_ctotalKg = ((1 / $query_items->recheio_regra) * $subfields->e_items_recheio_cunit);
        		$valueFloat = number_format(($subfields->e_items_recheio_qtde / $query_items->recheio_regra), 2, '.', '');
        		$arr = explode('.',$valueFloat);
        		$subfields->e_items_recheio_rendto = $arr[0];
        
        		// Calculo do objeto com resultado final pela quantidade informada na composição da empanda
        		$subfields->e_items_recheio_CustoFinal = (($subfields->e_items_recheio_ctotal * $subfields->e_items_fcr) * $subfields->e_items_qtde);
        		$rendeFinal = (($subfields->e_items_recheio_rendto * $subfields->e_items_qtde) / $subfields->e_items_fcr);
        		$arr = explode(".", $rendeFinal);
        		$subfields->e_items_recheio_RendiFinal = $arr[0];
        		if($rendeFinal == null){echo 'null';}
        		
        		
        		$obj->empanada_cu += $subfields->e_items_recheio_CustoFinal / $subfields->e_items_recheio_RendiFinal;;
        		$obj->empanada_cp  = $obj->empanada_cu * 10;
        		$obj->empanada_cc  = $obj->empanada_cp * 6;
        		 
        		$obj->empanada_venda   =  ($obj->empanada_cc + (($obj->empanada_cc * $obj->empanada_margem) / 100));
        	}
        
        	if($obj->recheio->num_rows() > 1)
        	{
        		$ItensQuest = $obj->recheio->result();
        		$obj->e_items_recheio_RendiFinal  = $ItensQuest[0]->e_items_recheio_RendiFinal;
        	}
        	else
        	{
        		$obj->e_items_recheio_RendiFinal = ($obj->recheio->num_rows() == 1 ? $subfields->e_items_recheio_RendiFinal : 0);
        	}
        
        	
        $data['custo_unidade']  = $obj->empanada_cu;
        $data['panel_default']  = sales_table_empanada($obj);
        $data['sales_info']     = $obj;
        $data['title'] = '<font color="">'.$obj->empanada_nome.'</font>';
        
        $this->load->view('popup/popup_product', $data);

    }
}

/* End of file end_product.php */
/* Location: ./application/controllers/param/end_product.php */