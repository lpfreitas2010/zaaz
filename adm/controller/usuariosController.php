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
	$control = new usuariosController();  // ****-
}

//CLASS ****-
class usuariosController {

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
		$this->core->includeController('usuarios',$this->dir_app); //incluo model ****-

		//INSTANCIO
		$this->funcoes           = new funcoes();     //Instancio Funções
		$this->model             = new usuariosModel();  //Instancio Model ****-
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
            $this->url_pagina                    = "usuarios"; //Url da página
            $this->nome_pagina_singular          = "Usuário"; // Nome da página singular
            $this->nome_pagina_plural            = "Admin Usuários"; // Nome da página plural
            $this->btns_acoes['foco_campo_form'] = "nome"; // Foco campo form add e edd

			//===========================================================
			//CAMPOS DEFAULT
	    	require $this->core->includeControllerInclude("carrego_parametros_0", $this->dir_app);

			//===========================================================
			//TEXTOS GERAIS ****-


			//===========================================================
			//STATUS DE BTNS DE AÇÕES GERAL ****-
			$this->btns_acoes['status_btn_editar']        = true; // Status do btn de editar [true or false]
			if($_SESSION['adm_id_cargo'] == 1){
				$this->btns_acoes['status_btn_excluir'] = true; // Status do btn de excluir [true or false]
			}else{
				$this->btns_acoes['status_btn_excluir'] = false; // Status do btn de excluir [true or false]
			}
			$this->btns_acoes['status_btn_detalhamento']  = true; // Status do btn de detalhamento [true or false]
			$this->btns_acoes['status_btn_ativar']        = true; // Status do btn de ativar [true or false]
			$this->btns_acoes['status_btn_novo']          = true; // Status de btn de cadastro [true or false]
			$this->btns_acoes['status_btn_atualizar']     = true; // Status de btn de atualizar [true or false]
			$this->btns_acoes['status_imprimir']          = true; // Status de btn de imprimir [true or false]
			$this->btns_acoes['status_exportar_pdf']      = true; // Status de btn de exportar para pdf [true or false]
			$this->btns_acoes['status_exportar_csv']      = true; // Status de btn de exportar para csv [true or false]

			//===========================================================
			//STATUS DE ÁREA DA LISTAGEM ****-
			$this->btns_acoes['status_btn_filtros']            = true; // Status do botão de filtros [true or false]

			//===========================================================
			//CONFIGURAÇÕES DE ÁREA DA LISTAGEM ****-
            $this->btns_acoes['title_campo_pesquisa'] = 'ID, Nome, Sexo, E-mail, Username, Telefone, Bairro, Estado ou Cidade'; // Parametros de pesquisa
            $this->btns_acoes['ordenar_selecionado']  = array("ID DECRESCENTE"); // Value padrão do campo Odernar
            $this->btns_acoes['agrupar_selecionado']  = array(""); // Value padrão do campo Agrupar

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
				$this->btns_acoes['mensagem_informativa'] = 'Ao excluir um Usuário todos os dados relacionados a este usuário também serão excluídos do sistema!'; // Mensagem informativa relacionada a tabela
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
//LOGIN NO SISTEMA
//***************************************************************************************************************************************************************
    function login_sistema(){

		//===========================================================
        //RECEBO OS DADOS
        $username  = $this->funcoes->anti_injection($_POST['username']);
        $senha     = $this->funcoes->anti_injection($_POST['senha']);
        $senha_md5 = $this->funcoes->anti_injection(md5($_POST['senha']));

		//INSTANCIO SMS
		$this->core->includeHelper('sms');
		$sms = new smsHelper();

		//CORE
		$this->core->includeControllerView('core',$this->dir_app); //Incluo arquivo coreController.php
		$controller_geral = new coreController();

		//===========================================================
		//CONTADOR
		if(empty($_SESSION[$this->dir_app.'_cont_login'])){
			$_SESSION[$this->dir_app.'_cont_login'] = 0;
		}
		if(empty($_SESSION[$this->dir_app.'_cont_login1'])){
			$_SESSION[$this->dir_app.'_cont_login1'] = 0;
		}

		//===========================================================
        //SETO OS DADOS
        $this->model->setUsername($username); //Seto o username
        $this->model->setSenha($senha_md5); // Seto a senha

		//===========================================================
        //VALIDO OS DADOS
        $this->funcoes->set($this->core->get_msg_array('msg_username'),"username", $username)->is_required();
        $this->funcoes->set($this->core->get_msg_array('msg_senha'),"senha", $senha)->is_required();

		//===========================================================
        //MENSAGEM DE ERROS
        if(count($this->funcoes->get_errors())>=1){
            echo json_encode($this->funcoes->get_errors());
            exit;
        }

		//===========================================================
		//BARRO TERCEIRA TENTATIVA DE LOGIN
		$this->model->setUsername($username);
		$exec__ = $this->model->retorno_username_valido_email();
		if($exec__ === true){
			if($_SESSION[$this->dir_app.'_cont_login'] >= $this->config_apps->get_config('num_tentativas_bloq_requisicoes')){
				if($exec__ === true){

					//===========================================================
					//INSIRO NOTIFICACAO
					$usuario_id_ = $this->model->getCampos('id_user');
					$nome_       = $this->model->getNome();
					$controller_geral->insiro_notificacao('fa fa-pencil-square-o',"Olá {$nome_}, Ocorreram várias tentativas mal sucedidas de acesso a sua conta. Se você fez isso, você pode desconsiderar esta notificação. Se você não fez isso, altere sua senha.",'usuarios/editar/'.$usuario_id_,$usuario_id_,'Alterar Senha');

					//===========================================================
					//ENVIO SMS ---
					$configs_admin = $this->model->retorno_configs_admin();
					$telefone = $this->model->getCampos('usertelefone');
					if(!empty($telefone)){

						//DADOS DE SMS
						$sms->setUsername($configs_admin[0]['sms_username']);
						$sms->setSenha($configs_admin[0]['sms_senha']);

						//MONTO DADOS
						$telefone = $sms->trato_num_telefone($telefone);
						$id       = $sms->gero_id();
						$nome     = substr($this->model->getNome(), 0, 10);
						$mensagem = "Olá {$nome}, Ocorreram várias tentativas mal sucedidas de acesso a sua conta {$configs_admin[0]['sms_nome']}.";

						//DISPARO SMS
						$msg_list .= "55{$telefone};{$mensagem};{$id}"."\n";
						$sms->setMensagem($msg_list);
						$return = $sms->envia_sms();

						//GRAVO LOG SMS
						for ($i=0; $i <count($return) ; $i++) {
	   					    $this->logs->setApp($this->dir_app) //Pasta da aplicação
	   					               ->setId_sms($id)->setTelefone($telefone)->setStatus(''.$return[$i].'')->setMensagem($mensagem)->gravo_log_sms_enviado();

						}
					}

					//===========================================================
					//ENVIO EMAIL ---

					//SETO OS DADOS DA VIEW
					$this->core->includeView();
					$view = new view($this->dir_app);
					$view->seto_dados('nome',$this->model->getNome());
					$view->seto_dados('username',$username);
					$view->seto_dados('so',$this->funcoes->identifico_so());
					$view->seto_dados('navegador',$this->funcoes->identifico_navegador());
					$view->seto_dados('ip',$_SERVER['REMOTE_ADDR']);
					$view->seto_dados('assinatura_email',$configs_admin[0]['email_assinatura']);
					$view->seto_dados('nome_empresa',$configs_admin[0]['smtp_nome']);

					//MONTO O E-MAIL
					$this->email->setConexoes('true');
					$this->email->setHost_smtp($configs_admin[0]['smtp_host']);
					$this->email->setUsername_smtp($configs_admin[0]['smtp_username']);
					$this->email->setSenha_smtp($configs_admin[0]['smtp_senha']);
					$this->email->setPorta_smtp($configs_admin[0]['smtp_porta']);
					$this->email->setTls_smtp($configs_admin[0]['smtp_tls']);
					$this->email->setEmail_from($configs_admin[0]['smtp_username']); //email remetente
					$this->email->setNome_remetente($configs_admin[0]['smtp_nome']); //nome remetente
					$this->email->setEmail_send(array($this->model->getEmail())); //destinatario
					$this->email->setEmail_resposta($configs_admin[0]['smtp_username']); //email resposta
					$this->email->setNome_resposta($configs_admin[0]['smtp_nome']); //nome resposta
					$this->email->setAssunto('Atividade suspeita em sua conta'); //Assunto
					$this->email->setConteudo($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_atividade_suspeita.phtml')); //Conteúdo

					//ENVIO EMAIL
					$exec_email = $this->email->envio_email_phpmailer();
					if($exec_email != true){
						$this->logs->setApp($this->dir_app)
						->setEmail_re($configs_admin[0]['smtp_username'])->setEmail($this->model->getEmail()) //E-mail Remetene - E-mail destinatario
						->setStatus('Erro no envio')->setMensagem($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_atividade_suspeita.phtml'))->gravo_log_email_enviado();
					}else{
						$this->logs->setApp($this->dir_app)
						->setEmail_re($configs_admin[0]['smtp_username'])->setEmail($this->model->getEmail()) //E-mail Remetene - E-mail destinatario
						->setStatus('Enviado')->setMensagem($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_atividade_suspeita.phtml'))->gravo_log_email_enviado();
					}

					//CONTADOR
					$_SESSION[$this->dir_app.'_cont_login1'] = $_SESSION[$this->dir_app.'_cont_login1'] + 1;
				}

				//ZERO CONTADOR
			 	unset($_SESSION[$this->dir_app.'_cont_login']);
			}

			//===========================================================
			//FORÇO USUARIO A ALTERAR SUA SENHA QUANDO ERRAR MUITO A SENHA
			if($_SESSION[$this->dir_app.'_cont_login1'] >= $this->config_apps->get_config('num_tentativas_bloq_requisicoes')){

				//GRAVO LOG
				$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
				->setMensagem('Usuário '.$username.' forçado a alterar sua senha.')->gravo_log();

				//ZERO CONTADOR
				unset($_SESSION[$this->dir_app.'_cont_login1']);

				//FORCE SENHA
				$force_senha = true;
			}

			//===========================================================
			//FORÇO USUARIO A ALTERAR SUA SENHA QUANDO TIVER O CAMPO FORCE SENHA NO BANCO
			if($force_senha == false){
				$this->model->setId($this->model->getUsuario_id());
				$force_senha = $this->model->return_force_senha();
			}
			if($force_senha == true){

				//INSIRO O TOKEN NO BANCO E ENVIO O SMS E EMAIL COM O TOKEN
				$id    = $this->model->getUsuario_id();
				$exec_ = $this->cad_reenviar_token($id,1);
				if($exec_ == true){
					  $this->funcoes->set_array(null,'sucesso','Aguarde')->set_array(null,'redireciono','cadastro/'.$this->funcoes->mycrypt($id).''); //Mensagem
					  echo json_encode($this->funcoes->get_array()); //Mostro na tela
					  exit();
				}
			}
		}

		//===========================================================
        //VALIDO E RETORNO
        $exec = $this->model->valido_login();
		sleep(3);

		//===========================================================
        //SUCESSO
        if($exec === true){

          //SETO DADOS
          $_SESSION[$this->dir_app.'_id_user']     = $this->model->getUsuario_id();
          $_SESSION[$this->dir_app.'_nome_user']   = $this->model->getNome();
          $_SESSION[$this->dir_app.'_email_user']  = $this->model->getUseremail();
          $_SESSION[$this->dir_app.'_foto_perfil'] = $this->model->getImg_perfil();
          $_SESSION[$this->dir_app.'_auth_status'] = true;
		  $_SESSION[$this->dir_app.'_id_cargo']    = $this->model->retorno_cargo_usuario($this->model->getUsuario_id());

		  //SETO OS DADOS DE CONFIGURAÇÕES
		  $configs_admin = $this->model->retorno_configs_admin();
		  $_SESSION[$this->dir_app.'_smtp_host']          = $configs_admin[0]['smtp_host'];
		  $_SESSION[$this->dir_app.'_smtp_username']      = $configs_admin[0]['smtp_username'];
		  $_SESSION[$this->dir_app.'_smtp_senha']         = $configs_admin[0]['smtp_senha'];
		  $_SESSION[$this->dir_app.'_smtp_porta']         = $configs_admin[0]['smtp_porta'];
		  $_SESSION[$this->dir_app.'_smtp_tls']           = $configs_admin[0]['smtp_tls'];
		  $_SESSION[$this->dir_app.'_email_principal']    = $configs_admin[0]['email_principal'];
		  $_SESSION[$this->dir_app.'_sms_username']       = $configs_admin[0]['sms_username'];
		  $_SESSION[$this->dir_app.'_sms_senha']          = $configs_admin[0]['sms_senha'];
		  $_SESSION[$this->dir_app.'_telefone_principal'] = $configs_admin[0]['telefone_principal'];
		  $_SESSION[$this->dir_app.'_nome_logo_admin']    = $configs_admin[0]['nome_logo_admin'];
		  $_SESSION[$this->dir_app.'_versao_admin']       = $configs_admin[0]['versao_aplicacao'];
		  $_SESSION[$this->dir_app.'_tema_admin']         = $configs_admin[0]['tema_admin'];
		  $_SESSION[$this->dir_app.'_smtp_nome']          = $configs_admin[0]['smtp_nome'];
		  $_SESSION[$this->dir_app.'_sms_nome']           = $configs_admin[0]['sms_nome'];
		  $_SESSION[$this->dir_app.'_modo_sistema']       = $configs_admin[0]['modo_sistema'];

          //PAGINA DE REDIRECIONAMENTO
          if($_SESSION[$this->dir_app.'_page_historico'] == ""){
              if(!empty($_COOKIE['pg_hist'.$this->dir_app])){ //Pego o cokkie
                  $pagina = $this->funcoes->decrypt($_COOKIE['pg_hist'.$this->dir_app]);
              }else{
                  $pagina = $this->config_apps->getPaginas(2);
              }
          }else{
              $pagina = $_SESSION[$this->dir_app.'_page_historico']; //Pego a sessão
          }

		  //EXECUTO FUNÇÕES DO CORE AO EFETUAR LOGIN
		  $controller_geral->core_action_login();

		  //MOSTRO MENSAGEM DE SUCESSO
		  $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('auth_sucesso')) //Mensagem
						->set_array(null,'redireciono',$pagina) //Redireciono
						->set_array(null,'tempo','90000'); //tempo
		  echo json_encode($this->funcoes->get_array()); //Mostro na tela

		  //ZERO CONTADOR
		  unset($_SESSION[$this->dir_app.'_cont_login']);

          //GRAVO LOG DE ACESSO
          $this->logs->setApp($this->dir_app)->setMensagem('Login')->gravo_log_acesso();

          //GRAVO LOG
          $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
          ->setMensagem($this->core->get_msg_array('auth_sucesso_log',$username))->gravo_log();
          exit();

        }else{

		  //CONTADOR
		  $_SESSION[$this->dir_app.'_cont_login'] = $_SESSION[$this->dir_app.'_cont_login'] + 1;

		  //MOSTRO MENSAGEM DE ERRO
		  $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('erro_autenticacao',$exec)); //Mensagem
		  echo json_encode($this->funcoes->get_array()); //Mostro na tela

          //GRAVO LOG
          $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
          ->setMensagem($this->core->get_msg_array('erro_autenticacao_log',$username))->gravo_log();
          sleep(3);
          exit();
        }
    }





//***************************************************************************************************************************************************************
//DESTRUO SESSÃO NO SISTEMA
//***************************************************************************************************************************************************************
    function logoff(){
	      $this->autentico_usuario(); //Autentico usuário no sistema
	      //**********************************************************

	      //DESTRUO VISITANTE ONLINE
	      $usuario_sessao = new usuario_sessao();
	      $usuario_sessao->destruo_visitante($this->dir_app);

	      //GRAVO LOG DE ACESSO
	      $this->logs->setApp($this->dir_app)->setMensagem('Logoff')->gravo_log_acesso();

	      //GRAVO LOG
	      $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
	      ->setMensagem($this->core->get_msg_array('logoff_log'))->gravo_log();

	      //DESTRUO SESSÃO E REDIRECIONO
	      $this->funcoes->destroy_usuario();
	      header('location: ../'.$this->config_apps->getPaginas(2).''); //Redireciono
	      exit();
    }





//***************************************************************************************************************************************************************
//EXCLUO COOKIES DE LOGIN AUTOMATIZADO
//***************************************************************************************************************************************************************
    function excluo_cookies_sessao(){
        setcookie("nm_tmp".$this->dir_app, "", time()-60000, "/");
        setcookie("eml_tmp".$this->dir_app, "", time()-60000, "/");
        setcookie("ft_tmp".$this->dir_app, "", time()-60000, "/");
        header('location: ../'.$this->config_apps->get_config('url_index').''); //Redireciono
    }





//***************************************************************************************************************************************************************
//ESQUECI OS DADOS DE ACESSO
//***************************************************************************************************************************************************************
    function esqueci_senha(){

        //RECEBO OS DADOS
        $username = $this->funcoes->anti_injection($_POST['username_esqueci_senha']);

        //VALIDO OS DADOS
        $this->funcoes->set($this->core->get_msg_array('msg_username'),"username_esqueci_senha", $username)->is_required();

        //MENSAGEM DE ERROS
        if(count($this->funcoes->get_errors())>=1){
            echo json_encode($this->funcoes->get_errors());
            exit;
        }

        //SETO OS DADOS
        $this->model->setUsername($username);

        //VERIFICO E RETORNO
        $exec = $this->model->retorno_username_valido_email();
        if($exec === true){

			//INSIRO O TOKEN NO BANCO E ENVIO O SMS E EMAIL COM O TOKEN
			$id    = $this->model->getUsuario_id();
			$exec_ = $this->cad_reenviar_token($id,1);
			if($exec_ == true){
	      		  $this->funcoes->set_array(null,'sucesso','Aguarde')->set_array(null,'redireciono','cadastro/'.$this->funcoes->mycrypt($id).''); //Mensagem
	      		  echo json_encode($this->funcoes->get_array()); //Mostro na tela
			}

        }else{

		  //MOSTRO MENSAGEM DE ERRO
		  $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('erro_esqueci_senha_user_adm')); //Mensagem
		  echo json_encode($this->funcoes->get_array()); //Mostro na tela
          $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
          ->setMensagem($this->core->get_msg_array('erro_esqueci_senha_user_log',$exec))->gravo_log();
		  sleep(2);
          exit();
        }
    }

//***************************************************************************************************************************************************************
//CADASTRO VALIDO TOKEN
//***************************************************************************************************************************************************************
    function cad_valido_token(){

		//===========================================================
		//RECEBO OS DADOS
		$id    = $this->funcoes->anti_injection($_POST['form-_r']); //Padrão
		$token = $this->funcoes->anti_injection($_POST['codigo']); //token

		//===========================================================
		//DESCRIPTOGRAFO OS DADOS
		$id = $this->funcoes->decrypt($id);

		//===========================================================
		//VALIDO OS DADOS
		$this->funcoes->set('Código',"codigo", $token)->is_required();

		//===========================================================
		//MOSTRO MENSAGEM DE ERROS NA TELA
		if(count($this->funcoes->get_errors())>=1){
			echo json_encode($this->funcoes->get_errors());
			exit;
		}

		//===========================================================
		//SETO OS DADOS
		$this->model->setId($id);
		$this->model->setCampos('token',$token);

		//===========================================================
		//VERIFICO SE TOKEN É VÁLIDO
		if(!is_numeric($id)){ // verifico se id é numérico
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('is_num',$id))->gravo_log(); // Gravo log
			exit;
		}
		if($this->model->valido_token() == false){
			$this->funcoes->set_array(null,'erro','O Código informado é inválido!'); //Mensagem
			echo json_encode($this->funcoes->get_array()); //Mostro na tela
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem('Cadastro - Token inválido!')->gravo_log(); // Gravo log
			sleep(2);
			exit();
		}

		//===========================================================
		//MENSAGENS E LOGS
		$this->funcoes->set_array(null,'sucesso','O Código foi validado com sucesso!')->set_array(null,'redireciono','../cadastro/'.$this->funcoes->mycrypt($id).'/'.$this->funcoes->mycrypt($token).''); //Mensagem Sucesso
		echo json_encode($this->funcoes->get_array()); //Mostro na tela
		$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
		->setMensagem('Cadastro - Token validado com sucesso! ID: '.$id.'')->gravo_log(); // Gravo log
		exit();
	}

//***************************************************************************************************************************************************************
//CADASTRO ALTERO SENHA
//***************************************************************************************************************************************************************
    function cad_altero_senha(){

		//===========================================================
		//RECEBO OS DADOS
		$id                      = $this->funcoes->anti_injection($_POST['form-_r']); //Padrão
		$token                   = $this->funcoes->anti_injection($_POST['form-_rs']); //token
		(string) $senha          = $this->funcoes->anti_injection(md5($_POST['senha']));
		(string) $senha_sem_hash = $this->funcoes->anti_injection($_POST['senha']);
		(string) $conf_senha     = $this->funcoes->anti_injection($_POST['conf_senha']);

		//INSTANCIO SMS
		$this->core->includeHelper('sms');
		$sms = new smsHelper();

		//===========================================================
		//DESCRIPTOGRAFO OS DADOS
		$id    = $this->funcoes->decrypt($id);
		$token = $this->funcoes->decrypt($token);

		//===========================================================
		//VALIDO OS DADOS
		$this->funcoes->set('Senha',"senha", $senha_sem_hash)->is_required()->min_length(7)->max_length(20)->is_password_num_let();
		$this->funcoes->set('Confirme sua senha',"conf_senha", $conf_senha)->is_required()->is_compare_campo($senha_sem_hash,"Senha");

		//===========================================================
		//MOSTRO MENSAGEM DE ERROS NA TELA
		if(count($this->funcoes->get_errors())>=1){
			echo json_encode($this->funcoes->get_errors());
			exit;
		}

		//SENHA IGUAL
		$this->model->setCampos('tabela_retorn_campo_editar','adm_usuario_auth');
		if($this->model->retorn_campo_editar('senha', $senha) == $senha){
			$senha_unique = true;
		}
		if($senha_unique == true){
			$this->funcoes->set_array(null,'erro','A nova senha deve ser diferente da senha antiga.'); //Mensagem
			echo json_encode($this->funcoes->get_array()); //Mostro na tela
			exit;
		}

		//===========================================================
		//SETO OS DADOS
		$this->model->setId($id);
		$this->model->setCampos('token',$token);
		$this->model->setSenha($senha);

		//===========================================================
		//VERIFICO SE TOKEN E ID É VÁLIDO
		if(!is_numeric($id)){ // verifico se id é numérico
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('is_num',$id))->gravo_log(); // Gravo log
			exit;
		}
		if($this->model->valido_token() == false){
			$this->funcoes->set_array(null,'erro','O Token informado é inválido!'); //Mensagem
		    echo json_encode($this->funcoes->get_array()); //Mostro na tela
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem('Cadastro - Alterar senha - Token inválido!')->gravo_log(); // Gravo log
			exit();
		}

		//===========================================================
		//EXECUTO
		$exec = $this->model->altero_senha();

		//===========================================================
		//PEGO OS DADOS DO USUARIO
		$dados        = $this->model->set_editar();
		$usertelefone = $dados[0]['usertelefone'];
		$useremail    = $dados[0]['useremail'];
		$nome         = $dados[0]['nome'];

		//===========================================================
		//ENVIO EMAIL E SMS AVISANDO DA ALTERAÇÃO DA SENHA

		//SMS
		$configs_admin = $this->model->retorno_configs_admin();
		if(!empty($usertelefone)){

			//DADOS DE SMS
			$sms->setUsername($configs_admin[0]['sms_username']);
			$sms->setSenha($configs_admin[0]['sms_senha']);

			//MONTO DADOS
			$telefone = $sms->trato_num_telefone($usertelefone);
			$id       = $sms->gero_id();
			$nome     = substr($nome, 0, 10);
			$mensagem = "Olá ".$nome.", Sua senha de acesso ao sistema {$configs_admin[0]['sms_nome']} foi redefinida Hoje dia ".date('d/m/Y')." ás ".date('H:i:s').". ";

			//DISPARO SMS
			$msg_list .= "55{$telefone};{$mensagem};{$id}"."\n";
			$sms->setMensagem($msg_list);
			$return = $sms->envia_sms();

			//GRAVO LOG SMS
			for ($i=0; $i <count($return) ; $i++) {
				$this->logs->setApp($this->dir_app) //Pasta da aplicação
						   ->setId_sms($id)->setTelefone($telefone)->setStatus(''.$return[$i].'')->setMensagem($mensagem)->gravo_log_sms_enviado();
			}
		}

		//EMAIL
		if(!empty($useremail)){

			//SETO OS DADOS DA VIEW
			$this->core->includeView();
			$view = new view($this->dir_app);
			$view->seto_dados('nome',$nome);
			$view->seto_dados('so',$this->funcoes->identifico_so());
			$view->seto_dados('navegador',$this->funcoes->identifico_navegador());
			$view->seto_dados('ip',$_SERVER['REMOTE_ADDR']);
			$view->seto_dados('assinatura_email',$configs_admin[0]['email_assinatura']);
			$view->seto_dados('nome_empresa',$configs_admin[0]['smtp_nome']);

			//MONTO O E-MAIL
			$this->email->setConexoes('true');
			$this->email->setHost_smtp($configs_admin[0]['smtp_host']);
			$this->email->setUsername_smtp($configs_admin[0]['smtp_username']);
			$this->email->setSenha_smtp($configs_admin[0]['smtp_senha']);
			$this->email->setPorta_smtp($configs_admin[0]['smtp_porta']);
			$this->email->setTls_smtp($configs_admin[0]['smtp_tls']);
			$this->email->setEmail_from($configs_admin[0]['smtp_username']); //email remetente
			$this->email->setNome_remetente($configs_admin[0]['smtp_nome']); //nome remetente
			$this->email->setEmail_send(array($useremail)); //destinatario
			$this->email->setEmail_resposta($configs_admin[0]['smtp_username']); //email resposta
			$this->email->setNome_resposta($configs_admin[0]['smtp_nome']); //nome resposta
			$this->email->setAssunto('Redefinição de Senha'); //Assunto
			$this->email->setConteudo($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_aviso_alt_senha.phtml')); //Conteúdo

			//ENVIO EMAIL
			$exec_email = $this->email->envio_email_phpmailer();
			if($exec_email != true){
				  $this->logs->setApp($this->dir_app)
				  ->setEmail_re($configs_admin[0]['smtp_username'])->setEmail($useremail) //E-mail Remetene - E-mail destinatario
				  ->setStatus('Erro no envio')->setMensagem($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_aviso_alt_senha.phtml'))->gravo_log_email_enviado();
			}else{
				$this->logs->setApp($this->dir_app)
				->setEmail_re($configs_admin[0]['smtp_username'])->setEmail($useremail) //E-mail Remetene - E-mail destinatario
				->setStatus('Enviado')->setMensagem($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_aviso_alt_senha.phtml'))->gravo_log_email_enviado();
			}
		}

		//===========================================================
		//MENSAGENS E LOGS
		if($exec == true){
			$this->funcoes->set_array(null,'sucesso','Senha cadastrada com sucesso!')->set_array(null,'redireciono',''); //Mensagem Sucesso
			echo json_encode($this->funcoes->get_array()); //Mostro na tela
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem('Cadastro - Senha cadastrada com sucesso! ID: '.$id.'')->gravo_log(); // Gravo log
			exit();
		}else{
			$this->funcoes->set_array(null,'erro','Erro ao cadastrar a senha!'); //Mensagem Erro
			echo json_encode($this->funcoes->get_array()); //Mostro na tela
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem('Cadastro - Erro ao cadastrar a senha. ID: '.$id.' - '.$exec.' ')->gravo_log(); // Gravo log
			exit();
		}
	}

//***************************************************************************************************************************************************************
//CADASTRO REENVIAR TOKEN
//***************************************************************************************************************************************************************
	function cad_reenviar_token($id_ = null,$tipo = null){

		//===========================================================
		//RECEBO OS DADOS
		if(!empty($id_)){
			$id       = $this->funcoes->anti_injection($id_); //
			$tipo_    = $tipo;
			$msg_tipo = 1;
		}else{
			$id    = $this->funcoes->anti_injection($_GET['d']); //
			$id_sc = $this->funcoes->anti_injection($_GET['id']); //
			$tipo_ = $this->funcoes->anti_injection($_GET['t']); //

			//===========================================================
			//DESCRIPTOGRAFO OS DADOS
			if(empty($id_sc)){
				$id = $this->funcoes->decrypt($id);
			}else{
				$id = $id_sc;
			}
			$msg_tipo = 2;
		}

		//INSTANCIO SMS
		$this->core->includeHelper('sms');
		$sms = new smsHelper();

		//===========================================================
		//VERIFICO SE ID É VÁLIDO
		if(!is_numeric($id)){ // verifico se id é numérico
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('is_num',$id))->gravo_log(); // Gravo log
			exit;
		}
		if(!is_numeric($tipo_)){ // verifico se $tipo é numérico
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('is_num',$tipo_))->gravo_log(); // Gravo log
			exit;
		}

		//===========================================================
		//SETO OS DADOS
		$this->model->setId($id);

		//===========================================================
		//PEGO OS DADOS DO USUARIO
		$dados        = $this->model->set_editar();
		$usertelefone = $dados[0]['usertelefone'];
		$useremail    = $dados[0]['useremail'];
		$nome         = $dados[0]['nome'];

		//===========================================================
		//GERO TOKEN E INSIRO O TOKEN NO BANCO
		$token = $this->funcoes->gero_cod_numerico(7);
		$this->model->setCampos('token',$token);
		$exec = $this->model->insiro_token();
		if($exec == true){
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
		    ->setMensagem('Token inserido com sucesso. '.$token.' - '.$useremail.' ')->gravo_log();
		}else{
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
		    ->setMensagem('Erro ao inserir token no banco de dados. '.$token.' - '.$useremail.' - '.$exec.'')->gravo_log();
		    exit();
		}

		//===========================================================
		//ENVIO SMS
		$configs_admin = $this->model->retorno_configs_admin();
		if(!empty($usertelefone)){

			//DADOS DE SMS
			$sms->setUsername($configs_admin[0]['sms_username']);
			$sms->setSenha($configs_admin[0]['sms_senha']);

			//MONTO DADOS
			$telefone = $sms->trato_num_telefone($usertelefone);
			$id       = $sms->gero_id();
			$nome     = substr($nome, 0, 10);
			if($tipo_ == 1){
				$mensagem = "Olá ".$nome.", ".$token." é o seu código de recuperação da sua conta {$configs_admin[0]['sms_nome']}.";
			}
			if($tipo_ == 2){
				$mensagem = "Olá ".$nome.", foi enviado em seu e-mail o código de recuperação da sua conta {$configs_admin[0]['sms_nome']}.";
			}

			//DISPARO SMS
			$msg_list .= "55{$telefone};{$mensagem};{$id}"."\n";
			$sms->setMensagem($msg_list);
			$return = $sms->envia_sms();

			//GRAVO LOG SMS
			for ($i=0; $i <count($return) ; $i++) {
				$this->logs->setApp($this->dir_app) //Pasta da aplicação
						->setId_sms($id)->setTelefone($telefone)->setStatus(''.$return[$i].'')->setMensagem($mensagem)->gravo_log_sms_enviado();
			}
		}

		//===========================================================
		//ENVIO EMAIL
		if(!empty($useremail)){

			//SETO OS DADOS DA VIEW
			$this->core->includeView();
			$view = new view($this->dir_app);
			if($tipo_ == 1){
				$view->seto_dados('tipo','1');
			}
			if($tipo_ == 2){
				$view->seto_dados('tipo','2');
			}
			$view->seto_dados('nome',$nome);
			$view->seto_dados('useremail',$useremail);
			$view->seto_dados('token',$this->funcoes->mycrypt($token));
			$view->seto_dados('token_',$token);
			$view->seto_dados('id',$this->funcoes->mycrypt($id));
			$view->seto_dados('site',$this->core->get_config('servidor_ativo_comp'));
			$view->seto_dados('path_raiz',$this->core->get_config('dir_raiz_http').$this->dir_app."/");
			$view->seto_dados('assinatura_email',$configs_admin[0]['email_assinatura']);
			$view->seto_dados('nome_empresa',$configs_admin[0]['smtp_nome']);

			//MONTO O E-MAIL
			$this->email->setConexoes('true');
			$this->email->setHost_smtp($configs_admin[0]['smtp_host']);
			$this->email->setUsername_smtp($configs_admin[0]['smtp_username']);
			$this->email->setSenha_smtp($configs_admin[0]['smtp_senha']);
			$this->email->setPorta_smtp($configs_admin[0]['smtp_porta']);
			$this->email->setTls_smtp($configs_admin[0]['smtp_tls']);
			$this->email->setEmail_from($configs_admin[0]['smtp_username']); //email remetente
			$this->email->setNome_remetente($configs_admin[0]['smtp_nome']); //nome remetente
			$this->email->setEmail_send(array($useremail)); //destinatario
			$this->email->setEmail_resposta($configs_admin[0]['smtp_username']); //email resposta
			$this->email->setNome_resposta($configs_admin[0]['smtp_nome']); //nome resposta
			$this->email->setAssunto('Recuperação da Conta'); //Assunto
			$this->email->setConteudo($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_token.phtml')); //Conteúdo

			//ENVIO EMAIL
			$exec_email = $this->email->envio_email_phpmailer();
			if($exec_email != true){
				  $this->funcoes->set_array(null,'erro','Erro ao enviar e-mail com código de recuperação! '.$exec_email.' '); //Mensagem
				  echo json_encode($this->funcoes->get_array()); //Mostro na tela
				  $this->logs->setApp($this->dir_app)
  				  ->setEmail_re($configs_admin[0]['smtp_username'])->setEmail($useremail) //E-mail Remetene - E-mail destinatario
  				  ->setStatus('Erro ao enviar')->setMensagem($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_token.phtml'))->gravo_log_email_enviado();
				  exit();
			}else{
				$this->logs->setApp($this->dir_app)
				->setEmail_re($configs_admin[0]['smtp_username'])->setEmail($useremail) //E-mail Remetene - E-mail destinatario
				->setStatus('Enviado')->setMensagem($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_token.phtml'))->gravo_log_email_enviado();
			}
		}

		//===========================================================
		//RETORNO MENSAGEM DE SUCESSO
		if($msg_tipo == 1){
			return true;
		}
		if($msg_tipo == 2){
			$this->funcoes->set_array(null,'sucesso','O Código de recuperação da conta foi enviado com sucesso!'); //Mensagem
			echo json_encode($this->funcoes->get_array()); //Mostro na tela
		}
	}

//***************************************************************************************************************************************************************
//FORÇO USUARIO A ALTERAR SENHA
//***************************************************************************************************************************************************************
	function force_cad_senha(){

		//===========================================================
		//RECEBO OS DADOS
		$id = $this->funcoes->anti_injection($_GET['id']);

		//===========================================================
		//VERIFICO SE ID É VÁLIDO
		if(!is_numeric($id)){ // verifico se id é numérico
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem($this->core->get_msg_array('is_num',$id))->gravo_log(); // Gravo log
			exit;
		}

		//===========================================================
		//SETO OS DADOS
		$this->model->setId($id);
		$exec = $this->model->force_senha();

		//MENSAGEM
		if($exec == 2){
			$this->funcoes->set_array(null,'sucesso','Usuário forçado a alterar sua senha com sucesso!'); //Mensagem
			echo json_encode($this->funcoes->get_array()); //Mostro na tela
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem('Usuário forçado a alterar sua senha com sucesso! - '.$id.'  ')->gravo_log();
			exit();
		}else{
			$this->funcoes->set_array(null,'erro','Erro ao inserir parametro no banco de dados!'); //Mensagem
			echo json_encode($this->funcoes->get_array()); //Mostro na tela
			$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
			->setMensagem('Erro ao forçar usuário a alterar sua senha de acesso - '.$id.'  ')->gravo_log();
			exit();
		}
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

				//MONTO CAMPO MENSAGEM INFORMATIVA
			    $mont_html->set_array(null,'id','msg_info_usuario_cad');
			    $mont_html->set_array(null,'class','');
			    $mont_html->set_array(null,'tipo','info'); //tipos: danger, info, warning, success
			    $mont_html->set_array(null,'titulo','ATENÇÃO!');
			    $mont_html->set_array(null,'mensagem','Assim que finalizar o cadastro não se esqueça de definir as permissões que este usuário tem no sistema. <br /> Para definir as retrições clique em <strong>RESTRIÇÕES</strong> na listagem do usuários.');
			    $msg_informativa = $mont_html->monto_html_mensagem_informativa();

				//MONTO CAMPO INPUT TEXT NOME
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-4 col-md-5 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','* Nome');
			    $mont_html->set_array(null,'input_type','text'); //text, password
			    $mont_html->set_array(null,'input_name','nome');
			    $mont_html->set_array(null,'input_id','nome');
			    $mont_html->set_array(null,'input_class','count_input'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
			    $mont_html->set_array(null,'input_placeholder','');
			    $mont_html->set_array(null,'input_title','Digite o Nome');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_value','');
			    $mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-user'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
			    $nome = $mont_html->monto_html_input();

				//MONTO CAMPO SELECT SEXO
				$this->funcoes->set_array(0,'id','Masculino')->set_array(0,'value','Masculino');
				$this->funcoes->set_array(1,'id','Feminino')->set_array(1,'value','Feminino');
				$valores_select = $this->funcoes->get_array();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Sexo');
				$mont_html->set_array(null,'input_text_select','Selecione');
				$mont_html->set_array(null,'input_name','sexo');
				$mont_html->set_array(null,'input_id','sexo');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select2) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione o Sexo');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$sexo = $mont_html->monto_html_select();

				//MONTO CAMPO INPUT TEXT DATA DE NASCIMENTO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Data de Nascimento');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','data_nascimento');
				$mont_html->set_array(null,'input_id','data_nascimento');
				$mont_html->set_array(null,'input_class','input-mask date-picker'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite a Data de Nascimento');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros','data-mask="00/00/0000"'); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-calendar'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$data_nascimento = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT TELEFONE
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Telefone');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','telefone');
				$mont_html->set_array(null,'input_id','telefone');
				$mont_html->set_array(null,'input_class','input-mask telefone count_input'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Telefone');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-phone'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$telefone = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT TELEFONE
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Telefone 2');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','telefone2');
				$mont_html->set_array(null,'input_id','telefone2');
				$mont_html->set_array(null,'input_class','input-mask telefone'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Telefone');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-phone'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$telefone2 = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT EMAIL
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','E-mail');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','email');
				$mont_html->set_array(null,'input_id','email');
				$mont_html->set_array(null,'input_class','input_minusculo block_past'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o E-mail');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-envelope'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$email = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT EMAIL
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','E-mail 2');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','email2');
				$mont_html->set_array(null,'input_id','email2');
				$mont_html->set_array(null,'input_class','input_minusculo block_past'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o E-mail');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-envelope'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$email2 = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT USERNAME
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12 hidden_area tel_acesso'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Username');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','username');
				$mont_html->set_array(null,'input_id','username');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Nome de Usuário');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-user'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$username = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT USERTELEFONE
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12 hidden_area tel_acesso'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Telefone de acesso');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','usertelefone');
				$mont_html->set_array(null,'input_id','usertelefone');
				$mont_html->set_array(null,'input_class','numeros'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Telefone de Acesso. (Código de Área + Telefone)');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-phone'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$usertelefone = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT USEREMAIL
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* E-mail de acesso');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','useremail');
				$mont_html->set_array(null,'input_id','useremail');
				$mont_html->set_array(null,'input_class','input_minusculo block_past'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o E-mail de Acesso');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-envelope'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$useremail = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT SENHA
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12 hidden_area area_senha'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Nova Senha');
				$mont_html->set_array(null,'input_type','password'); //text, password
				$mont_html->set_array(null,'input_name','senha');
				$mont_html->set_array(null,'input_id','senha');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite a Senha');
				$mont_html->set_array(null,'title_help','Minimo de 8 caracteres, letras e números'); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-lock'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$senha = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT SENHA
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12 hidden_area area_senha'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Confirme a senha');
				$mont_html->set_array(null,'input_type','password'); //text, password
				$mont_html->set_array(null,'input_name','conf_senha');
				$mont_html->set_array(null,'input_id','conf_senha');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Confirme a senha');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-lock'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$conf_senha = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT LOGRADOURO
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-5 col-md-6 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','Logradouro');
			    $mont_html->set_array(null,'input_type','text'); //text, password
			    $mont_html->set_array(null,'input_name','logradouro');
			    $mont_html->set_array(null,'input_id','logradouro');
			    $mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
			    $mont_html->set_array(null,'input_placeholder','');
			    $mont_html->set_array(null,'input_title','Digite o Logradouro');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_value','');
			    $mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $logradouro = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT NÚMERO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-1 col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Número');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','numero');
				$mont_html->set_array(null,'input_id','numero');
				$mont_html->set_array(null,'input_class','numeros'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Número');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$numeros = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT COMPLEMENTO
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','Complemento');
			    $mont_html->set_array(null,'input_type','text'); //text, password
			    $mont_html->set_array(null,'input_name','complemento');
			    $mont_html->set_array(null,'input_id','complemento');
			    $mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
			    $mont_html->set_array(null,'input_placeholder','');
			    $mont_html->set_array(null,'input_title','Digite o Complemento');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_value','');
			    $mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $complemento = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT BAIRRO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Bairro');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','bairro');
				$mont_html->set_array(null,'input_id','bairro');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Bairro');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$bairro = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT CEP
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','CEP');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','CEP');
				$mont_html->set_array(null,'input_id','CEP');
				$mont_html->set_array(null,'input_class','input-mask'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o CEP');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros','data-mask="00000-000"'); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$cep = $mont_html->monto_html_input();

				//MONTO CAMPO SELECT ESTADO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Estado');
				$mont_html->set_array(null,'input_text_select','Selecione');
				$mont_html->set_array(null,'input_name','estado');
				$mont_html->set_array(null,'input_id','estado');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select2) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione o Estado');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select1); //Array com os valores do select
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$estado = $mont_html->monto_html_select();

				//MONTO CAMPO SELECT ESTADO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','* Cidade');
				$mont_html->set_array(null,'input_text_select','Selecione');
				$mont_html->set_array(null,'input_name','cidade');
				$mont_html->set_array(null,'input_id','cidade');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select2) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione a Cidade');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select1); //Array com os valores do select
				$mont_html->set_array(null,'input_value','');
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$cidade = $mont_html->monto_html_select();

				//MONTO CAMPO BOTÃO DE LINK
				$mont_html->set_array(null,'texto','Alterar a senha');
				$mont_html->set_array(null,'href',"javascript:void(0);"); // $this->core->get_config('dir_raiz_http').$this->dir_app."/
				$mont_html->set_array(null,'target',''); //target _blank
				$mont_html->set_array(null,'id','btn_alterar_senha');
				$mont_html->set_array(null,'class','btn btn-dois btn-default mostra_area hidden_area m-b-5');
				$mont_html->set_array(null,'icon_class',''); // Classe icone - Font Awesome Icons
				$mont_html->set_array(null,'outros','show_area=".area_senha" hide_area="#btn_alterar_senha" focus="senha" ');
				$link_btn_senha = $mont_html->monto_html_btn_externo();

				//TRATO RESTRIÇÃO - LISTAGEM
				$this->core->includeControllerView('core',$this->dir_app); //Incluo arquivo coreController.php
				$controller_geral_ = new coreController();
				(array) $restricoes = $controller_geral_->verifico_restricoes_sistema($this->adm_usuario_modulo_id);
				if(count($restricoes)>=1){
					for ($i=0; $i <count($restricoes) ; $i++) {
						if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 18 && $restricoes[$i]['opcoes'] == 1
						){
							//MONTO CAMPO BOTÃO DE LINK
							$mont_html->set_array(null,'texto','Restrições de acesso');
							$mont_html->set_array(null,'href',"javascript:void(0);"); // $this->core->get_config('dir_raiz_http').$this->dir_app."/
							$mont_html->set_array(null,'target',''); //target _blank
							$mont_html->set_array(null,'id','link_restricao');
							$mont_html->set_array(null,'class','btn btn-dois btn-default link_restricao m-b-5');
							$mont_html->set_array(null,'icon_class',''); // Classe icone - Font Awesome Icons
							$mont_html->set_array(null,'outros','link="'.$this->core->get_config('dir_raiz_http').$this->dir_app.'/adm_restricoes/busca/"');
							$link_btn = $mont_html->monto_html_btn_externo();
						}
					}
				}

				if($_SESSION['adm_id_cargo'] == 1 || $_SESSION['adm_id_cargo'] == 2 || $_SESSION['adm_id_cargo'] == 3) {

					//MONTO CAMPO BOTÃO DE LINK DE ATIVACAO
					$mont_html->set_array(null,'texto','Reenviar link de ativação');
					$mont_html->set_array(null,'href',"javascript:void(0);"); // $this->core->get_config('dir_raiz_http').$this->dir_app."/
					$mont_html->set_array(null,'target',''); //target _blank
					$mont_html->set_array(null,'id','link_ativacaoo');
					$mont_html->set_array(null,'class','btn btn-dois btn-default hidden_area link_restricao2 ajax_jquery m-b-5');
					$mont_html->set_array(null,'icon_class',''); // Classe icone - Font Awesome Icons
					$mont_html->set_array(null,'outros','link="'.$this->funcoes->monto_path_controller_comp($this->dir_app, 'usuarios',$this->config_apps->getCmds_controller('core',26)).'&t=2" link_id="true" ');
					$link_btn_ativacao = $mont_html->monto_html_btn_externo();

					//MONTO CAMPO BOTÃO DE LINK DE ATIVACAO
					$mont_html->set_array(null,'texto','Forçar alteração da senha');
					$mont_html->set_array(null,'href',"javascript:void(0);"); // $this->core->get_config('dir_raiz_http').$this->dir_app."/
					$mont_html->set_array(null,'target',''); //target _blank
					$mont_html->set_array(null,'id','force_senhaa');
					$mont_html->set_array(null,'class','btn btn-dois btn-default mostra_area hidden_area link_restricao2 ajax_jquery m-b-5');
					$mont_html->set_array(null,'icon_class',''); // Classe icone - Font Awesome Icons
					$mont_html->set_array(null,'outros','link="'.$this->funcoes->monto_path_controller_comp($this->dir_app, 'usuarios',$this->config_apps->getCmds_controller('core',27)).'&t=2" link_id="true" ');
					$link_btn_force_senha = $mont_html->monto_html_btn_externo();

				}

				if($_SESSION['adm_id_cargo'] == 1 || $_SESSION['adm_id_cargo'] == 2 || $_SESSION['adm_id_cargo'] == 3) {

					//MONTO CAMPO SELECT CARGOS
					$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
					$mont_html->set_array(null,'label_for_texto','* Cargo');
					$mont_html->set_array(null,'input_text_select','Selecione ');
					$mont_html->set_array(null,'input_name','cargos');
					$mont_html->set_array(null,'input_id','cargos');
					$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
					$mont_html->set_array(null,'input_title','Selecione o Cargo');
					$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
					$mont_html->set_array(null,'input_options',$valores_select2); //Array com os valores do select
					$mont_html->set_array(null,'input_value','');
					$mont_html->set_array(null,'input_outros','');
					$mont_html->set_array(null,'input_disabled',''); //disabled
					$mont_html->set_array(null,'input_multiple',''); //SELECT MULTIPLO - multiple ou ""
					$mont_html->set_array(null,'input_pagina_title','Ir para a listagem de Cargos'); //TITLE DO BTN DE LISTAGEM
					$mont_html->set_array(null,'input_pagina_url',$this->core->get_config('dir_raiz_http').$this->dir_app.'/adm_usuario_nivel'); //URL BTN DE LISTAGEM
					$mont_html->set_array(null,'input_pagina_add_title','Adicionar Cargo'); //TITLE DO BTN DE ADICIONAR
					$mont_html->set_array(null,'input_pagina_add_id','modal_cargo'); //ID QUE CHAMA O MODAL DE ADICIONAR
					$cargos = $mont_html->monto_html_select();
				}

				//MONTO CAMPO MODAL ADICIONAR
				$mont_html->set_array(null,'input_id_area','modal_cargo'); //Coloque o mesmo valor que foi inserido no select em input_pagina_add_id
				$mont_html->set_array(null,'url_pagina',$this->core->get_config('dir_raiz_http').$this->dir_app.'/adm_usuario_nivel'); //PARAMETRO TIPO
				$modal = $mont_html->monto_html_modal_add();

				//MONTO CAMPO TEXTAREA
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-12 col-md-12 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','Assinatura de E-mail');
			    $mont_html->set_array(null,'input_name','assinatura_email');
			    $mont_html->set_array(null,'input_id','assinatura_email');
			    $mont_html->set_array(null,'input_class','editor_html_basico');
			    $mont_html->set_array(null,'input_placeholder','');
			    $mont_html->set_array(null,'input_title','');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_value','');
			    $mont_html->set_array(null,'input_outros','');
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $mont_html->set_array(null,'input_rows',''); //número de linhas
			    $assinatura_email = $mont_html->monto_html_textarea();

				//MONTO CAMPO INPUT FILE
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'input_multiple',false); //true ou false
				$mont_html->set_array(null,'label_for_texto','Imagem de Perfil');
				$mont_html->set_array(null,'input_name','img_perfil');
				$mont_html->set_array(null,'input_id','img_perfil');
				$mont_html->set_array(null,'input_class','');
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Selecione a imagem de perfil');
				$mont_html->set_array(null,'title_help','FORMATOS: <b>(jpg)</b> <br /> TAMANHO: <b>Máx. 2 MB p/ arquivo</b> ');
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
				$mont_html->set_array(null,'titulo_listagem_arquivos','Imagem de Perfil'); //TITULO DA LISTAGEM - imagem ou arquivo
				$mont_html->set_array(null,'icone_doc','fa fa-file-text'); //ICONE DA LISTAGEM DOCUMENTOS - Font Awesome Icons
				$mont_html->set_array(null,'icone_img','fa fa-picture-o'); //TITULO DA LISTAGEM IMAGENS - Font Awesome Icons
				$mont_html->set_array(null,'campo_input_file','img_perfil'); //ID DO CAMPO INPUT FILE
				$mont_html->set_array(null,'url_listagem_arquivos','img_perfil'); // PARAMETRO TIPO - LISTAGEM DE ARQUIVOS
				$listagem_arquivos = $mont_html->monto_html_list_arquivos();

				//===========================================================
				//MONTO CAMPOS E ÁREAS
				$mont_html->set_array(0,'titulo','Opções Avançadas do Usuário');
				$mont_html->set_array(0,'icone_titulo','fa fa-bars'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(0,'class_area','restricoes_acess hidden_area'); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(0,'conteudo'," <div class='col-md-12'> {$link_btn} {$link_btn_senha} {$link_btn_ativacao} {$link_btn_force_senha} <span class='ajax_jquery_load pull-right'></span> </div> "); //conteudo ***
				$mont_html->set_array(1,'titulo','Imagem de Perfil');
				$mont_html->set_array(1,'icone_titulo','fa fa-camera'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(1,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(1,'conteudo'," {$input_file} {$listagem_arquivos}"); //conteudo ***
				$mont_html->set_array(2,'titulo','Dados Pessoais');
				$mont_html->set_array(2,'icone_titulo','fa fa-list'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(2,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(2,'conteudo'," {$modal} {$nome} {$sexo} {$data_nascimento} {$telefone} {$telefone2} {$email} {$email2} {$cargos} "); //conteudo ***
				$mont_html->set_array(3,'titulo','Dados de Acesso');
				$mont_html->set_array(3,'icone_titulo','fa fa-lock'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(3,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(3,'conteudo'," {$username} {$useremail} {$usertelefone} {$senha} {$conf_senha} "); //conteudo ***
				$mont_html->set_array(4,'titulo','Endereço');
				$mont_html->set_array(4,'icone_titulo','fa fa-map'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(4,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(4,'conteudo'," {$logradouro} {$numeros} {$complemento} {$bairro} {$cep} {$estado} {$cidade} "); //conteudo ***
				$mont_html->set_array(5,'titulo','Informações Gerais');
				$mont_html->set_array(5,'icone_titulo','fa fa-list'); // Classe icone - Font Awesome Icons
				$mont_html->set_array(5,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
				$mont_html->set_array(5,'conteudo'," {$assinatura_email}  "); //conteudo ***
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
				$this->funcoes->set_array(0,'input_id','exp_pdf_status');
				$this->funcoes->set_array(0,'input_name','campos_pdf[]');
				$this->funcoes->set_array(0,'input_class','');
				$this->funcoes->set_array(0,'input_title','');
				$this->funcoes->set_array(0,'input_value','status'); //value do campo
				$this->funcoes->set_array(0,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(0,'input_texto_checkbox','Status'); //Texto
				$this->funcoes->set_array(1,'input_id','exp_pdf_nome');
				$this->funcoes->set_array(1,'input_name','campos_pdf[]');
				$this->funcoes->set_array(1,'input_class','');
				$this->funcoes->set_array(1,'input_title','');
				$this->funcoes->set_array(1,'input_value','nome'); //value do campo
				$this->funcoes->set_array(1,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(1,'input_texto_checkbox','Usuario'); //Texto
				$this->funcoes->set_array(2,'input_id','exp_pdf_sexo');
				$this->funcoes->set_array(2,'input_name','campos_pdf[]');
				$this->funcoes->set_array(2,'input_class','');
				$this->funcoes->set_array(2,'input_title','');
				$this->funcoes->set_array(2,'input_value','sexo'); //value do campo
				$this->funcoes->set_array(2,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(2,'input_texto_checkbox','Sexo'); //Texto
				$this->funcoes->set_array(3,'input_id','exp_pdf_data_nascimento');
				$this->funcoes->set_array(3,'input_name','campos_pdf[]');
				$this->funcoes->set_array(3,'input_class','');
				$this->funcoes->set_array(3,'input_title','');
				$this->funcoes->set_array(3,'input_value','data_nascimento'); //value do campo
				$this->funcoes->set_array(3,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(3,'input_texto_checkbox','Data de nascimento'); //Texto
				$this->funcoes->set_array(4,'input_id','exp_pdf_nivel');
				$this->funcoes->set_array(4,'input_name','campos_pdf[]');
				$this->funcoes->set_array(4,'input_class','');
				$this->funcoes->set_array(4,'input_title','');
				$this->funcoes->set_array(4,'input_value','nivel'); //value do campo
				$this->funcoes->set_array(4,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(4,'input_texto_checkbox','Cargo'); //Texto
				$this->funcoes->set_array(5,'input_id','exp_pdf_telefone');
				$this->funcoes->set_array(5,'input_name','campos_pdf[]');
				$this->funcoes->set_array(5,'input_class','');
				$this->funcoes->set_array(5,'input_title','');
				$this->funcoes->set_array(5,'input_value','telefone'); //value do campo
				$this->funcoes->set_array(5,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(5,'input_texto_checkbox','Telefone'); //Texto
				$this->funcoes->set_array(6,'input_id','exp_pdf_telefone2');
				$this->funcoes->set_array(6,'input_name','campos_pdf[]');
				$this->funcoes->set_array(6,'input_class','');
				$this->funcoes->set_array(6,'input_title','');
				$this->funcoes->set_array(6,'input_value','telefone2'); //value do campo
				$this->funcoes->set_array(6,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(6,'input_texto_checkbox','Telefone 2'); //Texto
				$this->funcoes->set_array(7,'input_id','exp_pdf_email');
				$this->funcoes->set_array(7,'input_name','campos_pdf[]');
				$this->funcoes->set_array(7,'input_class','');
				$this->funcoes->set_array(7,'input_title','');
				$this->funcoes->set_array(7,'input_value','email'); //value do campo
				$this->funcoes->set_array(7,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(7,'input_texto_checkbox','E-mail'); //Texto
				$this->funcoes->set_array(8,'input_id','exp_pdf_email2');
				$this->funcoes->set_array(8,'input_name','campos_pdf[]');
				$this->funcoes->set_array(8,'input_class','');
				$this->funcoes->set_array(8,'input_title','');
				$this->funcoes->set_array(8,'input_value','email2'); //value do campo
				$this->funcoes->set_array(8,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(8,'input_texto_checkbox','E-mail 2'); //Texto
				$this->funcoes->set_array(9,'input_id','exp_pdf_username');
				$this->funcoes->set_array(9,'input_name','campos_pdf[]');
				$this->funcoes->set_array(9,'input_class','');
				$this->funcoes->set_array(9,'input_title','');
				$this->funcoes->set_array(9,'input_value','username'); //value do campo
				$this->funcoes->set_array(9,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(9,'input_texto_checkbox','Username'); //Texto
				$this->funcoes->set_array(10,'input_id','exp_pdf_useremail');
				$this->funcoes->set_array(10,'input_name','campos_pdf[]');
				$this->funcoes->set_array(10,'input_class','');
				$this->funcoes->set_array(10,'input_title','');
				$this->funcoes->set_array(10,'input_value','useremail'); //value do campo
				$this->funcoes->set_array(10,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(10,'input_texto_checkbox','Email de acesso'); //Texto
				$this->funcoes->set_array(11,'input_id','exp_pdf_usertelefone');
				$this->funcoes->set_array(11,'input_name','campos_pdf[]');
				$this->funcoes->set_array(11,'input_class','');
				$this->funcoes->set_array(11,'input_title','');
				$this->funcoes->set_array(11,'input_value','usertelefone'); //value do campo
				$this->funcoes->set_array(11,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(11,'input_texto_checkbox','Telefone de acesso'); //Texto
				$this->funcoes->set_array(12,'input_id','exp_pdf_logradouro');
				$this->funcoes->set_array(12,'input_name','campos_pdf[]');
				$this->funcoes->set_array(12,'input_class','');
				$this->funcoes->set_array(12,'input_title','');
				$this->funcoes->set_array(12,'input_value','logradouro'); //value do campo
				$this->funcoes->set_array(12,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(12,'input_texto_checkbox','Rua'); //Texto
				$this->funcoes->set_array(13,'input_id','exp_pdf_numero');
				$this->funcoes->set_array(13,'input_name','campos_pdf[]');
				$this->funcoes->set_array(13,'input_class','');
				$this->funcoes->set_array(13,'input_title','');
				$this->funcoes->set_array(13,'input_value','numero'); //value do campo
				$this->funcoes->set_array(13,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(13,'input_texto_checkbox','Numero'); //Texto
				$this->funcoes->set_array(14,'input_id','exp_pdf_complemento');
				$this->funcoes->set_array(14,'input_name','campos_pdf[]');
				$this->funcoes->set_array(14,'input_class','');
				$this->funcoes->set_array(14,'input_title','');
				$this->funcoes->set_array(14,'input_value','complemento'); //value do campo
				$this->funcoes->set_array(14,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(14,'input_texto_checkbox','Complemento'); //Texto
				$this->funcoes->set_array(15,'input_id','exp_pdf_bairro');
				$this->funcoes->set_array(15,'input_name','campos_pdf[]');
				$this->funcoes->set_array(15,'input_class','');
				$this->funcoes->set_array(15,'input_title','');
				$this->funcoes->set_array(15,'input_value','bairro'); //value do campo
				$this->funcoes->set_array(15,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(15,'input_texto_checkbox','Bairro'); //Texto
				$this->funcoes->set_array(16,'input_id','exp_pdf_CEP');
				$this->funcoes->set_array(16,'input_name','campos_pdf[]');
				$this->funcoes->set_array(16,'input_class','');
				$this->funcoes->set_array(16,'input_title','');
				$this->funcoes->set_array(16,'input_value','CEP'); //value do campo
				$this->funcoes->set_array(16,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(16,'input_texto_checkbox','CEP'); //Texto
				$this->funcoes->set_array(17,'input_id','exp_pdf_estado');
				$this->funcoes->set_array(17,'input_name','campos_pdf[]');
				$this->funcoes->set_array(17,'input_class','');
				$this->funcoes->set_array(17,'input_title','');
				$this->funcoes->set_array(17,'input_value','estado'); //value do campo
				$this->funcoes->set_array(17,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(17,'input_texto_checkbox','Estado'); //Texto
				$this->funcoes->set_array(18,'input_id','exp_pdf_cidade');
				$this->funcoes->set_array(18,'input_name','campos_pdf[]');
				$this->funcoes->set_array(18,'input_class','');
				$this->funcoes->set_array(18,'input_title','');
				$this->funcoes->set_array(18,'input_value','cidade'); //value do campo
				$this->funcoes->set_array(18,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(18,'input_texto_checkbox','Cidade'); //Texto
				$this->funcoes->set_array(19,'input_id','exp_pdf_ultimo_acesso');
				$this->funcoes->set_array(19,'input_name','campos_pdf[]');
				$this->funcoes->set_array(19,'input_class','');
				$this->funcoes->set_array(19,'input_title','');
				$this->funcoes->set_array(19,'input_value','ultimo_acesso'); //value do campo
				$this->funcoes->set_array(19,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(19,'input_texto_checkbox','Ultimo acesso'); //Texto
				$this->funcoes->set_array(20,'input_id','exp_pdf_ultima_mod_senha');
				$this->funcoes->set_array(20,'input_name','campos_pdf[]');
				$this->funcoes->set_array(20,'input_class','');
				$this->funcoes->set_array(20,'input_title','');
				$this->funcoes->set_array(20,'input_value','ultima_mod_senha'); //value do campo
				$this->funcoes->set_array(20,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(20,'input_texto_checkbox','Ultima mudança senha'); //Texto
				$this->funcoes->set_array(21,'input_id','exp_pdf_ultima_mod_username');
				$this->funcoes->set_array(21,'input_name','campos_pdf[]');
				$this->funcoes->set_array(21,'input_class','');
				$this->funcoes->set_array(21,'input_title','');
				$this->funcoes->set_array(21,'input_value','ultima_mod_username'); //value do campo
				$this->funcoes->set_array(21,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(21,'input_texto_checkbox','Ultima mudança username'); //Texto
				$this->funcoes->set_array(22,'input_id','exp_pdf_assinatura_email');
				$this->funcoes->set_array(22,'input_name','campos_pdf[]');
				$this->funcoes->set_array(22,'input_class','');
				$this->funcoes->set_array(22,'input_title','');
				$this->funcoes->set_array(22,'input_value','assinatura_email'); //value do campo
				$this->funcoes->set_array(22,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(22,'input_texto_checkbox','Assinatura de e-mail'); //Texto
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
				$this->funcoes->set_array(0,'input_id','exp_imprimir_status');
				$this->funcoes->set_array(0,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(0,'input_class','');
				$this->funcoes->set_array(0,'input_title','');
				$this->funcoes->set_array(0,'input_value','status'); //value do campo
				$this->funcoes->set_array(0,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(0,'input_texto_checkbox','Status'); //Texto
				$this->funcoes->set_array(1,'input_id','exp_imprimir_nome');
				$this->funcoes->set_array(1,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(1,'input_class','');
				$this->funcoes->set_array(1,'input_title','');
				$this->funcoes->set_array(1,'input_value','nome'); //value do campo
				$this->funcoes->set_array(1,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(1,'input_texto_checkbox','Usuario'); //Texto
				$this->funcoes->set_array(2,'input_id','exp_imprimir_sexo');
				$this->funcoes->set_array(2,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(2,'input_class','');
				$this->funcoes->set_array(2,'input_title','');
				$this->funcoes->set_array(2,'input_value','sexo'); //value do campo
				$this->funcoes->set_array(2,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(2,'input_texto_checkbox','Sexo'); //Texto
				$this->funcoes->set_array(3,'input_id','exp_imprimir_data_nascimento');
				$this->funcoes->set_array(3,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(3,'input_class','');
				$this->funcoes->set_array(3,'input_title','');
				$this->funcoes->set_array(3,'input_value','data_nascimento'); //value do campo
				$this->funcoes->set_array(3,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(3,'input_texto_checkbox','Data de nascimento'); //Texto
				$this->funcoes->set_array(4,'input_id','exp_imprimir_nivel');
				$this->funcoes->set_array(4,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(4,'input_class','');
				$this->funcoes->set_array(4,'input_title','');
				$this->funcoes->set_array(4,'input_value','nivel'); //value do campo
				$this->funcoes->set_array(4,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(4,'input_texto_checkbox','Cargo'); //Texto
				$this->funcoes->set_array(5,'input_id','exp_imprimir_telefone');
				$this->funcoes->set_array(5,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(5,'input_class','');
				$this->funcoes->set_array(5,'input_title','');
				$this->funcoes->set_array(5,'input_value','telefone'); //value do campo
				$this->funcoes->set_array(5,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(5,'input_texto_checkbox','Telefone'); //Texto
				$this->funcoes->set_array(6,'input_id','exp_imprimir_telefone2');
				$this->funcoes->set_array(6,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(6,'input_class','');
				$this->funcoes->set_array(6,'input_title','');
				$this->funcoes->set_array(6,'input_value','telefone2'); //value do campo
				$this->funcoes->set_array(6,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(6,'input_texto_checkbox','Telefone 2'); //Texto
				$this->funcoes->set_array(7,'input_id','exp_imprimir_email');
				$this->funcoes->set_array(7,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(7,'input_class','');
				$this->funcoes->set_array(7,'input_title','');
				$this->funcoes->set_array(7,'input_value','email'); //value do campo
				$this->funcoes->set_array(7,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(7,'input_texto_checkbox','E-mail'); //Texto
				$this->funcoes->set_array(8,'input_id','exp_imprimir_email2');
				$this->funcoes->set_array(8,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(8,'input_class','');
				$this->funcoes->set_array(8,'input_title','');
				$this->funcoes->set_array(8,'input_value','email2'); //value do campo
				$this->funcoes->set_array(8,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(8,'input_texto_checkbox','E-mail 2'); //Texto
				$this->funcoes->set_array(9,'input_id','exp_imprimir_username');
				$this->funcoes->set_array(9,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(9,'input_class','');
				$this->funcoes->set_array(9,'input_title','');
				$this->funcoes->set_array(9,'input_value','username'); //value do campo
				$this->funcoes->set_array(9,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(9,'input_texto_checkbox','Username'); //Texto
				$this->funcoes->set_array(10,'input_id','exp_imprimir_useremail');
				$this->funcoes->set_array(10,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(10,'input_class','');
				$this->funcoes->set_array(10,'input_title','');
				$this->funcoes->set_array(10,'input_value','useremail'); //value do campo
				$this->funcoes->set_array(10,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(10,'input_texto_checkbox','Email de acesso'); //Texto
				$this->funcoes->set_array(11,'input_id','exp_imprimir_usertelefone');
				$this->funcoes->set_array(11,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(11,'input_class','');
				$this->funcoes->set_array(11,'input_title','');
				$this->funcoes->set_array(11,'input_value','usertelefone'); //value do campo
				$this->funcoes->set_array(11,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(11,'input_texto_checkbox','Telefone de acesso'); //Texto
				$this->funcoes->set_array(12,'input_id','exp_imprimir_logradouro');
				$this->funcoes->set_array(12,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(12,'input_class','');
				$this->funcoes->set_array(12,'input_title','');
				$this->funcoes->set_array(12,'input_value','logradouro'); //value do campo
				$this->funcoes->set_array(12,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(12,'input_texto_checkbox','Rua'); //Texto
				$this->funcoes->set_array(13,'input_id','exp_imprimir_numero');
				$this->funcoes->set_array(13,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(13,'input_class','');
				$this->funcoes->set_array(13,'input_title','');
				$this->funcoes->set_array(13,'input_value','numero'); //value do campo
				$this->funcoes->set_array(13,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(13,'input_texto_checkbox','Numero'); //Texto
				$this->funcoes->set_array(14,'input_id','exp_imprimir_complemento');
				$this->funcoes->set_array(14,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(14,'input_class','');
				$this->funcoes->set_array(14,'input_title','');
				$this->funcoes->set_array(14,'input_value','complemento'); //value do campo
				$this->funcoes->set_array(14,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(14,'input_texto_checkbox','Complemento'); //Texto
				$this->funcoes->set_array(15,'input_id','exp_imprimir_bairro');
				$this->funcoes->set_array(15,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(15,'input_class','');
				$this->funcoes->set_array(15,'input_title','');
				$this->funcoes->set_array(15,'input_value','bairro'); //value do campo
				$this->funcoes->set_array(15,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(15,'input_texto_checkbox','Bairro'); //Texto
				$this->funcoes->set_array(16,'input_id','exp_imprimir_CEP');
				$this->funcoes->set_array(16,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(16,'input_class','');
				$this->funcoes->set_array(16,'input_title','');
				$this->funcoes->set_array(16,'input_value','CEP'); //value do campo
				$this->funcoes->set_array(16,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(16,'input_texto_checkbox','CEP'); //Texto
				$this->funcoes->set_array(17,'input_id','exp_imprimir_estado');
				$this->funcoes->set_array(17,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(17,'input_class','');
				$this->funcoes->set_array(17,'input_title','');
				$this->funcoes->set_array(17,'input_value','estado'); //value do campo
				$this->funcoes->set_array(17,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(17,'input_texto_checkbox','Estado'); //Texto
				$this->funcoes->set_array(18,'input_id','exp_imprimir_cidade');
				$this->funcoes->set_array(18,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(18,'input_class','');
				$this->funcoes->set_array(18,'input_title','');
				$this->funcoes->set_array(18,'input_value','cidade'); //value do campo
				$this->funcoes->set_array(18,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(18,'input_texto_checkbox','Cidade'); //Texto
				$this->funcoes->set_array(19,'input_id','exp_imprimir_ultimo_acesso');
				$this->funcoes->set_array(19,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(19,'input_class','');
				$this->funcoes->set_array(19,'input_title','');
				$this->funcoes->set_array(19,'input_value','ultimo_acesso'); //value do campo
				$this->funcoes->set_array(19,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(19,'input_texto_checkbox','Ultimo acesso'); //Texto
				$this->funcoes->set_array(20,'input_id','exp_imprimir_ultima_mod_senha');
				$this->funcoes->set_array(20,'input_name','campos_pdf[]');
				$this->funcoes->set_array(20,'input_class','');
				$this->funcoes->set_array(20,'input_title','');
				$this->funcoes->set_array(20,'input_value','ultima_mod_senha'); //value do campo
				$this->funcoes->set_array(20,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(20,'input_texto_checkbox','Ultima mudança senha'); //Texto
				$this->funcoes->set_array(21,'input_id','exp_imprimir_ultima_mod_username');
				$this->funcoes->set_array(21,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(21,'input_class','');
				$this->funcoes->set_array(21,'input_title','');
				$this->funcoes->set_array(21,'input_value','ultima_mod_username'); //value do campo
				$this->funcoes->set_array(21,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(21,'input_texto_checkbox','Ultima mudança username'); //Texto
				$this->funcoes->set_array(22,'input_id','exp_imprimir_assinatura_email');
				$this->funcoes->set_array(22,'input_name','campos_imprimir[]');
				$this->funcoes->set_array(22,'input_class','');
				$this->funcoes->set_array(22,'input_title','');
				$this->funcoes->set_array(22,'input_value','assinatura_email'); //value do campo
				$this->funcoes->set_array(22,'input_checked','checked'); //checked ou ""
				$this->funcoes->set_array(22,'input_texto_checkbox','Assinatura de e-mail'); //Texto
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
				$mont_html->set_array(null,'area_descricao','Períodos e Status')->set_array(null,'area_descricao_icon_class','fa fa-calendar');
				$area_descricao = $mont_html->monto_html_area_descricao();

				//CAMPO TIPO DE DATA
				$this->funcoes->set_array(0,'id','adm_usuario_auth.ultimo_acesso')->set_array(0,'value','Último Acesso');
				$this->funcoes->set_array(1,'id','adm_usuario_auth.ultima_mod_senha')->set_array(1,'value','Última Modificação da Senha');
				$this->funcoes->set_array(2,'id','adm_usuario.data_nascimento')->set_array(2,'value','Data de Nascimento');
				$this->funcoes->set_array(3,'id','adm_usuario.criado')->set_array(3,'value','Data de Cadastro');
				$this->funcoes->set_array(4,'id','adm_usuario.modificado')->set_array(4,'value','Data de Edição');
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


				//MONTO CAMPO INPUT TEXT NOME
				$mont_html->set_array(null,'input_class_tamanho','col-lg-4 col-md-5 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Nome');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_nome');
				$mont_html->set_array(null,'input_id','pesq_avanc_nome');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Nome');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_nome']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-user'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$nome = $mont_html->monto_html_input();

				//MONTO CAMPO SELECT SEXO
				$this->funcoes->set_array(0,'id','Masculino')->set_array(0,'value','Masculino');
				$this->funcoes->set_array(1,'id','Feminino')->set_array(1,'value','Feminino');
				$valores_select = $this->funcoes->get_array();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Sexo');
				$mont_html->set_array(null,'input_text_select','Selecione');
				$mont_html->set_array(null,'input_name','pesq_avanc_sexo');
				$mont_html->set_array(null,'input_id','pesq_avanc_sexo');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select2) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione o Sexo');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_sexo']);
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$sexo = $mont_html->monto_html_select();

				//MONTO CAMPO INPUT TEXT DATA DE NASCIMENTO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Data de Nascimento');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_data_nascimento');
				$mont_html->set_array(null,'input_id','pesq_avanc_data_nascimento');
				$mont_html->set_array(null,'input_class','input-mask date-picker'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite a Data de Nascimento');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_data_nascimento']);
				$mont_html->set_array(null,'input_outros','data-mask="00/00/0000"'); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-calendar'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$data_nascimento = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT TELEFONE
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Telefone');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_telefone');
				$mont_html->set_array(null,'input_id','pesq_avanc_telefone');
				$mont_html->set_array(null,'input_class','input-mask telefone'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Telefone');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_telefone']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-phone'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$telefone = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT TELEFONE
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Telefone 2');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_telefone2');
				$mont_html->set_array(null,'input_id','pesq_avanc_telefone2');
				$mont_html->set_array(null,'input_class','input-mask telefone'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Telefone');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_telefone2']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-phone'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$telefone2 = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT EMAIL
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','E-mail');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_email');
				$mont_html->set_array(null,'input_id','pesq_avanc_email');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o E-mail');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_email']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-envelope'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$email = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT EMAIL
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','E-mail 2');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_email2');
				$mont_html->set_array(null,'input_id','pesq_avanc_email2');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o E-mail');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_email2']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-envelope'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$email2 = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT USERNAME
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Username');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_username');
				$mont_html->set_array(null,'input_id','pesq_avanc_username');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Nome de Usuário');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_username']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-user'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$username = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT USERTELEFONE
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12 hidden_area tel_acesso'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Telefone de acesso');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_usertelefone');
				$mont_html->set_array(null,'input_id','pesq_avanc_usertelefone');
				$mont_html->set_array(null,'input_class','numeros'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Telefone de Acesso. (Código de Área + Telefone)');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_usertelefone']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-phone'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$usertelefone = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT USEREMAIL
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','E-mail de acesso');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_useremail');
				$mont_html->set_array(null,'input_id','pesq_avanc_useremail');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o E-mail de Acesso');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_useremail']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'icon_in_input','glyphicon glyphicon-envelope'); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
				$useremail = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT LOGRADOURO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-5 col-md-6 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Logradouro');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_logradouro');
				$mont_html->set_array(null,'input_id','pesq_avanc_logradouro');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Logradouro');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_logradouro']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$logradouro = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT NÚMERO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-1 col-md-2 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Número');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_numero');
				$mont_html->set_array(null,'input_id','pesq_avanc_numero');
				$mont_html->set_array(null,'input_class','numeros'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Número');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_numero']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$numeros = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT COMPLEMENTO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Complemento');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_complemento');
				$mont_html->set_array(null,'input_id','pesq_avanc_complemento');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Complemento');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_complemento']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$complemento = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT BAIRRO
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Bairro');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_bairro');
				$mont_html->set_array(null,'input_id','pesq_avanc_bairro');
				$mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o Bairro');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_bairro']);
				$mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$bairro = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT CEP
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','CEP');
				$mont_html->set_array(null,'input_type','text'); //text, password
				$mont_html->set_array(null,'input_name','pesq_avanc_CEP');
				$mont_html->set_array(null,'input_id','pesq_avanc_CEP');
				$mont_html->set_array(null,'input_class','input-mask'); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
				$mont_html->set_array(null,'input_placeholder','');
				$mont_html->set_array(null,'input_title','Digite o CEP');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_CEP']);
				$mont_html->set_array(null,'input_outros','data-mask="00000-000"'); // [MASCARA: (data-mask="000.000.000-00")]
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$cep = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','Estado');
			    $mont_html->set_array(null,'input_type','text'); //text, password
			    $mont_html->set_array(null,'input_name','pesq_avanc_estado');
			    $mont_html->set_array(null,'input_id','pesq_avanc_estado');
			    $mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
			    $mont_html->set_array(null,'input_placeholder','');
			    $mont_html->set_array(null,'input_title','Digite o Estado');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_estado']);
			    $mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $estado = $mont_html->monto_html_input();

				//MONTO CAMPO INPUT TEXT
			    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
			    $mont_html->set_array(null,'label_for_texto','Cidade');
			    $mont_html->set_array(null,'input_type','text'); //text, password
			    $mont_html->set_array(null,'input_name','pesq_avanc_cidade');
			    $mont_html->set_array(null,'input_id','pesq_avanc_cidade');
			    $mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
			    $mont_html->set_array(null,'input_placeholder','');
			    $mont_html->set_array(null,'input_title','Digite a Cidade');
			    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
			    $mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_cidade']);
			    $mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
			    $mont_html->set_array(null,'input_disabled',''); //disabled
			    $cidade = $mont_html->monto_html_input();

				//MONTO CAMPO SELECT CARGOS
				$this->model->setCampos('campo_tabela',"adm_usuario_nivel");
				$this->model->setCampos('campo_coluna',"id");
				$this->model->setCampos('campo_coluna2',"nivel");
				$this->model->setCampos('campo_where',"");
				$this->model->setCampos('campo_orderby',"nivel ASC");
				$valor = $this->model->select_simples_retorna_array_mont_vcol();
				for ($i=0; $i < count($valor) ; $i++) {
					$this->funcoes->set_array($i,'id',$valor[$i]['id'])->set_array($i,'value',$this->funcoes->conv_string($valor[$i]['nivel'],2));
				}
				$valores_select2 = $this->funcoes->get_array();
				$this->model->getLimpoCampos();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto','Cargo');
				$mont_html->set_array(null,'input_text_select','Selecione ');
				$mont_html->set_array(null,'input_name','pesq_avanc_cargos');
				$mont_html->set_array(null,'input_id','pesq_avanc_cargos');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione o Cargo');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select2); //Array com os valores do select
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_cargos']);
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$mont_html->set_array(null,'input_multiple',''); //SELECT MULTIPLO - multiple ou ""
				$cargos = $mont_html->monto_html_select();

				//MONTO CAMPO SELECT STATUS
				$this->model->setCampos('campo_tabela',"config_status");
				$this->model->setCampos('campo_coluna',"id");
				$this->model->setCampos('campo_coluna2',"status");
				$this->model->setCampos('campo_where',"");
				$this->model->setCampos('campo_orderby',"status ASC");
				$valor = $this->model->select_simples_retorna_array_mont_vcol();
				for ($i=0; $i < count($valor) ; $i++) {
					$this->funcoes->set_array($i,'id',$valor[$i]['id'])->set_array($i,'value',$this->funcoes->conv_string($valor[$i]['status'],2));
				}
				$valores_select = $this->funcoes->get_array();
				$this->model->getLimpoCampos();
				$mont_html->set_array(null,'input_class_tamanho','col-lg-2 col-md-3 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
				$mont_html->set_array(null,'label_for_texto',' Status');
				$mont_html->set_array(null,'input_text_select','Selecione');
				$mont_html->set_array(null,'input_name','pesq_avanc_status');
				$mont_html->set_array(null,'input_id','pesq_avanc_status');
				$mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select2) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
				$mont_html->set_array(null,'input_title','Selecione o Status');
				$mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
				$mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
				$mont_html->set_array(null,'input_value',$valores_pesq_avancada['pesq_avanc_status']);
				$mont_html->set_array(null,'input_outros','');
				$mont_html->set_array(null,'input_disabled',''); //disabled
				$status = $mont_html->monto_html_select();

				//MONTO CAMPO ÁREA DESCRIÇÃO
				$mont_html->set_array(null,'area_descricao','Dados Pessoais')->set_array(null,'area_descricao_icon_class','fa fa-list')->set_array(null,'class','subtitulo-margin_top'); // Classes úteis = (subtitulo-margin_top) -Classe icone - Font Awesome Icons
				$area_descricao2 = $mont_html->monto_html_area_descricao();

				//MONTO CAMPO ÁREA DESCRIÇÃO
				$mont_html->set_array(null,'area_descricao','Dados de Acesso')->set_array(null,'area_descricao_icon_class','fa fa-lock')->set_array(null,'class','subtitulo-margin_top'); // Classes úteis = (subtitulo-margin_top) -Classe icone - Font Awesome Icons
				$area_descricao3 = $mont_html->monto_html_area_descricao();

				//MONTO CAMPO ÁREA DESCRIÇÃO
				$mont_html->set_array(null,'area_descricao','Endereço')->set_array(null,'area_descricao_icon_class','fa fa-map')->set_array(null,'class','subtitulo-margin_top'); // Classes úteis = (subtitulo-margin_top) -Classe icone - Font Awesome Icons
				$area_descricao4 = $mont_html->monto_html_area_descricao();

				//===========================================================
				//MONTO CAMPOS
				$mont_html->set_array(0,'conteudo'," {$area_descricao} {$input_text} {$input_text2} {$select}  ");
				$mont_html->set_array(1,'conteudo'," {$status} {$area_descricao2} {$nome} {$sexo} {$data_nascimento} {$telefone} {$telefone2} {$email} {$email2} {$cargos} ");
				$mont_html->set_array(2,'conteudo'," {$area_descricao3} {$username} {$useremail} {$usertelefone} ");
				$mont_html->set_array(3,'conteudo'," {$area_descricao4} {$logradouro} {$numeros} {$complemento} {$bairro} {$cep} {$estado} {$cidade} ");
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
				(string) $pesq_avanc4  = $this->funcoes->anti_injection($_POST['pesq_avanc_nome']); // Recebo dados
				(string) $pesq_avanc5  = $this->funcoes->anti_injection($_POST['pesq_avanc_sexo']); // Recebo dados
				(string) $pesq_avanc6  = $this->funcoes->anti_injection($_POST['pesq_avanc_data_nascimento']); // Recebo dados
				(string) $pesq_avanc7  = $this->funcoes->anti_injection($_POST['pesq_avanc_telefone']); // Recebo dados
				(string) $pesq_avanc8  = $this->funcoes->anti_injection($_POST['pesq_avanc_telefone2']); // Recebo dados
				(string) $pesq_avanc9  = $this->funcoes->anti_injection($_POST['pesq_avanc_email']); // Recebo dados
				(string) $pesq_avanc10 = $this->funcoes->anti_injection($_POST['pesq_avanc_email2']); // Recebo dados
				(int) $pesq_avanc11    = $this->funcoes->anti_injection($_POST['pesq_avanc_cargos']); // Recebo dados
				(string) $pesq_avanc12 = $this->funcoes->anti_injection($_POST['pesq_avanc_username']); // Recebo dados
				(string) $pesq_avanc13 = $this->funcoes->anti_injection($_POST['pesq_avanc_useremail']); // Recebo dados
				(string) $pesq_avanc14 = $this->funcoes->anti_injection($_POST['pesq_avanc_logradouro']); // Recebo dados
				(int) $pesq_avanc15    = $this->funcoes->anti_injection($_POST['pesq_avanc_numero']); // Recebo dados
				(string) $pesq_avanc16 = $this->funcoes->anti_injection($_POST['pesq_avanc_complemento']); // Recebo dados
				(string) $pesq_avanc17 = $this->funcoes->anti_injection($_POST['pesq_avanc_bairro']); // Recebo dados
				(string) $pesq_avanc18 = $this->funcoes->anti_injection($_POST['pesq_avanc_CEP']); // Recebo dados
				(string) $pesq_avanc19 = $this->funcoes->anti_injection($_POST['pesq_avanc_estado']); // Recebo dados
				(string) $pesq_avanc20 = $this->funcoes->anti_injection($_POST['pesq_avanc_cidade']); // Recebo dados
				(string) $pesq_avanc21 = $this->funcoes->anti_injection($_POST['pesq_avanc_status']); // Recebo dados

				//SETO OS DADOS DA PESQUISA AVANÇADA NA SESSÃO ****-
				if(!empty($pesq_avanc1)){ $pesq_avancada['periodo_tipo_data'] = $pesq_avanc1; }
				if(!empty($pesq_avanc2) && !empty($pesq_avanc1)){ $pesq_avancada['periodo_data_de'] = $pesq_avanc2; }
				if(!empty($pesq_avanc3) && !empty($pesq_avanc1)){ $pesq_avancada['periodo_data_ate'] = $pesq_avanc3; }
				if(!empty($pesq_avanc4)){ $pesq_avancada['pesq_avanc_nome'] = $pesq_avanc4; }
				if(!empty($pesq_avanc5)){ $pesq_avancada['pesq_avanc_sexo'] = $pesq_avanc5; }
				if(!empty($pesq_avanc6)){ $pesq_avancada['pesq_avanc_data_nascimento'] = $pesq_avanc6; }
				if(!empty($pesq_avanc7)){ $pesq_avancada['pesq_avanc_telefone'] = $pesq_avanc7; }
				if(!empty($pesq_avanc8)){ $pesq_avancada['pesq_avanc_telefone2'] = $pesq_avanc8; }
				if(!empty($pesq_avanc9)){ $pesq_avancada['pesq_avanc_email'] = $pesq_avanc9; }
				if(!empty($pesq_avanc10)){ $pesq_avancada['pesq_avanc_email2'] = $pesq_avanc10; }
				if(!empty($pesq_avanc11)){ $pesq_avancada['pesq_avanc_cargos'] = $pesq_avanc11; }
				if(!empty($pesq_avanc12)){ $pesq_avancada['pesq_avanc_username'] = $pesq_avanc12; }
				if(!empty($pesq_avanc13)){ $pesq_avancada['pesq_avanc_useremail'] = $pesq_avanc13; }
				if(!empty($pesq_avanc14)){ $pesq_avancada['pesq_avanc_logradouro'] = $pesq_avanc14; }
				if(!empty($pesq_avanc15)){ $pesq_avancada['pesq_avanc_numero'] = $pesq_avanc15; }
				if(!empty($pesq_avanc16)){ $pesq_avancada['pesq_avanc_complemento'] = $pesq_avanc16; }
				if(!empty($pesq_avanc17)){ $pesq_avancada['pesq_avanc_bairro'] = $pesq_avanc17; }
				if(!empty($pesq_avanc18)){ $pesq_avancada['pesq_avanc_CEP'] = $pesq_avanc18; }
				if(!empty($pesq_avanc19)){ $pesq_avancada['pesq_avanc_estado'] = $pesq_avanc19; }
				if(!empty($pesq_avanc20)){ $pesq_avancada['pesq_avanc_cidade'] = $pesq_avanc20; }
				if(!empty($pesq_avanc21)){ $pesq_avancada['pesq_avanc_status'] = $pesq_avanc21; }

				//

				//===========================================================
				//MONTO O MENU DE FILTROS ****-
				$this->funcoes->set_array(0,'btn_status',true);
				$this->funcoes->set_array(0,'btn_texto','Status Ativo');
				$this->funcoes->set_array(0,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(0,'btn_target','');
				$this->funcoes->set_array(0,'btn_title','');
				$this->funcoes->set_array(0,'btn_id','');
				$this->funcoes->set_array(0,'btn_class','btn_param_pesquisa'); // (btn_param_pesquisa) = seto e submeto a pesquisa - (btn_param_pesquisa_focus) = seto o valor e foco -
				$this->funcoes->set_array(0,'btn_class_icon','fa fa-check');
				$this->funcoes->set_array(0,'btn_outros','param_pesquisa="Status: Ativo"');
				$this->funcoes->set_array(1,'btn_status',true);
				$this->funcoes->set_array(1,'btn_texto','Status Inativo');
				$this->funcoes->set_array(1,'btn_href','javascript:void(0);');
				$this->funcoes->set_array(1,'btn_target','');
				$this->funcoes->set_array(1,'btn_title','');
				$this->funcoes->set_array(1,'btn_id','');
				$this->funcoes->set_array(1,'btn_class','btn_param_pesquisa'); // (btn_param_pesquisa) = seto e submeto a pesquisa - (btn_param_pesquisa_focus) = seto o valor e foco -
				$this->funcoes->set_array(1,'btn_class_icon','fa fa-remove');
				$this->funcoes->set_array(1,'btn_outros','param_pesquisa="Status: Inativo"');
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
				$this->funcoes->set_array(7,'value',"DATA MODIFICADO DECRESCENTE");
				$this->funcoes->set_array(8,'value',"separator"); //separator
				$this->funcoes->set_array(9,'value',"NOME CRESCENTE");
				$this->funcoes->set_array(10,'value',"NOME DECRESCENTE");
				$this->funcoes->set_array(11,'value',"separator"); //separator
				$this->funcoes->set_array(12,'value',"USERNAME CRESCENTE");
				$this->funcoes->set_array(13,'value',"USERNAME DECRESCENTE");
				$this->funcoes->set_array(14,'value',"separator"); //separator
				$this->funcoes->set_array(15,'value',"EMAIL CRESCENTE");
				$this->funcoes->set_array(16,'value',"EMAIL DECRESCENTE");
				$option_ordenar = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO OS OPTIONS DE AGRUPAR ****-
				$this->funcoes->set_array(null,'pagina','Página');
				$this->funcoes->set_array(null,'acao','Ação');
				$option_agrupar = $this->funcoes->get_array(); // Seto o array na view [Array]

				//===========================================================
				//MONTO O HEADER DA TABELA ****-
				$this->funcoes->set_array(0,'th_titulo','');
				$this->funcoes->set_array(0,'th_class','text-center col-md-1 col-lg-1 hidden-md');
				$this->funcoes->set_array(0,'th_outros','');
				$this->funcoes->set_array(1,'th_titulo','Usuário');
				$this->funcoes->set_array(1,'th_class','col-md-4 col-lg-3');
				$this->funcoes->set_array(1,'th_outros','');
				$this->funcoes->set_array(2,'th_titulo','Restrições');
				$this->funcoes->set_array(2,'th_class','text-center col-md-2 col-lg-2 hidden-md');
				$this->funcoes->set_array(2,'th_outros','');
				$this->funcoes->set_array(3,'th_titulo','<i class="fa fa-check-circle"></i> Status');
				$this->funcoes->set_array(3,'th_class','text-center col-md-2 col-lg-2');
				$this->funcoes->set_array(3,'th_outros','');
				$this->funcoes->set_array(4,'th_titulo','<i class="fa fa-clock-o"></i> Último Acesso');
				$this->funcoes->set_array(4,'th_class','text-center col-md-3 col-lg-2');
				$this->funcoes->set_array(4,'th_outros','');
				$this->funcoes->set_array(5,'th_titulo','<i class="fa fa-calendar-o"></i> Data Cadastro');
				$this->funcoes->set_array(5,'th_class','text-center col-md-3 col-lg-2 hidden-md');
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
				$mont_html->set_array(0,'tipo',"data"); // data, hora, hora_min, data_hora, idade, tempo, tempo_hora, enc_texto, maiusculo, minusculo, maiusc_minusc e format_num
				$mont_html->set_array(0,'campo',"[bd:data_nascimento]"); // campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'valor_enc_texto',""); // valor numérico do tamanho do texto
				$data_nascimento = $mont_html->filtros_smarty();

				//FILTROS SMARTY PHP
				$mont_html->set_array(0,'tipo',"data_hora"); // data, hora, hora_min, data_hora, idade, tempo, tempo_hora, enc_texto, maiusculo, minusculo, maiusc_minusc e format_num
				$mont_html->set_array(0,'campo',"[bd:ultimo_acesso]"); // campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'valor_enc_texto',""); // valor numérico do tamanho do texto
				$ult_acesso = $mont_html->filtros_smarty();

				//FILTROS SMARTY PHP
			    $mont_html->set_array(0,'tipo',"maiusc_minusc"); // data, hora, hora_min, data_hora, idade, tempo, tempo_hora, enc_texto, maiusculo, minusculo, maiusc_minusc e format_num
			    $mont_html->set_array(0,'campo',"[bd:nome]"); // campo do banco [bd:campo] ou variavel smarty [sm:variavel]
			    $mont_html->set_array(0,'valor_enc_texto',""); // valor numérico do tamanho do texto
			    $nome = $mont_html->filtros_smarty();


				//CONDIÇÕES IF ELSE SMARTY
				$mont_html->set_array(0,'condicoes',"[bd:status_id] == 1"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," <label class='label-success label'><i class='fa fa-check'></i> Ativo</label> "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else',""); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$status = $mont_html->if_else_smarty();
				$mont_html->set_array(0,'condicoes',"[bd:status_id] == 2"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," <label class='label-danger label'><i class='fa fa-times'></i> Inativo</label> "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else',""); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$status1 = $mont_html->if_else_smarty();

				//AÇÃO DE LINHA DA TABELA
			    $mont_html->set_array(0,'status',true); // Status da ação true or false
			    $mont_html->set_array(0,'class',"btn_detalhamento"); // Tipo: btn_ativar_desativar, btn_detalhamento, btn_editar ou url
			    $mont_html->set_array(0,'url',""); // url de redirecionamento $this->core->get_config('dir_raiz_http').$this->dir_app."/"
			    $mont_html->set_array(0,'status_btn_detalhamento',$this->btns_acoes['status_btn_detalhamento']); // Status ação detalhamento true or false
			    $mont_html->set_array(0,'status_btn_editar',$this->btns_acoes['status_btn_editar']); // Status ação editar true or false
			    $mont_html->set_array(0,'status_btn_ativar',$this->btns_acoes['status_btn_ativar']); // Status ação ativar e desativar true or false
			    $mont_html->set_array(0,'url_btn_ativar',$this->cmds['action_ativar']); // Action do btn ativar
			    $acao_linha = $mont_html->acao_linha_smarty();

				//AÇÃO DE LINHA DA TABELA
				$mont_html->set_array(0,'status',true); // Status da ação true or false
				$mont_html->set_array(0,'class',"btn_ativar_desativar"); // Tipo: btn_ativar_desativar, btn_detalhamento, btn_editar ou url
				$mont_html->set_array(0,'url',""); // url de redirecionamento $this->core->get_config('dir_raiz_http').$this->dir_app."/"
				$mont_html->set_array(0,'status_btn_detalhamento',$this->btns_acoes['status_btn_detalhamento']); // Status ação detalhamento true or false
				$mont_html->set_array(0,'status_btn_editar',$this->btns_acoes['status_btn_editar']); // Status ação editar true or false
				$mont_html->set_array(0,'status_btn_ativar',$this->btns_acoes['status_btn_ativar']); // Status ação ativar e desativar true or false
				$mont_html->set_array(0,'url_btn_ativar',$this->cmds['action_ativar']); // Action do btn ativar
				$acao_linha1 = $mont_html->acao_linha_smarty();

				//AÇÃO DE LINHA DA TABELA
				$mont_html->set_array(0,'status',true); // Status da ação true or false
				$mont_html->set_array(0,'class',"url"); // Tipo: btn_ativar_desativar, btn_detalhamento, btn_editar ou url
				$mont_html->set_array(0,'url',$this->core->get_config('dir_raiz_http').$this->dir_app."/adm_restricoes/busca/ID Usuário: [bd:id]"); // url de redirecionamento $this->core->get_config('dir_raiz_http').$this->dir_app."/"
				$mont_html->set_array(0,'status_btn_detalhamento',$this->btns_acoes['status_btn_detalhamento']); // Status ação detalhamento true or false
				$mont_html->set_array(0,'status_btn_editar',$this->btns_acoes['status_btn_editar']); // Status ação editar true or false
				$mont_html->set_array(0,'status_btn_ativar',$this->btns_acoes['status_btn_ativar']); // Status ação ativar e desativar true or false
				$mont_html->set_array(0,'url_btn_ativar',$this->cmds['action_ativar']); // Action do btn ativar
				$acao_linha2 = $mont_html->acao_linha_smarty();

				//MONTO HTML DE UMA IMAGEM
				$mont_html->set_array(0,'src',$this->core->get_config('dir_raiz_http')."files/perfil_usuario/p/[bd:img_perfil]"); // $this->core->get_config('dir_raiz_http').'files/'; texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'alt',"[bd:nome]"); // texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'class',"img-circle center-cropped");
				$mont_html->set_array(0,'width',"50"); // valor numérico do tamanho do da imagem
				$mont_html->set_array(0,'height',"50"); // valor numérico do tamanho do da imagem
				$mont_html->set_array(0,'lightbox',true); // lightbox true ou false
				$imagem_bd = $mont_html->monto_html_img();
				$mont_html->set_array(0,'src',$this->core->get_config('dir_raiz_http')."".$this->dir_app."/view/assets/img/avatar/avatar_h.jpg"); // $this->core->get_config('dir_raiz_http').'files/'; texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'alt',"[bd:nome]"); // texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'class',"img-circle center-cropped");
				$mont_html->set_array(0,'width',"50"); // valor numérico do tamanho do da imagem
				$mont_html->set_array(0,'height',""); // valor numérico do tamanho do da imagem
				$mont_html->set_array(0,'lightbox',false); // lightbox true ou false
				$imagem_pd = $mont_html->monto_html_img();
				$mont_html->set_array(0,'condicoes'," [bd:img_perfil] != '' "); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_if'," {$imagem_bd} "); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$mont_html->set_array(0,'cod_else'," {$imagem_pd} "); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
				$img_usuario = $mont_html->if_else_smarty();

				//CONDIÇÕES IF ELSE SMARTY
			    $mont_html->set_array(0,'condicoes',"[bd:usuario_online] == 1"); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
			    $mont_html->set_array(0,'cod_if',' <i title="Online" class="fa fa-circle text-success"></i> '); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
			    $mont_html->set_array(0,'cod_else',' <i title="Offline" class="fa fa-circle text-cinza"></i> '); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
			    $usuario_online = $mont_html->if_else_smarty();

				//===========================================================
				//CONTEUDO MONTADO
				$mont_html->set_array(0,'td'," {$img_usuario} "); // Coluna montada **
				$mont_html->set_array(0,'class',"hidden-md text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(0,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
				$mont_html->set_array(1,'td'," <span class='text-uppercase'><strong>{$nome}</strong></span> {$usuario_online} <br /> [bd:username] <br /> [bd:useremail]"); // Coluna montada **
				$mont_html->set_array(1,'class',"{$acao_linha['class']}"); // Classes
				$mont_html->set_array(1,'outros'," {$acao_linha['outros']}"); //Outros parametros do td
				$mont_html->set_array(2,'td'," <i class='fa fa-lock'></i> Restrições "); // Coluna montada **
				$mont_html->set_array(2,'class'," hidden-md text-center {$acao_linha2['class']}"); // Classes
				$mont_html->set_array(2,'outros',"title='Definir as restrições de acesso do usuário no sistema'  {$acao_linha2['outros']}"); // Outros parametros do td
				$mont_html->set_array(3,'td'," {$status}{$status1} "); // Coluna montada **
				$mont_html->set_array(3,'class',"load-elements text-center {$acao_linha1['class']}"); // Classes
				$mont_html->set_array(3,'outros',"title='Alterar Status' {$acao_linha1['outros']}"); // Outros parametros do td
				$mont_html->set_array(4,'td'," {$ult_acesso} "); // Coluna montada **
				$mont_html->set_array(4,'class',"text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(4,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
				$mont_html->set_array(5,'td'," {$criado} "); // Coluna montada **
				$mont_html->set_array(5,'class',"hidden-md text-center {$acao_linha['class']}"); // Classes
				$mont_html->set_array(5,'outros'," {$acao_linha['outros']}"); // Outros parametros do td
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

		//TRATO RESTRIÇÃO - Somente o id do usuario ativo sera mostrado
		$this->core->includeControllerView('core',$this->dir_app); //Incluo arquivo coreController.php
	    $controller_geral_ = new coreController();
	    (array) $restricoes = $controller_geral_->verifico_restricoes_sistema($this->adm_usuario_modulo_id);
	    if(count($restricoes)>=1){
	        for ($i=0; $i <count($restricoes) ; $i++) {
				if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 18 && $restricoes[$i]['opcoes'] == 0
				){
					$id	= $_SESSION[$this->dir_app.'_id_user'];
				}
			}
		}

		//===========================================================
		//SETO VALORES EM ADICIONAR
		if(empty($id)){

			//MONTO OS DADOS NOS CAMPOS ****

			/*//CAMPO
			$this->funcoes->set_array(0,'tipo_set','input'); // input, textarea, checkbox, select, show_hide
			$this->funcoes->set_array(0,'campo_set_form','campo_name');
			$this->funcoes->set_array(0,'valor_campo_set','VALOR DE TESTE');*/

			//CAMPO HIDDEN RESTRIÇÕES DE ACESSO
			$this->funcoes->set_array(0,'tipo_set','show_hide'); // input, textarea, checkbox, select
			$this->funcoes->set_array(0,'campo_set_form','.restricoes_acess');
			$this->funcoes->set_array(0,'valor_campo_set','hide');

			//CAMPO HIDDEN tel_acesso
			$this->funcoes->set_array(1,'tipo_set','show_hide'); // input, textarea, checkbox, select
			$this->funcoes->set_array(1,'campo_set_form','.tel_acesso');
			$this->funcoes->set_array(1,'valor_campo_set','hide');

			//CAMPO ESTADO
			$this->funcoes->set_array(2,'tipo_set','input'); // input, textarea, checkbox, select
			$this->funcoes->set_array(2,'campo_set_form','estado_id');
			$this->funcoes->set_array(2,'valor_campo_set',11);

			//CAMPO CIDADE
			$this->funcoes->set_array(3,'tipo_set','input'); // input, textarea, checkbox, select
			$this->funcoes->set_array(3,'campo_set_form','cidade_id');
			$this->funcoes->set_array(3,'valor_campo_set',3574);

			//CAMPO HIDDEN MENSAGEM INFORMATIVA
			$this->funcoes->set_array(4,'tipo_set','show_hide'); // input, textarea, checkbox, select
			$this->funcoes->set_array(4,'campo_set_form','#msg_info_usuario_cad');
			$this->funcoes->set_array(4,'valor_campo_set','show');

			//CAMPO HIDDEN BTN ALTERAR SENHA
			$this->funcoes->set_array(5,'tipo_set','show_hide'); // input, textarea, checkbox, select
			$this->funcoes->set_array(5,'campo_set_form','#btn_alterar_senha');
			$this->funcoes->set_array(5,'valor_campo_set','hide');

			//CAMPO HIDDEN AREA DE SENHA
			$this->funcoes->set_array(6,'tipo_set','show_hide'); // input, textarea, checkbox, select
			$this->funcoes->set_array(6,'campo_set_form','.area_senha');
			$this->funcoes->set_array(6,'valor_campo_set','hide');

			//CAMPO HIDDEN BTNS
			$this->funcoes->set_array(7,'tipo_set','show_hide'); // input, textarea, checkbox, select
			$this->funcoes->set_array(7,'campo_set_form','.link_restricao');
			$this->funcoes->set_array(7,'valor_campo_set','hide');

			//CAMPO HIDDEN BTNS
			$this->funcoes->set_array(8,'tipo_set','show_hide'); // input, textarea, checkbox, select
			$this->funcoes->set_array(8,'campo_set_form','.link_restricao2');
			$this->funcoes->set_array(8,'valor_campo_set','hide');



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

					//CAMPO NOME
					$this->funcoes->set_array(0,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(0,'campo_set_form','nome');
					$this->funcoes->set_array(0,'valor_campo_set',$exec[$i]['nome']);

					//CAMPO SEXO
					$this->funcoes->set_array(1,'tipo_set','select_estatico'); // input, textarea, checkbox, select
					$this->funcoes->set_array(1,'campo_set_form','sexo');
					$this->funcoes->set_array(1,'valor_campo_set',$exec[$i]['sexo']);

					//CAMPO DATA DE NASCIMENTO
					$this->funcoes->set_array(2,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(2,'campo_set_form','data_nascimento');
					$this->funcoes->set_array(2,'valor_campo_set',$this->funcoes->conv_datahora($exec[$i]['data_nascimento'],'d/m/Y'));

					//CAMPO TELEFONE
					$this->funcoes->set_array(3,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(3,'campo_set_form','telefone');
					$this->funcoes->set_array(3,'valor_campo_set',$exec[$i]['telefone']);

					//CAMPO TELEFONE 2
					$this->funcoes->set_array(4,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(4,'campo_set_form','telefone2');
					$this->funcoes->set_array(4,'valor_campo_set',$exec[$i]['telefone2']);

					//CAMPO EMAIL
					$this->funcoes->set_array(5,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(5,'campo_set_form','email');
					$this->funcoes->set_array(5,'valor_campo_set',$exec[$i]['email']);

					//CAMPO EMAIL 2
					$this->funcoes->set_array(6,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(6,'campo_set_form','email2');
					$this->funcoes->set_array(6,'valor_campo_set',$exec[$i]['email2']);

					//CAMPO USERNAME
					$this->funcoes->set_array(7,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(7,'campo_set_form','username');
					$this->funcoes->set_array(7,'valor_campo_set',$exec[$i]['username']);

					//CAMPO EMAIL DE ACESSO
					$this->funcoes->set_array(8,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(8,'campo_set_form','useremail');
					$this->funcoes->set_array(8,'valor_campo_set',$exec[$i]['useremail']);

					//CAMPO TELEFONE DE ACESSO
					$this->funcoes->set_array(9,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(9,'campo_set_form','usertelefone');
					$this->funcoes->set_array(9,'valor_campo_set',$exec[$i]['usertelefone']);

					//CAMPO LOGRADOURO
					$this->funcoes->set_array(10,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(10,'campo_set_form','logradouro');
					$this->funcoes->set_array(10,'valor_campo_set',$exec[$i]['logradouro']);

					//CAMPO NÚMERO
					$this->funcoes->set_array(11,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(11,'campo_set_form','numero');
					$this->funcoes->set_array(11,'valor_campo_set',$exec[$i]['numero']);

					//CAMPO COMPLEMENTO
					$this->funcoes->set_array(12,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(12,'campo_set_form','complemento');
					$this->funcoes->set_array(12,'valor_campo_set',$exec[$i]['complemento']);

					//CAMPO BAIRRO
					$this->funcoes->set_array(13,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(13,'campo_set_form','bairro');
					$this->funcoes->set_array(13,'valor_campo_set',$exec[$i]['bairro']);

					//CAMPO CEP
					$this->funcoes->set_array(14,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(14,'campo_set_form','CEP');
					$this->funcoes->set_array(14,'valor_campo_set',$exec[$i]['CEP']);

					//CAMPO ESTADO
					$this->funcoes->set_array(15,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(15,'campo_set_form','estado_id');
					$this->funcoes->set_array(15,'valor_campo_set',$exec[$i]['estado_id']);

					//CAMPO CIDADE
					$this->funcoes->set_array(16,'tipo_set','input'); // input, textarea, checkbox, select
					$this->funcoes->set_array(16,'campo_set_form','cidade_id');
					$this->funcoes->set_array(16,'valor_campo_set',$exec[$i]['cidade_id']);

					//CAMPO HIDDEN RESTRIÇÕES DE ACESSO
					$this->funcoes->set_array(17,'tipo_set','show_hide'); // input, textarea, checkbox, select
					$this->funcoes->set_array(17,'campo_set_form','.restricoes_acess');
					$this->funcoes->set_array(17,'valor_campo_set','show');

					//CAMPO HIDDEN tel_acesso
					$this->funcoes->set_array(18,'tipo_set','show_hide'); // input, textarea, checkbox, select
					$this->funcoes->set_array(18,'campo_set_form','.tel_acesso');
					$this->funcoes->set_array(18,'valor_campo_set','show');

					//CAMPO HIDDEN tel_acesso
					$this->funcoes->set_array(19,'tipo_set','attr_param'); // input, textarea, checkbox, select
					$this->funcoes->set_array(19,'campo_set_form','#link_restricao');
					$this->funcoes->set_array(19,'valor_campo_set','ID Usuário: '.$exec[$i]['id']);

					//CAMPO CARGOS
					$exec1 = $this->model->set_editar2();
					for ($i1=0; $i1 < count($exec1) ; $i1++) {
						$this->funcoes->set_array(20,'tipo_set','input'); // input, textarea, checkbox, select
						$this->funcoes->set_array(20,'campo_set_form','cargos_id');
						$this->funcoes->set_array(20,'valor_campo_set',$exec1[$i1]['adm_usuario_nivel_id']);
					}

					//CAMPO HIDDEN MENSAGEM INFORMATIVA
					$this->funcoes->set_array(21,'tipo_set','show_hide'); // input, textarea, checkbox, select
					$this->funcoes->set_array(21,'campo_set_form','#msg_info_usuario_cad');
					$this->funcoes->set_array(21,'valor_campo_set','hide');

					//CAMPO HIDDEN BTN ALTERAR SENHA
					$this->funcoes->set_array(22,'tipo_set','show_hide'); // input, textarea, checkbox, select
					$this->funcoes->set_array(22,'campo_set_form','#btn_alterar_senha');
					$this->funcoes->set_array(22,'valor_campo_set','show');

					//CAMPO HIDDEN AREA DE SENHA
					$this->funcoes->set_array(23,'tipo_set','show_hide'); // input, textarea, checkbox, select
					$this->funcoes->set_array(23,'campo_set_form','.area_senha');
					$this->funcoes->set_array(23,'valor_campo_set','hide');

					//CAMPO HIDDEN BTNS
					$this->funcoes->set_array(24,'tipo_set','show_hide'); // input, textarea, checkbox, select
					$this->funcoes->set_array(24,'campo_set_form','.link_restricao');
					$this->funcoes->set_array(24,'valor_campo_set','show');

					//CAMPO HIDDEN BTNS
					$this->funcoes->set_array(25,'tipo_set','show_hide'); // input, textarea, checkbox, select
					$this->funcoes->set_array(25,'campo_set_form','.link_restricao2');
					$this->funcoes->set_array(25,'valor_campo_set','show');

					//CAMPO ASSINATURA DE EMAIL
					$this->funcoes->set_array(26,'tipo_set','textarea'); // input, textarea, checkbox, select
					$this->funcoes->set_array(26,'campo_set_form','assinatura_email');
					$this->funcoes->set_array(26,'valor_campo_set',$exec[$i]['assinatura_email']);

					//SETO INFORMAÇÕES HTML NO EDITAR
					$this->funcoes->set_array(27,'tipo_set','info_editar');
					if($exec[$i]['ultimo_acesso'] != "0000-00-00 00:00:00"){
						$ultimo_acesso = ' - <strong> <i class="fa fa-clock-o"></i> Último Acesso: </strong> '.$this->funcoes->retorno_tempo_post($exec[$i]['ultimo_acesso']);
					}
					if($exec[$i]['ultima_mod_senha']!="0000-00-00 00:00:00"){
						$ultima_mod_senha = '<br /> <strong><i class="fa fa-clock-o"></i> Última Modificação da Senha: </strong> '.$this->funcoes->retorno_tempo_post($exec[$i]['ultima_mod_senha']);
					}
					$this->funcoes->set_array(27,'valor_campo_set','<strong> <i class="fa fa-clock-o"></i> Cadastrado em: </strong> '.$this->funcoes->conv_datahora($exec[$i]['criado'],'d/m/Y H:i:s').'  '.$ultimo_acesso.' '.$ultima_mod_senha.'  ');

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

		//MONTO COMBOBOX
		$this->funcoes->set_array(0,'tipo','combobox'); // Tipo
		$this->funcoes->set_array(0,'tipo_combobox','estado'); // tipo combobox
		$this->funcoes->set_array(0,'id_combobox','estado'); // id do campo
		$this->funcoes->set_array(0,'txt_plural_combobox','Estados'); // Texto no plural
		$this->funcoes->set_array(0,'txt_sing_combobox','Estado'); // Texto no singular

		//MONTO CHANGBOX
		$this->funcoes->set_array(1,'tipo','changbox'); // Tipo
		$this->funcoes->set_array(1,'tipo_changbox','cidade'); // tipo changbox
		$this->funcoes->set_array(1,'id_changbox','cidade'); // id do campo
		$this->funcoes->set_array(1,'txt_plural_changbox','Cidades'); // Texto no plural
		$this->funcoes->set_array(1,'txt_sing_changbox','Cidade'); // Texto no singular
		$this->funcoes->set_array(1,'id_combobox','estado'); // id do campo
		$this->funcoes->set_array(1,'txt_sing_combobox','Estado'); // Texto no singular

		//MONTO AUTOCOMPLETE
	    $this->funcoes->set_array(2,'tipo','autocomplete'); // Tipo
	    $this->funcoes->set_array(2,'tipo_autocomplete','logradouro'); // tipo autocomplete
	    $this->funcoes->set_array(2,'id_autocomplete','logradouro'); // id do campo

		//MONTO AUTOCOMPLETE
		$this->funcoes->set_array(3,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(3,'tipo_autocomplete','bairro'); // tipo autocomplete
		$this->funcoes->set_array(3,'id_autocomplete','bairro'); // id do campo

		//MONTO cep
		$this->funcoes->set_array(4,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(4,'tipo_autocomplete','CEP'); // tipo autocomplete
		$this->funcoes->set_array(4,'id_autocomplete','CEP'); // id do campo

		//MONTO COMBOBOX
		$this->funcoes->set_array(5,'tipo','combobox'); // Tipo
		$this->funcoes->set_array(5,'tipo_combobox','cargos'); // tipo combobox
		$this->funcoes->set_array(5,'id_combobox','cargos'); // id do campo
		$this->funcoes->set_array(5,'txt_plural_combobox','Cargos'); // Texto no plural
		$this->funcoes->set_array(5,'txt_sing_combobox','Cargo'); // Texto no singular

		//MONTO NOME
		$this->funcoes->set_array(6,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(6,'tipo_autocomplete','nome'); // tipo autocomplete
		$this->funcoes->set_array(6,'id_autocomplete','pesq_avanc_nome'); // id do campo

		//MONTO TELEFONE
		$this->funcoes->set_array(7,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(7,'tipo_autocomplete','telefone'); // tipo autocomplete
		$this->funcoes->set_array(7,'id_autocomplete','pesq_avanc_telefone'); // id do campo

		//MONTO TELEFONE2
		$this->funcoes->set_array(8,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(8,'tipo_autocomplete','telefone2'); // tipo autocomplete
		$this->funcoes->set_array(8,'id_autocomplete','pesq_avanc_telefone2'); // id do campo

		//MONTO EMAIL
		$this->funcoes->set_array(9,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(9,'tipo_autocomplete','email'); // tipo autocomplete
		$this->funcoes->set_array(9,'id_autocomplete','pesq_avanc_email'); // id do campo

		//MONTO EMAIL2
		$this->funcoes->set_array(10,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(10,'tipo_autocomplete','email2'); // tipo autocomplete
		$this->funcoes->set_array(10,'id_autocomplete','pesq_avanc_email2'); // id do campo

		//MONTO USERNAME
		$this->funcoes->set_array(11,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(11,'tipo_autocomplete','username'); // tipo autocomplete
		$this->funcoes->set_array(11,'id_autocomplete','pesq_avanc_username'); // id do campo

		//MONTO EMAIL DE ACESSO
		$this->funcoes->set_array(12,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(12,'tipo_autocomplete','useremail'); // tipo autocomplete
		$this->funcoes->set_array(12,'id_autocomplete','pesq_avanc_useremail'); // id do campo

		//MONTO LOGRADOURO
		$this->funcoes->set_array(13,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(13,'tipo_autocomplete','logradouro_pesq'); // tipo autocomplete
		$this->funcoes->set_array(13,'id_autocomplete','pesq_avanc_logradouro'); // id do campo

		//MONTO NUMERO
		$this->funcoes->set_array(14,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(14,'tipo_autocomplete','numero'); // tipo autocomplete
		$this->funcoes->set_array(14,'id_autocomplete','pesq_avanc_numero'); // id do campo

		//MONTO COMPLEMENTO
		$this->funcoes->set_array(15,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(15,'tipo_autocomplete','complemento'); // tipo autocomplete
		$this->funcoes->set_array(15,'id_autocomplete','pesq_avanc_complemento'); // id do campo

		//MONTO BAIRRO
		$this->funcoes->set_array(16,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(16,'tipo_autocomplete','bairro_pesq'); // tipo autocomplete
		$this->funcoes->set_array(16,'id_autocomplete','pesq_avanc_bairro'); // id do campo

		//MONTO CEP
		$this->funcoes->set_array(17,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(17,'tipo_autocomplete','CEP_pesq'); // tipo autocomplete
		$this->funcoes->set_array(17,'id_autocomplete','pesq_avanc_CEP'); // id do campo

		//MONTO ESTADO
		$this->funcoes->set_array(18,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(18,'tipo_autocomplete','estado'); // tipo autocomplete
		$this->funcoes->set_array(18,'id_autocomplete','pesq_avanc_estado'); // id do campo

		//MONTO CIDADE
		$this->funcoes->set_array(19,'tipo','autocomplete'); // Tipo
		$this->funcoes->set_array(19,'tipo_autocomplete','cidade'); // tipo autocomplete
		$this->funcoes->set_array(19,'id_autocomplete','pesq_avanc_cidade'); // id do campo


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
		//AUTOCOMPLETE
		if($tipo == 'logradouro'){
			$this->model->setCampos('campo_tabela',"adm_usuario_endereco");
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
		//AUTOCOMPLETE
		if($tipo == 'bairro'){
			$this->model->setCampos('campo_tabela',"adm_usuario_endereco");
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
		//AUTOCOMPLETE
		if($tipo == 'CEP'){
			$this->model->setCampos('campo_tabela',"adm_usuario_endereco");
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
		//AUTOCOMPLETE
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

		//===========================================================
		//AUTOCOMPLETE
		if($tipo == 'telefone'){
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

		//===========================================================
		//AUTOCOMPLETE
		if($tipo == 'telefone2'){
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

		//===========================================================
		//AUTOCOMPLETE
		if($tipo == 'email'){
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

		//===========================================================
		//AUTOCOMPLETE
		if($tipo == 'email2'){
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

		//===========================================================
		//AUTOCOMPLETE
		if($tipo == 'username'){
			$this->model->setCampos('campo_tabela',"adm_usuario_auth");
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
		//AUTOCOMPLETE
		if($tipo == 'useremail'){
			$this->model->setCampos('campo_tabela',"adm_usuario_auth");
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
		//AUTOCOMPLETE
		if($tipo == 'logradouro_pesq'){
			$this->model->setCampos('campo_tabela',"adm_usuario_endereco");
			$this->model->setCampos('campo_coluna',"logradouro");
			$this->model->setCampos('campo_where',"logradouro LIKE '%{$valor}%'");
			$this->model->setCampos('campo_groupby',"logradouro");
			$this->model->setCampos('campo_orderby',"");
			$valor0 = $this->model->select_simples_retorna_array_mont();
			if(!empty($valor0)){
				echo json_encode($valor0);
			}
			$this->model->getLimpoCampos();
		}

		//===========================================================
		//AUTOCOMPLETE
		if($tipo == 'numero'){
			$this->model->setCampos('campo_tabela',"adm_usuario_endereco");
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
		//AUTOCOMPLETE
		if($tipo == 'complemento'){
			$this->model->setCampos('campo_tabela',"adm_usuario_endereco");
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
		//AUTOCOMPLETE
		if($tipo == 'bairro_pesq'){
			$this->model->setCampos('campo_tabela',"adm_usuario_endereco");
			$this->model->setCampos('campo_coluna',"bairro");
			$this->model->setCampos('campo_where',"bairro LIKE '%{$valor}%'");
			$this->model->setCampos('campo_groupby',"bairro");
			$this->model->setCampos('campo_orderby',"");
			$valor0 = $this->model->select_simples_retorna_array_mont();
			if(!empty($valor0)){
				echo json_encode($valor0);
			}
			$this->model->getLimpoCampos();
		}

		//===========================================================
		//AUTOCOMPLETE
		if($tipo == 'CEP_pesq'){
			$this->model->setCampos('campo_tabela',"adm_usuario_endereco");
			$this->model->setCampos('campo_coluna',"CEP");
			$this->model->setCampos('campo_where',"CEP LIKE '%{$valor}%'");
			$this->model->setCampos('campo_groupby',"CEP");
			$this->model->setCampos('campo_orderby',"");
			$valor0 = $this->model->select_simples_retorna_array_mont();
			if(!empty($valor0)){
				echo json_encode($valor0);
			}
			$this->model->getLimpoCampos();
		}

		//===========================================================
		//AUTOCOMPLETE
		if($tipo == 'estado'){
			$this->model->setCampos('campo_tabela',"local_estado");
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
		//AUTOCOMPLETE
		if($tipo == 'cidade'){
			$this->model->setCampos('campo_tabela',"local_cidade");
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

		//===========================================================
		//COMBOBOX CARGOS
			if($tipo == 'cargos'){
				$this->model->setCampos('campo_tabela',"adm_usuario_nivel");
				$this->model->setCampos('campo_coluna',"id");
				$this->model->setCampos('campo_coluna2',"nivel");
				$this->model->setCampos('campo_where',"");
				$this->model->setCampos('campo_orderby',"");
				$valor = $this->model->select_simples_retorna_array_mont_vcol();
				for ($i=0; $i < count($valor) ; $i++) {
					$array[] = array(
						'campo1' => $valor[$i]['id'],
						'campo2' => $valor[$i]['nivel'],
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
        $id                  = $this->funcoes->anti_injection($_POST['id']); //Padrão
        $op_salvar           = $this->funcoes->anti_injection($_POST['op_salvar']); //Padrão
		(string) $nome             = $this->funcoes->anti_injection($_POST['nome']);
		(string) $sexo             = $this->funcoes->anti_injection($_POST['sexo']);
		(string) $data_nascimento  = $this->funcoes->anti_injection($_POST['data_nascimento']);
		(string) $telefone         = $this->funcoes->anti_injection($_POST['telefone']);
		(string) $telefone2        = $this->funcoes->anti_injection($_POST['telefone2']);
		(string) $email            = $this->funcoes->anti_injection($_POST['email']);
		(string) $email2           = $this->funcoes->anti_injection($_POST['email2']);
		(string) $username         = $this->funcoes->anti_injection($_POST['username']);
		(string) $useremail        = $this->funcoes->anti_injection($_POST['useremail']);
		(int) $usertelefone        = $this->funcoes->anti_injection($_POST['usertelefone']);
		(string) $senha            = $this->funcoes->anti_injection(md5($_POST['senha']));
		(string) $senha_sem_hash   = $this->funcoes->anti_injection($_POST['senha']);
		(string) $conf_senha       = $this->funcoes->anti_injection($_POST['conf_senha']);
		(string) $logradouro       = $this->funcoes->anti_injection($_POST['logradouro']);
		(int) $numero              = $this->funcoes->anti_injection($_POST['numero']);
		(string) $complemento      = $this->funcoes->anti_injection($_POST['complemento']);
		(string) $bairro           = $this->funcoes->anti_injection($_POST['bairro']);
		(string) $CEP              = $this->funcoes->anti_injection($_POST['CEP']);
		(int) $estado              = $this->funcoes->anti_injection($_POST['estado']);
		(int) $cidade              = $this->funcoes->anti_injection($_POST['cidade']);
		(int) $cargos              = $this->funcoes->anti_injection($_POST['cargos']);
		(string) $assinatura_email = $this->funcoes->anti_injection($_POST['assinatura_email'],'html');
		$this->core->includeHelper('upload');
		$upload = new upload();
		$imagem_perfil = $_FILES['img_perfil'];

		//CONVERTO STRING PARA MAIUSCULO E MINUSCULO
		$nome        = $this->funcoes->conv_string($nome,2);
		$email       = $this->funcoes->conv_string($email,0);
		$email2      = $this->funcoes->conv_string($email2,0);
		$useremail   = $this->funcoes->conv_string($useremail,0);
		$logradouro  = $this->funcoes->conv_string($logradouro,2);
		$bairro      = $this->funcoes->conv_string($bairro,2);
		$complemento = $this->funcoes->conv_string($complemento,2);

		//INSTANCIO SMS
		$this->core->includeHelper('sms');
		$sms = new smsHelper();

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
			if($this->model->retorn_campo_editar('nome', $nome) == $nome){
				$nome_unique = true;
			}
      		$this->funcoes->set('Nome',"nome", $nome)->is_required()->min_length(8)->max_length(250)->is_unique($nome_unique);
			$this->funcoes->set('Sexo',"sexo", $sexo)->is_required()->min_length(2)->max_length(10);
			$this->funcoes->set('Data de Nascimento',"data_nascimento", $data_nascimento)->is_required()->is_date();

			//CONVERTO TELEFONE SE TIVER
			$this->model->setCampos('tabela_retorn_campo_editar','adm_usuario_auth');
			if(!empty($telefone)){
				$usertelefone = $this->funcoes->substituo_strings(array('(',')','-',' '),array('','','',''),$telefone);
				if($this->model->retorn_campo_editar('usertelefone', $usertelefone) == $usertelefone){
					$telefone_unique = true;
				}
				$this->funcoes->set('Telefone',"telefone", $telefone)->is_telefone()->is_unique($telefone_unique);
			}

			$this->funcoes->set('Telefone 2',"telefone2", $telefone2)->is_telefone();
			$this->funcoes->set('E-mail',"email", $email)->is_email()->max_length(250);
			$this->funcoes->set('E-mail 2',"email2", $email2)->is_email()->max_length(250);
			if($_SESSION['adm_id_cargo'] == 1 || $_SESSION['adm_id_cargo'] == 2) {
				$this->funcoes->set('Cargos',"cargos", $cargos)->is_required()->is_num();
			}
			if($this->model->retorn_campo_editar('username', $username) == $username){
				$username_unique = true;
			}
			if($this->model->retorn_campo_editar('useremail', $useremail) == $useremail){
				$useremail_unique = true;
			}
			//$this->funcoes->set('Username',"username", $username)->is_required()->no_whitespaces()->min_length(5)->max_length(20)->is_unique($username_unique);
			$this->funcoes->set('E-mail de acesso',"useremail", $useremail)->is_required()->is_email()->max_length(250)->is_unique($useremail_unique);
			//$this->funcoes->set('Senha',"senha", $senha_sem_hash)->is_required()->min_length(7)->max_length(20)->is_password_num_let();
			//$this->funcoes->set('Confirme sua senha',"conf_senha", $conf_senha)->is_required()->is_compare_campo($senha_sem_hash,"Senha");
			$this->funcoes->set('Logradouro',"logradouro", $logradouro)->min_length(2)->max_length(250);
			$this->funcoes->set('Número',"numero", $numero)->max_length(11)->is_num();
			$this->funcoes->set('Complemento',"complemento", $complemento)->min_length(2)->max_length(250);
			$this->funcoes->set('Bairro',"bairro", $bairro)->min_length(2)->max_length(250);
			$this->funcoes->set('CEP',"CEP", $CEP)->max_length(20)->is_CEP(false);
			$this->funcoes->set('Estado',"estado", $estado)->is_required()->max_length(11)->is_num();
			$this->funcoes->set('Cidade',"cidade", $cidade)->is_required()->max_length(11)->is_num();
			$this->funcoes->set('Assinatura de E-mail',"assinatura_email", $assinatura_email)->min_length(2)->max_length(6000000);


			//GERO O USERNAME COM O NOME DA PESSOA
			$username = str_replace(' ','', $nome).'_'.$this->funcoes->gero_cod_numerico();

			//===========================================================
			//MOSTRO MENSAGEM DE ERROS NA TELA
			if(count($this->funcoes->get_errors())>=1){
				echo json_encode($this->funcoes->get_errors());
				exit;
			}

			//BARRO ADD CARGO DESENVOLVEDOR
			$cargo_ativo = $this->model->retorno_cargo_usuario($_SESSION[$this->dir_app.'_id_user']);
			if($cargo_ativo != 1) {
				if($cargos == 1){
					$this->funcoes->set_array(null,'erro','Você não tem permissão para inserir este tipo de usuário!'); // Mensagem de erro
					echo json_encode($this->funcoes->get_array());
					$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
					->setMensagem('Acesso restrito ao cargo desenvolvedor')->gravo_log(); // Gravo log
					exit();
				}
				if($cargo_ativo == 2) {
					if($cargos == 2){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para inserir este tipo de usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo administrador')->gravo_log(); // Gravo log
						exit();
					}
				}
				if($cargo_ativo == 3) {
					if($cargos == 2){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para inserir este tipo de usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo administrador')->gravo_log(); // Gravo log
						exit();
					}
					if($cargos == 3){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para inserir este tipo de usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo gerente')->gravo_log(); // Gravo log
						exit();
					}
				}
			}

			//===========================================================
			//SE TIVER UPLOAD DE IMAGEM

			//SETO DADOS DE UPLOAD DE ARQUIVO
			$upload->setPasta('perfil_usuario')->setArquivo($imagem_perfil)->setNome_arquivo('')->setTipo_arquivo('jpg')
				   ->setTamanho(5)->setValido_dimensoes(false)->setRedimensiono(true)->setUpload_multiplo(false)
				   ->setWidth(0)->setHeight(0)
				   ->setWidth_p(240)->setHeight_p(110)->setRes_p(93)
				   ->setWidth_m(340)->setHeight_m(210)->setRes_m(92)
				   ->setWidth_g(600)->setHeight_g(400)->setRes_g(91)
				   ->upload_file();

			//RETORNO ARRAY COM ERROS
			$erro_upload = $upload->getMsg_erro();
			if (!empty($erro_upload)) {
				$this->funcoes->set_array(null,'erro','Imagem de Perfil <br />'.$this->funcoes->get_errors_inline($erro_upload)); // Mensagem de erro
				echo json_encode($this->funcoes->get_array());
				exit();
			}

			//RETORNO ARRAY COM NOMES DE ARQUIVOS UPADOS
			$imagem_perfil = $upload->getNome_arquivo_return();

			//===========================================================
	        //SETO OS DADOS ****-
			$this->model->setCampos('nome',$nome);
			$this->model->setCampos('sexo',$sexo);
			$this->model->setCampos('data_nascimento',$this->funcoes->conv_datahora($data_nascimento,"Y-m-d"));
			$this->model->setCampos('telefone',$telefone);
			$this->model->setCampos('telefone2',$telefone2);
			$this->model->setCampos('email',$email);
			$this->model->setCampos('email2',$email2);
			$this->model->setCampos('username',$username);
			$this->model->setCampos('useremail',$useremail);
			$this->model->setCampos('email_notificacoes',$useremail);
			$this->model->setCampos('usertelefone',$usertelefone);
			//$this->model->setCampos('senha',$senha);
			$this->model->setCampos('logradouro',$logradouro);
			$this->model->setCampos('numero',$numero);
			$this->model->setCampos('complemento',$complemento);
			$this->model->setCampos('bairro',$bairro);
			$this->model->setCampos('CEP',$CEP);
			$this->model->setCampos('estado_id',$estado);
			$this->model->setCampos('status_id',1);
			$this->model->setCampos('cidade_id',$cidade);
			$this->model->setCampos('cargos',$cargos);
			$token = md5(uniqid(rand(), true));
			$this->model->setCampos('token',$token);
			$this->model->setCampos('assinatura_email',$assinatura_email);
			$this->model->setCampos('img_perfil',$imagem_perfil[0]);

			//===========================================================
	        //EXECUTO
	        $exec = $this->model->inserir();
			$ult_id = $this->model->getUltimo_id();

			//===========================================================
			//INSIRO NOTIFICACAO
			$this->core->includeControllerView('core',$this->dir_app); //Incluo arquivo coreController.php
		    $controller_geral = new coreController();
			$controller_geral->insiro_notificacao('fa fa-user','Bem-Vindo '.$nome.', complete seu cadastro no sistema. ','usuarios/editar/'.$ult_id.'',$ult_id,'Complete seu Cadastro');

			//===========================================================
			//ENVIO SMS
			$configs_admin = $this->model->retorno_configs_admin();
			if(!empty($usertelefone)){

				//DADOS DE SMS
				$sms->setUsername($configs_admin[0]['sms_username']);
				$sms->setSenha($configs_admin[0]['sms_senha']);

				//MONTO DADOS
				$telefone = $sms->trato_num_telefone($usertelefone);
				$id       = $sms->gero_id();
				$nome     = substr($nome, 0, 10);
				$mensagem = "Olá ".$nome.", você foi cadastrado com sucesso no sistema {$configs_admin[0]['sms_nome']} acesse seu e-mail para finalizar seu cadastro.";

				//DISPARO SMS
				$msg_list .= "55{$telefone};{$mensagem};{$id}"."\n";
				$sms->setMensagem($msg_list);
				$return = $sms->envia_sms();

				//GRAVO LOG SMS
				for ($i=0; $i <count($return) ; $i++) {
					$this->logs->setApp($this->dir_app) //Pasta da aplicação
							   ->setId_sms($id)->setTelefone($telefone)->setStatus(''.$return[$i].'')->setMensagem($mensagem)->gravo_log_sms_enviado();
				}
			}

			//===========================================================
			//ENVIO EMAIL COM CÓDIGO DE VALIDACAO
			$this->core->includeView();
			$view = new view($this->dir_app);
			$view->seto_dados('nome',$nome);
			$view->seto_dados('token',$this->funcoes->mycrypt($token));
			$view->seto_dados('useremail',$useremail);
			$view->seto_dados('id',$this->funcoes->mycrypt($ult_id));
			$view->seto_dados('site',$this->core->get_config('servidor_ativo_comp'));
			$view->seto_dados('path_raiz',$this->core->get_config('dir_raiz_http').$this->dir_app."/");
			$configs_admin = $this->model->retorno_configs_admin();
			$view->seto_dados('assinatura_email',$configs_admin[0]['email_assinatura']);
			$view->seto_dados('nome_empresa',$configs_admin[0]['smtp_nome']);

			//MONTO O E-MAIL
			$this->email->setConexoes('true');
			$this->email->setHost_smtp($configs_admin[0]['smtp_host']);
			$this->email->setUsername_smtp($configs_admin[0]['smtp_username']);
			$this->email->setSenha_smtp($configs_admin[0]['smtp_senha']);
			$this->email->setPorta_smtp($configs_admin[0]['smtp_porta']);
			$this->email->setTls_smtp($configs_admin[0]['smtp_tls']);
			$this->email->setEmail_from($configs_admin[0]['smtp_username']); //email remetente
			$this->email->setNome_remetente($configs_admin[0]['smtp_nome']); //nome remetente
			$this->email->setEmail_send(array($useremail)); //destinatario
			$this->email->setEmail_resposta($configs_admin[0]['smtp_username']); //email resposta
			$this->email->setNome_resposta($configs_admin[0]['smtp_nome']); //nome resposta
			$this->email->setAssunto('Finalize seu Cadastro'); //Assunto
			$this->email->setConteudo($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_cadastro.phtml')); //Conteúdo

			//ENVIO EMAIL
			$exec_email = $this->email->envio_email_phpmailer();
			if($exec_email != true){
			  $this->funcoes->set_array(null,'erro','Erro ao enviar e-mail de cadastro de usuário. '); //Mensagem
			  echo json_encode($this->funcoes->get_array()); //Mostro na tela
			  $this->logs->setApp($this->dir_app)
 			  ->setEmail_re($configs_admin[0]['smtp_username'])->setEmail($useremail) //E-mail Remetene - E-mail destinatario
 			  ->setStatus('Erro ao enviar')->setMensagem($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_cadastro.phtml'))->gravo_log_email_enviado();
			  exit();
			}else{
				$this->logs->setApp($this->dir_app)
   			    ->setEmail_re($configs_admin[0]['smtp_username'])->setEmail($useremail) //E-mail Remetene - E-mail destinatario
   			    ->setStatus('Enviado')->setMensagem($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/emails/email_cadastro.phtml'))->gravo_log_email_enviado();
			}

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

			//TRATO RESTRIÇÃO - Somente o id do usuario ativo sera mostrado
			$this->core->includeControllerView('core',$this->dir_app); //Incluo arquivo coreController.php
			$controller_geral_ = new coreController();
			(array) $restricoes = $controller_geral_->verifico_restricoes_sistema($this->adm_usuario_modulo_id);
			if(count($restricoes)>=1){
				for ($i=0; $i <count($restricoes) ; $i++) {
					if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 18 && $restricoes[$i]['opcoes'] == 0
					){
						$id	= $_SESSION[$this->dir_app.'_id_user'];
					}
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

			//NOME UNICO
			if($this->model->retorn_campo_editar_val_id('nome') != $nome){
				if($this->model->retorn_campo_editar('nome', $nome) == $nome){
					$nome_unique = true;
				}
				$this->funcoes->set('Nome',"nome", $nome)->is_required()->min_length(8)->max_length(250)->is_unique($nome_unique);
			}else{
				$this->funcoes->set('Nome',"nome", $nome)->is_required()->min_length(8)->max_length(250);
			}
			$this->funcoes->set('Sexo',"sexo", $sexo)->is_required()->min_length(2)->max_length(10);
			$this->funcoes->set('Data de Nascimento',"data_nascimento", $data_nascimento)->is_required()->is_date();
			$this->funcoes->set('Telefone',"telefone", $telefone)->is_telefone();
			$this->funcoes->set('Telefone 2',"telefone2", $telefone2)->is_telefone();
			$this->funcoes->set('E-mail',"email", $email)->is_email()->max_length(250);
			$this->funcoes->set('E-mail 2',"email2", $email2)->is_email()->max_length(250);
			if($_SESSION['adm_id_cargo'] == 1 || $_SESSION['adm_id_cargo'] == 2) {
				$this->funcoes->set('Cargos',"cargos", $cargos)->is_required()->is_num();
			}
			//CAMPO UNICO USERNAME
			$this->model->setCampos('tabela_retorn_campo_editar','adm_usuario_auth');
			$this->model->setCampos('tabela_retorn_campo_editar_val_id','adm_usuario_auth');
			$this->model->setCampos('campo_id_retorn_campo_editar_val_id','adm_usuario_id');
			if($this->model->retorn_campo_editar_val_id('username') != $username){
				if($this->model->retorn_campo_editar('username', $username) == $username){
					$username_unique = true;
				}
				$this->funcoes->set('Username',"username", $username)->is_required()->no_whitespaces()->min_length(5)->max_length(50)->is_unique($username_unique);
			}else{
				$this->funcoes->set('Username',"username", $username)->is_required()->no_whitespaces()->min_length(5)->max_length(50);
			}
			//CAMPO UNICO USERTELEFONE
			if($this->model->retorn_campo_editar_val_id('usertelefone') != $usertelefone){
				if($this->model->retorn_campo_editar('usertelefone', $usertelefone) == $usertelefone){
					$telefone_unique = true;
				}
				$this->funcoes->set('Telefone',"usertelefone", $usertelefone)->is_telefone()->max_length(20)->is_unique($telefone_unique);
			}else{
				$this->funcoes->set('Telefone',"usertelefone", $usertelefone)->is_telefone()->max_length(20);
			}
			//CAMPO UNICO USEREMAIL
			if($this->model->retorn_campo_editar_val_id('useremail') != $useremail){
				if($this->model->retorn_campo_editar('useremail', $useremail) == $useremail){
					$useremail_unique = true;
				}
				$this->funcoes->set('E-mail de acesso',"useremail", $useremail)->is_required()->is_email()->max_length(250)->is_unique($useremail_unique);
			}else{
				$this->funcoes->set('E-mail de acesso',"useremail", $useremail)->is_required()->is_email()->max_length(250);
			}
			//SENHA
			if(!empty($senha_sem_hash)){
				$this->funcoes->set('Senha',"senha", $senha_sem_hash)->is_required()->min_length(7)->max_length(20)->is_password_num_let();
				$this->funcoes->set('Confirme sua senha',"conf_senha", $conf_senha)->is_required()->is_compare_campo($senha_sem_hash,"Senha");
			}
			$this->funcoes->set('Logradouro',"logradouro", $logradouro)->min_length(2)->max_length(250);
			$this->funcoes->set('Número',"numero", $numero)->max_length(11)->is_num();
			$this->funcoes->set('Complemento',"complemento", $complemento)->min_length(2)->max_length(250);
			$this->funcoes->set('Bairro',"bairro", $bairro)->min_length(2)->max_length(250);
			$this->funcoes->set('CEP',"CEP", $CEP)->max_length(20)->is_CEP(false);
			$this->funcoes->set('Estado',"estado", $estado)->is_required()->max_length(11)->is_num();
			$this->funcoes->set('Cidade',"cidade", $cidade)->is_required()->max_length(11)->is_num();
			$this->funcoes->set('Assinatura de E-mail',"assinatura_email", $assinatura_email)->min_length(2)->max_length(6000000);

			//===========================================================
			//MOSTRO MENSAGEM DE ERROS NA TELA
			if(count($this->funcoes->get_errors())>=1){
				echo json_encode($this->funcoes->get_errors());
				exit;
			}

			//BARRO ADD CARGO DESENVOLVEDOR
			$cargo_ativo = $this->model->retorno_cargo_usuario($_SESSION[$this->dir_app.'_id_user']);
			$cargos_b = $this->model->retorno_cargo_usuario($id);
			if($cargo_ativo != 1) {
				if($cargo_ativo == 3) {
					if($cargos_b == 1 && $id != $_SESSION['adm_id_user']){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
						exit();
					}
					if($cargos_b == 2 && $id != $_SESSION['adm_id_user']){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
						exit();
					}
					if($cargos_b == 3 && $id != $_SESSION['adm_id_user']){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
						exit();
					}

					if($cargos == 1){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
						exit();
					}
					if($cargos == 2){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
						exit();
					}
					if($cargos == 3 && $id != $_SESSION['adm_id_user']){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
						exit();
					}
				}
				if($cargo_ativo == 2) {
					if($cargos_b == 1 && $id != $_SESSION['adm_id_user']){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
						exit();
					}
					if($cargos_b == 2 && $id != $_SESSION['adm_id_user']){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
						exit();
					}
					if($cargos == 1){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
						exit();
					}
					if($cargos == 2 && $id != $_SESSION['adm_id_user']){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
						exit();
					}
				}
			}

			//===========================================================
			//SE TIVER UPLOAD DE IMAGEM

			//SETO DADOS DE UPLOAD DE ARQUIVO
			$upload->setPasta('perfil_usuario')->setArquivo($imagem_perfil)->setNome_arquivo('')->setTipo_arquivo('jpg')
				   ->setTamanho(5)->setValido_dimensoes(false)->setRedimensiono(true)->setUpload_multiplo(false)
				   ->setWidth(0)->setHeight(0)
				   ->setWidth_p(240)->setHeight_p(110)->setRes_p(93)
				   ->setWidth_m(340)->setHeight_m(210)->setRes_m(92)
				   ->setWidth_g(600)->setHeight_g(400)->setRes_g(91)
				   ->upload_file();

			//RETORNO ARRAY COM ERROS
			$erro_upload = $upload->getMsg_erro();
			if (!empty($erro_upload)) {
				$this->funcoes->set_array(null,'erro','Imagem de Perfil <br />'.$this->funcoes->get_errors_inline($erro_upload)); // Mensagem de erro
				echo json_encode($this->funcoes->get_array());
				exit();
			}

			//RETORNO ARRAY COM NOMES DE ARQUIVOS UPADOS
			$imagem_perfil = $upload->getNome_arquivo_return();

			//EXCLUO IMAGEM ANTIGA
			if(!empty($imagem_perfil[0])){
				$this->model->setCampos('campo_tabela',"adm_usuario");
				$this->model->setCampos('campo_coluna',"id");
				$this->model->setCampos('campo_coluna2',"img_perfil");
				$this->model->setCampos('campo_where',"id = {$id}");
				$valor = $this->model->select_simples_retorna_array_mont_vcol();
				if(count($valor) >= 1){
					for ($i=0; $i < count($valor) ; $i++) {
						unlink($this->core->get_config('dir_files_comp')."/perfil_usuario/p/".$valor[$i]['img_perfil']);
						unlink($this->core->get_config('dir_files_comp')."/perfil_usuario/m/".$valor[$i]['img_perfil']);
						unlink($this->core->get_config('dir_files_comp')."/perfil_usuario/g/".$valor[$i]['img_perfil']);
					}
				}
				$this->model->getLimpoCampos();
			}

			//===========================================================
			//SETO OS DADOS ****-
			$this->model->setCampos('nome',$nome);
			$this->model->setCampos('sexo',$sexo);
			$this->model->setCampos('data_nascimento',$this->funcoes->conv_datahora($data_nascimento,"Y-m-d"));
			$this->model->setCampos('telefone',$telefone);
			$this->model->setCampos('telefone2',$telefone2);
			$this->model->setCampos('email',$email);
			$this->model->setCampos('email2',$email2);
			$this->model->setCampos('username',$username);
			$this->model->setCampos('useremail',$useremail);
			$this->model->setCampos('email_notificacoes',$useremail);
			$this->model->setCampos('usertelefone',$usertelefone);
			$this->model->setCampos('senha',$senha);
			$this->model->setCampos('senha_normal',$senha_sem_hash);
			$this->model->setCampos('logradouro',$logradouro);
			$this->model->setCampos('numero',$numero);
			$this->model->setCampos('complemento',$complemento);
			$this->model->setCampos('bairro',$bairro);
			$this->model->setCampos('CEP',$CEP);
			$this->model->setCampos('estado_id',$estado);
			$this->model->setCampos('cidade_id',$cidade);
			$this->model->setCampos('cargos',$cargos);
			$this->model->setCampos('assinatura_email',$assinatura_email);
			$this->model->setCampos('img_perfil',$imagem_perfil[0]);
			if($_SESSION[$this->dir_app.'_id_user'] == $id){
				setcookie("ft_tmp".$this->dir_app, "", time()-60000, "/");
				$_SESSION[$this->dir_app.'_foto_perfil'] = $imagem_perfil[0];
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
		$mpdf = new mPDF(null,'A4-L');

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

			//MONTO RESTRIÇÕES
			$restricoess = $this->model->detalhamento_restricoes();
			for ($i=0; $i <count($restricoess) ; $i++) {
				$monto .= '<div class="row" style="margin-bottom:19px;"><div class="col-md-12 col-pdf-12"><h4 class="text-uppercase">Módulo '.$restricoess[$i]['modulo'].' <span style="font-size:14px;color:#737373;text-transform:lowercase">('.$restricoess[$i]['descricao'].')</span></h4>';
				$acoes = $this->model->detalhamento_restricoes2($restricoess[$i]['adm_usuario_modulo_id']);
				for ($i0=0; $i0 <count($acoes); $i0++) {
					$monto .= '<div class="col-md-3 col-sm-4 col-pdf-4">'.$acoes[$i0]['acoes'].'</div>';
				}
				$monto .= '</div></div>';
			}

			//SETO PARAMETROS GERAIS
            $interface['titulo']            = $this->nome_pagina_singular; // ****-
            $interface['icon_titulo']       = $this->btns_acoes['class_icone_detalhamento'];
            $interface['action_exp_pdf']    = $this->cmds['action_detalhamento2'];
            $interface['url_pagina']        = $this->url_pagina;
            $interface['id']                = $id;
            $interface['array']             = $exec; //ARRAY
			$interface['restricoes']        = $monto; //RESTRICOES
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
				$css = file_get_contents('');
				$css .= file_get_contents('../view/assets/css/theme.css');
				$css .= file_get_contents('../view/assets/css/pdf.css');
				$css .= file_get_contents('../view/assets/css/font-awesome.css');
				$mpdf->WriteHTML($css,1);
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

		//INSTANCIO
		$usuario_sessao = new usuario_sessao();

		//PARAMETROS DA CONSULTA
		$this->model->setCampos('tabela','adm_usuario_auth'); //tabela
		$this->model->setCampos('campo_id','adm_usuario_id'); //campo id

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

		//VERIFICO E DESTRUO SESSAO
		if(is_array($id)){ // verifico se $id é array
	        for ($i=0; $i < $total_ids; $i++) {
				if($status_id == 1){
					$usuario_sessao->controlo_tempo_sessao('adm','',$id[$i],true); //Controlo tempo de sessão
				}
	        }
	    }

		//VERIFICO SE POSSO ATIVAR OU DESATIVART
		if(is_array($id)){ // verifico se $id é array
			for ($i=0; $i < count($id); $i++) {
				if($id[$i] == $_SESSION['adm_id_user']){
					$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar o seu próprio status!'); // Mensagem de erro
					echo json_encode($this->funcoes->get_array());
					exit();
				}
				$cargo_ativo = $this->model->retorno_cargo_usuario($_SESSION[$this->dir_app.'_id_user']);
				$cargos = $this->model->retorno_cargo_usuario($id[$i]);
				if($cargo_ativo != 1) {
					if($cargos == 1){
						$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar o status deste usuário!'); // Mensagem de erro
						echo json_encode($this->funcoes->get_array());
						$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
						->setMensagem('Acesso restrito ao cargo desenvolvedor')->gravo_log(); // Gravo log
						exit();
					}
					if($cargo_ativo == 2) {
						if($cargos == 2){
							$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar o status deste usuário!'); // Mensagem de erro
							echo json_encode($this->funcoes->get_array());
							$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
							->setMensagem('Acesso restrito ao cargo administrador')->gravo_log(); // Gravo log
							exit();
						}
					}
					if($cargo_ativo == 3) {
						if($cargos == 2){
							$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar o status deste usuário!'); // Mensagem de erro
							echo json_encode($this->funcoes->get_array());
							$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
							->setMensagem('Acesso restrito ao cargo administrador')->gravo_log(); // Gravo log
							exit();
						}
						if($cargos == 3){
							$this->funcoes->set_array(null,'erro','Você não tem permissão para alterar o status deste usuário!'); // Mensagem de erro
							echo json_encode($this->funcoes->get_array());
							$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
							->setMensagem('Acesso restrito ao cargo administrador')->gravo_log(); // Gravo log
							exit();
						}
					}
				}
			}
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
		$this->model->setCampos('tabela','adm_usuario'); //tabela
		$this->model->setCampos('campo_id','id'); //campo id

		//===========================================================
		//INCLUDE - Recebo e trato os dados
		require $this->core->includeControllerInclude("excluir_1", $this->dir_app);

		//===========================================================
		//EXECUTO O MODEL
		if(is_array($id)){ // verifico se $id é array
			for ($i=0; $i < $total_ids; $i++) {

				//VERIFICO SE PODE EXCLUIR
				if($id[$i] == $_SESSION['adm_id_user']){
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
		$id   = $this->funcoes->anti_injection($_GET['id']); // tipo

		//===========================================================
		//LISTAGEM DE TESTE
		if($tipo == 'img_perfil'){
			$this->model->setCampos('campo_tabela',"adm_usuario");
			$this->model->setCampos('campo_coluna',"id");
			$this->model->setCampos('campo_coluna2',"img_perfil");
			$this->model->setCampos('campo_coluna3',"criado");
			$this->model->setCampos('campo_where',"id = {$id}");
			$this->model->setCampos('campo_orderby',"");
			$valor = $this->model->select_simples_retorna_array_mont_vcol();
			if(count($valor) >= 1){
				for ($i=0; $i < count($valor) ; $i++) {
					$arquivo = $valor[$i]['img_perfil'];
					$array[] = array(
                        'id_arquivo'   => $valor[$i]['id'],
                        'path_arquivo' => $this->core->get_config('dir_raiz_http').'files/perfil_usuario/p/',
                        'url_arquivo'  => $valor[$i]['img_perfil'],
                        'descricao'    => $valor[$i]['img_perfil'],
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

        //BARRO EXCLUSAO CARGO DESENVOLVEDOR
        $cargo_ativo = $this->model->retorno_cargo_usuario($_SESSION[$this->dir_app.'_id_user']);
        $cargos_b = $this->model->retorno_cargo_usuario($id);
        if($cargo_ativo != 1) {
            if($cargo_ativo == 3) {
                if($cargos_b == 1 && $id != $_SESSION['adm_id_user']){
                    $this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
                    echo json_encode($this->funcoes->get_array());
                    $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                    ->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
                    exit();
                }
                if($cargos_b == 2 && $id != $_SESSION['adm_id_user']){
                    $this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
                    echo json_encode($this->funcoes->get_array());
                    $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                    ->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
                    exit();
                }
                if($cargos_b == 3 && $id != $_SESSION['adm_id_user']){
                    $this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
                    echo json_encode($this->funcoes->get_array());
                    $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                    ->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
                    exit();
                }
            }
            if($cargo_ativo == 2) {
                if($cargos_b == 1 && $id != $_SESSION['adm_id_user']){
                    $this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
                    echo json_encode($this->funcoes->get_array());
                    $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                    ->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
                    exit();
                }
                if($cargos_b == 2 && $id != $_SESSION['adm_id_user']){
                    $this->funcoes->set_array(null,'erro','Você não tem permissão para alterar este usuário!'); // Mensagem de erro
                    echo json_encode($this->funcoes->get_array());
                    $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                    ->setMensagem('Acesso restrito ao cargo')->gravo_log(); // Gravo log
                    exit();
                }
            }
        }


		//===========================================================
		//TIPO ....
		if($tipo == 'img_perfil'){
			//PARAMETROS ****-
			$tabela      = 'adm_usuario'; //tabela do bd
			$nome_pagina = 'Imagem de Perfil'; //nome da página

			//SE FOR UPLOAD ÚNICO - Atualizo o campo da tabela para vazio
			$this->model->setCampos('campo_tabela',$tabela);
			$this->model->setCampos('campo_coluna',"img_perfil"); // ****-
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
			unlink($this->core->get_config('dir_files_comp')."/perfil_usuario/p/".$url_arquivo);
			unlink($this->core->get_config('dir_files_comp')."/perfil_usuario/m/".$url_arquivo);
			unlink($this->core->get_config('dir_files_comp')."/perfil_usuario/g/".$url_arquivo);

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
