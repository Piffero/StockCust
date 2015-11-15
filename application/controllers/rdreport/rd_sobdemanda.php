<?php

class RD_Sobdemanda extends CI_Controller
{
    
    function index()
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
        $obj = new stdClass();
        
        $obj->actCtr_option = array(                        
            '0' => 'Exportar Selecionados',            
            '1' => 'Remover Seleção',            
        );
        
        $obj->btn_default = array(
            'id'        => 'btn_application',
            'name'      => 'btn_aplicar',
            'class'     => 'btn btn-default',
            'type'      => 'submit',            
            'content'   => 'Aplicar',
            'onclick'   => 'outinput_pdf()'
        );
        

        
        $obj->empanadas = new stdClass();
        $obj->empanadas->fields = $this->DB_End_Product->get_fields();
        $obj->empanadas->result = $this->DB_End_Product->get_all(0,1000);
               
        $html  = '                    <div class="panel panel-default">'.PHP_EOL;
        $html .= '                        <div class="panel-heading">'.PHP_EOL;
        $html .= '                           Planilha Responsive'.PHP_EOL;
        $html .= '                        </div>'.PHP_EOL;
        $html .= '                        <div class="row wrapper">'.PHP_EOL;
        $html .= '                            <div class="col-sm-11 m-b-xs"></div>'.PHP_EOL;                
        $html .= '                            <div class="col-sm-1 m-b-xs">'.form_button($obj->btn_default).'</div>'.PHP_EOL;
        $html .= '                        </div>'.PHP_EOL;
        $html .= '                             <table class="table table-striped b-t b-light">'.PHP_EOL;
        $html .= '                                <thead>'.PHP_EOL;
        $html .= '                                    <tr>'.PHP_EOL;
        $html .= '                                        <th style="width:20px;"><input type="checkbox" onclick="checkall();"></input></th>'.PHP_EOL;
        $html .= '                                        <th style="width:5px;"></th>'.PHP_EOL;
        $html .= '                                        <th style="width:100px;">'.$this->lang->line($obj->empanadas->fields[1]).'</th>'.PHP_EOL;
        $html .= '                                        <th style="width:300px;">'.$this->lang->line($obj->empanadas->fields[2]).'</th>'.PHP_EOL;        
        $html .= '                                        <th></th>'.PHP_EOL;
        $html .= '                                    </tr>'.PHP_EOL;
        $html .= '                                </thead>'.PHP_EOL;
        $html .= '                                <tbody>'.PHP_EOL;
        
        
        // Aqui vou lista as empanadas e colocar as sub categorias
        foreach ($obj->empanadas->result->result() as $field) {
            $html .= '                                    <tr>'.PHP_EOL;
            $html .= '                                        <td style="width:20px;"><input id="'.$field->empanada_id.'" type="checkbox"></input></td>'.PHP_EOL;
            $html .= '                                        <td style="width:20px;"><a id="open_'.$field->empanada_id.'" class="" href="javascript:active_subitem('.$field->empanada_id.');"><i class="fa fa-plus text-success text-active"></i></a></td>'.PHP_EOL;
            $html .= '                                        <td style="width:100px;">'.$field->empanada_codigo.'</input></td>'.PHP_EOL;
            $html .= '                                        <td style="width:300px;">'.$field->empanada_nome.'</input></td>'.PHP_EOL;
            $html .= '                                        <td></td>'.PHP_EOL;
            $html .= '                                    </tr>'.PHP_EOL;
            
            $html .= '                                    <tr id="showDistictMateria_'.$field->empanada_id.'" style="display:none;">'.PHP_EOL; //style="display:none;"
            $html .= '                                        <td colspan="5">'.PHP_EOL;
            $html .= '                                          <table class="table">'.PHP_EOL;

            // faço um laço em cascata para chegar na matareia_prima imprimo             
            $recheio = $this->DB_End_Product->get_items($field->empanada_id);
            foreach ($recheio->result() as $itens) {
                $materia_prima = $this->DB_Fillings->get_items($itens->db_recheio_id);
                foreach ($materia_prima->result() as $material){
                    $insumo = $this->DB_Raw_Material->get_info($material->db_materia_id);
                    
                        $html .= '                                              <tr>'.PHP_EOL;
                        $html .= '                                                  <td style="width:20px;"></td>'.PHP_EOL;
                        $html .= '                                                  <td style="width:40px;"></td>'.PHP_EOL;
                        $html .= '                                                  <td style="width:100px;">'.$insumo->materia_id.'</td>'.PHP_EOL;
                        $html .= '                                                  <td style="width:250px;">'.$insumo->materia_nome.'</td>'.PHP_EOL;
                        $html .= '                                                  <td style="width:250px;"><input class="form-control" type="text" id="'.$field->empanada_id.'_'.$insumo->materia_id.'" placeholder="Quantidade em '.$insumo->materia_unid.'"></td>'.PHP_EOL;
                        $html .= '                                                  <td></td>'.PHP_EOL;
                        $html .= '                                              </tr>'.PHP_EOL;
                        
                }
            }
            
            
            $html .= '                                              <th>';
            $html .= '                                                  <td colspan="5"></td>'.PHP_EOL;
            $html .= '                                              </th>';
                        
            $html .= '                                          </table>'.PHP_EOL;
            $html .= '                                        </td>'.PHP_EOL;
            $html .= '                                    </tr>'.PHP_EOL;
        }
        
        
        
        
        $html .= '                                </tbody>'.PHP_EOL;
        $html .= '                            </table>'.PHP_EOL;
        
        
        $html .= '                    </div>'.PHP_EOL;
        
        
        $data['wrapper'] = 'Relatório de Empanda Sob Demanda';
        $data['panel_default'] = $html;
        
        return $data;
    }
    
    
    function get_from_script()
    {
        $script = $this->load->file(APPPATH . 'hooks/js_rd_sobdemanda.php');
	    return $script;
    }
    
    
    public function set_post()
    {
        $this->session->set_userdata('pacotes', $this->input->post('num_pacotes'));
    }
    
    
    
    public function outinput_pdf()
	{
	    $pacotes = $this->session->userdata('pacotes');
	    
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
	    
	    $table = $this->create_object($this->outinput_obj($pacotes));	    
	    
	    // Imprimir texto usando writeHTML()
	    $pdf->writeHTML('<br><h5>Lista de Empanadas<hr></h5>');
	    $pdf->SetFontSize(7);
	    $pdf->writeHTML($table);	    
	     
	    // Feche e saída PDF documento
	    // Este método tem várias opções, verifique a documentação do código-fonte para obter mais informações.
	    $pdf->Output('NTIC_Regras.pdf', 'I');
	}
    
  
	private function outinput_obj($pacotes=null)
	{
	   
	    $obj = Array();
	    $row = 0;	  
	    
	    for ($i = 0; $i < count($pacotes); $i++)
	    {
	    
	       $arr_orig = explode(":", $pacotes[$i]);
	       
	       $empanada_info = $this->DB_End_Product->get_info($arr_orig[0]);
	       $material_info = $this->DB_Raw_Material->get_info($arr_orig[1]);
	       $gramatura     = $arr_orig[2];
	       
	       
	       if(($i - 1) < 0)
	       {
	           $obj[$i] = new stdClass();
	           $obj[$i]->empanada_id = $empanada_info->empanada_id;
	           $obj[$i]->empanada_codigo = $empanada_info->empanada_codigo;
	           $obj[$i]->empanada_nome = $empanada_info->empanada_nome;
	           $obj[$i]->empanada_rendimento = null;	           
	       }
	       
	       while ($row < count($pacotes)) 
	       {
	           $arr_compara = explode(":", $pacotes[$row]);
	           $empanada_info = $this->DB_End_Product->get_info($arr_compara[0]);
	           $material_info = $this->DB_Raw_Material->get_info($arr_compara[1]);
	           $gramatura     = $arr_compara[2];
	           	
	           if($arr_orig[0] == $arr_compara[0])
	           {	               
	               $material_info->materia_valor = number_format($gramatura,4);
	               $obj[$i]->empanada_material[] = $material_info;	               
    	       }
    	       else
    	       {	               
	               $obj[$row] = new stdClass();
	               $obj[$row]->empanada_id = $empanada_info->empanada_id;
	               $obj[$row]->empanada_codigo = $empanada_info->empanada_codigo;
	               $obj[$row]->empanada_nome = $empanada_info->empanada_nome;
	               $obj[$row]->empanada_rendimento = null;
	               
	               $material_info->materia_valor = number_format($gramatura,4);
	               $obj[$row]->empanada_material[] = $material_info;
               }
               
               
               $row++;
	       }
	       
	        
	    }
	    
// 	    echo '<strong>Objeto enviado pelo cliente</strong><br>';
// 	    echo '<pre>';
// 	    print_r($pacotes);
// 	    echo '</pre>';
	     
	    
// 	    echo '<strong>Objeto final</strong><br>';
// 	    echo '<pre>';	    
// 	    print_r($obj);
// 	    echo '<hr></pre>';
// 	    exit();
	    
	    return $obj;
	    
	}
	
	
	private function create_object($obj)
	{
	    
	    foreach ($obj as $fields) 
	    {
	        // buscamos os dados sobre o recheio atribuido a empanada em questão
	        $recheio = $this->DB_End_Product->get_items($fields->empanada_id);
	        
	        foreach ($recheio->result() as $subfields)
	        {
	            $subfields->e_items_recheio_qtde = 0;
	            
	            // Agora que já sabemos qual recheio buscamos a informações do mesmo
	            // montando nosso abjeto em seguinda atravez do indese "e_items_recheio_<nome do campo>"	            
	            $query_items = $this->DB_Fillings->get_info($subfields->db_recheio_id);
	            $subfields->e_items_recheio_nome = $query_items->recheio_nome;
	            $subfields->e_items_recheio_regra = $query_items->recheio_regra;
	            
	            // para calcular oas informações sobre o rendimento.
	            // buscamos os dados dos materiais que compõem o recheio em nosso objeto
	            
	            // para calcular oas informações de custos e rendimento.
	            // buscamos os dados de materiais que compõem o recheio
	            foreach ($fields->empanada_material as $field_item) {
    	               // Passamos a somatoria as instancias de acordo com a quantidade e custo
    	                $subfields->e_items_recheio_qtde += $field_item->materia_valor;    	                
    	        }
    	        
    	        $fields->empanada_rendimento = number_format(($subfields->e_items_recheio_qtde / $query_items->recheio_regra), 0) ;	            
	        }
	        
	    }

// 	    echo '<pre>';
// 	    print_r($obj);
// 	    echo '</pre>';
	    
	    
	    
	    $html = '';
	    
	    $html = '                        <div class="table-responsive">'.PHP_EOL;
	    
	    $html .= '                             <table>'.PHP_EOL;
	    $html .= '                                <thead>'.PHP_EOL;
	    $html .= '                                    <tr>'.PHP_EOL;
	    $html .= '                                        <th><strong>Código</strong></th>'.PHP_EOL;
	    $html .= '                                        <th><strong>Nome</strong></th>'.PHP_EOL;
	    $html .= '                                        <th><strong>Rendimento</strong></th>'.PHP_EOL;
	    $html .= '                                        <th><strong> </strong></th>'.PHP_EOL;
	    $html .= '                                        <th><strong> </strong></th>'.PHP_EOL;
	    $html .= '                                    </tr>'.PHP_EOL;
	    $html .= '                                </thead>'.PHP_EOL;
	    $html .= '                                <tbody>'.PHP_EOL;
	    
	    $html .= $this->create_object_rows($obj);
	    
	    $html .= '                                </tbody>'.PHP_EOL;
	    $html .= '                            </table>'.PHP_EOL;
	    $html .= '                        </div>'.PHP_EOL;
	    
	    return $html;
    }
	
	
	
	
	
	private function create_object_rows($obj)
	{
	     
	    $html = '';
	     
	    if(!array_key_exists("0", $obj))
	    {
	        $html .= '                                    <tr><td colspan="5">Não a linhas para serem mostradas</td></tr>'.PHP_EOL;
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
	    $html .= '                                        <td>'.$field->empanada_codigo.'</td>'.PHP_EOL;
	    $html .= '                                        <td>'.$field->empanada_nome.'</td>'.PHP_EOL;
	    $html .= '                                        <td>'.str_replace(",", ".", $field->empanada_rendimento).'</td>'.PHP_EOL;
	    $html .= '                                        <td> </td>'.PHP_EOL;
	    $html .= '                                        <td> </td>'.PHP_EOL;	    
	    $html .= '                                    </tr>'.PHP_EOL;
	    
	    return $html;
	
	}
}