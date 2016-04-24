<?php

    //===========================================================
    //CONFIGURAÇÕES
    $cont_sucesso = 0; // Zero contador
    $cont_erro    = 0; // Zero contador

    //===========================================================
    //RECEBO PARAMETROS
    (array) $id      = explode(",",$this->funcoes->anti_injection($_POST['id'])); // Recebo id
    (int) $status_id = $this->funcoes->anti_injection($_GET['s']); // Recebo status_id
    (int) $total_ids = count($id); //Total de ids

    //===========================================================
    //TRATO PARAMETROS
    if(is_array($id)){
        for ($i=0; $i <$total_ids; $i++) {
            if(!is_numeric($id[$i])){ // verifico se id é numérico
                $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('is_num',$id[$i])); // Mensagem de erro parametro $id deve ser numérico
                echo json_encode($this->funcoes->get_array());
                $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
                ->setMensagem($this->core->get_msg_array('is_num',$id[$i]))->gravo_log(); // Gravo log
                exit();
            }
        }
    }
    if(!is_numeric($status_id)){ // verfifico se status_id é numérico
        $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('is_num',$status_id)); // Mensagem de erro parametro $id deve ser numérico
        echo json_encode($this->funcoes->get_array());
        $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
        ->setMensagem($this->core->get_msg_array('is_num',$status_id))->gravo_log(); // Gravo log
        exit();
    }
