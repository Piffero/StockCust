<?php

class RD_New_Sobdemanda extends CI_Controller
{
	
	
	public function index()
    {
        if ($this->DB_User->is_logged_in())
        {
                    
            $info_user = $this->DB_User->get_logged_in_user_info();
            $info_user_permissions = $this->DB_Permission->get_permission($info_user->usuario_id, 'impressao_relatorio');
            $info_all_permissions =  $this->DB_Permission->get_permission($info_user->usuario_id);
        
            $num_linhas = $info_all_permissions->num_rows();
            $margem = ($num_linhas * 100) / 10;
        
            if($info_user_permissions->permissao_checked == 1)
            {
            	$app_header['header'] = $this->get_style();
                $app_header['user_info'] = $info_user;
                $app_header['mrg'] = $margem;
                

                $data = $this->get_content();
                
                $this->load->view('blocks/app_header', $app_header);
                $this->load->view('blocks/app_aside');
                $this->load->view('content', $data);
                $this->get_from_script();
            }
            else
            {
                redirect('login');
            }
        }
        else
        {
            redirect('login');
        }
         
    }
	
    public function get_content()
    {
    
    	$html  = '';
    	$html  .= '<div class="cf nestable-lists">'.PHP_EOL;    	
    	$html  .= '	<div class="dd" id="nestable">'.PHP_EOL;
    	$html  .= '		<ol class="dd-list">'.PHP_EOL;
    
    	$obj = new stdClass();
    	
    	$obj->btn_material = array(
    			'id'        => 'btn_application',
    			'name'      => 'btn_material',
    			'class'     => 'btn btn-primary',
    			'type'      => 'submit',
    			'content'   => 'Materia-Prima',
    			'onclick'   => 'popup_material()'
    	);
    	
    	$obj->btn_default = array(
    			'id'        => 'btn_application',
    			'name'      => 'btn_aplicar',
    			'class'     => 'btn btn-default',
    			'type'      => 'submit',
    			'content'   => 'Aplicar',
    			'onclick'   => 'outinput_pdf()'
    	);
    	
    	$obj->result = $this->DB_End_Product->get_all(0,1000);
    	
    	foreach ($obj->result->result() as $fields) {
    		$html  .= '			<li class="dd-item" data-id="'.$fields->empanada_codigo.'"><div class="dd-handle">'.$fields->empanada_nome.'</div></li>'.PHP_EOL;;
    	}
    	
    	$html  .= '		</ol>'.PHP_EOL;
    	$html  .= '	</div>'.PHP_EOL;    	
    	$html  .= '	<div class="dd" id="nestable2">'.PHP_EOL;
    	$html  .= '		<div class="dd-empty"></div>'.PHP_EOL;
    	$html  .= '	</div>'.PHP_EOL;
    	$html  .= '	<div class="dd" style="text-align: right;"><br>'.form_button($obj->btn_material).' '.form_button($obj->btn_default).'</div>'.PHP_EOL;
    	$html  .= '</div>'.PHP_EOL;
    	$html  .= '<p><strong>Saída Serializada (pela demanda)</strong></p>'.PHP_EOL;    	
    	$html  .= '<textarea id="nestable2-output"></textarea>'.PHP_EOL;
    	
    	
    	$data['wrapper'] = 'Relatório de Empanda Sob Demanda';
    	$data['panel_default'] = $html;
    	return $data;
    }
    
    
    public function get_style()
    {
    	$html  = '<style type="text/css">'.PHP_EOL;
    	$html .= '	.cf:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }'.PHP_EOL;
    	$html .= '	* html .cf { zoom: 1; }'.PHP_EOL;
    	$html .= '	*:first-child+html .cf { zoom: 1; }'.PHP_EOL;

    	$html .= '	/**'.PHP_EOL;
    	$html .= '	 * Nestable'.PHP_EOL;
    	$html .= '	 */'.PHP_EOL;

    	$html .= '	.dd { position: relative; display: block; margin: 0; padding: 0; max-width: 600px; list-style: none; font-size: 13px; line-height: 20px; }'.PHP_EOL;
    	$html .= '	.dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }'.PHP_EOL;
    	$html .= '	.dd-list .dd-list { padding-left: 30px; }'.PHP_EOL;
    	$html .= '	.dd-collapsed .dd-list { display: none; }'.PHP_EOL;
    	
    	$html .= '	.dd-item,'.PHP_EOL;
    	$html .= '	.dd-empty,'.PHP_EOL;
    	$html .= '	.dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }'.PHP_EOL;
    	
    	$html .= '	.dd-handle { display: block; height: 30px; margin: 5px 0; padding: 5px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;'.PHP_EOL;
    	$html .= '    	  background: #fafafa;'.PHP_EOL;
    	$html .= '        background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);'.PHP_EOL;
    	$html .= '        background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);'.PHP_EOL;
    	$html .= '        background:         linear-gradient(top, #fafafa 0%, #eee 100%);'.PHP_EOL;
    	$html .= '        -webkit-border-radius: 3px;'.PHP_EOL;
    	$html .= '                border-radius: 3px;'.PHP_EOL;
    	$html .= '        box-sizing: border-box; -moz-box-sizing: border-box;'.PHP_EOL;
    	$html .= '    }'.PHP_EOL;
    	$html .= '    .dd-handle:hover { color: #2ea8e5; background: #fff; }'.PHP_EOL;
    	
    	$html .= '    .dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }'.PHP_EOL;
    	$html .= '    .dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }'.PHP_EOL;
    	$html .= '    .dd-item > button[data-action="collapse"]:before { content: '-'; }'.PHP_EOL;
    	
    	$html .= '    .dd-placeholder,'.PHP_EOL;
    	$html .= '    .dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }'.PHP_EOL;
    	$html .= '    .dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;'.PHP_EOL;
    	$html .= '        background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),'.PHP_EOL;
    	$html .= '                          -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);'.PHP_EOL;
    	$html .= '        background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),'.PHP_EOL;
    	$html .= '                             -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);'.PHP_EOL;
    	$html .= '        background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),'.PHP_EOL;
    	$html .= '                                  linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);'.PHP_EOL;
    	$html .= '        background-size: 60px 60px;'.PHP_EOL;
    	$html .= '        background-position: 0 0, 30px 30px;'.PHP_EOL;
    	$html .= '    }'.PHP_EOL;
    	
    	$html .= '    .dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }'.PHP_EOL;
    	$html .= '    .dd-dragel > .dd-item .dd-handle { margin-top: 0; }'.PHP_EOL;
    	$html .= '    .dd-dragel .dd-handle {'.PHP_EOL;
    	$html .= '        -webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);'.PHP_EOL;
    	$html .= '                box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);'.PHP_EOL;
    	$html .= '    }'.PHP_EOL;
    	
    	$html .= '    /**'.PHP_EOL;
     	$html .= '    * Nestable Extras'.PHP_EOL;
     	$html .= '    */'.PHP_EOL;
    	
    	$html .= '    .nestable-lists { display: block; clear: both; padding: 30px 0; width: 100%; border: 0; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd; }'.PHP_EOL;
    	
    	$html .= '    #nestable-menu { padding: 0; margin: 20px 0; }'.PHP_EOL;
    	
    	$html .= '    #nestable-output,'.PHP_EOL;
    	$html .= '    #nestable2-output { width: 100%; height: 7em; font-size: 0.75em; line-height: 1.333333em; font-family: Consolas, monospace; padding: 5px; box-sizing: border-box; -moz-box-sizing: border-box; }'.PHP_EOL;
    	
    	$html .= '    #nestable2 .dd-handle {'.PHP_EOL;
    	$html .= '        color: #fff;'.PHP_EOL;
    	$html .= '        border: 1px solid #999;'.PHP_EOL;
    	$html .= '        background: #bbb;'.PHP_EOL;
    	$html .= '        background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);'.PHP_EOL;
    	$html .= '        background:    -moz-linear-gradient(top, #bbb 0%, #999 100%);'.PHP_EOL;
    	$html .= '        background:         linear-gradient(top, #bbb 0%, #999 100%);'.PHP_EOL;
    	$html .= '    }'.PHP_EOL;
    	$html .= '    #nestable2 .dd-handle:hover { background: #bbb; }'.PHP_EOL;
    	$html .= '    #nestable2 .dd-item > button:before { color: #fff; }'.PHP_EOL;
    	
    	$html .= '    @media only screen and (min-width: 700px) {'.PHP_EOL;
    	
    	$html .= '        .dd { float: left; width: 48%; }'.PHP_EOL;
    	$html .= '        .dd + .dd { margin-left: 2%; }'.PHP_EOL;
    	
    	$html .= '    }'.PHP_EOL;
    	
    	$html .= '    .dd-hover > .dd-handle { background: #2ea8e5 !important; }'.PHP_EOL;
    	
    	$html .= '    /**'.PHP_EOL;
    	$html .= '     * Nestable Draggable Handles'.PHP_EOL;
     	$html .= '    */'.PHP_EOL;
    	
    	$html .= '    .dd3-content { display: block; height: 30px; margin: 5px 0; padding: 5px 10px 5px 40px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;'.PHP_EOL;
    	$html .= '        background: #fafafa;'.PHP_EOL;
    	$html .= '        background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);'.PHP_EOL;
    	$html .= '        background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);'.PHP_EOL;
    	$html .= '        background:         linear-gradient(top, #fafafa 0%, #eee 100%);'.PHP_EOL;
    	$html .= '        -webkit-border-radius: 3px;'.PHP_EOL;
    	$html .= '                border-radius: 3px;'.PHP_EOL;
    	$html .= '        box-sizing: border-box; -moz-box-sizing: border-box;'.PHP_EOL;
    	$html .= '    }'.PHP_EOL;
    	$html .= '    .dd3-content:hover { color: #2ea8e5; background: #fff; }'.PHP_EOL;
    	
    	$html .= '    .dd-dragel > .dd3-item > .dd3-content { margin: 0; }'.PHP_EOL;
    	
    	$html .= '    .dd3-item > button { margin-left: 30px; }'.PHP_EOL;
    	
    	$html .= '    .dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 30px; text-indent: 100%; white-space: nowrap; overflow: hidden;'.PHP_EOL;
    	$html .= '        border: 1px solid #aaa;'.PHP_EOL;
    	$html .= '        background: #ddd;'.PHP_EOL;
    	$html .= '        background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);'.PHP_EOL;
    	$html .= '        background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);'.PHP_EOL;
    	$html .= '        background:         linear-gradient(top, #ddd 0%, #bbb 100%);'.PHP_EOL;
    	$html .= '        border-top-right-radius: 0;'.PHP_EOL;
    	$html .= '        border-bottom-right-radius: 0;'.PHP_EOL;
    	$html .= '    }'.PHP_EOL;
    	$html .= '    .dd3-handle:before { content: \'≡\'; display: block; position: absolute; left: 0; top: 3px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }'.PHP_EOL;
    	$html .= '    .dd3-handle:hover { background: #ddd; }'.PHP_EOL;
    	
    	$html .= '        </style>';
    	
    	$data['header'] = $html;
    	return $data;
    }
    
    public function get_from_script()
    {
    	$script = $this->load->file(APPPATH . 'hooks/js_rd_new_sobdemanda.php');
    	return $script;
    }
    
    
    /**   ***********************************************************************     */
    
    
    public function set_material()
    {
    	$obj = new stdClass();
    	$obj->datalist = $this->DB_Raw_Material->get_all(0,1000);
    	$obj->result = $this->DB_Stock->get_all();
    	
    	$obj->btn_default = array(
    			'id'        => 'btn_application',
    			'name'      => 'btn_aplicar',
    			'class'     => 'btn btn-info',
    			'type'      => 'submit',
    			'content'   => 'Informar',
    			'onclick'   => 'insert_data();'
    	);
    	
    	$obj->btn_delete = array(
    			'id'        => 'btn_application',
    			'name'      => 'btn_aplicar',
    			'class'     => 'btn btn-danger',
    			'type'      => 'submit',
    			'content'   => 'Limpar',
    			'onclick'   => 'clear_data();'
    	);
    	
    	$data['panel_default'] = mt_stock($obj);
    	$this->load->view('popup/popup_materia', $data);
    }
    
    
    public function truncate_material()
    {
       if($this->input->post('action') == 1)
       {
	       	if($this->DB_Stock->clear()){
	       		$html ='<div class="alert ng-isolate-scope alert-success alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
	       		$html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
	       		$html .='<div ng-transclude=""><span class="ng-binding ng-scope">Sucesso! O registro foi limpo com sucesso.</span></div>';
	       		$html .='</div>';
	       		echo $html;	       		
	       	}
	       	else
	       	{
	       		$html ='<div class="alert ng-isolate-scope alert-danger alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
	       		$html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
	       		$html .='<div ng-transclude=""><span class="ng-binding ng-scope">Erro! Não foi possivel limpar a tabela.</span></div>';
	       		$html .='</div>';
	       		echo $html;	       		
	       	}
       }	
    }
    
    
    public function insert_material()
    {
    	$data_material = $this->DB_Raw_Material->get_info($this->input->post('code'));
    	$estoque_data = array(
    			'estoque_codigo' => $data_material->materia_id,
    			'estoque_nome' => $data_material->materia_nome,
    			'estoque_qtde' => $this->input->post('amount'),
    			);
    			
    	
    	
    		if($this->DB_Stock->save($estoque_data))
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
    
    
    public function delete_material()
    {
    	$estoque_id = $this->input->post('code');    	
    	
    	if($this->DB_Stock->delete($estoque_id))
    	{
    		$html ='<div class="alert ng-isolate-scope alert-success alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
    		$html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
    		$html .='<div ng-transclude=""><span class="ng-binding ng-scope">Sucesso! O registro foi excluido com êxito.</span></div>';
    		$html .='</div>';
    		echo $html;
    	}
    	else
    	{
    		$html ='<div class="alert ng-isolate-scope alert-danger alert-dismissable" role="alert" ng-class="[\'alert-\' + (type || \'warning\'), closeable ? \'alert-dismissable\' : null]" close="closeAlert($index)" type="success" ng-repeat="alert in alerts">'.PHP_EOL;
    		$html .='<button class="close" onclick="window.location.reload();" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>'.PHP_EOL;
    		$html .='<div ng-transclude=""><span class="ng-binding ng-scope">Erro! Não foi possivel excluir o registro.</span></div>';
    		$html .='</div>';
    		echo $html;
    	}
    	 
    }
    
    
    public function update_material()
    {
    	$estoque_id = $this->input->post('code');
    	
    	$estoque_data = array(    			
    			'estoque_qtde' => $this->input->post('value'),
    	);
    	 
    	 
    	 
    	if($this->DB_Stock->save($estoque_data, $estoque_id))
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


    /**   ***********************************************************************     */
    public function set_post()
    {
    	// coleção de IDs
    	$arr_default = json_decode($this->input->post('json'));
    	$this->session->set_userdata('pacotes', $arr_default);
    }
    
    public function outinput_pdf()
    {
    	$arr_default = $this->session->userdata('pacotes');
    	
    	
    	//Inicializar mecanismo de visÃ£o carregando do documento .pdf
    	ob_start();
    	
    	
    	//Incluir a biblioteca PDF principal (que integra ao caminho de instalação do tcpdf_min)
    	$this->load->library('pdf');
    	
    	// Criar um novo documento PDF
    	$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
    	
    	//define o conjunto de informações de documentos
    	
    	// define informações de documentos
    	$pdf->SetCreator(PDF_CREATOR);
    	$pdf->SetAuthor('BitStock-3.0');
    	$pdf->SetTitle('Empanadas Registradas');
    	$pdf->SetSubject('Listagem e rendimento das Empanadas');
    	$pdf->SetKeywords('BitStock-3.0, PDF, Listagem, 0001, guia');
    	
    	// define dados de cabeçalho padrão
    	$PDF_HEADER_LOGO = 'img/icon_inventario.gif';
    	$PDF_HEADER_LOGO_WIDTH = 20;
    	$PDF_HEADER_TITLE = 'Registro de Empanadas';
    	
    	$pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, 'Ferras Fernandes'.PHP_EOL, array(51,51,51), array(153,153,153));
    	$pdf->setFooterData(array(0,64,0), array(0,64,128));
    	
    	// define fontes cabeçalho e rodapé
    	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    	
    	// define fonte com espaçamento uniforme padrão
    	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    	
    	// define margens
    	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    	
    	// define quebras de página automática
    	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    	
    	// define modo de subconjunto de fontes padrão
    	$pdf->setFontSubsetting(true);
    	
    	// Adicionar uma página
    	// Este método tem várias opções, verifique a documentação do código-fonte para mais informações.
    	$pdf->AddPage();
    	
    	 
    	// Monta tabela de acordo com os dados de produção
    	$table = $this->create_object($arr_default);
    	
    	
    	// Imprimir texto usando writeHTML()
    	$pdf->writeHTML('<br><h5>Lista de Empanadas<hr></h5>');
    	$pdf->SetFontSize(7);
    	$pdf->writeHTML($table);
    	
    	
    	// Feche e saída PDF documento
    	// Este método tem várias opções, verifique a documentação do código-fonte para obter mais informações.
    	$pdf->Output('NTIC_Regras.pdf', 'I');
    }
    
    
    private function create_object($arr_default)
    {
    	
    	$obj = array(); // cria um novo array
    	
    	// Obtém a relação de produtos que esta em estoque;
    	$estoque_datas = $this->DB_Stock->get_all(); 
    	
    	
    	// Bloco 1 -- montando meu objeto 
    	foreach ($arr_default as $empanada)
    	{
    		$empanada_info = $this->DB_End_Product->get_info_for_code($empanada->id);    		
    		$empanada_info->empanada_rend_unidade = 0;
    		$empanada_info->empanada_rend_pacotes = 0;
    		$empanada_info->empanada_rend_caixas  = 0;
    		$empanada_info->empanada_producao     = 0;
    	
    		$obj[] = $empanada_info;    			
    	}
    	 
    	
    	
    	// Bloco 2 -- Construindo Hierarquia de Tabelas da Empanada 
    	foreach ($obj as $fields) 
    	{
    		// buscamos os dados sobre o recheio atribuido a empanada em questão
	        $recheio = $this->DB_End_Product->get_items($fields->empanada_id);
	        
	        // Bloco 3 corre pelo recheio
	        foreach ($recheio->result() as $subfields)
	        {
	        	// Agora que já sabemos qual recheio buscamos a informações do mesmo
	        	// montando nosso abjeto em seguinda atravez do indese "e_items_recheio_<nome do campo>"
	        	$query_items = $this->DB_Fillings->get_info($subfields->db_recheio_id);
	        	$subfields->e_items_recheio_nome = $query_items->recheio_nome;
	        	
	        	$e_items_recheio_items = $this->DB_Fillings->get_items($subfields->db_recheio_id);	        	
	        	$fields->empanada_recheio[$query_items->recheio_id] = $e_items_recheio_items->result();
	        	
	        	// Bloco 4 corre pelo itens do recheio
	        	foreach ($fields->empanada_recheio[$query_items->recheio_id] as $childfields){
	        		$childfields->recheio_regra = $query_items->recheio_regra;
	        		$childfields->recheio_fcr = $subfields->e_items_fcr;
	        	} // Fim do Bloco 4
	        	
	        } // Fim do Bloco 3	        	       
    	} // Fim do Bloco 2
    	 
    	
    	
    	// caso entre este fator de redução modifica objeto
    	
    	foreach ($obj as $newObj) {
    		$receita = new new_recipe();
    		$data_estoque = $this->DB_Stock->get_all();
    		$receita->valor_reducao = 0;
    		for ($i = 0; $i < $receita->loop_ativo; $i++) {
    			$objFinal[] = $receita->alter_recipe($newObj, $estoque_datas);
    		}	
    	}
    	
    	
    	
// 		echo '<pre>';
// 		print_r($objFinal);
// 		echo '</pre>';
    	
    	$html = '';
    	$html .= '                        <div class="table-responsive">'.PHP_EOL;
    	 
    	$html .= '                             <table>'.PHP_EOL;
    	$html .= '                                <thead>'.PHP_EOL;
    	$html .= '                                    <tr>'.PHP_EOL;
    	$html .= '                                        <th style="width:30px;"><strong>Código</strong></th>'.PHP_EOL;
    	$html .= '                                        <th style="width:200px;"><strong>Nome</strong></th>'.PHP_EOL;
    	$html .= '                                        <th style="width:50px;"><strong>Rendimento</strong></th>'.PHP_EOL;
    	$html .= '                                    </tr>'.PHP_EOL;
    	$html .= '                                </thead>'.PHP_EOL;
    	$html .= '                                <tbody>'.PHP_EOL;
    	 
    	$html .= $this->create_object_rows($obj);
    	 
    	$html .= '                                </tbody>'.PHP_EOL;
    	$html .= '                            </table>'.PHP_EOL;
    	$html .= '                        </div>'.PHP_EOL;
    	
    	
    	$html .= '                        <div class="table-responsive">'.PHP_EOL;
    	$html .= '                             <table>'.PHP_EOL;
    	$html .= '                                <thead>'.PHP_EOL;
    	$html .= '                                    <tr>'.PHP_EOL;
    	$html .= '                                        <th style="width:50px;"><strong>Código</strong></th>'.PHP_EOL;
    	$html .= '                                        <th style="width:150px;"><strong>Materia Prima</strong></th>'.PHP_EOL;    	
    	$html .= '                                        <th style="width:60px;"><strong>Quantidade</strong></th>'.PHP_EOL;
    	$html .= '                                    </tr>'.PHP_EOL;
    	$html .= '                                </thead>'.PHP_EOL;
    	$html .= '                                <tbody>'.PHP_EOL;    	
    	$estoque_datas = $this->DB_Stock->get_all();
    	foreach ($estoque_datas->result() as $fields_estoque) {
    		$html .= '                                    <tr>'.PHP_EOL;
    		$html .= '                                        <td style="width:50px;">'. $fields_estoque->estoque_codigo .'</td>'.PHP_EOL;
    		$html .= '                                        <td style="width:150px;">'. $fields_estoque->estoque_nome .'</td>'.PHP_EOL;    		
    		$html .= '                                        <td style="width:60px;">'. $fields_estoque->estoque_qtde .'</td>'.PHP_EOL;
    		$html .= '                                    </tr>'.PHP_EOL;
    	}
    	    	
    	$html .= '                                <tbody>'.PHP_EOL;
    	$html .= '                        </div>'.PHP_EOL;
    	
    	return $html;
    	
    }
    
    private function create_object_rows($obj)
    {
    
    	$html = '';
    
    	if(!array_key_exists("0", $obj))
    	{
    		$html .= '                                    <tr><td colspan="3">Não a produtos  suficientes para produção</td></tr>'.PHP_EOL;
    	}
    	else
    	{
    		foreach ($obj as $field) {
    			$html .= $this->create_object_data_row($field);
    		}
    	}
    
    	return $html;
    }
    
    
    private function create_object_data_row($field)
    {
    	$html  = '';
    	$html .= '                                    <tr>'.PHP_EOL;
    	$html .= '                                        <td style="width:30px;">'.$field->empanada_codigo.'</td>'.PHP_EOL;
    	$html .= '                                        <td style="width:200px;">'.$field->empanada_nome.'</td>'.PHP_EOL;
    	    		
    	$new_array = array_keys($field->empanada_rendimento);
    	$html .= '                                        <td style="width:40px;">'.$field->empanada_rendimento[$new_array[0]].'</td>'.PHP_EOL;    		
    	    	    	
    	$html .= '                                    </tr>'.PHP_EOL;
    	 
    	return $html;
    
    }
}
