<?php

        //===========================================================
        //STATUS DE ÁREA DOS BOTÕES DE LISTAGEM ****-
        $interface['area_listagem_geral']           = $this->btns_acoes['area_listagem_geral']; // Status da área de listagem geral [true or false]
        $interface['area_listagem']                 = $this->btns_acoes['area_listagem']; // Status da área de listagem [true or false]
        $interface['area_btns_status_acoes']        = $this->btns_acoes['area_btns_status_acoes']; // Status de área geral dos botôes de opções da listagem [true or false]
        $interface['area_formulario']               = $this->btns_acoes['area_formulario'] ; // Status da área do formulário [true or false]
        $interface['area_btns_status_acoes_forms']  = $this->btns_acoes['area_btns_status_acoes_forms']; // Status da área geral dos botões de opções do formulário [true or false]

        //===========================================================
        //TITULOS GERAIS ****
        $interface['title_pagina']           = $this->nome_pagina_plural." -  "; // Titulo geral da página singular
        $interface['titulo_pagina_listagem'] = $this->nome_pagina_plural; // Titulo geral da página de listagem
        $interface['titulo_pagina_singular'] = $this->nome_pagina_singular; // Titulo geral da página singular
        $interface['titulo_pagina_plural']   = $this->nome_pagina_plural; // Titulo geral da página plural
        $interface['titulo_pagina_novo']     = $this->btns_acoes['txt_btn_novo2']; // Titulo geral da página novo
        $interface['titulo_pagina_editar']   = $this->btns_acoes['title_btn_editar']; // Titulo geral da página novo
        $interface['titulo_pagina_listagem'] = $this->nome_pagina_plural; // Titulo geral da página de listagem
        $interface['txt_btn_pdf']            = $this->btns_acoes['txt_btn_pdf2']; // Texto do botão pdf
        $interface['txt_btn_csv']            = $this->btns_acoes['txt_btn_csv2']; // Texto do botão csv
        $interface['txt_btn_imprimir']       = $this->btns_acoes['txt_btn_imprimir2']; // Texto do botão imprimir

        //===========================================================
        //CLASSE DE ICONES DE BTNS DE AÇÕES ****
        $interface['class_icone_listagem']     = $this->btns_acoes['class_icone_listagem']; // Classe do icone listagem
        $interface['class_icone_novo']         = $this->btns_acoes['class_icone_novo']; // Classe do icone novo geral
        $interface['class_icone_editar']       = $this->btns_acoes['class_icone_editar']; // Classe do icone editar geral
        $interface['class_icone_excluir']      = $this->btns_acoes['class_icone_excluir']; // Classe do icone excluir geral
        $interface['class_icone_detalhamento'] = $this->btns_acoes['class_icone_detalhamento']; // Classe do icone detalho conteudo
        $interface['class_icone_atualizar']    = $this->btns_acoes['class_icone_atualizar']; // Classe do icone atualizar
        $interface['class_icone_imprimir']     = $this->btns_acoes['class_icone_imprimir']; // Classe do icone imprimir
        $interface['class_icone_exp_pdf']      = $this->btns_acoes['class_icone_exp_pdf']; // Classe do icone exp_pdf
        $interface['class_icone_exp_csv']      = $this->btns_acoes['class_icone_exp_csv']; // Classe do icone exp_csv
        $interface['class_icone_ativar']       = $this->btns_acoes['class_icone_ativar']; // Classe do icone ativar
        $interface['class_icone_desativar']    = $this->btns_acoes['class_icone_desativar']; // Classe do desativar

        //===========================================================
        //STATUS DE BTNS DE AÇÕES ****
        $interface['status_btn_novo']          = $this->btns_acoes['status_btn_novo']; // Status de btn de cadastro [true or false]
        $interface['status_btn_editar']        = $this->btns_acoes['status_btn_editar']; // Status de btn de editar [true or false]
        $interface['status_btn_excluir']       = $this->btns_acoes['status_btn_excluir']; // Status de btn de excluir [true or false]
        $interface['status_btn_detalhamento']  = $this->btns_acoes['status_btn_detalhamento']; // Status de btn de detlhamento de conteúdo [true or false]
        $interface['status_btn_atualizar']     = $this->btns_acoes['status_btn_atualizar']; // Status de btn de atualizar [true or false]
        $interface['status_imprimir']          = $this->btns_acoes['status_imprimir']; // Status de btn de imprimir [true or false]
        $interface['status_exportar_pdf']      = $this->btns_acoes['status_exportar_pdf']; // Status de btn de exportar para pdf [true or false]
        $interface['status_exportar_csv']      = $this->btns_acoes['status_exportar_csv']; // Status de btn de exportar para csv [true or false]
        $interface['status_btn_salvar']        = $this->btns_acoes['status_btn_salvar']; // Status de btn salvar [true or false]
        $interface['status_btn_fechar']        = $this->btns_acoes['status_btn_fechar']; // Status de btn fechar [true or false]
        $interface['status_btn_salvar_fechar'] = $this->btns_acoes['status_btn_salvar_fechar']; // Status de btn salvar e fechar [true or false]
        $interface['status_btn_salvar_novo']   = $this->btns_acoes['status_btn_salvar_novo']; // Status de btn salvar e novo [true or false]
        $interface['status_area_grafico']      = $this->btns_acoes['status_area_grafico']; // Status da area de grafico

        //===========================================================
        //STATUS DE ÁREA DOS BOTÕES DE LISTAGEM
        $interface['area_imprimir_status'] = false; // Status da área de imprimir [true or false]
        $interface['area_pdf_status']      = false; // Status da área de exporto para pdf [true or false]
        $interface['area_csv_status']      = false; // Status da área de exporto para csv [true or false]
        $interface['area_detalho_status']  = false; // Status da área de detalhamento [true or false

        //===========================================================
        //HREF DOS BTNS DE AÇÕES
        $interface['href_btn_novo'] = "javascript:void(0);"; // Href padrão do botão novo

        //===========================================================
        //CARREGO Á AREA DO BOTÃO IMPRIMIR
        if(!empty($url[1]) && $url[1] == 'imprimir'){
            $interface['area_imprimir_status'] = true; // Status da área de imprimir [true or false]
        }

        //===========================================================
        //CARREGO Á AREA DO BOTÃO EXPORTAR PARA PDF
        if(!empty($url[1]) && $url[1] == 'exportar_pdf'){
            $interface['area_pdf_status']  = true; // Status da área de exporto para pdf [true or false]
        }

        //===========================================================
        //CARREGO Á AREA DO BOTÃO EXPORTAR PARA CSV
        if(!empty($url[1]) && $url[1] == 'exportar_csv'){
            $interface['area_csv_status'] = true; // Status da área de exporto para csv [true or false]
        }

        //===========================================================
        //CARREGO Á AREA DO BOTÃO DE DETALHAMENTO
        if(!empty($url[1]) && $url[1] == 'detalho' && !empty($url[2]) && is_numeric($url[2])){
            $interface['area_detalho_status'] = true; // Status da área de detalhamento [true or false]
            $interface['id_detalho']          = $this->funcoes->anti_injection($url[2]); // Passo id do detalhamento
        }else{
            if(!empty($url[1]) && $url[1] == 'detalho' && !is_numeric($url[2])){
                header('location: '.$path_parametro); //redireciono para a página atual
                exit;
            }
        }

        //===========================================================
        //CARREGO A LISTAGEM COM UM PARAMETRO DE PESQUISA
        if(!empty($url[1]) && $url[1] == 'busca' && !empty($url[2])){
            $_SESSION[$this->url_pagina.'_param_pesquisa'] = $this->funcoes->anti_injection($url[2]); // Passo para a sessão o parametro de pesquisa
        }

        //===========================================================
        //CARREGO A LISTAGEM COM A PESQUISA AVANÇADA ATIVA
        if(!empty($url[1]) && $url[1] == 'pesquisa_avancada'){
            $_SESSION[$this->url_pagina.'_param_pesquisa_avancada'] = true; // Passo para a sessão o parametro para abrir a listagem com a pesquisa avançada ativa
        }else{
            unset($_SESSION[$this->url_pagina.'_param_pesquisa_avancada']); // Deleto da sessão a variavel
        }

        //===========================================================
        //CARREGO Á AREA DO BOTÃO NOVO
        if($this->btns_acoes['status_btn_novo'] === true){
            if(!empty($url[1]) && $url[1] == 'novo'){
                $interface['area_formulario']              = true; // Status da área do formulário [true or false]
                $interface['area_listagem']                = false; // Status da área de listagem [true or false]
                $interface['area_btns_status_acoes_forms'] = true; // Status da área geral dos botões de opções do formulário [true or false]
                $interface['area_btns_status_acoes']       = false; // Status de área geral dos botôes de opções da listagem [true or false]
                $interface['title_pagina']                 = $interface['titulo_pagina_novo']." - "; // Titulo geral da página singular
            }
        }

        //===========================================================
        //CARREGO Á AREA DO BOTÃO DE EDITAR
        if($this->btns_acoes['status_btn_editar'] === true){
            if(!empty($url[1]) && $url[1] == 'editar' && !empty($url[2]) && is_numeric($url[2]) && $interface['area_listagem_geral'] == true){
                $interface['area_formulario']              = true; // Status da área do formulário [true or false]
                $interface['area_listagem']                = false; // Status da área de listagem [true or false]
                $interface['area_btns_status_acoes_forms'] = true; // Status da área geral dos botões de opções do formulário [true or false]
                $interface['area_btns_status_acoes']       = false; // Status de área geral dos botôes de opções da listagem [true or false]
                $interface['title_pagina']                 = $interface['titulo_pagina_editar']." - "; // Titulo geral da página singular
                $interface['form_campo_id']                = $url[2]; //Passo o id
                $interface['href_btn_novo']                = "../novo"; // Href do botão novo
            }else{
                if(!empty($url[1]) && $url[1] == 'editar' && !is_numeric($url[2]) && $interface['area_listagem_geral'] == true){
                    header('location: '.$path_parametro); //redireciono para a página atual
                    exit;
                }
            }
        }

        //===========================================================
        //CARREGO SOMENTE A PÁGINA DE EDITAR E OCULTO A PÁGINA DE LISTAGEM
        if($interface['area_listagem_geral'] == false){

            //NOVO
            if(empty($url[1]) || $url[1] == 'novo'){
                if($this->btns_acoes['status_btn_novo'] === true){

                    //STATUS E CONFIGURAÇÕES GERAIS
                    $interface['area_formulario']              = true; // Status da área do formulário [true or false]
                    $interface['area_listagem']                = false; // Status da área de listagem [true or false]
                    $interface['area_listagem_geral']          = false;
                    $interface['area_btns_status_acoes_forms'] = true; // Status da área geral dos botões de opções do formulário [true or false]
                    $interface['area_btns_status_acoes']       = false; // Status de área geral dos botôes de opções da listagem [true or false]

                    //STATUS DO BTNS DE AÇÕES DO FORM
                    $interface['status_btn_salvar']        = true; // Status de btn salvar [true or false]
                    $interface['status_btn_fechar']        = false; // Status de btn fechar [true or false]
                    $interface['status_btn_salvar_fechar'] = false; // Status de btn salvar e fechar [true or false]
                    $interface['status_btn_salvar_novo']   = false; // Status de btn salvar e novo [true or false]

                    //TITULOS, DESCRIÇÕES E ICONES GERAIS
                    $interface['titulo_pagina_editar'] = $interface['titulo_pagina_novo']; // Titulo geral da página novo
                    $interface['class_icone_editar']   = $this->btns_acoes['class_icone_novo']; // Classe do icone editar geral
                    $interface['title_pagina']         = $interface['titulo_pagina_novo']." - "; // Titulo geral da página singular

                }else{
                    header('location: '.$path_raiz); //redireciono para a página atual
                    exit;
                }
            }

            //EDITAR
            if(empty($url[1]) || $url[1] == 'editar'){
                if($this->btns_acoes['status_btn_editar'] === true){

                    if(empty($url[2])){
                        header('location: '.$path_raiz); //redireciono para a página atual
                        exit;
                    }else{

                        //STATUS E CONFIGURAÇÕES GERAIS
                        $interface['area_formulario']              = true; // Status da área do formulário [true or false]
                        $interface['area_listagem']                = false; // Status da área de listagem [true or false]
                        $interface['area_listagem_geral']          = false;
                        $interface['area_btns_status_acoes_forms'] = true; // Status da área geral dos botões de opções do formulário [true or false]
                        $interface['area_btns_status_acoes']       = false; // Status de área geral dos botôes de opções da listagem [true or false]
                        $interface['form_campo_id']                = $url[2]; //Passo o id
                        $interface['href_btn_novo']                = "../novo"; // Href do botão novo

                        //STATUS DO BTNS DE AÇÕES DO FORM
                        $interface['status_btn_salvar']        = true; // Status de btn salvar [true or false]
                        $interface['status_btn_fechar']        = false; // Status de btn fechar [true or false]
                        $interface['status_btn_salvar_fechar'] = false; // Status de btn salvar e fechar [true or false]
                        $interface['status_btn_salvar_novo']   = false; // Status de btn salvar e novo [true or false]

                        //TITULOS, DESCRIÇÕES E ICONES GERAIS
                        $interface['titulo_pagina_editar'] = $interface['titulo_pagina_editar']; // Titulo geral da página novo
                        $interface['class_icone_editar']   = $this->btns_acoes['class_icone_editar']; // Classe do icone editar geral
                        $interface['title_pagina']         = $interface['titulo_pagina_editar']." - "; // Titulo geral da página singular

                    }
                }else{
                    header('location: '.$path_raiz); //redireciono para a página atual
                    exit;
                }
            }

        }

        //===========================================================
        //CARREGO PARAMETRO QUE MONTA O MODAL
        if(!empty($url[1]) && $url[1] == 'modal'){
             $interface['area_formulario']              = true; // Status da área do formulário [true or false]
             $interface['area_listagem']                = false; // Status da área de listagem [true or false]
             $interface['area_btns_status_acoes_forms'] = true; // Status da área geral dos botões de opções do formulário [true or false]
             $interface['area_btns_status_acoes']       = false; // Status de área geral dos botôes de opções da listagem [true or false]
             $interface['title_pagina']                 = $interface['titulo_pagina_novo']." - "; // Titulo geral da página singular
             $interface['param_modal_add']              = 'param_modal_add'; // Status [true or false]
        }

        //===========================================================
        //ACTION E CMDS DO CONTROLLER
        $interface['action_controller']     = $this->action_controller;
        $interface['action_excluir']        = $this->cmds['action_excluir'];
        $interface['action_detalhamento']   = $this->cmds['action_detalhamento'];
        $interface['action_listagem']       = $this->cmds['action_listagem'];
        $interface['action_ativar']         = $this->cmds['action_ativar'];
        $interface['action_set_editar']     = $this->cmds['action_set_editar'];
        $interface['cmd_add_edd']           = $this->cmds['cmd_add_edd'];
        $interface['cmd_exportar_imprimir'] = $this->cmds['cmd_exportar_imprimir'];

        //===========================================================
        /*$this->funcoes->set_array(1,'btn_status','') // Status do botão [ true or false ]
                        ->set_array(1,'btn_texto','') // Texto do botão
                        ->set_array(1,'btn_href','') // Href do botão
                        ->set_array(1,'btn_target','') // Target do botão
                        ->set_array(1,'btn_title','') // Title do botão
                        ->set_array(1,'btn_id','') // id do botão
                        ->set_array(1,'btn_class','') // class do botão
                        ->set_array(1,'btn_class_resp','') // class do botão responsivo
                        ->set_array(1,'btn_class_icon','') // class do icone
                        ->set_array(1,'btn_outros',''); // outros parametros do botão */

        //===========================================================
        //BTNS DE OPÇÕES BTNS DE AÇÕES ******
        $this->funcoes->set_array(1,'btn_status',$interface['status_btn_novo'])
                        ->set_array(1,'btn_texto',$this->btns_acoes['txt_btn_novo'])
                        ->set_array(1,'btn_href',$interface['href_btn_novo'])
                        ->set_array(1,'btn_title',$this->btns_acoes['title_btn_novo'])
                        ->set_array(1,'btn_id','btn_adicionar')
                        ->set_array(1,'btn_class','btn-dois btn-success mostra_area monta_dados_form limpo_id_editar')
                        ->set_array(1,'btn_class_resp','mostra_area monta_dados_form limpo_id_editar')
                        ->set_array(1,'btn_class_icon',$interface['class_icone_novo'])
                        ->set_array(1,'btn_outros','limpar_campos_form="form_add_edd" hide_area="#area_btns_acoes" show_area2="#area_btns_status_acoes_forms" hide_area2="#area_listagem" hide_area3="#area-editar" hide_area4=".area-geral-listagem" show_area3="#area-adicionar" show_area4="#btn_salvar_novo_form" show_area5="#area_formulario"');
        $this->funcoes->set_array(2,'btn_status',$interface['status_btn_editar'])
                        ->set_array(2,'btn_texto',$this->btns_acoes['txt_btn_editar'])
                        ->set_array(2,'btn_href','javascript:void(0);')
                        ->set_array(2,'btn_title',$this->btns_acoes['title_btn_editar'])
                        ->set_array(2,'btn_id','btn_editar')
                        ->set_array(2,'btn_class','btn-dois btn-default2 mostra_area btn_editar hidden_area list_op1')
                        ->set_array(2,'btn_class_resp','mostra_area btn_editar hidden_area list_op1')
                        ->set_array(2,'btn_hidden_resp','hidden_area list_op1')
                        ->set_array(2,'btn_class_icon',$interface['class_icone_editar'])
                        ->set_array(2,'btn_outros','param_url="'.$interface['action_set_editar'].'" hide_area="#btn_salvar_novo_form" show_area5="#area_formulario"');
        $this->funcoes->set_array(3,'btn_status',$interface['status_btn_excluir'])
                        ->set_array(3,'btn_texto',$this->btns_acoes['txt_btn_excluir'])
                        ->set_array(3,'btn_href','javascript:void(0);')
                        ->set_array(3,'btn_title',$this->btns_acoes['title_btn_excluir'])
                        ->set_array(3,'btn_id','btn_excluir_check')
                        ->set_array(3,'btn_id_resp','btn_excluir_check_resp')
                        ->set_array(3,'btn_class','btn-dois btn-default2 hidden_area list_op2')
                        ->set_array(3,'btn_class_resp','hidden_area list_op2')
                        ->set_array(3,'btn_hidden_resp','hidden_area list_op2')
                        ->set_array(3,'btn_class_icon',$interface['class_icone_excluir'])
                        ->set_array(3,'btn_outros','param_url="'.$interface['action_excluir'].'"');
        $this->funcoes->set_array(4,'btn_status',$interface['status_btn_detalhamento'])
                        ->set_array(4,'btn_texto',$this->btns_acoes['txt_btn_detalhamento'])
                        ->set_array(4,'btn_href','javascript:void(0);')
                        ->set_array(4,'btn_title',$this->btns_acoes['title_btn_detalhamento'])
                        ->set_array(4,'btn_id','btn_detalhamento')
                        ->set_array(4,'btn_class','btn-dois btn-default2 btn_detalhamento hidden_area list_op1')
                        ->set_array(4,'btn_class_resp','btn_detalhamento hidden_area list_op1')
                        ->set_array(4,'btn_hidden_resp','hidden_area list_op1')
                        ->set_array(4,'btn_class_icon',$interface['class_icone_detalhamento']);
                        $this->funcoes->set_array(5,'btn_status',$this->btns_acoes['status_btn_ativar'])
        ->set_array(5,'btn_texto',$this->btns_acoes['txt_btn_ativar'])
                        ->set_array(5,'btn_href','javascript:void(0);')
                        ->set_array(5,'btn_title',$this->btns_acoes['title_btn_ativar'])
                        ->set_array(5,'btn_id','btn_ativar_check')
                        ->set_array(5,'btn_id_resp','btn_ativar_check_resp')
                        ->set_array(5,'btn_class','btn-dois btn-default2 hidden_area list_op2')
                        ->set_array(5,'btn_class_resp','hidden_area list_op2')
                        ->set_array(5,'btn_hidden_resp','hidden_area list_op2')
                        ->set_array(5,'btn_class_icon',$interface['class_icone_ativar'])
                        ->set_array(5,'btn_outros','param_url="'.$interface['action_ativar'].'&s=2"');
        $this->funcoes->set_array(6,'btn_status',$this->btns_acoes['status_btn_ativar'])
                        ->set_array(6,'btn_texto',$this->btns_acoes['txt_btn_desativar'])
                        ->set_array(6,'btn_href','javascript:void(0);')
                        ->set_array(6,'btn_title',$this->btns_acoes['title_btn_desativar'])
                        ->set_array(6,'btn_id','btn_ativar_check2')
                        ->set_array(6,'btn_id_resp','btn_ativar_check2_resp')
                        ->set_array(6,'btn_class','btn-dois btn-default2 hidden_area list_op2')
                        ->set_array(6,'btn_class_resp','hidden_area list_op2')
                        ->set_array(6,'btn_hidden_resp','hidden_area list_op2')
                        ->set_array(6,'btn_class_icon',$interface['class_icone_desativar'])
                        ->set_array(6,'btn_outros','param_url="'.$interface['action_ativar'].'&s=1"');
        $this->funcoes->set_array(11,'btn_status',$this->btns_acoes['status_btn_excluir_tudo'])
                        ->set_array(11,'btn_texto',$this->btns_acoes['txt_btn_excluir_tudo'])
                        ->set_array(11,'btn_href','javascript:void(0);')
                        ->set_array(11,'btn_title',$this->btns_acoes['title_btn_excluir_tudo'])
                        ->set_array(11,'btn_id','btn_excluir_tudo')
                        ->set_array(11,'btn_id_resp','btn_excluir_tudo2')
                        ->set_array(11,'btn_class','btn-dois btn-danger')
                        ->set_array(11,'btn_class_resp','')
                        ->set_array(11,'btn_hidden_resp','')
                        ->set_array(11,'btn_class_icon',$this->btns_acoes['class_icone_excluir_tudo'])
                        ->set_array(11,'btn_outros','param_url="'.$this->cmds['action_excluir_tudo'].'"');
        $this->funcoes->set_array(10,'btn_status',$interface['status_btn_atualizar'])
                        ->set_array(10,'btn_texto',$this->btns_acoes['txt_btn_atualizar'])
                        ->set_array(10,'btn_href',$path_parametro)
                        ->set_array(10,'btn_title',$this->btns_acoes['title_btn_atualizar'])
                        ->set_array(10,'btn_class','btn-dois pull-right btn-transparente')
                        ->set_array(10,'btn_class_resp','')
                        ->set_array(10,'btn_class_icon',$interface['class_icone_atualizar']);
        $this->funcoes->set_array(7,'btn_status',$interface['status_imprimir'])
                        ->set_array(7,'btn_texto',$this->btns_acoes['txt_btn_imprimir'])
                        ->set_array(7,'btn_href','javascript:void(0);')
                        ->set_array(7,'btn_title',$this->btns_acoes['title_btn_imprimir'])
                        ->set_array(7,'btn_class','btn-dois pull-right btn-transparente mostra_area')
                        ->set_array(7,'btn_class_resp','mostra_area')
                        ->set_array(7,'btn_class_icon',$interface['class_icone_imprimir'])
                        ->set_array(7,'btn_outros','topo="true" hide_area=".area_boxes_pers" show_area="#area_imprimir"');
        $this->funcoes->set_array(9,'btn_status',$interface['status_exportar_csv'])
                        ->set_array(9,'btn_texto',$this->btns_acoes['txt_btn_csv'])
                        ->set_array(9,'btn_href',$this->cmds['action_exp_csv'])
                        ->set_array(9,'btn_title',$this->btns_acoes['title_btn_csv'])
                        ->set_array(9,'btn_class','btn-dois pull-right btn-transparente')
                        ->set_array(9,'btn_class_resp','')
                        ->set_array(9,'btn_class_icon',$interface['class_icone_exp_csv'])
                        ->set_array(9,'btn_target','_blank') // Target do botão
                        ->set_array(9,'btn_outros','');
        $this->funcoes->set_array(8,'btn_status',$interface['status_exportar_pdf'])
                        ->set_array(8,'btn_texto',$this->btns_acoes['txt_btn_pdf'])
                        ->set_array(8,'btn_href','javascript:void(0);')
                        ->set_array(8,'btn_title',$this->btns_acoes['title_btn_pdf'])
                        ->set_array(8,'btn_class','btn-dois pull-right btn-transparente mostra_area')
                        ->set_array(8,'btn_class_resp','mostra_area')
                        ->set_array(8,'btn_class_icon',$interface['class_icone_exp_pdf'])
                        ->set_array(8,'btn_outros','topo="true" hide_area=".area_boxes_pers" show_area="#area_exp_pdf"');

        $interface['btns_opcoes_acoes'] = $this->funcoes->get_array(); // Seto o array na view [Array]

        //ICONE SALVAR
        $icon_salvar_ = $this->btns_acoes['class_icone_salvar'];
        if(empty($icon_salvar_)){
            $icon_salvar = 'fa fa-save';
        }else{
            $icon_salvar = $icon_salvar_;
        }

        //===========================================================
        //BTNS DE OPÇÕES BTNS DE AÇÕES FORMS ******
        $this->funcoes->set_array(1,'btn_status',$interface['status_btn_salvar'])
                        ->set_array(1,'btn_texto',$this->btns_acoes['txt_btn_salvar'])
                        ->set_array(1,'btn_href','javascript:void(0);')
                        ->set_array(1,'btn_title',$this->btns_acoes['title_btn_salvar'])
                        ->set_array(1,'btn_id','btn_salvar_form')
                        ->set_array(1,'btn_class','btn-dois btn-success mostra_area btn_salvar_form')
                        ->set_array(1,'btn_class_resp','btn_salvar_form')
                        ->set_array(1,'btn_class_icon',$icon_salvar)
                        ->set_array(1,'btn_outros','');
        $this->funcoes->set_array(4,'btn_status',$interface['status_btn_salvar_novo'])
                        ->set_array(4,'btn_texto',$this->btns_acoes['txt_btn_salvar_novo'])
                        ->set_array(4,'btn_href','javascript:void(0);')
                        ->set_array(4,'btn_title',$this->btns_acoes['title_btn_salvar_novo'])
                        ->set_array(4,'btn_id','btn_salvar_novo_form')
                        ->set_array(4,'btn_class','btn-dois btn-default2 btn_salvar_novo_form')
                        ->set_array(4,'btn_class_resp','btn_salvar_novo_form')
                        ->set_array(4,'btn_class_icon','fa fa-save')
                        ->set_array(4,'btn_outros','');
        $this->funcoes->set_array(3,'btn_status',$interface['status_btn_salvar_fechar'])
                        ->set_array(3,'btn_texto',$this->btns_acoes['txt_btn_salvar_fechar'])
                        ->set_array(3,'btn_href','javascript:void(0);')
                        ->set_array(3,'btn_title',$this->btns_acoes['title_btn_salvar_fechar'])
                        ->set_array(3,'btn_id','btn_salvar_fechar_form')
                        ->set_array(3,'btn_class','btn-dois btn-default2 btn_salvar_fechar_form')
                        ->set_array(3,'btn_class_resp','btn_salvar_fechar_form')
                        ->set_array(3,'btn_class_icon','fa fa-save')
                        ->set_array(3,'btn_outros','');
        $this->funcoes->set_array(2,'btn_status',$interface['status_btn_fechar'])
                        ->set_array(2,'btn_texto',$this->btns_acoes['txt_btn_fechar'])
                        ->set_array(2,'btn_href','javascript:void(0);')
                        ->set_array(2,'btn_title',$this->btns_acoes['title_btn_fechar'])
                        ->set_array(2,'btn_id','')
                        ->set_array(2,'btn_class','btn-dois btn-default2 right-force mostra_area limpo_form_add_edd')
                        ->set_array(2,'btn_class_resp','mostra_area limpo_form_add_edd')
                        ->set_array(2,'btn_class_icon','fa fa-times-circle')
                        ->set_array(2,'btn_outros','show_area="#area_listagem" show_area2="#area_btns_acoes" hide_area="#area_formulario" hide_area2="#area_btns_status_acoes_forms"');
        $this->funcoes->set_array(10,'btn_status',$interface['status_btn_atualizar'])
                        ->set_array(10,'btn_texto',$this->btns_acoes['txt_btn_atualizar'])
                        ->set_array(10,'btn_href','javascript:void(0);')
                        ->set_array(10,'btn_title',$this->btns_acoes['title_btn_atualizar'])
                        ->set_array(10,'btn_class','btn-dois pull-right btn-transparente monta_dados_form')
                        ->set_array(10,'btn_class_resp','monta_dados_form')
                        ->set_array(10,'btn_class_icon',$interface['class_icone_atualizar']);
        $interface['btns_opcoes_acoes_forms'] = $this->funcoes->get_array(); // Seto o array na view [Array]

        //===========================================================
        //BTNS DE OPÇÕES BTNS DE AÇÕES FORMS MODAL ******
        $this->funcoes->set_array(0,'btn_status',$interface['status_btn_novo'])
                        ->set_array(0,'btn_texto',$this->btns_acoes['txt_btn_salvar_novo'])
                        ->set_array(0,'btn_href','javascript:void(0);')
                        ->set_array(0,'btn_title',$this->btns_acoes['title_btn_salvar_novo'])
                        ->set_array(0,'btn_id','btn_salvar_novo_form')
                        ->set_array(0,'btn_class','btn-dois btn-success btn_salvar_novo_form')
                        ->set_array(0,'btn_class_resp','btn_salvar_novo_form')
                        ->set_array(0,'btn_class_icon','fa fa-save')
                        ->set_array(0,'btn_outros','');
        $interface['btns_opcoes_acoes_forms_modal'] = $this->funcoes->get_array(); // Seto o array na view [Array]

        //===========================================================
        //MONTO FORMS DINAMICAMENTE
        $this->monto_conteudo_html_pagina('campos_html_add_edd'); //campos de adicionar e editar
        $this->monto_conteudo_html_pagina('campos_html_exp_pdf'); //campos de exportar para pdf
        $this->monto_conteudo_html_pagina('campos_html_imprimir'); //campos de imprimir

        //===========================================================
        //ACTIONS ******
        $interface['action_autocomplete']      = $this->cmds['action_autocomplete'];
        $interface['action_combobox']          = $this->cmds['action_combobox'];
        $interface['action_changcombobox']     = $this->cmds['action_changcombobox'];
        $interface['action_set_editar']        = $this->cmds['action_set_editar'];
        $interface['action_monto_campos_form'] = $this->cmds['action_monto_campos_form'];
        $interface['action_outros_js']         = $this->cmds['action_outros_js'];
        $interface['action_add_modal']         = $this->cmds['action_add_modal'];
        $interface['action_list_arquivos']     = $this->cmds['action_list_arquivos'];
        $interface['action_excluir_arquivo']   = $this->cmds['action_excluir_arquivo'];
        $interface['action_funcao_aberta']     = $this->cmds['action_funcao_aberta'];
        $interface['action_grafico']           = $this->cmds['action_grafico'];

        //===========================================================
        //OUTROS
        $interface['foco_campo_form_add_edd'] = $this->btns_acoes['foco_campo_form'];
        $interface['status_menu_lateral']     = $this->btns_acoes['status_menu_lateral'];

    
