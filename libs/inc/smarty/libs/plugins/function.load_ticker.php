<?php
// configura nossa função para pegar os dados do estoque
function fetch_ticker($symbol)
{
   // coloque a lógica que obtém os dados de
   // algum recurso e guarde na variável $ticker_info
   $ticker_info = 'teste';
   return $ticker_info;
}

function smarty_function_load_ticker($params, &$smarty)
{
   // chama a função
   $ticker_info = fetch_ticker($params['symbol']);

   // atribuite o valor à uma variável no template
   $smarty->assign($params['assign'], $ticker_info);
}
