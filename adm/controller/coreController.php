<?php

    /**
    * Controller
    *
    * @author Fernando
    * @version 2.0.0
    * */

    //=================================================================
    //INCLUDES
    require_once (dirname(dirname((dirname(__FILE__))))) . "/libs/core/core.php";
    //=================================================================

    //INICIO A CLASSE
    if (!empty($_GET['cmd_core']) || !empty($_POST['cmd_core'])) {
        $control = new coreController();
    }

    //CLASS
    class coreController{

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
            $this->core->includeController('core', $this->dir_app); //incluo model

            //INSTANCIO
            $this->funcoes           = new funcoes();     //Instancio Funções
            $this->model             = new coreModel();  //Instancio Model ****-
            $this->logs              = new logs();        //Instancio Logs
            $this->email             = new email();       //Instancio E-mails
            $this->config_apps       = new config_apps_adm(); //Instancio configurações da aplicação
            $this->config_controller = new config_controller_adm(); //Instancio Funções

            //FUNÇÕES PERMITIDAS
            $funcoes_permitidas = $this->config_apps->getCmds_controller('core'); //Funções permitidas nesta página

            //GRAVO USUARIO ONLINE ****
            $usuario_sessao    = new usuario_sessao();
            $usuario_sessao->registra_visita($this->dir_app); //Registro visita

            //------------------------------------------------------------
            //PEGO E TRATO O PARAMETRO RECEBIDO
            if (!empty($_GET['cmd_core']) || !empty($_POST['cmd_core'])) {

                //TRATO O PARAMETRO RECEBIDO
                if (!empty($_GET['cmd_core'])) {
                    $cmd = $this->funcoes->anti_injection($_GET['cmd_core']);
                }
                if (!empty($_POST['cmd_core'])) {
                    $cmd = $this->funcoes->anti_injection($_POST['cmd_core']);
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
//FUNÇÃO QUE EXECUTA FUNÇÕES NO AGENDADOR (CRON)
//***************************************************************************************************************************************************************
        function core_agendador() {

            //RECEBO PARAMETRO GET DA FUNÇÃO
            $tipo = $this->funcoes->anti_injection($_GET['t']);

            // .. código aqui ..
        }





//***************************************************************************************************************************************************************
//FUNÇÃO QUE EXECUTA FUNÇÕES GERAIS A CADA CARREGAMENTO DE PÁGINA DO SISTEMA
//***************************************************************************************************************************************************************
        function core_action_geral() {
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            // .. código aqui ..
        }





//***************************************************************************************************************************************************************
//FUNÇÃO QUE EXECUTA FUNÇÕES NO LOGIN DO SISTEMA
//***************************************************************************************************************************************************************
        function core_action_login() {
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            // Verifico se os dados de configuração foram preenchidos
            $this->verifico_configs_admin();

            // Aniversariantes do dia
            $this->aniversariantes_dia();

            // .. código aqui ..
        }





//***************************************************************************************************************************************************************
//FUNÇÃO QUE VERIFICA SE AS CONFIURAÇÕES FORAM CRIADAS E CRIA UMA NOTIFICAÇÃO DE LEMBRETE
//***************************************************************************************************************************************************************
        function verifico_configs_admin(){
            $configs = $this->model->retorno_configs_admin();
            if(empty($configs[0]['smtp_host']) || empty($configs[0]['smtp_username']) || empty($configs[0]['smtp_senha']) || empty($configs[0]['smtp_porta']) ){
                $this->insiro_notificacao('fa fa-envelope','Insira os dados de SMTP do sistema! Esses dados são essências para o funcionamento do sistema.','adm_configuracoes/editar/1',$_SESSION[$this->dir_app.'_id_user'],'Inserir Dados de SMTP');
            }
            if(empty($configs[0]['img_fundo_login'])){
                $this->insiro_notificacao('fa fa-picture-o','Insira a Imagem padrão do sistema! Essa imagem será exibida na página de login, locksreen e página de erro do sistema.','adm_configuracoes/editar/1',$_SESSION[$this->dir_app.'_id_user'],'Inserir Imagem');
            }
            if(empty($configs[0]['modo_sistema'])){
                $this->insiro_notificacao('fa fa-cog','Insira o Modo do Sistema!','adm_configuracoes/editar/1',$_SESSION[$this->dir_app.'_id_user'],'Inserir Dados');
            }
        }





//***************************************************************************************************************************************************************
//FUNÇÃO QUE CRIO UMA NOTIFICAÇÃO DE PARABÉNS
//***************************************************************************************************************************************************************
        function aniversariantes_dia(){
            $aniversariantes = $this->model->retorno_aniversariantes_dia();
            for ($i=0; $i < count($aniversariantes) ; $i++) {
                if(!empty($aniversariantes[$i]['id'])){
                     $this->insiro_notificacao('fa fa-birthday-cake','Parabéns '.$aniversariantes[$i]['nome'].'! <br />Hoje '.$this->funcoes->retorno_data_por_extenso($this->funcoes->conv_datahora($aniversariantes[$i]['data_nascimento'],'d-m-Y')).' é o dia do seu Aniversário. ',null,$aniversariantes[$i]['id'],null);
                }
            }
        }





//***************************************************************************************************************************************************************
//RETORNO DADOS COM TOTAL DE REGISTROS DE TABELAS PARA O MENU
//***************************************************************************************************************************************************************
        function retorno_array_total_reg_menu(){
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            //===========================================================
            //INCLUDE COM ARRAY
            $array = require $this->core->includeControllerInclude("total_registros_menu", $this->dir_app);
            return $array;
        }





//***************************************************************************************************************************************************************
//RETORNO TELEFONES UTILIZADOS NO SMS
//***************************************************************************************************************************************************************
        function retorno_telefones_utilizados(){
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            return $this->model->retorno_telefones_utilizados();
        }





//***************************************************************************************************************************************************************
//RETORNO EMAILS UTILIZADOS NO EMAILS
//***************************************************************************************************************************************************************
        function retorno_emails_utilizados(){
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            return $this->model->retorno_emails_utilizados();
        }





//***************************************************************************************************************************************************************
//RETORNO DADOS DO USUARIO (TABELA USUARIOS)
//***************************************************************************************************************************************************************
        function retorno_dados_usuario_usuario($id = null) {
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            if (!empty($id)) {
                $this->model->setId($id);
            } else {
                $this->model->setId($_SESSION[$this->dir_app.'_id_user']);
            }
            return $this->model->retorno_dados_usuario_usuario();
        }





//***************************************************************************************************************************************************************
//VERIFICO RESTRIÇÕES DO SISTEMA
//***************************************************************************************************************************************************************
        function verifico_restricoes_sistema($adm_usuario_modulo_id = null) {
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            //SETO DADOS
            $this->model->setId($_SESSION[$this->dir_app.'_id_user']);

            //EXECUTO E RETORNO
            if(!empty($_SESSION[$this->dir_app.'_id_user'])){
                if(empty($adm_usuario_modulo_id)){
                    return $this->model->restricoes_sistema();
                }else{
                    return $this->model->restricoes_sistema($adm_usuario_modulo_id);
                }
            }
        }





//***************************************************************************************************************************************************************
//VERIFICO NOTIFICAÇÕES DO SISTEMA
//***************************************************************************************************************************************************************
        function verifico_notificacoes() {
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            //INSTANCIO A VIEW E O HELPER DE EXPORTAR
    		$this->core->includeView();
    	    $view = new view($this->dir_app);

            //SETO DADOS
            $this->model->setId($_SESSION[$this->dir_app.'_id_user']);

            //EXECUTO E RETORNO
            $array  = $this->model->verifico_notificacoes(); //todas as notificações
            $array1 = $this->model->verifico_notificacoes('status'); //notificações não lidas

            //SETO OS DADOS
            $interface['total_registros'] = count($array1); //total de notificacoes não lidas
            $interface['array']           = $array;
            $interface['path']            = $this->core->get_config('path_template_comp_' . $this->dir_app.'_apps'); //Diretório raiz até assets
            $interface['path_raiz']       = $this->core->get_config('dir_raiz_http').$this->dir_app."/";
			$view->seto_dados_array($interface);

            //MOSTRO NA TELA
            echo json_encode($view->retorno_template_php('modulos/geral/includes/notificacoes.phtml'));
        }





//***************************************************************************************************************************************************************
//INSIRO NOTIFICAÇÕES DO SISTEMA
//***************************************************************************************************************************************************************
        function insiro_notificacao($class_icone,$mensagem,$url_destino = null,$usuario_id,$texto_botao_acao = null) {

            //SETO OS DADOS
            $this->model->setCampos('adm_usuario_id',$usuario_id);
            $this->model->setCampos('status_id',2);
            $this->model->setCampos('mensagem',$this->funcoes->anti_injection($mensagem,'html'));
            $this->model->setCampos('url_destino',$url_destino);
            $this->model->setCampos('class_icon',$class_icone);
            $this->model->setCampos('texto_botao_acao',$texto_botao_acao);

            //EXECUTO E GRAVO LOG
            $exec = $this->model->insiro_notificacao();
            $ult_id = $this->model->getUltimo_id();
            $this->model->getLimpoCampos();
            if($exec == true){
                //GRAVO LOG
                $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                ->setMensagem($this->core->get_msg_array('sucesso_inserir_log_adm','Notificações,'.$ult_id))->gravo_log();
            }else{
                //GRAVO LOG
               // $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
               // ->setMensagem($this->core->get_msg_array('erro_inserir','Notificações'))->gravo_log(); //Gravo log
            }
        }





//***************************************************************************************************************************************************************
//ALTERO NOTIFICACAO DO SISTEMA
//***************************************************************************************************************************************************************
        function altero_notificacao() {
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            //RECEBO OS DADOS
            (int) $id = $this->funcoes->anti_injection($_GET['id']);

            //SETO OS DADOS
            $this->model->setId($id);
            $this->model->setCampos('status_id',1);
            $this->model->setCampos('status_exibicao',1);

            //EXECUTO E GRAVO LOG
            $exec = $this->model->altero_notificacao();
            $this->model->getLimpoCampos();
            if($exec == true){
                echo json_encode('ok');
                //GRAVO LOG
                $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                ->setMensagem($this->core->get_msg_array('sucesso_editar','Notificação ID:'.$id.' '))->gravo_log();
                exit();
            }else{
                //GRAVO LOG
                $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                ->setMensagem($this->core->get_msg_array('erro_editar_log','Notificacao,'.$id))->gravo_log(); //Gravo log
                exit();
             }
        }





//***************************************************************************************************************************************************************
//VERIFICO NOTIFICAÇÕES DO SISTEMA POPUP
//***************************************************************************************************************************************************************
        function verifico_notificacoes_popup() {
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            //SETO DADOS
            $this->model->setId($_SESSION[$this->dir_app.'_id_user']);
            $this->model->setCampos('status_exibicao',1);

            //EXECUTO E RETORNO
            echo json_encode($this->model->verifico_notificacoes_popup()); //todas as notificações
        }





//***************************************************************************************************************************************************************
//PESQUISA GERAL
//***************************************************************************************************************************************************************

        //===========================================================
        //FUNÇÃO QUE REDIRECIONA A PESQUISA PARA A PÁGINA
        function pesq_geral(){
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            //===========================================================
            //RECEBO A PESQUISA
            $query_pesquisa = $this->funcoes->anti_injection($_GET['param']);

            //===========================================================
            //TRATO E MONTO A PESQUISA
            $string = explode(':',$query_pesquisa); //separo string
            $pagina = $this->funcoes->conv_string($string[0], 0); //deixo tudo em minusculo
            $pagina = $this->funcoes->substituo_strings(" ","_",$pagina); //troco o espaco por _
            $redireciono = $this->core->get_config('dir_raiz_http').$this->dir_app."/".$pagina."/busca/".ltrim($string[1]);

            //===========================================================
            //REDIRECIONO PÁGINA
            if(empty($string[1]) || empty($query_pesquisa)){
                header('location: '.$this->core->get_config('dir_raiz_http').$this->dir_app.'');
                exit;
            }else{
                header('location: '.$redireciono.'');
                exit;
            }
        }

        //===========================================================
        //AUTOCOMPLETE PESQUISA GERAL GERAL
        function autocomplete_pesq_geral(){
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            //===========================================================
            //RECEBO OS DADOS
            (string) $valor = $this->funcoes->anti_injection($_GET['term']); // valor pesquisado

            //===========================================================
            //INCLUDE PESQUISA GERAL
            require $this->core->includeControllerInclude("auto_complete_geral", $this->dir_app);
        }





//***************************************************************************************************************************************************************
//RETORNO GRAFICO
//***************************************************************************************************************************************************************
        function list_grafico() {
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            //RECEBO TIPO GRAFICO
            $tipo = $this->funcoes->anti_injection($_GET['tipo']); // Pego a página ativa [ inteiro ]

            //============================================================
            //GRAFICO TOTAL EMAILS ADMIN
            if($tipo == 'total_emails_admin'){
                $this->core->includeController('adm_logs', $this->dir_app); //incluo model
                $model = new adm_logsModel();
            }

            //============================================================
            //GRAFICO TOTAL NAVEGADORES LOGS ADMIN
            if($tipo == 'total_navegadores_logs_admin'){
                $this->core->includeControllerView('adm_logs',$this->dir_app); //Incluo arquivo coreController.php
                $controller = new adm_logsController();
                $controller->list_grafico($tipo);
            }

            //============================================================
            //GRAFICO SO LOGS ADMIN
            if($tipo == 'total_so_logs_admin'){
                $this->core->includeControllerView('adm_logs',$this->dir_app); //Incluo arquivo coreController.php
                $controller = new adm_logsController();
                $controller->list_grafico($tipo);
            }

            //============================================================
            //GRAFICO PAGINAS LOGS ADMIN
            if($tipo == 'total_paginas_logs_admin'){
                $this->core->includeControllerView('adm_logs',$this->dir_app); //Incluo arquivo coreController.php
                $controller = new adm_logsController();
                $controller->list_grafico($tipo);
            }

            //============================================================
            //GRAFICO TOTAL ACESSOS ADMIN
            if($tipo == 'total_logs_acesso_usuarios'){
                $this->core->includeControllerView('adm_logs_acesso',$this->dir_app); //Incluo arquivo coreController.php
                $controller = new adm_logs_acessoController();
                $controller->list_grafico($tipo);
            }

            //============================================================
            //GRAFICO LOGS ACESSO LOGOFF E LOGIN ADMIN
            if($tipo == 'total_logs_logoff_login'){
                $this->core->includeControllerView('adm_logs_acesso',$this->dir_app); //Incluo arquivo coreController.php
                $controller = new adm_logs_acessoController();
                $controller->list_grafico($tipo);
            }

        }





//***************************************************************************************************************************************************************
//RETORNO TOTAL REGISTROS ADMIN
//***************************************************************************************************************************************************************
        function total_registros_adm() {
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            //MONTO ARRAY COM VALORES
            $this->funcoes->set_array(0,'total_sms_enviados_dia_admin',$this->model->retorno_total_sms_adm('dia'));
            $this->funcoes->set_array(0,'total_sms_enviados_semana_admin',$this->model->retorno_total_sms_adm('semana'));
            $this->funcoes->set_array(0,'total_sms_enviados_mes_admin',$this->model->retorno_total_sms_adm('mes'));
            $this->funcoes->set_array(0,'total_email_enviados_dia_admin',$this->model->retorno_total_email_adm('dia'));
            $this->funcoes->set_array(0,'total_email_enviados_semana_admin',$this->model->retorno_total_email_adm('semana'));
            $this->funcoes->set_array(0,'total_email_enviados_mes_admin',$this->model->retorno_total_email_adm('mes'));
            $this->funcoes->set_array(0,'total_usuarios_online_admin',$this->model->retorno_total_usuarios_online_adm());
            $this->funcoes->set_array(0,'total_notificacoes_admin',$this->model->retorno_total_notificacoes_adm());
            $array = $this->funcoes->get_array(); // Seto o array na view [Array]
            return $array;

        }





//***************************************************************************************************************************************************************
//RETORNO LISTAGEM ADMIN
//***************************************************************************************************************************************************************
        function retorno_listagens_adm($param) {
            $this->autentico_usuario(); //Autentico usuário no sistema
            //**********************************************************

            //LISTAGEM DE USUARIOS ONLINE ADMIN
            if($param == 'retorno_listagem_usuarios_online_adm'){
                $array = $this->model->retorno_listagem_usuarios_online_adm();
            }

            //LISTAGEM DE NOTIFICAÇÕES ADMIN
            if($param == 'retorno_listagem_notificacoes_adm'){
                $array = $this->model->retorno_listagem_notificacoes_adm();
            }
            return $array;
        }





//***************************************************************************************************************************************************************
//RETORNO CONFIGURAÇÕES DO SISTEMA
//***************************************************************************************************************************************************************
		function retorno_configs_admin(){
			return $this->model->retorno_configs_admin();
		}
        function retorno_configs_admin2(){
            return $this->model->retorno_configs_admin2();
        }





    }
