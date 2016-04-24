<?php

    //===========================================================
    //EXECUTO O MODEL
    if(is_array($id)){ // verifico se $id é array
        for ($i=0; $i < $total_ids; $i++) {

            //SETO OS DADOS
            $this->model->setId($id[$i]);
            $this->model->setStatus_id($status_id_);

            //EXECUTO
            $exec = $this->model->ativar_desativar();
            if($exec == true){
                $cont_sucesso++; // contador de sucesso
                $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                ->setMensagem($this->core->get_msg_array('sucesso_cadastro_array_log',"{$texto1},{$this->nome_pagina_singular},{$id[$i]}"))->gravo_log(); // Gravo log
            }else{
                $cont_erro++; // contador de erro
                $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                ->setMensagem($this->core->get_msg_array('erro_cadastro_array_log',"{$texto1},{$this->nome_pagina_singular},{$id[$i]}"))->gravo_log(); // Gravo log
            }
        }
    }else{
        $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('is_arr',$id)); // Mensagem de erro $id não é um array
        echo json_encode($this->funcoes->get_array());
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('is_arr',$id))->gravo_log(); // Gravo log
        exit();
    }

    //===========================================================
    //MENSAGEM DE SUCESSO E ERRO
    if($total_ids == $cont_sucesso){
        if($total_ids == 1){ // Se total do array de ids for 1
            $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_cadastro',"{$this->nome_pagina_singular},{$texto3}")); // Mensagem de sucesso para um registro
            echo json_encode($this->funcoes->get_array());
            exit();
        }else{
            $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_cadastro_array',"{$cont_sucesso},{$total_ids},{$this->nome_pagina_plural},{$texto2}")); // Mensagem de sucesso para vários registros
            echo json_encode($this->funcoes->get_array());
            exit();
        }
    }else{
        if($cont_sucesso == 0){ // Se contador de sucesso for 0
            $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('erro_cadastro',"{$texto1},{$this->nome_pagina_plural}")); // Mensagem de erro geral
            echo json_encode($this->funcoes->get_array());
            exit();
        }else{
            $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_cadastro_array',"{$cont_sucesso},{$total_ids},{$this->nome_pagina_plural},{$texto2}")); // Mensagem de erro geral
            echo json_encode($this->funcoes->get_array());
            exit();
        }
    }
