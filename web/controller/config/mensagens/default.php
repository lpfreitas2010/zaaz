<?php

  /**
  * Mensagens
  *
  * @author Fernando
  * @version 2.0.0
  **/

  //Classe
  class language_web{

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

      public function __construct() {


//***************************************************************************************************************************************************************
//MENSAGENS DE INTERFACE
//***************************************************************************************************************************************************************


              //=================================================================
              //MENSAGENS GERAL
              $this->set_array(array(

                  //VALIDAÇÃO DE DADOS
                  'is_required'               => 'O campo <b>%s</b> é obrigatório',
                  'min_length'                => 'O campo <b>%s</b> deve conter no mínimo <b>%s</b> caracter(es)',
                  'max_length'                => 'O campo <b>%s</b> deve conter no máximo <b>%s</b> caracter(es)',
                  'between_length'            => 'O campo <b>%s</b> deve conter entre <b>%s</b> e <b>%s</b> caracter(es)',
                  'min_value'                 => 'O valor do campo <b>%s</b> deve ser maior que <b>%s</b> ',
                  'max_value'                 => 'O valor do campo <b>%s</b> deve ser menor que <b>%s</b> ',
                  'between_values'            => 'O valor do campo <b>%s</b> deve estar entre <b>%s</b> e <b>%s</b>',
                  'is_email'                  => 'O email <b>%s</b> não é válido ',
                  'is_url'                    => 'A URL <b>%s</b> não é válida ',
                  'is_slug'                   => '<b>%s</b> não é um slug ',
                  'is_num'                    => 'O valor <b>%s</b> não é numérico ',
                  'is_integer'                => 'O valor <b>%s</b> não é inteiro ',
                  'is_float'                  => 'O valor <b>%s</b> não é float ',
                  'is_string'                 => 'O valor <b>%s</b> não é String ',
                  'is_boolean'                => 'O valor <b>%s</b> não é booleano ',
                  'is_obj'                    => 'A variável <b>%s</b> não é um objeto ',
                  'is_instance_of'            => '<b>%s</b> não é uma instância de <b>%s</b> ',
                  'is_arr'                    => 'A variável <b>%s</b> não é um array ',
                  'is_directory'              => '<b>%s</b> não é um diretório válido ',
                  'is_equals'                 => 'O valor do campo <b>%s</b> deve ser igual à <b>%s</b> ',
                  'is_not_equals'             => 'O valor do campo <b>%s</b> não deve ser igual à <b>%s</b> ',
                  'is_cpf'                    => 'O valor <b>%s</b> não é um CPF válido ',
                  'is_cnpj'                   => 'O valor <b>%s</b> não é um CNPJ válido ',
                  'contains'                  => 'O campo <b>%s</b> só aceita um do(s) seguinte(s) valore(s): [<b>%s</b>] ',
                  'not_contains'              => 'O campo <b>%s</b> não aceita o(s) seguinte(s) valore(s): [<b>%s</b>] ',
                  'is_lowercase'              => 'O campo <b>%s</b> só aceita caracteres minúsculos ',
                  'is_uppercase'              => 'O campo <b>%s</b> só aceita caracteres maiúsculos ',
                  'is_multiple'               => 'O valor <b>%s</b> não é múltiplo de <b>%s</b>',
                  'is_positive'               => 'O campo <b>%s</b> só aceita valores positivos',
                  'is_negative'               => 'O campo <b>%s</b> só aceita valores negativos',
                  'is_date'                   => 'A data <b>%s</b> não é válida',
                  'is_alpha'                  => 'O campo <b>%s</b> só aceita caracteres alfabéticos',
                  'is_alpha_num'              => 'O campo <b>%s</b> só aceita caracteres alfanuméricos',
                  'no_whitespaces'            => 'O campo <b>%s</b> não aceita espaços em branco',
                  'is_time_hm'                => 'O tempo <b>%s</b> não é válido',
                  'is_time_hms'               => 'O tempo <b>%s</b> não é válido',
                  'is_password_num_let'       => 'A Senha deve ter Números e Letras',
                  'is_IP'                     => 'O IP <b>%s</b> não é válido',
                  'is_CEP'                    => 'O CEP <b>%s</b> não é válido',
                  'is_CEP_invalid'            => 'O CEP <b>%s</b> não foi encontrado no banco de dados dos Correios',
                  'is_captcha_google'         => 'Marque o campo NÃO SOU UM ROBÔ e tente novamente',
                  'is_unique'                 => 'Este(a) <b>%s</b> já esta cadastrado(a) no banco de dados',
                  'is_date_validate_past'     => 'A data <b>%s</b> deve ser maior que a data atual',
                  'is_telefone'               => 'Número de telefone <b>%s</b> inválido',
                  'is_compare_campo'          => 'O valor do campo <b>%s</b> deve ser igual ao do campo <b>%s</b>',
                  'is_maioridade'             => 'Você deve ter mais de <b>%s anos</b> para participar ',

                  //VALIDAÇÃO DE UPLOAD
                  'error_limit_up'            => 'Somente 1 arquivo pode ser enviado por vez',
                  'file_exceeded_up'          => 'O tamanho do arquivo <b>%s</b> excede o permitido de <b>%s</b>',
                  'file_error_up'             => 'O upload do arquivo <b>%s</b> foi feito parcialmente',
                  'empty_file_up'             => 'Selecione o(s) arquivo(s) do(a) <b>%s</b>',
                  'file_block_up'             => 'O formato de arquivo <b>%s</b> não é permitido',
                  'img_width_incorrect_up'    => 'A imagem <b>%s</b> deve ter <b>%s</b> px de Largura',
                  'img_heigth_incorrect_up'   => 'A imagem <b>%s</b> deve ter <b>%s</b> px de Altura',
                  'img_dimen_incorrect_up'    => 'A imagem <b>%s</b> deve estar nas dimensões: <b>%s</b> px de Largura por <b>%s</b> px de Altura',
                  'error_upload_file_paste'   => 'Erro ao enviar arquivo <b>%s</b> para a pasta <b>%s</b>',

              ));




              //=================================================================
              //CORE CONTROLLER
              $this->set_array(array(

              ));




              //=================================================================
              //USUARIO CONTROLLER
              $this->set_array(array(

                  'erro_autenticacao'               => 'Dados de acesso incorretos!',
                  'auth_sucesso'                    => 'Autenticando usuário aguarde...',
                  'barra_tempo_requisicao'          => 'Você foi bloqueado devido a várias tentativas mal sucedidas. Tente novamente em alguns minutos.',
                  'sucesso_cadastro_user'           => 'Usuário cadastrado com sucesso. ',
                  'erro_cadastro_user'              => 'Algo deu errado no cadastro',
                  'erro_envio_email'                => 'Erro ao enviar e-mail %s ',
                  'sucesso_esqueci_senha_user'      => 'Instruções de alteração da senha enviados com sucesso para o e-mail cadastrado no sistema. Verifique seu e-mail',
                  'erro_esqueci_senha_user'         => 'Erro ao alterar a senha',
                  'erro_enviar_email_esqueci_senha' => 'Erro ao alterar a senhas',
                  'send_confirmacao_email'          => 'E-mail com autenticação de conta enviado com sucesso para <b>%s</b> verifique seu e-mail. ',
                  'sucesso_cadastro_end_user'       => 'Local cadastrado com sucesso. ',
                  'erro_cadastro_end_user'          => 'Algo deu errado no cadastro do Local',

                  //MENSAGENS GERAL DE USUARIO CONTROLLER
                  'msg_esqueci_senha' => 'Esqueci a senha',
                  'msg_senha'         => 'Senha',
                  'msg_email_ou_cpf'  => 'E-mail ou CPF',
                  'msg_estado'        => 'Estado',
                  'msg_cidade'        => 'Cidade',
                  'msg_logradouro'    => 'Logradouro',
                  'msg_numero'        => 'Numero',
                  'msg_complemento'   => 'Complemento',
                  'msg_bairro'        => 'Bairro',
                  'msg_cep'           => 'CEP',
                  'msg_nome'          => 'Nome',
                  'msg_email'         => 'E-mail',
                  'msg_email_novo'    => 'Novo E-mail',
                  'msg_conf_email'    => 'Confirme seu e-mail',
                  'msg_cpf'           => 'CPF',
                  'msg_sexo'          => 'Sexo',
                  'msg_dt_nascimento' => 'Data de Nascimento',
                  'msg_profissao'     => 'Profissão',
                  'msg_rg'            => 'RG',
                  'msg_telefone'      => 'Telefone',
                  'msg_telefone2'     => 'Telefone 2',
                  'msg_conf_senha'    => 'Confirme sua senha',

              ));




              //=================================================================
              //ERRO CONTROLLER
              $this->set_array(array(

                  //MENSAGENS GERAL DO CONTROLLER
                  'msg_pagina_n_encontrada'   => 'Página não encontrada!',
                  'msg_javascript_desativado' => 'O Javascript de seu navegador esta desabilitado!',
                  'msg_acesso_negado'         => 'Acesso negado!',

              ));




//***************************************************************************************************************************************************************
//MENSAGENS DE LOGS
//***************************************************************************************************************************************************************


              //=================================================================
              //GERAL
              $this->set_array(array(

                  'parametro_incorreto_get_log'  => 'Parâmetro incorreto [%s] ',

              ));




              //=================================================================
              //CORE CONTROLLER
              $this->set_array(array(

              ));




              //=================================================================
              //USUARIO CONTROLLER
              $this->set_array(array(

                  'erro_autenticacao_log'               => 'Tentativa mal sucedida de login. Username: %s ',
                  'auth_sucesso_log'                    => 'Entrou no sistema',
                  'sucesso_cadastro_user_log'           => 'Usuário cadastrado. CPF %s',
                  'erro_cadastro_user_log'              => 'Erro no cadastro. ERRO: %s ',
                  'acesso_pagina_log'                   => 'Acesso a página %s ',
                  'erro_envio_email_log'                => 'Erro ao enviar e-mail %s ',
                  'sucesso_cadastro_end_user_log'       => 'Cadastrou a Cidade e Estado com sucesso',
                  'erro_cadastro_end_user_log'          => 'Erro no cadastro da Cidade e Estado. ERRO: %s ',
                  'sucesso_esqueci_senha_user_log'      => '%s esqueceu sua senha de acesso',
                  'erro_esqueci_senha_user_log'         => 'Erro ao alterar a senha. ERRO: %s ',
                  'erro_enviar_email_esqueci_senha_log' => 'Erro no envio do e-mail área esqueci minha senha. ERRO: %s ',
                  'logoff_log'                          => 'Saiu do sistema',
                  'send_confirmacao_email_log'          => 'E-mail com autenticação de conta enviado com sucesso para %s ',

              ));


          //Seto e retorno os dados do Array
          return $this->get_array();
      }
}
