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
	class adm_usuario_nivelModel extends config_model_adm {


//***************************************************************************************************************************************************************
//CARREGO PARAMETROS
//***************************************************************************************************************************************************************
		function carrego_parametros(){
			$this->setTabela('adm_usuario_nivel'); // Tabela ****-
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
				 ->setColuna($tabela.'.nivel')
				 ;

			//===========================================================
			//MONTO INNER JOIN ****-
			//$this->setInner(''); // Inner join

			//TRATO RESTRIÇÃO
			if($_SESSION['adm_id_cargo'] != 1) {

				//SETO O WHERE
				$where_ = "(".$tabela.".id != 1 )";
				$this->setWhere($where_);
			}

			//===========================================================
			//MONTO PESQUISA SIMPLES ****-
			if(!empty($query_pesquisa) && $query_pesquisa != "Pesquisa Avançada"){

				//PESQUISA SIMPLES
				if($conf_personalizada == false){
					//MONTAGEM DE QUERYS AUTOMATIZADA
					$array_pesq[] = $this->return_where_int($tabela.'.id', '=' ,$query_pesquisa);
					$array_pesq[] = $this->return_where_like($tabela.'.nivel',$query_pesquisa);
					$where = $this->return_params_mont($array_pesq,'OR',true);
					if($_SESSION['adm_id_cargo'] != 1) {
						$where = $where_." AND ".$where;
					}
				}

				//SETO O WHERE
				$this->setWhere($where);
			}


			//===========================================================
			//MONTO PESQUISA AVANÇADA ****-
			if(!empty($query_pesquisa_avancada) && $query_pesquisa == "Pesquisa Avançada"){

				//PERIODO DE DATA
				$where = $this->return_where_data($tabela.'.'.$query_pesquisa_avancada['periodo_tipo_data'],$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_de'],'Y-m-d'),$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_ate'],'Y-m-d'));
				if($_SESSION['adm_id_cargo'] != 1) {
					$where = $where_." AND ".$where;
				}

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
				//CAMPO NIVEL CRESCENTE
				if($order_by[$i] == 'CARGO CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.nivel','ASC');
				}
				//CAMPO NIVEL DECRESCENTE
				if($order_by[$i] == 'CARGO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.nivel','DESC');
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
					$array_orderby[] = $this->return_order_by($tabela.'.modificado','ASC');
				}
				//CAMPO CADASTRO DECRESCENTE
				if($order_by[$i] == 'MODIFICADO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.modificado','DESC');
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
			$this->setColuna('nivel')->setColuna('nome_logo_admin')->setColuna('tema_admin'); // Colunas ****-
			$this->setValue($this->getCampos('nivel'))->setValue($this->getCampos('nome_logo_admin'))->setValue($this->getCampos('tema_admin')); //Valores ****-
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
				$this->setColuna('modificado')->setColuna('nivel')->setColuna('nome_logo_admin')->setColuna('tema_admin'); // Colunas ****-
				$this->setValue($this->getModificado())->setValue($this->getCampos('nivel'))->setValue($this->getCampos('nome_logo_admin'))->setValue($this->getCampos('tema_admin')); //Valores ****-
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
//FUNÇÃO retorno cargo usuario
//***************************************************************************************************************************************************************
		function retorno_cargo_usuario($id){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_usuario_nivel_usuario'); // Tabela
			$this->setColuna('adm_usuario_nivel_id'); // Colunas ****-
			$this->setWhere("adm_usuario_nivel_usuario.adm_usuario_id = {$id}"); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$string = $exec[$i]['adm_usuario_nivel_id'];
			}
			if(count($exec) >= 1){
				return $string;
			}else{
				return false;
			}
		}





}
