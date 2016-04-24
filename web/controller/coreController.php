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
if (!empty($_GET['cmd']) || !empty($_POST['cmd'])) {
    $control = new coreController();
}

//CLASS
class coreController {

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
        $this->core->includeController('core', $this->dir_app); //incluo model

        //INSTANCIO
        $this->funcoes           = new funcoes();     //Instancio Funções
        $this->model             = new coreModel();  //Instancio Model
        $this->logs              = new logs();        //Instancio Logs
        $this->email             = new email();       //Instancio E-mails
        $this->config_apps       = new config_apps(); //Instancio configurações da aplicação
        $this->config_controller = new config_controller();     //Instancio Funções

        //GRAVO USUARIO ONLINE ****
        $usuario_sessao = new usuario_sessao();
        $usuario_sessao->registra_visita($this->dir_app); //Registro visita

        //FUNÇÕES PERMITIDAS
        $funcoes_permitidas = $this->config_apps->getCmds_controller('core'); //Funções permitidas nesta página

        //------------------------------------------------------------
        //PEGO E TRATO O PARAMETRO RECEBIDO
        if (!empty($_GET['cmd']) || !empty($_POST['cmd'])) {

            //TRATO O PARAMETRO RECEBIDO
            if (!empty($_GET['cmd'])) {
                $cmd = $this->funcoes->anti_injection($_GET['cmd']);
            }
            if (!empty($_POST['cmd'])) {
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

    //=================================================================
    //AUTENTICO USUARIO NO SISTEMA
    function autentico_usuario() {
        $param = $this->funcoes->auth_usuario($this->dir_app, true, '../../'.$this->config_apps->get_config('link_page') . $this->config_apps->getPaginas(0)); //Se não tiver logado redireciono para index
    }

    //=================================================================
    //PEGO OS CMDS DO CONTROLLER DA APLICAÇÃO
    function cmds_controller(){

        //-------------------------------------------------------
        //MOSTRO INFORMAÇÕES NA TELA (JSON)
        echo json_encode($this->config_apps->getCmds_controller_array());
    }



}
