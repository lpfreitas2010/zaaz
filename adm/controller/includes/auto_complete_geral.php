<?php

    //===========================================================
    //PEGO AS RESTRIÇÕES
    $restricoes = $this->verifico_restricoes_sistema();
    for ($i=0; $i <count($restricoes) ; $i++) {

        //===========================================================
        //MÓDULO 1
        if($restricoes[$i]['adm_usuario_modulo_id'] == 1){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $autocomplete1 = true;
            }
        }

        //===========================================================
        //MÓDULO 2
        if($restricoes[$i]['adm_usuario_modulo_id'] == 2){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $autocomplete2 = true;
            }
        }

        //===========================================================
        //MÓDULO 3
        if($restricoes[$i]['adm_usuario_modulo_id'] == 3){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $autocomplete3 = true;
            }
        }

        //===========================================================
        //MÓDULO 4
        if($restricoes[$i]['adm_usuario_modulo_id'] == 4){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $autocomplete4 = true;
            }
        }

        //===========================================================
        //MÓDULO 5
        if($restricoes[$i]['adm_usuario_modulo_id'] == 5){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $autocomplete5 = true;
            }
        }

        //===========================================================
        //MÓDULO 6
        if($restricoes[$i]['adm_usuario_modulo_id'] == 6){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $autocomplete6 = true;
            }
        }


    }

    //===========================================================
    //AUTOCOMPLETE MÓDULO 1
    if($autocomplete1 == true){

        //STATUS
        $this->model->setCampos('campo_tabela',"config_status");
        $this->model->setCampos('campo_coluna',"status");
        $this->model->setCampos('campo_valor',"status");
        $this->model->setCampos('campo_where',"status LIKE '%{$valor}%' OR id LIKE '%{$valor}%'");
        $this->model->setCampos('campo_groupby',"status");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'STATUS: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //MODULOS
        $this->model->setCampos('campo_tabela',"adm_usuario_modulo");
        $this->model->setCampos('campo_coluna',"modulo");
        $this->model->setCampos('campo_valor',"modulo");
        $this->model->setCampos('campo_where',"modulo LIKE '%{$valor}%' OR id LIKE '%{$valor}%' OR descricao LIKE '%{$valor}%'");
        $this->model->setCampos('campo_groupby',"modulo");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'MODULOS: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //AÇÕES
        $this->model->setCampos('campo_tabela',"adm_usuario_permissoes_acoes");
        $this->model->setCampos('campo_coluna',"acoes");
        $this->model->setCampos('campo_valor',"acoes");
        $this->model->setCampos('campo_where',"acoes LIKE '%{$valor}%' OR id LIKE '%{$valor}%' ");
        $this->model->setCampos('campo_groupby',"acoes");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'MODULOS ACOES: '.$this->funcoes->anti_injection(substr($valor0[$i], 0, 150));
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

    }

    //===========================================================
    //AUTOCOMPLETE MODULO 2
    if($autocomplete2 == true){

        //USUARIOS
        $this->model->setCampos('campo_tabela',"adm_usuario");
        $this->model->setCampos('campo_coluna',"nome");
        $this->model->setCampos('campo_valor',"nome");
        $this->model->setCampos('campo_where',"nome LIKE '%{$valor}%' OR id LIKE '%{$valor}%'");
        $this->model->setCampos('campo_groupby',"nome");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'USUARIOS: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //CARGOS
        $this->model->setCampos('campo_tabela',"adm_usuario_nivel");
        $this->model->setCampos('campo_coluna',"nivel");
        $this->model->setCampos('campo_valor',"nivel");
        $this->model->setCampos('campo_where',"nivel LIKE '%{$valor}%' or id LIKE '%{$valor}%'");
        $this->model->setCampos('campo_groupby',"nivel");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'ADM USUARIO NIVEL: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();
    }

    //===========================================================
    //AUTOCOMPLETE MODULO 3
    if($autocomplete3 == true){

        //LOGS
        $this->model->setCampos('campo_tabela',"adm_usuario_logs");
        $this->model->setCampos('campo_coluna',"pagina");
        $this->model->setCampos('campo_valor',"pagina");
        $this->model->setCampos('campo_where',"acao LIKE '%{$valor}%' or pagina LIKE '%{$valor}%' or id LIKE '%{$valor}%'  ");
        $this->model->setCampos('campo_groupby',"pagina");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'ADM LOGS: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //LOGS ACESSO
        $this->model->setCampos('campo_tabela',"adm_usuario_logs_acesso");
        $this->model->setCampos('campo_coluna',"acao");
        $this->model->setCampos('campo_valor',"acao");
        $this->model->setCampos('campo_where',"acao LIKE '%{$valor}%' or id LIKE '%{$valor}%' ");
        $this->model->setCampos('campo_groupby',"acao");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'ADM LOGS ACESSO: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //EMAILS ENVIADOS
        $this->model->setCampos('campo_tabela',"adm_email_enviados");
        $this->model->setCampos('campo_coluna',"email");
        $this->model->setCampos('campo_valor',"email");
        $this->model->setCampos('campo_where',"mensagem LIKE '%{$valor}%' or email_re LIKE '%{$valor}%' or email LIKE '%{$valor}%' ");
        $this->model->setCampos('campo_groupby',"email");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'ADM EMAILS ENVIADOS: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //SMS ENVIADOS
        $this->model->setCampos('campo_tabela',"adm_sms_enviados");
        $this->model->setCampos('campo_coluna',"mensagem");
        $this->model->setCampos('campo_valor',"mensagem");
        $this->model->setCampos('campo_where',"mensagem LIKE '%{$valor}%' or telefone LIKE '%{$valor}%' or id_sms LIKE '%{$valor}%'");
        $this->model->setCampos('campo_groupby',"mensagem");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'ADM SMS ENVIADOS: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();
    }

    //===========================================================
    //AUTOCOMPLETE MODULO 4
    if($autocomplete4 == true){

        //USUARIOS ONLINE
        $this->model->setCampos('campo_tabela',"adm_usuario_online");
        $this->model->setCampos('campo_coluna',"adm_usuario.nome");
        $this->model->setCampos('campo_valor',"nome");
        $this->model->setCampos('campo_inner_join'," INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_online.adm_usuario_id  ");
        $this->model->setCampos('campo_where',"adm_usuario.nome like '%{$valor}%' ");
        $this->model->setCampos('campo_groupby',"adm_usuario_online.adm_usuario_id");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'ADM USUARIO ONLINE: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();
    }

    //===========================================================
    //AUTOCOMPLETE MODULO 5
    if($autocomplete5 == true){

        //USUARIOS RESTRIÇÕES
        $this->model->setCampos('campo_tabela',"adm_usuario_modulo_opcoes");
        $this->model->setCampos('campo_coluna',"adm_usuario.nome");
        $this->model->setCampos('campo_valor',"nome");
        $this->model->setCampos('campo_inner_join'," INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_modulo_opcoes.adm_usuario_id ");
        $this->model->setCampos('campo_where',"adm_usuario.nome like '%{$valor}%' ");
        $this->model->setCampos('campo_groupby',"adm_usuario.nome");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'ADM RESTRICOES: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();
    }

    //===========================================================
    //AUTOCOMPLETE MODULO 6
    if($autocomplete6 == true){

        //NOTIFICAÇÕES
        $this->model->setCampos('campo_tabela',"adm_usuario_notificaoes");
        $this->model->setCampos('campo_coluna',"mensagem");
        $this->model->setCampos('campo_valor',"mensagem");
        $this->model->setCampos('campo_where',"mensagem like '%{$valor}%' ");
        $this->model->setCampos('campo_groupby',"mensagem");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'NOTIFICACOES: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //FEEDBACK
        $this->model->setCampos('campo_tabela',"adm_feedback");
        $this->model->setCampos('campo_coluna',"mensagem");
        $this->model->setCampos('campo_valor',"mensagem");
        $this->model->setCampos('campo_where',"tipo LIKE '%{$valor}%' OR id LIKE '%{$valor}%' OR mensagem LIKE '%{$valor}%' OR area LIKE '%{$valor}%'");
        $this->model->setCampos('campo_groupby',"mensagem");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $json[] = 'ADM FEEDBACK: '.substr($valor0[$i], 0, 150);
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();
    }


    //===========================================================
    //CARREGO JSON
    echo json_encode($json);
