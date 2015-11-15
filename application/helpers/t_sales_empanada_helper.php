<?php
function sales_table_empanada($obj)
{
    $html  = '                    <div class="panel panel-default">'.PHP_EOL;
    $html .= '                        <div class="panel-heading">'.PHP_EOL;
    $html .= '                           Planilha Responsive'.PHP_EOL;
    $html .= '                        </div>'.PHP_EOL;
    
    
    
    $html .= '                             <table class="table table-striped b-t b-light">'.PHP_EOL;
    $html .= '                                <thead>'.PHP_EOL;
    $html .= '                                    <tr>'.PHP_EOL;        
    $html .= '                                        <th>Margem</th>'.PHP_EOL;
    $html .= '                                        <th>Venda Unitário</th>'.PHP_EOL;
    $html .= '                                        <th>Venda Pacote</th>'.PHP_EOL;
    $html .= '                                        <th>Venda Caixa</th>'.PHP_EOL;
    $html .= '                                    </tr>'.PHP_EOL;
    $html .= '                                </thead>'.PHP_EOL;
    $html .= '                                <tbody>'.PHP_EOL;
    $html .= '                                    <tr id="rowMethodData">'.PHP_EOL;        
    $html .= '                                        <td><input id="iMargem" class="form-control" type="text" value="'.$obj->empanada_margem.'"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iCUnita" class="form-control" type="text" value="'.number_format($obj->empanada_cu,2).'"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iCPacot" class="form-control" type="text" value="'.number_format($obj->empanada_cp,2).'"></td>'.PHP_EOL;
    $html .= '                                        <td><input id="iCCaixa" class="form-control" type="text" value="'.number_format($obj->empanada_cc,2).'"></td>'.PHP_EOL;    
    $html .= '                                    </tr>'.PHP_EOL;  
    $html .= '                                </tbody>'.PHP_EOL;
    $html .= '                            </table>'.PHP_EOL;
    
    
    
    $html .= '                        <footer class="panel-footer">'.PHP_EOL;
    $html .= '                            <div class="row">'.PHP_EOL;
    
    $html .= '                                  <div class="col-lg-6 col-sm-6 col-xs-6">'.PHP_EOL;
    $html .= '                                  </div>'.PHP_EOL;
    
    $html .= '                                  <div class="col-lg-6 col-sm-6 col-xs-6">'.PHP_EOL;
    $html .= '                                      <a class="btn m-b-xs btn-sm btn-primary btn-addon" href="javascript:popup_sales_proced();"><i class="fa fa-superscript"></i> Calcular</a>'.PHP_EOL;
    $html .= '                                      <a class="btn m-b-xs btn-sm btn-success btn-addon" href="javascript:popup_sales_submit('.$obj->empanada_id.');"><i class="fa fa-check"></i> Aplicar</a>'.PHP_EOL;    
    $html .= '                                      <a class="btn m-b-xs btn-sm btn-default btn-addon" href="javascript:popup_sales_reboot();"><i class="fa fa-rotate-left"></i> Restaurar</a>'.PHP_EOL;
    $html .= '                                  </div>'.PHP_EOL;
    
    $html .= '                           </div>'.PHP_EOL;
    $html .= '                        </footer>'.PHP_EOL;
    $html .= '                    </div>'.PHP_EOL;
    
    return $html;
}