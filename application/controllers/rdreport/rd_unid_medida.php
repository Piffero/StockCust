<?php

class RD_Unid_Medida extends CI_Controller
{
    
	function index()
	{

	    //Inicializar mecanismo de visão carregando do documento .pdf
	    ob_start();
	    
	    // define algum conteúdo para imprimir HTML
	    $obj = new stdClass();   
	    $obj->showFields =  3;
	    $obj->fields = $this->DB_Unid_Medida->get_fields();
        $obj->result = $this->DB_Unid_Medida->get_all(0,1000);      
	    
	    
	    //Incluir a biblioteca PDF principal (que integra ao caminho de instalação do tcpdf_min)
	    $this->load->library('pdf');
	    
	    // Criar um novo documento PDF
	    $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
	    
	    //define o conjunto de informações de documentos
	    
	    // define informações de documentos
	    $pdf->SetCreator(PDF_CREATOR);
	    $pdf->SetAuthor('BitStock-3.0');
	    $pdf->SetTitle('Unidades de Medida Registradas');
	    $pdf->SetSubject('Listagem');
	    $pdf->SetKeywords('BitStock-3.0, PDF, Listagem, 0001, guia');
	    
	    // define dados de cabeçalho padrão
	    $PDF_HEADER_LOGO = 'img/icon_inventario.gif';
	    $PDF_HEADER_LOGO_WIDTH = 20;
	    $PDF_HEADER_TITLE = 'Registro de Unidades de Medida';
	    
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
	    $table = $this->create_object($obj);

	    // Imprimir texto usando writeHTML()
	    $pdf->writeHTML('<br><h5>Lista de Unidades de Medida<hr></h5>');
	    $pdf->SetFontSize(7);
	    $pdf->writeHTML($table);
	     
	    
	    // Feche e saída PDF documento
	    // Este método tem várias opções, verifique a documentação do código-fonte para obter mais informações.
	    $pdf->Output('NTIC_Regras.pdf', 'I');
	    
	}
	     

	private function create_object($obj)
	{	
        
        $html = '                        <div class="table-responsive">'.PHP_EOL;

        $html .= '                             <table>'.PHP_EOL;
        $html .= '                                <thead>'.PHP_EOL;
        $html .= '                                    <tr>'.PHP_EOL;        
        $html .= '                                        <th><strong>Nome</strong></th>'.PHP_EOL;
        $html .= '                                        <th><strong>Sigla</strong></th>'.PHP_EOL;
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
        $html .= '                                        <td>'.$field->$fields[$i].'</td>'.PHP_EOL;
     }
        $html .= '                                    </tr>'.PHP_EOL;
        
        return $html;    
    }
	     	
}