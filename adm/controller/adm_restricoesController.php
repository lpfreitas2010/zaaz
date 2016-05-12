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
	$control = new adm_restricoesController();  // ****-
}

//CLASS ****-
class adm_restricoesController {

	//VARIAVEIS
	private $core;
	private $funcoes;
	private $model;
	private $logs;
	private $email;
	private $dir_app;
	private $config_apps;
	private $config_controller;
	private $url_pagina;
	private $nome_pagina_singular;
	private $nome_pagina_plural;
	private $action_controller;
	private $adm_usuario_modulo_id;
    private $btns_acoes = array();
    private $cmds       = array();

	//=================================================================
	//FUNÇÃO PRINCIPAL
	function __construct() {

		//PARAMETROS
		$this->dir_app = "adm"; //Pasta da aplicação

		//INCLUDES
		$this->core = new core(); //Instancio CORE
		$this->core->includeViewConfig($this->dir_app); //incluo configurações da aplicação
		$this->core->includeController('adm_restricoes',$this->dir_app); //incluo model ****-

		//INSTANCIO
		$this->funcoes           = new funcoes();     //Instancio Funções
		$this->model             = new adm_restricoesModel();  //Instancio Model ****-
		$this->logs              = new logs();        //Instancio Logs
		$this->email             = new email();       //Instancio E-mails
		$this->config_apps       = new config_apps_adm(); //Instancio configurações da aplicação
		$this->config_controller = new config_controller_adm(); //Instancio Funções

		//FUNÇÕES PERMITIDAS
		$funcoes_permitidas = $this->config_apps->getCmds_controller('core'); //Funções permitidas nesta página

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


//***************************************************************************************************************************************************************
//AUTENTICO USUARIO NO SISTEMA
//***************************************************************************************************************************************************************
		function autentico_usuario(){
			$this->funcoes->auth_usuario($this->dir_app,true,$this->core->get_config('dir_raiz_http').$this->dir_app.'/'.$this->config_apps->getPaginas(0)); //Se não tiver logado redireciono para index
		}


//***************************************************************************************************************************************************************
//CARREGO PARAMETROS
//***************************************************************************************************************************************************************
		function carrego_parametros(){

			//===========================================================
			//PARAMETROS GERAIS ****-
			$this->adm_usuario_modulo_id         = 5; //Id do módulo
            $this->url_pagina                    = "adm_restricoes"; //Url da página
            $this->nome_pagina_singular          = "Restrição"; // Nome da página singular
            $this->nome_pagina_plural            = "Restrições Usuários"; // Nome da página plural
            $this->btns_acoes['foco_campo_form'] = "adm_usuario_id"; // Foco campo form add e edd

			//===========================================================
			//CAMPOS DEFAULT
			require $this->core->includeControllerInclude("carrego_parametros_0", $this->dir_app);

			//===========================================================
			//TEXTOS GERAIS ****-


			//===========================================================
			//STATUS DE BTNS DE AÇÕES GERAL ****-
			$this->btns_acoes['status_btn_editar']        = false; // Status do btn de editar [true or false]
			$this->btns_acoes['status_btn_excluir']       = false; // Status do btn de excluir [true or false]
			$this->btns_acoes['status_btn_detalhamento']  = false; // Status do btn de detalhamento [true or false]
			$this->btns_acoes['status_btn_ativar']        = true; // Status do btn de ativar [true or false]
			$this->btns_acoes['status_btn_novo']          = false; // Status de btn de cadastro [true or false]
			$this->btns_acoes['status_btn_atualizar']     = true; // Status de btn de atualizar [true or false]
			$this->btns_acoes['status_imprimir']          = false; // Status de btn de imprimir [true or false]
			$this->btns_acoes['status_exportar_pdf']      = false; // Status de btn de exportar para pdf [true or false]
			$this->btns_acoes['status_exportar_csv']      = false; // Status de btn de exportar para csv [true or false]

			//===========================================================
			//STATUS DE ÁREA DA LISTAGEM ****-
			$this->btns_acoes['status_btn_filtros']            = true; // Status do botão de filtros [true or false]
			$this->btns_acoes['status_col_checkbox']           = false; // Status da coluna do ckeckbox [true or false]
			$this->btns_acoes['status_col_acoes']              = false; // Status da coluna de ações [true or false]

			//===========================================================
			//CONFIGURAÇÕES DE ÁREA DA LISTAGEM ****-
            $this->btns_acoes['qtd_reg_listagem']     = 80; // Quantidade de registros p/ página
            $this->btns_acoes['title_campo_pesquisa'] = 'ID, Usuário, Módulo ou Ação'; // Parametros de pesquisa
            $this->btns_acoes['ordenar_selecionado']  = array("USUÁRIO CRESCENTE","MODULO CRESCENTE","ACAO CRESCENTE"); // Value padrão do campo Odernar
            $this->btns_acoes['agrupar_selecionado']  = array(""); // Value padrão do campo Agrupar
            $this->btns_acoes['mensagem_informativa'] = ''; // Mensagem informativa relacionada a tabela

			//===========================================================
			//STATUS DE ÁREA DOS BOTÕES DE LISTAGEM DEFAULT


			//===========================================================
			//TEXTO DE BTNS DE AÇÕES ****-
			$this->btns_acoes['txt_btn_novo']          = "Inserir"; // Texto do btn de novo

			//===========================================================
			//TITLE DE BTNS DE AÇÕES ****-
			$this->btns_acoes['title_btn_novo']         = "{$this->btns_acoes['txt_btn_novo']} {$this->nome_pagina_singular} "; // Title do btn de novo
			$this->btns_acoes['title_btn_salvar_novo']   = "Salvar e inserir {$this->nome_pagina_singular}"; // Title do btn salvar e novo [true or false]

			//===========================================================
			//CLASSE DE ICONES DE BTNS DE AÇÕES ****-
			$this->btns_acoes['class_icone_listagem']     = "fa fa-lock"; // Classe do icone da listagem

			//===========================================================
			//ACTION E CMDS DO CONTROLLER ****-
	    	require $this->core->includeControllerInclude("carrego_parametros_1", $this->dir_app);

			//===========================================================
			//RESTRIÇÕES DA PÁGINA ****-
			require $this->core->includeControllerInclude("carrego_parametros_2", $this->dir_app);

		}





//***************************************************************************************************************************************************************
//CARREGO INTERFACE DA PÁGINA (VIEW)
//***************************************************************************************************************************************************************
	function view($parametro){

		//===========================================================
		//CONFIGURAÇÕES GERAIS ****-
		$status_auth            = true; // Carrego código de autenticação [ True or false ]
		$parm_auth_status       = true; // Parametro permitido ou não [ True or false ]
		$indice_pagina_red_auth = 2; // Indice do array da página que sera redirecionado
		$status_tempo_sessao    = true; // Carrego código de tempo de sessão [ True or false ]
		$status_cookies_page    = true; // Carrego código que grava o cookie da página acessada [ True or false ]
		$carrego_parametros     = true; // Carrego parametros [ True or false ]

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
		//INCLUDE DE VIEW, CARREGO ÁREAS
		require $this->core->includeControllerInclude("view_2", $this->dir_app);

		//===========================================================
		//TITULOS GERAIS ****-
		//$interface['title_pagina']           = " / "; // Titulo geral da página singular
		//$interface['titulo_pagina_listagem'] = ''; // Titulo geral da página de listagem * altere também em parametros gerais

		//===========================================================
		//OUTRAS LISTAGENS ****-
		/*$this->funcoes->set_array(0,'url',$this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',9)))->set_array(0,'parametro',"1");
		$interface['outras_listagens'] = $this->funcoes->get_array();*/

		//===========================================================
		//OUTROS INCLUDES ****-
		/*$this->funcoes->set_array(0,'include',"arquivo.phtml");
		$interface['outros_includes'] = $this->funcoes->get_array();*/

		//===========================================================
		//CARREGO FUNÇÕES GERAIS DO MODEL
		$this->model->inserir_restricoes_usuarios();

		//============================================================================================
		//MONTO A VIEW
		$view->seto_dados_array($interface);
		$view->monto_view('modulos/'.$interface['modulos'].'/geral.phtml');
	}





//***************************************************************************************************************************************************************
//FUNÇÃO QUE MONTA O CONTEÚDO HTML DE TODAS A PÁGINA
//***************************************************************************************************************************************************************
		function monto_conteudo_html_pagina($param){
			$this->autentico_usuario(); //Autentico usuário no sistema
			//**********************************************************

			//INSTANCIO
			$this->core->includeHelper('mont_html');
			$mont_html = new mont_html();


			//*************************************************************************************
			//MONTO A PÁGINA DE (ADICIONAR E EDITAR)
			//*************************************************************************************
			$conteudo_montado = null; // Documentação da montagem dos campos na pasta /Documentos/doc.php ****-
			if($param == 'campos_html_add_edd'){

				//MONTO CAMPO SELECT
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-4 col-xs-12 area_edit'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','* Usuário');
			    $mont_html->set_array(null,'input_text_select','Selecione');
			    $mont_html->set_array(null,'input_name','adm_usuario');
			    $mont_html->set_array(null,'input_id','adm_usuario');
			    $mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select2) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
			    $mont_html->set_array(null,'input_title','Selecione o Usuário');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
			    $mont_html->set_array(null,'input_value','');
			    $mont_html->set_array(null,'input_outros','');
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $mont_html->set_array(null,'input_multiple',''); //SELECT MULTIPLO - multiple ou ""
			    $mont_html->set_array(null,'input_pagina_title','Ir para a listagem de Usuários'); //TITLE DO BTN DE LISTAGEM
			    $mont_html->set_array(null,'input_pagina_url',$this->core->get_config('dir_raiz_http').$this->dir_app.'/adm_usuario'); //URL BTN DE LISTAGEM
			    $mont_html->set_array(null,'input_pagina_add_title','Adicionar Usuário'); //TITLE DO BTN DE ADICIONAR
			    $mont_html->set_array(null,'input_pagina_add_id','area_nivel'); //ID QUE CHAMA O MODAL DE ADICIONAR
			    $nivel = $mont_html->monto_html_select();

				//MONTO CAMPO SELECT
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-4 col-xs-12 area_edit'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','* Módulo');
			    $mont_html->set_array(null,'input_text_select','Selecione');
			    $mont_html->set_array(null,'input_name','adm_usuario_modulo');
			    $mont_html->set_array(null,'input_id','adm_usuario_modulo');
			    $mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select2) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
			    $mont_html->set_array(null,'input_title','Selecione o Módulo');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
			    $mont_html->set_array(null,'input_value','');
			    $mont_html->set_array(null,'input_outros','');
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $mont_html->set_array(null,'input_multiple',''); //SELECT MULTIPLO - multiple ou ""
			    $mont_html->set_array(null,'input_pagina_title','Ir para a listagem de Módulos'); //TITLE DO BTN DE LISTAGEM
			    $mont_html->set_array(null,'input_pagina_url',$this->core->get_config('dir_raiz_http').$this->dir_app.'/modulos'); //URL BTN DE LISTAGEM
			    $mont_html->set_array(null,'input_pagina_add_title','Adicionar Módulo'); //TITLE DO BTN DE ADICIONAR
			    $mont_html->set_array(null,'input_pagina_add_id','area_modulo'); //ID QUE CHAMA O MODAL DE ADICIONAR
			    $modulo = $mont_html->monto_html_select();

				//MONTO CAMPO SELECT
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-4 col-xs-12 area_edit'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','* Permissão');
			    $mont_html->set_array(null,'input_text_select','Selecione');
			    $mont_html->set_array(null,'input_name','adm_usuario_permissoes_acoes');
			    $mont_html->set_array(null,'input_id','adm_usuario_permissoes_acoes');
			    $mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select2) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
			    $mont_html->set_array(null,'input_title','Selecione a Permissão');
			    $mont_html->set_array(null,'title_help','Para bloquear ou liberar o acesso a todo o módulo selecione. <br /><strong>Permissão: interface</strong>'); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
			    $mont_html->set_array(null,'input_value','');
			    $mont_html->set_array(null,'input_outros','');
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $mont_html->set_array(null,'input_multiple',''); //SELECT MULTIPLO - multiple ou ""
			    $mont_html->set_array(null,'input_pagina_title','Ir para a listagem de Permissões'); //TITLE DO BTN DE LISTAGEM
			    $mont_html->set_array(null,'input_pagina_url',$this->core->get_config('dir_raiz_http').$this->dir_app.'/modulos_acoes'); //URL BTN DE LISTAGEM
			    $mont_html->set_array(null,'input_pagina_add_title','Adicionar Permissão'); //TITLE DO BTN DE ADICIONAR
			    $mont_html->set_array(null,'input_pagina_add_id','area_permissao'); //ID QUE CHAMA O MODAL DE ADICIONAR
			    $permissoes = $mont_html->monto_html_select();

				//MONTO CAMPO MODAL ADICIONAR
				$mont_html->set_array(null,'input_id_area','area_nivel'); //Coloque o mesmo valor que foi inserido no select em input_pagina_add_id
				$mont_html->set_array(null,'url_pagina',$this->core->get_config('dir_raiz_http').$this->dir_app.'/adm_usuario_nivel'); //PARAMETRO TIPO
				$modal_nivel = $mont_html->monto_html_modal_add();

				//MONTO CAMPO MODAL ADICIONAR
				$mont_html->set_array(null,'input_id_area','area_modulo'); //Coloque o mesmo valor que foi inserido no select em input_pagina_add_id
				$mont_html->set_array(null,'url_pagina',$this->core->get_config('dir_raiz_http').$this->dir_app.'/modulos'); //PARAMETRO TIPO
				$modal_modulo = $mont_html->monto_html_modal_add();

				//MONTO CAMPO MODAL ADICIONAR
				$mont_html->set_array(null,'input_id_area','area_permissao'); //Coloque o mesmo valor que foi inserido no select em input_pagina_add_id
				$mont_html->set_array(null,'url_pagina',$this->core->get_config('dir_raiz_http').$this->dir_app.'/modulos_acoes'); //PARAMETRO TIPO
				$modal_permissao = $mont_html->monto_html_modal_add();


				//MONTO CAMPO SELECT
				$this->funcoes->set_array(0,'id',1)->set_array(0,'value','Não Permitido');
				$this->funcoes->set_array(1,'id',2)->set_array(1,'value','Permitido');
				$valores_select = $this->funcoes->get_array();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-4 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Ação');
				$mont_html->set_array(null,'input_text_select','Selecione');
				$mont_html->set_array(null,'input_name','opcoes');
				$mont_html->set_array(null,'input_id','opcoes');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select2) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'input_multiple',''); //SELECT MULTIPLO - multiple ou ""
				$opcoes = $mont_html->monto_html_select();


				//===========================================================
				//MONTO CAMPOS E ÁREAS
				$mont_html->set_array(0,'titulo','Informações Gerais');
				$mont_html->set_array(0,'icone_titulo',''); // Classe icone - Font Awesome Icons
				$mont_html->set_array(0,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(0,'conteudo'," {$modal_nivel} {$modal_modulo} {$modal_permissao} {$nivel} {$modulo} {$permissoes} {$opcoes} "); //conteudo ***
				$conteudo_montado = $mont_html->monto_html_area_forms();

				//===========================================================
				//CRIO UM ARQUIVO.PHTML COM OS CAMPOS MONTADOS
				$this->funcoes->crio_arquivo_phtml($this->core->get_config('dir_view_template_'.$this->dir_app.'_comp').'modulos/'.$this->config_apps->get_config('modulos',0).'/includes/forms/form_'.$this->url_pagina,$conteudo_montado);
			}


			//*************************************************************************************
			//MONTO A PÁGINA DE (EXPORTAR PARA PDF)
			//*************************************************************************************
			$conteudo_montado = null; // Documentação da montagem dos campos na pasta /Documentos/doc.php ****-
			if($param == 'campos_html_exp_pdf'){

				//MONTO VALORES DO CHECKBOX CAMPOS DA TABELA
				$this->funcoes->set_array(0,'input_id','app');
				$this->funcoes->set_array(0,'input_name','campos_pdf[]');
				$this->funcoes->set_array(0,'input_class','');
				$this->funcoes->set_array(0,'input_title','');
				$this->funcoes->set_array(0,'input_value','app'); //value do campo
				$this->funcoes->set_array(0,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(0,'input_texto_checkbox','App'); //Texto
				$this->funcoes->set_array(1,'input_id','url');
				$this->funcoes->set_array(1,'input_name','campos_pdf[]');
				$this->funcoes->set_array(1,'input_class','');
				$this->funcoes->set_array(1,'input_title','');
				$this->funcoes->set_array(1,'input_value','url'); //value do campo
				$this->funcoes->set_array(1,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(1,'input_texto_checkbox','Url'); //Texto
				$valores_checkbox = $this->funcoes->get_array();

				//MONTO CAMPO INPUT CHECKBOX
				$mont_html->set_array(null,'input_class_tamanho','col-md-12 col-sm-4 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'input_id','campos_pdf'); // id da area
				$mont_html->set_array(null,'label_for_texto','');
				$mont_html->set_array(null,'input_options',$valores_checkbox);
				$input_checkbox = $mont_html->monto_html_input_checkbox();

				//MONTO CAMPO ÁREA DESCRIÇÃO 1
				$mont_html->set_array(null,'area_descricao','Marque os campos que deseja exportar:')->set_array(null,'area_descricao_icon_class','fa fa-check-square-o'); // Classe icone - Font Awesome Icons
				$area_descricao = $mont_html->monto_html_area_descricao();

				//

				//===========================================================
				//MONTO CAMPOS
				$mont_html->set_array(0,'conteudo'," {$area_descricao} {$input_checkbox} ");
				$conteudo_montado = $mont_html->monto_html_forms();

				//===========================================================
				//CRIO UM ARQUIVO.PHTML COM OS CAMPOS MONTADOS
				$this->funcoes->crio_arquivo_phtml($this->core->get_config('dir_view_template_'.$this->dir_app.'_comp').'modulos/'.$this->config_apps->get_config('modulos',0).'/includes/forms/form_pdf_'.$this->url_pagina,$conteudo_montado);
			}


			//*************************************************************************************
			//MONTO A PÁGINA DE (IMPRIMIR)
			//*************************************************************************************
			$conteudo_montado = null; // Documentação da montagem dos campos na pasta /Documentos/doc.php ****-
			if($param == 'campos_html_imprimir'){

				//MONTO VALORES DO CHECKBOX CAMPOS DA TABELA
				$this->funcoes->set_array(0,'input_id','app');
				$this->funcoes->set_array(0,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(0,'input_class','');
				$this->funcoes->set_array(0,'input_title','');
				$this->funcoes->set_array(0,'input_value','app'); //value do campo
				$this->funcoes->set_array(0,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(0,'input_texto_checkbox','App'); //Texto
				$this->funcoes->set_array(1,'input_id','url');
				$this->funcoes->set_array(1,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(1,'input_class','');
				$this->funcoes->set_array(1,'input_title','');
				$this->funcoes->set_array(1,'input_value','url'); //value do campo
				$this->funcoes->set_array(1,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(1,'input_texto_checkbox','Url'); //Texto
				$valores_checkbox = $this->funcoes->get_array();

				//MONTO CAMPO INPUT CHECKBOX
				$mont_html->set_array(null,'input_class_tamanho','col-md-12 col-sm-4 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'input_id','campos_pdf'); // id da area
				$mont_html->set_array(null,'label_for_texto','');
				$mont_html->set_array(null,'input_options',$valores_checkbox);
				$input_checkbox = $mont_html->monto_html_input_checkbox();

				//MONTO CAMPO ÁREA DESCRIÇÃO 1
				$mont_html->set_array(null,'area_descricao','Marque os campos que deseja imprimir:')->set_array(null,'area_descricao_icon_class','fa fa-check-square-o'); // Classe icone - Font Awesome Icons
				$area_descricao = $mont_html->monto_html_area_descricao();

				//

				//===========================================================
				//MONTO CAMPOS
				$mont_html->set_array(0,'conteudo'," {$area_descricao} {$input_checkbox} ");
				$conteudo_montado = $mont_html->monto_html_forms();

				//===========================================================
				//CRIO UM ARQUIVO.PHTML COM OS CAMPOS MONTADOS
				$this->funcoes->crio_arquivo_phtml($this->core->get_config('dir_view_template_'.$this->dir_app.'_comp').'modulos/'.$this->config_apps->get_config('modulos',0).'/includes/forms/form_impimir_'.$this->url_pagina,$conteudo_montado);
			}


			//*************************************************************************************
			//MONTO A PÁGINA DE (PESQUISA AVANÇADA)
			//*************************************************************************************
			$conteudo_montado = null; // Documentação da montagem dos campos na pasta /Documentos/doc.php ****-
			if($param == 'campos_html_pesq_avancada'){

				//VALORES DA PESQUISA AVANÇADA
				$valores_pesq_avancada = $_SESSION[$pref.$this->url_pagina.'_pesquisa_avancada'];

				//MONTO CAMPO ÁREA DESCRIÇÃO
				$mont_html->set_array(null,'area_descricao','Períodos')->set_array(null,'area_descricao_icon_class','fa fa-calendar');
				$area_descricao = $mont_html->monto_html_area_descricao();

				//CAMPO TIPO DE DATA
				$this->funcoes->set_array(0,'id','criado')->set_array(0,'value','Data de Cadastro');
				$this->funcoes->set_array(1,'id','modificado')->set_array(1,'value','Data de Edição');
				$valores_select = $this->funcoes->get_array();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
				$mont_html->set_array(null,'label_for_texto','Tipo de Data');
				$mont_html->set_array(null,'input_text_select','Selecione');
				$mont_html->set_array(null,'input_name','pesq_avanc_tipo_data');
				$mont_html->set_array(null,'input_id','pesq_avanc_tipo_data');
				$mont_html->set_array(null,'input_class',''); // select personalizado: select2
				$mont_html->set_array(null,'input_title','Selecione o Tipo de Data');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value',''.$valores_pesq_avancada['periodo_tipo_data'].'');
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$select = $mont_html->monto_html_select();

				//CAMPO PERIODO INICIAL
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
				$mont_html->set_array(null,'label_for_texto','Período Inicial');
				$mont_html->set_array(null,'input_type','text');
				$mont_html->set_array(null,'input_name','pesq_avanc_periodo_data_de');
				$mont_html->set_array(null,'input_id','pesq_avanc_periodo_data_de');
				$mont_html->set_array(null,'input_class','input-mask date-picker'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Período Inicial');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['periodo_data_de']);
				$mont_html->set_array(null,'input_outros','data-mask="00/00/0000"'); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-calendar'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$input_text = $mont_html->monto_html_input();

				//CAMPO PERIODO FINAL
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
				$mont_html->set_array(null,'label_for_texto','Período Final');
				$mont_html->set_array(null,'input_type','text');
				$mont_html->set_array(null,'input_name','pesq_avanc_periodo_data_ate');
				$mont_html->set_array(null,'input_id','pesq_avanc_periodo_data_ate');
				$mont_html->set_array(null,'input_class','input-mask date-picker'); // [MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Período Final');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['periodo_data_ate']);
				$mont_html->set_array(null,'input_outros','data-mask="00/00/0000"'); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-calendar'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$input_text2 = $mont_html->monto_html_input();

				//MONTO CAMPO ÁREA DESCRIÇÃO
				$mont_html->set_array(null,'area_descricao','Outras Informações')->set_array(null,'area_descricao_icon_class','fa fa-list')->set_array(null,'class','subtitulo-margin_top'); // Classes úteis = (subtitulo-margin_top) -Classe icone - Font Awesome Icons
				$area_descricao2 = $mont_html->monto_html_area_descricao();

				//MONTO CAMPO INPUT TEXT
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','Usuário');
			    $mont_html->set_array(null,'input_type','text'); //text, password
			    $mont_html->set_array(null,'input_name','pesq_avanc_usuario');
			    $mont_html->set_array(null,'input_id','pesq_avanc_usuario');
			    $mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
			    $mont_html->set_array(null,'input_placeholder','');
			    $mont_html->set_array(null,'input_title','Digite o Usuário');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_usuario']);
			    $mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-user'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
			    $usuario = $mont_html->monto_html_input();

				//VALORES SELECT BANCO DE DADOS ** não funciona o adicionar dinamicamente
			    $this->model->setCampos('campo_tabela',"adm_usuario_modulo");
			    $this->model->setCampos('campo_coluna',"id");
			    $this->model->setCampos('campo_coluna2',"modulo");
			    $this->model->setCampos('campo_where',"");
			    $this->model->setCampos('campo_orderby',"modulo ASC");
			    $valor = $this->model->select_simples_retorna_array_mont_vcol();
			    for ($i=0; $i < count($valor) ; $i++) {
			        $this->funcoes->set_array($i,'id',$valor[$i]['id'])->set_array($i,'value',$this->funcoes->conv_string($valor[$i]['modulo'],2));
			    }
			    $valores_select = $this->funcoes->get_array();
			    $this->model->getLimpoCampos();
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','Módulo');
			    $mont_html->set_array(null,'input_text_select','Selecione');
			    $mont_html->set_array(null,'input_name','pesq_avanc_modulo');
			    $mont_html->set_array(null,'input_id','pesq_avanc_modulo');
			    $mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
			    $mont_html->set_array(null,'input_title','Selecione o Módulo');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
			    $mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_modulo']);
			    $mont_html->set_array(null,'input_outros','');
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $modulo = $mont_html->monto_html_select();

				//VALORES SELECT BANCO DE DADOS ** não funciona o adicionar dinamicamente
				$this->model->setCampos('campo_tabela',"adm_usuario_permissoes_acoes");
				$this->model->setCampos('campo_coluna',"id");
				$this->model->setCampos('campo_coluna2',"acoes");
				$this->model->setCampos('campo_where'," id != 17");
				$this->model->setCampos('campo_orderby',"id ASC");
				$valor = $this->model->select_simples_retorna_array_mont_vcol();
				for ($i=0; $i < count($valor) ; $i++) {
					$this->funcoes->set_array($i,'id',$valor[$i]['id'])->set_array($i,'value',$this->funcoes->conv_string($valor[$i]['acoes'],2));
				}
				$valores_select = $this->funcoes->get_array();
				$this->model->getLimpoCampos();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Ação');
				$mont_html->set_array(null,'input_text_select','Selecione');
				$mont_html->set_array(null,'input_name','pesq_avanc_acoes');
				$mont_html->set_array(null,'input_id','pesq_avanc_acoes');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione a Ação');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_acoes']);
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$acoes = $mont_html->monto_html_select();

				//VALORES SELECT ESTÁTICO
				$this->funcoes->set_array(0,'id',0)->set_array(0,'value','Não Permitido');
				$this->funcoes->set_array(1,'id',1)->set_array(1,'value','Permitido');
				$valores_select = $this->funcoes->get_array();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-4 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Status');
				$mont_html->set_array(null,'input_text_select','Selecione  ');
				$mont_html->set_array(null,'input_name','pesq_avanc_status');
				$mont_html->set_array(null,'input_id','pesq_avanc_status');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione o Status');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_status']);
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$status = $mont_html->monto_html_select();

				//===========================================================
				//MONTO CAMPOS
				$mont_html->set_array(0,'conteudo'," {$area_descricao} {$input_text} {$input_text2} {$select}  ");
				$mont_html->set_array(1,'conteudo'," {$area_descricao2} {$usuario} {$modulo} {$acoes} {$status}");
				$conteudo_montado = $mont_html->monto_html_forms();

				//===========================================================
				//CRIO UM ARQUIVO.PHTML COM OS CAMPOS MONTADOS
				$this->funcoes->crio_arquivo_phtml($this->core->get_config('dir_view_template_'.$this->dir_app.'_comp').'modulos/'.$this->config_apps->get_config('modulos',0).'/includes/forms/form_pes_avancada_'.$this->url_pagina,$conteudo_montado);
			}


			//*************************************************************************************
			//MONTO A PÁGINA DE (LISTAGEM)
			//*************************************************************************************
			$conteudo_montado = null; // Documentação da montagem dos campos na pasta /Documentos/doc.php ****-
			if($param == 'listagem'){

				//===========================================================
				//RECEBO OS DADOS DE PESQUISA AVANÇADA ****-
				$pesq_avanc1 = $this->funcoes->anti_injection($_POST['pesq_avanc_tipo_data']); // Recebo dados
				$pesq_avanc2 = $this->funcoes->anti_injection($_POST['pesq_avanc_periodo_data_de']); // Recebo dados
				$pesq_avanc3 = $this->funcoes->anti_injection($_POST['pesq_avanc_periodo_data_ate']); // Recebo dados
				(string) $pesq_avanc4 = $this->funcoes->anti_injection($_POST['pesq_avanc_usuario']); // Recebo dados
				(int) $pesq_avanc5 = $this->funcoes->anti_injection($_POST['pesq_avanc_modulo']); // Recebo dados
				(int) $pesq_avanc6 = $this->funcoes->anti_injection($_POST['pesq_avanc_acoes']); // Recebo dados
				(int) $pesq_avanc7 = $this->funcoes->anti_injection($_POST['pesq_avanc_status']); // Recebo dados

				//SETO OS DADOS DA PESQUISA AVANÇADA NA SESSÃO ****-
				if(!empty($pesq_avanc1)){ $pesq_avancada['periodo_tipo_data'] = $pesq_avanc1; }
				if(!empty($pesq_avanc2) && !empty($pesq_avanc1)){ $pesq_avancada['periodo_data_de'] = $pesq_avanc2; }
				if(!empty($pesq_avanc3) && !empty($pesq_avanc1)){ $pesq_avancada['periodo_data_ate'] = $pesq_avanc3; }
				if(!empty($pesq_avanc4)){ $pesq_avancada['pesq_avanc_usuario'] = $pesq_avanc4; }
				if(!empty($pesq_avanc5)){ $pesq_avancada['pesq_avanc_modulo'] = $pesq_avanc5; }
				if(!empty($pesq_avanc6)){ $pesq_avancada['pesq_avanc_acoes'] = $pesq_avanc6; }
				if(!empty($pesq_avanc7)){ $pesq_avancada['pesq_avanc_status'] = $pesq_avanc7; }

				//

				//===========================================================
				//MONTO O MENU DE FILTROS ****-
				$this->funcoes->set_array(0,'btn_status',true);
				$this->funcoes->set_array(0,'btn_texto','Status Permitido');
				$this->funcoes->set_array(0,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(0,'btn_target','');
				$this->funcoes->set_array(0,'btn_title','');
				$this->funcoes->set_array(0,'btn_id','');
				$this->funcoes->set_array(0,'btn_class','btn_param_pesquisa'); // (btn_param_pesquisa) = seto e submeto a pesquisa - (btn_param_pesquisa_focus) = seto o valor e foco -
				$this->funcoes->set_array(0,'btn_class_icon','fa fa-check');
				$this->funcoes->set_array(0,'btn_outros','param_pesquisa="Status: Permitido"');
				$this->funcoes->set_array(1,'btn_status',true);
				$this->funcoes->set_array(1,'btn_texto','Status Não Permitido');
				$this->funcoes->set_array(1,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(1,'btn_target','');
				$this->funcoes->set_array(1,'btn_title','');
				$this->funcoes->set_array(1,'btn_id','');
				$this->funcoes->set_array(1,'btn_class','btn_param_pesquisa'); // (btn_param_pesquisa) = seto e submeto a pesquisa - (btn_param_pesquisa_focus) = seto o valor e foco -
				$this->funcoes->set_array(1,'btn_class_icon','fa fa-remove');
				$this->funcoes->set_array(1,'btn_outros','param_pesquisa="Status: Não Permitido"');
				$this->funcoes->set_array(2,'class_divider','divider'); // divider
				$this->funcoes->set_array(3,'btn_status',true);
				$this->funcoes->set_array(3,'btn_texto','ID Usuário');
				$this->funcoes->set_array(3,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(3,'btn_target','');
				$this->funcoes->set_array(3,'btn_title','');
				$this->funcoes->set_array(3,'btn_id','');
				$this->funcoes->set_array(3,'btn_class','btn_param_pesquisa_focus'); // (btn_param_pesquisa) = seto e submeto a pesquisa - (btn_param_pesquisa_focus) = seto o valor e foco -
				$this->funcoes->set_array(3,'btn_class_icon','');
				$this->funcoes->set_array(3,'btn_outros','param_pesquisa="ID Usuário: "');
				$filtros = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO OS OPTION DE ORDENAR ****-
				$this->funcoes->set_array(0,'value',"ID CRESCENTE");
				$this->funcoes->set_array(1,'value',"ID DECRESCENTE");
				$this->funcoes->set_array(2,'value',"separator"); //separator
				$this->funcoes->set_array(3,'value',"CADASTRO CRESCENTE");
				$this->funcoes->set_array(4,'value',"CADASTRO DECRESCENTE");
				$this->funcoes->set_array(5,'value',"separator"); //separator
				$this->funcoes->set_array(6,'value',"EDITADO CRESCENTE");
				$this->funcoes->set_array(7,'value',"EDITADO DECRESCENTE");
				$this->funcoes->set_array(8,'value',"separator"); //separator
				$this->funcoes->set_array(9,'value',"USUÁRIO CRESCENTE");
				$this->funcoes->set_array(10,'value',"USUÁRIO DECRESCENTE");
				$this->funcoes->set_array(11,'value',"separator"); //separator
				$this->funcoes->set_array(12,'value',"MODULO CRESCENTE");
				$this->funcoes->set_array(13,'value',"MODULO DECRESCENTE");
				$this->funcoes->set_array(14,'value',"separator"); //separator
				$this->funcoes->set_array(15,'value',"ACAO CRESCENTE");
				$this->funcoes->set_array(16,'value',"ACAO DECRESCENTE");
				$option_ordenar = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO OS OPTIONS DE AGRUPAR ****-
				$this->funcoes->set_array(null,'pagina','Página');
				$this->funcoes->set_array(null,'acao','Ação');
				$option_agrupar = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO O HEADER DA TABELA ****-
				$this->funcoes->set_array(0,'th_titulo','<i class="fa fa-hashtag"></i> ID');
				$this->funcoes->set_array(0,'th_class','hidden-md text-center col-md-1 col-lg-1');
				$this->funcoes->set_array(0,'th_outros','');
				$this->funcoes->set_array(1,'th_titulo','Usuário');
				$this->funcoes->set_array(1,'th_class',' col-md-3 col-lg-2');
				$this->funcoes->set_array(1,'th_outros','');
				$this->funcoes->set_array(2,'th_titulo','Ação');
				$this->funcoes->set_array(2,'th_class','col-md-4 col-lg-3');
				$this->funcoes->set_array(2,'th_outros','');
				$this->funcoes->set_array(3,'th_titulo','<i class="fa fa-check-circle"></i> Status');
				$this->funcoes->set_array(3,'th_class','text-center col-md-2 col-lg-2');
				$this->funcoes->set_array(3,'th_outros','');
				$this->funcoes->set_array(4,'th_titulo','<i class="fa fa-calendar-o"></i> Data Modificado');
				$this->funcoes->set_array(4,'th_class','hidden-md text-center col-md-2 col-lg-2');
				$this->funcoes->set_array(4,'th_outros','');
				$this->funcoes->set_array(5,'th_titulo','<i class="fa fa-calendar-o"></i> Data Cadastro');
				$this->funcoes->set_array(5,'th_class','hidden-md text-center col-md-2 col-lg-2');
				$this->funcoes->set_array(5,'th_outros','');
				$header_tabela = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO O CONTEUDO DA TABELA ****-

				//FILTROS SMARTY PHP
				$mont_html->set_array(0,'tipo',"data_hora"); // data, hora, hora_min, data_hora, idade, tempo, tempo_hora, enc_texto, maiusculo, minusculo, maiusc_minusc e format_num
				$mont_html->set_array(0,'campo',"[bd:criado]"); // campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'valor_enc_texto',""); // valor numérico do tamanho do texto
				$criado = $mont_html->filtros_smarty();

				//FILTROS SMARTY PHP
				$mont_html->set_array(0,'tipo',"data_hora"); // data, hora, hora_min, data_hora, idade, tempo, tempo_hora, enc_texto, maiusculo, minusculo, maiusc_minusc e format_num
				$mont_html->set_array(0,'campo',"[bd:modificado]"); // campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'valor_enc_texto',""); // valor numérico do tamanho do texto
				$modificado = $mont_html->filtros_smarty();

				//CONDIÇÕES IF ELSE SMARTY
			    $mont_html->set_array(0,'condicoes',"[bd:opcoes] == 1 && [bd:acoes] != 'interface'"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
			    $mont_html->set_array(0,'cod_if'," <label class='label-success label'><i class='fa fa-check'></i> Permitido</label> "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
			    $mont_html->set_array(0,'cod_else',""); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
			    $status = $mont_html->if_else_smarty();
				$mont_html->set_array(0,'condicoes',"[bd:opcoes] == 0 && [bd:acoes] != 'interface'"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," <label class='label-danger label'><i class='fa fa-times'></i> Não Permitido</label> "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else',""); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$status1 = $mont_html->if_else_smarty();


				//CONDIÇÕES IF ELSE SMARTY
				$mont_html->set_array(0,'condicoes',"[bd:opcoes] == 0"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," <label class='label-success label'><i class='fa fa-check'></i> TUDO PERMITIDO</label> "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else',""); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$status2 = $mont_html->if_else_smarty();
				$mont_html->set_array(0,'condicoes',"[bd:opcoes] == 1"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," <label class='label-danger label'><i class='fa fa-times'></i> NADA PERMITIDO</label> "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else',""); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$status3 = $mont_html->if_else_smarty();

				//AÇÃO DE LINHA DA TABELA
			    $mont_html->set_array(0,'status',true); // Status da ação true or false
			    $mont_html->set_array(0,'class',"livre"); // Tipo: btn_ativar_desativar, btn_detalhamento, btn_editar, url ou livre
			    $mont_html->set_array(0,'url',""); // url de redirecionamento $this->core->get_config('dir_raiz_http').$this->dir_app."/"
			    $mont_html->set_array(0,'outros','');
			    $mont_html->set_array(0,'campo_ativar',"opcoes"); //outros campos ativar e desativar
			    $mont_html->set_array(0,'status_btn_detalhamento',$this->btns_acoes['status_btn_detalhamento']); // Status ação detalhamento true or false
			    $mont_html->set_array(0,'status_btn_editar',$this->btns_acoes['status_btn_editar']); // Status ação editar true or false
			    $mont_html->set_array(0,'status_btn_ativar',$this->btns_acoes['status_btn_ativar']); // Status ação ativar e desativar true or false
			    $mont_html->set_array(0,'url_btn_ativar',$this->cmds['action_ativar']); // Action do btn ativar
			    $acao_linha = $mont_html->acao_linha_smarty();

				//MONTO HTML DE UMA IMAGEM
				$mont_html->set_array(0,'src',$this->core->get_config('dir_raiz_http')."files/perfil_usuario/p/[bd:img_perfil]"); // $this->core->get_config('dir_raiz_http').'files/'; texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'alt',"[bd:nome]"); // texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'class',"img-circle");
				$mont_html->set_array(0,'width',"30"); // valor numérico do tamanho do da imagem
				$mont_html->set_array(0,'height',"30"); // valor numérico do tamanho do da imagem
				$mont_html->set_array(0,'lightbox',true); // lightbox true ou false
				$imagem_bd = $mont_html->monto_html_img();
				$mont_html->set_array(0,'src',$this->core->get_config('dir_raiz_http')."".$this->dir_app."/view/assets/img/avatar/avatar_h.jpg"); // $this->core->get_config('dir_raiz_http').'files/'; texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'alt',"[bd:nome]"); // texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'class',"img-circle");
				$mont_html->set_array(0,'width',"30"); // valor numérico do tamanho do da imagem
				$mont_html->set_array(0,'height',""); // valor numérico do tamanho do da imagem
				$mont_html->set_array(0,'lightbox',false); // lightbox true ou false
				$imagem_pd = $mont_html->monto_html_img();
				$mont_html->set_array(0,'condicoes'," [bd:img_perfil] != '' "); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," {$imagem_bd} "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else'," {$imagem_pd} "); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$img_usuario = $mont_html->if_else_smarty();

				//CONDIÇÕES IF ELSE SMARTY
				$mont_html->set_array(0,'condicoes',"[bd:acoes] == 'interface'"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," <span title='Marcar todos as ações como Permitidas ou Não Permitidas'> {$status2}{$status3} </span> "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else'," [bd:acoes] "); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$acoes = $mont_html->if_else_smarty();

				//CONDIÇÕES IF ELSE SMARTY
				$mont_html->set_array(0,'condicoes',"[bd:acoes] == 'interface'"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else'," [bd:id] "); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$id = $mont_html->if_else_smarty();

				//CONDIÇÕES IF ELSE SMARTY
				$mont_html->set_array(0,'condicoes',"[bd:acoes] == 'interface'"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else'," {$img_usuario} [bd:nome] "); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$usuario = $mont_html->if_else_smarty();

				//CONDIÇÕES IF ELSE SMARTY
				$mont_html->set_array(0,'condicoes',"[bd:acoes] == 'interface'"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else'," {$modificado} "); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$modificado = $mont_html->if_else_smarty();

				//CONDIÇÕES IF ELSE SMARTY
				$mont_html->set_array(0,'condicoes',"[bd:acoes] == 'interface'"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else'," {$criado} "); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$criado = $mont_html->if_else_smarty();

				//===========================================================
				//CONTEUDO MONTADO
				$mont_html->set_array(0,'td'," {$id} "); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'class',"hidden-md load-elements text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(0,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
				$mont_html->set_array(1,'td'," {$usuario} "); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(1,'class',"load-elements {$acao_linha['class']}"); // Classes
				$mont_html->set_array(1,'outros'," {$acao_linha['outros']}"); //Outros parametros do td
				$mont_html->set_array(2,'td'," <span style='line-height:28px'><strong style='font-size:16px;'>[bd:modulo]</strong> - {$acoes} </span> "); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(2,'class',"load-elements {$acao_linha['class']}"); // Classes
				$mont_html->set_array(2,'outros',"{$acao_linha['outros']}"); //Outros parametros do td
				$mont_html->set_array(3,'td'," {$status} {$status1} "); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(3,'class',"load-elements text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(3,'outros',"title='Alterar Status' {$acao_linha['outros']}"); //Outros parametros do td
				$mont_html->set_array(4,'td'," {$modificado} "); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(4,'class',"hidden-md load-elements text-center  {$acao_linha['class']}"); // Classes
				$mont_html->set_array(4,'outros',"{$acao_linha['outros']}"); //Outros parametros do td
				$mont_html->set_array(5,'td'," {$criado} "); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(5,'class',"hidden-md load-elements text-center  {$acao_linha['class']}"); // Classes
				$mont_html->set_array(5,'outros',"{$acao_linha['outros']}"); //Outros parametros do td
				$conteudo_td_montado = $mont_html->monto_html_td();

				//===========================================================
				//RETORNO OS DADOS MONTADOS
				$array = array('filtros' => $filtros, 'option_ordenar' => $option_ordenar, 'option_agrupar' => $option_agrupar, 'header_tabela' => $header_tabela, 'conteudo_td_montado' => $conteudo_td_montado, 'pesq_avancada' => $pesq_avancada );
				return $array;
			}
		}





//***************************************************************************************************************************************************************
//FUNÇÃO DE SETAR OS DADOS DO ADICIONAR E EDITAR REGISTRO JAVASCRIPT
//***************************************************************************************************************************************************************
	function cmd_set_add_edd(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		$this->carrego_parametros(); //Carrego parametros gerais
		//**********************************************************

		//RECEBO OS PARAMETROS
		$id = $this->funcoes->anti_injection($_GET['id']); // Recebo id

		//===========================================================
		//SETO VALORES EM ADICIONAR
		if(empty($id)){

			//MONTO OS DADOS NOS CAMPOS ****

			//CAMPO
			$this->funcoes->set_array(0,'tipo_set','select'); // input, textarea, checkbox, select
			$this->funcoes->set_array(0,'campo_set_form','opcoes');
			$this->funcoes->set_array(0,'valor_campo_set',1);

			//CAMPO
			/*$this->funcoes->set_array(1,'tipo_set','show_hide'); // input, textarea, checkbox, select
			$this->funcoes->set_array(1,'campo_set_form','.area_edit');
			$this->funcoes->set_array(1,'valor_campo_set','show');*/


			//MOSTRO NA TELA
			echo json_encode($this->funcoes->get_array());

		//===========================================================
		//SETO VALORES EM EDITAR
		}else{

			//INCLUDE - verifico permissão, seto e executo model
			require $this->core->includeControllerInclude("cmd_set_add_edd_1", $this->dir_app);
			if(!empty($exec)){

				//MONTO OS DADOS NOS CAMPOS ****-
				for ($i=0; $i < count($exec) ; $i++) {

					//CAMPO
					$this->funcoes->set_array(0,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(0,'campo_set_form','adm_usuario_id');
					$this->funcoes->set_array(0,'valor_campo_set',$exec[$i]['adm_usuario_id']);

					//CAMPO
					/*$this->funcoes->set_array(4,'tipo_set','show_hide'); // input, textarea, checkbox, select
					$this->funcoes->set_array(4,'campo_set_form','.area_edit');
					$this->funcoes->set_array(4,'valor_campo_set','hide');*/

					//CAMPO
					$this->funcoes->set_array(1,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(1,'campo_set_form','adm_usuario_modulo_id');
					$this->funcoes->set_array(1,'valor_campo_set',$exec[$i]['adm_usuario_modulo_id']);

					//CAMPO
					$this->funcoes->set_array(2,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(2,'campo_set_form','adm_usuario_permissoes_acoes_id');
					$this->funcoes->set_array(2,'valor_campo_set',$exec[$i]['adm_usuario_permissoes_acoes_id']);

					//converto valor opcoes
					if($exec[$i]['opcoes'] == 0){
						$opcoes = 1;
					}
					if($exec[$i]['opcoes'] == 1){
						$opcoes = 2;
					}

					//CAMPO
					$this->funcoes->set_array(3,'tipo_set','select'); // input, textarea, checkbox, select
					$this->funcoes->set_array(3,'campo_set_form','opcoes');
					$this->funcoes->set_array(3,'valor_campo_set',$opcoes);

				}

				//MOSTRO NA TELA
				echo json_encode($this->funcoes->get_array());
			}else{
				$this->funcoes->set_array(null,'erro',$this->core->get_msg_array('erro_set_form')); // Mensagem de erro
				echo json_encode($this->funcoes->get_array());
				$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
				->setMensagem($this->core->get_msg_array('erro_set_form'))->gravo_log(); // Gravo log
				exit();
			}
		}
	}





//***************************************************************************************************************************************************************
//FUNÇÃO QUE MONTA OS JAVASCRIPT DO CKEDITOR, COMBOBOX, CHANGBOX E AUTOCOMPLETE
//***************************************************************************************************************************************************************
	function monto_campos_form_js(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		//**********************************************************

		//Documentação da montagem dos campos na pasta /Documentos/doc.php ****-

		//MONTO COMBOBOX USUARIOS
	    $this->funcoes->set_array(0,'tipo','combobox'); // Tipo
	    $this->funcoes->set_array(0,'tipo_combobox','nome'); // tipo combobox
	    $this->funcoes->set_array(0,'id_combobox','adm_usuario'); // id do campo
	    $this->funcoes->set_array(0,'txt_plural_combobox','Usuários'); // Texto no plural
	    $this->funcoes->set_array(0,'txt_sing_combobox','Usuário'); // Texto no singular

		//MONTO COMBOBOX MODULO
	    $this->funcoes->set_array(1,'tipo','combobox'); // Tipo
	    $this->funcoes->set_array(1,'tipo_combobox','modulo'); // tipo combobox
	    $this->funcoes->set_array(1,'id_combobox','adm_usuario_modulo'); // id do campo
	    $this->funcoes->set_array(1,'txt_plural_combobox','Módulos'); // Texto no plural
	    $this->funcoes->set_array(1,'txt_sing_combobox','Módulo'); // Texto no singular

		//MONTO COMBOBOX PERMISSÃO
		$this->funcoes->set_array(2,'tipo','combobox'); // Tipo
		$this->funcoes->set_array(2,'tipo_combobox','acoes'); // tipo combobox
		$this->funcoes->set_array(2,'id_combobox','adm_usuario_permissoes_acoes'); // id do campo
		$this->funcoes->set_array(2,'txt_plural_combobox','Permissões'); // Texto no plural
		$this->funcoes->set_array(2,'txt_sing_combobox','Permissão'); // Texto no singular

		//MONTO AUTOCOMPLETE
		$this->funcoes->set_array(3,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(3,'tipo_autocomplete','nome'); // tipo autocomplete
		$this->funcoes->set_array(3,'id_autocomplete','pesq_avanc_usuario'); // id do campo

	    //MOSTRO NA TELA
	    $array = $this->funcoes->get_array();
	    if(!empty($array)){
		    echo json_encode($array); //Mostro na tela
	    }else{
		    echo json_encode(array('null')); //Mostro na tela
	    }
	}





//***************************************************************************************************************************************************************
//FUNÇÃO QUE MONTA OUTROS JAVASCRIPT
//***************************************************************************************************************************************************************
	function monto_outros_javascript(){
	   $this->autentico_usuario(); //Autentico usuário no sistema
	   //**********************************************************

	   //OUTRAS LISTAGENS
	   /*$this->funcoes->set_array(0,'tipo','listagem'); // Tipo
	   $this->funcoes->set_array(0,'param_listagem',''); // parametro listagem*/

	   //MOSTRO NA TELA
	   $array = $this->funcoes->get_array();
	   if(!empty($array)){
		   echo json_encode($array); //Mostro na tela
	   }else{
		   echo json_encode(array('null')); //Mostro na tela
	   }
	}





//***************************************************************************************************************************************************************
//FUNÇÃO QUE MONTA UM CAMPO DE AUTOCOMPLETE COM DADOS DO BANCO DE DADOS
//***************************************************************************************************************************************************************
	function autocomplete(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		//**********************************************************

		//===========================================================
		//RECEBO OS DADOS
		$tipo  = $this->funcoes->anti_injection($_GET['tipo']); // campo do autocomplete
		$valor = $this->funcoes->anti_injection($_GET['term']); // valor pesquisado

		//===========================================================
		//AUTOCOMPLETE NIVEL
		if($tipo == 'nivel'){
			$this->model->setCampos('campo_tabela',"adm_usuario_nivel");
			$this->model->setCampos('campo_coluna',"{$tipo}");
			$this->model->setCampos('campo_where',"{$tipo} LIKE '%{$valor}%'");
			$this->model->setCampos('campo_groupby',"{$tipo}");
			$this->model->setCampos('campo_orderby',"");
			$valor0 = $this->model->select_simples_retorna_array_mont();
			if(!empty($valor0)){
				echo json_encode($valor0);
			}
			$this->model->getLimpoCampos();
		}

		//===========================================================
		//AUTOCOMPLETE NIVEL
		if($tipo == 'nome'){
			$this->model->setCampos('campo_tabela',"adm_usuario");
			$this->model->setCampos('campo_coluna',"{$tipo}");
			$this->model->setCampos('campo_where',"{$tipo} LIKE '%{$valor}%'");
			$this->model->setCampos('campo_groupby',"{$tipo}");
			$this->model->setCampos('campo_orderby',"");
			$valor0 = $this->model->select_simples_retorna_array_mont();
			if(!empty($valor0)){
				echo json_encode($valor0);
			}
			$this->model->getLimpoCampos();
		}



	}





//***************************************************************************************************************************************************************
//FUNÇÃO QUE MONTA OS COMBOBOX DO BANCO DE DADOS
//***************************************************************************************************************************************************************
	function combobox(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		//**********************************************************

		//===========================================================
		//RECEBO OS DADOS
		$tipo = $this->funcoes->anti_injection($_GET['tipo']); // tipo

		//===========================================================
		//COMBOBOX NÍVEL
		if($tipo == 'nome'){
			$this->model->setCampos('campo_tabela',"adm_usuario");
			$this->model->setCampos('campo_coluna',"id");
			$this->model->setCampos('campo_coluna2',"{$tipo}");
			$this->model->setCampos('campo_where'," id != 1");
			$this->model->setCampos('campo_orderby',"");
			$valor = $this->model->select_simples_retorna_array_mont_vcol();
			for ($i=0; $i < count($valor) ; $i++) {
				$array[] = array(
					'campo1' => $valor[$i]['id'],
					'campo2' => $valor[$i][$tipo],
				);
			}
			if(!empty($valor)){
				echo json_encode($array);
			}
			$this->model->getLimpoCampos();
		}

		//===========================================================
		//COMBOBOX MODULO
		if($tipo == 'modulo'){
			$this->model->setCampos('campo_tabela',"adm_usuario_modulo");
			$this->model->setCampos('campo_coluna',"id");
			$this->model->setCampos('campo_coluna2',"{$tipo}");
			$this->model->setCampos('campo_where',"");
			$this->model->setCampos('campo_orderby',"");
			$valor = $this->model->select_simples_retorna_array_mont_vcol();
			for ($i=0; $i < count($valor) ; $i++) {
				$array[] = array(
					'campo1' => $valor[$i]['id'],
					'campo2' => $valor[$i][$tipo],
				);
			}
			if(!empty($valor)){
				echo json_encode($array);
			}
			$this->model->getLimpoCampos();
		}

		//===========================================================
		//COMBOBOX PERMISSAO
		if($tipo == 'acoes'){
			$this->model->setCampos('campo_tabela',"adm_usuario_permissoes_acoes");
			$this->model->setCampos('campo_coluna',"id");
			$this->model->setCampos('campo_coluna2',"{$tipo}");
			$this->model->setCampos('campo_where',"");
			$this->model->setCampos('campo_orderby',"");
			$valor = $this->model->select_simples_retorna_array_mont_vcol();
			for ($i=0; $i < count($valor) ; $i++) {
				$array[] = array(
					'campo1' => $valor[$i]['id'],
					'campo2' => $valor[$i][$tipo],
				);
			}
			if(!empty($valor)){
				echo json_encode($array);
			}
			$this->model->getLimpoCampos();
		}

	}





//***************************************************************************************************************************************************************
//FUNÇÃO QUE MONTA OS CHANGCOMBOBOX DO BANCO DE DADOS
//***************************************************************************************************************************************************************
	function changcombobox(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		//**********************************************************

		//===========================================================
		//RECEBO OS DADOS
		$tipo  = $this->funcoes->anti_injection($_GET['tipo']); // tipo
		$param = $this->funcoes->anti_injection($_GET['param']); // recebo id para comparação

		//===========================================================
		//CHANGCOMBOBOX CIDADE
		if($tipo == 'cidade'){
			$this->model->setCampos('campo_tabela',"local_cidade");
			$this->model->setCampos('campo_coluna',"id");
			$this->model->setCampos('campo_coluna2',"{$tipo}");
			$this->model->setCampos('campo_where'," estado_id = {$param} ");
			$this->model->setCampos('campo_orderby',"");
			$valor = $this->model->select_simples_retorna_array_mont_vcol();
			for ($i=0; $i < count($valor) ; $i++) {
				$array[] = array(
					'campo1' => $valor[$i]['id'],
					'campo2' => $valor[$i][$tipo],
				);
			}
			if(!empty($valor)){
				echo json_encode($array);
			}
			$this->model->getLimpoCampos();
		}

	}





//***************************************************************************************************************************************************************
//FUNÇÃO DE ADICIONAR E REMOVER REGISTROS
//***************************************************************************************************************************************************************
	function add_edd(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		$this->carrego_parametros(); //Carrego parametros gerais
		//**********************************************************

		//===========================================================
		//RECEBO OS DADOS ****-
        $id        = $this->funcoes->anti_injection($_POST['id']); //Padrão
        $op_salvar = $this->funcoes->anti_injection($_POST['op_salvar']); //Padrão
		(int) $adm_usuario            = $this->funcoes->anti_injection($_POST['adm_usuario']);
		(int) $adm_usuario_modulo           = $this->funcoes->anti_injection($_POST['adm_usuario_modulo']);
		(int) $adm_usuario_permissoes_acoes = $this->funcoes->anti_injection($_POST['adm_usuario_permissoes_acoes']);
		(int) $opcoes                       = $this->funcoes->anti_injection($_POST['opcoes']);

		//*************************************************************************************
		//AÇÃO DE ADICIONAR
		//*************************************************************************************
		if(empty($id)){
			if($this->btns_acoes['status_btn_novo'] === false){ // Verifico se tem permissão de acesso a função
				$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
				->setMensagem($this->core->get_msg_array('funcao_acesso_negado','add()'))->gravo_log(); // Gravo log
				exit();
			}


			//===========================================================
			//VALIDO OS DADOS ****-
      		$this->funcoes->set('Usuário',"adm_usuario", $adm_usuario)->is_required()->is_num()->max_length(11);
			$this->funcoes->set('Módulo',"adm_usuario_modulo", $adm_usuario_modulo)->is_required()->is_num()->max_length(11);
			$this->funcoes->set('Permissão',"adm_usuario_permissoes_acoes", $adm_usuario_permissoes_acoes)->is_required()->is_num()->max_length(11);
			$this->funcoes->set('Ação',"opcoes", $opcoes)->is_required()->is_num()->max_length(11);

			//===========================================================
			//MOSTRO MENSAGEM DE ERROS NA TELA
			if(count($this->funcoes->get_errors())>=1){
				echo json_encode($this->funcoes->get_errors());
				exit;
			}

			//VALIDO SE TIVER CAMPO JA CADASTRADO
			if($this->model->retorn_campo_editar('adm_usuario_id', $adm_usuario) == $adm_usuario && $this->model->retorn_campo_editar('adm_usuario_modulo_id', $adm_usuario_modulo) == $adm_usuario_modulo && $this->model->retorn_campo_editar('adm_usuario_permissoes_acoes_id', $adm_usuario_permissoes_acoes) == $adm_usuario_permissoes_acoes ){
				$this->funcoes->set_array(null,'erro','Ação já cadastrada no banco de dados!');
				echo json_encode($this->funcoes->get_array());
				exit;
			}

			//INVERTO DADOS
			if($opcoes == 1){ // nao permitido
				$opcoes = 0;
			}
			if($opcoes == 2){ // permitido
				$opcoes = 1;
			}

			//SE PERMISSÃO FOR INTERFACE - NADA PERMITIDO
			if($adm_usuario_permissoes_acoes == 17 && $opcoes == 0){

				//===========================================================
				//SETO OS DADOS ****-
				$this->model->setCampos('adm_usuario_id',$adm_usuario);
				$this->model->setCampos('adm_usuario_modulo_id',$adm_usuario_modulo);
				$this->model->setCampos('opcoes',$opcoes);

				$exec = $this->model->inserir_tudo();
				//INCLUDE - mostro mensagem de sucesso e erro
				require $this->core->includeControllerInclude("add_1", $this->dir_app);
				exit;
			}

			//SE PERMISSÃO FOR INTERFACE - TUDO PERMITIDO
			if($adm_usuario_permissoes_acoes == 17 && $opcoes == 1){

				//===========================================================
				//SETO OS DADOS ****-
				$this->model->setCampos('adm_usuario_id',$adm_usuario);
				$this->model->setCampos('adm_usuario_modulo_id',$adm_usuario_modulo);
				$this->model->setCampos('opcoes',$opcoes);

				$exec = $this->model->inserir_tudo();
				//INCLUDE - mostro mensagem de sucesso e erro
				require $this->core->includeControllerInclude("add_1", $this->dir_app);
				exit;
			}

			//===========================================================
	        //SETO OS DADOS ****-
			$this->model->setCampos('adm_usuario_id',$adm_usuario);
			$this->model->setCampos('adm_usuario_modulo_id',$adm_usuario_modulo);
			$this->model->setCampos('adm_usuario_permissoes_acoes_id',$adm_usuario_permissoes_acoes);
			$this->model->setCampos('opcoes',$opcoes);

			//===========================================================
	        //EXECUTO
	        $exec = $this->model->inserir();
			//INCLUDE - mostro mensagem de sucesso e erro
			require $this->core->includeControllerInclude("add_1", $this->dir_app);


		//*************************************************************************************
		//AÇÃO DE EDITAR
		//*************************************************************************************
		}else{
			if($this->btns_acoes['status_btn_editar'] === false){ // Verifico se tem permissão de acesso a função
				$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
				->setMensagem($this->core->get_msg_array('funcao_acesso_negado','edd()'))->gravo_log(); // Gravo log
				exit();
			}


			//===========================================================
			//VALIDO OS DADOS ****-
			if(!is_numeric($id)){ // verifico se id é numérico
				$this->funcoes->set_array(null,'erro',$this->core->get_msg_array('is_num',$id));
				echo json_encode($this->funcoes->get_array());
				$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
				->setMensagem($this->core->get_msg_array('is_num',$id))->gravo_log(); // Gravo log
				exit();
			}
			$this->funcoes->set('Usuário',"adm_usuario", $adm_usuario)->is_required()->is_num()->max_length(11);
			$this->funcoes->set('Módulo',"adm_usuario_modulo", $adm_usuario_modulo)->is_required()->is_num()->max_length(11);
			$this->funcoes->set('Permissão',"adm_usuario_permissoes_acoes", $adm_usuario_permissoes_acoes)->is_required()->is_num()->max_length(11);
			$this->funcoes->set('Ação',"opcoes", $opcoes)->is_required()->is_num()->max_length(11);

			//===========================================================
			//MOSTRO MENSAGEM DE ERROS NA TELA
			if(count($this->funcoes->get_errors())>=1){
				echo json_encode($this->funcoes->get_errors());
				exit;
			}

			//VALIDO SE TIVER CAMPO JA CADASTRADO
			$this->model->setId($id);
			if($this->model->retorn_campo_editar_val_id('adm_usuario_id') != $adm_usuario &&  $this->model->retorn_campo_editar_val_id('adm_usuario_modulo_id') != $adm_usuario_modulo && $this->model->retorn_campo_editar_val_id('adm_usuario_permissoes_acoes_id') != $adm_usuario_permissoes_acoes){
				if($this->model->retorn_campo_editar('adm_usuario_id', $adm_usuario) == $adm_usuario && $this->model->retorn_campo_editar('adm_usuario_modulo_id', $adm_usuario_modulo) == $adm_usuario_modulo && $this->model->retorn_campo_editar('adm_usuario_permissoes_acoes_id', $adm_usuario_permissoes_acoes) == $adm_usuario_permissoes_acoes ){
					$this->funcoes->set_array(null,'erro','Ação já cadastrada no banco de dados!');
					echo json_encode($this->funcoes->get_array());
					exit;
				}
			}

			//INVERTO DADOS
			if($opcoes == 1){ // nao permitido
				$opcoes = 0;
			}
			if($opcoes == 2){ // permitido
				$opcoes = 1;
			}

			//SE PERMISSÃO FOR INTERFACE - NADA PERMITIDO
			if($adm_usuario_permissoes_acoes == 17 && $opcoes == 0){

				//===========================================================
				//SETO OS DADOS ****-
				$this->model->setId($id);
				$this->model->setCampos('adm_usuario_id',$adm_usuario);
				$this->model->setCampos('adm_usuario_modulo_id',$adm_usuario_modulo);
				$this->model->setCampos('opcoes',$opcoes);

				$exec = $this->model->inserir_tudo();
				//INCLUDE - mostro mensagem de sucesso e erro
				require $this->core->includeControllerInclude("add_1", $this->dir_app);
				exit;
			}

			//SE PERMISSÃO FOR INTERFACE - TUDO PERMITIDO
			if($adm_usuario_permissoes_acoes == 17 && $opcoes == 1){

				//===========================================================
				//SETO OS DADOS ****-
				$this->model->setId($id);
				$this->model->setCampos('adm_usuario_id',$adm_usuario);
				$this->model->setCampos('adm_usuario_modulo_id',$adm_usuario_modulo);
				$this->model->setCampos('opcoes',$opcoes);

				$exec = $this->model->inserir_tudo();
				//INCLUDE - mostro mensagem de sucesso e erro
				require $this->core->includeControllerInclude("add_1", $this->dir_app);
				exit;
			}

			//===========================================================
	        //SETO OS DADOS ****-
			$this->model->setId($id);
			$this->model->setCampos('adm_usuario_id',$adm_usuario);
			$this->model->setCampos('adm_usuario_modulo_id',$adm_usuario_modulo);
			$this->model->setCampos('adm_usuario_permissoes_acoes_id',$adm_usuario_permissoes_acoes);
			$this->model->setCampos('opcoes',$opcoes);

			//===========================================================
			//EXECUTO
			$exec = $this->model->editar();
			//INCLUDE - mostro mensagem de sucesso e erro
			require $this->core->includeControllerInclude("edd_1", $this->dir_app);
		}
	}





//***************************************************************************************************************************************************************
//FUNÇÃO DE DETALHAMENTO DE CONTEÚDO
//***************************************************************************************************************************************************************
	function detalho_conteudo(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		$this->carrego_parametros(); // Carrego parametros gerais
		if($this->btns_acoes['status_btn_detalhamento'] === false){ // Verifico se tem permissão de acesso a função
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('funcao_acesso_negado','detalho_conteudo()'))->gravo_log(); // Gravo log
			exit();
		}
		//**********************************************************

		//INSTANCIO A VIEW E O HELPER DE EXPORTAR
		$this->core->includeView();
		$this->core->includeInc('mpdf/mpdf.php');
	    $view = new view($this->dir_app);
		$mpdf = new mPDF();

		//===========================================================
		//RECEBO PARAMETROS E VERIFICO
		$id  = $this->funcoes->anti_injection($_GET['id']);
		$exp = $this->funcoes->anti_injection($_GET['exp']);
		if(!is_numeric($id)){ // verifico se id é numérico
			echo json_encode($this->core->get_msg_array('is_num',$id));
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('is_num',$id))->gravo_log(); // Gravo log
			exit();
		}

		//===========================================================
		//SETO OS DADOS E EXECUTO
		$this->model->setId($id);
		$exec = $this->model->detalhamento();
		if(count($exec)>=1){

			//SETO PARAMETROS GERAIS
            $interface['titulo']            = $this->nome_pagina_singular; // ****-
            $interface['icon_titulo']       = $this->btns_acoes['class_icone_detalhamento'];
            $interface['action_exp_pdf']    = $this->cmds['action_detalhamento2'];
            $interface['url_pagina']        = $this->url_pagina;
            $interface['id']                = $id;
            $interface['array']             = $exec; //ARRAY
            $interface['status_btn_editar'] = $this->btns_acoes['status_btn_editar'];
			$interface['path']              = $this->core->get_config('path_template_comp_' . $this->dir_app.'_apps'); //Diretório raiz até assets
			$interface['path_raiz']         = $this->core->get_config('dir_raiz_http').'/'; //Diretório raiz
			$interface['path_atual']        = $this->core->get_config('dir_raiz_http').$this->dir_app."/";
			$interface['id_cargo']          = $_SESSION[$this->dir_app.'_id_cargo'];


			//SETO MODO DE EXIBIÇÃO OU EXPORTAÇÃO
			if($exp == 'true'){
				$interface['op_exibicao'] = 'exportar';
			}else{
				$interface['op_exibicao'] = 'exibir';
			}

			//SETO OS DADOS
			$view->seto_dados_array($interface);
			//MOSTRO NA TELA
			if($exp == ''){
				echo json_encode($view->retorno_template_php('modulos/geral/detalho/detalho_'.$this->url_pagina.'.phtml'));
			//EXPORTO PARA PDF
			}else{
				$mpdf->SetDisplayMode('fullpage');
				//$css = file_get_contents("css/estilo.css");
				//$mpdf->WriteHTML($css,1);
				$mpdf->WriteHTML($view->retorno_template_php('modulos/geral/detalho/detalho_'.$this->url_pagina.'.phtml'));
				$mpdf->Output();
			}
		}
	}





//***************************************************************************************************************************************************************
//FUNÇÃO DE EXPORTAR REGISTRO PARA PDF, CSV E IMPRIMIR
//***************************************************************************************************************************************************************
	function exp_imprimir_reg(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		$this->carrego_parametros(); // Carrego parametros gerais
		//**********************************************************

		//INCLUDE
		require $this->core->includeControllerInclude("exp_imprimir_reg_1", $this->dir_app);
	}





//***************************************************************************************************************************************************************
//FUNÇÃO DE ATIVAR E DESATIVAR REGISTRO
//***************************************************************************************************************************************************************
	function ativar_desativar(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		$this->carrego_parametros(); // Carrego parametros gerais
		if($this->btns_acoes['status_btn_ativar'] === false){ // Verifico se tem permissão de acesso a função
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('funcao_acesso_negado','ativar_desativar()'))->gravo_log(); // Gravo log
			exit();
		}
		//**********************************************************

		//PARAMETROS DA CONSULTA
		//$this->model->setCampos('tabela',''); //tabela
		//$this->model->setCampos('campo_id',''); //campo id

		//===========================================================
		//INCLUDE - Recebo parametros e trato
		require $this->core->includeControllerInclude("ativar_desativar_1", $this->dir_app);

		//===========================================================
		//VERIFICO STATUS ID RECEBIDO E INVERTO VALORES
		if($status_id == 1){
			$status_id_ = 2; // status_id
			$texto1     = $this->btns_acoes['txt_btn_desativar']; // texto do botão desativar
			$texto2     = $this->btns_acoes['txt_desativar_pret_plural']; // texto no plural
			$texto3     = $this->btns_acoes['txt_desativar_pret_sing']; // texto no singular
		}
		if($status_id == 2){
			$status_id_ = 1; // status_id
			$texto1     = $this->btns_acoes['txt_btn_ativar']; // texto do botão ativar
			$texto2     = $this->btns_acoes['txt_ativar_pret_plural']; // texto no plural
			$texto3     = $this->btns_acoes['txt_ativar_pret_sing']; // texto no singular
		}

		//===========================================================
		//INCLUDE - Executo e mostro mensagem
		require $this->core->includeControllerInclude("ativar_desativar_2", $this->dir_app);
	}





//***************************************************************************************************************************************************************
//FUNÇÃO DE EXCLUIR REGISTRO
//***************************************************************************************************************************************************************
	function excluir(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		$this->carrego_parametros(); // Carrego parametros gerais
		if($this->btns_acoes['status_btn_excluir'] === false){ // Verifico se tem permissão de acesso a função
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('funcao_acesso_negado','excluir()'))->gravo_log(); // Gravo log
			exit();
		}
		//**********************************************************

		//PARAMETROS DA CONSULTA
		//$this->model->setCampos('tabela',''); //tabela
		//$this->model->setCampos('campo_id',''); //campo id

		//===========================================================
		//INCLUDE - Recebo e trato os dados
		require $this->core->includeControllerInclude("excluir_1", $this->dir_app);

		//===========================================================
		//EXECUTO O MODEL
		if(is_array($id)){ // verifico se $id é array
			for ($i=0; $i < $total_ids; $i++) {

				//VERIFICO SE PODE EXCLUIR
				//Documentação da montagem dos campos na pasta /Documentos/doc.php ****-

				//EXCLUIR ARQUIVO DO SERVIDOR
				//Documentação da montagem dos campos na pasta /Documentos/doc.php ****-

				//SETO OS DADOS
				$this->model->setId($id[$i]);

				//EXECUTO
				require $this->core->includeControllerInclude("excluir_3", $this->dir_app);
		   }
		}else{
			$this->funcoes->set_array(null,'erro',$this->core->get_msg_array('is_arr',$id)); // Mensagem de erro $id não é um array
			echo json_encode($this->funcoes->get_array());
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('is_arr',$id))->gravo_log(); // Gravo log
			exit();
		}

		//===========================================================
		//INCLUDE - Mostro mensagens de erros e sucesso
		require $this->core->includeControllerInclude("excluir_2", $this->dir_app);
	}





//***************************************************************************************************************************************************************
//FUNÇÃO DE EXCLUIR TODOS OS REGISTROS DE UMA TABELA
//***************************************************************************************************************************************************************
	function cmd_excluir_tudo(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		$this->carrego_parametros(); //Carrego parametros gerais
		if($this->btns_acoes['status_btn_excluir_tudo'] === false){ // Verifico se tem permissão de acesso a função
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('funcao_acesso_negado','cmd_excluir_tudo()'))->gravo_log(); // Gravo log
			exit();
		}
		//**********************************************************

		//INCLUDE
		require $this->core->includeControllerInclude("cmd_excluir_tudo_1", $this->dir_app);
	}





//***************************************************************************************************************************************************************
//FUNÇÃO DE LISTAGEM DE ARQUIVOS UPADOS
//***************************************************************************************************************************************************************
	function listagem_arquivos(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		$this->carrego_parametros(); //Carrego parametros gerais
		if($this->btns_acoes['status_btn_editar'] === false){ // Verifico se tem permissão de acesso a função
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('funcao_acesso_negado','listagem_arquivos()'))->gravo_log(); // Gravo log
			exit();
		}
		//**********************************************************

		//===========================================================
		//RECEBO OS DADOS
		$tipo = $this->funcoes->anti_injection($_GET['tipo']); // tipo

		//===========================================================
		//LISTAGEM DE TESTE
		if($tipo == 'teste'){
			$this->model->setCampos('campo_tabela',"adm_usuario");
			$this->model->setCampos('campo_coluna',"id");
			$this->model->setCampos('campo_coluna2',"nome");
			$this->model->setCampos('campo_coluna3',"criado");
			$this->model->setCampos('campo_where',"");
			$this->model->setCampos('campo_orderby',"");
			$valor = $this->model->select_simples_retorna_array_mont_vcol();
			if(count($valor) >= 1){
				for ($i=0; $i < count($valor) ; $i++) {
					$array[] = array(
                        'id_arquivo'   => $valor[$i]['id'],
                        'path_arquivo' => $this->core->get_config('dir_raiz_http').'files/perfil/g/',
                        'url_arquivo'  => $valor[$i]['nome'],
                        'descricao'    => $valor[$i]['nome'],
					);
				}
			}
			if(!empty($valor)){
				echo json_encode($array);
			}
			$this->model->getLimpoCampos();
		}

	}





//***************************************************************************************************************************************************************
//FUNÇÃO DE EXCLUIR UM ARQUIVO UPADO
//***************************************************************************************************************************************************************
	function excluir_arquivo(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		$this->carrego_parametros(); //Carrego parametros gerais
		if($this->btns_acoes['status_btn_editar'] === false){ // Verifico se tem permissão de acesso a função
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('funcao_acesso_negado','excluir_arquivo()'))->gravo_log(); // Gravo log
			exit();
		}
		//**********************************************************

		//INCLUDE - Recebo e trato os dados
		require $this->core->includeControllerInclude("excluir_arquivo_1", $this->dir_app);

		//===========================================================
		//TIPO ....
		if($tipo == 'teste'){
			//PARAMETROS ****-
			$tabela      = ''; //tabela do bd
			$nome_pagina = ''; //nome da página

			/*//SE FOR UPLOAD ÚNICO - Atualizo o campo da tabela para vazio
			$this->model->setCampos('campo_tabela',$tabela);
			$this->model->setCampos('campo_coluna',"url_imagem"); // ****-
			$this->model->setCampos('campo_id',"id"); // ****-
			$this->model->setCampos('campo_value',""); //vazio
			$this->model->setId($id);
            $exec = $this->model->update_geral();

			//SE FOR UPLOAD MULTIPLO - Excluo o registro da tabela
			$this->model->setCampos('campo_tabela',$tabela);
			$this->model->setCampos('campo_id',"id"); // ****-
			$this->model->setId($id);
            $exec = $this->model->excluir_geral();*/

			//EXCLUO DO SERVIDOR ****-
			unlink($this->core->get_config('dir_files_comp')."/perfil/p/".$url_arquivo);
			unlink($this->core->get_config('dir_files_comp')."/perfil/m/".$url_arquivo);
			unlink($this->core->get_config('dir_files_comp')."/perfil/g/".$url_arquivo);

			//INCLUDE - Mostro os dados na tela
			require $this->core->includeControllerInclude("excluir_arquivo_2", $this->dir_app);
		}

	}





//***************************************************************************************************************************************************************
//FUNÇÃO ABERTA RETORNO MENSAGEM EM JSON
//***************************************************************************************************************************************************************
	function cmd_funcao_aberta(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		if($this->btns_acoes['status_btn_ativar'] === false){ // Verifico se tem permissão de acesso a função
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('funcao_acesso_negado','ativar_desativar()'))->gravo_log(); // Gravo log
			exit();
		}
		//**********************************************************

		//===========================================================
		//RECEBO OS DADOS
		(int) $id = $this->funcoes->anti_injection($_GET['id']); // Recebo id
	    (int) $status = $this->funcoes->anti_injection($_GET['s']); // Recebo id

	    //===========================================================
	    //TRATO PARAMETROS
        if(!is_numeric($id)){ // verifico se id é numérico
            $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('is_num',$id)); // Mensagem de erro parametro $id deve ser numérico
            echo json_encode($this->funcoes->get_array());
            $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
            ->setMensagem($this->core->get_msg_array('is_num',$id))->gravo_log(); // Gravo log
            exit();
        }
		if(!is_numeric($status)){ // verifico se id é numérico
            $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('is_num',$status)); // Mensagem de erro parametro $id deve ser numérico
            echo json_encode($this->funcoes->get_array());
            $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
            ->setMensagem($this->core->get_msg_array('is_num',$status))->gravo_log(); // Gravo log
            exit();
        }

		//===========================================================
		//INVERTO VALORES
		if($status == 0){
			$status = 1;
		}else{
			if($status == 1){
				$status = 0;
			}
		}

		//===========================================================
		//SETO E EXECUTO
		$this->model->setId($id);
		$this->model->setCampos('opcoes',$status);
		$exec = $this->model->editar_permissao();

		//===========================================================
		//MENSAGENS
		if($exec == true){
			$this->funcoes->set_array(null,'sucesso','Permissão alterada com sucesso!'); // Mensagem de sucesso
			echo json_encode($this->funcoes->get_array());
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem(' Permissão alterado com sucesso! '.$id.' ')->gravo_log(); // Gravo log
			exit;
		}else{
			$this->funcoes->set_array(null,'erro','Erro ao alterar Permissão!'); // Mensagem de erro
			echo json_encode($this->funcoes->get_array());
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem(' Erro ao alterar Permissão! '.$exec.' '.$id.' ')->gravo_log(); // Gravo log
			exit;
		}
	}





//***************************************************************************************************************************************************************
//FUNÇÃO DE LISTAGEM
//***************************************************************************************************************************************************************
	function listagem(){
		$this->autentico_usuario(); //Autentico usuário no sistema
		$this->carrego_parametros(); //Carrego parametros gerais
		//**********************************************************

		//===========================================================
		//CONFIGURAÇÕES GERAIS ****-
		$qtd_reg_listagem                  = $this->btns_acoes['qtd_reg_listagem']; // Quantidade de registros p/ página
		$interface['param_de_pesquisa']    = $this->btns_acoes['title_campo_pesquisa']; // Parametros de pesquisa
		$ordenar_selecionado               = $this->btns_acoes['ordenar_selecionado']; // Value padrão do campo Odernar
		$agrupar_selecionado               = $this->btns_acoes['agrupar_selecionado']; // Value padrão do campo Agrupar
		$limit                             = $this->btns_acoes['status_limit']; // Limit da tabela [ true or false ]
		$funcao_listagem                   = $this->btns_acoes['funcao_listagem']; // Função de listagem
		$interface['mensagem_informativa'] = $this->btns_acoes['mensagem_informativa']; // Mensagem informativa relacionada a tabela

		//===========================================================
		//CONFIGURAÇÕES DE OUTRAS LISTAGENS ****-
		$pref                             = ""; // param de outras listagens
		$interface['titulo_tabela']       = $this->nome_pagina_plural; // Titulo da tabela
		$interface['icone_titulo_tabela'] = $this->btns_acoes['class_icone_listagem']; // Classe icone do Titulo da tabela
		$interface['param_out_list']      = $pref; //param de outras listagens
		$tabela                           = ""; // Tabela de outras listagens

		//===========================================================
		//PEGO OS PARAMETROS HTML
		$conteudo_html = $this->monto_conteudo_html_pagina('listagem');

		//===========================================================
		//RECEBO OS DADOS DE PESQUISA AVANÇADA ****-
        $pesq_avancada = $conteudo_html['pesq_avancada'];

		//SETO OS DADOS NA SESSÃO
		//

		//===========================================================
		//INCLUDE DE LISTAGEM RECEBO, TRATO E SETO PARAMETROS
		require $this->core->includeControllerInclude("listagem_1", $this->dir_app);

		//===========================================================
		//MONTO O MENU DE FILTROS ****-
		$interface['dropdown_filtros'] = $conteudo_html['filtros']; // Seto o array na view [Array]

		//===========================================================
		//MONTO OS OPTION DE ORDENAR ****-
		$interface['option_ordenar'] = $conteudo_html['option_ordenar']; // Seto o array na view [Array]

		//===========================================================
		//MONTO OS OPTIONS DE AGRUPAR ****-
		$interface['option_agrupar'] = $conteudo_html['option_agrupar']; // Seto o array na view [Array]

		//===========================================================
		//MONTO O HEADER DA TABELA ****-
		$interface['header_tabela'] = $conteudo_html['header_tabela']; // Seto o array na view [Array]

		//===========================================================
		//CONTEUDO MONTADO
		$conteudo_td_montado = $conteudo_html['conteudo_td_montado'];

		//===========================================================
		//INCLUDE DE LISTAGEM, EXECUTO MODEL, VIEW, TRATO DADOS
		require $this->core->includeControllerInclude("listagem_2", $this->dir_app);
	}



}
