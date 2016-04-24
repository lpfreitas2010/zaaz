<?php

    //===========================================================
    //ACTION E CMDS DO CONTROLLER ****-
    $this->action_controller                = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,null,'true'); // Action do controller
    $this->cmds['action_excluir']           = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',3),'true'); // Cmd do controller de excluir
    $this->cmds['action_detalhamento']      = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',5),'true'); // Cmd do controller de detalho_conteudo
    $this->cmds['action_detalhamento2']     = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',5)); // Cmd do controller de detalho_conteudo
    $this->cmds['action_listagem']          = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',6),'true'); // Cmd do controller de listagem
    $this->cmds['action_ativar']            = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',4),'true');; // Cmd do controller de ativar e desativar registro
    $this->cmds['action_set_editar']        = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',2),'true'); // Cmd que seta os valores do form em editar
    $this->cmds['action_excluir_tudo']      = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',9),'true'); // Cmd do controller que exclui todos os registros
    $this->cmds['action_autocomplete']      = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',10)); // Cmd do controller que ativa o autocomplet
    $this->cmds['action_combobox']          = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',11)); // Cmd do controller que ativa o combobox
    $this->cmds['action_changcombobox']     = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',12)); // Cmd do controller que ativa o changcombobox
    $this->cmds['action_monto_campos_form'] = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',13),'true'); // Cmd do controller que carrega os códigos javascript do combobox, changbox, autocomplete e do ckeditor
    $this->cmds['action_outros_js']         = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',14),'true'); // Cmd do controller que carrega outros códigos javascript da página
    $this->cmds['action_list_arquivos']     = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',16)); // Cmd do controller listagem de arquivos
    $this->cmds['action_excluir_arquivo']   = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',17),'true'); // Cmd do controller excluir arquivo
    $this->cmds['action_funcao_aberta']     = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',15),'true'); // Cmd do controller função aberta
    $this->cmds['cmd_add_edd']              = $this->config_apps->getCmds_controller('core',1); // Cmd do controller de adicionar e editar
    $this->cmds['cmd_exportar_imprimir']    = $this->config_apps->getCmds_controller('core',7); // Cmd do controller de exportar
    $this->cmds['action_exp_csv']           = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',7).'&tipo=csv','true');
    $this->cmds['action_grafico']           = $this->funcoes->monto_path_controller_comp($this->dir_app, $this->url_pagina,$this->config_apps->getCmds_controller('core',28));
