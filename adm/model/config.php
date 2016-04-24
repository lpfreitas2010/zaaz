<?php

	/**
	* Configurações do Model
	*
	* @author Fernando
	* @version 2.0.0
	**/

	//CLASSE
	class config_model_adm extends conexao {

		protected $id;
		protected $criado;
		protected $modificado;
		protected $status_id;
		protected $tabela;
		protected $campos = array();
		protected $ultimo_id;

		public function getUltimo_id(){
			return $this->ultimo_id;
		}
		public function setUltimo_id($ultimo_id){
			$this->ultimo_id = $ultimo_id;
			return $this;
		}

	    public function getId(){
	        return $this->id;
	    }
	    public function setId($id){
	        $this->id = $id;
	        return $this;
	    }
	    public function getCriado(){
	        return date('Y-m-d H:i:s');
	    }
	    public function setCriado($criado){
	        $this->criado = $criado;
	        return $this;
	    }
	    public function getModificado(){
	        return date('Y-m-d H:i:s');
	    }
	    public function setModificado($modificado){
	        $this->modificado = $modificado;
	        return $this;
	    }
	    public function getStatus_id(){
	        return $this->status_id;
	    }
	    public function setStatus_id($status_id){
	        $this->status_id = $status_id;
	        return $this;
	    }
	    public function getTabela(){
	        return $this->tabela;
	    }
	    public function setTabela($tabela){
	        $this->tabela = $tabela;
	        return $this;
	    }
		public function getCampos($indice){
	        return $this->campos[$indice];
	    }
	    public function setCampos($indice,$valor){
	        $this->campos[$indice] = $valor;
	        return $this;
	    }
		public function getLimpoCampos(){
	        unset($this->campos);
	    }




		//=================================================================
		//FUNÇÃO DE ATIVAR E DESATIVAR REGISTRO
		function ativar_desativar(){
			$this->carrego_parametros(); // Carrego parametros
			$tabela = $this->getCampos('tabela'); // Pego valor
			if(empty($tabela)){
				$tabela = $this->getTabela(); // Pego valor
			}
			$campo_id = $this->getCampos('campo_id'); // Pego valor
			if(empty($campo_id)){
				$campo_id = 'id'; // Pego valor
			}
			$this->setTable($tabela); // Tabela
			$this->setColuna('status_id')->setColuna('modificado'); // Colunas
			$this->setValue($this->getStatus_id())->setValue($this->getModificado()); // Valores
			$this->setWhere("{$campo_id} = {$this->getId()}"); // Where
			$exec = $this->Update(); // Executo
			$this->limpo_campos();
			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}

		//=================================================================
		//FUNÇÃO PARA EXCLUIR TODOS OS REGISTROS
		function excluir_tudo(){
			$this->carrego_parametros(); // Carrego parametros
			$exec = $this->Read("TRUNCATE TABLE {$this->getTabela()}"); //Executo comando
			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}

		//=================================================================
		//FUNÇÃO DE EXCLUIR REGISTRO
		function excluir(){
			$this->carrego_parametros(); // Carrego parametros
			$tabela = $this->getCampos('tabela'); // Pego valor
			if(empty($tabela)){
				$tabela = $this->getTabela(); // Pego valor
			}
			$campo_id = $this->getCampos('campo_id'); // Pego valor
			if(empty($campo_id)){
				$campo_id = 'id'; // Pego valor
			}
			$this->setTable($tabela); // Tabela
			$this->setWhere("{$campo_id} = {$this->getId()}"); // Where
			$exec = $this->Delete(); // Executo
			$this->limpo_campos();
			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}

		//=================================================================
		//FUNÇÃO GENÉRICA QUE RETORNA TRUE OR FALSE
		function select_simples_retorna_true_false(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getCampos('campo_tabela')); // Tabela
			$this->setColuna($this->getCampos('campo_coluna')); // Colunas
			$inner_join = $this->getCampos('campo_inner_join'); // Inner
			if(!empty($inner_join)){
				$this->setInner($inner_join); // Inner
			}
			$this->setWhere($this->getCampos('campo_where')); // Where
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}

		//=================================================================
		//FUNÇÃO GENÉRICA QUE RETORNA ARRAY
		function select_simples_retorna_array(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getCampos('campo_tabela')); // Tabela
			$this->setColuna($this->getCampos('campo_coluna')); // Colunas
			$coluna2 = $this->getCampos('campo_coluna2');
			if(!empty($coluna2)){
				$this->setColuna($this->getCampos('campo_coluna2')); // Colunas
			}
			$inner_join = $this->getCampos('campo_inner_join'); // Inner
			if(!empty($inner_join)){
				$this->setInner($inner_join); // Inner
			}
			$this->setWhere($this->getCampos('campo_where')); // Where
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return $exec;
			}else{
				return false;
			}
		}

		//=================================================================
		//FUNÇÃO GENÉRICA QUE RETORNA STRING
		function select_simples_retorna_array_mont(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getCampos('campo_tabela')); // Tabela
			$this->setColuna($this->getCampos('campo_coluna')); // Colunas
			$inner_join = $this->getCampos('campo_inner_join'); // Inner
			if(!empty($inner_join)){
				$this->setInner($inner_join); // Inner
			}
			$this->setWhere($this->getCampos('campo_where')); // Where
			$this->setGroup($this->getCampos('campo_groupby')); // Group by
			$this->setOrder($this->getCampos('campo_orderby')); // Order by
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array[] = $exec[$i][$this->getCampos('campo_coluna')];
			}
			if(count($exec) >= 1){
				return $array;
			}else{
				return false;
			}
		}

		//=================================================================
		//FUNÇÃO GENÉRICA QUE RETORNA STRING
		function select_simples_retorna_array_mont_vcol(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getCampos('campo_tabela')); // Tabela
			$this->setColuna($this->getCampos('campo_coluna')); // Colunas
			$this->setColuna($this->getCampos('campo_coluna2')); // Colunas
			$coluna3 = $this->getCampos('campo_coluna3');
			if(!empty($coluna3)){
				$this->setColuna($this->getCampos('campo_coluna3')); // Colunas
			}
			$inner_join = $this->getCampos('campo_inner_join'); // Inner
			if(!empty($inner_join)){
				$this->setInner($inner_join); // Inner
			}
			$this->setWhere($this->getCampos('campo_where')); // Where
			$this->setGroup($this->getCampos('campo_groupby')); // Group by
			$this->setOrder($this->getCampos('campo_orderby')); // Order by
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

		//=================================================================
		//FUNÇÃO DE EXCLUIR GENÉRICA
		function excluir_geral(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getCampos('campo_tabela')); // Tabela
			$this->setWhere("{$this->getCampos('campo_id')} = {$this->getId()}"); // Where
			$exec = $this->Delete(); // Executo
			$this->limpo_campos();
			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}

		//=================================================================
		//FUNÇÃO DE UPDATE GENÉRICA
		function update_geral(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getCampos('campo_tabela')); // Tabela
			$this->setColuna($this->getCampos('campo_coluna')); // Colunas
			$this->setValue($this->getCampos('campo_value')); // Valores
			$this->setWhere("{$this->getCampos('campo_id')} = {$this->getId()}"); // Where
			$exec = $this->Update(); // Executo
			$this->limpo_campos();
			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}





//***************************************************************************************************************************************************************
//FUNÇÃO RETORNA STRING PARA VERIFICAÇÃO
//***************************************************************************************************************************************************************
		function retorn_campo_editar($campo, $valor){
			$this->carrego_parametros(); // Carrego parametros
			$tabela = $this->getCampos('tabela_retorn_campo_editar'); // Pego valor
			if(empty($tabela)){
				$tabela = $this->getTabela(); // Pego valor
			}
			$this->setTable($tabela); // Tabela
			$this->setColuna($campo); // Colunas ****-
			$this->setWhere(" {$campo} = '{$valor}'  "); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return $exec[0][$campo];
			}else{
				return 'false';
			}
		}





//***************************************************************************************************************************************************************
//FUNÇÃO RETORNA STRING PARA VERIFICAÇÃO PELO ID
//***************************************************************************************************************************************************************
		function retorn_campo_editar_val_id($campo){
			$this->carrego_parametros(); // Carrego parametros
			$tabela = $this->getCampos('tabela_retorn_campo_editar_val_id'); // Pego valor
			if(empty($tabela)){
				$tabela = $this->getTabela(); // Pego valor
			}
			$campo_id = $this->getCampos('campo_id_retorn_campo_editar_val_id'); // Pego valor
			if(empty($campo_id)){
				$campo_id = 'id'; // Pego valor
			}
			$this->setTable($tabela); // Tabela
			$this->setColuna($campo); // Colunas ****-
			$this->setWhere(" {$campo_id} = {$this->getId()}  "); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return $exec[0][$campo];
			}else{
				return 'false';
			}
		}






}
