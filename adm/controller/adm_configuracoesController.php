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
	$control = new adm_configuracoesController();  // ****-
}

//CLASS ****-
class adm_configuracoesController {

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
		$this->core->includeController('adm_configuracoes',$this->dir_app); //incluo model ****-

		//INSTANCIO
		$this->funcoes           = new funcoes();     //Instancio Funções
		$this->model             = new adm_configuracoesModel();  //Instancio Model ****-
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
			$this->adm_usuario_modulo_id         = 1; //Id do módulo
            $this->url_pagina                    = "adm_configuracoes"; //Url da página
            $this->nome_pagina_singular          = "Configurações Gerais"; // Nome da página singular
            $this->nome_pagina_plural            = "Configurações"; // Nome da página plural
            $this->btns_acoes['foco_campo_form'] = "smtp_host"; // Foco campo form add e edd

			//===========================================================
			//CAMPOS DEFAULT
			require $this->core->includeControllerInclude("carrego_parametros_0", $this->dir_app);

			//===========================================================
			//TEXTOS GERAIS ****-


			//===========================================================
			//STATUS DE BTNS DE AÇÕES GERAL ****-
			$this->btns_acoes['status_btn_editar']        = true; // Status do btn de editar [true or false]
			$this->btns_acoes['status_btn_excluir']       = false; // Status do btn de excluir [true or false]
			$this->btns_acoes['status_btn_detalhamento']  = false; // Status do btn de detalhamento [true or false]
			$this->btns_acoes['status_btn_ativar']        = false; // Status do btn de ativar [true or false]
			$this->btns_acoes['status_btn_novo']          = false; // Status de btn de cadastro [true or false]
			$this->btns_acoes['status_btn_atualizar']     = false; // Status de btn de atualizar [true or false]
			$this->btns_acoes['status_imprimir']          = false; // Status de btn de imprimir [true or false]
			$this->btns_acoes['status_exportar_pdf']      = false; // Status de btn de exportar para pdf [true or false]
			$this->btns_acoes['status_exportar_csv']      = false; // Status de btn de exportar para csv [true or false]

			//===========================================================
			//STATUS DE ÁREA DA LISTAGEM ****-


			//===========================================================
			//CONFIGURAÇÕES DE ÁREA DA LISTAGEM ****-
            $this->btns_acoes['title_campo_pesquisa'] = 'ID ou Status'; // Parametros de pesquisa
            $this->btns_acoes['ordenar_selecionado']  = array("ID DECRESCENTE"); // Value padrão do campo Odernar
            $this->btns_acoes['agrupar_selecionado']  = array(""); // Value padrão do campo Agrupar
            $this->btns_acoes['mensagem_informativa'] = ''; // Mensagem informativa relacionada a tabela

			//===========================================================
			//STATUS DE ÁREA DOS BOTÕES DE LISTAGEM DEFAULT
			$this->btns_acoes['area_listagem_geral']           = false; // Status da área de listagem geral [true or false]
			$this->btns_acoes['area_listagem']                 = false; // Status da área de listagem [true or false]
			$this->btns_acoes['area_btns_status_acoes']        = false; // Status de área geral dos botôes de opções da listagem [true or false]
			$this->btns_acoes['area_formulario']               = false; // Status da área do formulário [true or false]
			$this->btns_acoes['area_btns_status_acoes_forms']  = false; // Status da área geral dos botões de opções do formulário [true or false]

			//===========================================================
			//TEXTO DE BTNS DE AÇÕES ****-
			$this->btns_acoes['txt_btn_novo']          = ""; // Texto do btn de novo
			$this->btns_acoes['txt_btn_editar']        = ""; // Texto do btn de editar
			$this->btns_acoes['txt_btn_atualizar']     = ""; // Texto do btn de atualizar
			$this->btns_acoes['txt_btn_salvar']        = "Salvar"; // Texto do btn salvar [true or false]

			//===========================================================
			//TITLE DE BTNS DE AÇÕES ****-
			$this->btns_acoes['title_btn_novo']         = " {$this->nome_pagina_singular} "; // Title do btn de novo
			$this->btns_acoes['title_btn_editar']       = " {$this->nome_pagina_singular}"; // Title do btn de editar
			$this->btns_acoes['title_btn_salvar']        = "Salvar (Alt+S)"; // Title do btn salvar [true or false]

			//===========================================================
			//CLASSE DE ICONES DE BTNS DE AÇÕES ****-
			$this->btns_acoes['class_icone_editar']       = "fa fa-cog"; // Classe do icone editar geral
			$this->btns_acoes['class_icone_novo']         = "fa fa-cog"; // Classe do icone novo geral

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

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Nome principal E-mail');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','smtp_nome');
				$mont_html->set_array(null,'input_id','smtp_nome');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o nome da empresa que será exibida no e-mail');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$nome_empresa_email = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Nome principal SMS');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','sms_nome');
				$mont_html->set_array(null,'input_id','sms_nome');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o nome da empresa que será exibido no SMS');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$nome_empresa_sms = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Host SMTP');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','smtp_host');
				$mont_html->set_array(null,'input_id','smtp_host');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o host SMTP');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$host_smtp = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-4 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* E-mail SMTP');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','smtp_username');
				$mont_html->set_array(null,'input_id','smtp_username');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o e-mail SMTP');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$username_smtp = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Senha do e-mail SMTP');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','smtp_senha');
				$mont_html->set_array(null,'input_id','smtp_senha');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite a senha do seu e-mail SMTP');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$senha_smtp = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Porta SMTP');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','smtp_porta');
				$mont_html->set_array(null,'input_id','smtp_porta');
				$mont_html->set_array(null,'input_class','numeros'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite a porta SMTP');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$sms_porta = $mont_html->monto_html_input();

				//VALORES SELECT ESTÁTICO
			    $this->funcoes->set_array(0,'id','true')->set_array(0,'value','Ativo');
			    $this->funcoes->set_array(1,'id','false')->set_array(1,'value','Inativo');
			    $valores_select = $this->funcoes->get_array();
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','* Tls SMTP');
			    $mont_html->set_array(null,'input_text_select','Selecione');
			    $mont_html->set_array(null,'input_name','smtp_tls');
			    $mont_html->set_array(null,'input_id','smtp_tls');
			    $mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
			    $mont_html->set_array(null,'input_title','Selecione Tls SMTP');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
			    $mont_html->set_array(null,'input_value','');
			    $mont_html->set_array(null,'input_outros','');
			    $smtp_tls = $mont_html->monto_html_select();

				//VALORES SELECT ESTÁTICO
				$this->funcoes->set_array(0,'id','true')->set_array(0,'value','Ativo');
				$this->funcoes->set_array(1,'id','false')->set_array(1,'value','Inativo');
				$valores_select = $this->funcoes->get_array();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Debug SMTP');
				$mont_html->set_array(null,'input_text_select','Selecione');
				$mont_html->set_array(null,'input_name','smtp_debug');
				$mont_html->set_array(null,'input_id','smtp_debug');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione Debug SMTP');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros','');
				$smtp_debug = $mont_html->monto_html_select();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* E-mail principal');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','email_principal');
				$mont_html->set_array(null,'input_id','email_principal');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o e-mail principal do sistema');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$email_principal = $mont_html->monto_html_input();

				//MONTO CAMPO TEXTAREA
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-12 col-md-12 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','Assinatura de e-mail');
			    $mont_html->set_array(null,'input_name','email_assinatura');
			    $mont_html->set_array(null,'input_id','email_assinatura');
			    $mont_html->set_array(null,'input_class','editor_html_basico');
			    $mont_html->set_array(null,'input_placeholder','');
			    $mont_html->set_array(null,'input_title','');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_value','');
			    $mont_html->set_array(null,'input_outros','');
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $mont_html->set_array(null,'input_rows',''); //número de linhas
			    $email_assinatura = $mont_html->monto_html_textarea();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Username SMS');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','sms_username');
				$mont_html->set_array(null,'input_id','sms_username');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o username SMS');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$sms_username = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Senha SMS');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','sms_senha');
				$mont_html->set_array(null,'input_id','sms_senha');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite a senha SMS');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$sms_senha = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Telefone principal');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','telefone_principal');
				$mont_html->set_array(null,'input_id','telefone_principal');
				$mont_html->set_array(null,'input_class','numeros'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o telefone principal (código + telefone) ');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$telefone_principal = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Versão do Administrativo');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','versao_aplicacao');
				$mont_html->set_array(null,'input_id','versao_aplicacao');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite a versão da aplicação');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$versao_aplicacao = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Titulo do Painel');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','nome_logo_admin');
				$mont_html->set_array(null,'input_id','nome_logo_admin');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o titulo do painel');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$nome_logo_admin = $mont_html->monto_html_input();

				//VALORES SELECT ESTÁTICO
				$this->funcoes->set_array(0,'id','skin-blue')->set_array(0,'value','Azul');
				$this->funcoes->set_array(1,'id','skin-green')->set_array(1,'value','Verde');
				$this->funcoes->set_array(2,'id','skin-red')->set_array(2,'value','Vermelho');
				$this->funcoes->set_array(3,'id','skin-yellow')->set_array(3,'value','Amarelo');
				$this->funcoes->set_array(4,'id','skin-purple')->set_array(4,'value','Roxo');
				$this->funcoes->set_array(5,'id','skin-black')->set_array(5,'value','Preto');
				$valores_select = $this->funcoes->get_array();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Tema do Painel');
				$mont_html->set_array(null,'input_text_select','Selecione ');
				$mont_html->set_array(null,'input_name','tema_admin');
				$mont_html->set_array(null,'input_id','tema_admin');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione o tema do admin');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value','');
				$tema = $mont_html->monto_html_select();

				//MONTO CAMPO MENSAGEM INFORMATIVA
			    $mont_html->set_array(null,'id','');
			    $mont_html->set_array(null,'class','');
			    $mont_html->set_array(null,'tipo','info'); //tipos: danger, info, warning, success
			    $mont_html->set_array(null,'titulo','ATENÇÃO!');
			    $mont_html->set_array(null,'mensagem','Para que as alterações façam efeito saia do sistema e entre novamente.');
			    $msg_informativa = $mont_html->monto_html_mensagem_informativa();

				//MONTO CAMPO INPUT FILE
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'input_multiple',false); //true ou false
				$mont_html->set_array(null,'label_for_texto','* Imagem de Fundo Geral');
				$mont_html->set_array(null,'input_name','img_fundo_login');
				$mont_html->set_array(null,'input_id','img_fundo_login');
				$mont_html->set_array(null,'input_class','');
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'title_help','FORMATOS: <b>(jpg)</b> <br /> DIMENSÕES: <b>1280px x 721px</b> <br /> TAMANHO: <b>Máx. 2 MB p/ arquivo</b> ');
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'editor_img_online',true); // Editor de imagens online - true ou false
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$input_file = $mont_html->monto_html_input_file();

				//MONTO CAMPO DE LISTAGEM DE ARQUIVOS *** MÁXIMO 4 ÁREAS POR PÁGINA
				$mont_html->set_array(null,'status_listagem_arquivos',true); //STATUS LISTAGEM DE ARQUIVOS - true ou false
				$mont_html->set_array(null,'param_controle_list_arq','1'); //máximo 4 - 1 a 4 - incremente o valor de acordo com a qtd de área de listagens de arquivos
				$mont_html->set_array(null,'upload_multiplo',false); //UPLOAD MULTIPLO - true ou false
				$mont_html->set_array(null,'tipo_listagem_arquivos','imagem'); //TIPO - imagem ou arquivo
				$mont_html->set_array(null,'titulo_listagem_arquivos','Imagem de Fundo Geral'); //TITULO DA LISTAGEM - imagem ou arquivo
				$mont_html->set_array(null,'icone_doc','fa fa-file-text'); //ICONE DA LISTAGEM DOCUMENTOS - Font Awesome Icons
				$mont_html->set_array(null,'icone_img','fa fa-picture-o'); //TITULO DA LISTAGEM IMAGENS - Font Awesome Icons
				$mont_html->set_array(null,'campo_input_file','img_fundo_login'); //ID DO CAMPO INPUT FILE
				$mont_html->set_array(null,'url_listagem_arquivos','img_fundo_login'); // PARAMETRO TIPO - LISTAGEM DE ARQUIVOS
				$listagem_arquivos = $mont_html->monto_html_list_arquivos();

				//VALORES SELECT ESTÁTICO
				$this->funcoes->set_array(0,'id','Modo de Desenvolvimento')->set_array(0,'value','Modo de Desenvolvimento');
				$this->funcoes->set_array(1,'id','Modo de Produção')->set_array(1,'value','Modo de Produção');
				$valores_select = $this->funcoes->get_array();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Modo do Sistema');
				$mont_html->set_array(null,'input_text_select','Selecione');
				$mont_html->set_array(null,'input_name','modo_sistema');
				$mont_html->set_array(null,'input_id','modo_sistema');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione o Modo do Sistema');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$modo_sistema = $mont_html->monto_html_select();

				//===========================================================
				//MONTO CAMPOS E ÁREAS
				$mont_html->set_array(0,'titulo','Configurações de e-mail e smtp');
				$mont_html->set_array(0,'icone_titulo','fa fa-envelope'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(0,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(0,'conteudo'," {$host_smtp} {$username_smtp} {$senha_smtp} {$sms_porta} {$smtp_tls} {$smtp_debug} {$nome_empresa_email} {$email_principal} {$email_assinatura} "); //conteudo ***
				$mont_html->set_array(1,'titulo','Configurações do Gateway de SMS - Zenvia');
				$mont_html->set_array(1,'icone_titulo','fa fa-lg fa-mobile'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(1,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(1,'conteudo'," {$sms_username} {$sms_senha}  {$nome_empresa_sms} {$telefone_principal}"); //conteudo ***
				$mont_html->set_array(2,'titulo','Configurações do Painel Administrativo');
				$mont_html->set_array(2,'icone_titulo','fa fa-cog'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(2,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(2,'conteudo'," {$nome_logo_admin} {$versao_aplicacao} {$modo_sistema} "); //conteudo ***
				$mont_html->set_array(3,'titulo','Imagem de Fundo Geral Administrativo');
				$mont_html->set_array(3,'icone_titulo','fa fa-camera'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(3,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(3,'conteudo'," {$input_file} {$listagem_arquivos}"); //conteudo ***
				$conteudo_montado = $msg_informativa.$mont_html->monto_html_area_forms();

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
				$this->funcoes->set_array(0,'input_id','campo_name');
				$this->funcoes->set_array(0,'input_name','campos_pdf[]');
				$this->funcoes->set_array(0,'input_class','');
				$this->funcoes->set_array(0,'input_title','');
				$this->funcoes->set_array(0,'input_value','campo_name'); //value do campo
				$this->funcoes->set_array(0,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(0,'input_texto_checkbox','NAME CAMPO'); //Texto
				$valores_checkbox = $this->funcoes->get_array();

				//MONTO CAMPO INPUT CHECKBOX
				$mont_html->set_array(null,'input_class_tamanho','col-md-12 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
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
				$this->funcoes->set_array(0,'input_id','id');
				$this->funcoes->set_array(0,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(0,'input_class','');
				$this->funcoes->set_array(0,'input_title','');
				$this->funcoes->set_array(0,'input_value','id'); //value do campo
				$this->funcoes->set_array(0,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(0,'input_texto_checkbox','ID'); //Texto
				$this->funcoes->set_array(1,'input_id','criado');
				$this->funcoes->set_array(1,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(1,'input_class','');
				$this->funcoes->set_array(1,'input_title','');
				$this->funcoes->set_array(1,'input_value','criado'); //value do campo
				$this->funcoes->set_array(1,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(1,'input_texto_checkbox','Data de Cadastro'); //Texto
				$valores_checkbox = $this->funcoes->get_array();

				//MONTO CAMPO INPUT CHECKBOX
				$mont_html->set_array(null,'input_class_tamanho','col-md-12 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
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
				$mont_html->set_array(null,'input_class_tamanho','col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
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
				$mont_html->set_array(null,'input_class_tamanho','col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
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
				$mont_html->set_array(null,'input_class_tamanho','col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12
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
				(string) $pesq_avanc1 = $this->funcoes->anti_injection($_POST['pesq_avanc_tipo_data']); // Recebo dados
				(string) $pesq_avanc2 = $this->funcoes->anti_injection($_POST['pesq_avanc_periodo_data_de']); // Recebo dados
				(string) $pesq_avanc3 = $this->funcoes->anti_injection($_POST['pesq_avanc_periodo_data_ate']); // Recebo dados
				//(string) $pesq_avanc4 = $this->funcoes->anti_injection($_POST['pesq_avanc_']); // Recebo dados

				//SETO OS DADOS DA PESQUISA AVANÇADA NA SESSÃO ****-
				if(!empty($pesq_avanc1)){ $pesq_avancada['periodo_tipo_data'] = $pesq_avanc1; }
				if(!empty($pesq_avanc2) && !empty($pesq_avanc1)){ $pesq_avancada['periodo_data_de'] = $pesq_avanc2; }
				if(!empty($pesq_avanc3) && !empty($pesq_avanc1)){ $pesq_avancada['periodo_data_ate'] = $pesq_avanc3; }
				//if(!empty($pesq_avanc4)){ $pesq_avancada['pesq_avanc_'] = $pesq_avanc4; }

				//

				//===========================================================
				//MONTO O MENU DE FILTROS ****-
				$this->funcoes->set_array(1,'btn_status',true);
				$this->funcoes->set_array(1,'btn_texto','Item teste');
				$this->funcoes->set_array(1,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(1,'btn_target','');
				$this->funcoes->set_array(1,'btn_title','');
				$this->funcoes->set_array(1,'btn_id','');
				$this->funcoes->set_array(1,'btn_class','btn_param_pesquisa_focus'); // (btn_param_pesquisa) = seto e submeto a pesquisa - (btn_param_pesquisa_focus) = seto o valor e foco -
				$this->funcoes->set_array(1,'btn_class_icon','');
				$this->funcoes->set_array(1,'btn_outros','param_pesquisa="ID: "');
				//$this->funcoes->set_array(1,'class_divider','divider'); // divider
				$filtros = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO OS OPTION DE ORDENAR ****-
				$this->funcoes->set_array(0,'value',"ID CRESCENTE");
				$this->funcoes->set_array(1,'value',"ID DECRESCENTE");
				$this->funcoes->set_array(2,'value',"separator"); //separator
				$this->funcoes->set_array(3,'value',"DATA CADASTRO CRESCENTE");
				$this->funcoes->set_array(4,'value',"DATA CADASTRO DECRESCENTE");
				$this->funcoes->set_array(5,'value',"separator"); //separator
				$this->funcoes->set_array(6,'value',"DATA MODIFICADO CRESCENTE");
				$this->funcoes->set_array(7,'value',"DATA MODIFICADO DECRESCENTE");$this->funcoes->set_array(5,'value',"separator"); //separator
				$this->funcoes->set_array(6,'value',"STATUS MODIFICADO CRESCENTE");
				$this->funcoes->set_array(7,'value',"STATUS MODIFICADO DECRESCENTE");
				$option_ordenar = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO OS OPTIONS DE AGRUPAR ****-
				$this->funcoes->set_array(null,'name_campo','Campo');
				$option_agrupar = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO O HEADER DA TABELA ****-
				$this->funcoes->set_array(0,'th_titulo','ID');
				$this->funcoes->set_array(0,'th_class','text-center col-md-1');
				$this->funcoes->set_array(0,'th_outros','');
				$this->funcoes->set_array(1,'th_titulo','Status');
				$this->funcoes->set_array(1,'th_class',' col-md-9');
				$this->funcoes->set_array(1,'th_outros','');
				$this->funcoes->set_array(2,'th_titulo','<i class="fa fa-calendar-o"></i> Data Modificado');
				$this->funcoes->set_array(2,'th_class','text-center col-md-2');
				$this->funcoes->set_array(2,'th_outros','');
				$this->funcoes->set_array(3,'th_titulo','<i class="fa fa-calendar-o"></i> Data Cadastro');
				$this->funcoes->set_array(3,'th_class','text-center col-md-2');
				$this->funcoes->set_array(3,'th_outros','');
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
				$mont_html->set_array(0,'td'," [bd:id] "); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'class',"text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(0,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
				$mont_html->set_array(1,'td',"[bd:status]"); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(1,'class'," {$acao_linha['class']}"); // Classes
				$mont_html->set_array(1,'outros'," {$acao_linha['outros']}"); //Outros parametros do td
				$mont_html->set_array(2,'td',"{$modificado}"); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(2,'class',"text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(2,'outros'," {$acao_linha['outros']}"); //Outros parametros do td
				$mont_html->set_array(3,'td',"{$criado}"); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(3,'class',"text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(3,'outros'," {$acao_linha['outros']}"); //Outros parametros do td
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
		$id = 1;

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
					$this->funcoes->set_array(0,'campo_set_form','smtp_host');
					$this->funcoes->set_array(0,'valor_campo_set',$exec[$i]['smtp_host']);

					//CAMPO
					$this->funcoes->set_array(1,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(1,'campo_set_form','smtp_username');
					$this->funcoes->set_array(1,'valor_campo_set',$exec[$i]['smtp_username']);

					//CAMPO
					$this->funcoes->set_array(2,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(2,'campo_set_form','smtp_senha');
					$this->funcoes->set_array(2,'valor_campo_set',$exec[$i]['smtp_senha']);

					//CAMPO
					$this->funcoes->set_array(3,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(3,'campo_set_form','smtp_porta');
					$this->funcoes->set_array(3,'valor_campo_set',$exec[$i]['smtp_porta']);

					//CAMPO
					$this->funcoes->set_array(5,'tipo_set','select'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(5,'campo_set_form','smtp_tls');
					$this->funcoes->set_array(5,'valor_campo_set',$exec[$i]['smtp_tls']);

					//CAMPO
					$this->funcoes->set_array(6,'tipo_set','select'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(6,'campo_set_form','smtp_debug');
					$this->funcoes->set_array(6,'valor_campo_set',$exec[$i]['smtp_debug']);

					//CAMPO
					$this->funcoes->set_array(7,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(7,'campo_set_form','email_principal');
					$this->funcoes->set_array(7,'valor_campo_set',$exec[$i]['email_principal']);

					//CAMPO
					$this->funcoes->set_array(8,'tipo_set','textarea'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(8,'campo_set_form','email_assinatura');
					$this->funcoes->set_array(8,'valor_campo_set',$exec[$i]['email_assinatura']);

					//CAMPO
					$this->funcoes->set_array(9,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(9,'campo_set_form','sms_username');
					$this->funcoes->set_array(9,'valor_campo_set',$exec[$i]['sms_username']);

					//CAMPO
					$this->funcoes->set_array(10,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(10,'campo_set_form','sms_senha');
					$this->funcoes->set_array(10,'valor_campo_set',$exec[$i]['sms_senha']);

					//CAMPO
					$this->funcoes->set_array(11,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(11,'campo_set_form','telefone_principal');
					$this->funcoes->set_array(11,'valor_campo_set',$exec[$i]['telefone_principal']);

					//CAMPO
					$this->funcoes->set_array(12,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(12,'campo_set_form','nome_logo_admin');
					$this->funcoes->set_array(12,'valor_campo_set',$exec[$i]['nome_logo_admin']);

					//CAMPO
					$this->funcoes->set_array(13,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(13,'campo_set_form','versao_aplicacao');
					$this->funcoes->set_array(13,'valor_campo_set',$exec[$i]['versao_aplicacao']);


					//CAMPO
					$this->funcoes->set_array(15,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(15,'campo_set_form','smtp_nome');
					$this->funcoes->set_array(15,'valor_campo_set',$exec[$i]['smtp_nome']);

					//CAMPO
					$this->funcoes->set_array(16,'tipo_set','input'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(16,'campo_set_form','sms_nome');
					$this->funcoes->set_array(16,'valor_campo_set',$exec[$i]['sms_nome']);

					//CAMPO
					$this->funcoes->set_array(17,'tipo_set','select'); // input, textarea, checkbox, select, show_hide = (valor_campo_set = show ou hide)
					$this->funcoes->set_array(17,'campo_set_form','modo_sistema');
					$this->funcoes->set_array(17,'valor_campo_set',$exec[$i]['modo_sistema']);

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
		(string) $tipo  = $this->funcoes->anti_injection($_GET['tipo']); // campo do autocomplete
		(string) $valor = $this->funcoes->anti_injection($_GET['term']); // valor pesquisado

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
        (int) $id           = $this->funcoes->anti_injection($_POST['id']); //Padrão **
        (string) $op_salvar = $this->funcoes->anti_injection($_POST['op_salvar']); //Padrão **
        (string) $smtp_host  = $this->funcoes->anti_injection($_POST['smtp_host']);
		(string) $smtp_username    = $this->funcoes->anti_injection($_POST['smtp_username']);
		(string) $smtp_senha       = $this->funcoes->anti_injection($_POST['smtp_senha']);
		(int) $smtp_porta          = $this->funcoes->anti_injection($_POST['smtp_porta']);
		(string) $smtp_tls         = $this->funcoes->anti_injection($_POST['smtp_tls']);
		(string) $smtp_debug       = $this->funcoes->anti_injection($_POST['smtp_debug']);
		(string) $email_principal  = $this->funcoes->anti_injection($_POST['email_principal']);
		(string) $email_assinatura = $this->funcoes->anti_injection($_POST['email_assinatura'],'html');
		(string) $sms_username     = $this->funcoes->anti_injection($_POST['sms_username']);
		(string) $sms_senha        = $this->funcoes->anti_injection($_POST['sms_senha']);
		(int) $telefone_principal  = $this->funcoes->anti_injection($_POST['telefone_principal']);
		(string) $nome_logo_admin  = $this->funcoes->anti_injection($_POST['nome_logo_admin']);
		(string) $versao_aplicacao = $this->funcoes->anti_injection($_POST['versao_aplicacao']);
		(string) $tema_admin       = $this->funcoes->anti_injection($_POST['tema_admin']);
		(string) $smtp_nome        = $this->funcoes->anti_injection($_POST['smtp_nome']);
		(string) $sms_nome         = $this->funcoes->anti_injection($_POST['sms_nome']);
		(string) $modo_sistema     = $this->funcoes->anti_injection($_POST['modo_sistema']);
		$this->core->includeHelper('upload');
		$upload = new upload();
		$img_fundo_login = $_FILES['img_fundo_login'];


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
			$this->funcoes->set('O que você deseja excluir?',"opcoes_cache", $opcoes_p)->is_required();

			//===========================================================
			//MOSTRO MENSAGEM DE ERROS NA TELA
			if(count($this->funcoes->get_errors())>=1){
				echo json_encode($this->funcoes->get_errors());
				exit;
			}





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
			$this->funcoes->set('Host SMTP',"smtp_host", $smtp_host)->is_required()->min_length(2)->max_length(100);
			$this->funcoes->set('E-mail SMTP',"smtp_username", $smtp_username)->is_required()->is_email()->min_length(2)->max_length(250);
			$this->funcoes->set('Senha do e-mail SMTP',"smtp_senha", $smtp_senha)->is_required()->min_length(2)->max_length(100);
			$this->funcoes->set('Porta SMTP',"smtp_porta", $smtp_porta)->is_required()->is_num()->max_length(10);
			$this->funcoes->set('Tls SMTP',"smtp_tls", $smtp_tls)->is_required()->max_length(10);
			$this->funcoes->set('Debug SMTP',"smtp_debug", $smtp_debug)->is_required()->max_length(250);
			$this->funcoes->set('Nome principal E-mail',"smtp_nome", $smtp_nome)->is_required()->min_length(2)->max_length(100);
			$this->funcoes->set('E-mail principal',"email_principal", $email_principal)->is_required()->is_email()->min_length(2)->max_length(250);
			$this->funcoes->set('Assinatura de e-mail',"email_assinatura", $email_assinatura)->min_length(2)->max_length(60000000);
			if(!empty($sms_username) || !empty($sms_senha) || !empty($sms_nome) || !empty($telefone_principal)){
				$this->funcoes->set('Username SMS',"sms_username", $sms_username)->is_required()->min_length(2)->max_length(100);
				$this->funcoes->set('Senha SMS',"sms_senha", $sms_senha)->is_required()->min_length(2)->max_length(100);
				$this->funcoes->set('Nome da Empresa SMS',"sms_nome", $sms_nome)->is_required()->min_length(2)->max_length(100);
				$this->funcoes->set('Telefone principal',"telefone_principal", $telefone_principal)->is_required()->is_num()->is_telefone();
			}
			$this->funcoes->set('Titulo do Painel',"nome_logo_admin", $nome_logo_admin)->is_required()->min_length(2)->max_length(50);
			$this->funcoes->set('Versão do Administrativo',"versao_aplicacao", $versao_aplicacao)->is_required()->min_length(2)->max_length(50);
			//$this->funcoes->set('Tema do Admin',"tema_admin", $tema_admin)->is_required()->min_length(2)->max_length(50);
			$this->funcoes->set('Modo do Sistema',"modo_sistema", $modo_sistema)->is_required()->min_length(2)->max_length(50);

			//===========================================================
			//MOSTRO MENSAGEM DE ERROS NA TELA
			if(count($this->funcoes->get_errors())>=1){
				echo json_encode($this->funcoes->get_errors());
				exit;
			}

			//===========================================================
			//ENVIO EMAIL E SMS AVISANDO ALTERAÇÃO DA CONIGURAÇÃO

			//SMS
			if(!empty($telefone_principal)){

				//INSTANCIO SMS
				$this->core->includeHelper('sms');
				$sms = new smsHelper();

				//DADOS DE SMS
				$sms->setUsername($sms_username);
				$sms->setSenha($sms_senha);

				//MONTO DADOS
				$telefone = $sms->trato_num_telefone($telefone_principal);
				$id       = $sms->gero_id();
				$mensagem = "Olá, as configurações do sistema ".$sms_nome." foram alteradas Hoje dia ".date('d/m/Y')." ás ".date('H:i:s').". ";

				//DISPARO SMS
				$msg_list .= "55{$telefone};{$mensagem};{$id}"."\n";
				$sms->setMensagem($msg_list);
				$return = $sms->envia_sms();

				//GRAVO LOG SMS
				if(count($return)==0){
					$this->funcoes->set_array(null,'erro','As configurações de SMS estão incorretas. Verifique se os dados estão corretos!'); // Mensagem de erro
					echo json_encode($this->funcoes->get_array());
					exit();
				}
				for ($i=0; $i <count($return) ; $i++) {
					$this->logs->setApp($this->dir_app) //Pasta da aplicação
					->setId_sms($id)->setTelefone($telefone_principal)->setStatus(''.$return[$i].'')->setMensagem($mensagem)->gravo_log_sms_enviado();
					if($return[$i] != '000'){
						$this->funcoes->set_array(null,'erro','As configurações de SMS estão incorretas. Verifique se os dados estão corretos! <br /> <strong> Código do Erro: '.$return[$i].'</strong> '); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						exit();
					}
				}
			}

			//EMAIL
			$configs_admin = $this->model->retorno_configs_admin();
			if(!empty($email_principal)){

				//SETO OS DADOS DA VIEW
				$this->core->includeView();
				$view = new view($this->dir_app);
				$view->seto_dados('so',$this->funcoes->identifico_so());
				$view->seto_dados('navegador',$this->funcoes->identifico_navegador());
				$view->seto_dados('ip',$_SERVER['REMOTE_ADDR']);
				$view->seto_dados('assinatura_email',$email_assinatura);
				$view->seto_dados('nome_empresa',$smtp_nome);

				//MONTO O E-MAIL
				$this->email->setConexoes('true');
				$this->email->setHost_smtp($smtp_host);
				$this->email->setUsername_smtp($smtp_username);
				$this->email->setSenha_smtp($smtp_senha);
				$this->email->setPorta_smtp($smtp_porta);
				$this->email->setTls_smtp($smtp_tls);
				$this->email->setEmail_from($smtp_username); //email remetente
				$this->email->setNome_remetente($smtp_nome); //nome remetente
				$this->email->setEmail_send(array($email_principal)); //destinatario
				$this->email->setEmail_resposta($smtp_username); //email resposta
				$this->email->setNome_resposta($smtp_nome); //nome resposta
				$this->email->setAssunto('Configurações Alteradas'); //Assunto
				$this->email->setConteudo($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_aviso_alt_configuracoes.phtml')); //Conteúdo

				//ENVIO EMAIL
				$exec_email = $this->email->envio_email_phpmailer();
				if($exec_email != true){
					$this->logs->setApp($this->dir_app)
					->setEmail_re($configs_admin[0]['smtp_username'])->setEmail($email_principal) //E-mail Remetene - E-mail destinatario
					->setStatus('Erro no envio')->setMensagem($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_aviso_alt_configuracoes.phtml'))->gravo_log_email_enviado();
					$this->funcoes->set_array(null,'erro','As configurações de E-mail estão incorretas. Verifique se os dados estão corretos!'); // Mensagem de erro
					echo json_encode($this->funcoes->get_array());
					exit();
				}else{
					$this->logs->setApp($this->dir_app)
					->setEmail_re($configs_admin[0]['smtp_username'])->setEmail($email_principal) //E-mail Remetene - E-mail destinatario
					->setStatus('Enviado')->setMensagem($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_aviso_alt_configuracoes.phtml'))->gravo_log_email_enviado();
				}
			}

			//===========================================================
			//SE TIVER UPLOAD DE IMAGEM

			//VERIFICO SE TEM IMAGEM
			$this->model->setCampos('adm_configuracoes');
			if($this->model->retorn_campo_editar_val_id('img_fundo_login') == ''){
				if($this->model->retorn_campo_editar('img_fundo_login', '') == ''){
					if (empty($img_fundo_login['tmp_name'])) {
						$this->funcoes->set_array(null,'erro','Selecione uma imagem de fundo geral.'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						exit();
					}
				}
			}

			//SETO DADOS DE UPLOAD DE ARQUIVO
			$upload->setPasta('admin')->setArquivo($img_fundo_login)->setNome_arquivo('')->setTipo_arquivo('jpg')
				   ->setTamanho(5)->setValido_dimensoes(true)->setRedimensiono(false)->setUpload_multiplo(false)
				   ->setWidth(1280)->setHeight(721)
				   ->setWidth_p(0)->setHeight_p(0)->setRes_p(0)
				   ->setWidth_m(0)->setHeight_m(0)->setRes_m(0)
				   ->setWidth_g(0)->setHeight_g(0)->setRes_g(0)
				   ->upload_file();

			//RETORNO ARRAY COM ERROS
			$erro_upload = $upload->getMsg_erro();
			if (!empty($erro_upload)) {
				$this->funcoes->set_array(null,'erro','<b>Imagem de fundo geral</b> <br />'.$this->funcoes->get_errors_inline($erro_upload)); // Mensagem de erro
				echo json_encode($this->funcoes->get_array());
				exit();
			}

			//RETORNO ARRAY COM NOMES DE ARQUIVOS UPADOS
			$img_fundo_login = $upload->getNome_arquivo_return();

			//EXCLUO IMAGEM ANTIGA
			if(!empty($img_fundo_login[0])){
				$this->model->setCampos('campo_tabela',"adm_configuracoes");
				$this->model->setCampos('campo_coluna',"id");
				$this->model->setCampos('campo_coluna2',"img_fundo_login");
				$this->model->setCampos('campo_where',"id = {$id}");
				$valor = $this->model->select_simples_retorna_array_mont_vcol();
				if(count($valor) >= 1){
					for ($i=0; $i < count($valor) ; $i++) {
						unlink($this->core->get_config('dir_files_comp')."/admin/".$valor[$i]['img_fundo_login']);
						unlink($this->core->get_config('dir_files_comp')."/admin/p/".$valor[$i]['img_fundo_login']);
						unlink($this->core->get_config('dir_files_comp')."/admin/m/".$valor[$i]['img_fundo_login']);
						unlink($this->core->get_config('dir_files_comp')."/admin/g/".$valor[$i]['img_fundo_login']);
					}
				}
				$this->model->getLimpoCampos();
			}

			//===========================================================
			//SETO OS DADOS ****-
			$this->model->setId(1);
			$this->model->setCampos('smtp_host',$smtp_host);
			$this->model->setCampos('smtp_username',$smtp_username);
			$this->model->setCampos('smtp_senha',$smtp_senha);
			$this->model->setCampos('smtp_porta',$smtp_porta);
			$this->model->setCampos('smtp_tls',$smtp_tls);
			$this->model->setCampos('smtp_debug',$smtp_debug);
			$this->model->setCampos('email_principal',$email_principal);
			$this->model->setCampos('email_assinatura',$email_assinatura);
			$this->model->setCampos('sms_username',$sms_username);
			$this->model->setCampos('sms_senha',$sms_senha);
			$this->model->setCampos('telefone_principal',$telefone_principal);
			$this->model->setCampos('nome_logo_admin',$nome_logo_admin);
			$this->model->setCampos('versao_aplicacao',$versao_aplicacao);
			$this->model->setCampos('tema_admin','');
			$this->model->setCampos('img_fundo_login',$img_fundo_login[0]);
			$this->model->setCampos('smtp_nome',$smtp_nome);
			$this->model->setCampos('sms_nome',$sms_nome);
			$this->model->setCampos('modo_sistema',$modo_sistema);

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
		$tipo = $this->funcoes->anti_injection($_GET['tipo']); // tipo
		$id   = $this->funcoes->anti_injection($_GET['id']); // tipo

		//===========================================================
		//LISTAGEM DE TESTE
		if($tipo == 'img_fundo_login'){
			$this->model->setCampos('campo_tabela',"adm_configuracoes");
			$this->model->setCampos('campo_coluna',"id");
			$this->model->setCampos('campo_coluna2',"img_fundo_login");
			$this->model->setCampos('campo_where',"id = {$id}");
			$valor = $this->model->select_simples_retorna_array_mont_vcol();
			if(count($valor) >= 1){
				for ($i=0; $i < count($valor) ; $i++) {
					$arquivo = $valor[$i]['img_fundo_login'];
					$array[] = array(
						'id_arquivo'   => $valor[$i]['id'],
						'path_arquivo' => $this->core->get_config('dir_raiz_http').'files/admin/',
						'url_arquivo'  => $valor[$i]['img_fundo_login'],
						'descricao'    => $valor[$i]['img_fundo_login'],
					);
				}
			}
			if(!empty($arquivo)){
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
		if($tipo == 'img_fundo_login'){
			//PARAMETROS ****-
			$tabela      = 'adm_configuracoes'; //tabela do bd
			$nome_pagina = 'Imagem de Login'; //nome da página

			//SE FOR UPLOAD ÚNICO - Atualizo o campo da tabela para vazio
			$this->model->setCampos('campo_tabela',$tabela);
			$this->model->setCampos('campo_coluna',"img_fundo_login"); // ****-
			$this->model->setCampos('campo_id',"id"); // ****-
			$this->model->setCampos('campo_value',""); //vazio
			$this->model->setId($id);
            $exec = $this->model->update_geral();

			//SE FOR UPLOAD MULTIPLO - Excluo o registro da tabela
			/*$this->model->setCampos('campo_tabela',$tabela);
			$this->model->setCampos('campo_id',"id"); // ****-
			$this->model->setId($id);
            $exec = $this->model->excluir_geral();*/

			//EXCLUO DO SERVIDOR ****-
			unlink($this->core->get_config('dir_files_comp')."/admin/".$url_arquivo);
			unlink($this->core->get_config('dir_files_comp')."/admin/p/".$url_arquivo);
			unlink($this->core->get_config('dir_files_comp')."/admin/m/".$url_arquivo);
			unlink($this->core->get_config('dir_files_comp')."/admin/g/".$url_arquivo);

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
