<?php

    //===========================================================
    //SETO OS DADOS
    $inicio = ($qtd_reg_listagem * $page) - $qtd_reg_listagem; // Configuração de paginação
    $this->model->setCampos('inicio',$inicio);
    $this->model->setCampos('quantidade',$qtd_reg_listagem);

    //===========================================================
    //PEGO OS DADOS DO BANCO DE DADOS
    $array           = $this->model->$funcao_listagem(); // Array com limit
    $array1          = $this->model->$funcao_listagem('true'); // Array sem limit
    $total_registros = count($array1); // Total de registros
    $qtd_paginas     = ceil($total_registros / $qtd_reg_listagem); // Quantidade de páginas
    $this->model->getLimpoCampos(); // Limpo campos

    //===========================================================
    //VERIFICO SE PÁGINA EXISTE VOLTO PARA PÁGINA 1
    if(empty($interface['param_pesquisa'])){
       if(($page > $qtd_paginas) || ($page <= 0)){

           //===========================================================
           //SETO OS DADOS
           $page   = 1; // Página padrão
           $inicio = ($qtd_reg_listagem * $page) - $qtd_reg_listagem; // Configuração de paginação
           $this->model->setCampos('inicio',$inicio);
           $this->model->setCampos('quantidade',$qtd_reg_listagem);

           //===========================================================
           //PEGO OS DADOS DO BANCO DE DADOS
           $array           = $this->model->$funcao_listagem(); // Array com limit
           $array1          = $this->model->$funcao_listagem('true'); // Array sem limit
           $total_registros = count($array1); // Total de registros
           $qtd_paginas     = ceil($total_registros / $qtd_reg_listagem); // Quantidade de páginas
           $this->model->getLimpoCampos(); // Limpo campos
       }
    }

    //===========================================================
    //SETO OS DADOS NA VIEW
    if($limit == true){
        $interface['conteudo_tabela'] = $array; // Array com o conteúdo da tabela [Array]
    }else{
        $interface['conteudo_tabela'] = $array1; // Array com o conteúdo da tabela [Array]
    }
    $interface['total_reg']        = $total_registros; // Total de registros da tabela
    $interface['page']             = $page; // Número da página atual
    $interface['qtd_reg_listagem'] = $qtd_reg_listagem; // Quantidade de registros da página
    $interface['qtd_paginas']      = $qtd_paginas; // Quantidade de páginas
    $interface['id_usuario_ativo'] = $_SESSION[$this->dir_app.'_id_user']; // Id do usuário ativo no sistema
    $interface['path']             = $this->core->get_config('path_template_comp_'.$this->dir_app); // Caminho completo até a pasta ASSETS
    $interface['path_raiz']        = $this->core->get_config('dir_raiz_http'); // Caminho até a raiz da aplicação
    $interface['paginas']          = $this->config_apps->getPaginas_array(); // Array com as páginas da aplicação [Array]
    $interface['pagina_ativa']     = $this->url_pagina; // Página ativa

    //===========================================================
    //VERIFICO SE RECEBI PARAMETRO DE PESQUISA AVANÇADA
    if($_SESSION[$pref.$this->url_pagina.'_param_pesquisa'] == "Pesquisa Avançada"){
        $interface['status_area_pesquisa_avancada']          = true;
        $interface['status_area_pesquisa_avancada_conteudo'] = false;
        $interface['status_area_geral_pesquisa']             = true;
    }

    //===========================================================
    //VERIFICO SE PESQUISA AVANÇADA ESTA ATIVA NA LISTAGEM
    if(isset($_SESSION[$pref.$this->url_pagina.'_param_pesquisa_avancada'])){
        if($interface['status_acao_pesquisa_avancada'] == true){
            $interface['status_area_pesquisa_avancada']          = true;
            $interface['status_area_pesquisa_avancada_conteudo'] = false;
            $interface['status_area_geral_pesquisa']             = true;
        }
    }

   //===========================================================
    //VERIFICO SE NÃO TIVER NENHUM FILTRO OCULTO FILTRO
    if(empty($interface['dropdown_filtros'])){
         $interface['status_btn_filtros'] = false; // Status do botão de filtros [true or false]
    }

    //===========================================================
    //VERIFICO SE STATUS DE AÇÕES FOREM TODOS FALSE OCULTO COLUNA DE AÇÕES E CHECKBOX
    if($this->btns_acoes['status_btn_editar'] == false && $this->btns_acoes['status_btn_excluir'] == false && $this->btns_acoes['status_btn_detalhamento'] == false && $this->btns_acoes['status_btn_ativar'] == false){
        $interface['status_col_acoes']    = false; // Status da coluna de ações [true or false]
        $interface['status_col_checkbox'] = false; // Status da coluna do ckeckbox [true or false]
    }

    //===========================================================
    //REMONTO O RODENAR DE ACORDO COM O ARRAY
    $ordenar = $interface['ordenar'];
    for ($i=0; $i <count($ordenar) ; $i++) {
        $exp_ob = explode('_ord_',$ordenar[$i]);
        $string = $this->funcoes->substituo_strings('criado','Data Cadastro',$exp_ob[0]);
        $string = $this->funcoes->substituo_strings('modificado','Data Modificado',$string);
        $ordenar_[] = ' '.ucfirst(strtolower($string)).' '.$exp_ob[1].'';
        $_SESSION[$this->url_pagina.'_txt_ordenar_ativo'] = $ordenar_;
    }
    $interface['option_ordenar_selected'] = $ordenar_;

    //===========================================================
    //MONTO O MENU DE AÇÕES
    $interface['status_btn_editar']        = $this->btns_acoes['status_btn_editar'];
    $interface['status_btn_excluir']       = $this->btns_acoes['status_btn_excluir'];
    $interface['status_btn_detalhamento']  = $this->btns_acoes['status_btn_detalhamento'];
    $interface['status_btn_ativar']        = $this->btns_acoes['status_btn_ativar'];
    $interface['txt_btn_editar']           = $this->btns_acoes['txt_btn_editar'];
    $interface['txt_btn_excluir']          = $this->btns_acoes['txt_btn_excluir'];
    $interface['txt_btn_detalhamento']     = $this->btns_acoes['txt_btn_detalhamento'];
    $interface['txt_btn_ativar']           = $this->btns_acoes['txt_btn_ativar'];
    $interface['txt_btn_desativar']        = $this->btns_acoes['txt_btn_desativar'];
    $interface['class_icone_editar']       = $this->btns_acoes['class_icone_editar'];
    $interface['class_icone_excluir']      = $this->btns_acoes['class_icone_excluir'];
    $interface['class_icone_detalhamento'] = $this->btns_acoes['class_icone_detalhamento'];
    $interface['class_icone_ativar']       = $this->btns_acoes['class_icone_ativar'];
    $interface['class_icone_desativar']    = $this->btns_acoes['class_icone_desativar'];
    $interface['title_btn_editar']         = $this->btns_acoes['title_btn_editar'];
    $interface['title_btn_excluir']        = $this->btns_acoes['title_btn_excluir'];
    $interface['title_btn_detalhamento']   = $this->btns_acoes['title_btn_detalhamento'];
    $interface['title_btn_ativar']         = $this->btns_acoes['title_btn_ativar'];
    $interface['title_btn_desativar']      = $this->btns_acoes['title_btn_desativar'];
    $interface['title_btn_atualizar']      = $this->btns_acoes['title_btn_atualizar'];

    //===========================================================
    //ARRAY COM AS QUANTIDADE DE REGISTROS POR PÁGINA
    $this->funcoes->set_array(null,10,10)
                  ->set_array(null,20,20)
                  ->set_array(null,35,35)
                  ->set_array(null,60,60)
                  ->set_array(null,80,80)
                  ->set_array(null,100,100)
                  ->set_array(null,180,180)
                  ->set_array(null,320,320)
                  ->set_array(null,480,480)
                  ->set_array(null,800,800)
                  ->set_array(null,1200,1200)
                  ->set_array(null,5000,5000);
    $interface['option_qtd_reg_listagem'] = $this->funcoes->get_array(); // Seto o array na view [Array]

    //===========================================================
    //CRIO UM ARQUIVO.PHTML COM OS TDS DA TABELA E PESQUISA AVANÇADA
    $this->funcoes->crio_arquivo_phtml($this->core->get_config('dir_view_template_'.$this->dir_app.'_comp').'modulos/'.$this->config_apps->get_config('modulos',0).'/includes/listagens/listagens_'.$this->url_pagina,$conteudo_td_montado);
    $this->monto_conteudo_html_pagina('campos_html_pesq_avancada'); //campos de pesquisa avançada

    //===========================================================
    //INSTANCIO CLASSES
    $this->core->includeControllerView('core',$this->dir_app); //Incluo arquivo coreController.php
    $controller_geral_ = new coreController();

    //===========================================================
    //SETO OS DADOS DO ARRAY
    $view->seto_dados_array($interface);

    //===========================================================
    //MOSTRO INFORMAÇÕES NA TELA (JSON)
    $this->funcoes->gera_saida_json($view->retorno_template_php('modulos/'.$this->config_apps->get_config('modulos',0).'/includes/listagem.phtml'));
