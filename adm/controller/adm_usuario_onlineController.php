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
	$control = new adm_usuario_onlineController();  // ****-
}

//CLASS ****-
class adm_usuario_onlineController {

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
		$this->core->includeController('adm_usuario_online',$this->dir_app); //incluo model ****-

		//INSTANCIO
		$this->funcoes           = new funcoes();     //Instancio Funções
		$this->model             = new adm_usuario_onlineModel();  //Instancio Model ****-
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
				(string) $cmd = $this->funcoes->anti_injection($_GET['cmd']);
			}
			if(!empty($_POST['cmd'])){
				(string) $cmd = $this->funcoes->anti_injection($_POST['cmd']);
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
			$this->adm_usuario_modulo_id         = 4; //Id do módulo
            $this->url_pagina                    = "adm_usuario_online"; //Url da página
            $this->nome_pagina_singular          = "Usuário online"; // Nome da página singular
            $this->nome_pagina_plural            = "Usuários Online"; // Nome da página plural
            $this->btns_acoes['foco_campo_form'] = "campo_id"; // Foco campo form add e edd

			//===========================================================
			//CAMPOS DEFAULT
			require $this->core->includeControllerInclude("carrego_parametros_0", $this->dir_app);

			//===========================================================
			//TEXTOS GERAIS ****-


			//===========================================================
			//STATUS DE BTNS DE AÇÕES GERAL ****-
			$this->btns_acoes['status_btn_editar']        = false; // Status do btn de editar [true or false]
			if($_SESSION['adm_id_cargo'] == 1){
				$this->btns_acoes['status_btn_excluir'] = true; // Status de btn excluir tudo
			}else{
				$this->btns_acoes['status_btn_excluir'] = false; // Status de btn excluir tudo
			}
			$this->btns_acoes['status_btn_detalhamento']  = true; // Status do btn de detalhamento [true or false]
			$this->btns_acoes['status_btn_ativar']        = false; // Status do btn de ativar [true or false]
			$this->btns_acoes['status_btn_novo']          = false; // Status de btn de cadastro [true or false]
			$this->btns_acoes['status_btn_atualizar']     = true; // Status de btn de atualizar [true or false]
			$this->btns_acoes['status_imprimir']          = true; // Status de btn de imprimir [true or false]
			$this->btns_acoes['status_exportar_pdf']      = true; // Status de btn de exportar para pdf [true or false]
			$this->btns_acoes['status_exportar_csv']      = false; // Status de btn de exportar para csv [true or false]
			if($_SESSION['adm_id_cargo'] == 1){
				$this->btns_acoes['status_btn_excluir_tudo'] = true; // Status de btn excluir tudo
			}else{
				$this->btns_acoes['status_btn_excluir_tudo'] = false; // Status de btn excluir tudo
			}

			//===========================================================
			//STATUS DE ÁREA DA LISTAGEM ****-
			$this->btns_acoes['status_btn_filtros']            = true; // Status do botão de filtros [true or false]

			//===========================================================
			//CONFIGURAÇÕES DE ÁREA DA LISTAGEM ****-
            $this->btns_acoes['title_campo_pesquisa'] = 'Usuário'; // Parametros de pesquisa
            $this->btns_acoes['ordenar_selecionado']  = array("ID DECRESCENTE"); // Value padrão do campo Odernar
            $this->btns_acoes['agrupar_selecionado']  = array(""); // Value padrão do campo Agrupar
            $this->btns_acoes['mensagem_informativa'] = ''; // Mensagem informativa relacionada a tabela

			//===========================================================
			//STATUS DE ÁREA DOS BOTÕES DE LISTAGEM DEFAULT


			//===========================================================
			//TEXTO DE BTNS DE AÇÕES ****-


			//===========================================================
			//TITLE DE BTNS DE AÇÕES ****-


			//===========================================================
			//CLASSE DE ICONES DE BTNS DE AÇÕES ****-
			$this->btns_acoes['class_icone_listagem']     = "fa fa-circle text-success"; // Classe do icone da listagem

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

				//

				//===========================================================
				//MONTO CAMPOS E ÁREAS
				$mont_html->set_array(0,'titulo','Titulo ');
				$mont_html->set_array(0,'icone_titulo',''); // Classe icone - Font Awesome Icons
				$mont_html->set_array(0,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(0,'conteudo',"  "); //conteudo ***
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
				$this->funcoes->set_array(0,'input_id','usuario');
				$this->funcoes->set_array(0,'input_name','campos_pdf[]');
				$this->funcoes->set_array(0,'input_class','');
				$this->funcoes->set_array(0,'input_title','');
				$this->funcoes->set_array(0,'input_value','usuario'); //value do campo
				$this->funcoes->set_array(0,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(0,'input_texto_checkbox','Usuario'); //Texto
				$this->funcoes->set_array(1,'input_id','ip');
				$this->funcoes->set_array(1,'input_name','campos_pdf[]');
				$this->funcoes->set_array(1,'input_class','');
				$this->funcoes->set_array(1,'input_title','');
				$this->funcoes->set_array(1,'input_value','ip'); //value do campo
				$this->funcoes->set_array(1,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(1,'input_texto_checkbox','IP'); //Texto
				$this->funcoes->set_array(2,'input_id','so');
				$this->funcoes->set_array(2,'input_name','campos_pdf[]');
				$this->funcoes->set_array(2,'input_class','');
				$this->funcoes->set_array(2,'input_title','');
				$this->funcoes->set_array(2,'input_value','so'); //value do campo
				$this->funcoes->set_array(2,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(2,'input_texto_checkbox','SO'); //Texto
				$this->funcoes->set_array(3,'input_id','navegador');
				$this->funcoes->set_array(3,'input_name','campos_pdf[]');
				$this->funcoes->set_array(3,'input_class','');
				$this->funcoes->set_array(3,'input_title','');
				$this->funcoes->set_array(3,'input_value','navegador'); //value do campo
				$this->funcoes->set_array(3,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(3,'input_texto_checkbox','Navegador'); //Texto
				$this->funcoes->set_array(4,'input_id','idioma');
				$this->funcoes->set_array(4,'input_name','campos_pdf[]');
				$this->funcoes->set_array(4,'input_class','');
				$this->funcoes->set_array(4,'input_title','');
				$this->funcoes->set_array(4,'input_value','idioma'); //value do campo
				$this->funcoes->set_array(4,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(4,'input_texto_checkbox','Idioma'); //Texto
				$this->funcoes->set_array(4,'input_id','hora');
				$this->funcoes->set_array(4,'input_name','campos_pdf[]');
				$this->funcoes->set_array(4,'input_class','');
				$this->funcoes->set_array(4,'input_title','');
				$this->funcoes->set_array(4,'input_value','hora'); //value do campo
				$this->funcoes->set_array(4,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(4,'input_texto_checkbox','Ultima hora'); //Texto
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
				$this->funcoes->set_array(0,'input_id','usuario');
				$this->funcoes->set_array(0,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(0,'input_class','');
				$this->funcoes->set_array(0,'input_title','');
				$this->funcoes->set_array(0,'input_value','usuario'); //value do campo
				$this->funcoes->set_array(0,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(0,'input_texto_checkbox','Usuario'); //Texto
				$this->funcoes->set_array(1,'input_id','ip');
				$this->funcoes->set_array(1,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(1,'input_class','');
				$this->funcoes->set_array(1,'input_title','');
				$this->funcoes->set_array(1,'input_value','ip'); //value do campo
				$this->funcoes->set_array(1,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(1,'input_texto_checkbox','IP'); //Texto
				$this->funcoes->set_array(2,'input_id','so');
				$this->funcoes->set_array(2,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(2,'input_class','');
				$this->funcoes->set_array(2,'input_title','');
				$this->funcoes->set_array(2,'input_value','so'); //value do campo
				$this->funcoes->set_array(2,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(2,'input_texto_checkbox','SO'); //Texto
				$this->funcoes->set_array(3,'input_id','navegador');
				$this->funcoes->set_array(3,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(3,'input_class','');
				$this->funcoes->set_array(3,'input_title','');
				$this->funcoes->set_array(3,'input_value','navegador'); //value do campo
				$this->funcoes->set_array(3,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(3,'input_texto_checkbox','Navegador'); //Texto
				$this->funcoes->set_array(4,'input_id','idioma');
				$this->funcoes->set_array(4,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(4,'input_class','');
				$this->funcoes->set_array(4,'input_title','');
				$this->funcoes->set_array(4,'input_value','idioma'); //value do campo
				$this->funcoes->set_array(4,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(4,'input_texto_checkbox','Idioma'); //Texto
				$this->funcoes->set_array(4,'input_id','hora');
				$this->funcoes->set_array(4,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(4,'input_class','');
				$this->funcoes->set_array(4,'input_title','');
				$this->funcoes->set_array(4,'input_value','hora'); //value do campo
				$this->funcoes->set_array(4,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(4,'input_texto_checkbox','Ultima hora'); //Texto
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
				$mont_html->set_array(null,'area_descricao','Períodos')->set_array(null,'area_descricao_icon_class','fa fa-calendar')->set_array(null,'class',''); // Classes úteis = (subtitulo-margin_top) -Classe icone - Font Awesome Icons
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

				//
				//MONTO CAMPO ÁREA DESCRIÇÃO 2
				$mont_html->set_array(null,'area_descricao','Outras Informações')->set_array(null,'area_descricao_icon_class','fa fa-list-ul')->set_array(null,'class','subtitulo-margin_top'); // Classes úteis = (subtitulo-margin_top) -Classe icone - Font Awesome Icons
				$area_descricao2 = $mont_html->monto_html_area_descricao();

				//CAMPO USUARIO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-5 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
				$mont_html->set_array(null,'label_for_texto','Usuário');
				$mont_html->set_array(null,'input_type','text');
				$mont_html->set_array(null,'input_name','pesq_avanc_usuario');
				$mont_html->set_array(null,'input_id','pesq_avanc_usuario');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o nome do usuario');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_usuario']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$usuario = $mont_html->monto_html_input();

				//CAMPO ACAO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
				$mont_html->set_array(null,'label_for_texto','Hora ');
				$mont_html->set_array(null,'input_type','text');
				$mont_html->set_array(null,'input_name','pesq_avanc_hora');
				$mont_html->set_array(null,'input_id','pesq_avanc_hora');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite a ação');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_hora']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$acao = $mont_html->monto_html_input();

				//CAMPO IP
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
				$mont_html->set_array(null,'label_for_texto','IP ');
				$mont_html->set_array(null,'input_type','text');
				$mont_html->set_array(null,'input_name','pesq_avanc_ip');
				$mont_html->set_array(null,'input_id','pesq_avanc_ip');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o IP');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_ip']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$ip = $mont_html->monto_html_input();

				//CAMPO SO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
				$mont_html->set_array(null,'label_for_texto','SO ');
				$mont_html->set_array(null,'input_type','text');
				$mont_html->set_array(null,'input_name','pesq_avanc_so');
				$mont_html->set_array(null,'input_id','pesq_avanc_so');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o SO');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_so']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$so = $mont_html->monto_html_input();

				//CAMPO NAVEGADOR
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
				$mont_html->set_array(null,'label_for_texto','Navegador ');
				$mont_html->set_array(null,'input_type','text');
				$mont_html->set_array(null,'input_name','pesq_avanc_navegador');
				$mont_html->set_array(null,'input_id','pesq_avanc_navegador');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o navegador');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_navegador']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$navegador = $mont_html->monto_html_input();

				//CAMPO IDIOMA
				$mont_html->set_array(null,'input_class_tamanho','col-lg-1 col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
				$mont_html->set_array(null,'label_for_texto','Idioma ');
				$mont_html->set_array(null,'input_type','text');
				$mont_html->set_array(null,'input_name','pesq_avanc_idioma');
				$mont_html->set_array(null,'input_id','pesq_avanc_idioma');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o idioma');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_idioma']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$idioma = $mont_html->monto_html_input();
				//

				//===========================================================
				//MONTO CAMPOS
				$mont_html->set_array(0,'conteudo'," {$area_descricao} {$input_text} {$input_text2} {$select} {$area_descricao2} {$usuario} {$acao}");
				$mont_html->set_array(1,'conteudo'," {$ip} {$so} {$navegador} {$idioma}");
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
				$pesq_avanc4 = $this->funcoes->anti_injection($_POST['pesq_avanc_usuario']); // Recebo dados
				$pesq_avanc5 = $this->funcoes->anti_injection($_POST['pesq_avanc_hora']); // Recebo dados
				$pesq_avanc6 = $this->funcoes->anti_injection($_POST['pesq_avanc_ip']); // Recebo dados
				$pesq_avanc7 = $this->funcoes->anti_injection($_POST['pesq_avanc_so']); // Recebo dados
				$pesq_avanc8 = $this->funcoes->anti_injection($_POST['pesq_avanc_navegador']); // Recebo dados
				$pesq_avanc9 = $this->funcoes->anti_injection($_POST['pesq_avanc_idioma']); // Recebo dados

				//SETO OS DADOS DA PESQUISA AVANÇADA NA SESSÃO ****-
				if(!empty($pesq_avanc1)){ $pesq_avancada['periodo_tipo_data'] = $pesq_avanc1; }
				if(!empty($pesq_avanc2) && !empty($pesq_avanc1)){ $pesq_avancada['periodo_data_de'] = $pesq_avanc2; }
				if(!empty($pesq_avanc3) && !empty($pesq_avanc1)){ $pesq_avancada['periodo_data_ate'] = $pesq_avanc3; }
				if(!empty($pesq_avanc4)){ $pesq_avancada['pesq_avanc_usuario'] = $pesq_avanc4; }
				if(!empty($pesq_avanc5)){ $pesq_avancada['pesq_avanc_hora'] = $pesq_avanc5; }
				if(!empty($pesq_avanc6)){ $pesq_avancada['pesq_avanc_ip'] = $pesq_avanc6; }
				if(!empty($pesq_avanc7)){ $pesq_avancada['pesq_avanc_so'] = $pesq_avanc7; }
				if(!empty($pesq_avanc8)){ $pesq_avancada['pesq_avanc_navegador'] = $pesq_avanc8; }
				if(!empty($pesq_avanc9)){ $pesq_avancada['pesq_avanc_idioma'] = $pesq_avanc9; }

				//

				//===========================================================
				//MONTO O MENU DE FILTROS ****-
				$this->funcoes->set_array(1,'btn_status',true);
				$this->funcoes->set_array(1,'btn_texto','IP');
				$this->funcoes->set_array(1,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(1,'btn_target','');
				$this->funcoes->set_array(1,'btn_title','');
				$this->funcoes->set_array(1,'btn_id','');
				$this->funcoes->set_array(1,'btn_class','btn_param_pesquisa_focus');
				$this->funcoes->set_array(1,'btn_class_icon','');
				$this->funcoes->set_array(1,'btn_outros','param_pesquisa="IP: "');
				$this->funcoes->set_array(2,'btn_status',true);
				$this->funcoes->set_array(2,'btn_texto','SO');
				$this->funcoes->set_array(2,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(2,'btn_target','');
				$this->funcoes->set_array(2,'btn_title','');
				$this->funcoes->set_array(2,'btn_id','');
				$this->funcoes->set_array(2,'btn_class','btn_param_pesquisa_focus');
				$this->funcoes->set_array(2,'btn_class_icon','');
				$this->funcoes->set_array(2,'btn_outros','param_pesquisa="SO: "');
				$this->funcoes->set_array(3,'btn_status',true);
				$this->funcoes->set_array(3,'btn_texto','Navegador');
				$this->funcoes->set_array(3,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(3,'btn_target','');
				$this->funcoes->set_array(3,'btn_title','');
				$this->funcoes->set_array(3,'btn_id','');
				$this->funcoes->set_array(3,'btn_class','btn_param_pesquisa_focus');
				$this->funcoes->set_array(3,'btn_class_icon','');
				$this->funcoes->set_array(3,'btn_outros','param_pesquisa="Navegador: "');
				$this->funcoes->set_array(4,'btn_status',true);
				$this->funcoes->set_array(4,'btn_texto','Idioma');
				$this->funcoes->set_array(4,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(4,'btn_target','');
				$this->funcoes->set_array(4,'btn_title','');
				$this->funcoes->set_array(4,'btn_id','');
				$this->funcoes->set_array(4,'btn_class','btn_param_pesquisa_focus');
				$this->funcoes->set_array(4,'btn_class_icon','');
				$this->funcoes->set_array(4,'btn_outros','param_pesquisa="Idioma: "');
				$this->funcoes->set_array(5,'btn_status',true);
				$this->funcoes->set_array(5,'btn_texto','Hora');
				$this->funcoes->set_array(5,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(5,'btn_target','');
				$this->funcoes->set_array(5,'btn_title','');
				$this->funcoes->set_array(5,'btn_id','');
				$this->funcoes->set_array(5,'btn_class','btn_param_pesquisa_focus');
				$this->funcoes->set_array(5,'btn_class_icon','');
				$this->funcoes->set_array(5,'btn_outros','param_pesquisa="Hora: "');
				//$this->funcoes->set_array(1,'class_divider','divider'); // divider
				$filtros = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO OS OPTION DE ORDENAR ****-
				$this->funcoes->set_array(0,'value',"ID CRESCENTE");
				$this->funcoes->set_array(1,'value',"ID DECRESCENTE");
				$this->funcoes->set_array(2,'value',"separator"); //separator
				$this->funcoes->set_array(3,'value',"USUARIO CRESCENTE");
				$this->funcoes->set_array(4,'value',"USUARIO DECRESCENTE");
				$this->funcoes->set_array(5,'value',"separator"); //separator
				$this->funcoes->set_array(6,'value',"separator"); //separator
				$this->funcoes->set_array(7,'value',"HORA CRESCENTE");
				$this->funcoes->set_array(8,'value',"HORA DECRESCENTE");
				$this->funcoes->set_array(9,'value',"separator"); //separator
				$this->funcoes->set_array(10,'value',"IP CRESCENTE");
				$this->funcoes->set_array(11,'value',"IP DECRESCENTE");
				$this->funcoes->set_array(12,'value',"separator"); //separator
				$this->funcoes->set_array(13,'value',"SO CRESCENTE");
				$this->funcoes->set_array(14,'value',"SO DECRESCENTE");
				$this->funcoes->set_array(15,'value',"separator"); //separator
				$this->funcoes->set_array(16,'value',"NAVEGADOR CRESCENTE");
				$this->funcoes->set_array(17,'value',"NAVEGADOR DECRESCENTE");
				$this->funcoes->set_array(18,'value',"separator"); //separator
				$this->funcoes->set_array(19,'value',"IDIOMA CRESCENTE");
				$this->funcoes->set_array(20,'value',"IDIOMA DECRESCENTE");
				$this->funcoes->set_array(21,'value',"separator"); //separator
				$this->funcoes->set_array(22,'value',"CADASTRADO CRESCENTE");
				$this->funcoes->set_array(23,'value',"CADASTRADO DECRESCENTE");
				$this->funcoes->set_array(24,'value',"separator"); //separator
				$this->funcoes->set_array(25,'value',"MODIFICADO CRESCENTE");
				$this->funcoes->set_array(26,'value',"MODIFICADO DECRESCENTE");
				$option_ordenar = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO OS OPTIONS DE AGRUPAR ****-
				$this->funcoes->set_array(null,'name_campo','Campo');
				$option_agrupar = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO O HEADER DA TABELA ****-
				$this->funcoes->set_array(0,'th_titulo','Usuário');
				$this->funcoes->set_array(0,'th_class',' col-md-4');
				$this->funcoes->set_array(0,'th_outros','');
				$this->funcoes->set_array(1,'th_titulo','Outros');
				$this->funcoes->set_array(1,'th_class','hidden-md col-md-2');
				$this->funcoes->set_array(1,'th_outros','');
				$this->funcoes->set_array(2,'th_titulo','<i class="fa fa-sign-out"></i> Deslogar');
				$this->funcoes->set_array(2,'th_class','text-center col-md-2');
				$this->funcoes->set_array(2,'th_outros','');
				$this->funcoes->set_array(3,'th_titulo','<i class="fa fa-calendar-o"></i> Data Modificado');
				$this->funcoes->set_array(3,'th_class','hidden-md text-center col-md-2');
				$this->funcoes->set_array(3,'th_outros','');
				$this->funcoes->set_array(4,'th_titulo','<i class="fa fa-calendar-o"></i> Data Cadastro');
				$this->funcoes->set_array(4,'th_class','hidden-md text-center col-md-2');
				$this->funcoes->set_array(4,'th_outros','');
				$header_tabela = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO O CONTEUDO DA TABELA ****-

				//FILTROS SMARTY PHP
				$mont_html->set_array(0,'tipo',"data_hora"); // data, hora, data_hora, tempo, tempo_hora, enc_texto, maiusculo, minusculo, maiusc_minusc e format_num
				$mont_html->set_array(0,'campo',"[bd:criado]"); // campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'valor_enc_texto',""); // valor numérico do tamanho do texto
				$criado = $mont_html->filtros_smarty();

				//FILTROS SMARTY PHP
				$mont_html->set_array(0,'tipo',"data_hora"); // data, hora, data_hora, tempo, tempo_hora, enc_texto, maiusculo, minusculo, maiusc_minusc e format_num
				$mont_html->set_array(0,'campo',"[bd:hora]"); // campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'valor_enc_texto',""); // valor numérico do tamanho do texto
				$hora = $mont_html->filtros_smarty();

				//AÇÃO DE LINHA DA TABELA
				$mont_html->set_array(0,'status',true); // Status da ação true or false
				$mont_html->set_array(0,'class',"btn_detalhamento"); // Tipo: btn_ativar_desativar, btn_detalhamento, btn_editar ou url
				$mont_html->set_array(0,'url',""); // url de redirecionamento $this->core->get_config('dir_raiz_http').$this->dir_app."/"
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

				//===========================================================
				//CONTEUDO MONTADO
				$mont_html->set_array(0,'td'," <span class='m-r-5'>{$img_usuario}</span> [bd:nome] <i title='Online' class='fa fa-circle text-success'></i>"); // Coluna montada **
				$mont_html->set_array(0,'class'," {$acao_linha['class']}"); // Classes
				$mont_html->set_array(0,'outros'," {$acao_linha['outros']}"); //Outros parametros do td
				$mont_html->set_array(1,'td',"<strong>IP:</strong> [bd:IP]</br><strong>SO:</strong> [bd:SO]</br><strong>Navegador:</strong> [bd:navegador]</br><strong>Idioma:</strong> [bd:idioma]"); // Coluna montada **
				$mont_html->set_array(1,'class',"hidden-md  {$acao_linha['class']}"); // Classes
				$mont_html->set_array(1,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
				$mont_html->set_array(2,'td'," <i class='fa fa-sign-out'></i> Deslogar Usuário"); // Coluna montada **
				$mont_html->set_array(2,'class',"funcao_aberta text-center {$acao_linha1['class']}"); // Classes
				$mont_html->set_array(2,'outros',"param='&id=[bd:usuario_id]' {$acao_linha1['outros']}"); // Outros parametros do td
				$mont_html->set_array(3,'td',"{$hora}"); // Coluna montada **
				$mont_html->set_array(3,'class',"hidden-md text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(3,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
				$mont_html->set_array(4,'td',"{$criado}"); // Coluna montada **
				$mont_html->set_array(4,'class',"hidden-md text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(4,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
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
		(int) $id = $this->funcoes->anti_injection($_GET['id']); // Recebo id

		//===========================================================
		//SETO VALORES EM ADICIONAR
		if(empty($id)){

			//MONTO OS DADOS NOS CAMPOS ****

			/*//CAMPO
			$this->funcoes->set_array(0,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
			$this->funcoes->set_array(0,'campo_set_form','campo_name');
			$this->funcoes->set_array(0,'valor_campo_set','VALOR DE TESTE');*/


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
					$this->funcoes->set_array(0,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(0,'campo_set_form','nome');
					$this->funcoes->set_array(0,'valor_campo_set',$exec[$i]['criado']);

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

		//MONTO AUTOCOMPLETE
		$this->funcoes->set_array(0,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(0,'tipo_autocomplete','usuario'); // tipo autocomplete
		$this->funcoes->set_array(0,'id_autocomplete','pesq_avanc_usuario'); // id do campo

		$this->funcoes->set_array(1,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(1,'tipo_autocomplete','hora'); // tipo autocomplete
		$this->funcoes->set_array(1,'id_autocomplete','pesq_avanc_hora'); // id do campo

		$this->funcoes->set_array(2,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(2,'tipo_autocomplete','IP'); // tipo autocomplete
		$this->funcoes->set_array(2,'id_autocomplete','pesq_avanc_ip'); // id do campo

		$this->funcoes->set_array(3,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(3,'tipo_autocomplete','SO'); // tipo autocomplete
		$this->funcoes->set_array(3,'id_autocomplete','pesq_avanc_so'); // id do campo

		$this->funcoes->set_array(4,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(4,'tipo_autocomplete','navegador'); // tipo autocomplete
		$this->funcoes->set_array(4,'id_autocomplete','pesq_avanc_navegador'); // id do campo

		$this->funcoes->set_array(5,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(5,'tipo_autocomplete','idioma'); // tipo autocomplete
		$this->funcoes->set_array(5,'id_autocomplete','pesq_avanc_idioma'); // id do campo

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
		(string) $tipo  = $this->funcoes->anti_injection($_GET['tipo']); // campo do autocomplete
		(string) $valor = $this->funcoes->anti_injection($_GET['term']); // valor pesquisado

		//===========================================================
		//AUTOCOMPLETE USUARIO
		if($tipo == 'usuario'){
			$this->model->setCampos('campo_tabela',"adm_usuario");
			$this->model->setCampos('campo_coluna',"nome");
			$this->model->setCampos('campo_where',"nome LIKE '%{$valor}%'");
			$this->model->setCampos('campo_groupby',"nome");
			$this->model->setCampos('campo_orderby',"");
			$valor0 = $this->model->select_simples_retorna_array_mont();
			if(!empty($valor0)){
				echo json_encode($valor0);
			}
			$this->model->getLimpoCampos();
		}
		//AUTOCOMPLETE ACAO
		if($tipo == 'hora'){
			$this->model->setCampos('campo_tabela',"adm_usuario_online");
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
		//AUTOCOMPLETE IP
		if($tipo == 'IP'){
			$this->model->setCampos('campo_tabela',"adm_usuario_online");
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
		//AUTOCOMPLETE SO
		if($tipo == 'SO'){
			$this->model->setCampos('campo_tabela',"adm_usuario_online");
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
		//AUTOCOMPLETE NAVEGADOR
		if($tipo == 'navegador'){
			$this->model->setCampos('campo_tabela',"adm_usuario_online");
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
		//AUTOCOMPLETE IDIOMA
		if($tipo == 'idioma'){
			$this->model->setCampos('campo_tabela',"adm_usuario_online");
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
		(string) $tipo = $this->funcoes->anti_injection($_GET['tipo']); // tipo

		//===========================================================
		//COMBOBOX ESTADO
		if($tipo == 'estado'){
			$this->model->setCampos('campo_tabela',"local_estado");
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
		(string) $tipo  = $this->funcoes->anti_injection($_GET['tipo']); // tipo
		(string) $param = $this->funcoes->anti_injection($_GET['param']); // recebo id para comparação

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
        (int) $id            = $this->funcoes->anti_injection($_POST['id']); //Padrão **
        (string) $op_salvar  = $this->funcoes->anti_injection($_POST['op_salvar']); //Padrão **
        //(string) $name_campo = $this->funcoes->anti_injection($_POST['name_campo']);



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
      		//$this->funcoes->set('Texto Label',"name_campo", $name_campo)->is_required()->min_length(2)->max_length(250);

			//===========================================================
	        //SETO OS DADOS ****-
			//$this->model->setCampos('name_campo',$name_campo);


			//===========================================================
			//MOSTRO MENSAGEM DE ERROS NA TELA
			if(count($this->funcoes->get_errors())>=1){
				echo json_encode($this->funcoes->get_errors());
				exit;
			}

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
			$this->funcoes->set('Texto Label',"name_campo", $name_campo)->is_required()->min_length(2)->max_length(250);

			//===========================================================
			//SETO OS DADOS ****-
			$this->model->setId($id);
			$this->model->setCampos('name_campo',$name_campo);


			//===========================================================
			//MOSTRO MENSAGEM DE ERROS NA TELA
			if(count($this->funcoes->get_errors())>=1){
				echo json_encode($this->funcoes->get_errors());
				exit;
			}

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
        (int) $id     = $this->funcoes->anti_injection($_GET['id']);
        (string) $exp = $this->funcoes->anti_injection($_GET['exp']);
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
		(string) $tipo = $this->funcoes->anti_injection($_GET['tipo']); // tipo

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

		//===========================================================
		//TRATO PARAMETROS
		if(!is_numeric($id)){ // verifico se id é numérico
			$this->funcoes->set_array(null,'erro',$this->core->get_msg_array('is_num',$id)); // Mensagem de erro parametro $id deve ser numérico
			echo json_encode($this->funcoes->get_array());
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('is_num',$id))->gravo_log(); // Gravo log
			exit();
		}

		//VERIFICO E DESTRUO SESSAO
		$usuario_sessao = new usuario_sessao();
        $exec = $usuario_sessao->controlo_tempo_sessao($this->dir_app,$this->core->get_config('dir_raiz_http').$this->dir_app.'/'.$this->config_apps->getPaginas(2),$id,true); //Controlo tempo de sessão

		//===========================================================
		//MENSAGENS
		if($exec == true){
			$this->funcoes->set_array(null,'sucesso','Usuário deslogado com sucesso!'); // Mensagem de sucesso
			echo json_encode($this->funcoes->get_array());
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem(' Usuário deslogado com sucesso! '.$id.' ')->gravo_log(); // Gravo log
			exit;
		}else{
			$this->funcoes->set_array(null,'erro','Erro ao deslogar Usuário!'); // Mensagem de erro
			echo json_encode($this->funcoes->get_array());
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem(' Usuário deslogado com sucesso! '.$exec.' '.$id.' ')->gravo_log(); // Gravo log
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
