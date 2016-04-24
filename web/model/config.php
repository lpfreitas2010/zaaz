<?php

	/**
	* Configurações do Model
	*
	* @author Fernando
	* @version 2.0.0
	**/

	//CLASSE
	class config_model extends conexao {

		protected $id;
		protected $criado;
		protected $modificado;
		protected $status_id;
		protected $tabela;

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



	}
