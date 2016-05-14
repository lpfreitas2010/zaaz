<?php

    /**
    * Envio de SMS
    *
    * @author Fernando
    * @version 1.0.0
    **/

    //=================================================================
    //INCLUDE
    require_once (dirname(dirname((dirname(__FILE__))))).'/libs/core/core.php';
    //=================================================================

    //Classe
    class smsHelper{

        //Variaveis
        protected $username;
        protected $senha;
        protected $telefone;
        protected $mensagem;
        protected $return_cod;
        protected $return_msg;

        public function getUsername(){
            return $this->username;
        }
        public function setUsername($username){
            $this->username = $username;
            return $this;
        }

        public function getSenha(){
            return $this->senha;
        }
        public function setSenha($senha){
            $this->senha = $senha;
            return $this;
        }

        public function getTelefone(){
            return $this->telefone;
        }
        public function setTelefone($telefone){
            $this->telefone = $telefone;
            return $this;
        }

        public function getMensagem(){
            return $this->mensagem;
        }
        public function setMensagem($mensagem){
            $this->mensagem = $mensagem;
            return $this;
        }

        public function getReturn_cod(){
            return $this->return_cod;
        }
        public function setReturn_cod($return_cod){
            $this->return_cod = $return_cod;
            return $this;
        }

        public function getReturn_msg(){
            return $this->return_msg;
        }
        public function setReturn_msg($return_msg){
            $this->return_msg = $return_msg;
            return $this;
        }

        public function gero_id(){
            return md5(uniqid(rand(), true));
        }

        public function trato_num_telefone($numero){
            $numero = str_replace(array('(',')','-',' '), array('','','',''), $numero);
            if(strlen($numero) == 10){
                $parte1 = substr($numero, 0, 2);
                $parte2 = substr($numero, -8);
                $numero = $parte1.'9'.$parte2;
            }
            return $numero;
        }

        //ENVIA SMS
        function envia_sms(){
            
            $username = $this->getUsername();
            $senha    =  $this->getSenha();
            if(!empty($username) && !empty($senha)){
                
                //INICIO REQUISIÇÃO
                $core = new core();
                $core->includeInc('zenvia/php/PHP_tutorial/human_gateway_client_api/HumanClientMain.php');
                
                //EXECUTO
                $humanMultipleSend = new HumanMultipleSend($this->getUsername(), $this->getSenha());

                //TRATO MENSAGEM
                $mensagem = $this->getMensagem();

                //RETORNO
                $response = $humanMultipleSend->sendMultipleList(HumanMultipleSend::TYPE_C, utf8_decode($mensagem));
                foreach ($response as $resp) {
                    $code = $resp->getCode();
                    if($code != 200){
                        $array[] = $code;
                    }
                }
                return $array;
            }
    
        }

    }
