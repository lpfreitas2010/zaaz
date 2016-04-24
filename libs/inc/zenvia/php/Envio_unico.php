<? 
 
//echo 'teste do SMS<BR>';
 
 
// Função para Envio de SMS Individual
function sendSms($to,$from,$msg,$id,$schedule) {
                $account = "account";  // ---> preencher o nome da conta
                $code = "senha";   // ---> preencher o código da conta
 
                $msg = URLEncode($msg);
                $from = URLEncode($from);
                $id = URLEncode($id);
                $url = "https://system.human.com.br/GatewayIntegration/msgSms.do?dispatch=send";
                $url.= "&account=".$account;
                $url.= "&code=".$code;
                if ($id) {
                               $url.= "&id=".$id;
                }
                if ($from) {
                               $url.= "&from=".$from;
                }
                if ($schedule) {
                               $schedule_url = date("d/m/Y H:i:s",$schedule);
                               $schedule_url = URLEncode($schedule_url);
                               $url.= "&schedule=".$schedule_url;
                }
                $url.= "&to=".$to;
                $url.= "&msg=".$msg;
                $r = new HTTPRequest($url);
                
                return substr($r->DownloadToString(), 0, 3);
}
 
// Classe para requisição HTTP via socket
class HTTPRequest
{
   var $_fp;        // HTTP socket
   var $_url;        // full URL
   var $_host;        // HTTP host
   var $_protocol;    // protocol (HTTP/HTTPS)
   var $_uri;        // request URI
   var $_port;        // port
   
   // scan url
   function _scan_url()
   {
       $req = $this->_url;
       
       $pos = strpos($req, '://');
       $this->_protocol = strtolower(substr($req, 0, $pos));
       
       $req = substr($req, $pos+3);
       $pos = strpos($req, '/');
       if($pos === false)
           $pos = strlen($req);
       $host = substr($req, 0, $pos);
       
       if(strpos($host, ':') !== false)
       {
           list($this->_host, $this->_port) = explode(':', $host);
       }
       else 
       {
           $this->_host = $host;
           $this->_port = 80;
       }
       
       $this->_uri = substr($req, $pos);
       if($this->_uri == '')
           $this->_uri = '/';
   }
   
   // constructor
   function HTTPRequest($url)
   {
       $this->_url = $url;
       $this->_scan_url();
   }
   
   // download URL to string
   function DownloadToString()
   {
       $crlf = "\r\n";
       
       // generate request
       $req = 'POST ' . $this->_uri . ' HTTP/1.0' . $crlf
           .    'Host: ' . $this->_host . $crlf
           .    $crlf;
       
       // fetch
       $this->_fp = fsockopen($this->_host, $this->_port);
       fwrite($this->_fp, $req);
       while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp))
           $response .= fread($this->_fp, 1024);
       fclose($this->_fp);
       
       // split header and body
       $pos = strpos($response, $crlf . $crlf);
       if($pos === false)
           return($response);
       $header = substr($response, 0, $pos);
       $body = substr($response, $pos + 2 * strlen($crlf));
       
       // parse headers
       $headers = array();
       $lines = explode($crlf, $header);
       foreach($lines as $line)
           if(($pos = strpos($line, ':')) !== false)
               $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));
       
       // redirection?
       if(isset($headers['location']))
       {
           $http = new HTTPRequest($headers['location']);
           return($http->DownloadToString($http));
       }
       else 
       {
           return($body);
       }
   }
}

//$schedule = date();
$corposms = 'Mensagem';
$cellPhone ='555199999999';
$retorno = sendSms($cellPhone,"From",$corposms,"ID",false);
echo 'meu retorno é: '.$retorno.'<BR><BR>';

?>