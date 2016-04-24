<?php

	/**
  	* Gravo Logs em TXT ou Gravo no Banco de Dados
  	*
  	* @author Fernando
  	* @version 2.0.0
  	**/

    //=================================================================
	//INCLUO ESTRUTURA
	require_once (dirname(dirname((dirname(__FILE__))))).'/libs/core/core.php';
	//=================================================================

	//Classe
	class logs{

		//Variavel de Controle
		private $funcoes;
		private $core;
		private $mensagem;
		private $app;
		private $url;
		private $pagina;
		private $telefone;
		private $email;
		private $email_re;
		private $status;
		private $id_sms;

		function __construct() {
			$this->core    = new core();
			$this->funcoes = new funcoes();
		}


		//NOME DO USUÁRIO LOGADO NO SISTEMA
		public function getNome_usuario($param){
			return $_SESSION[$param.'_nome_user'];
		}

		//ID DO USUÁRIO LOGADO NO SISTEMA
		public function getUsuario_id($param){
			$usuario_id = $_SESSION[$param.'_id_user'];
			if(empty($usuario_id)){ //Se a sessão estiver vazia eu atribuo o valor padrão 1
					$usuario_id = 1;
			}
			return $usuario_id;
		}

		//CAMPO DO BANCO (usuario_id)
		public function getCampo_usuario_id($param){
			if($param == 'web'){ //pasta web
					return 'usuario_id';
			}else{ //outras pastas apps
					return $param.'_usuario_id';
			}
		}

		//TABELA DO BANCO (usuario_logs)
		public function getTabela_usuario_logs($param){
			if($param == 'web'){ //pasta web
					return 'usuario_logs';
			}else{ //outras pastas apps
					return $param.'_usuario_logs';
			}
		}

		//TABELA DO BANCO (usuario_logs_acesso)
		public function getTabela_usuario_logs_acesso($param){
			if($param == 'web'){ //pasta web
					return 'usuario_logs_acesso';
			}else{ //outras pastas apps
					return $param.'_usuario_logs_acesso';
			}
		}

		//TABELA DO BANCO (sms)
		public function getTabela_usuario_sms($param){
			if($param == 'web'){ //pasta web
				return 'adm_sms_enviados';
			}else{ //outras pastas apps
				return $param.'_sms_enviados';
			}
		}

		//TABELA DO BANCO (email)
		public function getTabela_usuario_email($param){
			if($param == 'web'){ //pasta web
				return 'adm_email_enviados';
			}else{ //outras pastas apps
				return $param.'_email_enviados';
			}
		}

		//DATA ATUAL
		public function getData(){
			return date("d-m-Y");
		}

		//HORA ATUAL
		public function getHora(){
			return date("H:i:s");
		}

		//IP
		public function getIp(){
			return $_SERVER['REMOTE_ADDR'];
		}

		//SISTEMA OPERACIONAL
		public function getSO(){
			return $this->funcoes->identifico_so();
		}

		//NAVEGADOR
		public function getNavegador(){
			return $this->funcoes->identifico_navegador();
		}

		//IDIOMA
		public function getIdioma(){
			return substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
		}

		//MENSAGEM
		public function getMensagem(){
			return $this->mensagem;
		}
		public function setMensagem($mensagem){
			$this->mensagem = $mensagem;
			return $this;
		}

		//PASTA DA APLICAÇÃO
		public function getApp(){
			return $this->app;
		}
		public function setApp($app){
			$this->app = $app;
			return $this;
		}

		//URL
		public function getUrl(){
			return $this->url;
		}
		public function setUrl($url){
			$this->url = $url;
			return $this;
		}

		//PÁGINA
		public function getPagina(){
			return $this->pagina;
		}
		public function setPagina($pagina){
			$this->pagina = $pagina;
			return $this;
		}

		//NOME ARQUIVO
		public function getNome_arquivo(){
			return $this->nome_arquivo;
		}
		public function setNome_arquivo($nome_arquivo){
			$this->nome_arquivo = $nome_arquivo;
			return $this;
		}

		//TELEFONE
		public function getTelefone(){
			return $this->telefone;
		}
		public function setTelefone($telefone){
			$this->telefone = $telefone;
			return $this;
		}

		//EMAIL
		public function getEmail(){
			return $this->email;
		}
		public function setEmail($email){
			$this->email = $email;
			return $this;
		}

		//EMAIL REMETENTE
		public function getEmail_re(){
			return $this->email_re;
		}
		public function setEmail_re($email_re){
			$this->email_re = $email_re;
			return $this;
		}

		//STATUS
		public function getStatus(){
			return $this->status;
		}
		public function setStatus($status){
			$this->status = $status;
			return $this;
		}

		//ID SMS
		public function getId_sms(){
			return $this->id_sms;
		}
		public function setId_sms($id_sms){
			$this->id_sms = $id_sms;
			return $this;
		}


 		//=================================================================
 		//GRAVO LOG EM TXT
 		function gravo_log_txt(){

			//TIRO TAGS HTML
			$mensagem = $this->getMensagem();
			$mensagem = $this->funcoes->substituo_strings('<br />','',$mensagem);
			$mensagem = $this->funcoes->substituo_strings('<br>','',$mensagem);
			$mensagem = $this->funcoes->substituo_strings('<b>','',$mensagem);
			$mensagem = $this->funcoes->substituo_strings('</b>','',$mensagem);
			$mensagem = $this->funcoes->substituo_strings('<strong>','',$mensagem);
			$mensagem = $this->funcoes->substituo_strings('</strong>','',$mensagem);

			//MONTO O NOME DO ARQUIVO
			$nome_arquivo = $this->getNome_arquivo();
			if(empty($nome_arquivo)){
				$arquivo = "{$this->core->get_config('dir_logs_comp')}/{$this->getApp()}log_{$this->getData()}.txt";
			}else{
				$arquivo = "{$this->core->get_config('dir_logs_comp')}/{$this->getApp()}{$nome_arquivo}_{$this->getData()}.txt"; //Nome Personalizado
			}

			//MONTO STRING
			$texto = "USUÁRIO ID: {$this->getUsuario_id($this->getApp())}  |  DATA: {$this->getData()} às {$this->getHora()}  |  IP: {$this->getIp()}  |  NAVEGADOR: {$this->getNavegador()}  |  SO: {$this->getSO()} \r\nMENSAGEM: {$mensagem} \r\n \r\n";

			//CRIO O ARQUIVO
			$manipular = fopen("$arquivo", "a+b");
			fwrite($manipular, $texto);
			fclose($manipular);
 		}

		//=================================================================
		//GRAVO LOGS
		function gravo_log(){
			$this->core->includeModel();
			$conexao = new conexao();
			$conexao->setTable($this->getTabela_usuario_logs($this->getApp()));
			$conexao->setColuna($this->getCampo_usuario_id($this->getApp()))->setColuna('SO')->setColuna('navegador')->setColuna('IP')->setColuna('acao')->setColuna('app')->setColuna('url')->setColuna('pagina')->setColuna('idioma');
			$conexao->setValue($this->getUsuario_id($this->getApp()))->setValue($this->getSO())->setValue($this->getNavegador())->setValue($this->getIp())->setValue($this->getMensagem())->setValue($this->getApp())->setValue($this->getUrl())->setValue($this->getPagina())->setValue($this->getIdioma());
			$conexao->Create();
			$conexao->limpo_campos();
		}

		//=================================================================
		//GRAVO LOGS DE ACESSO
		function gravo_log_acesso(){
			$this->core->includeModel();
			$conexao = new conexao();
			$conexao->setTable($this->getTabela_usuario_logs_acesso($this->getApp()));
			$conexao->setColuna($this->getCampo_usuario_id($this->getApp()))->setColuna('hora')->setColuna('acao')->setColuna('IP')->setColuna('SO')->setColuna('navegador')->setColuna('idioma');
			$conexao->setValue($this->getUsuario_id($this->getApp()))->setValue(date("Y-m-d H:i:s"))->setValue($this->getMensagem())->setValue($this->getIp())->setValue($this->getSO())->setValue($this->getNavegador())->setValue($this->getIdioma());
			$conexao->Create();
			$conexao->limpo_campos();
		}

		//=================================================================
		//GRAVO LOGS DE SMS ENVIADO
		function gravo_log_sms_enviado(){
			$this->core->includeModel();
			$conexao = new conexao();
			$conexao->setTable($this->getTabela_usuario_sms($this->getApp()));
			$conexao->setColuna($this->getCampo_usuario_id($this->getApp()))->setColuna('id_sms')->setColuna('telefone')->setColuna('mensagem')->setColuna('status')->setColuna('SO')->setColuna('IP')->setColuna('navegador')->setColuna('idioma');
			$conexao->setValue($this->getUsuario_id($this->getApp()))->setValue($this->getId_sms())->setValue($this->getTelefone())->setValue($this->getMensagem())->setValue($this->getStatus())->setValue($this->getSO())->setValue($this->getIp())->setValue($this->getNavegador())->setValue($this->getIdioma());
			$conexao->Create();
			$conexao->limpo_campos();
		}

		//=================================================================
		//GRAVO LOGS DE EMAIL ENVIADO
		function gravo_log_email_enviado(){
			$this->core->includeModel();
			$conexao = new conexao();
			$conexao->setTable($this->getTabela_usuario_email($this->getApp()));
			$conexao->setColuna($this->getCampo_usuario_id($this->getApp()))->setColuna('email_re')->setColuna('email')->setColuna('mensagem')->setColuna('status')->setColuna('SO')->setColuna('IP')->setColuna('navegador')->setColuna('idioma');
			$conexao->setValue($this->getUsuario_id($this->getApp()))->setValue($this->getEmail_re())->setValue($this->getEmail())->setValue($this->getMensagem())->setValue($this->getStatus())->setValue($this->getSO())->setValue($this->getIp())->setValue($this->getNavegador())->setValue($this->getIdioma());
			$conexao->Create();
			$conexao->limpo_campos();
		}



}
