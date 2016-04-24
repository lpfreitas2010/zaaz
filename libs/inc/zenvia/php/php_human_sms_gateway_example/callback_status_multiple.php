<?php


function checkMultiple($account, $code, $idList) {

    // Configura o POST
    $postdata  = "dispatch=checkMultiple&";
    $postdata .= "account=" . $account."&";
    $postdata .= "code=" . $code."&";
    $postdata .= "idList=" . $idList;

    // Configura a URI
    $host = "system.human.com.br";
    $path = "/GatewayIntegration/msgSms.do";
    $port = 8080;

    $fp = fsockopen($host, $port, $errorNumber, $errorString, 15);

    if (!$fp) {
        echo 'Falha de conexao: ' . $errorString . ' (' . $errorNumber . ')\n';
        return "";
    }

    fputs($fp, "POST $path HTTP/1.1\r\n");
    fputs($fp, "Host: $host\r\n");
    fputs($fp, "Content-type: application/x-www-form-urlencoded; charset=ISO-8859-1\r\n");
    fputs($fp, "Content-length: " . strlen($postdata) . "\r\n");
    fputs($fp, "User-Agent: PHP Script\r\n");
    fputs($fp, "Connection: close\r\n\r\n");
    fputs($fp, $postdata);

    $response = "";
    while (!feof($fp)) {
        $response .= fgets($fp,128);
    }

    fclose($fp);

    // limpa o retorno, separa o header do conteudo
    $response = split("\r\n\r\n", $response);

    $header = $response[0];
    $responsecontent=$response[1];

    // verifica se nao ocorreu um erro
    if(!(strpos($header,"Transfer-Encoding: chunked")===false)) {
        $aux = split("\r\n", $responsecontent);
        for($i=0; $i<count($aux); $i++)
            if($i==0 || ($i%2==0))
                $aux[$i]="";
        $responsecontent = implode("", $aux);
    } //if strpos

    return chop($responsecontent);
}


echo checkMultiple("account", "code", "id1;id2");

?>
