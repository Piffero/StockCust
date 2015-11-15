<?php
function manager_table($obj)
{   
        
    $html = '                        <div class="table-responsive">'.PHP_EOL;    
    $html .= manager_data_table($obj);
    $html .= '                        </div>'.PHP_EOL;

    return $html;
}


function manager_data_table($obj)
{
    $CI = & get_instance();
    $html = '                             <table class="table table-striped b-t b-light">'.PHP_EOL;
    $html .= '                                <thead>'.PHP_EOL;
    $html .= '                                    <tr>'.PHP_EOL;
    $html .= '                                        <th style="width:20px;"><input type="checkbox"></input></th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[0]).'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[1]).'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[2]).'</th>'.PHP_EOL;
    $html .= '                                        <th style="width:30px;"></th>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= '                                </thead>'.PHP_EOL;
    $html .= '                                <tbody>'.PHP_EOL;
    $html .= '                                    <tr id="rowMethodData" style="display: none;">'.PHP_EOL;
    $html .= '                                        <td style="width:20px;"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iCodigo" class="form-control" type="text" readonly="readonly"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iNome" class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iBC" class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td style="width:30px;"></td>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= manager_table_rows($obj);
    $html .= '                                </tbody>'.PHP_EOL;
    $html .= '                            </table>'.PHP_EOL;
    
    return $html;
}

function manager_table_rows($obj)
{
    $html = '';
    
    if($obj->result->num_rows() <= 0)
    {
        $html .= '                                    <tr><td colspan="5">Não a linhas para serem mostradas</td><tr>'.PHP_EOL;
    }
    else 
    {
        $i = 0;
        foreach ($obj->result->result() as $field) {
            $html .= manager_data_row($field, $obj->fields, $obj->showFields);
        }
    }
    
    return $html;
}


function manager_data_row($field, $fields, $show)
{
    $html  = '';
    $html .= '                                    <tr>'.PHP_EOL;
    $html .= '                                        <td><input type="checkbox" name="post[]" id="'.$field->$fields[0].'"></input></td>'.PHP_EOL;
    for($i=0; $i < $show; $i++)
    {
        $html .= '                                        <td>'.$field->$fields[$i].'</td>'.PHP_EOL;
    }    
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
    
    return $html;    
}
