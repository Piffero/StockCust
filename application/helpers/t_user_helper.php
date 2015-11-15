<?php
function mt_user($obj)
{   
        
    $html = '                        <div class="table-responsive">'.PHP_EOL;    
     $html .= mt_user_data($obj);
    $html .= '                        </div>'.PHP_EOL;

    return $html;
}


function mt_user_data($obj)
{
    $CI = & get_instance();
    $html = '                             <table class="table table-striped b-t b-light">'.PHP_EOL;
    $html .= '                                <thead>'.PHP_EOL;
    $html .= '                                    <tr>'.PHP_EOL;
    $html .= '                                        <th style="width:20px;"><input type="checkbox"></input></th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[1]).'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[2]).'</th>'.PHP_EOL;
    $html .= '                                        <th>'.$CI->lang->line($obj->fields[3]).'</th>'.PHP_EOL;
    $html .= '                                        <th style="width:30px;"></th>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= '                                </thead>'.PHP_EOL;
    $html .= '                                <tbody>'.PHP_EOL;
    $html .= '                                    <tr id="rowMethodData" style="display: none;">'.PHP_EOL;
    $html .= '                                        <td style="width:20px;"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iNome" class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iNomeUsuario" class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iSenha" class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <td style="width:30px;"></td>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= mt_user_rows($obj);
    $html .= '                                </tbody>'.PHP_EOL;
    $html .= '                            </table>'.PHP_EOL;
    
    
    
    
    return $html;
}

function mt_user_rows($obj)
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
            $html .= mt_user_data_row($field, $obj->fields, $obj->showFields);
        }
    }
    
    return $html;
}


function mt_user_data_row($field, $fields, $show)
{
    $html  = '';
    $html .= '                                    <tr>'.PHP_EOL;
    $html .= '                                        <td><input type="checkbox" name="post[]" id="'.$field->$fields[0].'"></input></td>'.PHP_EOL;
    for($i=1; $i < $show; $i++)
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
