<?php

  	/**
  	* Configurações da Aplicação
  	*
  	* @author Fernando
  	* @version 2.0.0
  	**/

  	//=================================================================
	//Includes
	require_once ((dirname(dirname(__FILE__))))."/libs/core/core.php";
	//=================================================================

	//RECEBO A PÁGINA POR PARAMETRO
	if(!empty($_GET['p'])){
		$pages = new pages();
	}else{
		header('location: /');
		exit();
	}

	//=================================================================

	//Classe
	class pages{

		//Variavel de Controle
		private $funcoes;
		private $core;

		//Construct
		function __construct() {

			//INSTANCIO
			$this->core    = new core(); //Instancio Includes
			$this->funcoes = new funcoes(); //Instancio Includes

			//INCLUO ARQUI DE VIEW
			$this->core->includeView();

			//PASTA DE APLICAÇÃO
			$dir_app = "adm";

			//=================================================================
			//INCLUO ARQUIVO DE CONFIGURAÇÕES
			require_once $this->core->get_config('dir_include_conf_'.$dir_app);
			$config_apps = new config_apps_adm();


			//SE FOR URL AMIGAVEL
			if($this->core->get_config('status_url_amigavel') == true){ //Url amigavel

				//=================================================================
				//URL AMIGAVEL
				$parte1 = str_replace("?","",$this->core->get_config('par_url_htacess'));
				$parte2 = str_replace($parte1,"",$parte1."/".$_SERVER['QUERY_STRING']);
				$url    = explode("/",$parte2);
				array_shift($url);

				//=================================================================
				//VERIFICO PERMISSÃO DA PÁGINA
				if(!empty($url[0])){
					if(!in_array($url[0], $config_apps->get_config('paginas_permitidas'))){
						header('location: '.$config_apps->get_config('url_erro1').'/'.$url[0]);
						exit();
					}
				}

				//=================================================================
				//INCLUO E INSTANCIO A CLASSE
				$arquivo = $this->core->get_config('dir_'.$dir_app.'_controller_comp')."/".$url[0]."Controller.php";
				if (!file_exists($arquivo)) {
					$this->core->mensagem_erro($this->core->get_msg_array('controller_view_not_found',$arquivo));
					exit();
				}else{
					require_once $arquivo;
					$conc = $url[0].'Controller';
					$index = new $conc();
					$index->view($url[0]);
				}

			}

		}
	}
