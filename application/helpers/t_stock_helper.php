<?php
function mt_stock($obj)
{   
        
    $html = '                        <div class="table-responsive">'.PHP_EOL;    
    $html .= mt_stock_data($obj);
    $html .= '                        </div>'.PHP_EOL;

    return $html;
}


function mt_stock_data($obj)
{
    $CI = & get_instance();
    $html = '                             <table class="table table-striped b-t b-light">'.PHP_EOL;
    $html .= '                                <thead>'.PHP_EOL;
    $html .= '                                    <tr>'.PHP_EOL;        
    $html .= '                                        <th style="width:200px;">Código</th>'.PHP_EOL;
    $html .= '                                        <th>Materia Prima</th>'.PHP_EOL;
    $html .= '                                        <th style="width:20px;">Quantidade</th>'.PHP_EOL;
    $html .= '                                        <th style="width:20px;"></th>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= '                                </thead>'.PHP_EOL;
    $html .= '                                <tbody>'.PHP_EOL;
    $html .= '                                    <tr id="rowMethodData">'.PHP_EOL;    
    $html .= '                                        <td style="width:200px;"><input id="iCodigo" class="form-control" type="text" list="browsers" style="border: solid 1px #6611DD"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iNome"   class="form-control" type="text" disabled="disabled"></td>'.PHP_EOL;
    $html .= '                                        <td style="width:20px;"><input id="iQtde"   class="form-control" type="text"></td>'.PHP_EOL;
    $html .= '                                        <th>'.form_button($obj->btn_default).' '.form_button($obj->btn_delete).'</th>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= mt_stock_rows($obj);
    $html .= '                                </tbody>'.PHP_EOL;
    $html .= '                            </table>'.PHP_EOL;
    
    
    $html .= '<datalist id="browsers">';    
    foreach ($obj->datalist->result() as $item_list)
    {
           $html .= '<option value="'.$item_list->materia_id.'">'.$item_list->materia_nome.'</option>';
    }
    $html .= '</datalist>';
    
    return $html;
}

function mt_stock_rows($obj)
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
            $html .= mt_stock_data_row($field);
        }
    }
    
    return $html;
}


function mt_stock_data_row($field)
{
    $html  = '';
    $html .= '                                    <tr id="row_'.$field->estoque_id.'" ondblclick="update_data('.$field->estoque_id.');">'.PHP_EOL;
    $html .= '                                        <td style="width:200px;">'.$field->estoque_codigo.'</td>'.PHP_EOL;
    $html .= '                                        <td>'.$field->estoque_nome.'</td>'.PHP_EOL;
    $html .= '                                        <td id="field_'.$field->estoque_id.'" style="width:20px;">'.$field->estoque_qtde.'</td>'.PHP_EOL;
    $html .= '                                        <td style="width:20px;">'.PHP_EOL;
    $html .= '                                            <a class="active" href="javascript:delete_data('.$field->estoque_id.');">'.PHP_EOL;
    $html .= '                                                <i class="fa fa-trash text-danger text-active"></i>'.PHP_EOL;
    $html .= '                                            </a>'.PHP_EOL;
    $html .= '                                        </td>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    
    return $html;    
}
