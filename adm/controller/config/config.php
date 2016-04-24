<?php

/**
 * Configurações Gerais da Aplicação
 *
 * @author Fernando
 * @version 2.0.0
 * */

//Classe
class config_apps_adm {

    //DADOS GERAIS
    protected $_dados = array();
    public function set_array($array) {
        if (is_array($array)) {
            $this->_dados = array_merge($array, $this->_dados);
        }
    }
    public function get_config($param,$param2 = null) {
        if(isset($param2)){
          return $this->_dados[$param][$param2];
        }else{
          return $this->_dados[$param];
        }
    }

    //PÁGINAS DO SITE
    public $paginas;
    public function getPaginas($param = null){
      if(!empty($param) || $param == 0){
          return $this->paginas[$param];
      }
    }
    public function getPaginas_array(){
        return $this->paginas;
    }
    public function setPaginas($paginas){
        $this->paginas = $paginas;
        return $this;
    }

    //CONTROLLER MONTADO
    public $controller_montado;
    public function getController_montado($param = null){
      return $this->controller_montado[$param];
    }
    public function getController_montado_array(){
        return $this->controller_montado;
    }
    public function setController_montado($controller_montado){
        $this->controller_montado = $controller_montado;
        return $this;
    }

    //CMDS DO CONTROLLER
    protected $cmds_controller = array();
    public function getCmds_controller($param,$param2 = null){
      if(isset($param2)){
        return $this->cmds_controller[$param][$param2];
      }else{
        return $this->cmds_controller[$param];
      }
    }
    public function getCmds_controller_array(){
        return $this->cmds_controller;
    }
    public function setCmds_controller($array){
        if (is_array($array)) {
            $this->cmds_controller = array_merge($array, $this->cmds_controller);
        }
    }

    //Construct
    function __construct() {

        //INSTANCIO CORE
        $core    = new core();
        $funcoes = new funcoes();

        //VERIFICO STATUS DE URL AMIGAVEL
        $link_page = null;
        if ($core->get_config('status_url_amigavel') == false) {
            $link_page = $core->get_config('nome_pag_controller_view') . $core->get_config('par_url_htacess');
        }

        //=================================================================
        //DEFINO AS PÁGINAS DO APLICAÇÃO
        //=================================================================

        $paginas = array(
            "index", //0
            "erro", //1
            "lockscreen", //2
            "home", //3
            "cadastro", //4
  			"modulos",//5
  			"modulos_acoes",//6
  			"adm_logs",//7
  			"adm_logs_acesso",//8
  			"adm_usuario_online",//9
  			"status",//10
  			"notificacoes",//11
  			"adm_usuario_nivel",//12
            "modulos_acoes_default",//13
            "adm_restricoes",//14
            "usuarios",//15
            "backup", //16
            "cache", //17
            "adm_configuracoes", //18
            "adm_feedback", //19
            "adm_emails_enviados",//20
            "adm_sms_enviados",//21

        );
        $this->setPaginas($paginas);


        //=================================================================
        //DEFINO OS MÓDULOS DA APLICAÇÃO
        //=================================================================

        //MÓDULOS
        $modulos['modulos'] = array(
            "geral", //0

        );
        $this->set_array($modulos);


        //=================================================================
        //DEFINO OS CMDS DO CONTROLLER DA APLICAÇÃO
        //=================================================================

        //CORE
        $cmds['core'] = array(
            $funcoes->mycrypt('cmds_controller'), //0
            $funcoes->mycrypt('add_edd'), //1
            $funcoes->mycrypt('cmd_set_add_edd'), //2
            $funcoes->mycrypt('excluir'), //3
            $funcoes->mycrypt('ativar_desativar'), //4
            $funcoes->mycrypt('detalho_conteudo'), //5
            $funcoes->mycrypt('listagem'), //6
            $funcoes->mycrypt('exp_imprimir_reg'), //7
            $funcoes->mycrypt('excluo_cookies_sessao'), //8
            $funcoes->mycrypt('cmd_excluir_tudo'), //9
            $funcoes->mycrypt('autocomplete'), //10
            $funcoes->mycrypt('combobox'), //11
            $funcoes->mycrypt('changcombobox'), //12
            $funcoes->mycrypt('monto_campos_form_js'), //13
            $funcoes->mycrypt('monto_outros_javascript'), //14
            $funcoes->mycrypt('cmd_funcao_aberta'), //15
            $funcoes->mycrypt('listagem_arquivos'), //16
            $funcoes->mycrypt('excluir_arquivo'), //17
            $funcoes->mycrypt('login_sistema'), //18
            $funcoes->mycrypt('logoff'), //19
            $funcoes->mycrypt('esqueci_senha'), //20
            $funcoes->mycrypt('verifico_notificacoes'), //21
            $funcoes->mycrypt('altero_notificacao'), //22
            $funcoes->mycrypt('verifico_notificacoes_popup'), //23
            $funcoes->mycrypt('cad_valido_token'), //24
            $funcoes->mycrypt('cad_altero_senha'), //25
            $funcoes->mycrypt('cad_reenviar_token'), //26
            $funcoes->mycrypt('force_cad_senha'), //27
            $funcoes->mycrypt('list_grafico'), //28
            $funcoes->mycrypt('pesq_geral'), //29
            $funcoes->mycrypt('autocomplete_pesq_geral'), //30
            $funcoes->mycrypt('core_agendador'), //31
            $funcoes->mycrypt('core_action_geral'), //32
            $funcoes->mycrypt('core_action_login') //33

        );
        $this->setCmds_controller($cmds);

        //OUTROS
        /*$cmds2['core2'] = array(
            $funcoes->mycrypt("outros"), //0
        );
        $this->setCmds_controller($cmds2);*/


        //=================================================================
        //CONFIGURAÇÕES GERAIS
        //=================================================================

        //PEGO O MODO DO SISTEMA
        if($_SESSION['adm_modo_sistema'] == "Modo de Produção"){
            $pasta_min = 'min';
            $ext_min   = '.min';
        }else{
            $pasta_min = '';
            $ext_min   = '';
        }

        $this->set_array(array(

            //CONFIGURAÇÕES GERAIS
            'paginas_permitidas'              => $paginas, //Páginas permitidas
            'link_page'                       => $link_page, //Config url amigavel
            'description_geral'               => '', //Tag html description
            'keyword_geral'                   => '', //Tag html keyword
            'tempo_bloq_requisicoes'          => 80, //tempo em segundos de bloqueo entre requisições
            'num_tentativas_bloq_requisicoes' => 3, //número para o bloqueo das requisições
            'site_key_captcha_google'         => '6LeE4hgTAAAAAAbcgtKQy5bNlDMfrSYsCvqIhwB7', //Site key captcha google
            'secret_key_captcha_google'       => '6LeE4hgTAAAAAJypdmiDMiJlCGzIPYqeDXXICq_6', //Secret key captcha google

            //CSS DA APLICAÇÃO
            'bootstrap'         => '/assets/js/libs/inc/bootstrap/css/bootstrap.min',
            'icon'              => '/assets/css/min/font-awesome.min',
            'datetimepicker'    => '/assets/js/libs/inc/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'animate'           => '/assets/css/min/animate.min',
            'sweetalert'        => '/assets/js/libs/inc/plugins/bootstrap-sweetalert/lib/sweet-alert.min',
            'select2'           => '/assets/js/libs/inc/plugins/select2/select2.min',
            'fakeLoader'        => '/assets/js/libs/inc/plugins/fakeLoader/fakeLoader.min',
            'bsmSelect-master'  => '/assets/js/libs/inc/plugins/bsmSelect-master/css/jquery.bsmselect.min',
            'lightbox'          => '/assets/js/libs/inc/plugins/lightbox/dist/css/lightbox.min',
            'chosen'            => '/assets/js/libs/inc/plugins/chosen/chosen.min',
            'admin'             => '/assets/css/theme',

            //CSS DOS TEMAS DA APLICAÇÃO
            'skin-black'        => '/assets/css/skins/skin-black',
            'skin-blue'         => '/assets/css/skins/skin-blue',
            'skin-green'        => '/assets/css/skins/skin-green',
            'skin-purple'       => '/assets/css/skins/skin-purple',
            'skin-red'          => '/assets/css/skins/skin-red',
            'skin-yellow'       => '/assets/css/skins/skin-yellow',
            'skin-aqua'         => '/assets/css/skins/skin-aqua',
            'skin-blue-dark'    => '/assets/css/skins/skin-blue-dark',
            'skin-pink'         => '/assets/css/skins/skin-pink',


        ));

        //=================================================================
        //PÁGINAS PADRÕES
        //=================================================================

        if ($core->get_config('status_url_amigavel') == true) { //URL AMIGAVEL
            $this->set_array(array(
                'url_erro1' => $link_page . $this->getPaginas(1).'/pagina-nao-encontrada', //Página não encontrada
                'url_erro2' => $link_page . $this->getPaginas(1).'/javascript-desabilitado', //Javascript
                'url_erro3' => $link_page . $this->getPaginas(1).'/acesso-negado', //Acesso negado
                'url_index' => $link_page . $this->getPaginas(0).'', //Página inicial
            ));
        }

    }
}
