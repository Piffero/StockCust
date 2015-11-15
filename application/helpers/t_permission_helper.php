<?php
function mt_permission()
{
    $html = '                        <div class="table-responsive">'.PHP_EOL;    
    $html .= mt_permission_data();
    $html .= '                        </div>'.PHP_EOL;

    return $html;
}

function mt_permission_data()
{    
    $html = '                             <table class="table table-striped b-t b-light">'.PHP_EOL;
    $html .= '                                <thead>'.PHP_EOL;
    $html .= '                                    <tr>'.PHP_EOL;
    $html .= '                                        <th style="width:20px;"><i class="fa fa-users"></i></input></th>'.PHP_EOL;
    $html .= '                                        <th style="width:200px;">Permissões do Usuário:</th>'.PHP_EOL;    
    $html .= '                                        <th></th>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= '                                </thead>'.PHP_EOL;
    $html .= '                                <tbody id="body_permission">'.PHP_EOL;
    
    $html .= '                                </tbody>'.PHP_EOL;
    $html .= '                            </table>'.PHP_EOL;
    return $html;
}

function mt_permission_data_row($obj)
{
    
    $html = '';
     if(count($obj) <= 0)
    {
        $html .= '                                    <tr><td colspan="3">Não a linhas para serem mostradas</td><tr>'.PHP_EOL;
    }
    else 
    {       
        foreach ($obj as $field) {
            $html .= mt_permission_row($field);
        }
    }
    
    return $html;
    
}

function mt_permission_row($field)
{
        $html = '                                    <tr>'.PHP_EOL;
    if($field->permissao_checked == 0){
        $html .= '                                        <td style="width:20px;"><input type="checkbox" value="'.$field->modulo_descricao.'"></input></td>'.PHP_EOL;
    }else{
        $html .= '                                        <td style="width:20px;"><input type="checkbox" value="'.$field->modulo_descricao.'" checked></input></td>'.PHP_EOL;
    }
        $html .= '                                        <td style="width:200px;">'.$field->modulo_descricao.'</td>'.PHP_EOL;
        $html .= '                                        <td></td>'.PHP_EOL;
        $html .= '                                    </tr>'.PHP_EOL;
        
    return $html;
}


