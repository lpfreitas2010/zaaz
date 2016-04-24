<?php

    //===========================================================
    //MENSAGEM DE SUCESSO E ERRO
    if($total_ids == $cont_sucesso){
        if($total_ids == 1){ // Se total do array de ids for 1
            $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_cadastro',"{$this->nome_pagina_singular},{$this->btns_acoes['txt_excluir_pret_sing']}")); // Mensagem de sucesso para um registro
            echo json_encode($this->funcoes->get_array());
            exit();
        }else{
            $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_cadastro_array',"{$total_ids},{$cont_sucesso},{$this->nome_pagina_plural},{$this->btns_acoes['txt_excluir_pret_plural']}")); // Mensagem de sucesso para vários registros
            echo json_encode($this->funcoes->get_array());
            exit();
        }
    }else{
        if($exec_ == true && $total_ids == 1){ //mensagem de restrição de exclusão
            $this->funcoes->set_array(null,'erro',$this->core->get_msg_array('erro_exc_restricao')); // Mensagem de erro geral
            echo json_encode($this->funcoes->get_array());
            exit();
        }else{
            $this->funcoes->set_array(null,'sucesso',$this->core->get_msg_array('sucesso_cadastro_array',"{$total_ids},{$cont_sucesso},{$this->nome_pagina_plural},{$this->btns_acoes['txt_excluir_pret_plural']}")); // Mensagem de erro geral
            echo json_encode($this->funcoes->get_array());
            exit();
        }
    }
