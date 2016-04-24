<?php

    if($this->btns_acoes['status_btn_editar'] === false){ // Verifico se tem permissão de acesso a função
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('funcao_acesso_negado','cmd_set_editar()'))->gravo_log(); // Gravo log
        exit();
    }

    //VERIFICO ID
    if(!is_numeric($id)){ // verifico se id é numérico
        $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('is_num',$id)); // Mensagem de erro parametro $id deve ser numérico
        echo json_encode($this->funcoes->get_array());
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('is_num',$id))->gravo_log(); // Gravo log
        exit();
    }

    //SETO OS DADOS E EXECUTO
    $this->model->setId($id);
    $exec = $this->model->set_editar();
