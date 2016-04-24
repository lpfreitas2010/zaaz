<?php

	/**
	* Classe com conexão ao banco de dados PDO
	*
	* @author Fernando
	* @version 2.0.0
	**/

	//=================================================================
	//INCLUDE
	require_once (dirname(dirname((dirname(__FILE__))))).'/libs/core/core.php';
	//=================================================================

	//Classe
	class conexao {

		//Parametros gerais
		public $last_id;
		public $rowCount;
		public $msgError;
		public $conexoes;
		public $table;
		public $livre;
		public $value;
		public $coluna;
		public $inner;
		public $where;
		public $where_value;
		public $order;
		public $group;
		public $limit;

		//Variavel de Controle
		private $funcoes;
		private $core;
		private $include;
		private $logs;
		private $msg_error_db;
		private $logs_bd_txt;

		public function set_last_id($last_id){ //Último ID
			$this->last_id = $last_id;
		}
		public function get_last_id(){
			return $this->last_id;
		}
		public function set_rowCount($rowCount){ //Total de Rows
			$this->rowCount = $rowCount;
		}
		public function get_rowCount(){
			return $this->rowCount;
		}
		public function set_msgError($msgError){ //Mensagem de erro
			$this->msgError = $msgError;
		}
		public function get_msgError(){
			return $this->msgError;
		}
		public function getMsg_error_db()
		{
			return $this->msg_error_db;
		}
		public function setMsg_error_db($msg_error_db)
		{
			$this->msg_error_db = $msg_error_db;

			return $this;
		}
		public function getLogs_bd_txt()
		{
			return $this->logs_bd_txt;
		}
		public function setLogs_bd_txt($logs_bd_txt)
		{
			$this->logs_bd_txt = $logs_bd_txt;
			return $this;
		}

		public function getConexoes()
		{
			return $this->conexoes;
		}
		public function setConexoes($conexoes)
		{
			$this->conexoes = $conexoes;
			return $this;
		}


		public function getTable()
		{
			return $this->table;
		}
		public function setTable($table)
		{
			$this->table = $table;
			return $this;
		}
		public function getValue()
		{
			return $this->value;
		}
		public function setValue($value)
		{
			$this->value[] = $value;
			return $this;
		}
		public function getColuna()
		{
			return $this->coluna;
		}
		public function setColuna($coluna = null)
		{
			$this->coluna[] = $coluna;
			return $this;
		}

		public function getLivre()
		{
			return $this->livre;
		}
		public function setLivre($livre)
		{
			$this->livre = $livre;
			return $this;
		}

		public function getInner()
		{
			return $this->inner;
		}
		public function setInner($inner = null)
		{
			$this->inner = $inner;
			return $this;
		}

		public function getWhere()
		{
			return $this->where;
		}
		public function setWhere($where = null)
		{
			$this->where = $where;
			return $this;
		}
		public function getWhere_value()
		{
			return $this->where_value;
		}
		public function setWhere_value($where_value = null)
		{
			$this->where_value[] = $where_value;
			return $this;
		}

		public function getOrder()
		{
			return $this->order;
		}
		public function setOrder($order = null)
		{
			$this->order = $order;
			return $this;
		}

		public function getGroup()
		{
			return $this->group;
		}
		public function setGroup($group = null)
		{
			$this->group = $group;
			return $this;
		}

		public function getLimit()
		{
			return $this->limit;
		}
		public function setLimit($limit = null)
		{
			$this->limit = $limit;
			return $this;
		}

		//Construct
		function __construct() {
			$this->core    = new core();    //Instancio Includes
			$this->logs    = new logs();    //Instancio Logs
			$this->funcoes = new funcoes(); //Instancio Funções
		}

		//=================================================================
		//CONECTO O BANCO DE DADOS
		private function Connect () {

			//OUTRAS CONEXÕES
			$conexoess = $this->getConexoes();
			if(!empty($conexoess)){
				$user_db      = $this->core->get_config($conexoess,'user_db');
				$password_db  = $this->core->get_config($conexoess,'password_db');
				$host_db      = $this->core->get_config($conexoess,'host_db');
				$database_db  = $this->core->get_config($conexoess,'database_db');
				$this->setMsg_error_db($this->core->get_config($conexoess,'msg_error_db'));
				$this->setLogs_bd_txt($this->core->get_config($conexoess,'logs_bd_txt'));

			}else{

				//SERVIDOR
				if($this->core->get_config('servidor_ativo') === "servidor"){
					$user_db      = $this->core->get_config('default','user_db');
					$password_db  = $this->core->get_config('default','password_db');
					$host_db      = $this->core->get_config('default','host_db');
					$database_db  = $this->core->get_config('default','database_db');
					$this->setMsg_error_db($this->core->get_config('default','msg_error_db'));
					$this->setLogs_bd_txt($this->core->get_config('default','logs_bd_txt'));
				}

				//LOCAL
				if($this->core->get_config('servidor_ativo') === "local"){
					$user_db      = $this->core->get_config('local','user_db');
					$password_db  = $this->core->get_config('local','password_db');
					$host_db      = $this->core->get_config('local','host_db');
					$database_db  = $this->core->get_config('local','database_db');
					$this->setMsg_error_db($this->core->get_config('local','msg_error_db'));
					$this->setLogs_bd_txt($this->core->get_config('local','logs_bd_txt'));
				}
			}

			//INICIO A CONEXÃO
			try{

				$utf  = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
				$conn = new PDO("mysql:host=$host_db;dbname=$database_db",$user_db, $password_db, $utf);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;

			}catch(PDOException $e){

				//GRAVO LOG DE ERRO
				if($this->getLogs_bd_txt() == true){
						$this->logs->setApp('')->setNome_arquivo('bd')->setMensagem($this->core->get_msg_array('error_connection_bd',$e->getMessage()))->gravo_log_txt();
				}

				//MOSTRO MENSAGEM DE ERRO
				if($this->getMsg_error_db() == true){
					$this->core->mensagem_erro($this->core->get_msg_array('error_connection_bd',$e->getMessage()));
				}
				exit();
			}
		}

		//=================================================================
		//MÉTODO CREATE - insere valores no bd retorna true or false e último id inserido
		public function Create(){

			//MONTO A QUERY
			$table        = $this->getTable();
			$coluna_array = $this->getColuna();
			$valor_array  = $this->getValue();
			$coluna       = implode(", ", array_values($coluna_array));
			$valor        = ":".implode(", :", array_values($coluna_array));
			$query        = ("INSERT INTO {$table} ({$coluna}) VALUES ({$valor})"); //AES_ENCRYPT(valor,secret_key)

			//EXECUTO A QUERY
			try{

				$conexao = $this->Connect(); //Conecto no bd

				//PREPARED STATEMENT
				$stm = $conexao->prepare($query);
				for ($i=0; $i < count($coluna_array) ; $i++) {
					$stm->bindParam(':'.$coluna_array[$i], $valor_array[$i]);
				}
				$exec = $stm->execute(); //Executo
				$this->set_last_id($conexao->lastInsertId()); //Id que acabou de ser inserido
				return $exec;

			}catch (PDOException $e){

				//GRAVO LOG DE ERRO
				if($this->getLogs_bd_txt() == true){
					$message = $e->getMessage();
					$this->logs->setApp('')->setNome_arquivo('bd')->setMensagem($this->core->get_msg_array('error_query_bd',"{$message},{$query}"))->gravo_log_txt();
				}

				//RETORNO MENSAGEM DE ERRO
				if($this->getMsg_error_db() == true){
					$message = $e->getMessage();
					$this->set_msgError($this->core->get_msg_array('error_query_bd',"{$message},{$query}"));
					return false;
				}

			}
		}

		//=================================================================
		//MÉTODO READ - retorno lista com valores em array
		public function Read($sql = null){

			//MONTO A QUERY
			if(empty($sql)){

				//PARAMETROS
				$select = $this->getColuna();
				$table  = $this->getTable();
				$inner  = $this->getInner();
				$where  = $this->getWhere();
				$group  = $this->getGroup();
				$order  = $this->getOrder();
				$limit  = $this->getLimit();

				//SELECT
				if($select == "*"){

				} else {
					$select = implode(", ", $select);
				}
				$order = ($order == null) ? null : "ORDER BY {$order}";
				$group = ($group == null) ? null : "GROUP BY {$group}";
				$limit = ($limit == null) ? null : "LIMIT {$limit}";
				$where = ($where == null) ? null : "WHERE {$where}";

				//MONTO A QUERY
				$query = "SELECT {$select} FROM {$table} {$inner} {$where} {$group} {$order} {$limit}";

			}else{
				//MONTO A QUERY LIVRE
				$query = $sql;
			}

			//EXECUTO A QUERY
			try{

				$conexao = $this->Connect(); //Conecto no bd
				$stm      = $conexao->prepare($query);
				$stm->execute(); //Executo
				return $stm->fetchAll(PDO::FETCH_ASSOC);

			}catch (PDOException $e){

				//GRAVO LOG DE ERRO
				if($this->getLogs_bd_txt() == true){
					$message = $e->getMessage();
					$this->logs->setApp('')->setNome_arquivo('bd')->setMensagem($this->core->get_msg_array('error_query_bd',"{$message},{$query}"))->gravo_log_txt();
				}

				//RETORNO MENSAGEM DE ERRO
				if($this->getMsg_error_db() == true){
					$message = $e->getMessage();
					$this->set_msgError($this->core->get_msg_array('error_query_bd',"{$message},{$query}"));
					return false;
				}

			}

		}

		//=================================================================
		//MÉTODO UPDATE - altera os valores no bd retorna true or false
		public function Update(){

			//PARAMETROS
			$table       = $this->getTable();
			$coluna      = $this->getColuna();
			$valor       = $this->getValue();
			$where       = $this->getWhere();

			//VERIFICO SE ID EXISTE
			$exec = $this->Read("SELECT * FROM {$table} WHERE {$where}");
			if(count($exec) >= 1){

				//MONTO A QUERY
				for ($i=0; $i < count($coluna); $i++) {
					$campos[] = "{$coluna[$i]}=:{$coluna[$i]}";
				}
				$campos = implode(", ", $campos);
				$query  = "UPDATE {$table} SET {$campos} WHERE {$where}";

				//EXECUTO A QUERY
				try{

					$conexao = $this->Connect(); //Conecto no bd
					$stm     = $conexao->prepare($query);

					//PREPARED STATEMENTS SET
					for ($i=0; $i < count($coluna) ; $i++) {
						$stm->bindParam(':'.$coluna[$i], $valor[$i]);
					}
					$exec = $stm->execute(); //Executo
					$this->set_rowCount($stm->rowCount());
					return $exec;

				}catch (PDOException $e){

					//GRAVO LOG DE ERRO
					if($this->getLogs_bd_txt() == true){
						$message = $e->getMessage();
						$this->logs->setApp('')->setNome_arquivo('bd')->setMensagem($this->core->get_msg_array('error_query_bd',"{$message},{$query}"))->gravo_log_txt();
					}

					//RETORNO MENSAGEM DE ERRO
					if($this->getMsg_error_db() == true){
						$message = $e->getMessage();
						$this->set_msgError($this->core->get_msg_array('error_query_bd',"{$message},{$query}"));
						return false;
					}

				}

			}else{

				//GRAVO LOG DE ERRO
				if($this->getLogs_bd_txt() == true){
					$this->logs->setApp('')->setNome_arquivo('bd')->setMensagem($this->core->get_msg_array('error_id_bd',$query))->gravo_log_txt();
				}
				//RETORNO MENSAGEM DE ERRO
				if($this->getMsg_error_db() == true){
					$this->set_msgError($this->core->get_msg_array('error_id_bd',$table));
					return false;
				}

			}
		}

		//=================================================================
		//MÉTODO DELETE - excluir os valores no bd retorna true or false
		public function DELETE(){

			//PARAMETROS
			$table  = $this->getTable();
			$where  = $this->getWhere();

			//MONTO A QUERY
			$query = "DELETE FROM {$table} WHERE {$where}";

			//VERIFICO SE ID EXISTE
			$exec = $this->Read("SELECT * FROM {$table} WHERE {$where}");
			if(count($exec) >= 1){

				//EXECUTO A QUERY
				try{

					$conexao = $this->Connect(); //Conecto no bd
					$stm     = $conexao->prepare($query);
					$exec    = $stm->execute(); //Executo
					$this->set_rowCount($stm->rowCount());
					return $exec;

				}catch (PDOException $e){

					//GRAVO LOG DE ERRO
					if($this->getLogs_bd_txt() == true){
						$message = $e->getMessage();
						$this->logs->setApp('')->setNome_arquivo('bd')->setMensagem($this->core->get_msg_array('error_query_bd',"{$message},{$query}"))->gravo_log_txt();
					}

					//RETORNO MENSAGEM DE ERRO
					if($this->getMsg_error_db() == true){
						$message = $e->getMessage();
						$this->set_msgError($this->core->get_msg_array('error_query_bd',"{$message},{$query}"));
						return false;
					}

				}

			}else{

				//GRAVO LOG DE ERRO
				if($this->getLogs_bd_txt() == true){
						//$this->logs->setApp('')->setNome_arquivo('bd')->setMensagem($this->core->get_msg_array('error_id_bd',$query))->gravo_log_txt();
				}

				//RETORNO MENSAGEM DE ERRO
				if($this->getMsg_error_db() == true){
					$this->set_msgError($this->core->get_msg_array('error_id_bd',$table));
					return false;
				}

			}
		}

		//=================================================================
		//LIMPO CAMPOS
		public function limpo_campos(){
			$this->setTable(null);
			$this->setLivre(null);
			unset($this->value);
			unset($this->coluna);
			$this->setInner(null);
			$this->setWhere(null);
			unset($this->where_value);
			$this->setOrder(null);
			$this->setGroup(null);
			$this->setLimit(null);
		}

		//=================================================================
		//RETORNO CAMPO DATA BETWEEN MONTADO
		public function return_where_data($campo,$date1,$date2,$aspas = null){
			if(!empty($date1) && !empty($date2) && !empty($campo)){
				if(!empty($aspas)){
					return " ({$campo} BETWEEN {$date1} AND {$date2}) ";
				}else{
					return " ({$campo} BETWEEN '{$date1}' AND '{$date2}') ";
				}
			}
		}

		//=================================================================
		//RETORNO CAMPO WHERE LIKE MONTADO
		public function return_where_like($campo,$value){
			if(!empty($value)){
				return " ({$campo} LIKE '%{$value}%') ";
			}
		}

		//=================================================================
		//RETORNO CAMPO WHERE INT MONTADO
		public function return_where_int($campo,$param,$value){
			if(isset($value) && is_numeric($value)){
				return " ({$campo} {$param} {$value}) ";
			}
		}

		//=================================================================
		//RETORNO CAMPO WHERE DOUBLE MONTADO
		public function return_where_double($campo,$param,$value){
			if(!empty($value)){
				return " ({$campo} {$param} {$value}) ";
			}
		}

		//=================================================================
		//RETORNO CAMPO WHERE CHAR MONTADO
		public function return_where_char($campo,$param,$value){
			if(isset($value)){
				return " ({$campo} {$param} '{$value}') ";
			}
		}

		//=================================================================
		//RETORNO CAMPO ORDER BY MONTADO
		public function return_order_by($campo,$param){
			return "{$campo} {$param}";
			//Exemplos avançados
			//FIELD(NOME, "Categorias", "Subcategoria A", "Subcategoria B") DESC, ID DESC
			//NOME='Categoria A' DESC, ID DESC;
		}

		//=================================================================
		//MONTAGEM AUTOMATICA DE WHERE
		public function return_params_mont($array,$param,$parenteses = null){
			$total = count($array);
			for ($i=0; $i < $total; $i++) { //Contator
				if(!empty($array[$i])){
					$i1 = $i + 1;
				}
			}
			$i1 = $i1 = $i1 - 1; //tiro o último parametro
			for ($i=0; $i < $total; $i++) { //Monto
				if(!empty($array[$i])){
					if($i == $i1){
						$string .= " {$array[$i]} ";
					}else{
						$string .= " {$array[$i]} {$param} ";
					}
				}
			}
			if(!empty($parenteses)){
				if(!empty($string)){
					return $string;
				}
			}else{
				if(!empty($string)){
					return '('.$string.')';
				}
			}

		}


}
