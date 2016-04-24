<?php

    $this->model->getLimpoCampos();
    $ult_id = $this->model->getUltimo_id();
    if($exec == true){
        //MENSAGEM DE SUCESSO E RETORNO ID
        if($op_salvar == 'sucesso'){
            $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_inserir',$this->nome_pagina_singular))->set_array(null,'id',$ult_id);
            echo json_encode($this->funcoes->get_array());
        }
        //MENSAGEM DE SUCESSO E LIMPO CAMPOS
        if($op_salvar == 'sucesso_limpo'){
            $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_inserir',$this->nome_pagina_singular))->set_array(null,'limpo_campo','true');
            echo json_encode($this->funcoes->get_array());
        }
        //GRAVO LOG
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('sucesso_inserir_log_adm',$this->nome_pagina_singular.','.$ult_id))->gravo_log();
        exit();
    }else{
        //MENSAGEM DE ERRO
        $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('erro_inserir',$this->nome_pagina_singular));
        echo json_encode($this->funcoes->get_array());
        //GRAVO LOG
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('erro_inserir',$this->nome_pagina_singular))->gravo_log(); //Gravo log
        exit();
     }
