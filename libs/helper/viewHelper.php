<?php

  	/**
  	* Configurações da View SMARTY
  	*
  	* @author Fernando
  	* @version 2.0.0
  	**/

	//=================================================================
	//INCLUDE
	require_once (dirname(dirname((dirname(__FILE__))))).'/libs/core/core.php';
	//=================================================================

	//Classe
	class view {

		//Variaveis
		private $view;
		private $core;
		private $funcoes;

		//Construct
		function __construct($string) {

			//CONFIGURAÇÕES GERAIS
			$this->view    = new Smarty(); //Instancio plugin Smarty
			$this->core    = new core(); //Instancio core do sistema
			$this->funcoes = new funcoes(); //Instancio funções do sistema
			$this->core->includeView(); //Incluo arquivos da view

			//VARIAVEIS DE CONTROLE DO SMARTY PHP
			$this->view->debugging    = $this->core->get_config('smarty_debugging_'.$string); //Debugo o Código
			$this->view->caching      = $this->core->get_config('cache_smart_caching_'.$string); //Gravo Cache
			$this->view->template_dir = $this->core->get_config('diretorio_smart_template_dir_'.$string); //Defino a pasta da View
			$this->view->compile_dir  = $this->core->get_config('diretorio_smart_compile_dir_'.$string); //Defino a pasta de Cache
		}

		//Passo Valor para a View
		function seto_dados($var,$valor,$valor2 = null){
			if(!empty($valor2)){
				$this->view->assign($var,$valor,$valor2);
			}else{
				$this->view->assign($var,$valor);
			}
		}

		//Passo Valor para a View [ARRAY]
		function seto_dados_array($interface){
			foreach($interface as $key => $value){
					$this->view->assign($key, $value);
			}
		}

		//Monto a View
		function monto_view($arquivo){
			$this->test_config($arquivo);
			$this->view->display($arquivo);
		}

		//Retorno o nome da pasta View
		function return_pasta_view(){
			return $this->view->template_dir[0];
		}

		//Retorno campos para PHP
		function retorno_template_php($arquivo){
			$this->test_config($arquivo);
			return $this->view->fetch($arquivo);
		}

		//=================================================================
		//Verifico diretórios
		function test_config($arquivo){

	        if($this->core->verifico_diretorio($this->view->template_dir[0]) === false){
	        	$this->core->mensagem_erro($this->core->get_msg_array('dir_view_not_found',array($this->view->template_dir[0])));
	        } elseif (!is_dir($this->view->template_dir[0])) {
	            $this->core->mensagem_erro($this->core->get_msg_array('dir_not_found',array($this->view->template_dir[0])));
	        } elseif (!is_readable($this->view->template_dir[0])) {
	        	$this->core->mensagem_erro($this->core->get_msg_array('dir_not_readable',array($this->view->template_dir[0])));
	        }
	        //Verifico se arquivo de template existe
	        if($this->core->verifico_diretorio($this->view->template_dir[0]."".$arquivo) === false){
	            $this->core->mensagem_erro($this->core->get_msg_array('file_view_not_found',array($arquivo)));
	        }
	   	}


	}
