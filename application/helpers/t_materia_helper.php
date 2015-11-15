<?php
function mt_materia($obj)
{   
        
    $html = '                        <div class="table-responsive">'.PHP_EOL;    
    $html .= mt_materia_data($obj);
    $html .= '                        </div>'.PHP_EOL;

    return $html;
}


function mt_materia_data($obj)
{
    $CI = & get_instance();
    $html = '                             <table class="table table-striped b-t b-light">'.PHP_EOL;
    $html .= '                                <thead>'.PHP_EOL;
    $html .= '                                    <tr>'.PHP_EOL;
    $html .= '                                        <th style="width:20px;"><input type="checkbox"></input></th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[0]).'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[1]).'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[2]).'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[3]).'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[4]).'</th>'.PHP_EOL;
    $html .= '                                        <th style="width:30px;"></th>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= '                                </thead>'.PHP_EOL;
    $html .= '                                <tbody>'.PHP_EOL;
    $html .= '                                    <tr id="rowMethodData" style="display: none;">'.PHP_EOL;
    $html .= '                                        <td style="width:20px;"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iCodigo" class="form-control" type="text" readonly="readonly"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iNome"   class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iUN"     class="form-control" type="text" list="browsers" style="border: solid 1px #6611DD"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iValue"  class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iFC"     class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td style="width:30px;"></td>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= mt_materia_rows($obj);
    $html .= '                                </tbody>'.PHP_EOL;
    $html .= '                            </table>'.PHP_EOL;
    
    
    $html .= '<datalist id="browsers">';    
    foreach ($obj->datalist->result() as $item_list)
    {
           $html .= '<option value="'.$item_list->unidade_sigla.'">'.$item_list->unidade_nome.'</option>';
    }
    $html .= '</datalist>';
    
    return $html;
}

function mt_materia_rows($obj)
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
            $html .= mt_materia_data_row($field, $obj->fields, $obj->showFields);
        }
    }
    
    return $html;
}


function mt_materia_data_row($field, $fields, $show)
{
    $html  = '';
    $html .= '                                    <tr id="row_'.$field->$fields[0].'" ondblclick="showMethodEdit('.$field->$fields[0].');">'.PHP_EOL;
    $html .= '                                        <td><input type="checkbox" name="post[]" id="'.$field->$fields[0].'"></input></td>'.PHP_EOL;
    for($i=0; $i < $show; $i++)
    {
        $html .= '                                        <td id="col_'.$fields[$i].'_'.$field->$fields[0].'" >'.$field->$fields[$i].'</td>'.PHP_EOL;
    }    
    $html .= '                                        <td>'.PHP_EOL;
    if(($field->$fields[6] == 0)){
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
    
    return $html;    
}
