<?php

    //=================================================================
    //CONFIGURAÇÕES GERAIS
    //=================================================================

    //===========================================================
    //INSTANCIO CLASSES
    $this->core->includeControllerView('core',$this->dir_app); //Incluo arquivo coreController.php
    $controller_geral = new coreController();
    $this->core->includeView(); //Incluo arquivo viewHelper.php
    $view = new view($this->dir_app);

    //===========================================================
    //URL AMIGAVEL
    $parte1 = str_replace("?", "", $this->core->get_config('par_url_htacess'));
    $parte2 = str_replace($parte1, "", $parte1 . "/" . $_SERVER['QUERY_STRING']);
    $url = explode("/", $parte2);
    array_shift($url);
    $url = array_filter($url);

    //===========================================================
    //CAMINHO URL ATÉ A PÁGINA ATUAL
    $path_raiz      = $this->core->get_config('dir_raiz_http').$this->dir_app.'/'; //Diretório raiz
    $path_raiz2     = $this->core->get_config('dir_raiz_http').'/'; //Diretório raiz
    $path_parametro = $path_raiz.$parametro;

    //===========================================================
    //MONTO A PÁGINA DE REDIRECIONAMENTO COM OS PARAMETROS
    if ($url[0]) { //verifico se tenho algum parametro posicao 0
        $page_redireciona = $parametro;
        $param_caminho = '';
    }
    if ($url[1]) { //verifico se tenho algum parametro posicao 1
        $pagina_ = $url[1];
        $page_redireciona = $parametro."/".$pagina_;
        $param_caminho = '../';
    }
    if ($url[2]) { //verifico se tenho algum parametro posicao 2
        $page_redireciona = $parametro."/".$url[1].'/'.$url[2];
        $param_caminho = '../../';
    }
    $interface['param_caminho'] = $param_caminho;

    //===========================================================
    //AUTENTICO USUARIO NO SISTEMA
    if($status_auth == true){
        $this->funcoes->auth_usuario($this->dir_app, $parm_auth_status, $path_raiz.$this->config_apps->getPaginas($indice_pagina_red_auth),$page_redireciona); //Se tiver logado redireciono para index
    }

    //===========================================================
    //CARREGO OS PARAMETROS GERAIS
    if($carrego_parametros == true){
        $this->carrego_parametros();
    }

    //===========================================================
    //CONTROLO O TEMPO DE SESSÃO
    if($status_tempo_sessao == true){
        $usuario_sessao = new usuario_sessao();
        $usuario_sessao->controlo_tempo_sessao($this->dir_app,$path_raiz.$this->config_apps->getPaginas(2)); //Controlo tempo de sessão
    }

    //===========================================================
    //GRAVO PÁGINA ATUAL EM COOKIE
    if($status_cookies_page == true){
        if($pagina_ != 'modal'){
            setcookie('pg_hist'.$this->dir_app, $this->funcoes->mycrypt($page_redireciona), time()+3600*24*365, '/','',''); //cookie
        }
    }

    //===========================================================
    //GRAVO LOG DE ACESSO
    $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina($parametro)
    ->setMensagem($this->core->get_msg_array('acesso_pagina_log', $parametro))->gravo_log();

    //===========================================================
    //PEGO CONFIGURAÇÕES SYTEM
    $configs_admin = $controller_geral->retorno_configs_admin();
    $configs_admin2 = $controller_geral->retorno_configs_admin2();
    $param = $this->funcoes->auth_usuario($this->dir_app, false);
    if($param === false){ //substituo os valores de titulo da página e tema pelas configurações do cargo
        $configs_admin[0]['nome_logo_admin'] = $configs_admin2[0]['nome_logo_admin'];
        $configs_admin[0]['tema_admin']      = $configs_admin2[0]['tema_admin'];
    }

    //===========================================================
    //PEGO O MODO DO SISTEMA
    $interface['modo_sistema'] = $_SESSION['adm_modo_sistema'];

    //===========================================================
    //CRIO AS PASTAS DE INCLUDES LISTAGENS E FORMS
    if (!file_exists($this->core->get_config('dir_view_template_'.$this->dir_app.'_comp').'modulos/'.$this->config_apps->get_config('modulos',0).'/includes/listagens')) {
        mkdir($this->core->get_config('dir_view_template_'.$this->dir_app.'_comp').'modulos/'.$this->config_apps->get_config('modulos',0).'/includes/listagens', 0777, true);
    }
    if (!file_exists($this->core->get_config('dir_view_template_'.$this->dir_app.'_comp').'modulos/'.$this->config_apps->get_config('modulos',0).'/includes/forms')) {
        mkdir($this->core->get_config('dir_view_template_'.$this->dir_app.'_comp').'modulos/'.$this->config_apps->get_config('modulos',0).'/includes/forms', 0777, true);
    }

    //=================================================================
    //SETO OS DADOS GERAIS
    //=================================================================

    //===========================================================
    //CONFIGURAÇÕES GERAIS DAS PÁGINAS
    $interface['autor']                      = $this->core->get_config('autor_app'); //Autores da aplicação
    $interface['title_empresa']              = $configs_admin[0]['nome_logo_admin']; //$this->core->get_config('nome_site'); //Nome do site
    $interface['endereco_site']              = $this->core->get_config('endereco_site'); //Endereço do site do site
    $interface['title_empresa_desenvolvido'] = $configs_admin[0]['nome_logo_admin']; //$this->core->get_config('desenvolvido_por'); //Nome da empresa desenvolvedora
    $interface['endereco_site_desen']        = $this->core->get_config('endereco_site_desenv'); //Endereço do site do desenvolvedor
    $interface['versao']                     = $configs_admin[0]['versao_aplicacao']; //Versão da aplicação
    $interface['ano_desenvolvimento']        = $this->core->get_config('versao_ano'); //Ano de desenvolvimento da aplicação
    $interface['url_index']                  = $this->config_apps->get_config('url_index'); //Url da página incial
    $interface['url_erro2']                  = $this->config_apps->get_config('url_erro2'); //Url da página de erro javascript desativado
    $interface['link_page']                  = $this->config_apps->get_config('link_page'); //linkpage pag.php?p=
    $interface['description']                = $this->config_apps->get_config('description_geral'); //Descrição geral do site (metatag)
    $interface['keyword']                    = $this->config_apps->get_config('keyword_geral'); //Palavras chaves (metatag)
    $interface['paginas']                    = $this->config_apps->getPaginas_array(); //Páginas da aplicação
    $interface['language_'.$this->dir_app]   = $this->core->get_config('template_smarty_'.$this->dir_app); //Linguagens da aplicação
    $interface['nome_logo_admin']            = $configs_admin[0]['nome_logo_admin']; //Nome da empresa mostrado como logo
    $interface['img_fundo_login']            = $configs_admin[0]['img_fundo_login']; //Imagem de fundo login

    //===========================================================
    //DIRETÓRIOS
    $interface['path']                      = $this->core->get_config('path_template_comp_' . $this->dir_app.'_apps'); //Diretório raiz até assets
    $interface['path_raiz']                 = $this->core->get_config('dir_raiz_http').$this->dir_app.'/'; //Diretório raiz
    $interface['path_raiz2']                = $this->core->get_config('dir_raiz_http').'/'; //Diretório raiz
    $interface['path_logoff']               = $this->funcoes->monto_path_controller_comp($this->dir_app,'usuarios',$this->config_apps->getCmds_controller('core',19)); //path de sair do sistema
    $interface['action_pesq_geral']         = $this->funcoes->monto_path_controller_comp_core($this->dir_app, 'core',$this->config_apps->getCmds_controller('core',29));
    $interface['action_autoco_pesq_geral']  = $this->funcoes->monto_path_controller_comp_core($this->dir_app, 'core',$this->config_apps->getCmds_controller('core',30));
    $interface['action_notificacoes']       = $this->funcoes->monto_path_controller_comp_core($this->dir_app, 'core',$this->config_apps->getCmds_controller('core',21));
    $interface['action_notificacao_lida']   = $this->funcoes->monto_path_controller_comp_core($this->dir_app, 'core',$this->config_apps->getCmds_controller('core',22));
    $interface['action_notificacoes_popup'] = $this->funcoes->monto_path_controller_comp_core($this->dir_app, 'core',$this->config_apps->getCmds_controller('core',23));
    $interface['path_logoff']               = $this->funcoes->monto_path_controller_comp($this->dir_app,'usuarios',$this->config_apps->getCmds_controller('core',19)); //path de sair do sistema
    $interface['cargo_id']                  = $_SESSION['adm_id_cargo'];
    $interface['action_controller_feedback'] = $this->funcoes->monto_path_controller_comp($this->dir_app,'adm_feedback'); //contrtoller feed back
    $interface['cmd_add_feedback']           = $this->config_apps->getCmds_controller('core',1); //cmd de add e editar feedback
    $interface['action_emails_enviados']     = $this->funcoes->monto_path_controller_comp($this->dir_app,'adm_emails_enviados'); //contrtoller
    $interface['cmd_emails_enviados']        = $this->config_apps->getCmds_controller('core',15);
    $interface['action_sms_enviados']        = $this->funcoes->monto_path_controller_comp($this->dir_app,'adm_sms_enviados'); //contrtoller
    $interface['cmd_sms_enviados']           = $this->config_apps->getCmds_controller('core',15);

    //===========================================================
    //OUTROS PARAMETROS
    $interface['id_usuario_ativo'] = $_SESSION[$this->dir_app.'_id_user'];

    //===========================================================
    //CONFIGURAÇÕES QUANDO TIVER LOGADO
    $param = $this->funcoes->auth_usuario($this->dir_app, false);
    if($param === false){

        //=============================================-==============
        //PEGO OS TOTAL DE REGISTROS USADOS NO MENU
        $interface['total_registros_menu'] = $controller_geral->retorno_array_total_reg_menu();

        //PEGO O ARRAY COM OS EMAILS E TELEFONES UTILIZADOS
        $interface['telefones_sms'] = $controller_geral->retorno_telefones_utilizados();
        $interface['emails_email']  = $controller_geral->retorno_emails_utilizados();

        //===========================================================
        //EXECUTO FUNÇÕES GERAIS DO CORE
        $controller_geral->core_action_geral();

        //===========================================================
        //VERIFICO RESTRIÇÃO DO SISTEMA
        $interface['restricoes_sistema'] = $controller_geral->verifico_restricoes_sistema(); //Pego todas as retrições de todos os módulos
        if(!empty($this->adm_usuario_modulo_id)){
            $restricoes = $controller_geral->verifico_restricoes_sistema($this->adm_usuario_modulo_id); //Pego a restrição do módulo atual
            if(count($restricoes)>=1){
                for ($i=0; $i <count($restricoes) ; $i++) {
                    if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 17){
                        $opcao = (boolean) $restricoes[$i]['opcoes'];
                        if($opcao == false){
                            header('location: '.$param_caminho.$this->config_apps->getPaginas(3)); //Redireciono home
                            exit;
                        }
                    }
                }
           }else{
               header('location: '.$param_caminho.$this->config_apps->getPaginas(3)); //Redireciono home
               exit;
           }
        }

      //=================================================================
      //DADOS DO USUÁRIO
      //=================================================================

      //PEGO NO CONTROLLER GERAL AS INFORMAÇÕES DO USUÁRIO
      $dados_usuario = $controller_geral->retorno_dados_usuario_usuario($id); //Dados do usuário

      //SE NÃO TIVER ID MANTENHO OS DADOS DE SESSÃO
      $interface['nome_usuario_ativo'] = $dados_usuario[0]['nome'];
      $interface['foto_perfil']        = $dados_usuario[0]['img_perfil'];

      //=================================================================
      //NOTIFICAÇÕES DO SISTEMA
      //=================================================================



    }
