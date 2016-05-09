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
    $path_raiz      = $this->core->get_config('dir_raiz_http'); //Diretório raiz
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
        $usuario_sessao->controlo_tempo_sessao($this->dir_app,$path_raiz.$this->config_apps->getPaginas(0)); //Controlo tempo de sessão
    }

    //===========================================================
    //GRAVO PÁGINA ATUAL EM COOKIE
    if($status_cookies_page == true){
        setcookie('pg_hist'.$this->dir_app, $this->funcoes->mycrypt($page_redireciona), time()+3600*24*365, '/','',''); //cookie
    }

    //===========================================================
    //GRAVO LOG DE ACESSO
    $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina($parametro)
    ->setMensagem($this->core->get_msg_array('acesso_pagina_log', $parametro))->gravo_log();


    //=================================================================
    //SETO OS DADOS GERAIS
    //=================================================================

    //===========================================================
    //CONFIGURAÇÕES GERAIS DAS PÁGINAS
    $interface['autor']                      = $this->core->get_config('autor_app');
    $interface['title_empresa']              = $this->core->get_config('nome_site');
    $interface['endereco_site']              = $this->core->get_config('endereco_site');
    $interface['title_empresa_desenvolvido'] = $this->core->get_config('desenvolvido_por');
    $interface['endereco_site_desen']        = $this->core->get_config('endereco_site_desenv');
    $interface['versao']                     = $this->core->get_config('versao_app');
    $interface['ano_desenvolvimento']        = $this->core->get_config('versao_ano');
    $interface['url_index']                  = $this->core->get_config('url_index');
    $interface['url_erro2']                  = $this->core->get_config('url_erro2');
    $interface['link_page']                  = $this->core->get_config('link_page');
    $interface['description']                = '';
    $interface['keyword']                    = '';
    $interface['paginas']                    = $this->config_apps->getPaginas_array();
    $interface['language_'.$this->dir_app]   = $this->core->get_config('template_smarty_'.$this->dir_app);

    //===========================================================
    //DIRETÓRIOS
    $interface['path']                         = $this->core->get_config('path_template_comp_' . $this->dir_app); //Diretório raiz até assets
    $interface['path_raiz']                    = $this->core->get_config('dir_raiz_http'); //Diretório raiz
    $interface['path_logoff']                  = $this->funcoes->monto_path_controller_comp($this->dir_app,'usuarios',$this->config_apps->getCmds_controller('usuario',9)); //path de sair do sistema

    //===========================================================
    //OUTROS PARAMETROS
    //$interface['id_usuario_ativo'] = $_SESSION[$this->dir_app.'_id_user'];
