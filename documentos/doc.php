<?php

//***************************************************************************************************************************
//CONTROLLER - LISTAGEM - MONTAGEM HTML DA LISTAGEM DE REGISTROS - mont_htmlHelper.php
//***************************************************************************************************************************

    //CONDIÇÕES IF ELSE SMARTY
    $mont_html->set_array(0,'condicoes',""); // condições do if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'cod_if',""); // código do bloco if - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'cod_else',""); // código do bloco else se tiver - aceita campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $resultado = $mont_html->if_else_smarty();

    //FILTROS SMARTY PHP
    $mont_html->set_array(0,'tipo',""); // data, hora, hora_min, data_hora, idade, dia_semana, dia_semana_hoje, mes, data_extenso, tempo, tempo_hora, enc_texto, maiusculo, minusculo, maiusc_minusc e format_num
    $mont_html->set_array(0,'campo',""); // campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'valor_enc_texto',""); // valor numérico do tamanho do texto
    $filtro = $mont_html->filtros_smarty();

    //MONTO O HTML TEXTO
    $mont_html->set_array(0,'texto',""); // texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'class_icone',""); // classe do icone - Font Awesome Icons
    $texto = $mont_html->monto_html_texto();

    //MONTO HTML DE UMA IMAGEM
    $mont_html->set_array(0,'src',""); // $this->core->get_config('dir_raiz_http').'files/'; texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'alt',""); // texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'class',"");
    $mont_html->set_array(0,'width',""); // valor numérico do tamanho do da imagem
    $mont_html->set_array(0,'height',""); // valor numérico do tamanho do da imagem
    $mont_html->set_array(0,'lightbox',false); // lightbox true ou false
    $imagem = $mont_html->monto_html_img();

    //MONTO HMTL DE UM LINK
    $mont_html->set_array(0,'texto',""); // texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'title',""); // texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'href',""); // href do link, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'target',""); // _blank
    $mont_html->set_array(0,'class',""); // classes do link
    $mont_html->set_array(0,'class_icone',""); // classe do icone - Font Awesome Icons
    $mont_html->set_array(0,'outros',''); // outros parametros do link
    $link = $mont_html->monto_html_link();

    //MONTO HTML DE UM LABEL COLORIDO
    $mont_html->set_array(0,'texto',""); // texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'class',""); // classe do label - label-primary, label-success, label-info, label-warning, label-danger
    $mont_html->set_array(0,'class_icone',""); // classe do icone - Font Awesome Icons
    $label = $mont_html->monto_html_label_color();

    //AÇÃO DE LINHA DA TABELA
    $mont_html->set_array(0,'status',true); // Status da ação true or false
    $mont_html->set_array(0,'class',""); // Tipo: btn_ativar_desativar, btn_detalhamento, btn_editar, url ou livre
    $mont_html->set_array(0,'url',""); // url de redirecionamento $this->core->get_config('dir_raiz_http').$this->dir_app."/"
    $mont_html->set_array(0,'outros',"");
    $mont_html->set_array(0,'campo_ativar',""); //outros campos ativar e desativar
    $mont_html->set_array(0,'status_btn_detalhamento',$this->btns_acoes['status_btn_detalhamento']); // Status ação detalhamento true or false
    $mont_html->set_array(0,'status_btn_editar',$this->btns_acoes['status_btn_editar']); // Status ação editar true or false
    $mont_html->set_array(0,'status_btn_ativar',$this->btns_acoes['status_btn_ativar']); // Status ação ativar e desativar true or false
    $mont_html->set_array(0,'url_btn_ativar',$this->cmds['action_ativar']); // Action do btn ativar
    $acao_linha = $mont_html->acao_linha_smarty();

    //MONTAR CMD DO CONTROLLER - AÇÃO LINHA DA TABELA (cmd_funcao_aberta) COM PARAMETROS LIVRES ** Coloque os parametros em (outros) e coloque a classe (funcao_aberta) que chama o controller
    // param="&id=1&...." title_msg="Favoritar" title_text="Marcar como favorito!"
    //

    //CONTEUDO MONTADO
    $mont_html->set_array(0,'td',"   "); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(0,'class'," {$acao_linha['class']}"); // Classes
    $mont_html->set_array(0,'outros'," {$acao_linha['outros']}");
    $mont_html->set_array(1,'td',"  "); // Coluna montada ****** texto, campo do banco [bd:campo] ou variavel smarty [sm:variavel]
    $mont_html->set_array(1,'class'," {$acao_linha['class']}"); // Classes
    $mont_html->set_array(1,'outros'," {$acao_linha['outros']}");
    $conteudo_td_montado = $mont_html->monto_html_td();


//***************************************************************************************************************************
//CONTROLLER - FORMS - MONTAGEM HTML DOS CAMPOS DO FORMULÁRIO - mont_htmlHelper.php
//***************************************************************************************************************************

    //MONTO CAMPO INPUT TEXT
    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
    $mont_html->set_array(null,'label_for_texto','');
    $mont_html->set_array(null,'input_type','text'); //text, password
    $mont_html->set_array(null,'input_name','');
    $mont_html->set_array(null,'input_id','');
    $mont_html->set_array(null,'input_maxlength',''); //Quantidade de caracteres do campo
    $mont_html->set_array(null,'input_class',''); //[MASCARAS: (input-mask) (telefone) (money) (money2) ] [NÚMEROS use: (numeros)] [CALENDÁRIO: (date-picker) (time-picker) ]
    $mont_html->set_array(null,'input_placeholder','');
    $mont_html->set_array(null,'input_title','');
    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
    $mont_html->set_array(null,'input_value','');
    $mont_html->set_array(null,'input_outros',''); // [MASCARA: (data-mask="000.000.000-00")]
    $mont_html->set_array(null,'input_disabled',''); //disabled
    $mont_html->set_array(null,'icon_in_input',''); //CLASSE ICONE DENTRO DO INPUT - glyphicon icons
    $mont_html->set_array(null,'input_pagina_icon',''); //CLASSE DO ICONE DA OUTRA PÁGINA - Font Awesome Icons
    $mont_html->set_array(null,'input_pagina_title',''); //TITLE DA OUTRA PÁGINA
    $mont_html->set_array(null,'input_pagina_url',$this->core->get_config('dir_raiz_http').$this->dir_app.'/ '); //URL DA OUTRA PÁINGA
    $mont_html->set_array(null,'input_pagina_add_title','Adicionar '); //TITLE DO BTN DE ADICIONAR
    $mont_html->set_array(null,'input_pagina_add_id',' '); //ID QUE CHAMA O MODAL DE ADICIONAR
    $input_text = $mont_html->monto_html_input();

    //MONTO CAMPO INPUT HIDDEN
    $mont_html->set_array(null,'input_name','');
    $mont_html->set_array(null,'input_id','');
    $mont_html->set_array(null,'input_value','');
    $input_hidden = $mont_html->monto_html_input_hidden();

    //MONTO CAMPO INPUT FILE
    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
    $mont_html->set_array(null,'input_multiple',true); //true ou false
    $mont_html->set_array(null,'label_for_texto','');
    $mont_html->set_array(null,'input_name','');
    $mont_html->set_array(null,'input_id','');
    $mont_html->set_array(null,'input_class','');
    $mont_html->set_array(null,'input_placeholder','');
    $mont_html->set_array(null,'title_help','FORMATOS: <b>(jpg,png)</b> <br /> DIMENSÕES: <b>00 x 00px</b> <br /> TAMANHO: <b>Máx. 2 MB p/ arquivo</b> ');
    $mont_html->set_array(null,'input_value','');
    $mont_html->set_array(null,'input_outros','');
    $mont_html->set_array(null,'editor_img_online',true); // Editor de imagens online - true ou false
    $mont_html->set_array(null,'input_disabled',''); //disabled
    $input_file = $mont_html->monto_html_input_file();

    //MONTO CAMPO DE LISTAGEM DE ARQUIVOS *** MÁXIMO 4 ÁREAS POR PÁGINA
    $mont_html->set_array(null,'status_listagem_arquivos',true); //STATUS LISTAGEM DE ARQUIVOS - true ou false
    $mont_html->set_array(null,'param_controle_list_arq','1'); //máximo 4 - 1 a 4 - incremente o valor de acordo com a qtd de área de listagens de arquivos
    $mont_html->set_array(null,'upload_multiplo',true); //UPLOAD MULTIPLO - true ou false
    $mont_html->set_array(null,'tipo_listagem_arquivos','imagem'); //TIPO - imagem ou arquivo
    $mont_html->set_array(null,'titulo_listagem_arquivos','Listagem de '); //TITULO DA LISTAGEM - imagem ou arquivo
    $mont_html->set_array(null,'icone_doc','fa fa-file-text'); //ICONE DA LISTAGEM DOCUMENTOS - Font Awesome Icons
    $mont_html->set_array(null,'icone_img','fa fa-picture-o'); //TITULO DA LISTAGEM IMAGENS - Font Awesome Icons
    $mont_html->set_array(null,'campo_input_file','teste'); //ID DO CAMPO INPUT FILE
    $mont_html->set_array(null,'url_listagem_arquivos','teste'); // PARAMETRO TIPO - LISTAGEM DE ARQUIVOS
    $listagem_arquivos = $mont_html->monto_html_list_arquivos();

    //MONTO VALORES DO CHECKBOX
    $this->funcoes->set_array(0,'input_id','');
    $this->funcoes->set_array(0,'input_name','');
    $this->funcoes->set_array(0,'input_class','');
    $this->funcoes->set_array(0,'input_title','');
    $this->funcoes->set_array(0,'input_value',''); //value do campo
    $this->funcoes->set_array(0,'input_checked',''); //checked ou ""
    $this->funcoes->set_array(0,'input_texto_checkbox',''); //Texto
    $valores_checkbox = $this->funcoes->get_array();

    //MONTO CAMPO INPUT CHECKBOX
    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
    $mont_html->set_array(null,'input_id',''); // id da area
    $mont_html->set_array(null,'label_for_texto','');
    $mont_html->set_array(null,'input_options',$valores_checkbox);
    $input_checkbox = $mont_html->monto_html_input_checkbox();

    //MONTO VALORES DO RADIOBOX
    $this->funcoes->set_array(0,'input_id','');
    $this->funcoes->set_array(0,'input_name',''); //mesmo name
    $this->funcoes->set_array(0,'input_class','');
    $this->funcoes->set_array(0,'input_title','');
    $this->funcoes->set_array(0,'input_value',''); //value do campo
    $this->funcoes->set_array(0,'input_checked',''); //checked ou ""
    $this->funcoes->set_array(0,'input_texto_radiobox',''); //Texto
    $valores_radio = $this->funcoes->get_array();

    //MONTO CAMPO INPUT RADIOBOX
    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
    $mont_html->set_array(null,'input_id',''); // id da area
    $mont_html->set_array(null,'label_for_texto','');
    $mont_html->set_array(null,'input_options',$valores_radio);
    $input_radiobox = $mont_html->monto_html_input_radiobox();

    //MONTO CAMPO TEXTAREA
    $mont_html->set_array(null,'input_class_tamanho','col-lg-12 col-md-12 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
    $mont_html->set_array(null,'label_for_texto','');
    $mont_html->set_array(null,'input_name','');
    $mont_html->set_array(null,'input_id','');
    $mont_html->set_array(null,'input_class',''); //editor html = (editor_html_completo, editor_html_basico)
    $mont_html->set_array(null,'input_placeholder','');
    $mont_html->set_array(null,'input_title','');
    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
    $mont_html->set_array(null,'input_value','');
    $mont_html->set_array(null,'input_outros','');
    $mont_html->set_array(null,'input_disabled',''); //disabled
    $mont_html->set_array(null,'input_rows',''); //número de linhas
    $textarea = $mont_html->monto_html_textarea();

    //MONTO CAMPO TEXTAREA INLINE - USADO COM O EDITOR HTML
    $mont_html->set_array(null,'input_class_tamanho','col-lg-12 col-md-12 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
    $mont_html->set_array(null,'label_for_texto','');
    $mont_html->set_array(null,'input_id','');
    $mont_html->set_array(null,'input_class',''); //editor html = (editor_html_inline)
    $mont_html->set_array(null,'input_title','');
    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
    $mont_html->set_array(null,'input_value','');
    $mont_html->set_array(null,'input_outros','');
    $mont_html->set_array(null,'input_disabled',''); //disabled
    $textarea_inline = $mont_html->monto_html_textarea_inline();

    //VALORES SELECT BANCO DE DADOS ** não funciona o adicionar dinamicamente
    $this->model->setCampos('campo_tabela',"tabela");
    $this->model->setCampos('campo_coluna',"id");
    $this->model->setCampos('campo_coluna2',"campo");
    $this->model->setCampos('campo_where',"");
    $this->model->setCampos('campo_orderby',"campo ASC");
    $valor = $this->model->select_simples_retorna_array_mont_vcol();
    for ($i=0; $i < count($valor) ; $i++) {
        $this->funcoes->set_array($i,'id',$valor[$i]['id'])->set_array($i,'value',$this->funcoes->conv_string($valor[$i]['campo'],2));
    }
    $valores_select = $this->funcoes->get_array();
    $this->model->getLimpoCampos();

    //VALORES SELECT ESTÁTICO
    $this->funcoes->set_array(0,'id','')->set_array(0,'value','');
    $this->funcoes->set_array(1,'id','')->set_array(1,'value','');
    $valores_select = $this->funcoes->get_array();

    //MONTO CAMPO SELECT
    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
    $mont_html->set_array(null,'label_for_texto','');
    $mont_html->set_array(null,'input_text_select','Selecione o campo ');
    $mont_html->set_array(null,'input_name','');
    $mont_html->set_array(null,'input_id','');
    $mont_html->set_array(null,'input_class',''); // mostro área item selecionado: (select_chang_show_hide) - select multiple: (select_multiple) - select personalizado: (select_personalizado estatico) ou (select_personalizado dinamico)
    $mont_html->set_array(null,'input_title','');
    $mont_html->set_array(null,'title_help',''); //Ajuda Tags HTML ativadas
    $mont_html->set_array(null,'input_options',$valores_select); //Array com os valores do select
    $mont_html->set_array(null,'input_value','');
    $mont_html->set_array(null,'input_outros','');
    $mont_html->set_array(null,'input_disabled',''); //disabled
    $mont_html->set_array(null,'input_multiple',''); //SELECT MULTIPLO - multiple ou ""
    $mont_html->set_array(null,'input_pagina_title','Ir para a listagem de  '); //TITLE DO BTN DE LISTAGEM
    $mont_html->set_array(null,'input_pagina_url',$this->core->get_config('dir_raiz_http').$this->dir_app.'/  '); //URL BTN DE LISTAGEM
    $mont_html->set_array(null,'input_pagina_add_title','Adicionar '); //TITLE DO BTN DE ADICIONAR
    $mont_html->set_array(null,'input_pagina_add_id',' '); //ID QUE CHAMA O MODAL DE ADICIONAR
    $select = $mont_html->monto_html_select();

    //MONTO CAMPO MODAL ADICIONAR
    $mont_html->set_array(null,'input_id_area',''); //Coloque o mesmo valor que foi inserido no select em input_pagina_add_id
    $mont_html->set_array(null,'url_pagina',$this->core->get_config('dir_raiz_http').$this->dir_app.'/teste'); //PARAMETRO TIPO
    $modal = $mont_html->monto_html_modal_add();

    //MONTO VALORES DO RADIOBOX AREA HIDE E SHOW
    $this->funcoes->set_array(0,'input_id','');
    $this->funcoes->set_array(0,'input_name',''); //mesmo name
    $this->funcoes->set_array(0,'input_class','');
    $this->funcoes->set_array(0,'input_title','');
    $this->funcoes->set_array(0,'input_value',''); //value do campo
    $this->funcoes->set_array(0,'input_checked',''); //checked ou ""
    $this->funcoes->set_array(0,'input_texto_radiobox',''); //Texto
    $this->funcoes->set_array(0,'campo_show','.area_tal'); //campo que sera mostrado no click
    $valores_radio = $this->funcoes->get_array();

    //MONTO CAMPO INPUT RADIOBOX AREA HIDE E SHOW
    $mont_html->set_array(null,'input_class_tamanho','col-lg-3 col-md-4 col-sm-12 col-xs-12'); //col-md-1 ao col-md-12 e outras classes
    $mont_html->set_array(null,'input_id',''); // id da area
    $mont_html->set_array(null,'label_for_texto','');
    $mont_html->set_array(null,'input_options',$valores_radio);
    $input_radiobox = $mont_html->monto_html_input_radiobox_area_hide();

    //MONTO CAMPO BOTÃO DE LINK
    $mont_html->set_array(null,'texto','');
    $mont_html->set_array(null,'href',''); // $this->core->get_config('dir_raiz_http').$this->dir_app."/
    $mont_html->set_array(null,'target',''); //target _blank
    $mont_html->set_array(null,'id','');
    $mont_html->set_array(null,'class','');
    $mont_html->set_array(null,'icon_class',''); // Classe icone - Font Awesome Icons
    $mont_html->set_array(null,'outros','');
    $link_btn = $mont_html->monto_html_btn_externo();

    //MONTO CAMPO MENSAGEM INFORMATIVA
    $mont_html->set_array(null,'id','');
    $mont_html->set_array(null,'class','');
    $mont_html->set_array(null,'tipo',''); //tipos: danger, info, warning, success
    $mont_html->set_array(null,'titulo','');
    $mont_html->set_array(null,'mensagem','');
    $msg_informativa = $mont_html->monto_html_mensagem_informativa();

    //MONTO CAMPO ÁREA DESCRIÇÃO
    $mont_html->set_array(null,'area_descricao','')->set_array(null,'area_descricao_icon_class','')->set_array(null,'class',''); // Classes úteis = (subtitulo-margin_top) -Classe icone - Font Awesome Icons
    $area_descricao = $mont_html->monto_html_area_descricao();

    //MONTO CAMPOS E ÁREAS
    $mont_html->set_array(0,'titulo','teste');
    $mont_html->set_array(0,'icone_titulo',''); // Classe icone - Font Awesome Icons
    $mont_html->set_array(0,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
    $mont_html->set_array(0,'conteudo',"  "); //conteudo ***
    $mont_html->set_array(1,'titulo','teste2');
    $mont_html->set_array(1,'icone_titulo',''); // Classe icone - Font Awesome Icons
    $mont_html->set_array(1,'class_area',''); // Ocultar e mostrar uma área com um select: select_chang_show_hide_areas e area_sh_(value do option)
    $mont_html->set_array(1,'conteudo',"  "); //conteudo ***
    $conteudo_montado = $mont_html->monto_html_area_forms();

    //MONTO CAMPOS
    $mont_html->set_array(0,'conteudo',"  ");
    $mont_html->set_array(1,'conteudo',"  ");
    $conteudo_montado = $mont_html->monto_html_forms();


//***************************************************************************************************************************
//CONTROLLER - JAVASCRIPT - MONTAGEM DOS CAMPOS JAVASCRIPT DO COMBOBOX, CHANGBOX E AUTOCOMPLETE
//***************************************************************************************************************************


    //MONTO AUTOCOMPLETE
    $this->funcoes->set_array(0,'tipo','autocomplete'); // Tipo
    $this->funcoes->set_array(0,'tipo_autocomplete',''); // tipo autocomplete
    $this->funcoes->set_array(0,'id_autocomplete',''); // id do campo

    //MONTO COMBOBOX
    $this->funcoes->set_array(0,'tipo','combobox'); // Tipo
    $this->funcoes->set_array(0,'tipo_combobox',''); // tipo combobox
    $this->funcoes->set_array(0,'id_combobox',''); // id do campo
    $this->funcoes->set_array(0,'txt_plural_combobox',''); // Texto no plural
    $this->funcoes->set_array(0,'txt_sing_combobox',''); // Texto no singular

    //MONTO CHANGBOX
    $this->funcoes->set_array(0,'tipo','changbox'); // Tipo
    $this->funcoes->set_array(0,'tipo_changbox',''); // tipo changbox
    $this->funcoes->set_array(0,'id_changbox',''); // id do campo
    $this->funcoes->set_array(0,'txt_plural_changbox',''); // Texto no plural
    $this->funcoes->set_array(0,'txt_sing_changbox',''); // Texto no singular
    $this->funcoes->set_array(0,'id_combobox',''); // id do campo
    $this->funcoes->set_array(0,'txt_sing_combobox',''); // Texto no singular

    //SETO OS DADOS NOS CAMPOS INPUT, SELECT E OUTROS
    $this->funcoes->set_array(0,'tipo_set','input'); // input, textarea, checkbox, select, select_estatico, show_hide = (valor_campo_set = show ou hide), disabled = (valor_campo_set = disabled ou remove_disabled), moeda Real R$ $this->funcoes->set_array(8,'valor_campo_set',number_format($exec[$i]['valor'], 2, ',', '.'));
    $this->funcoes->set_array(0,'campo_set_form','campo_name');
    $this->funcoes->set_array(0,'valor_campo_set','VALOR DE TESTE');

    //SETO INFORMAÇÕES HTML NO EDITAR
    $this->funcoes->set_array(27,'tipo_set','info_editar');
    $this->funcoes->set_array(27,'valor_campo_set',' ID: '.$exec[$i]['id'].' ');


//***************************************************************************************************************************
//CONTROLLER - VALIDAÇÃO DE FORMULÁRIOS E FUNÇÕES RELACIONADAS - funcoesHelper.php
//***************************************************************************************************************************

    //RECEBO DADOS DO POST OU GET
    (string) $name  = $this->funcoes->anti_injection($_POST['name']); //(string) (int) (double) ...
    (string) $name2 = $this->funcoes->anti_injection($_POST['textareaname'],'html'); //recebo campo html
    (double) $valor = $this->funcoes->substituo_strings(array(',','.'),array('',''),$this->funcoes->anti_injection($_POST['campo_valor']));

    //SETO OS DADOS NO MODEL
    $this->model->setCampos('name',$name);
    $this->model->setCampos('name',$name2);
    $this->model->setCampos('name',$this->conv_datahora($name2,'Y-m-d')); //converto data para americano
    $this->model->setCampos('name',$this->conv_datahora($name2,'H:i')); //converto h:i:s para h:i

    //VALIÇÃO EXEMPLO ENCADEADO
    $this->funcoes->set('NOME CAMPO',"name_form", $valor_rec)->is_required()->min_length(2)->max_length(250);

    ->is_required()  //Campo obrigatório
    ->min_length(2)  //Minimo de caracteres
    ->max_length(250)  //Maximo de caracteres
    ->between_length(1, 10)  //O campo deve conter entre <b>%s</b> e <b>%s</b> caracter(es)
    ->min_value($value, $inclusive = false)  //O valor do campo deve ser maior que o valor
    ->max_value($value, $inclusive = false)  //O valor do campo deve ser menor que o valor
    ->between_values($min_value, $max_value)  //O valor do campo <b>%s</b> deve estar entre <b>%s</b> e <b>%s</b>
    ->is_email($param = null)  //O email <b>%s</b> não é válido
    ->is_url()  //A URL <b>%s</b> não é válida
    ->is_slug()  //<b>%s</b> não é um slug
    ->is_num()  //O valor <b>%s</b> não é numérico
    ->is_integer()  //O valor <b>%s</b> não é inteiro
    ->is_float()  //O valor <b>%s</b> não é float
    ->is_string()  //O valor <b>%s</b> não é String
    ->is_boolean()  //O valor <b>%s</b> não é booleano
    ->is_obj()  //A variável <b>%s</b> não é um objeto
    ->is_instance_of($class)  //<b>%s</b> não é uma instância de <b>%s</b>
    ->is_arr()  //A variável <b>%s</b> não é um array
    ->is_directory()  //<b>%s</b> não é um diretório válido
    ->is_equals($value, $identical = false)  //O valor do campo <b>%s</b> deve ser igual à <b>%s</b>
    ->is_not_equals($value, $identical = false)  //O valor do campo <b>%s</b> não deve ser igual à <b>%s</b>
    ->is_cpf()  //O valor <b>%s</b> não é um CPF válido
    ->is_cnpj()  //O valor <b>%s</b> não é um CNPJ válido
    ->contains($values, $separator = false)  //O campo <b>%s</b> só aceita um do(s) seguinte(s) valore(s): [<b>%s</b>]
    ->not_contains($values, $separator = false)  //O campo <b>%s</b> não aceita o(s) seguinte(s) valore(s): [<b>%s</b>]
    ->is_lowercase()  //O campo <b>%s</b> só aceita caracteres minúsculos
    ->is_uppercase()  //O campo <b>%s</b> só aceita caracteres maiúsculos
    ->is_multiple($value)  //O valor <b>%s</b> não é múltiplo de <b>%s</b>
    ->is_positive($inclusive = false)  //O campo <b>%s</b> só aceita valores positivos
    ->is_negative($inclusive = false)  //O campo <b>%s</b> só aceita valores negativos
    ->is_date()  //A data <b>%s</b> não é válida
    ->is_maioridade($idade_a)  //Você deve ter mais de <b>%s anos</b> para participar
    ->is_alpha($additional = '')  //O campo <b>%s</b> só aceita caracteres alfabéticos
    ->is_alpha_num($additional = '')  //O campo <b>%s</b> só aceita caracteres alfanuméricos
    ->no_whitespaces()  //O campo <b>%s</b> não aceita espaços em branco
    ->is_time_hm($param = null)  //O tempo <b>%s</b> não é válido
    ->is_time_hms($param = null)  //O tempo <b>%s</b> não é válido
    ->is_password_num_let()  //A Senha deve ter Números e Letras
    ->is_IP()  //O IP <b>%s</b> não é válido
    ->is_telefone($inclusive = false)  //Número de telefone <b>%s</b> inválido
    ->is_CEP($correios = null)  //O CEP <b>%s</b> não é válido
    ->is_captcha_google($recaptcha_secret) //Valido captcha google
    ->is_unique($param)  //Este(a) <b>%s</b> já esta cadastrado(a) no banco de dados
    ->is_date_validate_past($date)  //A data <b>%s</b> deve ser maior que a data atual
    ->is_required_upload()  //Selecione o(s) arquivo(s) do(a) <b>%s</b>
    ->is_compare_campo($param,$descricao2)  //O valor do campo <b>%s</b> deve ser igual ao do campo <b>%s</b>

    //MOSTRO MENSAGEM DE ERROS NA LINHA DO INPUT
    if(count($this->funcoes->get_errors())>=1){
        echo json_encode($this->funcoes->get_errors());
        exit;
    }

    //VERIFICO SE CAMPO JA FOI CADASTRADO (ADICIONAR)
    if($this->model->retorn_campo_editar('campo',$campo_valor) == $campo_valor){
        $this->funcoes->set_array(null,'erro','Este ____ já está cadastrado no banco de dados!'); //Mensagem
        echo json_encode($this->funcoes->get_array()); //Mostro na tela
        exit;
    }

    //VERIFICO SE CAMPO JA FOI CADASTRADO (EDITAR)
    if($this->model->retorn_campo_editar_val_id('campo') != $campo_valor){
        if($this->model->retorn_campo_editar('campo',$campo_valor) == $campo_valor){
            $this->funcoes->set_array(null,'erro','Este ____ já está cadastrado no banco de dados!'); //Mensagem
            echo json_encode($this->funcoes->get_array()); //Mostro na tela
            exit;
        }
    }

    //MOSTRO MENSAGEM DE SUCESSO
    $this->funcoes->set_array(null,'sucesso','MENSAGEM DE SUCESSO') //Mensagem
                  ->set_array(null,'id',$ult_id) //Retorno ID
                  ->set_array(null,'limpo_campo','true'); //Limpo campos
    echo json_encode($this->funcoes->get_array()); //Mostro na tela

    //MOSTRO MENSAGEM DE ERRO
    $this->funcoes->set_array(null,'erro','MENSAGEM DE ERRO'); //Mensagem
    echo json_encode($this->funcoes->get_array()); //Mostro na tela

    //PEGO MENSAGEM DA ÁREA DE MENSAGENS
    $this->core->get_msg_array('erro_inserir',"{$variavel}"); //config > mensagens > default.php


//***************************************************************************************************************************
//HELPER - FUNÇÕES ÚTEIS
//***************************************************************************************************************************

    //CAMINHO ATÉ A PASTA ATUAL
    $this->core->get_config('dir_raiz_http').$this->dir_app."/";

    //PEGO MENSAGEM DA ÁREA DE MENSAGENS
    $this->core->get_msg_array('erro_inserir',"{$variavel}"); //config > mensagens > default.php

    //PEGO O CMD CONTROLLER DE UMA PÁGINA
    $string = $this->config_apps->getCmds_controller('core',1);

    //MONTO O CAMINHO COMPLETO COM O CMD CONTROLLER DA PÁGINA
    $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',1));

    //CONVERTO DATA OU HORA
    $nova_data = $this->funcoes->conv_datahora($data,'Y-m-d');

    //RETORNO TEMPO
    $nova_data = $this->funcoes->retorno_tempo_post($data);

    //RETORNO DATA ATUAL - EX: Boa tarde! Segunda-feira, 10 Março de 2015
    $data_extenso = $this->funcoes->retorno_data_saudacao();

    //RETORNO DATA ESCRITO EX: Segunda-feira, 10 de Março de 2015
    $data_escrita = $this->funcoes->retorno_data_por_extenso('05-02-2016'); //data ou '' = data atual

    //GERO UMA SEQUENCIA DE NÚMEROS E LETRAS ALTERNADOS
    $token = $this->funcoes->gero_token();

    //GERO UMA SENHA ALEATÓRIA
    $senha = $this->funcoes->gero_senha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false);

    //CRIPTOGRAFO UMA STRING
    $string = $this->funcoes->mycrypt($string);

    //DESCRIPTOGRAFO UMA STRING
    $string = $this->funcoes->decrypt($string);

    //CRIO ARQUIVO PHTML
    $this->funcoes->crio_arquivo_phtml($caminho,$texto);

    //SUBSTITUO PALAVRAS DE UMA STRING POR OUTRA
    $string = $this->funcoes->substituo_strings($procuro,$substituo,$string_original);

    //PROCURO PALAVRA EM UMA STRING
    $string = $this->funcoes->localizo_string($string, $procuro);

    //RETIRO UMA POSIÇÃO INFORMADA DO ARRAY
    $array = $this->funcoes->array_retira_a_posicao_informada ($array, $pos);

    //FUNÇÃO ANTI INJECTION
    $string = $this->funcoes->anti_injection($sql,$param = null); // param html

    //TEXTO ENCURTADO
    $string = $this->funcoes->encurtarTexto($texto,$tamPermitido);

    //CONVERTO STRING PARA MAIUSCULO, MINUSCULO OU MAIUSCULO E MINUSCULO
    $string = $this->funcoes->conv_string($term, $tp); //$tp 0 = minusculo | 1 = maiusculo | 2 = maiusculo e minusculo

    //TRAVO O VALOR R$ REAL MOEDA
    (int) $valor = $this->funcoes->substituo_strings('.','',$valor);
    (int) $valor = $this->funcoes->substituo_strings(',','.',$valor);

//***************************************************************************************************************************
//CONTROLLER - EXCLUSÃO - VERIFICO SE PODE EXCLUIR, EXCLUIR ARQUIVO DO SERVIDOR
//***************************************************************************************************************************

    //VERIFICO SE PODE EXCLUIR
    $this->model->setCampos('campo_tabela',"");
    $this->model->setCampos('campo_coluna',"");
    $this->model->setCampos('campo_inner_join',"");
    $this->model->setCampos('campo_where',"");
    $exec_ = $this->model->select_simples_retorna_true_false();
    $this->model->getLimpoCampos();

    //EXCLUIR ARQUIVO DO SERVIDOR
    $this->model->setCampos('campo_tabela',"");
    $this->model->setCampos('campo_coluna',"url_imagem");
    $this->model->setCampos('campo_coluna2',"id");
    $this->model->setCampos('campo_inner_join',"");
    $this->model->setCampos('campo_where',"");
    $url_exclusao = $this->model->select_simples_retorna_array();
    $this->model->getLimpoCampos();
    if(!empty($url_exclusao)){
        for ($i=0; $i < count($url_exclusao) ; $i++) {

            //EXCLUO DO SERVIDOR
            unlink($this->core->get_config('dir_files_comp')."/perfil/p/".$url_exclusao[$i]['url_imagem']);
            unlink($this->core->get_config('dir_files_comp')."/perfil/m/".$url_exclusao[$i]['url_imagem']);
            unlink($this->core->get_config('dir_files_comp')."/perfil/g/".$url_exclusao[$i]['url_imagem']);

            /*//EXLUO DA TABELA
            $this->model->setCampos('campo_tabela',"");
            $this->model->setCampos('campo_id',"id");
            $this->model->setId($url_exclusao[$i]['id'])
            $this->model->excluir_geral();*/
        }
    }


//***************************************************************************************************************************
//HELPER - MODEL - MONTO AS QUERYS DO BANCO DE DADOS - conexao_pdoHelper.php
//***************************************************************************************************************************

    //CREATE
    $this->setTable('logs'); //Tabela
    $this->setColuna('usuario_id')->setColuna('acao')->setColuna('IP')->setColuna('SO'); //Colunas
    $this->setValue(1)->setValue('ação de teste')->setValue('IP de teste')->setValue('SO de teste'); //Valores
    $this->Create(); //Executo comando
    $this->limpo_campos(); //Limpo campos
    $this->get_last_id(); //Pego o ID que acabou de ser inserido

    //READ MONTADO
    $this->setTable('logs'); //Tabela
    $this->setColuna('acao')->setColuna('IP')->setColuna('SO')->setColuna('criado'); //Colunas ('nome coluna') ou todos os campos (*)
    $this->setInner(); // INNER JOIN ...
    $this->setWhere(); // id = 1 ...
    $this->setGroup(); // id ...
    $this->setOrder(); // nome ASC ...
    $this->setLimit(); // 1,0 ...
    $exec = $this->Read(); //Executo comando
    $this->limpo_campos(); //Limpo campos
    for($i=0;$i<count($exec);$i++){
        $array[] = $exec[$i];
    }

    //READ LIVRE
    $exec = $this->Read('SELECT * FROM logs'); //Executo comando
    for($i=0;$i<count($exec);$i++){
        $array[] = $exec[$i];
    }

    //UPDATE
    $this->setTable('logs'); //Tabela
    $this->setColuna('acao')->setColuna('IP')->setColuna('usuario_id'); //Colunas
    $this->setValue('VALOR AÇAO')->setValue('VALOR IP')->setValue(1); //Valores
    $this->setWhere("id = 11 "); // id = 11 ...
    $exec = $this->Update(); //Executo comando
    $this->limpo_campos(); //Limpo campos

    //DELETE
    $this->setTable('logs'); //Tabela
    $this->setWhere('id = 1'); // id = 1
    $exec = $this->Delete(); //Executo comando
    $this->limpo_campos(); //Limpo campos

    //MONTAGEM DE QUERYS (FUNÇÕES)
    $this->return_where_data($campo,$date1,$date2); //periodos
    $this->return_where_int($campo,$param,$value); //campo int
    $this->return_where_double($campo,$param,$value); //campo double
    $this->return_where_like($campos,$value); //campo like
    $this->return_where_char($campo,$param,$value); //campo varchar
    $this->return_params_mont($array,$param,$parenteses); //array, AND OR.. , true ou vazio

    //MONTAGEM DE QUERYS AUTOMATIZADA
    $array_pesq[] = $this->return_where_like($tabela.'.nome',$query_pesquisa);
    $array_pesq[] = $this->return_where_like($tabela.'.email',$query_pesquisa);
    $where = $this->return_params_mont($array_pesq,'OR',true);

    //CONDIÇÕES PERSONALIZADAS DE PESQUISA
    $param1         = 'id: '; // parametro personalizado
    $status_param1  = $funcoes->localizo_string($query_pesquisa, $param1);
    $query_pesquisa = $funcoes->substituo_strings($param1,'',$query_pesquisa);
    if($status_param1 !== false){
        $where = $this->return_where_int($tabela.'.id','=',$query_pesquisa);
        $conf_personalizada = true;
    }


//***************************************************************************************************************************
//HELPER - CONTROLLER - UPLOAD DE ARQUIVOS - uploadHelper.php
//***************************************************************************************************************************

    //INSTANCIO UPLOAD
    $this->core->includeHelper('upload');
    $upload = new upload();

    //SETO DADOS DE UPLOAD DE ARQUIVO
    $arquivo_file = $_FILES['arquivo'];
    $upload->setPasta('nome_pasta')->setArquivo($arquivo_file)->setNome_arquivo('')->setTipo_arquivo('jpg') // jpg, gif, png, doc, xls, pdf, todos_documentos, todas_imagens, tudo
           ->setTamanho(5)->setValido_dimensoes(false)->setRedimensiono(false)->setUpload_multiplo(false)
           ->setWidth(0)->setHeight(0)
           ->setWidth_p(0)->setHeight_p(0)->setRes_p(0)
           ->setWidth_m(0)->setHeight_m(0)->setRes_m(0)
           ->setWidth_g(0)->setHeight_g(0)->setRes_g(0)
           ->upload_file();

    //RETORNO ARRAY COM ERROS
    $upload->getMsg_erro();

    //RETORNO ARRAY COM NOMES DE ARQUIVOS UPADOS
    $upload->getNome_arquivo_return();

    //VERIFICO SE TEM IMAGEM - UPLOAD UNICO ADICIONAR **
    if (empty($imagem_perfil['tmp_name'])) {
        $this->funcoes->set_array(null,'erro','Selecione uma imagem de ...'); // Mensagem de erro
        echo json_encode($this->funcoes->get_array());
        exit();
    }


    //************************************************************
    //UPLOAD UNICO EDITAR

    //VERIFICO SE TEM IMAGEM - UPLOAD UNICO EDITAR **
    $this->model->setCampos('adm_usuario');
    if($this->model->retorn_campo_editar_val_id('img_perfil') == ''){
        if($this->model->retorn_campo_editar('img_perfil', '') == ''){
            if (empty($imagem_perfil['tmp_name'])) {
                $this->funcoes->set_array(null,'erro','Selecione uma imagem de ...'); // Mensagem de erro
                echo json_encode($this->funcoes->get_array());
                exit();
            }
        }
    }

    //RETORNO ARRAY COM ERROS - UPLOAD UNICO EDITAR **
    $erro_upload = $upload->getMsg_erro();
    if (!empty($erro_upload)) {
        $this->funcoes->set_array(null,'erro','Imagem de Perfil <br />'.$this->funcoes->get_errors_inline($erro_upload)); // Mensagem de erro
        echo json_encode($this->funcoes->get_array());
        exit();
    }

    //RETORNO ARRAY COM NOMES DE ARQUIVOS UPADOS - UPLOAD UNICO EDITAR **
    $imagem_perfil = $upload->getNome_arquivo_return();

    //EXCLUO IMAGEM ANTIGA - UPLOAD UNICO EDITAR **
    if(!empty($imagem_perfil[0])){
        $this->model->setCampos('campo_tabela',"adm_usuario");
        $this->model->setCampos('campo_coluna',"id");
        $this->model->setCampos('campo_coluna2',"img_perfil");
        $this->model->setCampos('campo_where',"id = {$id}");
        $valor = $this->model->select_simples_retorna_array_mont_vcol();
        if(count($valor) >= 1){
            for ($i=0; $i < count($valor) ; $i++) {
                unlink($this->core->get_config('dir_files_comp')."/perfil_usuario/p/".$valor[$i]['img_perfil']);
                unlink($this->core->get_config('dir_files_comp')."/perfil_usuario/m/".$valor[$i]['img_perfil']);
                unlink($this->core->get_config('dir_files_comp')."/perfil_usuario/g/".$valor[$i]['img_perfil']);
            }
        }
        $this->model->getLimpoCampos();
    }


//***************************************************************************************************************************
//HELPER - CONTROLLER - DISPARO E-MAIL - emailHelper.php
//***************************************************************************************************************************

    //MONTO O E-MAIL
    $this->email->setEmail_from(); //email remetente
    $this->email->setNome_remetente(); //nome remetente
    $this->email->setEmail_send(); //destinatario
    $this->email->setEmail_resposta(); //email resposta
    $this->email->setNome_resposta(); //nome resposta
    $this->email->setAssunto(); //Assunto
    $this->email->setConteudo(); //Conteúdo
    $exec_email = $this->email->envio_email_phpmailer(); //Envio o e-mail


//***************************************************************************************************************************
//HELPER - CORE - GRAVO LOGS - logsHelper.php
//***************************************************************************************************************************

    //GRAVO LOG
    $this->logs->setApp($this->dir_app) //Pasta da aplicação
                ->setUrl($_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI']) //Url de acesso
                ->setPagina(end(explode("/", $_SERVER['PHP_SELF']))) //Pagina de acesso
                ->setMensagem('Mensagem') //Mensagem
                ->gravo_log(); //Gravo log

    //GRAVO LOG DE ACESSO
    $this->logs->setApp($this->dir_app) //Pasta da aplicação
               ->setMensagem('Login') //Mensagem
               ->gravo_log_acesso(); //Gravo log

   //GRAVO LOG DE ENVIO DE SMS
   $this->logs->setApp($this->dir_app) //Pasta da aplicação
              ->setId_sms('')->setTelefone('')->setStatus('')->setMensagem('')->gravo_log_sms_enviado();

   //GRAVO LOG DE ENVIO DE EMAIL
   $this->logs->setApp($this->dir_app)
              ->setEmail_re('')->setEmail('') //E-mail Remetene - E-mail destinatario
              ->setStatus('Enviado')->setMensagem('')->gravo_log_email_enviado();


//***************************************************************************************************************************
//HELPER - CORE - VIEW SMARTY PHP - viewHelper.php
//***************************************************************************************************************************

    //SETO VARIAVEL NA VIEW
    $view->seto_dados('variavel_teste','valor');

    //SETO VALORES DE ARRAY NA VIEW
    $array['variavel_teste'] = 'valor';
    $view->seto_dados_array($array);

    //CARREGO A INTERFACE
    $view->monto_view('caminho_do_arquivo.phtml');

    //PEGO UM TEMPLATE.phtml PARA SER USADO NO PHP
    $view->retorno_template_php('caminho_do_arquivo.phtml');
