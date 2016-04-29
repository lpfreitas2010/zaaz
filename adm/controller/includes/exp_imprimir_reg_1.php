<?php

    //RECEBO PARAMETROS
    (string) $tipo = $this->funcoes->anti_injection($_POST['tipo']); // Recebo tipo
    if(empty($tipo)){
        (string) $tipo = $this->funcoes->anti_injection($_GET['tipo']); // Recebo tipo
    }
    $pref = '';

    //INSTANCIO A VIEW E O HELPER DE EXPORTAR
    $this->core->includeView();
    $this->core->includeInc('mpdf/mpdf.php');
    $view = new view($this->dir_app);
    $mpdf = new mPDF();

    //===========================================================
    //SETO OS DADOS
    $this->model->setCampos('order_by',$_SESSION[$pref.$this->url_pagina.'_campo_ordenar']);
    $this->model->setCampos('group_by',$_SESSION[$pref.$this->url_pagina.'_campo_agrupar']);
    $this->model->setCampos('query_pesquisa',$_SESSION[$pref.$this->url_pagina.'_param_pesquisa']);
    $this->model->setCampos('query_pesquisa_avancada',$_SESSION[$pref.$this->url_pagina.'_pesquisa_avancada']);

    //===========================================================
    //EXECUTO
    $array1          = $this->model->listagem('true'); // Array sem limit
    $total_registros = count($array1); // Total de registros
    $this->model->getLimpoCampos(); // Limpo campos

    //===========================================================
    //IMPRIMIR
    if($tipo == 'imprimir'){
        if($this->btns_acoes['status_btn_imprimir'] === false){ // Verifico se tem permissão de acesso a função
            $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
            ->setMensagem($this->core->get_msg_array('funcao_acesso_negado','imprimir()'))->gravo_log(); // Gravo log
            exit();
        }

        //===========================================================
        //RECEBO PARAMETROS E VERIFICO
        $campos_selecionados = $this->funcoes->anti_injection($_POST['campos_imprimir']); //Recebo campos para montagem

        //EXECUTO
        if(count($array1)>=1){

            //SETO PARAMETROS GERAIS
            $interface['order_by']        = $_SESSION[$this->url_pagina.'_txt_ordenar_ativo']; //TXT ORDENAR ATIVO
            $interface['group_by']        = $_SESSION[$this->url_pagina.'_campo_agrupar']; //TXT AGRUPAR ATIVO
            $interface['count_group_by']  = count($_SESSION[$this->url_pagina.'_campo_agrupar']); //COUNT AGRUPAR ATIVO
            $interface['query_pesquisa']  = $_SESSION[$this->url_pagina.'_param_pesquisa']; //PARAM PESQUISA
            $interface['total_registros'] = $total_registros; //TOTAL DE REGISTROS
            $interface['array']           = $array1; //ARRAY
            $interface['campos']          = $campos_selecionados; //CAMPOS SELECIONADOS
            $interface['titulo']          = $this->nome_pagina_plural; //TITULO
            $interface['path']            = $this->core->get_config('path_template_comp_' . $this->dir_app.'_apps'); //Diretório raiz até assets
            $interface['path_raiz']       = $this->core->get_config('dir_raiz_http').'/'; //Diretório raiz
            $interface['path_atual']      = $this->core->get_config('dir_raiz_http').$this->dir_app."/";
            $interface['id_cargo']        = $_SESSION[$this->dir_app.'_id_cargo'];

            //SETO OS DADOS
            $view->seto_dados_array($interface);

            //CARREGO LISTAGEM PARA IMPRESSÃO
            echo $view->retorno_template_php('modulos/geral/imprimir/imprimir_'.$this->url_pagina.'.phtml');

        }else{
            echo "Nenhuma informação encontrada para: ".$_SESSION[$pref.$this->url_pagina.'_param_pesquisa'];
        }
    }

    //===========================================================
    //EXPORTAR PARA PDF
    if($tipo == 'pdf'){
        if($this->btns_acoes['status_btn_pdf'] === false){ // Verifico se tem permissão de acesso a função
            $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
            ->setMensagem($this->core->get_msg_array('funcao_acesso_negado','pdf()'))->gravo_log(); // Gravo log
            exit();
        }

        //===========================================================
        //RECEBO PARAMETROS E VERIFICO
        $campos_selecionados = $this->funcoes->anti_injection($_POST['campos_pdf']); //Recebo campos para montagem

        //EXECUTO
        if(count($array1)>=1){

            //SETO PARAMETROS GERAIS
            $interface['order_by']        = $_SESSION[$this->url_pagina.'_txt_ordenar_ativo']; //TXT ORDENAR ATIVO
            $interface['group_by']        = $_SESSION[$this->url_pagina.'_campo_agrupar']; //TXT AGRUPAR ATIVO
            $interface['count_group_by']  = count($_SESSION[$this->url_pagina.'_campo_agrupar']); //COUNT AGRUPAR ATIVO
            $interface['query_pesquisa']  = $_SESSION[$this->url_pagina.'_param_pesquisa']; //PARAM PESQUISA
            $interface['total_registros'] = $total_registros; //TOTAL DE REGISTROS
            $interface['array']           = $array1; //ARRAY
            $interface['campos']          = $campos_selecionados; //CAMPOS SELECIONADOS
            $interface['titulo']          = $this->nome_pagina_plural; //TITULO

            //SETO OS DADOS
            $view->seto_dados_array($interface);

            //COMANDO PARA EXPORTAR PDF
            $mpdf->SetDisplayMode('fullpage');
			$css = file_get_contents('');
			$css .= file_get_contents('../view/assets/css/AdminLTE.css');
			$css .= file_get_contents('../view/assets/css/pdf.css');
			$css .= file_get_contents('../view/assets/css/font-awesome.css');
			$css .= file_get_contents('../view/assets/css/ionicons.css');
			$mpdf->WriteHTML($css,1);
            $mpdf->WriteHTML($view->retorno_template_php('modulos/geral/pdf/pdf_'.$this->url_pagina.'.phtml'));
            $mpdf->Output();
        }else{
            echo "Nenhuma informação encontrada para: ".$_SESSION[$pref.$this->url_pagina.'_param_pesquisa'];
        }
    }

    //===========================================================
    //EXPORTAR PARA CSV
    if($tipo == 'csv'){
        if($this->btns_acoes['status_btn_csv'] === false){ // Verifico se tem permissão de acesso a função
            $this->logs->setApp($this->dir_app)->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'])->setPagina(end(explode("/", $_SERVER['PHP_SELF'])))
            ->setMensagem($this->core->get_msg_array('funcao_acesso_negado','csv()'))->gravo_log(); // Gravo log
            exit();
        }

        //EXECUTO
        if(count($array1)>=1){
            echo $this->funcoes->exp_array_para_csv($array1,$this->url_pagina.'_'.date('d-m-Y-H-i-s').'.csv');
        }
    }
