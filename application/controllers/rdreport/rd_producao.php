<?php

class RD_Producao extends CI_Controller
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
            '0' => 'Adicionar Registro',
            '1' => 'Excluir Selecionado',
            '2' => 'Exportação para PDF'
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
        $html .= '                             <div style="left: 1020px; top: -7px;" class="col-sm-1">'.form_button($obj->btn_default).'</div>'.PHP_EOL;
        $html .= '                             <table class="table table-striped b-t b-light">'.PHP_EOL;
        $html .= '                                <thead>'.PHP_EOL;
        $html .= '                                    <tr>'.PHP_EOL;
        $html .= '                                        <th style="width:20px;"><input type="checkbox" onclick="checkall();"></input></th>'.PHP_EOL;
        $html .= '                                        <th style="width:5px;"></th>'.PHP_EOL;
        $html .= '                                        <th>Código</th>'.PHP_EOL;
        $html .= '                                        <th style="width:300px;">Nome</th>'.PHP_EOL;
        $html .= '                                        <th>Fator Multiplicador</th>'.PHP_EOL;
        $html .= '                                        <th style="width:450px;"></th>'.PHP_EOL;
        $html .= '                                    </tr>'.PHP_EOL;
        $html .= '                                </thead>'.PHP_EOL;
        $html .= '                                <tbody>'.PHP_EOL;
        foreach ($obj->empanadas->result->result() as $field) 
        {
        	$html .= '                          	<tr>'.PHP_EOL;
       		$html .= '                                	<td style="width:20px;"><input type="checkbox" id="'.$field->empanada_id.'"></input></td>'.PHP_EOL;
        	$html .= '                               	<td style="width:5px;"></td>'.PHP_EOL;
	        $html .= '                            		<td>'.$field->empanada_codigo.'</td>'.PHP_EOL;
	        $html .= '                            		<td>'.$field->empanada_nome.'</td>'.PHP_EOL;
        	$html .= '                                	<td><input id="qtde_'.$field->empanada_id.'" class="form-control" type="text"></td>'.PHP_EOL;
	        $html .= '                            		<td></td>'.PHP_EOL;
	        $html .= '                          	</tr>'.PHP_EOL;
        }
        $html .= '                                </tbody>'.PHP_EOL;
        $html .= '                            </table>'.PHP_EOL;
        $html .= '                        </div>'.PHP_EOL;
        $html .= '                    </div>'.PHP_EOL;

        $data['wrapper'] = 'Relatório de Empanda por Produção';
        $data['panel_default'] = $html;
        
        return $data;
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
	    $pdf->SetSubject('Listagem');
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
	    $lista = $this->create_list($pacotes);
	    
	    // Imprimir texto usando writeHTML()
	    $pdf->writeHTML('<br><h5>Lista de Empanadas<hr></h5>');
	    $pdf->SetFontSize(7);
	    $pdf->writeHTML($table);
	    $pdf->SetFontSize(12);
	    $pdf->writeHTML('<br><h5>Lista de Materia Prima Necessária<hr></h5>');
	    $pdf->SetFontSize(7);
	    $pdf->writeHTML($lista); 
	     
	    // Feche e saída PDF documento
	    // Este método tem várias opções, verifique a documentação do código-fonte para obter mais informações.
	    $pdf->Output('NTIC_Regras.pdf', 'I');
	}
	
	public function get_from_script()
	{
	    $script = $this->load->file(APPPATH . 'hooks/js_rd_producao.php');
	    return $script;
	}

	
	private function outinput_obj($pacotes)
	{

	    $obj = new stdClass();
	    
	    for ($i = 0; $i < count($pacotes); $i++)
	    {
	    
	    	if($i != 0)
	    	{
	    		$arr = explode(":", $pacotes[$i]);
	    		 
	    		if(key_exists(1, $arr))
	    		{
	    			$empanada_id[] = $arr[0];
	    			$mutiplicador[]= $arr[1];	    			
	    		
	    		}
	    	}
	    }
	    
	    // Obtém os items selecionados
	    $obj->result = $this->DB_End_Product->get_in($empanada_id);
	    
	    // Ajusta os motiplicadores
	    for ($j = 0; $j < count($mutiplicador); $j++)
	    {
	    	$ref =& $obj->result->result();
	    	$ref[$j]->mutiplicador = $mutiplicador[$j];
	    }
	    
	    
	    
	    
	    // buscamos os dados sobre todas as empanadas do banco
	    foreach ($obj->result->result() as $fields) {
	    
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
	    		$subfields->e_items_qtde *= $fields->mutiplicador;
	    			
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
	    			$materia_info->materia_qtde = $field_item->r_items_qtde;
	    			$subfields->Material[] = $materia_info;
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
	    // Fim do Objeto	    
	    return $obj;
	}
	        

	private function create_object($obj)
	{
	
		$html = '                        <div class="table-responsive">'.PHP_EOL;
	
		$html .= '                             <table>'.PHP_EOL;
		$html .= '                                <thead>'.PHP_EOL;
		$html .= '                                    <tr>'.PHP_EOL;
		$html .= '                                        <th style="width:50px;"><strong>Código</strong></th>'.PHP_EOL;
		$html .= '                                        <th style="width:150px;"><strong>Nome</strong></th>'.PHP_EOL;
		$html .= '                                        <th style="width:50px;"><strong>Margem</strong></th>'.PHP_EOL;
		$html .= '                                        <th style="width:60px;"><strong>Cust. Unitário</strong></th>'.PHP_EOL;
		$html .= '                                        <th style="width:60px;"><strong>Cust. Pacote</strong></th>'.PHP_EOL;
		$html .= '                                        <th style="width:60px;"><strong>Cust. Caixa</strong></th>'.PHP_EOL;
		$html .= '                                        <th style="width:60px;"><strong>Rendimento</strong></th>'.PHP_EOL;
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
	
		if($obj->result->num_rows() <= 0)
		{
			$html .= '                                    <tr><td colspan="5">Não a linhas para serem mostradas</td><tr>'.PHP_EOL;
		}
		else
		{
			foreach ($obj->result->result() as $field) {
				$html .= $this->create_object_data_row($field);
			}
		}
	
		return $html;
	}
	
	
	private function create_object_data_row($field)
	{
		$html  = '';
		$html .= '                                    <tr style="background-color:#FFFF00">'.PHP_EOL;
		$html .= '                             			<td style="width:50px;">'.$field->empanada_codigo.'</td>'.PHP_EOL;
		$html .= '                             			<td style="width:150px;">'.$field->empanada_nome.'</td>'.PHP_EOL;
		$html .= '										<td style="width:50px;">'.$field->empanada_margem.'</td>'.PHP_EOL;
		$html .= '										<td style="width:60px;">'.number_format($field->CUnitario,4).'</td>'.PHP_EOL;
		$html .= '										<td style="width:60px;">'.number_format($field->CPacote,4).'</td>'.PHP_EOL;
		$html .= '										<td style="width:60px;">'.number_format($field->CCaixa,4).'</td>'.PHP_EOL;
		$arr = $field->recheio->result();
		 
		$html .= '										<td style="width:60px;">'.$arr[0]->e_items_recheio_RendiFinal.'</td>'.PHP_EOL;
		$html .= '                                    </tr>'.PHP_EOL;
		$html .= '                                    <tr style="background-color:#FFFF00">'.PHP_EOL;
		$html .= '                                   	 	<td colspan="7">'.PHP_EOL;
		$html .= '                                    			<table style="left: 800px;">'.PHP_EOL;
	
	
		foreach ($field->recheio->result() as $field2)
		{
		  
			$html .= '                                    				<tr style="background-color:#FF9900">'.PHP_EOL;
			$html .= '                                      				<td style="width:195px;">'.$field2->e_items_recheio_nome.'</td>'.PHP_EOL;
			$html .= '                                      				<td style="width:50px;">'.number_format($field2->e_items_recheio_regra,4).'</td>'.PHP_EOL;
			$html .= '                                      			 	<td style="width:60px;">'.number_format($field2->e_items_recheio_cunit,4).'</td>'.PHP_EOL;
			$html .= '                                      			 	<td style="width:60px;">'.number_format($field2->e_items_recheio_ctotalKg,4).'</td>'.PHP_EOL;
			$html .= '                                      			 	<td style="width:60px;">'.number_format($field2->e_items_recheio_CustoFinal,4).'</td>'.PHP_EOL;
			$html .= '                                      			 	<td style="width:55px;">'.$field2->e_items_recheio_RendiFinal.'</td>'.PHP_EOL;
			$html .= '                                    				</tr>'.PHP_EOL;
			 
			foreach ($field2->Material as $itens) {
				$html .= '                                    				<tr style="background-color:#FFCC00">'.PHP_EOL;
				$html .= '                                      				<td style="width:10px;"> </td>'.PHP_EOL;
				$html .= '                                      				<td style="width:195px;">'.$itens->materia_nome.'</td>'.PHP_EOL;
				$html .= '                                      				<td style="width:40px;">'.$itens->materia_unid.'</td>'.PHP_EOL;
				$html .= '                                      				<td style="width:235px;">'.number_format($itens->materia_qtde,4).'</td>'.PHP_EOL;
				$html .= '                                    				</tr>'.PHP_EOL;;
			}
			 
		}
		$html .= '                                    			</table><br><br>'.PHP_EOL;
		$html .= '                                    		</td>'.PHP_EOL;
		$html .= '                                    </tr>'.PHP_EOL;
		$html .= '                                    <tr>'.PHP_EOL;
		$html .= '                                    	<td colspan="7"><br><br></td>'.PHP_EOL;
		$html .= '                                    </tr>'.PHP_EOL;
		return $html;
	}


    private function create_list($pacotes)
    {
        $html = '';
        
              
       
        $obj = new stdClass();
         
        for ($i = 0; $i < count($pacotes); $i++)
        {
        	 
        	if($i != 0)
        	{
        		$arr = explode(":", $pacotes[$i]);
        
        		if(key_exists(1, $arr))
        		{
        			$empanada_id[] = $arr[0];
        			$mutiplicador[]= $arr[1];
        	   
        		}
        	}
        }
                        
        
        
        $result = $this->create_object_materia($empanada_id, $mutiplicador);	             
        // montagem da lista OK
        
        $o = 0;
        foreach ($result as $key => $value) {        	
        	$objfinal[$o] = $this->DB_Raw_Material->get_info($key);
        	$objfinal[$o]->materia_qtde = $value; 
        	$o++;
        }
       
        
        $html = '';
        $html .= '                             <table>'.PHP_EOL;
        $html .= '                                <thead>'.PHP_EOL;
        $html .= '                                    <tr>'.PHP_EOL;
        $html .= '                                        <th style="width:50px;"><strong>Código</strong></th>'.PHP_EOL;
        $html .= '                                        <th style="width:150px;"><strong>Materia Prima</strong></th>'.PHP_EOL;
        $html .= '                                        <th style="width:50px;"><strong>Medida</strong></th>'.PHP_EOL;
        $html .= '                                        <th style="width:60px;"><strong>Quantidade</strong></th>'.PHP_EOL;
        $html .= '                                    </tr>'.PHP_EOL;
        $html .= '                                </thead>'.PHP_EOL;
        $html .= '                                <tbody>'.PHP_EOL;
        
    	foreach ($objfinal as $materia_prima_necessaria) {
        	$html .= '                                    <tr>'.PHP_EOL;
        	$html .= '                                        <td style="width:50px;">'.$materia_prima_necessaria->materia_id.'</td>'.PHP_EOL;
        	$html .= '                                        <td style="width:150px;">'.$materia_prima_necessaria->materia_nome.'</td>'.PHP_EOL;
        	$html .= '                                        <td style="width:50px;">'.$materia_prima_necessaria->materia_unid.'</td>'.PHP_EOL;
        	$html .= '                                        <td style="width:60px;">'.number_format($materia_prima_necessaria->materia_qtde,4).'</td>'.PHP_EOL;
        	$html .= '                                    </tr>'.PHP_EOL;
        }
        
        $html .= '                                </tbody>'.PHP_EOL;
        $html .= '                            </table>'.PHP_EOL;
        
        
        
        
        return $html;
    }
    
    
    
    private function create_object_materia($empanada_id, $mutiplicador)
    {
    	// Buscar os elementos selecionados no Front End;
    	// Nivel 0 Pai
    	$obj = $this->DB_End_Product->get_in($empanada_id);
    	
    	
    	$index = 0;     // zera $index contador 
    	$index_for = 0; // zera $index_for contador
    	
    	// agora vamos atribuir o fator mutiplicador em nosso objeto
    	foreach ($obj->result() as $fields) 
    	{	// Trabalhando no Nivel ( Pai ) 
    		$recheio = $this->DB_End_Product->get_items($fields->empanada_id);
    		$fields->empanada_recheio = $recheio->result();
    		$fields->mutiplicador = $mutiplicador[$index];    		
    		$index++;
    		
    		
    		foreach ($fields->empanada_recheio as $subfields) {
    			// Trabalhando no Nivel ( Filho )
    			$e_items_recheio = $this->DB_Fillings->get_items($subfields->db_recheio_id);
    			$subfields->e_items_recheio = $e_items_recheio->result();
    			
    			
    			foreach ($subfields->e_items_recheio as $r_items) {
    				// Trabalhando no Nivel ( Neto )
    				$r_items->r_items_qtde *= $fields->mutiplicador;
    				$r_items->r_items_materia = $this->DB_Raw_Material->get_info($r_items->db_materia_id);
    				$arr_items[$index_for]['materia_id'] = $r_items->r_items_materia->materia_id;
    				$arr_items[$index_for]['materia_qtde'] = $r_items->r_items_qtde;
    				$index_for ++;
    			} // termei o ( Neto )
    		} // termei o ( Filho )
    	} // termei o ( Pai )
    	
    	$index_for = 0; // Zerar Contador $index_for;
    	$obj = null;    // Destroy Objeto;
    	
    	// agora vou duplicar o array $arr_items para comparar index a index
    	$arr_itemClone = $arr_items;
    	
    	// agora vou correr pela matriz $arr_items
    	foreach ($arr_items as $keyItems => $valueItems) 
    	{
    		$obj[$valueItems['materia_id']] = $valueItems['materia_qtde'];
    		// agora vou correr pela matriz $arr_itemClone
	    	foreach ($arr_itemClone as $keyClone => $valueClone) 
	    	{
	    		// Estamos validando se as possições são diferentes
	    		if($keyItems != $keyClone){
	    			
	    			// Aqui validar se a materia-prima é a mesma
	    			if($valueItems['materia_id'] == $valueClone['materia_id'])
	    			{
	    				$obj[$valueItems['materia_id']] += $valueClone['materia_qtde'];
	    			}
	    				    			
	    		}
	    		
	    	}
	    	
    	}
    	
    	return $obj;
    }
}