<?php

    //MOSTRO NA TELA
    if($exec == true){
        //MENSAGEM DE SUCESSO E LIMPO CAMPOS
        $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_cadastro',"{$nome_pagina},Excluida"));
        echo json_encode($this->funcoes->get_array());
        //GRAVO LOG
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('sucesso_excluir_log_adm',"{$id},{$tabela}"))->gravo_log(); // Gravo log
    }else{
        //MENSAGEM DE ERRO
        $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('erro_cadastro',"Excluir,{$nome_pagina}")); // Mensagem de erro parametro $id deve ser numérico
        echo json_encode($this->funcoes->get_array());
        //GRAVO LOG
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('erro_excluir_log_adm',"{$id},{$tabela}"))->gravo_log(); // Gravo log
    }
