<?php

    //===========================================================
    //RECEBO OS DADOS
    (string) $tipo        = $this->funcoes->anti_injection($_GET['tipo']); // tipo
    (int) $id             = $this->funcoes->anti_injection($_GET['id']);
    (string) $url_arquivo = $this->funcoes->anti_injection($_GET['url_arquivo']);

    //VERIFICO ID
    if(!is_numeric($id)){ // verifico se id é numérico
        $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('is_num',$id)); // Mensagem de erro parametro $id deve ser numérico
        echo json_encode($this->funcoes->get_array());
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('is_num',$id))->gravo_log(); // Gravo log
        exit();
    }
