<?php

// Função para enviar parâmetros por HTTP/POST utilizando um arquivo .csv local


// nome da conta , codigo , layout escolhido(nossa caso: c) e o arquivo cvs
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
                $output ="POST $uri  HTTP/1.0\r\n";
                $output.="Host: $host\r\n";
                $output.="User-Agent: PHP Script\r\n";
                $output.="Content-Type: multipart/form-data; charset=ISO-8859-1\r\n";
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

// Faz a chamada usando lay-out C

/***************************************************************************************************************/


$arquivo = "arquivo.csv";
$msg_list = file($arquivo);

foreach ($msg_list as $line_num => $line) {
$numbers.=$line;

}

echo $numbers;

$response = sendMultiple("conta", "senha", "C",$numbers);


?>