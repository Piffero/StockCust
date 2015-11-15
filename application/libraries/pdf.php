<?php if ( ! defined('BASEPATH')) exit('Sem permiti&#231;&#227;o acesso direto aos roteiros');

/*
 * Notei em algumas diferen�as sutis entre outros APIs de para gera��o de documentos .PDF
 * nas integra��o junto ao Codeignater o processo n�o funciona bem como objeto PDF-CI,
 * 
 * A API que foi utilizada para integra��o foi a TCPDF devido sua vers�o compactada TCPDF_MIN,
 * esta API em quest�o exceto o arquivo contido em "/tcpdf_min/tools/tcpdf_addfont.php" j� est�
 * de acordo com as boas praticas de programa��o OOP-PHP.
 * 
 * Ent�o ap�s a analize da API pensei que teria como trabalho a forma mais simples e f�cil de 
 * integra��o criaando um novo arquivo: /application/libraries/Pdf.php.
 * 
 * Certifique-se de que o arquivo tcpdf.php principal nesta no diret�rio TCPDF_MIN
 * Para criar um novo PDF voc� faria algo assim em seu controlador
 * 
 * @example
 * 
 * 		$this->load->library('Pdf');
 * 		
 * 		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
 * 		$pdf->SetTitle('[MEU TITULO]');
 * 		$pdf->SetHeaderMargin(30);
 * 		$pdf->SetTopMargin(20);
 * 		$pdf->setFooterMargin(20);
 * 		$pdf->SetAutoPageBreak(true);
 * 		$pdf->SetAuthor('[TITULO DA APLICA��O]');
 * 		$pdf->SetDisplayMode('real', 'default');
 * 		
 * 		$pdf->Write(5, 'Some sample text');
 * 		$pdf->Output('My-File-Name.pdf', 'I');
 * 
 * @author Thiago Piffero
 * 
 * @link http://www.tcpdf.org/doc/code/classTCPDF.html
 */

require_once dirname(__FILE__) . '/tcpdf_min/tcpdf.php';

/**
 * Classe para a gera��o de documentos PDF compativel com CodeIgnater.<br>
 * O projeto TCPDF foi originalmente derivada, em 2002, a partir da classe FPDF,<br> mas agora � quase inteiramente reescrito.<br>
 * @version 6.2.7
 * @author Piffero
 */
class Pdf extends TCPDF
{
	/**
	 * Este � a classe construtor.
	 * Permite configurar o formato da folha, a orienta��o e a unidade de medida utilizada em todos os m�todos (excepto para os tamanhos de fonte).
	 *
	 * IMPORTANTE: Por favor note que este m�todo define o mb_internal_encoding para ASCII, por isso, se voc� estiver usando as fun��es do m�dulo mbstring com TCPDF voc� precisa definir corretamente / desactivado o mb_internal_encoding quando necess�rio.
	 *
	 * @param $orientation (string) orienta��o da p�gina. Os valores poss�veis s�o (case insensitive):<ul><li>P para Retrato (Padr�o)</li><li>L para paisagem</li><li>'' (string vazia) para orienta��o autom�tica</li></ul>
	 * @param $unit (string) Unidade de medida do utilizador. Os valores poss�veis s�o:<ul><li>pt: Ponto</li><li>mm: Milimetro (Padr�o)</li><li>cm: Centimetro</li><li>in: Polegada</li></ul>A point equals 1/72 de polegada, ou seja, cerca de 0,35 mm (sendo uma polegada 2,54 cm). Esta � uma unidade muito comum em tipografia; tamanhos de fonte s�o expressos nessa unidade.<br>
	 * @param $format (mixed) O formato usado para p�ginas. Ele pode ser: um dos valores de seq��ncia de caracteres especificada no getPageSizeFromFormat() ou um conjunto de par�metros especificados no setPageFormat().
	 * @param $unicode (boolean) TRUE significa que o texto de entrada � unicode (padr�o = true)
	 * @param $encoding (string) Codifica��o charset (usado somente quando a convers�o de volta entidades html); padr�o � UTF-8.
	 * @param $diskcache (boolean) recurso absoleto
	 * @param $pdfa (boolean) Se TRUE definir o documento para PDF/A modalidade.	 
	 */
	function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false)
	{
		parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
	}
}