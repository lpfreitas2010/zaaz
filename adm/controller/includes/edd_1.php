<?php
    
    $this->model->getLimpoCampos();
    if($exec == true){
        //MENSAGEM DE SUCESSO E RETORNO ID
        if($op_salvar == 'sucesso'){
            $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_editar',$this->nome_pagina_singular))->set_array(null,'id',$id);
            echo json_encode($this->funcoes->get_array());
        }
        //MENSAGEM DE SUCESSO E LIMPO CAMPOS
        if($op_salvar == 'sucesso_limpo'){
            $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_editar',$this->nome_pagina_singular))->set_array(null,'limpo_campo','true');
            echo json_encode($this->funcoes->get_array());
        }
        //GRAVO LOG
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('sucesso_editar_log_adm',$this->nome_pagina_singular.','.$id))->gravo_log();
        exit();
    }else{
        //MENSAGEM DE ERRO
        $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('erro_editar',$this->nome_pagina_singular));
        echo json_encode($this->funcoes->get_array());
        //GRAVO LOG
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('erro_editar_log',$this->nome_pagina_singular.','.$id))->gravo_log(); //Gravo log
        exit();
     }
