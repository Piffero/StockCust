<?php
class new_recipe 
{
	
	public $valor_linha = 0;
	public $valor_coluna = 0;
	public $valor_porcentual = 0;
	public $valor_reducao = 0;
	public $loop_ativo = 1;
	public $margem = 0;
	
	/**
	 * Calcula Custo Total de uma Matéria Prima
	 * @param float $qtde
	 * @param float $FCc
	 * @param float $CUnid
	 * 
	 * @return float
	 */
	public function calcula_linha(&$qtde, &$FCc, &$CUnid)
	{	
		$this->valor_linha = floatval($qtde * $FCc * $CUnid);
		return floatval($this->valor_linha);		
	}
	
	/**
	 * Realiza uma somatória de uma coluna podendo ser do custo total da receita ou Total Kg de uma Receita 
	 * @param array $array_expression
	 * @return float
	 */
	public function calcula_coluna(&$array_expression)
	{
		$soma = 0;
		
		if(!is_array($array_expression)){
			$this->valor_coluna = floatval($array_expression);
			return $this->valor_coluna;
		}
			
		foreach ($array_expression as $value) {
			$soma += $value;			
		}
		
		$this->valor_coluna = floatval($soma);
		return $this->valor_coluna;
	}
	
	/**
	 * Calcula a margem para obter o rendimento ou custo unitário, pacote, caixa de uma receita
	 * @param array $array_expression
	 * @param int $arg
	 */
	public function calcula_margem($array_expression, $arg)
	{
		$soma = $this->calcula_coluna($array_expression);
		$this->margem = $soma / $arg;		
		return $this->margem;
	}
	
	
	/** 
	 * Calcula a regra de 3
	 * @param int $mutilicador1
	 * @param int $mutilicador2
	 * @param int $divisor
	 */
	public function calcula_porcentual($mutilicador1, $mutilicador2, $divisor)
	{
		$resultado = $mutilicador1 * $mutilicador2;
		$this->valor_porcentual = $resultado / $divisor;
		return $this->valor_porcentual;
	}
	
	
	/** 
	 * Modifica receita função especifica para relatorio sobdemanda.
	 *  
	 */
	public function alter_recipe(&$obj, &$estoque_data)
	{
			$obj->empanada_rendimento = array();
			foreach ($obj->empanada_recheio as $subkey =>$subfields){
				foreach ($subfields as  $childfields){
		        	
								
					foreach ($estoque_data->result() as $estoquefields){
						
						// varifica caso trata-se da mesma materia prima
						if($childfields->db_materia_id == $estoquefields->estoque_codigo)
						{ 	
//							echo 'trata-se da mesma materia prima <br>';							
							if($estoquefields->estoque_reducao == 0)
							{	
//								echo 'é uma materia prima de reducao <br>';
								if($childfields->r_items_qtde > $estoquefields->estoque_qtde)
								{   
//									echo 'a quantidade da receita é maior que em estoque '.$childfields->r_items_qtde.'<br>';
									if($this->valor_reducao == 0)
									{   
//										echo 'é a primeria redução empanda echo = '.$childfields->r_items_qtde.'<br>';										
										$this->valor_reducao = (100 - $this->calcula_porcentual($estoquefields->estoque_qtde, 100, $childfields->r_items_qtde));
										$childfields->r_items_qtde = $estoquefields->estoque_qtde;
										$this->loop_ativo++;
										
//										echo '['. $obj->empanada_codigo .']['. $childfields->db_materia_id  .'] = '. $estoquefields->estoque_qtde. '<br>';
									}
									else
									{
//										echo ' não é primeria redução aplica a redução <br>';
										

										$reducao = $this->calcula_porcentual($this->valor_reducao, $childfields->r_items_qtde, 100);
										$childfields->r_items_qtde -= $reducao;	
										
										if($childfields->r_items_qtde > $estoquefields->estoque_qtde)
											$childfields->r_items_qtde = $estoquefields->estoque_qtde;
										
//											echo '['. $obj->empanada_codigo .']['. $childfields->db_materia_id  .'] = '. $estoquefields->estoque_qtde. '<br>';
									}
									
								}
								else
								{
//									echo 'a quantidade da receita não é maior que em estoque <br>';									
									if($this->valor_reducao != 0)
									{
//										echo ' a uma redução em andamento <br>';
										$reducao = $this->calcula_porcentual($this->valor_reducao, $childfields->r_items_qtde, 100);
										$childfields->r_items_qtde -= $reducao;
										
									}
										
//									echo 'muda a quantidade do estoque <br>';									
									$estoquefields->estoque_qtde -= $childfields->r_items_qtde;
									
//									echo '['. $obj->empanada_codigo .']['. $childfields->db_materia_id  .'] = '. $estoquefields->estoque_qtde + $estoquefields->estoque_qtde +  $childfields->r_items_qtde .' - '. $childfields->r_items_qtde .' = '. $estoquefields->estoque_qtde .'<br>';
								}
								$obj->empanada_producao += $childfields->r_items_qtde;								
								$obj->empanada_rendimento[$subkey] = (int) (($obj->empanada_producao / $childfields->recheio_regra) / $childfields->recheio_fcr) - 1;
//								echo '['. $obj->empanada_codigo .']['. $childfields->db_materia_id  .'] Rendimento = '. $obj->empanada_rendimento[$subkey]. '<br>';
							}
							
						}
						
					}
					
// 					print_r($childfields);
// 					echo '<br>';
		        } 
		        $obj->empanada_producao = 0;
			}
			
			
// 		echo '<hr>';

		
		return $obj;				
	}
	
}