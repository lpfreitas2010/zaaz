<?php

    //===========================================================
    //VERIFICO RESTRIÇÕES
    if($this->btns_acoes['area_listagem_geral'] === false){ // Verifico se tem permissão de acesso a função
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('funcao_acesso_negado','listagem()'))->gravo_log(); // Gravo log
        exit();
    }

    //===========================================================
    //INSTANCIO
    $this->core->includeView();
    $view  = new view($this->dir_app);

    //===========================================================
    //RECEBO OS DADOS GET
    $page           = $this->funcoes->anti_injection($_GET['page']); // Pego a página ativa [ inteiro ]
    $ord = $this->funcoes->anti_injection($_GET['ord']);
    if(!empty($ord)){
        $ordenar = explode(",",$this->funcoes->anti_injection($_GET['ord'])); // Pego o parametro 1 para ordenação
        $ordenar = array_filter($ordenar);
    }
    $group = $this->funcoes->anti_injection($_GET['ord2']);
    if(!empty($group)){
        $group_by = explode(",",$this->funcoes->anti_injection($_GET['ord2'])); // Pego o parametro para group by
        $group_by = array_filter($group_by);
    }
    $qtd_reg_pp     = $this->funcoes->anti_injection($_GET['qtd_reg']); // Pego quantidade de registros p/ página
    $param_pesquisa = $this->funcoes->anti_injection($_GET['pesq']); // Pego o parametro de pesquisa simples
    $limp_pesquisa  = $this->funcoes->anti_injection($_GET['limp_pesq']); // Pego o parametro de limpar pesquisa simples

    //===========================================================
    //VERIFICO SE PÁGINA É NUMERICO
    if (!empty($page) && !is_numeric($page)) {
      $page = 1; // Valor padrão
    }

    //===========================================================
    //VERIFICO SE RECEBI CAMPO ORDENAR
    if(!empty($ordenar)){
      $_SESSION[$pref.$this->url_pagina.'_campo_ordenar'] = $ordenar; // Gravo valor na sessão
    }

    //===========================================================
    //VERIFICO SE RECEBI CAMPO ORDENAR 2
    if(!empty($group_by)){
      $_SESSION[$pref.$this->url_pagina.'_campo_agrupar'] = $group_by; // Gravo valor na sessão
    }

    //===========================================================
    //VERIFICO SE QUANTIDADE DE REGISTROS P/ PAGINA É NUMERICO
    if (!empty($qtd_reg_pp) && !is_numeric($qtd_reg_pp)) {
      $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
      ->setMensagem($this->core->get_msg_array('parametro_incorreto_get_log',$qtd_reg_pp))->gravo_log(); // Gravo log
      exit();
    }else{
      if(!empty($qtd_reg_pp)){
          $_SESSION[$pref.$this->url_pagina.'_qtd_reg_pp'] = $qtd_reg_pp; // Gravo valor na sessão
      }
    }

    //===========================================================
    //VERIFICO SE RECEBI PARAMETRO DE PESQUISA
    if(isset($_SESSION[$pref.$this->url_pagina.'_param_pesquisa'])){
      $interface['param_pesquisa'] = $_SESSION[$pref.$this->url_pagina.'_param_pesquisa']; // Seto o parametro de pesquisa na view
      if(!empty($param_pesquisa)){
          $_SESSION[$pref.$this->url_pagina.'_param_pesquisa'] = $param_pesquisa;
          $interface['param_pesquisa'] = $param_pesquisa; // Seto o parametro de pesquisa na view
      }
    }else{
      if(!empty($param_pesquisa)){
          $_SESSION[$pref.$this->url_pagina.'_param_pesquisa'] = $param_pesquisa;
          $interface['param_pesquisa'] = $param_pesquisa; // Seto o parametro de pesquisa na view
      }
    }

    //===========================================================
    //GERO PAGINAÇÃO
    if(isset($_SESSION[$pref.$this->url_pagina.'_qtd_reg_pp'])){
      $qtd_reg_listagem = $_SESSION[$pref.$this->url_pagina.'_qtd_reg_pp'];
    }

    //===========================================================
    //ACTION E CMDS DO CONTROLLER
    $interface['action_controller'] = $this->action_controller;
    $interface['action_set_editar'] = $this->cmds['action_set_editar'];
    $interface['action_excluir']    = $this->cmds['action_excluir'];
    $interface['action_ativar']     = $this->cmds['action_ativar'];

    //===========================================================
    //OPTION SELECIONADO
    if(isset($_SESSION[$pref.$this->url_pagina.'_campo_ordenar'])){
        $interface['ordenar'] = $_SESSION[$pref.$this->url_pagina.'_campo_ordenar']; // Ordenar selecionado
    }else{
        $interface['ordenar'] = $ordenar_selecionado; // Ordenar selecionado
    }
    $_SESSION[$pref.$this->url_pagina.'_campo_ordenar'] = $interface['ordenar']; // Ordenar selecionado

    //===========================================================
    //AGRUPAR SELECIONADO
    if(isset($_SESSION[$pref.$this->url_pagina.'_campo_agrupar'])){
        $interface['agrupar'] = $_SESSION[$pref.$this->url_pagina.'_campo_agrupar']; // Agrupar selecionado
    }else{
        $interface['agrupar'] = $agrupar_selecionado; // Agrupar selecionado
    }
    $_SESSION[$pref.$this->url_pagina.'_campo_agrupar'] = $interface['agrupar']; // Agrupar selecionado

    //===========================================================
    //GRAVO PESQUISA AVANÇADA NA SESSÃO
    if(!empty($pesq_avancada)){
        $_SESSION[$pref.$this->url_pagina.'_pesquisa_avancada'] = $pesq_avancada;
    }else{
        $pesq_avancada = $_SESSION[$pref.$this->url_pagina.'_pesquisa_avancada'];
    }

    //===========================================================
    //LIMPO O PARAMETRO DA PESQUISA SIMPLES
    if(!empty($limp_pesquisa)){
      unset($_SESSION[$pref.$this->url_pagina.'_param_pesquisa']);
      unset($_SESSION[$pref.$this->url_pagina.'_param_pesquisa_avancada']);
      unset($_SESSION[$pref.$this->url_pagina.'_pesquisa_avancada']);
      unset($interface['param_pesquisa']);
      unset($_SESSION[$pref.$this->url_pagina.'_campo_ordenar']);
      unset($_SESSION[$pref.$this->url_pagina.'_campo_agrupar']);
    }

    //===========================================================
    //SETO OS DADOS MODEL
    if(!empty($tabela)){
        $this->model->setCampos('tabela',$tabela);
    }
    $this->model->setCampos('order_by',$interface['ordenar']);
    $this->model->setCampos('group_by',$interface['agrupar']);
    $this->model->setCampos('query_pesquisa',$interface['param_pesquisa']);
    $this->model->setCampos('query_pesquisa_avancada',$pesq_avancada);

    //===========================================================
    //OUTROS PARAMETROS
    $interface['nome_pagina_singular'] = $this->nome_pagina_singular;

    //===========================================================
    //STATUS DE ÁREAS ****-
    $interface['status_area_agrupar']           = $this->btns_acoes['status_area_agrupar']; // Status do group by [true or false]
    $interface['status_area_ordenacao']         = $this->btns_acoes['status_area_ordenacao']; // Status da ordenação [true or false]
    $interface['status_area_paginacao']         = $this->btns_acoes['status_area_paginacao']; // Status de paginação [ true or false ]
    $interface['status_area_total_registros']   = $this->btns_acoes['status_area_total_registros']; // Status de total registros [ true or false ]
    $interface['status_area_pesquisa']          = $this->btns_acoes['status_area_pesquisa']; // Status da pesquisa [true or false]
    $interface['status_btn_filtros']            = $this->btns_acoes['status_btn_filtros']; // Status do botão de filtros [true or false]
    $interface['status_acao_pesquisa_avancada'] = $this->btns_acoes['status_acao_pesquisa_avancada']; // Status da ação de pesquisa avançada [true or false]
    $interface['status_col_checkbox']           = $this->btns_acoes['status_col_checkbox']; // Status da coluna do ckeckbox [true or false]
    $interface['status_col_acoes']              = $this->btns_acoes['status_col_acoes']; // Status da coluna de ações [true or false]

    //===========================================================
    //STATUS OUTRAS LISTAGENS
    if(!empty($tabela)){
        $interface['status_area_agrupar']           = false; // Status do group by [true or false]
        $interface['status_area_ordenacao']         = true; // Status da ordenação [true or false]
        $interface['status_area_paginacao']         = true; // Status de paginação [ true or false ]
        $interface['status_area_total_registros']   = true; // Status de total registros [ true or false ]
        $interface['status_area_pesquisa']          = false; // Status da pesquisa [true or false]
        $interface['status_btn_filtros']            = false; // Status do botão de filtros [true or false]
        $interface['status_acao_pesquisa_avancada'] = false; // Status da ação de pesquisa avançada [true or false]
        $interface['status_col_checkbox']           = false; // Status da coluna do ckeckbox [true or false]
        $interface['status_col_acoes']              = false; // Status da coluna de ações [true or false]
    }
