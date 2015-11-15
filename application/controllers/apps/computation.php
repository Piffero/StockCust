<?php
class Computation 
{
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
		return floatval($qtde * $FCc * $CUnid);		
	}
	
	/**
	 * Realiza uma somatória de uma coluna podendo ser do custo total da receita ou Total Kg de uma Receita 
	 * @param array $array_expression
	 * @return float
	 */
	public function calcula_coluna(&$array_expression)
	{
		$soma = 0;
		foreach ($array_expression as $value) {
			$soma += $value;
		}
		return floatval($soma);
	}
	
	/**
	 * Calcula a margem para obter o rendimento ou custo unitário de uma receita
	 * @param array $array_expression
	 * @param int $arg
	 */
	public function calcula_margem($array_expression, $arg)
	{
		$soma = $this->calcula_coluna($array_expression);
		$margem = $soma / $arg;
		return $margem;
	}
}