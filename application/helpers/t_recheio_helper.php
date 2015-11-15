<?php
function mt_recheio($obj)
{   
        
    $html = '                        <div class="table-responsive">'.PHP_EOL;    
    $html .= mt_recheio_data($obj);
    $html .= '                        </div>'.PHP_EOL;

    return $html;
}


function mt_recheio_data($obj)
{
    $CI = & get_instance();
    $html = '                             <table class="table table-striped b-t b-light">'.PHP_EOL;
    $html .= '                                <thead>'.PHP_EOL;
    $html .= '                                    <tr>'.PHP_EOL;
    $html .= '                                        <th style="width:20px;"><input type="checkbox"></input></th>'.PHP_EOL;
    $html .= '                                        <th style="width:10px;"></th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[0]).'</th>'.PHP_EOL;
    $html .= '                                        <th style="width:250px;">'.$CI->lang->line($obj->fields[1]).'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[2]).'</th>'.PHP_EOL;
    $html .= '                                        <th>Qtde.</th>'.PHP_EOL;    
    $html .= '                                        <th>R$ C.Unid</th>'.PHP_EOL;
    $html .= '                                        <th>R$ C.Total/Kg</th>'.PHP_EOL;
    $html .= '                                        <th>R$ C.Total</th>'.PHP_EOL;
    $html .= '                                        <th>Rendimento</th>'.PHP_EOL;
    $html .= '                                        <th style="width:30px;"></th>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= '                                </thead>'.PHP_EOL;
    $html .= '                                <tbody>'.PHP_EOL;
    $html .= '                                    <tr id="rowMethodData" style="display: none;">'.PHP_EOL;
    $html .= '                                        <td style="width:20px;"></td>'.PHP_EOL;
    $html .= '                                        <td style="width:10px;"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iCodigo" class="form-control" type="text" readonly="readonly"></td>'.PHP_EOL;
    $html .= '                                        <td style="width:250px;"><input id="iNome"   class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iRegra"  class="form-control" type="text" list="browsers" style="border: solid 1px #6611DD"></td>'.PHP_EOL;
    $html .= '                                        <td><input disabled class="form-control" type="text"></td>'.PHP_EOL;    
    $html .= '                                        <td><input disabled class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input disabled class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input disabled class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input disabled class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td style="width:30px;"></td>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= mt_recheio_rows($obj);        
    $html .= '                                </tbody>'.PHP_EOL;
    $html .= '                            </table>'.PHP_EOL;
    
    
     $html .= '<datalist id="browsers">';    
     foreach ($obj->datalist->result() as $item_list)
     {
            $html .= '<option value="'.$item_list->regras_basecalculo.'">'.$item_list->regras_nome.'</option>';
     }
     $html .= '</datalist>';
    
     $html .= '<datalist id="materia">';
     foreach ($obj->subdatalist->result() as $sub_list)
     {
         $html .= '<option value="'.$sub_list->materia_id.'">'.$sub_list->materia_nome.'</option>';
     }
     $html .= '</datalist>';
     
    return $html;
}

function mt_recheio_rows($obj)
{
    $html = '';
    
    if($obj->result->num_rows() <= 0)
    {
        $html .= '                                    <tr><td colspan="10">Não a linhas para serem mostradas</td><tr>'.PHP_EOL;
    }
    else 
    {
        $i = 0;
        foreach ($obj->result->result() as $field) {
            $html .= mt_recheio_data_row($field, $obj->fields, $obj->showFields);
        }
    }
    
    return $html;
}


function mt_recheio_data_row($field, $fields, $show)
{
    $html  = '';
    $html .= '                                    <tr ondblclick="showMethodEdit('.$field->$fields[0].')">'.PHP_EOL;
    $html .= '                                        <td><input type="checkbox" name="post[]" id="'.$field->$fields[0].'"></input></td>'.PHP_EOL;
    $html .= '                                        <td>'.PHP_EOL;
    $html .= '                                          <a class="" id="open_'.$field->$fields[0].'" href="javascript:active_subitem('.$field->$fields[0].');">'.PHP_EOL;
    $html .= '                                                <i class="fa fa-plus text-success text-active"></i>'.PHP_EOL;
    $html .= '                                          </a>'.PHP_EOL;
    $html .= '                                        </td>'.PHP_EOL;
    for($i=0; $i < $show; $i++)
    {
        $html .= '                                        <td id="recheio_'.$fields[$i].'_'.$field->$fields[0].'">'.$field->$fields[$i].'</td>'.PHP_EOL;
    }     

    $html .= '                                        <td>'.number_format($field->recheio_qtde, 4).'</td>'.PHP_EOL;
    $html .= '                                        <td>'.number_format($field->recheio_cunit, 4).'</td>'.PHP_EOL;
    $html .= '                                        <td>'.number_format($field->recheio_ctotalKg, 4).'</td>'.PHP_EOL;
    $html .= '                                        <td>'.number_format($field->recheio_ctotal, 4).'</td>'.PHP_EOL;
    $html .= '                                        <td>'.$field->recheio_rendto.'</td>'.PHP_EOL;
    
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
    $html .= '                                        <td style="width:10px;"></td>'.PHP_EOL;
    $html .= '                                        <td colspan="9">'.PHP_EOL;
    $html .= mt_materia_recheio($field->materia, $field->$fields[0]);
    $html .= '                                        </td>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    
    return $html;    
}



function mt_materia_recheio($obj, $id)
{
    $html = '<form class="off" name="form_subitem">';
    $html .= '                                           <table class="table table-striped b-t b-light">'.PHP_EOL;
    $html .= '                                              <thead>'.PHP_EOL;
    $html .= '                                                  <tr>'.PHP_EOL;    
    $html .= '                                                      <th style="width:460px;">Máteria Prima / Código</th>'.PHP_EOL;
    $html .= '                                                      <th>Qtde</th>'.PHP_EOL;
    $html .= '                                                      <th>Unidade</th>'.PHP_EOL;
    $html .= '                                                      <th>Custo</th>'.PHP_EOL;
    $html .= '                                                  </tr>'.PHP_EOL;
    $html .= '                                              </thead>'.PHP_EOL;
    $html .= '                                              <tbody>'.PHP_EOL;    
    $html .= '                                                  <tr>'.PHP_EOL;          
    $html .= '                                                      <td style="width:460px;"><input id="input_item_materia_'.$id.'" name="input_item_materia" class="form-control" type="text" list="materia" style="border: solid 1px #6611DD" onkeyup="setFocus(event);" ></td>'.PHP_EOL;
    $html .= '                                                      <td><input id="input_item_qtde_'.$id.'" name="input_item_qtde" class="form-control" type="text" onkeyup="showMethodSaveItem('.$id.', event);"></td>'.PHP_EOL;
    $html .= '                                                      <td><input disabled class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                                      <td><input disabled class="form-control" type="text"></td>'.PHP_EOL;    
    $html .= '                                                  </tr>'.PHP_EOL;
    
    $html .= mt_materia_recheio_data($obj);
    
    $html .= '                                              </tbody>'.PHP_EOL;
    $html .= '                                          </table>'.PHP_EOL;
    $html .= '</form>';
    
    return $html;
}


function mt_materia_recheio_data($obj)
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
            $html .= mt_materia_recheio_row($field);
        }
    }
    
    return $html;
}


function mt_materia_recheio_row($field)
{   
    $html = '                                                  <tr id="row_'.$field->r_items_id.'" ondblclick="showMethodEdit('.$field->r_items_id.');">'.PHP_EOL;
    $html .= '                                                      <td style="width:460px;">'.PHP_EOL;
    $html .= '                                                          <a class="active" href="javascript:delete_subitem('.$field->r_items_id.');">'.PHP_EOL;
    $html .= '                                                              <i class="fa fa-trash-o text-danger text-active"></i>'.PHP_EOL;
    $html .= '                                                          </a> | '.PHP_EOL;
    $html .= '                                                          '.$field->db_materia_id.' | '.$field->r_items_nome.''.PHP_EOL;
    $html .= '                                                      </td>'.PHP_EOL;
    $html .= '                                                      <td id="r_items_qtde_'.$field->r_items_id.'">'.$field->r_items_qtde.'</td>'.PHP_EOL;
    $html .= '                                                      <td>'.$field->r_items_unid.'</td>'.PHP_EOL;
    $html .= '                                                      <td>'.number_format($field->r_items_custo, 4).'</td>'.PHP_EOL;
    $html .= '                                                  </tr>'.PHP_EOL;
    return $html;
}
