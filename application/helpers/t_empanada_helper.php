<?php
function mt_empanada($obj)
{   
        
    $html = '                        <div class="table-responsive">'.PHP_EOL;    
    $html .= mt_empanada_data($obj);
    $html .= '                        </div>'.PHP_EOL;

    return $html;
}


function mt_empanada_data($obj)
{
    $CI = & get_instance();
    $html = '                             <table class="table table-striped b-t b-light">'.PHP_EOL;
    $html .= '                                <thead>'.PHP_EOL;
    $html .= '                                    <tr>'.PHP_EOL;
    $html .= '                                        <th style="width:20px;"><input type="checkbox"></input></th>'.PHP_EOL;
    $html .= '                                        <th style="width:5px;"></th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[1]).'</th>'.PHP_EOL;
    $html .= '                                        <th style="width:300px;">'.$CI->lang->line($obj->fields[2]).'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[3]).'</th>'.PHP_EOL;    
    $html .= '                                        <th>'.$CI->lang->line('empanada_cunid').'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line('empanada_cpact').'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line('empanada_ccaix').'</th>'.PHP_EOL;
    $html .= '										  <th>Rendimento</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line('empanada_venda').'</th>'.PHP_EOL;
    $html .= '                                        <th style="width:30px;"></th>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= '                                </thead>'.PHP_EOL;
    $html .= '                                <tbody>'.PHP_EOL;
    $html .= '                                    <tr id="rowMethodData" style="display: none;">'.PHP_EOL;
    $html .= '                                        <td style="width:20px;"></td>'.PHP_EOL;
    $html .= '                                        <td style="width:5px;"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iCodigo" class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iNome"   class="form-control" type="text"></td>'.PHP_EOL;    
    $html .= '                                        <td><input id="iMarge"  class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iCU"     class="form-control" type="text" Disabled></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iCP"     class="form-control" type="text" Disabled></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iCC"     class="form-control" type="text" Disabled></td>'.PHP_EOL;
    $html .= '										  <th><input id="iRed"    class="form-control" type="text" Disabled></th>'.PHP_EOL;
    $html .= '                                        <td><input id="ivenda"  class="form-control" type="text" Disabled></td>'.PHP_EOL;
    $html .= '                                        <td style="width:30px;"></td>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= mt_empanada_rows($obj);
    $html .= '                                </tbody>'.PHP_EOL;
    $html .= '                            </table>'.PHP_EOL;
    
    
    $html .= '<datalist id="browsers">';    
    foreach ($obj->datalist->result() as $item_list)
    {
           $html .= '<option value="'.$item_list->regras_id.'">'.$item_list->regras_nome.'</option>';
    }
    $html .= '</datalist>';
    
    
    $html .= '<datalist id="recheio">';
    foreach ($obj->subdatalist->result() as $subitem_list)
    {
        $html .= '<option value="'.$subitem_list->recheio_id.'">'.$subitem_list->recheio_nome.'</option>';
    }
    $html .= '</datalist>';
    
    
    return $html;
}

function mt_empanada_rows($obj)
{
    $html = '';
    
    if($obj->result->num_rows() <= 0)
    {
        $html .= '                                    <tr><td colspan="7">Não a linhas para serem mostradas</td><tr>'.PHP_EOL;
    }
    else 
    {
        $i = 0;
        foreach ($obj->result->result() as $field) {
            $html .= mt_empanada_data_row($field, $obj->fields, $obj->showFields);
        }
    }
    
    return $html;
}


function mt_empanada_data_row($field, $fields, $show)
{
    $html  = '';
    $html .= '                                    <tr ondblclick="showMethodEdit('.$field->$fields[0].');">'.PHP_EOL;
    $html .= '                                        <td><input type="checkbox" name="post[]" id="'.$field->$fields[0].'"></input></td>'.PHP_EOL;
    $html .= '                                        <td>'.PHP_EOL;
    $html .= '                                          <a class="" id="open_'.$field->$fields[0].'" href="javascript:active_subitem('.$field->$fields[0].');">'.PHP_EOL;
    $html .= '                                                <i class="fa fa-plus text-success text-active"></i>'.PHP_EOL;
    $html .= '                                          </a>'.PHP_EOL;
    $html .= '                                        </td>'.PHP_EOL;
    for($i=1; $i < $show; $i++)
    {   
    	if($i == 2)
    	{
    		$html .= '                                        <td id="uNome_'.$field->$fields[0].'">'.$field->$fields[$i].'</td>'.PHP_EOL;
    	}
    	else 
    	{
    		$html .= '                                        <td>'.$field->$fields[$i].'</td>'.PHP_EOL;
    	}     
        
    }

    
    $html .= '                                        <td>'.number_format($field->CUnitario, 4, ',','.').'</td>'.PHP_EOL;
    $html .= '                                        <td>'.number_format($field->CPacote, 4, ',','.').'</td>'.PHP_EOL;
    $html .= '                                        <td>'.number_format($field->CCaixa, 4, ',','.').'</td>'.PHP_EOL;    
    $html .= '                                        <td>'.$field->e_items_recheio_RendiFinal.'</td>'.PHP_EOL;
    $html .= '                                        <td>'.PHP_EOL;
    $html .= '                                          <a class="btn m-b-xs btn-sm btn-info btn-addon" href="javascript:popup_sales('.$field->$fields[0].');">'.PHP_EOL;
    $html .= '                                               <i class="fa fa-folder-open"></i> Abrir'.PHP_EOL;
    $html .= '                                          </a>'.PHP_EOL;
    $html .= '                                        </td>'.PHP_EOL;
    
    
    $html .= '                                        <td>'.PHP_EOL;
    
    if(($field->$fields[4] == 0)){
        $html .= '                                            <a class="active" id="active_'.$field->$fields[0].'" href="javascript:active_item('.$field->$fields[0].');">'.PHP_EOL;
        $html .= '                                                <i class="fa fa-check text-success text-active"></i>'.PHP_EOL;     
        $html .= '                                            </a>'.PHP_EOL;
        
    }   
    else 
    {
        $html .= '                                            <a class="" id="active_'.$field->$fields[0].'" href="javascript:active_item('.$field->$fields[0].');">'.PHP_EOL;        
        $html .= '                                                <i class="fa fa-times text-danger text-active"></i>'.PHP_EOL;
        $html .= '                                            </a>'.PHP_EOL;
    }
    
    $html .= '                                        </td>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    
    $html .= '                                    <tr id="rowMethodSubData_'.$field->$fields[0].'" style="display: none;">'.PHP_EOL;
    $html .= '                                        <td style="width:20px;"></td>'.PHP_EOL;
    $html .= '                                        <td style="width:5px;"></td>'.PHP_EOL;
    $html .= '                                        <td style="width:10px;"></td>'.PHP_EOL;
    $html .= '                                        <td colspan="9">'.PHP_EOL;
    $html .= mt_empanada_recheio($field->recheio, $field->$fields[0]);
    $html .= '                                        </td>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    
    return $html;    
}



function mt_empanada_recheio($obj, $id)
{
    $html = '<form class="off" name="form_subitem">';
    $html .= '                                           <table class="table table-striped b-t b-light">'.PHP_EOL;
    $html .= '                                              <thead>'.PHP_EOL;
    $html .= '                                                  <tr>'.PHP_EOL;
    $html .= '                                                      <th style="width:380px;">Recheio / Código</th>'.PHP_EOL;
    $html .= '                                                      <th>Qtde</th>'.PHP_EOL;
    $html .= '                                                      <th>Fc Receita</th>'.PHP_EOL;
    $html .= '                                                      <th>C. Total</th>'.PHP_EOL;
    $html .= '                                                      <th>Rendimento</th>'.PHP_EOL;
    $html .= '                                                  </tr>'.PHP_EOL;
    $html .= '                                              </thead>'.PHP_EOL;
    $html .= '                                              <tbody>'.PHP_EOL;
    $html .= '                                                  <tr>'.PHP_EOL;
    $html .= '                                                      <td style="width:380px;"><input id="input_item_materia_'.$id.'" name="input_item_materia" class="form-control" type="text" list="recheio" style="border: solid 1px #6611DD" onkeyup="setFocus(event);" ></td>'.PHP_EOL;    
    $html .= '                                                      <td><input id="input_item_qtde_'.$id.'" name="input_item_qtde" class="form-control" type="text"></td>'.PHP_EOL;    
    $html .= '                                                      <td><input id="input_item_fcr_'.$id.'" name="input_item_fcr" class="form-control" type="text" onkeyup="showMethodSaveItem('.$id.', event);"</td>'.PHP_EOL;
    $html .= '                                                      <td><input disabled class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                                      <td><input disabled class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                                  </tr>'.PHP_EOL;

    $html .= mt_empanada_recheio_data($obj);

    $html .= '                                              </tbody>'.PHP_EOL;
    $html .= '                                          </table>'.PHP_EOL;
    $html .= '</form>';

    return $html;
}


function mt_empanada_recheio_data($obj)
{
    $html = '';

    if($obj->num_rows() <= 0)
    {
        $html .= '                                    <tr><td colspan="4">Não a linhas para serem mostradas</td><tr>'.PHP_EOL;
    }
    else
    {
        $i = 0;
        foreach ($obj->result() as $field) {
            $html .= mt_empanada_recheio_row($field);
        }
    }

    return $html;
}


function mt_empanada_recheio_row($field)
{
    $html = '                                                  <tr>'.PHP_EOL;
    $html .= '                                                      <td style="width:460px;">'.PHP_EOL;
    $html .= '                                                          <a class="active" href="javascript:delete_subitem('.$field->e_items_id.');">'.PHP_EOL;
    $html .= '                                                              <i class="fa fa-trash-o text-danger text-active"></i>'.PHP_EOL;
    $html .= '                                                          </a> | '.PHP_EOL;
    $html .= '                                                          '.$field->db_recheio_id.' | '.$field->e_items_recheio_nome.PHP_EOL;
    $html .= '                                                      </td>'.PHP_EOL;
    $html .= '                                                      <td>'.$field->e_items_qtde.'</td>'.PHP_EOL;
    $html .= '                                                      <td>'.$field->e_items_fcr.'</td>'.PHP_EOL;
    $html .= '                                                      <td>'.number_format($field->e_items_recheio_CustoFinal, 4).'</td>'.PHP_EOL;
    $html .= '                                                      <td>'.$field->e_items_recheio_RendiFinal.'</td>'.PHP_EOL;    
    $html .= '                                                  </tr>'.PHP_EOL;
    return $html;
}

