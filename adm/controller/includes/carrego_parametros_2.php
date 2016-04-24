<?php

    //===========================================================
    //INSTANCIO CLASSES
    $this->core->includeControllerView('core',$this->dir_app); //Incluo arquivo coreController.php
    $controller_geral_ = new coreController();

    //===========================================================
    //VERIFICO RESTRIÇÕES
    (array) $restricoes = $controller_geral_->verifico_restricoes_sistema($this->adm_usuario_modulo_id);

    //===========================================================
    //SETO NOVOS VALORES DE RESTRIÇÕES
    if(count($restricoes)>=1){
        for ($i=0; $i <count($restricoes) ; $i++) {

            //NOVO
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 1){
                $status_btn_novo    = $this->btns_acoes['status_btn_novo'];
                $status_btn_novo_bd = (boolean) $restricoes[$i]['opcoes'];
                if($status_btn_novo == 0){
                    $this->btns_acoes['status_btn_novo'] = $status_btn_novo;
                }else{
                    $this->btns_acoes['status_btn_novo'] = $status_btn_novo_bd;
                }
            }
            //EDITAR
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 2){
                $status_btn_editar    = $this->btns_acoes['status_btn_editar'];
                $status_btn_editar_bd = (boolean) $restricoes[$i]['opcoes'];
                if($status_btn_editar == 0){
                    $this->btns_acoes['status_btn_editar'] = $status_btn_editar;
                }else{
                    $this->btns_acoes['status_btn_editar'] = $status_btn_editar_bd;
                }
            }
            //EXCLUIR
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 3){
                $status_btn_excluir    = $this->btns_acoes['status_btn_excluir'];
                $status_btn_excluir_bd = (boolean) $restricoes[$i]['opcoes'];
                if($status_btn_excluir == 0){
                    $this->btns_acoes['status_btn_excluir'] = $status_btn_excluir;
                }else{
                    $this->btns_acoes['status_btn_excluir'] = $status_btn_excluir_bd;
                }
            }
            //DETALHO
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 4){
                $status_btn_detalhamento    = $this->btns_acoes['status_btn_detalhamento'];
                $status_btn_detalhamento_bd = (boolean) $restricoes[$i]['opcoes'];
                if($status_btn_detalhamento == 0){
                    $this->btns_acoes['status_btn_detalhamento'] = $status_btn_detalhamento;
                }else{
                    $this->btns_acoes['status_btn_detalhamento'] = $status_btn_detalhamento_bd;
                }
            }
            //ATIVAR
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 5){
                $status_btn_ativar    = $this->btns_acoes['status_btn_ativar'];
                $status_btn_ativar_bd = (boolean) $restricoes[$i]['opcoes'];
                if($status_btn_ativar == 0){
                    $this->btns_acoes['status_btn_ativar'] = $status_btn_ativar;
                }else{
                    $this->btns_acoes['status_btn_ativar'] = $status_btn_ativar_bd;
                }
            }
            //EXPORTAR PDF
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 6){
                $status_exportar_pdf    = $this->btns_acoes['status_exportar_pdf'];
                $status_exportar_pdf_bd = (boolean) $restricoes[$i]['opcoes'];
                if($status_exportar_pdf == 0){
                    $this->btns_acoes['status_exportar_pdf'] = $status_exportar_pdf;
                }else{
                    $this->btns_acoes['status_exportar_pdf'] = $status_exportar_pdf_bd;
                }
            }
            //EXPORTAR CSV
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 7){
                $status_exportar_csv    = $this->btns_acoes['status_exportar_csv'];
                $status_exportar_csv_bd = (boolean) $restricoes[$i]['opcoes'];
                if($status_exportar_csv == 0){
                    $this->btns_acoes['status_exportar_csv'] = $status_exportar_csv;
                }else{
                    $this->btns_acoes['status_exportar_csv'] = $status_exportar_csv_bd;
                }
            }
            //EXPORTAR XLS
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 8){

            }
            //IMPRIMIR
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 9){
                $status_imprimir    = $this->btns_acoes['status_imprimir'];
                $status_imprimir_bd = (boolean) $restricoes[$i]['opcoes'];
                if($status_imprimir == 0){
                    $this->btns_acoes['status_imprimir'] = $status_imprimir;
                }else{
                    $this->btns_acoes['status_imprimir'] = $status_imprimir_bd;
                }
            }
            //EXCLUIR TUDO
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 16){
                $status_btn_excluir_tudo    = $this->btns_acoes['status_btn_excluir_tudo'];
                $status_btn_excluir_tudo_bd = (boolean) $restricoes[$i]['opcoes'];
                if($status_btn_excluir_tudo == 0){
                    $this->btns_acoes['status_btn_excluir_tudo'] = $status_btn_excluir_tudo;
                }else{
                    $this->btns_acoes['status_btn_excluir_tudo'] = $status_btn_excluir_tudo_bd;
                }
            }
            //PESQUISAR
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 10){
                 $status_area_pesquisa    = $this->btns_acoes['status_area_pesquisa'];
                 $status_area_pesquisa_bd = (boolean) $restricoes[$i]['opcoes'];
                 if($status_area_pesquisa == 0){
                     $this->btns_acoes['status_area_pesquisa'] = $status_area_pesquisa;
                 }else{
                     $this->btns_acoes['status_area_pesquisa'] = $status_area_pesquisa_bd;
                 }
            }
            //PESQUISA AVANÇADA
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 11){
                 $status_acao_pesquisa_avancada    = $this->btns_acoes['status_acao_pesquisa_avancada'];
                 $status_acao_pesquisa_avancada_bd = (boolean) $restricoes[$i]['opcoes'];
                 if($status_acao_pesquisa_avancada == 0){
                     $this->btns_acoes['status_acao_pesquisa_avancada'] = $status_acao_pesquisa_avancada;
                 }else{
                     $this->btns_acoes['status_acao_pesquisa_avancada'] = $status_acao_pesquisa_avancada_bd;
                 }
            }
            //ORDENAR
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 12){
                 $status_area_ordenacao    = $this->btns_acoes['status_area_ordenacao'];
                 $status_area_ordenacao_bd = (boolean) $restricoes[$i]['opcoes'];
                 if($status_area_ordenacao == 0){
                     $this->btns_acoes['status_area_ordenacao'] = $status_area_ordenacao;
                 }else{
                     $this->btns_acoes['status_area_ordenacao'] = $status_area_ordenacao_bd;
                 }
            }
            //AGRUPAR
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 13){
                 $status_area_agrupar    = $this->btns_acoes['status_area_agrupar'];
                 $status_area_agrupar_bd = (boolean) $restricoes[$i]['opcoes'];
                 if($status_area_agrupar == 0){
                     $this->btns_acoes['status_area_agrupar'] = $status_area_agrupar;
                 }else{
                     $this->btns_acoes['status_area_agrupar'] = $status_area_agrupar_bd;
                 }
            }
            //AREA PAGINCACAO
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 14){
                 $status_area_paginacao    = $this->btns_acoes['status_area_paginacao'];
                 $status_area_paginacao_bd = (boolean) $restricoes[$i]['opcoes'];
                 if($status_area_paginacao == 0){
                     $this->btns_acoes['status_area_paginacao'] = $status_area_paginacao;
                 }else{
                     $this->btns_acoes['status_area_paginacao'] = $status_area_paginacao_bd;
                 }
            }
            //AREA TOTAL REGISTROS
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 15){
                 $status_area_total_registros    = $this->btns_acoes['status_area_total_registros'];
                 $status_area_total_registros_bd = (boolean) $restricoes[$i]['opcoes'];
                 if($status_area_total_registros == 0){
                     $this->btns_acoes['status_area_total_registros'] = $status_area_total_registros;
                 }else{
                     $this->btns_acoes['status_area_total_registros'] = $status_area_total_registros_bd;
                 }
            }
            //LISTAGEM
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 18){
                $area_listagem_geral    = $this->btns_acoes['area_listagem_geral'];
                $area_listagem_geral_bd = (boolean) $restricoes[$i]['opcoes'];
                if($area_listagem_geral == 0){
                    $this->btns_acoes['area_listagem_geral'] = $area_listagem_geral;
                }else{
                    $this->btns_acoes['area_listagem_geral'] = $area_listagem_geral_bd;
                }
            }
            //GRAFICO
            if($restricoes[$i]['adm_usuario_permissoes_acoes_id'] == 19){
                $area_listagem_geral    = $this->btns_acoes['status_area_grafico'];
                $area_listagem_geral_bd = (boolean) $restricoes[$i]['opcoes'];
                if($area_listagem_geral == 0){
                    $this->btns_acoes['status_area_grafico'] = $area_listagem_geral;
                }else{
                    $this->btns_acoes['status_area_grafico'] = $area_listagem_geral_bd;
                }
            }

        }
    }
