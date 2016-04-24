<?php

    //EXECUTO
    if($exec_ == false){
        $exec = $this->model->excluir();
        $cont_sucesso++; // contador de sucesso
        if($exec == true){
            $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
            ->setMensagem($this->core->get_msg_array('sucesso_cadastro_array_log',"{$this->btns_acoes['txt_btn_excluir']},{$this->nome_pagina_singular},{$id[$i]}"))->gravo_log(); // Gravo log
        }else{
            $cont_erro++; // contador de erro
            $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
            ->setMensagem($this->core->get_msg_array('erro_cadastro_array_log',"{$this->btns_acoes['txt_btn_excluir']},{$this->nome_pagina_singular},{$id[$i]}"))->gravo_log(); // Gravo log
        }
    }
