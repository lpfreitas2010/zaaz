<?php

    /**
    * Configurações do Sistema
    *
    * @author Fernando
    * @version 2.0.0
    **/

    //Classe
    class config{

        //ARRAY GERAL COM CONFIGURAÇÕES
        protected $_dados = array();
        public function set_array($array){
            if(is_array($array)){
                if(!empty($array)){
                    $this->_dados = array_merge($array,$this->_dados);
                }
            }
        }
        public function get_array(){
            return $this->_dados;
        }

        //CONFIGURAÇÕES
        function init() {

            //=================================================================
            //CONFIGURAÇÕES GERAIS
            //=================================================================

            //DEFAULT DATE
            date_default_timezone_set('America/Sao_Paulo');

            //CHARSET UTF8
            header("Content-Type: text/html; charset=utf-8",true);

            //VARIAVEIS UTILIZADAS
            $data_atual  = date('d/m/Y');
            $hora_atual  = date('H:i:s');

            //PEGO A LINGUAGEM DO NAVEGADOR DO USUÁRIO
            if(empty($_COOKIE['language'])){
                setcookie('language',  substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2), (time() + (1 * 3600)));
            }
            if(!empty($_COOKIE['language'])){

                //Verifico as linguagens permitidas no sistema
                if(!in_array($_COOKIE['language'], array('pt','en'))){
                    $language = 'default';
                }else{

                    //Verifico se linguagem é padrão
                    if($_COOKIE['language'] === 'pt'){
                        $language = 'default';
                    }else{
                        $language = $_COOKIE['language'];
                    }
                }
            }else{
              $language = 'default';
            }
            $language = 'default';

            //=================================================================
            //CONEXÕES COM O BANCO DE DADOS
            //=================================================================
            $conexoes_bd = array(

               //CONEXÃO LOCAL
               'local' => array('host_db'      => 'localhost', // Host
                                'user_db'      => 'root', // Usuário
                                'password_db'  => '', // Senha
                                'database_db'  => 'zaaz', // Banco de Dados
                                'msg_error_db' => true, // Mensagens de Erro do Banco de dados [ true or false ]
                                'logs_bd_txt'  => true, // Gravo logs de erro do banco de dados em txt [ true or false ]
                                ),

               //CONEXÃO PRODUÇÃO
               'default' => array('host_db'       => '', // Host
                                   'user_db'      => '', // Usuário
                                   'password_db'  => '', // Senha
                                   'database_db'  => '', // Banco de Dados
                                   'msg_error_db' => true, // Mensagens de Erro do Banco de dados [ true or false ]
                                   'logs_bd_txt'  => true, // Gravo logs de erro do banco de dados em txt [ true or false ]
                                 )

             );


             //=================================================================
             //CONEXÕES COM O SMTP
             //=================================================================
             $conexoes_smtp = array(

                //SMTP PADRÃO
                'smtp1' => array( 'Host_smtp'               => '', // Host SMTP
                                  'Username_smtp'           => '', // E-mail SMTP
                                  'Senha_smtp'              => '', // Senha SMTP
                                  'Porta_smtp'              => '', // Porta SMTP
                                  'Tls_smtp'                => true, // Conexão autenticada [ true or false ]
                                  'Debug_smtp'              => false, // Debug [ true or false ]
                                  'status_msg_rodape_email' => true, // Mensagem no rodapé do e-mail [ true or false ]
                                  'msg_rodape_email'        => "<hr /> E-mail enviado em {$data_atual} ás {$hora_atual}", // Mensagem no rodapé do e-mail
                                  )

            );


            //=================================================================
            //CONFIGURAÇÕES GERAIS DA APLICAÇÃO
            //=================================================================
            $config = array(

            'nome_site'                => 'Zaaz framework', // Nome do site
            'endereco_site'            => 'www.site.com.br', // Endereço do site
            'versao_app'               => '', // Versão da aplicação
            'versao_ano'               => '2015', // Ano da Versão da aplicação
            'autor_app'                => 'Fernando, Luis', // Programadores
            'desenvolvido_por'         => '', // Empresa
            'endereco_site_desenv'     => '', // Endereço do site desenvolvedora
            'email_notificacao'        => '', // E-mail para notificação da aplicação
            'status_files'             => true, // Pasta logs [ true or false ]

            //CONFIGURAÇÕES DE SERVIDOR E PASTA RAIZ

            'servidor_ativo_comp'      => 'localhost', // Servidor Ativo de comparação
            'pasta_raiz_local'         => '/ZAAZ/', // Diretório raiz (LOCAL)
            'pasta_raiz_servidor'      => '/', // Diretório raiz (SERVIDOR)

            //LINGUAGEM DO CORE GERAL DA APLICAÇÃO
            'arqui_msgs_core'          => $language, // Arquivo com as mensagens gerais do framework

            //PASTAS DA APLICAÇÃO
            'paste_apps'               => array('web','adm'), // Pasta das Aplicações
            'template_smarty_web'      => $language, // Pasta template do smarty (WEB)
            'template_smarty_adm'      => $language, // Pasta template do smarty (OUTRAS PASTAS)

            //CONFIGURAÇÕES DO GERENCIADOR DE TEMPLATES (SMARTY)
            'status_debug_smarty'      => false, // Debug do smarty [ true or false ]
            'status_cache_smarty'      => false, // Cache do smarty [ true or false ]

            //TEMPO (MINUTOS) DE SESSÃO DE LOGIN
            'tempo_sessao_web'         => 40, // Tempo em minutos (WEB)
            'tempo_sessao_adm'         => 40, // Tempo em minutos (OUTRAS PASTAS)

            //TEMPO (MINUTOS) DE USUÁRIO ONLINE
            'tempo_usuario_online'     => 4, // Tempo em minutos (GERAL)

            //CHAVE DE CRIPTOGRAFIA
            'chave_criptografia'       => '47410das32daa0dsaDc0dsa105040defdzZaaze0145', // Chave de criptografia


            //=================================================================
            //CONFIGURAÇÕES DE ERROS
            //=================================================================

            'status_erro_php_local'    => false, // Local [ true or false ]
            'status_erro_php_servidor' => false, // Servidor [ true or false ]
            'estilo_css_erro'          => 'margin:0 auto;font-family:arial;width:98%;height:auto;background:#dff151;color:#4b4b43;margin-top:10px;margin-bottom:10px;padding:20px;',


            //=================================================================
            //CONFIGURAÇÕES DE URL AMIGAVEL
            //=================================================================

            'status_url_amigavel'      => true,     // Status [ true or false ] * Somente o true esta hábilitado no framework
            'nome_pag_raiz'            => 'index',  // Noma da página raiz
            'nome_arquivo_index'       => 'index.php', // Nome do arquivo principal
            'nome_pag_controller_view' => 'pag.php', // Nome da página que controla as chamadas de url da view
            'ext_bloqueadas_htacess'   => 'txt|sql|ini|zip|phtml|rar', // Extensões bloqueadas no .htacess
            'par_url_htacess'          => '?p=',     // Parametro url amigavel .htacess


            //=================================================================
            //CONEXÃO COM O BANCO DE DADOS
            //=================================================================

            'test_db'         => true,  // Status do banco de dados [ true or false ]
            'tipo_conexao_bd' => 'pdo', // Tipo de conexão com o banco [ pdo, mysqli(não disponivel) ]


            //=================================================================
            //HELPERS DEPENDENTES
            //=================================================================

            'helpers_init' => array('logs','email','usuario_sessao'), // Funções carregadas automaticamente

            );


            //=================================================================
            //DIRETÓRIOS DA APLICAÇÃO
            //=================================================================

            //ARRAY
            $dir_aplicacao = array(
            'dir_view'          => 'view',       // Diretório da view
            'dir_core'          => 'core',       // Diretório do core do framework
            'dir_controller'    => 'controller', // Diretório de controller
            'dir_model'         => 'model',      // Diretório de model
            'dir_cache'         => 'cache',      // Diretório de cache
            'dir_logs'          => 'logs',       // Diretório de logs.txt
            'dir_config'        => 'config',     // Diretório de config
            'dir_files'         => 'files',      // Diretório publico de arquivos de uploads do framework
            'dir_libs'          => 'libs',       // Diretório de blibliotecas CORE do framework
            'dir_helper'        => 'helper',     // Diretório de códigos auxiliadores
            'dir_inc'           => 'inc',        // Diretório de blibliotecas de terceiros
            'dir_assets'        => 'assets',     // Diretório de assets
            'dir_mensagens_int' => 'mensagens',  // Diretório de mensagens (languages)
            'dir_includes'      => 'includes',   // Diretório de includes
            'dir_templates'     => 'templates',  // Diretório de templates
            'dir_languages'     => 'languages',  // Diretório de linguagens
            'suf_controller'    => 'Controller', // Sufixo de controller
            'suf_model'         => 'Model',      // Sufixo de model
            'suf_helper'        => 'Helper',     // Sufixo de helper
            'suf_config'        => 'config',     // Sufixo config
            'dir_raiz'          => ((dirname(dirname(dirname(__FILE__))))), // Caminho fisico da pasta do servidor
            'dir_raiz2'         => $_SERVER['HTTP_HOST'],                   // Caminho fisico da pasta do servidor
            'inc_template_view' => 'smarty/libs/Smarty.class.php',          // Caminho do controlador de templates do framework (Smarty PHP)
            );

            //IDENTIFICO SERVIDOR ATIVO
            if($_SERVER['HTTP_HOST'] != $config['servidor_ativo_comp']){ //SERVIDOR
                //ARRAY
                $dir_aplicacao2 = array(
                'dir_raiz_http' => str_replace($config['nome_pag_controller_view'],"",$config['pasta_raiz_servidor']), // Diretório raiz http://site.com.br
                );
            }
            if($_SERVER['HTTP_HOST'] == $config['servidor_ativo_comp']){ //LOCALHOST
                //ARRAY
                $dir_aplicacao2 = array(
                'dir_raiz_http' => str_replace($config['nome_pag_controller_view'],"",$config['pasta_raiz_local']), // Diretório raiz http://site.com.br
                );
            }


            //=================================================================
            //PASTAS MONTADAS DA APLICAÇÃO
            //=================================================================

            $dir_montados = array(
            'dir_files_comp'  => "{$dir_aplicacao['dir_raiz']}/{$dir_aplicacao['dir_files']}", // caminho:/raiz/files/
            'dir_libs_comp'   => "{$dir_aplicacao['dir_raiz']}/{$dir_aplicacao['dir_libs']}",  // caminho:/raiz/libs/
            'dir_core_comp'   => "{$dir_aplicacao['dir_raiz']}/{$dir_aplicacao['dir_libs']}/{$dir_aplicacao['dir_core']}",   // caminho:/raiz/libs/core/
            'dir_helper_comp' => "{$dir_aplicacao['dir_raiz']}/{$dir_aplicacao['dir_libs']}/{$dir_aplicacao['dir_helper']}", // caminho:/raiz/libs/helper/
            'dir_inc_comp'    => "{$dir_aplicacao['dir_raiz']}/{$dir_aplicacao['dir_libs']}/{$dir_aplicacao['dir_inc']}",    // caminho:/raiz/libs/inc/
            'dir_logs_comp'   => "{$dir_aplicacao['dir_raiz']}/{$dir_aplicacao['dir_libs']}/{$dir_aplicacao['dir_logs']}",   // caminho:/raiz/libs/logs
            'dir_msgs_core'   => "{$dir_aplicacao['dir_raiz']}/{$dir_aplicacao['dir_libs']}/{$dir_aplicacao['dir_core']}/{$dir_aplicacao['dir_mensagens_int']}/{$config['arqui_msgs_core']}.php", // caminho:/raiz/libs/core/mensagens/....php
            );


            //=================================================================
            //DIRETÓRIOS DA APLICAÇÃO APPS
            //=================================================================

            $count_paste_apps = $config['paste_apps'];
            for ($i=0; $i < count($count_paste_apps); $i++) {
                  $app = $count_paste_apps[$i];
                  $this->set_array(array(

                  //INCLUDES DO ARQUIVO PAG.PHP
                  "dir_include_conf_{$app}"  => "{$dir_aplicacao['dir_raiz']}/{$app}/{$dir_aplicacao['dir_controller']}/{$dir_aplicacao['dir_config']}/config.php", // caminho:/raiz/pasta_aplicacao/controller/config/config.php
                  "dir_include_core_{$app}"  => "{$dir_aplicacao['dir_raiz']}/{$app}/{$dir_aplicacao['dir_controller']}/{$dir_aplicacao['dir_includes']}/core.php", // caminho:/raiz/pasta_aplicacao/controller/include/core.php
                  "dir_include_msgs_{$app}"  => "{$dir_aplicacao['dir_raiz']}/{$app}/{$dir_aplicacao['dir_controller']}/{$dir_aplicacao['dir_config']}/{$dir_aplicacao['dir_mensagens_int']}/".$config['template_smarty_'.$app].".php", // caminho:/raiz/pasta_aplicacao/controller/config/mensagens/......php

                  //DIRETÓRIOS COMPLETOS DA APLICAÇÃO
                  "dir_cache_comp_{$app}"       => "{$dir_aplicacao['dir_templates']}/".$config['template_smarty_'.$app]."/{$dir_aplicacao['dir_cache']}", // caminho:/templates/pasta_linguagem_template/cache
                  "dir_view_template_{$app}"    => "{$dir_aplicacao['dir_raiz']}/{$app}/{$dir_aplicacao['dir_view']}/{$dir_aplicacao['dir_templates']}/".$config['template_smarty_'.$app]."/{$dir_aplicacao['dir_cache']}", // caminho:/raiz/pasta_aplicacao/view/templates/pasta_linguagem_template/cache/
                  "dir_{$app}_comp"             => "{$dir_aplicacao['dir_raiz']}/{$app}",                                    // caminho:/raiz/pasta_aplicacao/
                  "dir_{$app}_controller_comp"  => "{$dir_aplicacao['dir_raiz']}/{$app}/{$dir_aplicacao['dir_controller']}", // caminho:/raiz/pasta_aplicacao/controller/
                  "dir_{$app}_model_comp"       => "{$dir_aplicacao['dir_raiz']}/{$app}/{$dir_aplicacao['dir_model']}",      // caminho:/raiz/pasta_aplicacao/model/
                  "dir_{$app}_view_comp"        => "{$dir_aplicacao['dir_raiz']}/{$app}/{$dir_aplicacao['dir_view']}",       // caminho:/raiz/pasta_aplicacao/view/
                  "dir_view_template_{$app}_comp" => "{$dir_aplicacao['dir_raiz']}/{$app}/{$dir_aplicacao['dir_view']}/{$dir_aplicacao['dir_templates']}/".$config['template_smarty_'.$app]."/", // caminho:/raiz/pasta_aplicacao/view/templates/pasta_linguagem_template/

                  //DIRETÓRIOS COMPLETOS ATÉ A PASTA VIEW
                  "path_template_comp_{$app}"      => "{$dir_aplicacao2['dir_raiz_http']}{$app}/{$dir_aplicacao['dir_view']}", // caminho:/raiz/pasta_aplicacao/view/ (WEB)
                  "path_template_comp_{$app}_apps" => "{$dir_aplicacao2['dir_raiz_http']}{$app}/{$dir_aplicacao['dir_view']}",        // caminho:/raiz/pasta_aplicacao/view/ (OUTRAS PASTAS)

                  //CONFIGURAÇÕES DA VIEW
                  "diretorio_smart_compile_dir_{$app}"  => "{$dir_aplicacao['dir_raiz']}/{$app}/{$dir_aplicacao['dir_view']}/{$dir_aplicacao['dir_templates']}/".$config['template_smarty_'.$app]."/{$dir_aplicacao['dir_cache']}", // caminho:/raiz/pasta_aplicacao/view/templates/pasta_linguagem_template/cache/
                  "diretorio_smart_template_dir_{$app}" => "{$dir_aplicacao['dir_raiz']}/{$app}/{$dir_aplicacao['dir_view']}/{$dir_aplicacao['dir_templates']}/".$config['template_smarty_'.$app]."/", // caminho:/raiz/pasta_aplicacao/view/templates/pasta_linguagem_template/
                  "cache_smart_caching_{$app}"          => $config['status_cache_smarty'], // Cache [ true or false ]
                  "smarty_debugging_{$app}"             => $config['status_debug_smarty'], // Debugging Smarty [ true or false ]

                 ));
            }


            //Seto e retorno os dados do Array
            $this->set_array($config);
            $this->set_array($dir_aplicacao);
            $this->set_array($dir_aplicacao2);
            $this->set_array($dir_montados);
            $this->set_array($conexoes_bd);
            $this->set_array($conexoes_smtp);
            return $this->get_array();
        }
    }
