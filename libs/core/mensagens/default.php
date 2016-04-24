<?php

  /**
  * Mensagens
  *
  * @author Fernando
  * @version 2.0.0
  **/

  //Classe
  class language{

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
              //CORE
              $this->set_array(array(

                  'controller_view_not_found' => 'O Controller da View de ( <b>%s</b> ) não foi encontrado.',
                  'file_not_found'            => 'Não foi possível encontrar o arquivo: ( <b>%s<b/> )',
                  'dir_view_not_found'        => 'Não foi possível encontrar o diretório da View. ( <b>%s</b> )',
                  'dir_not_found'             => 'O Diretório ( <b>%s</b> ) não foi encontrado. ',
                  'dir_not_readable'          => 'Não foi possível ler o diretório. ( <b>%s</b> )',
                  'file_view_not_found'       => 'Não foi possível encontrar a View ( <b>%s</b> )',
                  'acess_block_function'      => 'Tentativa de acesso indevido. URL: [ %s ] ',
                  'empty_init_helper'         => 'Nenhum parametro foi definido na função do Helper',
                  'empty_init_inc'            => 'Nenhum parametro foi definido na função do Inc',

              ));






//***************************************************************************************************************************************************************
//MENSAGENS DE LOGS
//***************************************************************************************************************************************************************


              //=================================================================
              //ERROS DO BANDO DE DADOS
              $this->set_array(array(

                  'error_connection_bd' => 'Erro com a Conexão do Banco de Dados <br /> [ <b>%s</b> ]',
                  'error_query_bd'      => 'Erro de Query <br /> [ <b>%s</b> ] Comando: [ <b>%s</b> ] ',
                  'error_id_bd'         => 'ID informado não foi encontrado na tabela [ <b>%s</b> ] ',

              ));




          //Seto e retorno os dados do Array
          return $this->get_array();
      }
}
