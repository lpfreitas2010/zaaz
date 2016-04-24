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
		$control = new homeController();
	}

	//CLASS
	class homeController {

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
			$this->dir_app = "adm"; //Pasta da aplicação

			//INCLUDES
			$this->core = new core(); //Instancio CORE
			$this->core->includeViewConfig($this->dir_app); //incluo configurações da aplicação
	  		$this->core->includeController(null,$this->dir_app); //incluo model

			//INSTANCIO
			$this->funcoes           = new funcoes();     //Instancio Funções
			//$this->model             = new indexModel();  //Instancio Model
			$this->logs              = new logs();        //Instancio Logs
			$this->email             = new email();       //Instancio E-mails
			$this->config_apps       = new config_apps_adm(); //Instancio configurações da aplicação
			$this->config_controller = new config_controller_adm();     //Instancio Funções

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
				if(!in_array($cmd, $funcoes_permitidas_)){
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem($this->core->get_msg_array('acess_block_function', $_SERVER ['REQUEST_URI']))->gravo_log(); // Gravo log
						header('location: ../' . $this->config_apps->get_config('url_erro3') . ''); //Redireciono
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
			$status_auth            = true; // Carrego código de autenticação [ True or false ]
			$parm_auth_status       = true; // Parametro permitido ou não [ True or false ]
			$indice_pagina_red_auth = 0; // Indice do array da página que sera redirecionado
			$status_tempo_sessao    = true; // Carrego código de tempo de sessão [ True or false ]
			$status_cookies_page    = true; // Carrego código que grava o cookie da página acessada [ True or false ]
			$carrego_parametros     = false; // Carrego parametros [ True or false ]

			//===========================================================
			//PÁGINA ATIVA E MÓDULO ATIVO
			$interface['pagina_ativa'] = $parametro;
			$interface['modulos']      = $this->config_apps->get_config('modulos',0);

			//===========================================================
			//INCLUDE DE VIEW, CARREGO AS CONFIGURAÇÕES GERAIS
			require $this->core->includeControllerInclude("view_1", $this->dir_app);
			//============================================================================================

			//===========================================================
			//CARREGA CSS DAS PAGINAS ****-
			$interface['css'] = array(
				$this->config_apps->get_config('bootstrap'),
				$this->config_apps->get_config('icon'),
				$this->config_apps->get_config('bsmSelect-master'),
				$this->config_apps->get_config('select2'),
				$this->config_apps->get_config('chosen'),
				$this->config_apps->get_config('sweetalert'),
				$this->config_apps->get_config('datetimepicker'),
				$this->config_apps->get_config('animate'),
				$this->config_apps->get_config('lightbox'),
				$this->config_apps->get_config('fakeLoader'),
				$this->config_apps->get_config('admin'),
				$this->config_apps->get_config($configs_admin[0]['tema_admin']),
			);

			//===========================================================
        	//INFORMAÇÕES GERAIS
			$interface['title_pagina'] = ""; //title da página

			//===============================================
			//ACTION GRAFICO
			$interface['action_grafico'] = $this->core->get_config('dir_raiz_http').$this->dir_app.'/controller/coreController.php?cmd_core='.$this->config_apps->getCmds_controller('core',28);

			//===============================================

			//SETO INFORMAÇÕES DA PÁGINA
			$interface['saudacao_adm']                   = $this->funcoes->retorno_data_saudacao(); // Retorno saudação
			$interface['saudacao_hora'] 				 = $this->funcoes->retorno_saudacao(); // Retorno Bom dia ...
			$interface['navegador_ativo']                = $this->funcoes->identifico_navegador(); //Retorno navegador
			$interface['ip_ativo']                       = $_SERVER['REMOTE_ADDR']; //Retorno navegador
			$interface['so_ativo']                       = $this->funcoes->identifico_so(); //Retorno navegador
			$interface['total_registros_adm']            = $controller_geral->total_registros_adm(); // array com o total de registros admin
			$interface['listagem_usuarios_online_admin'] = $controller_geral->retorno_listagens_adm('retorno_listagem_usuarios_online_adm'); //array com listagem de usuarios online admin
			$interface['listagem_notificacoes_admin']    = $controller_geral->retorno_listagens_adm('retorno_listagem_notificacoes_adm'); //array com listagem de usuarios online admin
			$interface['status_usuario_online']          = true; //Status da área de usuarios online [true or false]

			//===============================================
			//===============================================
			//PERMISSOES
			$restricoes_sistema = $interface['restricoes_sistema'];
			for ($i=0; $i <count($restricoes_sistema) ; $i++) {

				//MODULO 1
				if ($restricoes_sistema[$i]['adm_usuario_modulo_id'] == 1){
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 19 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_grafico_mod1'] = true;
					}
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 18 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_listagem_mod1'] = true;
					}
				}

				//MODULO 2
				if ($restricoes_sistema[$i]['adm_usuario_modulo_id'] == 2){
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 19 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_grafico_mod2'] = true;
					}
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 18 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_listagem_mod2'] = true;
					}
				}

				//MODULO 3
				if ($restricoes_sistema[$i]['adm_usuario_modulo_id'] == 3){
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 19 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_grafico_mod3'] = true;
					}
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 18 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_listagem_mod3'] = true;
					}
				}

				//MODULO 4
				if ($restricoes_sistema[$i]['adm_usuario_modulo_id'] == 4){
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 19 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_grafico_mod4'] = true;
					}
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 18 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_listagem_mod4'] = true;
					}
				}

				//MODULO 5
				if ($restricoes_sistema[$i]['adm_usuario_modulo_id'] == 5){
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 19 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_grafico_mod5'] = true;
					}
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 18 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_listagem_mod5'] = true;
					}
				}

				//MODULO 6
				if ($restricoes_sistema[$i]['adm_usuario_modulo_id'] == 6){
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 19 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_grafico_mod6'] = true;
					}
					if ($restricoes_sistema[$i]['adm_usuario_permissoes_acoes_id'] == 18 && $restricoes_sistema[$i]['opcoes'] == 1){
						$interface['status_listagem_mod6'] = true;
					}
				}
			}

			//============================================================================================
			//MONTO A VIEW
			$view->seto_dados_array($interface);
			$view->monto_view('modulos/'.$interface['modulos'].'/'.$parametro . ".phtml");

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
