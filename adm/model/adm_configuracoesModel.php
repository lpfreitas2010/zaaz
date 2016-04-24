<?php

	/**
	* Model
	*
	* @author Fernando
	* @version 2.0.0
	**/

	//=================================================================
	//INCLUDES
	require_once (dirname(dirname((dirname(__FILE__)))))."/libs/core/core.php";
	$core = new core();
	$core->includeModel("adm");
	//=================================================================

	//CLASSE ****-
	class adm_configuracoesModel extends config_model_adm {


//***************************************************************************************************************************************************************
//CARREGO PARAMETROS
//***************************************************************************************************************************************************************
		function carrego_parametros(){
			$this->setTabela('adm_configuracoes'); // Tabela ****-
		}





//***************************************************************************************************************************************************************
//LISTAGEM
//***************************************************************************************************************************************************************
		function listagem($limit = null){

			//===========================================================
			//RECEBO PARAMETROS
			$funcoes = new funcoes();
			$this->carrego_parametros(); // Carrego parametros
			$tabela = $this->getCampos('tabela'); // Pego valor
			if(empty($tabela)){
				$tabela = $this->getTabela(); // Pego valor
			}
			$this->setTable($tabela); // Seto valor tabela
		    $order_by                = $this->getCampos('order_by');  // Pego valor
		    $group_by                = $this->getCampos('group_by'); // Pego valor
		    $query_pesquisa          = $this->getCampos('query_pesquisa'); // Pego valor
		    $query_pesquisa_avancada = $this->getCampos('query_pesquisa_avancada'); // Pego valor
		    $inicio                  = $this->getCampos('inicio'); // Pego valor
		    $quantidade              = $this->getCampos('quantidade'); // Pego valor

			//===========================================================
			//MONTO A QUERY
			//===========================================================

			//===========================================================
			//MONTO COLUNAS ****-
			$this->setColuna($tabela.'.id') // padrão
				 ->setColuna($tabela.'.modificado') // padrão
				 ->setColuna($tabela.'.criado') // padrão
				 ->setColuna($tabela.'.status')
				 ;

			//===========================================================
			//MONTO INNER JOIN ****-
			//$this->setInner(''); // Inner join

			//===========================================================
			//MONTO PESQUISA SIMPLES ****-
			if(!empty($query_pesquisa) && $query_pesquisa != "Pesquisa Avançada"){

				//PESQUISA SIMPLES
				if($conf_personalizada == false){
					$array_pesq[] = $this->return_where_int($tabela.'.id', '=' ,$query_pesquisa);
					$array_pesq[] = $this->return_where_like($tabela.'.status',$query_pesquisa);
					$where = $this->return_params_mont($array_pesq,'OR',true);
				}

				//SETO O WHERE
				$this->setWhere($where);
			}


			//===========================================================
			//MONTO PESQUISA AVANÇADA ****-
			if(!empty($query_pesquisa_avancada) && $query_pesquisa == "Pesquisa Avançada"){

				//PERIODO DE DATA
				$array_pesq1[] = $this->return_where_data($tabela.'.'.$query_pesquisa_avancada['periodo_tipo_data'],$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_de'],'Y-m-d'),$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_ate'],'Y-m-d'));
				$array_pesq1[] = $this->return_where_like($tabela.'.campo',$query_pesquisa_avancada['name_campo']);
				$where = $this->return_params_mont($array_pesq1,'AND',true);

				//SETO O WHERE
				$this->setWhere($where);
			}

			//===========================================================
			//MONTO O GROUP BY
			$this->setGroup($this->return_params_mont($group_by,',','false'));

			//===========================================================
			//MONTO O ORDER BY ****-
			for ($i=0; $i <count($order_by) ; $i++) {

				//CAMPO ID CRESCENTE
				if($order_by[$i] == 'ID CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.id','ASC');
				}
				//CAMPO ID DECRESCENTE
				if($order_by[$i] == 'ID DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.id','DESC');
				}
				//CAMPO CADASTRO CRESCENTE
				if($order_by[$i] == 'DATA CADASTRO CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.criado','ASC');
				}
				//CAMPO CADASTRO DECRESCENTE
				if($order_by[$i] == 'DATA CADASTRO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.criado','DESC');
				}
				//CAMPO EDITADO CRESCENTE
				if($order_by[$i] == 'DATA MODIFICADO CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.modificado','ASC');
				}
				//CAMPO EDITADO DECRESCENTE
				if($order_by[$i] == 'DATA MODIFICADO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.modificado','DESC');
				}
				//CAMPO STATUS CRESCENTE
				if($order_by[$i] == 'STATUS CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.status','ASC');
				}
				//CAMPO STATUS DECRESCENTE
				if($order_by[$i] == 'STATUS DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.status','DESC');
				}
			}
			$this->setOrder($this->return_params_mont($array_orderby,',','false'));


			//===========================================================
			//MONTO O LIMIT
			if(empty($limit)){
				$this->setLimit($inicio.','.$quantidade);
			}

			//===========================================================
			//EXECUTO A QUERY
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array[] = $exec[$i];
				/*
				$array[] = array(
					'id'         => $exec[$i]['id'],
					'modificado' => $exec[$i]['modificado'],
					'criado'     => $exec[$i]['criado'],
				);*/
			}
			return $array;
		}





//***************************************************************************************************************************************************************
//FUNÇÃO SETO DADOS DO EDITAR RETORNA ARRAY
//***************************************************************************************************************************************************************
		function set_editar(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getTabela()); // Tabela
			$this->setColuna('*'); // Colunas ****-
			//$this->setInner(); // Inner ****-
			$this->setWhere("{$this->getTabela()}.id={$this->getId()}"); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return $exec;
			}else{
				return false;
			}
		}





//***************************************************************************************************************************************************************
//FUNÇÃO ADICIONAR
//***************************************************************************************************************************************************************
		function inserir(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getTabela()); // Tabela
			$this->setColuna('status'); // Colunas ****-
			$this->setValue($this->getCampos('status')); //Valores ****-
			$exec = $this->Create();
			$this->limpo_campos();
			$this->setUltimo_id($this->get_last_id());
			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}





//***************************************************************************************************************************************************************
//FUNÇÃO EDITAR
//***************************************************************************************************************************************************************
		function editar(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getTabela()); // Tabela
			$url_imagem = $this->getCampos('img_fundo_login');
			if(!empty($url_imagem)){
				$this
				->setColuna('modificado')
				->setColuna('smtp_host')
				->setColuna('smtp_username')
				->setColuna('smtp_senha')
				->setColuna('smtp_porta')
				->setColuna('smtp_tls')
				->setColuna('smtp_debug')
				->setColuna('email_principal')
				->setColuna('email_assinatura')
				->setColuna('sms_username')
				->setColuna('sms_senha')
				->setColuna('telefone_principal')
				->setColuna('nome_logo_admin')
				->setColuna('versao_aplicacao')
				->setColuna('tema_admin')
				->setColuna('img_fundo_login')
				->setColuna('smtp_nome')
				->setColuna('sms_nome')
				;
				$this
				->setValue($this->getModificado())
				->setValue($this->getCampos('smtp_host'))
				->setValue($this->getCampos('smtp_username'))
				->setValue($this->getCampos('smtp_senha'))
				->setValue($this->getCampos('smtp_porta'))
				->setValue($this->getCampos('smtp_tls'))
				->setValue($this->getCampos('smtp_debug'))
				->setValue($this->getCampos('email_principal'))
				->setValue($this->getCampos('email_assinatura'))
				->setValue($this->getCampos('sms_username'))
				->setValue($this->getCampos('sms_senha'))
				->setValue($this->getCampos('telefone_principal'))
				->setValue($this->getCampos('nome_logo_admin'))
				->setValue($this->getCampos('versao_aplicacao'))
				->setValue($this->getCampos('tema_admin'))
				->setValue($this->getCampos('img_fundo_login'))
				->setValue($this->getCampos('smtp_nome'))
				->setValue($this->getCampos('sms_nome'))
				;
			}else{
				$this
				->setColuna('modificado')
				->setColuna('smtp_host')
				->setColuna('smtp_username')
				->setColuna('smtp_senha')
				->setColuna('smtp_porta')
				->setColuna('smtp_tls')
				->setColuna('smtp_debug')
				->setColuna('email_principal')
				->setColuna('email_assinatura')
				->setColuna('sms_username')
				->setColuna('sms_senha')
				->setColuna('telefone_principal')
				->setColuna('nome_logo_admin')
				->setColuna('versao_aplicacao')
				->setColuna('tema_admin')
				->setColuna('smtp_nome')
				->setColuna('sms_nome')
				->setColuna('modo_sistema')
				;
				$this
				->setValue($this->getModificado())
				->setValue($this->getCampos('smtp_host'))
				->setValue($this->getCampos('smtp_username'))
				->setValue($this->getCampos('smtp_senha'))
				->setValue($this->getCampos('smtp_porta'))
				->setValue($this->getCampos('smtp_tls'))
				->setValue($this->getCampos('smtp_debug'))
				->setValue($this->getCampos('email_principal'))
				->setValue($this->getCampos('email_assinatura'))
				->setValue($this->getCampos('sms_username'))
				->setValue($this->getCampos('sms_senha'))
				->setValue($this->getCampos('telefone_principal'))
				->setValue($this->getCampos('nome_logo_admin'))
				->setValue($this->getCampos('versao_aplicacao'))
				->setValue($this->getCampos('tema_admin'))
				->setValue($this->getCampos('smtp_nome'))
				->setValue($this->getCampos('sms_nome'))
				->setValue($this->getCampos('modo_sistema'))
				;
			}
			$this->setWhere("id={$this->getId()}"); // ****-
			$exec = $this->Update();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}




//***************************************************************************************************************************************************************
//FUNÇÃO DETALHAMENTO
//***************************************************************************************************************************************************************
		function detalhamento(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getTabela()); // Tabela
			$this->setColuna('*'); // Colunas ****-
			$this->setInner(); // Inner ****-
			$this->setWhere("{$this->getTabela()}.id={$this->getId()}"); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return $exec;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//RETORNO CONFIGURAÇÕES DO SISTEMA
//***************************************************************************************************************************************************************
		function retorno_configs_admin(){
			$this->setTable('adm_configuracoes'); // Tabela
			$this->setColuna('*');
			$this->setWhere("id = 1"); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array[] = $exec[$i];
			}
			if(count($exec) >= 1){
				return $array;
			}else{
				return false;
			}
		}



}
