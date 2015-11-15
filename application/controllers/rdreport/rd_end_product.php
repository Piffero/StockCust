<?php

class RD_End_Product extends CI_Controller
{
    
	function index()
	{

	    //Inicializar mecanismo de visÃ£o carregando do documento .pdf
	    ob_start();
	    
	    // define algum conteÃºdo para imprimir HTML
	    $obj = new stdClass();   
	    $obj->showFields =  9;
        $obj->fields = $this->DB_End_Product->get_fields();
        $obj->result = $this->DB_End_Product->get_all(0,1000);
        
        $obj->fields[] = 'CUnitario';
        $obj->fields[] = 'CPacote';
        $obj->fields[] = 'CCaixa';
        $obj->fields[] = 'RendiFinal';
        
        
        
        // buscamos os dados sobre todas as empanadas do banco
        foreach ($obj->result->result() as $fields) {
        
        	$fields->CUnitario = 0;
        	$fields->CPacote = 0;
        	$fields->CCaixa = 0;
        	$fields->PVenda = 0;
        	$fields->RendiFinal = 0;
        
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
        		$fields->RendiFinal  = $ItensQuest[0]->e_items_recheio_RendiFinal;
        	}
        	else
        	{
        		$fields->RendiFinal = ($fields->recheio->num_rows() == 1 ? $subfields->e_items_recheio_RendiFinal : 0);
        	}
        
        
        }
	    
	    //Incluir a biblioteca PDF principal (que integra ao caminho de instalaÃ§Ã£o do tcpdf_min)
	    $this->load->library('pdf');
	    
	    // Criar um novo documento PDF
	    $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
	    
	    //define o conjunto de informaÃ§Ãµes de documentos
	    
	    // define informaÃ§Ãµes de documentos
	    $pdf->SetCreator(PDF_CREATOR);
	    $pdf->SetAuthor('BitStock-3.0');
	    $pdf->SetTitle('Empanadas Registradas');
	    $pdf->SetSubject('Listagem');
	    $pdf->SetKeywords('BitStock-3.0, PDF, Listagem, 0001, guia');
	    
	    // define dados de cabeÃ§alho padrÃ£o
	    $PDF_HEADER_LOGO = 'img/icon_inventario.gif';
	    $PDF_HEADER_LOGO_WIDTH = 20;
	    $PDF_HEADER_TITLE = 'Registro de Empanadas';
	    
	    $pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, 'Ferras Fernandes'.PHP_EOL, array(51,51,51), array(153,153,153));
	    $pdf->setFooterData(array(0,64,0), array(0,64,128));
	    
	    // define fontes cabeÃ§alho e rodapÃ©
	    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	    
	    // define fonte com espaÃ§amento uniforme padrÃ£o
	    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	    
	    // define margens
	    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	    
	    // define quebras de pÃ¡gina automÃ¡tica
	    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	    
	    // define modo de subconjunto de fontes padrÃ£o
	    $pdf->setFontSubsetting(true);
	    
	    // Adicionar uma pÃ¡gina
	    // Este mÃ©todo tem vÃ¡rias opÃ§Ãµes, verifique a documentaÃ§Ã£o do cÃ³digo-fonte para mais informaÃ§Ãµes.
	    $pdf->AddPage();
	    
	  
	    // Monta tabela de acordo com os dados de produÃ§Ã£o
	    $table = $this->create_object($obj);

	    // Imprimir texto usando writeHTML()
	    $pdf->writeHTML('<br><h5>Lista de Empanadas<hr></h5>');
	    $pdf->SetFontSize(7);
	    $pdf->writeHTML($table);
	     
	    
	    // Feche e saÃ­da PDF documento
	    // Este mÃ©todo tem vÃ¡rias opÃ§Ãµes, verifique a documentaÃ§Ã£o do cÃ³digo-fonte para obter mais informaÃ§Ãµes.
	    $pdf->Output('NTIC_Regras.pdf', 'I');
	    
	}
	     

	private function create_object($obj)
	{	
        
        $html = '                        <div class="table-responsive">'.PHP_EOL;

        $html .= '                             <table>'.PHP_EOL;
        $html .= '                                <thead>'.PHP_EOL;
        $html .= '                                    <tr>'.PHP_EOL;        
        $html .= '                                        <th style="width: 30px;"><strong>Código</strong></th>'.PHP_EOL;        
        $html .= '                                        <th style="width: 150px;"><strong>Nome</strong></th>'.PHP_EOL;        
        $html .= '                                        <th><strong>Margem</strong></th>'.PHP_EOL;        
        $html .= '                                        <th><strong>Cust. Unitário</strong></th>'.PHP_EOL;        
        $html .= '                                        <th><strong>Cust. Pacote</strong></th>'.PHP_EOL;        
        $html .= '                                        <th><strong>Cust. Caixa</strong></th>'.PHP_EOL;  
        $html .= '                                        <th><strong>Rendimento</strong></th>'.PHP_EOL;
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
                $html .= $this->create_object_data_row($field, $obj->fields, $obj->showFields);
            }
        }
        
        return $html;
    }


    private function create_object_data_row($field, $fields, $show)
    {
        $html  = '';
        $html .= '                                    <tr>'.PHP_EOL;        
            
     for($i=1; $i < $show; $i++){
     	if($i == 1)
     	{
     		$html .= '                                        <td style="width: 30px;">'.$field->$fields[$i].'</td>'.PHP_EOL;
     	}
     	elseif ($i == 2)
     	{
     		$html .= '                                        <td style="width: 150px;">'.$field->$fields[$i].'</td>'.PHP_EOL;
     	}
     	elseif ($i != 4)
     	{   
     		if(($i >= 5) && ($i <= 7)){
     			$html .= '                                        <td>'.number_format($field->$fields[$i], 4).'</td>'.PHP_EOL;
     		}else{
     			$html .= '                                        <td>'.$field->$fields[$i].'</td>'.PHP_EOL;
     		}     		
     	}     	
     }
        $html .= '                                    </tr>'.PHP_EOL;		
        return $html;    
    }
	     	
}