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
	class modulos_acoesModel extends config_model_adm {


//***************************************************************************************************************************************************************
//CARREGO PARAMETROS
//***************************************************************************************************************************************************************
		function carrego_parametros(){
			$this->setTabela('adm_usuario_permissoes_acoes'); // Tabela ****-
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
			$this->setColuna($tabela.'.id')
				 ->setColuna($tabela.'.modificado')
				 ->setColuna($tabela.'.criado')
				 ->setColuna($tabela.'.acoes')
				 ;

			//===========================================================
			//MONTO INNER JOIN ****-
			//$this->setInner(''); // Inner join

			//===========================================================
			//MONTO PESQUISA SIMPLES ****-
			if(!empty($query_pesquisa) && $query_pesquisa != "Pesquisa Avançada"){

				//PESQUISA SIMPLES
				if($conf_personalizada == false){
					//MONTAGEM DE QUERYS AUTOMATIZADA
					$array_pesq[] = $this->return_where_int($tabela.'.id', '=' ,$query_pesquisa);
					$array_pesq[] = $this->return_where_like($tabela.'.acoes',$query_pesquisa);
					$where = $this->return_params_mont($array_pesq,'OR',true);
				}

				//SETO O WHERE
				$this->setWhere($where);
			}


			//===========================================================
			//MONTO PESQUISA AVANÇADA ****-
			if(!empty($query_pesquisa_avancada) && $query_pesquisa == "Pesquisa Avançada"){

				//PERIODO DE DATA
				$where = $this->return_where_data($tabela.'.'.$query_pesquisa_avancada['periodo_tipo_data'],$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_de'],'Y-m-d'),$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_ate'],'Y-m-d'));

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
				//CAMPO Módulo CRESCENTE
				if($order_by[$i] == 'AÇÃO CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.acoes','ASC');
				}
				//CAMPO Módulo DECRESCENTE
				if($order_by[$i] == 'AÇÃO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.acoes','DESC');
				}
				//CAMPO CADASTRO CRESCENTE
				if($order_by[$i] == 'CADASTRO CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.criado','ASC');
				}
				//CAMPO CADASTRO DECRESCENTE
				if($order_by[$i] == 'CADASTRO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.criado','DESC');
				}
				//CAMPO CADASTRO CRESCENTE
				if($order_by[$i] == 'MODIFICADO CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.modifcado','ASC');
				}
				//CAMPO CADASTRO DECRESCENTE
				if($order_by[$i] == 'MODIFICADO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.modifcado','DESC');
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
			$this->setColuna('acoes'); // Colunas ****-
			$this->setValue($this->getCampos('acoes')); //Valores ****-
			$exec = $this->Create();
			$this->limpo_campos();
			$this->setUltimo_id($this->get_last_id());
			$this->inserir_restricoes();
			$this->inserir_restricoes_usuarios();
			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//FUNÇÃO ADICIONAR RESTRIÇÕES
//***************************************************************************************************************************************************************
		function inserir_restricoes(){

			//FOR DE PERMISSÕES
			$this->setTable('adm_usuario_permissoes_acoes'); // Tabela
			$this->setColuna('acoes')->setColuna('id'); // Colunas ****-
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){

				//FOR DE MODULOS
				$this->setTable('adm_usuario_modulo'); // Tabela
				$this->setColuna('id'); // Colunas ****-
				$exec4 = $this->Read();
				$this->limpo_campos();
				for($i4=0;$i4<count($exec4);$i4++){

					//VERIFICO SE JA ESTA CADASTRADO
					if($this->verifica_restricao(null,$exec4[$i4]['id'],$exec[$i]['id']) == false){

						//INSERIR
						$this->setTable('adm_usuario_modulo_opcoes_default'); // Tabela
						$this->setColuna('adm_usuario_modulo_id')->setColuna('adm_usuario_permissoes_acoes_id')->setColuna('opcoes'); // Colunas ****-
						$this->setValue($exec4[$i4]['id'])->setValue($exec[$i]['id'])->setValue(0); //Valores ****-
						$exe2 = $this->Create();
						$this->limpo_campos();
					}
				}
			}
			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//FUNÇÃO VERIFICA SE JA TEM CADASTRO
//***************************************************************************************************************************************************************
		function verifica_restricao($campo1,$campo2,$campo3){
			$this->setTable('adm_usuario_modulo_opcoes_default'); // Tabela
			$this->setColuna('adm_usuario_modulo_id'); // Colunas ****-
			$this->setWhere(" (adm_usuario_modulo_id = ".$campo2.") AND (adm_usuario_permissoes_acoes_id = ".$campo3.") "); // ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec)>=1){
				return true;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//FUNÇÃO ADICIONAR RESTRIÇÕES USUARIOS
//***************************************************************************************************************************************************************
		function inserir_restricoes_usuarios(){
			$this->carrego_parametros(); // Carrego parametros

			//FOR DE PERMISSÕES
			$this->setTable('adm_usuario'); // Tabela
			$this->setColuna('id'); // Colunas ****-
			$this->setWhere(" id != 1 "); // Where ****-
			$exec2 = $this->Read();
			$this->limpo_campos();
			for($i2=0;$i2<count($exec2);$i2++){

				//QUERY QUE LISTA O NIVEL E PEGA O PADRÃO DEFAULT
				$this->setTable('adm_usuario_modulo_opcoes_default'); // Tabela
				$this->setColuna('*'); // Colunas ****-
				$exec4 = $this->Read();
				$this->limpo_campos();
				for($i0=0;$i0<count($exec4);$i0++){

					//VERIFICO SE JA ESTA CADASTRADO
					if($this->verifica_restricao_usuarios($exec2[$i2]['id'],$exec4[$i0]['adm_usuario_modulo_id'],$exec4[$i0]['adm_usuario_permissoes_acoes_id']) == false){

						//INSIRO NA TABELA ADM_USUARIO_MODULO_OPCOES
						$this->setTable('adm_usuario_modulo_opcoes'); // Tabela
						$this->setColuna('adm_usuario_id')->setColuna('adm_usuario_modulo_id')->setColuna('adm_usuario_permissoes_acoes_id')->setColuna('opcoes'); // Colunas ****-
						$this->setValue($exec2[$i2]['id'])->setValue($exec4[$i0]['adm_usuario_modulo_id'])->setValue($exec4[$i0]['adm_usuario_permissoes_acoes_id'])->setValue($exec4[$i0]['opcoes']); //Valores ****-
						$exec5 = $this->Create();
						$this->limpo_campos();
					}
				}
			}
			if(count($exec1) >= 1){
				return true;
			}else{
				return false;
			}
		}

//***************************************************************************************************************************************************************
//FUNÇÃO VERIFICA SE JA TEM CADASTRO
//***************************************************************************************************************************************************************
		function verifica_restricao_usuarios($campo1,$campo2,$campo3){
			$this->setTable('adm_usuario_modulo_opcoes'); // Tabela
			$this->setColuna('adm_usuario_id')->setColuna('adm_usuario_modulo_id'); // Colunas ****-
			$this->setWhere(" (adm_usuario_id = ".$campo1.") AND (adm_usuario_modulo_id = ".$campo2.") AND (adm_usuario_permissoes_acoes_id = ".$campo3.") "); // ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec)>=1){
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
				$this->setColuna('modificado')->setColuna('acoes'); // Colunas ****-
				$this->setValue($this->getModificado())->setValue($this->getCampos('acoes')); //Valores ****-
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
//FUNÇÃO ADICIONAR MODAL
//***************************************************************************************************************************************************************
		function inserir_modal_teste(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_usuario'); // Tabela ****-
			$this->setColuna('status_id')->setColuna('nome'); // Colunas ****-
			$this->setValue(2)->setValue($this->getCampos('modal_teste')); //Valores ****-
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





}
