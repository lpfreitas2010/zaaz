<?php

    /**
    * Configurações Gerais da Aplicação
    *
    * @author Fernando
    * @version 2.0.0
    * */

    //Classe
    class config_apps {

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
                //  $this->cmds_controller = array_unique($this->cmds_controller);
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
                $funcoes->mycrypt('cmd_aqui'), //0

            );
            $this->setCmds_controller($cmds);


            //=================================================================
            //CONFIGURAÇÕES GERAIS
            //=================================================================

            $this->set_array(array(
                'paginas_permitidas'              => $paginas, //Páginas permitidas
                'link_page'                       => $link_page, //Config url amigavel
                'description_geral'               => '',
                'keyword_geral'                   => '',
                'tempo_bloq_requisicoes'          => 80, //tempo em segundos de bloqueo entre requisições
                'num_tentativas_bloq_requisicoes' => 5, //número para o bloqueo das requisições

                //CSS DA APLICAÇÃO
                'app1'                                 => '/assets/css/app.min.1',
                'app2'                                 => '/assets/css/app.min.2',
                'custom'                               => '/assets/css/custom',
                'icon'                                 => '/assets/css/font-awesome',
                'material-design-color-palette-master' => '/assets/js/libs/inc/vendors/bower_components/material-design-color-palette-master/css/material-design-color-palette.min',
                'material-design-iconic'               => '/assets/js/libs/inc/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min',
                'lightgallery'                         => '/assets/js/libs/inc/vendors/bower_components/lightgallery/light-gallery/css/lightGallery',
                'datetimepicker'                       => '/assets/js/libs/inc/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min',
                'bootstrap-select'                     => '/assets/js/libs/inc/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select',
                'nouislider'                           => '/assets/js/libs/inc/vendors/bower_components/nouislider/distribute/jquery.nouislider.min',
                'summernote'                           => '/assets/js/libs/inc/vendors/bower_components/summernote/dist/summernote',
                'farbtastic'                           => '/assets/js/libs/inc/vendors/farbtastic/farbtastic',
                'animate'                              => '/assets/js/libs/inc/vendors/bower_components/animate.css/animate.min',
                'media-hover-effects'                  => '/assets/css/media-hover-effects',
                'sweetalert'                           => '/assets/js/libs/inc/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert',
                'imgareaselect'                        => '/assets/js/libs/inc/imgareaselect/css/imgareaselect-default',


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



            //Retorno
            //return $this->_dados;
        }


    }
