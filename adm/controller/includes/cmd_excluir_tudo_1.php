<?php

    //EXECUTO
    $exec = $this->model->excluir_tudo();
    if($exec == true){
        $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_cadastro',"{$this->nome_pagina_plural},{$this->btns_acoes['txt_excluir_pret_plural']}")); // Mensagem de erro com sucessos
        echo json_encode($this->funcoes->get_array());
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('sucesso_cadastro_log',"{$this->nome_pagina_plural},{$this->btns_acoes['txt_excluir_pret_plural']},"))->gravo_log(); // Gravo log
        exit();
    }else{
        $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('erro_cadastro',"{$this->btns_acoes['txt_btn_excluir']},{$this->nome_pagina_plural}")); // Mensagem de erro geral
        echo json_encode($this->funcoes->get_array());
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('erro_cadastro_log',"{$this->btns_acoes['txt_btn_excluir']},{$this->nome_pagina_singular}"))->gravo_log(); // Gravo log
        exit();
    }
