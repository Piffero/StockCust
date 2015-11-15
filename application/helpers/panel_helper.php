<?php 
/**
 * Monta panel que será exibido no layout
 * @param stdClass $obj
 */
function manager_panel($obj)
{    
    $html  = '                    <div class="panel panel-default">'.PHP_EOL;    
     $html .= panel_heading().PHP_EOL;
     $html .= wrapper($obj).PHP_EOL;
     $html .= manager_table($obj->table_manager).PHP_EOL;
     $html .= panel_footer($obj).PHP_EOL;    
    $html .= '                    </div>'.PHP_EOL;
    return $html;
}


/**
 * Monta panel que será exibido no layout
 * @param stdClass $obj
 */
function manager_materia($obj)
{
    $html  = '                    <div class="panel panel-default">'.PHP_EOL;
    $html .= panel_heading().PHP_EOL;
    $html .= wrapper($obj).PHP_EOL;
    $html .= mt_materia($obj->table_manager).PHP_EOL;
    $html .= panel_footer($obj).PHP_EOL;
    $html .= '                    </div>'.PHP_EOL;
    return $html;
}

/**
 * Monta panel que será exibido no layout
 * @param stdClass $obj
 */
function manager_user($obj)
{
	$html  = '                    <div class="panel panel-default">'.PHP_EOL;
	$html .= panel_heading().PHP_EOL;
	$html .= wrapper($obj).PHP_EOL;
 	$html .= mt_user($obj->table_manager).PHP_EOL;
	$html .= panel_footer($obj).PHP_EOL;
	$html .= '                    </div>'.PHP_EOL;
	return $html;
}

/**
 * Monta panel que será exibido no layout
 * @param stdClass $obj
 */
function manager_recheio($obj)
{
    $html  = '                    <div class="panel panel-default">'.PHP_EOL;
    $html .= panel_heading().PHP_EOL;
    $html .= wrapper($obj).PHP_EOL;
    $html .= mt_recheio($obj->table_manager).PHP_EOL;
    $html .= panel_footer($obj).PHP_EOL;
    $html .= '                    </div>'.PHP_EOL;
    return $html;
}



/**
 * Monta panel que será exibido no layout
 * @param stdClass $obj
 */
function manager_empanada($obj)
{
    $html  = '                    <div class="panel panel-default">'.PHP_EOL;
    $html .= panel_heading().PHP_EOL;
    $html .= wrapper($obj).PHP_EOL;
    $html .= mt_empanada($obj->table_manager).PHP_EOL;
    $html .= panel_footer($obj).PHP_EOL;
    $html .= '                    </div>'.PHP_EOL;
    return $html;
}

/**
 * Monta panel que será exibido no layout
 * @param stdClass $obj
 */
function manager_permission($obj)
{
	$html  = '                    <div class="panel panel-default">'.PHP_EOL;
	$html .= panel_heading().PHP_EOL;
	$html .= wrapper_permission($obj).PHP_EOL;
	$html .= mt_permission().PHP_EOL;
	$html .= '                    </div>'.PHP_EOL;
	return $html;
}

/**
 * Painal do Cabeçalho Permissões
 * @param stdClass $obj
 * @return string
 */
function wrapper_permission($obj)
{

	$html = '                        <div class="row wrapper">'.PHP_EOL;
	$html .= '                            <div class="col-sm-5 m-b-xs">'.PHP_EOL;
	$html .= '                                  '.form_dropdown('actCtrl', $obj->actCtr_option, $obj->actCtr_option[0],'class="input-sm form-control w-sm inline v-middle" id="actCtrl"').PHP_EOL;
	$html .= '                                  '.form_button($obj->btn_default).PHP_EOL;
	$html .= '                                  '.form_button($obj->btn_success).PHP_EOL;
	$html .= '                                  '.form_button($obj->btn_danger).PHP_EOL;
	$html .= '                            </div>'.PHP_EOL;
	$html .= '                            <div class="col-sm-4"></div>'.PHP_EOL;
	$html .= '                                  <div class="col-sm-3">'.PHP_EOL;
	$html .= '                                      <div class="input-group">'.PHP_EOL;
	$html .= '                                      <input class="input-sm form-control" type="text" id="actCtrl" onkeyup="search(this, event);" placeholder="Buscar"></input>'.PHP_EOL;
	$html .= '                                  </div>'.PHP_EOL;
	$html .= '                            </div>'.PHP_EOL;
	$html .= '                        </div>'.PHP_EOL;
	return $html;
}

/**
 * Painal do Cabeçalho
 * @param stdClass $obj
 * @return string
 */
function wrapper($obj)
{

	$html = '                        <div class="row wrapper">'.PHP_EOL;
	$html .= '                            <div class="col-sm-5 m-b-xs">'.PHP_EOL;
	$html .= '                                  '.form_dropdown('actCtrl', $obj->actCtr_option, $obj->actCtr_option[0],'class="input-sm form-control w-sm inline v-middle\" id="actCtrl"').PHP_EOL;
	$html .= '                                  '.form_button($obj->btn_default).PHP_EOL;
	$html .= '                                  '.form_button($obj->btn_success).PHP_EOL;
	$html .= '                                  '.form_button($obj->btn_danger).PHP_EOL;
	$html .= '                            </div>'.PHP_EOL;
	$html .= '                            <div class="col-sm-4"></div>'.PHP_EOL;
	$html .= '                                  <div class="col-sm-3">'.PHP_EOL;
	$html .= '                                      <div class="input-group">'.PHP_EOL;
	//$html .= '                                      <input class="input-sm form-control" type="text" onkeyup="search(this, event);" placeholder="Buscar"></input>'.PHP_EOL;
	$html .= '                                  </div>'.PHP_EOL;
	$html .= '                            </div>'.PHP_EOL;
	$html .= '                        </div>'.PHP_EOL;
	return $html;
}

/**
 * Cabeçalho do panel
 * @return string
 */
function panel_heading()
{
    $html = '                        <div class="panel-heading">'.PHP_EOL;
    $html .= '                           Planilha Responsive'.PHP_EOL;
    $html .= '                        </div>'.PHP_EOL;
    return $html;
}

/**
 * roda pé do panel
 * @param stdClass $obj
 */
function panel_footer($obj)
{
    $html = '                        <footer class="panel-footer">'.PHP_EOL;
    $html .= '                            <div class="row">'.PHP_EOL;
    $html .= panel_footer_col1($obj).PHP_EOL;
    $html .= panel_footer_col2($obj).PHP_EOL;
    $html .= panel_footer_col3($obj).PHP_EOL;
    $html .= '                           </div>'.PHP_EOL;
    $html .= '                        </footer>'.PHP_EOL;
    return $html;
}

/**
 * Define primeira coluna
 * @param stdClass $obj
 */
function panel_footer_col1($obj)
{
    $html = '                                  <div class="col-sm-4 hidden-xs">'.PHP_EOL;   
    $html .= '                                  </div>'.PHP_EOL;
    return $html;
}

/**
 * Define segunda coluna
 * @param  stdClass $obj
 */
function panel_footer_col2($obj)
{
    $html = '                                  <div class="col-sm-4 text-center">'.PHP_EOL;
    $html .= '                                      <small class="text-muted inline m-t-sm m-b-sm">'.PHP_EOL;
    $html .= '                                          Mostrando '.$obj->showStart.'-'.$obj->showEnd.' de '.$obj->showTotal.' itens'.PHP_EOL;
    $html .= '                                      </small>'.PHP_EOL;
    $html .= '                                 </div>'.PHP_EOL;
    return $html;
}

/**
 * Define terceira coluna
 * @param stdClass $obj
 */
function panel_footer_col3($obj)
{
    $html = '                                 <div class="col-sm-4 text-right text-center-xs">'.PHP_EOL;    
    $html .= '                                      <ul class="pagination pagination-sm m-t-none m-b-none">'.PHP_EOL;
    
    $num_pages = ceil($obj->showTotal/$obj->showDefault);
    for ($i = 1; $i <= $num_pages + 1; $i++)
        $html .= '                                          <li><a href="javascript: page_go('.$i.');">'.$i.'</a></li>'.PHP_EOL;
       
    $html .= '                                      </ul>'.PHP_EOL;
    $html .= '                                 </div>'.PHP_EOL;
    return $html;
}
