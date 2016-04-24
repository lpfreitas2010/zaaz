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
		$control = new indexController();
	}

	//CLASS
	class indexController {

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
			$this->config_controller = new config_controller();     //Instancio Funções

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
		function view($parametro = null){

			//CORE CONTROLLER
			$this->core->includeControllerView('core',$this->dir_app);
			$controller_geral = new coreController(); //Extendo dados do controller geral

			//INSTANCIO
			$this->core->includeView();
			$view = new view($this->dir_app);

			//URL AMIGAVEL
			$parte1 = str_replace("?", "", $this->core->get_config('par_url_htacess'));
			$parte2 = str_replace($parte1, "", $parte1 . "/" . $_SERVER['QUERY_STRING']);
			$url = explode("/", $parte2);
			array_shift($url);

			//=================================================================
			//=================================================================

			//============================================================================================
			//INFORMAÇÕES DA PÁGINA
			//============================================================================================

			//PÁGINA ATIVA
			$view->seto_dados('pagina_ativa',$parametro);
			$view->seto_dados("modulos", $this->config_apps->get_config('modulos',0));

			//INFORMAÇÕES GERAIS
			$view->seto_dados("title_pagina", ""); //title da página

			//CARREGA CSS DAS PAGINAS
			$css = array(
				$this->config_apps->get_config('animate'),
			);
			$view->seto_dados("css", $css);

			//VARIAVEIS
			$view->seto_dados("teste", "Testando.... ");

			//============================================================================================
			//INCLUDES PADRÃO
			//============================================================================================

			//INCLUDE GERAL
			require $this->core->includeControllerInclude("core", $this->dir_app);

			//MONTO A VIEW
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
