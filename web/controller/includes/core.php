<?php

/**
 * Passo dados comuns de todas as páginas para a View
 *
 * @author Fernando
 * @version 2.0.0
 * */
//=================================================================
//CONFIGURAÇÕES GERAIS
//=================================================================

//CONFIGURAÇÕES GERAIS DAS PÁGINAS
$view->seto_dados("autor", $this->core->get_config('autor_app'));
$view->seto_dados("title_empresa", $this->core->get_config('nome_site'));
$view->seto_dados("endereco_site", $this->core->get_config('endereco_site'));
$view->seto_dados("title_empresa_desenvolvido", $this->core->get_config('desenvolvido_por'));
$view->seto_dados("endereco_site_desen", $this->core->get_config('endereco_site_desenv'));
$view->seto_dados("versao", $this->core->get_config('versao_app'));
$view->seto_dados("ano_desenvolvimento", $this->core->get_config('versao_ano'));
$view->seto_dados("url_index", $this->config_apps->get_config('url_index')); //Url da página incial
$view->seto_dados("url_erro2", $this->config_apps->get_config('url_erro2')); //Url da página de erro javascript desativado
$view->seto_dados('link_page', $this->config_apps->get_config('link_page')); //linkpage pag.php?p=
$view->seto_dados('description', $this->config_apps->get_config('description_geral')); //Descrição geral do site (metatag)
$view->seto_dados('keyword', $this->config_apps->get_config('keyword_geral')); //Palavras chaves (metatag)
$view->seto_dados('paginas', $this->config_apps->getPaginas_array()); //Páginas da aplicação
$view->seto_dados('language_'.$this->dir_app, $this->core->get_config('template_smarty_'.$this->dir_app)); //LINGUAGEM DA APLICAÇÃO


//=================================================================
//DIRETÓRIOS PADRÕES
//=================================================================

//DIRETÓRIOS RAIZ
$view->seto_dados('path', $this->core->get_config('path_template_comp_' . $this->dir_app)); //Diretório raiz até assets
$view->seto_dados('path_raiz', $this->core->get_config('dir_raiz_http')); //Diretório raiz

//HEADER
$view->seto_dados('id_usuario_ativo', $_SESSION[$this->dir_app.'_id_user']);

//SE TIVER LOGADO
$param = $this->funcoes->auth_usuario($this->dir_app, false);
if($param === false){


}

//=================================================================
//GRAVO LOG DE ACESSO
//=================================================================

//GRAVO LOG DE ACESSO
$this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina($parametro)
->setMensagem($this->core->get_msg_array('acesso_pagina_log', $parametro))->gravo_log();
