<?php
$mobile = "555184220483";
$msg = "Teste de Mensagem";
$msg = URLEncode($msg);
$response = fopen("http://system.human.com.br/GatewayIntegration/msgSms.do?dispatch=send&account=nome_da_conta&code=codigo_da_conta&to=".$mobile."&msg=".$msg,"r");
$status_code = fgets($response,4);
echo "Status code = ".$status_code;
?>