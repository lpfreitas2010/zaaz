<?php

  	/**
  	* Configurações Gerais do CORE
  	*
  	* @author Fernando
  	* @version 2.0.0
  	**/

    //=================================================================
    //INICIO A SESSÃO PHP
    //=================================================================

    session_start();
    ini_set('zlib.output_compression','On');
    ini_set('zlib.output_compression_level','1');

    //=================================================================
    //INCLUDE
    require_once (dirname(dirname((dirname(__FILE__)))))."/libs/helper/funcoesHelper.php"; //Funcões helper
    require_once (dirname(dirname((dirname(__FILE__)))))."/libs/core/config.php"; //Configurações gerais
    //=================================================================

    //Classe
    class core {

        //Variaveis
        public $_dados     = array();
        public $_dados_msg = array();
        private $config;
        private $funcoes;

        //Seto valor de configuração
        public function set_config($name, $value){
            $this->_dados[$name] = $value;
        }
        //Busco valor de configuração
        public function get_config($param,$param2 = null){
            if(!empty($param2)){
              return $this->_dados[$param][$param2];
            }else{
              return $this->_dados[$param];
            }
        }
        //Seto Array Mesclo arrays
        public function set_array($array){
            if(is_array($array)){
                if(!empty($array)){
                    $this->_dados = array_merge($array,$this->_dados);
                }
            }
        }
        //Busco array mesclado montado
        public function get_array(){
            return $this->_dados;
        }

        //Seto Array Mesclo arrays com mensagens de todo o sistema CORE, WEB etc...
        public function set_array_msgs($array){
            if(is_array($array)){
                if(!empty($array)){
                    $this->_dados_msg = array_merge($array,$this->_dados_msg);
                }
            }
        }

        //GET Array Mesclo arrays com mensagens de todo o sistema CORE, WEB etc...
        public function get_msg_array($param,$array = null){
    	    	if(is_array($array) && !empty($param)){
    	    		return vsprintf($this->_dados_msg[$param],$array);
    	    	}else{
    					$array = explode(',', $array);
    					if(empty($array)){ $array = array(''); }
    					return vsprintf($this->_dados_msg[$param],$array);
    				}
  	    }
        public function get_array_msgs(){
              return $this->_dados_msg;
        }

        //Construct
        public function __construct() {
            $this->init();
        }

        public function init() {

            //=================================================================
            //INSTANCIO E SETO VALORES EM VARIAVEL DE ARRAY DE CONFIGURAÇÕES  E LANGUAGES CORE
            //=================================================================

            $this->config  = new config(); //Instancio classe de configurações
            if(empty($array)){
                $this->set_array($this->config->init()); //Pego configurações do sistema e seto em array
            }
            $array = $this->get_array(); //Pego array de configurações

            //PASTA LIBS > CORE > MENSAGENS
            if($this->verifico_diretorio($this->get_config('dir_msgs_core')) === false){ //Verifico se pasta existe
                $this->mensagem_erro("The folder [ <b> ".$this->get_config('dir_msgs_core')."</b> ] was not found in the root directory.");
                exit();
            }else{
                //MONTO ARRAY COM AS MENSAGENS DO SISTEMA ( CORE ) *****
                require_once $this->get_config('dir_msgs_core');
                $language = new language();
                $this->set_array_msgs($language->get_array());
            }


            //=================================================================
            //IDENTIFICO SERVIDOR ATIVO
            //=================================================================

            if($_SERVER['HTTP_HOST'] != $this->get_config('servidor_ativo_comp')){ //SERVIDOR
                $this->set_config("servidor_ativo",'servidor'); //Servidor
            }
            if($_SERVER['HTTP_HOST'] == $this->get_config('servidor_ativo_comp')){ //LOCALHOST
                $this->set_config("servidor_ativo",'local'); //Local
            }


            //=================================================================
            //TRATO OS ERROS PHP
            //=================================================================

            if((($this->get_config('status_erro_php_servidor') === true) && ($this->get_config('servidor_ativo') === "servidor")) || (($this->get_config('status_erro_php_local') === true) && ($this->get_config('servidor_ativo') === "local"))){
                error_reporting(E_ALL);
                ini_set('display_errors',1);
            }else{
                if(($this->get_config('status_erro_php_servidor') === false && $this->get_config('servidor_ativo') === "servidor") || ($this->get_config('status_erro_php_local') === false && $this->get_config('servidor_ativo') === "local")){
                   error_reporting(0);
                   ini_set('display_errors', 0 );
                }
            }


            //=================================================================
            //VERIFICO PASTAS
            //=================================================================

            //PASTA FILES
            if($this->get_config('status_files') === true){
                if($this->verifico_diretorio($this->get_config('dir_files_comp')) === false){ //Verifico se pasta existe
                    $this->crio_diretorio($this->get_config('dir_files_comp')); //crio pasta
                }
            }

            //PASTAS DA APLICAÇÃO - WEB - CMS E OUTRAS
            $count_paste_apps = $this->get_config('paste_apps');
            for ($i=0; $i < count($count_paste_apps); $i++) {

                $app                 = $count_paste_apps[$i];
                $app_comp            = $array['dir_'.$app.'_comp']; //raiz
                $app_comp_controller = $array['dir_'.$app.'_controller_comp']; //controller
                $app_comp_model      = $array['dir_'.$app.'_model_comp']; //model
                $app_comp_view       = $array['dir_'.$app.'_view_comp']; //view
                $dir_include_conf    = $array['dir_include_conf_'.$app]; //config do controller da view
                $dir_include_core    = $array['dir_include_core_'.$app]; //core do controller da view
                $dir_include_msgs    = $array['dir_include_msgs_'.$app]; //centro de mensagens do controller

                //verifico pasta mensagens do controller existe
                if($this->verifico_diretorio($dir_include_msgs) === false){
                    $this->mensagem_erro($this->get_msg_array('dir_not_found',array($dir_include_msgs)));
                    exit();
                }else{
                    //MONTO ARRAY COM AS MENSAGENS DO SISTEMA ( APPS - cms, cms2 etc... ) ******
                    require_once $dir_include_msgs;
                    $instancia_pers = 'language_'.$app;
                    $language = new $instancia_pers();
                    $this->set_array_msgs($language->get_array());
                }

                //Verifico se pasta existe raiz
                if($this->verifico_diretorio($app_comp) === false){
                    $this->mensagem_erro($this->get_msg_array('dir_not_found',array($app_comp)));
                    exit();
                }

                //Verifico se pasta existe controller
                if($this->verifico_diretorio($app_comp_controller) === false){
                    $this->mensagem_erro($this->get_msg_array('dir_not_found',array($app_comp_controller)));
                    exit();
                }

                //Verifico se pasta existe model
                if($this->verifico_diretorio($app_comp_model) === false){
                    $this->mensagem_erro($this->get_msg_array('dir_not_found',array($app_comp_model)));
                    exit();
                }

                //Verifico se pasta existe view
                if($this->verifico_diretorio($app_comp_view) === false){
                    $this->mensagem_erro($this->get_msg_array('dir_not_found',array($app_comp_view)));
                    exit();
                }

                //Verifico se arquivo pag.php existe
                if($app != "web"){
                    if($this->verifico_diretorio($app_comp."/".$this->get_config('nome_pag_controller_view')) === false){
                        $this->mensagem_erro($this->get_msg_array('dir_not_found',array($app_comp."/".$this->get_config('nome_pag_controller_view'))));
                        exit();
                    }
                }else{
                    if($this->verifico_diretorio($this->get_config('dir_raiz')."/".$this->get_config('nome_pag_controller_view')) === false){
                        $this->mensagem_erro($this->get_msg_array('dir_not_found',array($this->get_config('dir_raiz')."/".$this->get_config('nome_pag_controller_view'))));
                        exit();
                    }
                }

                //verifico pasta template existe
                if($this->verifico_diretorio($app_comp_view."/".$this->get_config('dir_templates')."/".$this->get_config('template_smarty_'.$app)) === false){
                    $this->mensagem_erro($this->get_msg_array('dir_not_found',array($app_comp_view."/".$this->get_config('dir_templates')."/".$this->get_config('template_smarty_'.$app))));
                    exit();
                }

                //verifico pasta assets existe
                if($this->verifico_diretorio($app_comp_view."/".$this->get_config('dir_assets')) === false){
                    $this->mensagem_erro($this->get_msg_array('dir_not_found',array($app_comp_view."/".$this->get_config('dir_assets'))));
                    exit();
                }

                //verifico pasta cache existe
                if($this->verifico_diretorio($app_comp_view."/".$this->get_config('dir_templates')."/".$this->get_config('template_smarty_'.$app)."/".$this->get_config('dir_cache')) === false){
                    $this->crio_diretorio($app_comp_view."/".$this->get_config('dir_templates')."/".$this->get_config('template_smarty_'.$app)."/".$this->get_config('dir_cache')); //crio pasta
                }

                //verifico pasta arquivo config.php existe
                if($this->verifico_diretorio($dir_include_conf) === false){
                    $this->mensagem_erro($this->get_msg_array('dir_not_found',array($dir_include_conf)));
                    exit();
                }

                //verifico pasta arquivo core.php com includes do smarty php existe
                /*if($this->verifico_diretorio($dir_include_core) === false){
                    $this->mensagem_erro($this->get_msg_array('dir_not_found',array($dir_include_core)));
                    exit();
                }*/

                //verifico pasta template existe
                if($this->verifico_diretorio($app_comp_view."/".$this->get_config('dir_templates')."/".$this->get_config('template_smarty_'.$app)) === false){
                    $this->mensagem_erro($this->get_msg_array('dir_not_found',array($app_comp_view."/".$this->get_config('dir_templates')."/".$this->get_config('template_smarty_'.$app))));
                    exit();
                }

            }

            //PASTA LIBS > LOGS
            if($this->verifico_diretorio($this->get_config('dir_logs_comp')) === false){ //Verifico se pasta logs existe
                $this->crio_diretorio($this->get_config('dir_logs_comp')); //crio diretorio
            }

            //PASTA LIBS > INC
            if($this->verifico_diretorio($this->get_config('dir_inc_comp')) === false){ //Verifico se pasta existe
                $this->mensagem_erro($this->get_msg_array('dir_not_found',array($this->get_config('dir_inc_comp'))));
                exit();
            }


            //=================================================================
            //CRIO URL AMIGAVEL HTACESS E WEB.CONFIG
            //=================================================================

            //VERIFICO SE TEM HTACESS
            if($this->get_config('status_url_amigavel') == true){

                //SERVIDOR APACHE
                if($this->proc_string($_SERVER['SERVER_SOFTWARE'],array('Apache')) == 1){
                    $count_paste_apps = $this->get_config('paste_apps');
                    for ($i=0; $i < count($count_paste_apps); $i++) {
                        $app          = $count_paste_apps[$i];
                        $htacess_comp = $array["dir_{$app}_comp"]."";
                        if($app == "web"){
                            $this->crio_htacess($this->get_config('dir_raiz')."/.htaccess",$this->get_config('nome_pag_raiz')); //crio htacess
                            $this->exclui_diretorio($this->get_config('dir_raiz')."/".$this->get_config('nome_arquivo_index')); //Excluo arquivo index.php
                            $this->exclui_diretorio($this->get_config('dir_raiz')."/web.config"); //Excluo arquivo web.config
                        }else{
                            $this->crio_htacess($htacess_comp."/.htaccess",$this->get_config('nome_pag_raiz')); //crio htacess
                            $this->exclui_diretorio($htacess_comp."/".$this->get_config('nome_arquivo_index')); //Excluo arquivo index.php
                            $this->exclui_diretorio($htacess_comp."/web.config"); //Excluo arquivo web.config
                        }
                    }
                }
                //SERVIDOR ISS
                if($this->proc_string($_SERVER['SERVER_SOFTWARE'],array('ISS')) == 1){
                    $count_paste_apps = $this->get_config('paste_apps');
                    for ($i=0; $i < count($count_paste_apps); $i++) {
                        $app          = $count_paste_apps[$i];
                        $htacess_comp = $array["dir_{$app}_comp"]."";
                        if($app == "web"){
                            $this->crio_web_config($this->get_config('dir_raiz')."/web.config",$this->get_config('nome_pag_raiz')); //crio web.config
                            $this->exclui_diretorio($this->get_config('dir_raiz')."/".$this->get_config('nome_arquivo_index')); //Excluo arquivo index.php
                            $this->exclui_diretorio($this->get_config('dir_raiz')."/.htaccess"); //Excluo arquivo .htacess
                        }else{
                            $this->crio_web_config($htacess_comp."/web.config",$this->get_config('nome_pag_raiz')); //crio web.config
                            $this->exclui_diretorio($htacess_comp."/".$this->get_config('nome_arquivo_index')); //Excluo arquivo index.php
                            $this->exclui_diretorio($htacess_comp."/.htaccess"); //Excluo arquivo .htacess
                        }
                    }
                }

            }else{

                //SERVIDOR APACHE
                if($this->proc_string($_SERVER['SERVER_SOFTWARE'],array('Apache')) == 1){
                    $count_paste_apps = $this->get_config('paste_apps');
                    for ($i=0; $i < count($count_paste_apps); $i++) {
                        $app          = $count_paste_apps[$i];
                        $htacess_comp = $array["dir_{$app}_comp"]."";
                        if($app == "web"){
                            $this->crio_indexphp($this->get_config('dir_raiz')."/".$this->get_config('nome_arquivo_index'),$this->get_config('nome_pag_raiz')); //crio inde
                            $this->exclui_diretorio($this->get_config('dir_raiz')."/web.config"); //Excluo arquivo web.config
                            $this->exclui_diretorio($this->get_config('dir_raiz')."/.htaccess"); //Excluo arquivo htacess
                        }else{
                            $this->crio_indexphp($htacess_comp."/".$this->get_config('nome_arquivo_index'),$this->get_config('nome_pag_raiz')); //crio index
                            $this->exclui_diretorio($htacess_comp."/web.config"); //Excluo arquivo web.config
                            $this->exclui_diretorio($htacess_comp."/.htaccess"); //Excluo arquivo htacess
                        }
                    }
                }
                //SERVIDOR ISS
                if($this->proc_string($_SERVER['SERVER_SOFTWARE'],array('ISS')) == 1){
                    $count_paste_apps = $this->get_config('paste_apps');
                    for ($i=0; $i < count($count_paste_apps); $i++) {
                        $app          = $count_paste_apps[$i];
                        $htacess_comp = $array["dir_{$app}_comp"]."";
                        if($app == "web"){
                            $this->crio_indexphp($this->get_config('dir_raiz')."/".$this->get_config('nome_arquivo_index'),$this->get_config('nome_pag_raiz')); //crio index
                            $this->exclui_diretorio($this->get_config('dir_raiz')."/web.config"); //Excluo arquivo .web.config
                            $this->exclui_diretorio($this->get_config('dir_raiz')."/.htaccess"); //Excluo arquivo htacess
                        }else{
                            $this->crio_indexphp($htacess_comp."/".$this->get_config('nome_arquivo_index'),$this->get_config('nome_pag_raiz')); //crio index
                            $this->exclui_diretorio($htacess_comp."/web.config"); //Excluo arquivo .web.config
                            $this->exclui_diretorio($htacess_comp."/.htaccess"); //Excluo arquivo htacess
                        }
                    }
                }
            }


            //=================================================================
            //PROTEJO A SESSÃO PHP
            //=================================================================

            if (array_key_exists('HTTP_USER_AGENT', $_SESSION)) { //Acesso inválido. O header User-Agent mudou durante a mesma sessão.
                if ($_SESSION['HTTP_USER_AGENT'] !=  md5($_SERVER['HTTP_USER_AGENT'])) {
                   $this->mensagem_erro("<b> Invalid access. </b> The User-Agent header changed during the same session.");
                   exit();
                }
            } else {
                $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']); //Primeiro acesso do usuário, vamos gravar na sessão um  hash md5 do header User- Agent
            }


        }//fim construct



        //*********************************************************************************
        //INCLUDES DO SISTEMA
        // @author Fernando Zueet
        //*********************************************************************************


        //=================================================================
        //INCLUDES (SOMENTE FUNÇÕES PADRÕES)
        function includePadrao(){
            $helpers_init = $this->get_config('helpers_init');
            if(count($helpers_init)){ //INCLUO FUNÇÕES
                for ($i=0; $i < count($helpers_init) ; $i++) {
                    $this->includeHelper($helpers_init[$i]);
                }
            }
        }

        //=================================================================
        //INCLUDES ( CONTROLLER )
        function includeController($var = null,$dir = null,$suf = null){

            //INCLUO HELPERS PADRÃO
            $this->includePadrao();

            //INCLUO VIEW (Controla o template)
            $this->includeHelper('view');

            //INCLUO MODEL
            if(!empty($var)){
                if(!empty($suf)){
                    $this->incluo_arquivo($this->get_config('dir_'.$dir.'_model_comp')."/{$var}.php");
                }else{
                    $this->incluo_arquivo($this->get_config('dir_'.$dir.'_model_comp')."/{$var}".$this->get_config('suf_model').".php");
                }
            }

            //INCLUO ARQUIVO configController.php
            $this->includeControllerView($this->get_config('suf_config'),$dir,"false");
        }

        //=================================================================
        //INCLUDES ( MODEL )
        function includeModel($dir = null){

            //INCLUO HELPERS PADRÃO
            $this->includePadrao();

            //CONEXÃO COM O BANCO
            $this->includeHelper("conexao_".$this->get_config('tipo_conexao_bd'));
            if($this->get_config('test_db') == true){ //TESTO A CONEXÃO
                $conexao = new conexao();
                $conexao->Read("show tables");
            }

            //INCLUO ARQUIVO configModel.php
            if(!empty($dir)){
                $this->includeController($this->get_config('suf_config'),$dir,"false");
            }
        }

        //=================================================================
        //INCLUDES ( VIEW )
        function includeView(){

            //INCLUO HELPERS PADRÃO
            $this->includePadrao();

            //INCLUO VIEW (Controla o template)
            $this->includeHelper('view');

            //Incluo o Template da View
            $this->includeInc($this->get_config('inc_template_view'));
        }

        //=================================================================
        //INCLUDES ( CONTROLLER VIEW )
        function includeControllerView($var = null,$dir = null,$suf = null){

            //INCLUO HELPERS PADRÃO
            $this->includePadrao();

            //INCLUO VIEW (Controla o template)
            $this->includeHelper('view');

            //INCLUO CONTROLLER
            if(!empty($var)){
                if(!empty($suf)){
                    $this->incluo_arquivo($this->get_config('dir_'.$dir.'_controller_comp')."/{$var}.php");
                }else{
                    $this->incluo_arquivo($this->get_config('dir_'.$dir.'_controller_comp')."/{$var}".$this->get_config('suf_controller').".php");
                }
            }
        }

        //=================================================================
        //INCLUDES ( HELPER )
        function includeHelper($helper = null){
            if(!empty($helper)){
                 if(is_array($helper)){
                    for ($i=0; $i < count($helper); $i++) {
                        $this->incluo_arquivo($this->get_config('dir_helper_comp')."/{$helper[$i]}".$this->get_config('suf_helper').".php");
                    }
                }else{
                    $this->incluo_arquivo($this->get_config('dir_helper_comp')."/{$helper}".$this->get_config('suf_helper').".php");
                }
            }else{
                $this->mensagem_erro($this->get_msg_array('empty_init_helper',array('')));
                exit();
            }
        }

        //=================================================================
        //INCLUDES ( INCLUDES CONTROLLER VIEW )
        function includeControllerViewInclude($includes = null,$dir = null){
            return $this->incluo_arquivo1($this->get_config('dir_raiz')."/".$dir."/".$this->get_config('dir_view')."/".$this->get_config('template_smarty_'.$dir)."/".$this->get_config('dir_controller_view')."/".$this->get_config('dir_includes')."/".$includes.".php"); //Incluo arquivo
        }

        //=================================================================
        //INCLUDES ( INCLUDES CONTROLLER VIEW )
        function includeControllerInclude($includes = null,$dir = null){
            return $this->incluo_arquivo1($this->get_config('dir_'.$dir.'_controller_comp')."/".$this->get_config('dir_includes')."/".$includes.".php"); //Incluo arquivo
        }

        //=================================================================
        //INCLUDES ( INC )
        function includeInc($inc = null){
            if(!empty($inc)){
                if(is_array($inc)){
                    for ($i=0; $i < count($inc); $i++) {
                        $this->incluo_arquivo($this->get_config('dir_inc_comp')."/{$inc[$i]}");
                    }
                }else{
                    $this->incluo_arquivo($this->get_config('dir_inc_comp')."/{$inc}");
                }
            }else{
                $this->mensagem_erro($this->get_msg_array('empty_init_inc',array('')));
                exit();
            }
        }

        //=================================================================
        //INCLUDES ( VIEW > CONFIG  )
        function includeViewConfig($dir){
            if(!empty($dir)){
                $this->incluo_arquivo($this->get_config('dir_include_conf_'.$dir.''));
            }
        }

        //*********************************************************************************
        //FUNÇÕES DO CORE
        // @author Fernando Zueet
        //*********************************************************************************


        //=================================================================
        //VERIFICO SE ARQUIVO EXISTE E INCLUO
        function incluo_arquivo($arquivo){
            if($this->verifico_diretorio($arquivo) === false){
                $this->mensagem_erro($this->get_msg_array('file_not_found',array($arquivo)));
                exit();
            }else{
                require_once $arquivo; //Incluo o arquivo
            }
        }

        //=================================================================
        //VERIFICO SE ARQUIVO EXISTE E INCLUO RETORNO ARQUIVO
        function incluo_arquivo1($arquivo){
            if($this->verifico_diretorio($arquivo) === false){
                $this->mensagem_erro($this->get_msg_array('file_not_found',array($arquivo)));
                exit();
            }else{
                return $arquivo; //Retorno caminho
            }
        }

        //=================================================================
        //MONTO PÁGINA DE ERRO
        function mensagem_erro($erro){
            $pag = null;
            $pag .= "<div style='width:98%;font-family:arial;'>";
            $pag .= "<h1 style='margin:20px;padding:0px;text-transform:uppercase;text-align:center;color:rgb(65, 68, 66)'>".$this->get_config('nome_site')."</h1>";
            $pag .= "<div style='".$this->get_config('estilo_css_erro')."'>";
            $pag .= "<h4 style='margin:0px;padding:0px;'>".$erro."</h4>";
            $pag .= "</div>";
            echo $pag;
        }

        //=================================================================
        //FUNÇÃO VERIFICA SE DIRETORIO EXISTE
        function verifico_diretorio($dir){
            if (file_exists($dir)) {
                return true;
            }else{
                return false;
            }
        }

        //=================================================================
        //FUNÇÃO CRIA DIRETÓRIO
        function crio_diretorio($dir){
            mkdir($dir, 0777, true);
        }

        //=================================================================
        //FUNÇÃO EXCLUI DIRETÓRIO
        function exclui_diretorio($dir){
            if ($this->verifico_diretorio($dir) === true) {
                unlink($dir);
            }
        }

        //=================================================================
        //FUNÇÃO MONTA DIRETÓRIOS
        function monta_diretorios($param){
           $string = null;
           for ($i=0; $i < count($param); $i++) {
                $string .= $param[$i]."/";
           }
           return $string;
        }

        //=================================================================
        //PESQUISO TERMOS EM UMA STRING
        function proc_string ($frase, $palavras, $resultado = 0) {
            foreach ( $palavras as $key => $value ) {
            $pos = strpos($frase, $value);
            if ($pos !== false) { $resultado = 1; break; }
            }
            return $resultado;
        }

        //=================================================================
        //FUNÇÃO PARA CRIAR ARQUIVO HTACESS
        function crio_htacess($caminho,$pagina){
            if ($this->verifico_diretorio($caminho) === false) {
                $texto = null;
                $texto .= "DirectoryIndex ".$this->get_config('nome_pag_controller_view').$this->get_config('par_url_htacess').$pagina." \r\n";
                $texto .= "RewriteEngine On \r\n";
                $texto .= "RewriteCond %{REQUEST_FILENAME} !-f \r\n";
                $texto .= "RewriteCond %{REQUEST_FILENAME} !-d \r\n";
                $texto .= "RewriteRule ^(.*)$ ".$this->get_config('nome_pag_controller_view').$this->get_config('par_url_htacess')."$1 \r\n";
                $texto .= "IndexIgnore */* \r\n";
                $texto .= "#Bloquea acesso arquivos \r\n";
                $texto .= "<FilesMatch \"\.(".$this->get_config('ext_bloqueadas_htacess').")$\"> \r\n";
                $texto .= "Deny FROM ALL \r\n";
                $texto .= "</FilesMatch>\r\n";
                $texto .= '
                #Força a utilizar Cache-Control e Expires header
                <IfModule mod_headers.c>
                Header unset ETag
                </IfModule>
                FileETag None
                <IfModule mod_expires.c>
                ExpiresActive on
                ExpiresDefault "access plus 1 month"
                ExpiresByType text/cache-manifest "access plus 0 seconds"

                # Html
                ExpiresByType text/html "access plus 0 seconds"

                # Data
                ExpiresByType text/xml "access plus 0 seconds"
                ExpiresByType application/xml "access plus 0 seconds"
                ExpiresByType application/json "access plus 0 seconds"

                # Feed
                ExpiresByType application/rss+xml "access plus 1 hour"
                ExpiresByType application/atom+xml "access plus 1 hour"

                # Favicon
                ExpiresByType image/x-icon "access plus 1 week"

                # Media: images, video, audio
                ExpiresByType image/gif "access plus 1 month"
                ExpiresByType image/png "access plus 1 month"
                ExpiresByType image/jpg "access plus 1 month"
                ExpiresByType image/jpeg "access plus 1 month"
                ExpiresByType video/ogg "access plus 1 month"
                ExpiresByType audio/ogg "access plus 1 month"
                ExpiresByType video/mp4 "access plus 1 month"
                ExpiresByType video/webm "access plus 1 month"

                # HTC files
                ExpiresByType text/x-component "access plus 1 month"

                # Webfonts
                ExpiresByType application/x-font-ttf "access plus 1 month"
                ExpiresByType font/opentype "access plus 1 month"
                ExpiresByType application/x-font-woff "access plus 1 month"
                ExpiresByType image/svg+xml "access plus 1 month"
                ExpiresByType application/vnd.ms-fontobject "access plus 1 month"

                # CSS / JS
                ExpiresByType text/css "access plus 1 year"
                ExpiresByType application/javascript "access plus 1 year"
                ExpiresByType application/x-javascript  "access plus 1 year"
                </IfModule>

                #Força o IE a sempre carregar utilizando a última versão disponível
                <IfModule mod_headers.c>
                Header set X-UA-Compatible "IE=Edge,chrome=1"
                <FilesMatch "\.(js|css|gif|png|jpeg|pdf|xml|oga|ogg|m4a|ogv|mp4|m4v|webm|svg|svgz|eot|ttf|otf|woff|ico|webp|appcache|manifest|htc|crx|oex|xpi|safariextz|vcf)$" >
                Header unset X-UA-Compatible
                </FilesMatch>
                </IfModule> ';

                $manipular = fopen("$caminho", "a+b");
                fwrite($manipular, $texto);
                fclose($manipular);
            }
        }

        //=================================================================
        //FUNÇÃO PARA CRIAR ARQUIVO WEB CONFIG
        function crio_web_config($caminho,$pagina){
            if ($this->verifico_diretorio($caminho) === false) {
                $texto = null;
                $texto .= '
                <?xml version="1.0" encoding="UTF-8"?>
                <configuration>
                <system.webServer>
                <rewrite>
                <rules>
                <rule name="exemplo 1" stopProcessing="true">
                <conditions>
                  <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                  <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                </conditions>
                <match url="^(.*)$" ignoreCase="true" />
                <action type="Rewrite" url="'.$this->get_config('nome_pag_controller_view').$this->get_config('par_url_htacess').'{R:1}" appendQueryString="true" />
                </rule>
                </rules>
                </rewrite>
                </system.webServer>
                </configuration> ';

                $manipular = fopen("$caminho", "a+b");
                fwrite($manipular, $texto);
                fclose($manipular);
            }
        }

        //=================================================================
        //FUNÇÃO PARA CRIAR ARQUIVO INDEX.PHP
        function crio_indexphp($caminho,$pagina){
            if ($this->verifico_diretorio($caminho) === false) {
                $texto = null;
                $texto .= "<?php header('location: ".$this->get_config('nome_pag_controller_view').$this->get_config('par_url_htacess').$pagina."'); ";
                $manipular = fopen("$caminho", "a+b");
                fwrite($manipular, $texto);
                fclose($manipular);
            }
        }



}
