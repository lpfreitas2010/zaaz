<?php

    /**
    * Classe para o disparo de e-mails autenticados por PHPMAILER
    *
    * @author Fernando
    * @version 2.0.0
    **/

    //=================================================================
    //INCLUDE
    require_once (dirname(dirname((dirname(__FILE__))))).'/libs/core/core.php';
    //=================================================================

    //Classe
    class email {

        private $conteudo;
        private $assunto;
        private $arquivo;
        private $email_from;
        private $email_send;
        private $host_smtp;
        private $username_smtp;
        private $senha_smtp;
        private $porta_smtp;
        private $debug_smtp;
        private $email_resposta;
        private $nome_remetente;
        private $nome_resposta;
        private $tls;
        private $core;
        private $conexoes;

        public function getConexoes()
        {
            return $this->conexoes;
        }
        public function setConexoes($conexoes)
        {
            $this->conexoes = $conexoes;
            return $this;
        }

        public function getConteudo()
        {
            return $this->conteudo;
        }
        public function setConteudo($conteudo)
        {
            $this->conteudo = $conteudo;
            return $this;
        }

        public function getAssunto()
        {
            return $this->assunto;
        }
        public function setAssunto($assunto)
        {
            $this->assunto = $assunto;
            return $this;
        }

        public function getArquivo()
        {
            return $this->arquivo;
        }
        public function setArquivo($arquivo)
        {
            $this->arquivo = $arquivo;
            return $this;
        }

        public function getEmail_from()
        {
            return $this->email_from;
        }
        public function setEmail_from($email_from)
        {
            $this->email_from = $email_from;
            return $this;
        }

        public function getEmail_send()
        {
            return $this->email_send;
        }
        public function setEmail_send($email_send)
        {
            $this->email_send = $email_send;
            return $this;
        }

        public function getHost_smtp()
        {
            return $this->host_smtp;
        }
        public function setHost_smtp($host_smtp)
        {
            $this->host_smtp = $host_smtp;
            return $this;
        }

        public function getUsername_smtp()
        {
            return $this->username_smtp;
        }
        public function setUsername_smtp($username_smtp)
        {
            $this->username_smtp = $username_smtp;
            return $this;
        }

        public function getSenha_smtp()
        {
            return $this->senha_smtp;
        }
        public function setSenha_smtp($senha_smtp)
        {
            $this->senha_smtp = $senha_smtp;
            return $this;
        }

        public function getPorta_smtp()
        {
            return $this->porta_smtp;
        }
        public function setPorta_smtp($porta_smtp)
        {
            $this->porta_smtp = $porta_smtp;
            return $this;
        }

        public function getDebug_smtp()
        {
            return $this->debug_smtp;
        }
        public function setDebug_smtp($debug_smtp)
        {
            $this->debug_smtp = $debug_smtp;
            return $this;
        }

        public function getEmail_resposta()
        {
            return $this->email_resposta;
        }
        public function setEmail_resposta($email_resposta)
        {
            $this->email_resposta = $email_resposta;
            return $this;
        }

        public function getNome_remetente()
        {
            return $this->nome_remetente;
        }
        public function setNome_remetente($nome_remetente)
        {
            $this->nome_remetente = $nome_remetente;
            return $this;
        }

        public function getNome_resposta()
        {
            return $this->nome_resposta;
        }
        public function setNome_resposta($nome_resposta)
        {
            $this->nome_resposta = $nome_resposta;
            return $this;
        }

        public function getTls_smtp()
        {
            return $this->tls_smtp;
        }
        public function setTls_smtp($tls_smtp)
        {
            $this->tls_smtp = $tls_smtp;
            return $this;
        }

        //Construct
        function __construct() {
            $this->core = new core(); //Instancio core do sistema
        }


        //=================================================================
        //ENVIO EMAIL PELO PLUGIN PHP MAILER
        function envio_email_phpmailer(){

            //INCLUO PLUGIN PHPMAILER
            $this->core->includeInc('phpmailer/PHPMailerAutoload.php');
            $mail = new PHPMailer;

            //VERIFICO SE OS DADOS DE CONFIGURAÇÃO ESTÃO SETADOS
            $smtps = $this->getConexoes();
            if(empty($smtps)){ //CONEXÃO DEFAULT
                $this->setHost_smtp($this->core->get_config('smtp1','Host_smtp'));
                $this->setUsername_smtp($this->core->get_config('smtp1','Username_smtp'));
                $this->setSenha_smtp($this->core->get_config('smtp1','Senha_smtp'));
                $this->setPorta_smtp($this->core->get_config('smtp1','Porta_smtp'));
                $this->setTls_smtp($this->core->get_config('smtp1','Tls_smtp'));
                $this->setDebug_smtp($this->core->get_config('smtp1','Debug_smtp'));
            }

            //CONFIGURAÇÕES DO ENVIO
            $mail->isSMTP();
            $mail->Host     = ''.$this->getHost_smtp().'';
            $mail->SMTPAuth = true;
            $mail->Username = ''.$this->getUsername_smtp().'';
            $mail->Password = ''.$this->getSenha_smtp().'';
            $mail->Port     = $this->getPorta_smtp();
            $debug          = $this->getDebug_smtp();
            $tls            = $this->getTls_smtp();
            if($debug == true){ //debug
                $mail->SMTPDebug  = 2;
            }
            if($tls == true){ //conexão autenticada
                $mail->SMTPSecure = 'tls';
            }

            //EMAIL DE RESPOSTA
            $mail->addReplyTo(''.$this->getEmail_resposta().'', ''.$this->getNome_resposta().''); //E-mail e Nome de Resposta

            //REMETENTE
            $mail->setFrom(''.$this->getEmail_from().'', ''.utf8_decode($this->getNome_remetente()).'');

            //DESTINATÁRIOS
            $email_destinatario = $this->getEmail_send();
            for ($i=0; $i < count($email_destinatario) ; $i++) {
                 $mail->addAddress(''.$email_destinatario[$i].'');
            }

            //CONTEÚDO E ASSUNTO
            $mail->Subject = utf8_decode($this->getAssunto()); //Assunto
            $mail->Body    = utf8_decode($this->getConteudo()); //Conteúdo
            $mail->isHTML(true); //Campo Html

            //ADICIONO ARQUIVO EM ANEXO
            $arquivo = $this->getArquivo();
            if(!empty($arquivo)){
                 $arquivo = $this->getArquivo();
                 if(is_array($arquivo['tmp_name'])){
                     for ($i=0; $i <count($arquivo['tmp_name']) ; $i++) {
                         $mail->AddAttachment($arquivo['tmp_name'][$i], $arquivo['name'][$i]); //Adiciono Anexo
                     }
                 }else{
                     $mail->AddAttachment($arquivo['tmp_name'], $arquivo['name']); //Adiciono Anexo
                 }
            }

            //LIMPO CAMPOS
            $this->limpo_campos();

            //ENVIO O E-MAIL
            if(!$mail->send()) {
                return false;//'Error: ' . $mail->ErrorInfo.'';
            } else {
                return true;
            }

        }

        //=================================================================
        //MONTO RODAPÉS DO CONTEUDO DO EMAIL
        function rodape_conteudo_email(){
            $smtps = $this->getConexoes();
            if(empty($smtps)){ //CONEXÃO DEFAULT
                if($this->core->get_config('smtp1','status_msg_rodape_email') == true){
                    return $this->core->get_config('msg_rodape_email');
                }
            }
        }

        //=================================================================
        //LIMPO CAMPOS
        function limpo_campos(){
            $this->setNome_remetente(null);
            $this->setNome_resposta(null);
            $this->setEmail_resposta(null);
            $this->setEmail_send(null);
            $this->setEmail_from(null);
            $this->setArquivo(null);
            $this->setAssunto(null);
            $this->setConteudo(null);
            unset($this->nome_remetente);
            unset($this->nome_resposta);
            unset($this->email_resposta);
            unset($this->email_send);
            unset($this->email_from);
            unset($this->arquivo);
            unset($this->assunto);
            unset($this->conteudo);
        }


    }
