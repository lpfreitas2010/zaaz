<?php

	/**
	* Controller
	*
	* @author Fernando
	* @version 2.0.0
	**/

	//=================================================================
	//INCLUDES
	require_once (dirname(dirname((dirname(__FILE__)))))."/libs/core/core.php";
	//=================================================================

	//INICIO A CLASSE
	if (!empty($_GET['cmd']) || !empty($_POST['cmd'])) {
		$control = new erroController();
	}

	//CLASS
	class erroController {

		//VARIAVEIS
		private $core;
		private $funcoes;
		private $model;
		private $logs;
		private $email;
		private $dir_app;
		private $config_apps;
		private $config_controller;

		//=================================================================
		//FUNÇÃO PRINCIPAL
		function __construct() {

			//PARAMETROS
			$this->dir_app = "web"; //Pasta da aplicação

			//INCLUDES
			$this->core = new core(); //Instancio CORE
			$this->core->includeViewConfig($this->dir_app); //incluo configurações da aplicação
			$this->core->includeController(null,$this->dir_app); //incluo model

			//INSTANCIO
			$this->funcoes           = new funcoes();     //Instancio Funções
			//$this->model             = new indexModel();  //Instancio Model
			$this->logs              = new logs();        //Instancio Logs
			$this->email             = new email();       //Instancio E-mails
			$this->config_apps       = new config_apps(); //Instancio configurações da aplicação
			$this->config_controller = new config_controller(); //Instancio Funções

			//FUNÇÕES PERMITIDAS
			$funcoes_permitidas = array();//$this->config_apps->getCmds_controller('core'); //Funções permitidas nesta página

			//------------------------------------------------------------
			//PEGO E TRATO O PARAMETRO RECEBIDO
			if (!empty($_GET['cmd']) || !empty($_POST['cmd'])) {

				//TRATO O PARAMETRO RECEBIDO
				if(!empty($_GET['cmd'])){
					$cmd = $this->funcoes->anti_injection($_GET['cmd']);
				}
				if(!empty($_POST['cmd'])){
					$cmd = $this->funcoes->anti_injection($_POST['cmd']);
				}

				//DESCRIPTOGRAFO
				$cmd = $this->funcoes->decrypt($cmd);
				for ($i=0; $i < count($funcoes_permitidas) ; $i++) {
					$funcoes_permitidas_[] = $this->funcoes->decrypt($funcoes_permitidas[$i]);
				}

				//VERIFICO SE FUNÇÃO ESTA NO ARRAY DE FUNÇÕES PERMITIDAS
				if (!in_array($cmd, $funcoes_permitidas_)) {
					//GRAVO LOG
					$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
					->setMensagem($this->core->get_msg_array('acess_block_function', $_SERVER ['REQUEST_URI']))->gravo_log();
					header('location: ../../' . $this->config_apps->get_config('url_erro3') . ''); //Redireciono
					exit();
				}

				//CHAMO A FUNÇÃO
				$this->$cmd();
			}
		}

		//====================================================================================================================================
		//====================================================================================================================================
		//CARREGO INTERFACE DA PÁGINA
		function view($parametro){

			//===========================================================
			//CONFIGURAÇÕES GERAIS
			//$status_auth            = false; // Carrego código de autenticação [ True or false ]
			//$parm_auth_status       = false; // Parametro permitido ou não [ True or false ]
			//$indice_pagina_red_auth = 0; // Indice do array da página que sera redirecionado
			//$status_tempo_sessao    = false; // Carrego código de tempo de sessão [ True or false ]
			//$status_cookies_page    = false; // Carrego código que grava o cookie da página acessada [ True or false ]
			//$carrego_parametros     = false; // Carrego parametros [ True or false ]

			//===========================================================
			//PÁGINA ATIVA E MÓDULO ATIVO
			$interface['pagina_ativa'] = 'main';
			$interface['modulos']      = $this->config_apps->get_config('modulos',0);

			//===========================================================
			//CARREGA CSS DAS PAGINAS
			$interface['css'] = array(
				$this->config_apps->get_config('animate'),
			);

			//===========================================================
			//INCLUDE DE VIEW, CARREGO AS CONFIGURAÇÕES GERAIS
			require $this->core->includeControllerInclude("view_1", $this->dir_app);

			//===========================================================
			//RECEBO PARAMETROS ( GET )
			if ($this->core->get_config('status_url_amigavel') == true) { //Url amigavel
				if (!empty($url[1])) {
					if ($url[1] == "pagina-nao-encontrada") {
						$view->seto_dados("mensagem_erro", $this->core->get_msg_array('msg_pagina_n_encontrada')); //ERRO PÁGINA NÃO ENCONTRADA
						$view->seto_dados("title_pagina", " ".$this->core->get_msg_array('msg_pagina_n_encontrada')." / "); //title da página

						//GRAVO LOG
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem($this->core->get_msg_array('acesso_pagina_log',$parametro.' '.$this->core->get_msg_array('msg_pagina_n_encontrada')))->gravo_log();
					}
					if ($url[1] == "javascript-desabilitado") {
						$view->seto_dados("mensagem_erro", $this->core->get_msg_array('msg_javascript_desativado')); //ERRO JAVASCRIPT DESATIVADO
						$view->seto_dados("title_pagina", " ".$this->core->get_msg_array('msg_javascript_desativado')." / "); //title da página

						//GRAVO LOG
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem($this->core->get_msg_array('acesso_pagina_log',$parametro.' '.$this->core->get_msg_array('msg_javascript_desativado')))->gravo_log();
					}
					if ($url[1] == "acesso-negado") {
						$view->seto_dados("mensagem_erro", $this->core->get_msg_array('msg_acesso_negado')); //ERRO ACESSO NEGADO
						$view->seto_dados("title_pagina", " ".$this->core->get_msg_array('msg_acesso_negado')." / "); //title da página

						//GRAVO LOG
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem($this->core->get_msg_array('acesso_pagina_log',$parametro.' '.$this->core->get_msg_array('msg_acesso_negado')))->gravo_log();
					}
				} else {
					header('location: ' . $this->core->get_config('dir_raiz_http') . $this->config_apps->get_config('url_index')); //redireciono p/ index
				}
			}


			//============================================================================================
			//MONTO A VIEW
			$view->seto_dados_array($interface);
			$view->monto_view('modulos/'.$this->config_apps->get_config('modulos',0).'/'.$parametro . ".phtml");

		}
		//====================================================================================================================================
		//====================================================================================================================================

		//=================================================================
		//AUTENTICO USUARIO NO SISTEMA
		function autentico_usuario(){
			$this->funcoes->auth_usuario($this->dir_app,true,$this->config_apps->get_config('link_page').$this->config_apps->getPaginas(0)); //Se não tiver logado redireciono para index
			exit();
		}



	}
