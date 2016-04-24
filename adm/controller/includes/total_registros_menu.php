<?php

    //===========================================================
    //PEGO AS RESTRIÇÕES
    $restricoes = $this->verifico_restricoes_sistema();
    for ($i=0; $i <count($restricoes) ; $i++) {

        //===========================================================
        //MÓDULO 1
        if($restricoes[$i]['adm_usuario_modulo_id'] == 1){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $totalreg1 = true;
            }
        }

        //===========================================================
        //MÓDULO 2
        if($restricoes[$i]['adm_usuario_modulo_id'] == 2){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $totalreg2 = true;
            }
        }

        //===========================================================
        //MÓDULO 3
        if($restricoes[$i]['adm_usuario_modulo_id'] == 3){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $totalreg3 = true;
            }
        }

        //===========================================================
        //MÓDULO 4
        if($restricoes[$i]['adm_usuario_modulo_id'] == 4){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $totalreg4 = true;
            }
        }

        //===========================================================
        //MÓDULO 5
        if($restricoes[$i]['adm_usuario_modulo_id'] == 5){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $totalreg5 = true;
            }
        }

        //===========================================================
        //MÓDULO 6
        if($restricoes[$i]['adm_usuario_modulo_id'] == 6){
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10 && $restricoes[$i]['opcoes'] == 1){
                $totalreg6 = true;
            }
        }


    }

    //===========================================================
    //TOTAL DE REGISTROS MÓDULO 1
    if($totalreg1 == true){


    }

    //===========================================================
    //TOTAL DE REGISTROS MODULO 2
    if($totalreg2 == true){

        //USUARIOS ATIVOS
        $this->model->setCampos('campo_tabela',"adm_usuario_auth");
        $this->model->setCampos('campo_coluna',"count(*) as total");
        $this->model->setCampos('campo_valor',"total");
        $this->model->setCampos('campo_where',"status_id=1");
        $this->model->setCampos('campo_groupby',"");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $array['adm_usuario'] = $valor0[$i];
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //USUARIOS INATIVOS
        $this->model->setCampos('campo_tabela',"adm_usuario_auth");
        $this->model->setCampos('campo_coluna',"count(*) as total");
        $this->model->setCampos('campo_valor',"total");
        $this->model->setCampos('campo_where',"status_id=2");
        $this->model->setCampos('campo_groupby',"");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $array['adm_usuario2'] = $valor0[$i];
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

    }

    //===========================================================
    //TOTAL DE REGISTROS MODULO 3
    if($totalreg3 == true){

        //EMAILS ENVIADOS
        $this->model->setCampos('campo_tabela',"adm_email_enviados");
        $this->model->setCampos('campo_coluna',"count(*) as total");
        $this->model->setCampos('campo_valor',"total");
        $this->model->setCampos('campo_where',"");
        $this->model->setCampos('campo_groupby',"");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $array['adm_email_enviados'] = $valor0[$i];
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //SMS ENVIADOS
        $this->model->setCampos('campo_tabela',"adm_sms_enviados");
        $this->model->setCampos('campo_coluna',"count(*) as total");
        $this->model->setCampos('campo_valor',"total");
        $this->model->setCampos('campo_where',"");
        $this->model->setCampos('campo_groupby',"");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $array['adm_sms_enviados'] = $valor0[$i];
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();
    }

    //===========================================================
    //TOTAL DE REGISTROS MODULO 4
    if($totalreg4 == true){

        //USUARIOS ONLINE
        $this->model->setCampos('campo_tabela',"adm_usuario_online");
        $this->model->setCampos('campo_coluna',"count(*) as total");
        $this->model->setCampos('campo_valor',"total");
        $this->model->setCampos('campo_where',"");
        $this->model->setCampos('campo_groupby',"");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $array['adm_usuario_online'] = $valor0[$i];
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

    }

    //===========================================================
    //TOTAL DE REGISTROS MODULO 5
    if($totalreg5 == true){


    }

    //===========================================================
    //TOTAL DE REGISTROS MODULO 6
    if($totalreg6 == true){

        //FEEDBACKS
        $this->model->setCampos('campo_tabela',"adm_feedback");
        $this->model->setCampos('campo_coluna',"count(*) as total");
        $this->model->setCampos('campo_valor',"total");
        $this->model->setCampos('campo_where',"status_id = 2");
        $this->model->setCampos('campo_groupby',"");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $array['adm_feedback'] = $valor0[$i];
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //NOTIFICACOES TUDO
        $this->model->setCampos('campo_tabela',"adm_usuario_notificaoes");
        $this->model->setCampos('campo_coluna',"count(*) as total");
        $this->model->setCampos('campo_valor',"total");
        $this->model->setCampos('campo_where',"");
        $this->model->setCampos('campo_groupby',"");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $array['adm_usuario_notificaoes'] = $valor0[$i];
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //NOTIFICACOES USUARIO
        $this->model->setCampos('campo_tabela',"adm_usuario_notificaoes");
        $this->model->setCampos('campo_coluna',"count(*) as total");
        $this->model->setCampos('campo_valor',"total");
        $this->model->setCampos('campo_where',"adm_usuario_id = {$_SESSION['adm_id_user']}");
        $this->model->setCampos('campo_groupby',"");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $array['adm_usuario_notificaoes2'] = $valor0[$i];
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //NOTIFICACOES LIDAS
        $this->model->setCampos('campo_tabela',"adm_usuario_notificaoes");
        $this->model->setCampos('campo_coluna',"count(*) as total");
        $this->model->setCampos('campo_valor',"total");
        $this->model->setCampos('campo_where',"status_id=1");
        $this->model->setCampos('campo_groupby',"");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $array['adm_usuario_notificaoes3'] = $valor0[$i];
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

        //NOTIFICACOES N LIDAS
        $this->model->setCampos('campo_tabela',"adm_usuario_notificaoes");
        $this->model->setCampos('campo_coluna',"count(*) as total");
        $this->model->setCampos('campo_valor',"total");
        $this->model->setCampos('campo_where',"status_id=2");
        $this->model->setCampos('campo_groupby',"");
        $valor0 = $this->model->select_simples_retorna_array_mont();
        if(!empty($valor0)){
            for ($i=0; $i < count($valor0) ; $i++) {
                $array['adm_usuario_notificaoes4'] = $valor0[$i];
            }
        }
        $valor0 = null;
        $this->model->getLimpoCampos();

    }


    //===========================================================
    //RETORNO
    //var_dump($array);
    //exit;
    return $array;
