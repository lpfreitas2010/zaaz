<?php

    //EXECUTO
    if($exec == true){
        //MENSAGEM DE SUCESSO E LIMPO CAMPOS
        $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_inserir',$nome_pagina))->set_array(null,'limpo_campo',['true']);
        echo json_encode($this->funcoes->get_array());
        //GRAVO LOG
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('sucesso_inserir_log_adm',$nome_pagina.','.$ult_id))->gravo_log();
        exit();
    }else{
        //MENSAGEM DE ERRO
        $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('erro_inserir',$nome_pagina));
        echo json_encode($this->funcoes->get_array());
        //GRAVO LOG
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('erro_inserir',$nome_pagina))->gravo_log(); //Gravo log
        exit();
     }
