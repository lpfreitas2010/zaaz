
<?php
// Função para enviar parâmetros por HTTP/POST utilizando um list

// nome da conta , codigo , layout escolhido(nossa caso: c)
function sendMultiple($account,$code,$type,$msg_list){

        // Prepara os dados para HTTP POST
        $postdata  = "dispatch=sendMultiple&";
        $postdata .= "account=".$account."&";
        $postdata .= "code=".$code."&";
        $postdata .= "type=".$type."&";
        $postdata .= "list=".$msg_list;

        $host = "system.human.com.br";
        $uri = "/GatewayIntegration/msgSms.do";
        $da = fsockopen($host, 80, $errno, $errstr);

        if (!$da && $errno != 0) {
           echo "$errstr ($errno)<br/>\n";
           echo $da;
        } else {
                $response = "";
                $output ="POST $uri  HTTP/1.0\r\n";
                $output.="Host: $host\r\n";
                $output.="User-Agent: PHP Script\r\n";
                $output.="Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1\r\n";
                $output.="Content-Length: ".strlen($postdata)."\r\n";
                $output.="Connection: close\r\n\r\n";
                $output.=$postdata;
                fwrite($da, $output);
                while (!feof($da)) $response.=fgets($da, 128);
                $response=split("\r\n\r\n",$response);
                $header=$response[0];
                $responsecontent=$response[1];
                if(!(strpos($header,"Transfer-Encoding: chunked")===false)){
                        $aux=split("\r\n",$responsecontent);
                        for($i=0;$i<count($aux);$i++)
                        if($i==0 || ($i%2==0))
                           $aux[$i]="";
                        $responsecontent=implode("",$aux);
                } //if
                return chop($responsecontent);
        } //else
}

// Exemplo para testar a lista no lay-out C
$msg_list  = "550092167288;teste0;000"."\n";
$msg_list .= "550081262695;teste1;001"."\n";
$msg_list .= "550081337773;teste2;002"."\n";
$msg_list .= "550096025425;teste3;003"."\n";
//*/

// Faz a chamada usando lay-out C
$response = sendMultiple("account", "code", "C", $msg_list);

echo $response;

?>
