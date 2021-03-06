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
	$control = new adm_usuario_nivelController();  // ****-
}

//CLASS ****-
class adm_usuario_nivelController {

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
		$this->core->includeController('adm_usuario_nivel',$this->dir_app); //incluo model ****-

		//INSTANCIO
		$this->funcoes           = new funcoes();     //Instancio Funções
		$this->model             = new adm_usuario_nivelModel();  //Instancio Model ****-
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
			$this->adm_usuario_modulo_id         = 2; //Id do módulo
            $this->url_pagina                    = "adm_usuario_nivel"; //Url da página
            $this->nome_pagina_singular          = "Cargo"; // Nome da página singular
            $this->nome_pagina_plural            = "Cargos"; // Nome da página plural
            $this->btns_acoes['foco_campo_form'] = "nivel"; // Foco campo form add e edd

			//===========================================================
			//CAMPOS DEFAULT
			require $this->core->includeControllerInclude("carrego_parametros_0", $this->dir_app);

			//===========================================================
			//TEXTOS GERAIS ****-


			//===========================================================
			//STATUS DE BTNS DE AÇÕES GERAL ****-
			$this->btns_acoes['status_btn_editar']        = true; // Status do btn de editar [true or false]
			$this->btns_acoes['status_btn_excluir']       = true; // Status do btn de excluir [true or false]
			$this->btns_acoes['status_btn_detalhamento']  = false; // Status do btn de detalhamento [true or false]
			$this->btns_acoes['status_btn_ativar']        = false; // Status do btn de ativar [true or false]
			$this->btns_acoes['status_btn_novo']          = true; // Status de btn de cadastro [true or false]
			$this->btns_acoes['status_btn_atualizar']     = true; // Status de btn de atualizar [true or false]
			$this->btns_acoes['status_imprimir']          = false; // Status de btn de imprimir [true or false]
			$this->btns_acoes['status_exportar_pdf']      = false; // Status de btn de exportar para pdf [true or false]
			$this->btns_acoes['status_exportar_csv']      = false; // Status de btn de exportar para csv [true or false]

			//===========================================================
			//STATUS DE ÁREA DA LISTAGEM ****-


			//===========================================================
			//CONFIGURAÇÕES DE ÁREA DA LISTAGEM ****-
            $this->btns_acoes['title_campo_pesquisa'] = 'ID ou Cargo'; // Parametros de pesquisa
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
			$this->btns_acoes['class_icone_listagem']     = "fa fa-user"; // Classe do icone da listagem

			//===========================================================
			//ACTION E CMDS DO CONTROLLER ****-
	    	require $this->core->includeControllerInclude("carrego_parametros_1", $this->dir_app);

			//===========================================================
			//RESTRIÇÕES DA PÁGINA ****-
			require $this->core->includeControllerInclude("carrego_parametros_2", $this->dir_app);

			//===========================================================
			//SE TIVER PERMISSAO DE EXCLUIR
			if($this->btns_acoes['status_btn_excluir'] == true){
				$this->btns_acoes['mensagem_informativa'] = 'Ao excluir um Cargo todos os dados relacionados a este cargo serão excluidos do sistema!'; // Mensagem informativa relacionada a tabela
			}

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

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Cargo');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','nivel');
				$mont_html->set_array(null,'input_id','nivel');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Cargo');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$nivel = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Titulo do Painel');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','nome_logo_admin');
				$mont_html->set_array(null,'input_id','nome_logo_admin');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o nome da empresa que será exibido no titulo da página');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$nome_logo_admin = $mont_html->monto_html_input();

				//VALORES SELECT ESTÁTICO
				$this->funcoes->set_array(0,'id','skin-blue')->set_array(0,'value','Azul');
				$this->funcoes->set_array(1,'id','skin-blue-dark')->set_array(1,'value','Azul Escuro');
				$this->funcoes->set_array(2,'id','skin-aqua')->set_array(2,'value','Aqua');
				$this->funcoes->set_array(3,'id','skin-green')->set_array(3,'value','Verde');
				$this->funcoes->set_array(4,'id','skin-red')->set_array(4,'value','Vermelho');
				$this->funcoes->set_array(5,'id','skin-pink')->set_array(5,'value','Pink');
				$this->funcoes->set_array(6,'id','skin-yellow')->set_array(6,'value','Amarelo');
				$this->funcoes->set_array(7,'id','skin-purple')->set_array(7,'value','Roxo');
				$this->funcoes->set_array(8,'id','skin-black')->set_array(8,'value','Preto');
				$valores_select = $this->funcoes->get_array();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Tema do Painel');
				$mont_html->set_array(null,'input_text_select','Selecione ');
				$mont_html->set_array(null,'input_name','tema_admin');
				$mont_html->set_array(null,'input_id','tema_admin');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione o tema do painel');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value','');
				$tema = $mont_html->monto_html_select();

				//===========================================================
				//MONTO CAMPOS E ÁREAS
				$mont_html->set_array(0,'titulo','Informações Gerais');
				$mont_html->set_array(0,'icone_titulo','fa fa-list'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(0,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(0,'conteudo'," {$nivel} {$nome_logo_admin} {$tema}"); //conteudo ***
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

				//

				//===========================================================
				//MONTO CAMPOS
				$mont_html->set_array(0,'conteudo'," {$area_descricao} {$input_text} {$input_text2} {$select}  ");
				$mont_html->set_array(1,'conteudo',"  ");
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

				//SETO OS DADOS DA PESQUISA AVANÇADA NA SESSÃO ****-
				if(!empty($pesq_avanc1)){ $pesq_avancada['periodo_tipo_data'] = $pesq_avanc1; }
				if(!empty($pesq_avanc2) && !empty($pesq_avanc1)){ $pesq_avancada['periodo_data_de'] = $pesq_avanc2; }
				if(!empty($pesq_avanc3) && !empty($pesq_avanc1)){ $pesq_avancada['periodo_data_ate'] = $pesq_avanc3; }

				//

				//===========================================================
				//MONTO O MENU DE FILTROS ****-
				$this->funcoes->set_array(1,'btn_status',true);
				$this->funcoes->set_array(1,'btn_texto','Listaa');
				$this->funcoes->set_array(1,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(1,'btn_target','');
				$this->funcoes->set_array(1,'btn_title','');
				$this->funcoes->set_array(1,'btn_id','');
				$this->funcoes->set_array(1,'btn_class','btn_param_pesquisa');
				$this->funcoes->set_array(1,'btn_class_icon','');
				$this->funcoes->set_array(1,'btn_outros','param_pesquisa="Valor de testeaa"');
				//$this->funcoes->set_array(1,'class_divider','divider'); // divider
				$filtros = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO OS OPTION DE ORDENAR ****-
				$this->funcoes->set_array(0,'value',"ID CRESCENTE");
				$this->funcoes->set_array(1,'value',"ID DECRESCENTE");
				$this->funcoes->set_array(2,'value',"separator"); //separator
				$this->funcoes->set_array(3,'value',"AÇÃO CRESCENTE");
				$this->funcoes->set_array(4,'value',"AÇÃO DECRESCENTE");
				$this->funcoes->set_array(5,'value',"separator"); //separator
				$this->funcoes->set_array(6,'value',"CADASTRADO CRESCENTE");
				$this->funcoes->set_array(7,'value',"CADASTRADO DECRESCENTE");
				$this->funcoes->set_array(8,'value',"separator"); //separator
				$this->funcoes->set_array(9,'value',"MODIFICADO CRESCENTE");
				$this->funcoes->set_array(10,'value',"MODFICADO DECRESCENTE");
				$this->funcoes->set_array(11,'value',"separator"); //separator
				$this->funcoes->set_array(12,'value',"CARGO CRESCENTE");
				$this->funcoes->set_array(13,'value',"CARGO DECRESCENTE");
				$option_ordenar = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO OS OPTIONS DE AGRUPAR ****-
				$this->funcoes->set_array(null,'pagina','Página');
				$this->funcoes->set_array(null,'acao','Ação');
				$option_agrupar = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO O HEADER DA TABELA ****-
				$this->funcoes->set_array(0,'th_titulo','ID');
				$this->funcoes->set_array(0,'th_class','text-center col-md-1');
				$this->funcoes->set_array(0,'th_outros','');
				$this->funcoes->set_array(1,'th_titulo','Cargo');
				$this->funcoes->set_array(1,'th_class','col-md-5 col-lg-6');
				$this->funcoes->set_array(1,'th_outros','');
				$this->funcoes->set_array(2,'th_titulo','<i class="fa fa-calendar-o"></i> Data Modificado');
				$this->funcoes->set_array(2,'th_class','hidden-md text-center col-md-3 col-lg-3');
				$this->funcoes->set_array(2,'th_outros','');
				$this->funcoes->set_array(3,'th_titulo','<i class="fa fa-calendar-o"></i> Data Cadastro');
				$this->funcoes->set_array(3,'th_class','text-center col-md-3 col-lg-3');
				$this->funcoes->set_array(3,'th_outros','');
				$header_tabela = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO O CONTEUDO DA TABELA ****-

				//FILTROS SMARTY PHP CADASTRADO
				$mont_html->set_array(0,'tipo',"data_hora"); // data, hora, data_hora, tempo, tempo_hora, enc_texto, maiusculo, minusculo, maiusc_minusc e format_num
				$mont_html->set_array(0,'campo',"[bd:criado]"); // campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'valor_enc_texto',""); // valor numérico do tamanho do texto
				$criado = $mont_html->filtros_smarty();

				//FILTROS SMARTY PHP CADASTRADO
				$mont_html->set_array(0,'tipo',"data_hora"); // data, hora, data_hora, tempo, tempo_hora, enc_texto, maiusculo, minusculo, maiusc_minusc e format_num
				$mont_html->set_array(0,'campo',"[bd:modificado]"); // campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'valor_enc_texto',""); // valor numérico do tamanho do texto
				$modificado = $mont_html->filtros_smarty();

				//AÇÃO DE LINHA DA TABELA
				$mont_html->set_array(0,'status',true); // Status da ação true or false
				$mont_html->set_array(0,'class',"btn_editar"); // Tipo: btn_ativar_desativar, btn_detalhamento, btn_editar ou url
				$mont_html->set_array(0,'url',""); // url de redirecionamento $this->core->get_config('dir_raiz_http').$this->dir_app."/"
				$mont_html->set_array(0,'status_btn_detalhamento',$this->btns_acoes['status_btn_detalhamento']); // Status ação detalhamento true or false
				$mont_html->set_array(0,'status_btn_editar',$this->btns_acoes['status_btn_editar']); // Status ação editar true or false
				$mont_html->set_array(0,'status_btn_ativar',$this->btns_acoes['status_btn_ativar']); // Status ação ativar e desativar true or false
				$mont_html->set_array(0,'url_btn_ativar',$this->cmds['action_ativar']); // Action do btn ativar
				$acao_linha = $mont_html->acao_linha_smarty();

				//===========================================================
				//CONTEUDO MONTADO
				$mont_html->set_array(0,'td',"[bd:id]"); // Coluna montada **
				$mont_html->set_array(0,'class',"text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(0,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
				$mont_html->set_array(1,'td',"[bd:nivel]"); // Coluna montada **
				$mont_html->set_array(1,'class'," {$acao_linha['class']}"); // Classes
				$mont_html->set_array(1,'outros'," {$acao_linha['outros']}"); //Outros parametros do td
				$mont_html->set_array(2,'td',"{$modificado}"); // Coluna montada **
				$mont_html->set_array(2,'class',"hidden-md text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(2,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
				$mont_html->set_array(3,'td',"{$criado}"); // Coluna montada **
				$mont_html->set_array(3,'class',"text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(3,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
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

			/*//CAMPO
			$this->funcoes->set_array(0,'tipo_set','input'); // input, textarea, checkbox, select, show_hide
			$this->funcoes->set_array(0,'campo_set_form','campo_name');
			$this->funcoes->set_array(0,'valor_campo_set','VALOR DE TESTE');*/


			//MOSTRO NA TELA
			echo json_encode($this->funcoes->get_array());

		//===========================================================
		//SETO VALORES EM EDITAR
		}else{

			//BARRO ID 1
			if($_SESSION['adm_id_cargo'] != 1) {
				if($id == 1){
					$id = null;
				}
			}

			//INCLUDE - verifico permissão, seto e executo model
			require $this->core->includeControllerInclude("cmd_set_add_edd_1", $this->dir_app);
			if(!empty($exec)){

				//MONTO OS DADOS NOS CAMPOS ****-
				for ($i=0; $i < count($exec) ; $i++) {

					//CAMPO
					$this->funcoes->set_array(0,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(0,'campo_set_form','nivel');
					$this->funcoes->set_array(0,'valor_campo_set',$exec[$i]['nivel']);

					//CAMPO
					$this->funcoes->set_array(1,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(1,'campo_set_form','nome_logo_admin');
					$this->funcoes->set_array(1,'valor_campo_set',$exec[$i]['nome_logo_admin']);

					//CAMPO
					$this->funcoes->set_array(2,'tipo_set','select_estatico'); // input, textarea, checkbox, select
					$this->funcoes->set_array(2,'campo_set_form','tema_admin');
					$this->funcoes->set_array(2,'valor_campo_set',$exec[$i]['tema_admin']);

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
		//AUTOCOMPLETE NOME
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
		$nivel           = $this->funcoes->anti_injection($_POST['nivel']);
		$nome_logo_admin = $this->funcoes->anti_injection($_POST['nome_logo_admin']);
		$tema_admin      = $this->funcoes->anti_injection($_POST['tema_admin']);

		//CONVERTO STRING PARA MAIUSCULO E MINUSCULO
		$nivel = $this->funcoes->conv_string($nivel,2);


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
			if($this->model->retorn_campo_editar('nivel', $nivel) == $nivel){
				$nivel_u = true;
			}
			$this->funcoes->set('Cargo',"nivel", $nivel)->is_required()->min_length(4)->max_length(250)->is_unique($nivel_u);
			$this->funcoes->set('Nome da Empresa Admin',"nome_logo_admin", $nome_logo_admin)->is_required()->min_length(2)->max_length(250);
      		$this->funcoes->set('Tema do Admin',"tema_admin", $tema_admin)->is_required()->min_length(4)->max_length(250);


			//===========================================================
	        //SETO OS DADOS ****-
			$this->model->setCampos('nivel',$nivel);
			$this->model->setCampos('nome_logo_admin',$nome_logo_admin);
			$this->model->setCampos('tema_admin',$tema_admin);


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

			//BARRO ID 1
			if($_SESSION['adm_id_cargo'] != 1) {
				if($id == 1){
					$id = null;
				}
				if($id == 2){
					$id = null;
				}
				if($id == 3){
					$id = null;
				}
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
			$this->model->setId($id);
			if($this->model->retorn_campo_editar_val_id('nivel') != $nivel){
				if($this->model->retorn_campo_editar('nivel', $nivel) == $nivel){  $nivel_u = true;  }
				$this->funcoes->set('Cargo',"nivel", $nivel)->is_required()->min_length(2)->max_length(250)->is_unique($nivel_u);
			}else{
				$this->funcoes->set('Cargo',"nivel", $nivel)->is_required()->min_length(2)->max_length(250);
			}
			$this->funcoes->set('Nome da Empresa Admin',"nome_logo_admin", $nome_logo_admin)->is_required()->min_length(2)->max_length(250);
			$this->funcoes->set('Tema do Admin',"tema_admin", $tema_admin)->is_required()->min_length(4)->max_length(250);

			//===========================================================
			//SETO OS DADOS ****-
			$this->model->setCampos('nivel',$nivel);
			$this->model->setCampos('nome_logo_admin',$nome_logo_admin);
			$this->model->setCampos('tema_admin',$tema_admin);

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
				$this->model->setCampos('campo_tabela',"adm_usuario_nivel_usuario");
				$this->model->setCampos('campo_coluna',"adm_usuario_nivel_id");
				$this->model->setCampos('campo_inner_join',"");
				$this->model->setCampos('campo_where',"adm_usuario_nivel_usuario.adm_usuario_nivel_id = ".$id[$i]."");
				$exec_ = $this->model->select_simples_retorna_true_false();
				$this->model->getLimpoCampos();

				if($id[$i] == 1){
					$exec_ = true;
				}
				if($id[$i] == 2){
					$exec_ = true;
				}
				if($id[$i] == 3){
					$exec_ = true;
				}

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
		//**********************************************************

		//RECEBO OS DADOS

		//TRATO OS DADOS

		//MENSAGEM DE SUCESSO
		$this->funcoes->set_array(null,'sucesso','MENSAGEM DE SUCESSO'); // Mensagem de erro $id não é um array
		echo json_encode($this->funcoes->get_array());

		//MENSAGEM DE ERRO
		/*$this->funcoes->set_array(null,'erro','MENSAGEM DE ERRO'); // Mensagem de erro $id não é um array
		echo json_encode($this->funcoes->get_array());*/
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
