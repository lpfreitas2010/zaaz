<?php

	/**
	* Sistema de contador de visitantes online
	*
	* @author Thiago Belem <contato@thiagobelem.net>
	* @link http://thiagobelem.net/
	*
	* @version 1.0
	* @package VisitantesOnline
	*/

    //=================================================================
	//INCLUO ESTRUTURA
	require_once (dirname(dirname((dirname(__FILE__))))).'/libs/core/core.php';
	//=================================================================

	//Classe
	class usuario_sessao{

			private $funcoes;
			private $core;

			function __construct() {
				$this->core    = new core(); //Instancio Core
				$this->funcoes = new funcoes(); //Instancio Funções
			}


			//TABELA DO BANCO (usuario_online)
			public function getTabela_usuario_online($param){
					/*if($param == 'web'){ //pasta web
							return 'usuario_online';
					}else{ //outras pastas apps
							return $param.'_usuario_online';
					}*/
					return 'adm_usuario_online';
			}

			//TABELA DO BANCO (usuario_tempo_sessao)
			public function getTabela_usuario_tempo_sessao($param){
					/*if($param == 'web'){ //pasta web
							return 'usuario_tempo_sessao';
					}else{ //outras pastas apps
							return $param.'_usuario_tempo_sessao';
					}*/
					return 'adm_usuario_tempo_sessao';
			}

			//CAMPO DO BANCO (usuario_id)
			public function getCampo_usuario_id($param){
					/*if($param == 'web'){ //pasta web
							return 'usuario_id';
					}else{ //outras pastas apps
					  	return $param.'_usuario_id';
					}*/
					return 'adm_usuario_id';
			}

			//ID DO USUÁRIO LOGADO NO SISTEMA
			public function getUsuario_id($param){
					$usuario_id = $_SESSION[$param.'_id_user'];
					if(empty($usuario_id)){ //Se a sessão estiver vazia eu atribuo o valor padrão 1
							$usuario_id = 1;
					}
					return $usuario_id;
			}

			//NOME DO USUÁRIO LOGADO NO SISTEMA
			public function getNome_usuario($param){
					return $_SESSION[$param.'_nome_user'];
			}

			//EMAIL DO USUÁRIO LOGADO NO SISTEMA
			public function getEmail_usuario($param){
					return $_SESSION[$param.'_email_user'];
			}

			//URL IMAGEM DO USUÁRIO LOGADO NO SISTEMA
			public function getImg_perfil_usuario($param){
				return $_SESSION[$param.'_foto_perfil'];
			}

			//DATA ATUAL
		  public function getData(){
		      return date("d-m-y");
		  }

		  //HORA ATUAL
		  public function getHora(){
		      return date("H:i:s");
		  }

		  //IP
		  public function getIp(){
		      return $_SERVER['REMOTE_ADDR'];
		  }

		  //SISTEMA OPERACIONAL
		  public function getSO(){
		      return $this->funcoes->identifico_so();
		  }

		  //NAVEGADOR
		  public function getNavegador(){
		      return $this->funcoes->identifico_navegador();
		  }

			//PEGO O IDIOMA
			public function getIdioma(){
					return substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
			}



			//=================================================================
			/* REGISTRA VISITA DE USUÁRIO TABELA (usuario_online)
			Recebo por parametro a pasta da aplicação que verifica na tabela usuario_online
			se encontra o id do usuário, se não encontrar é inserido um registro na tabela,
			caso ja tenha o registro na tabela o tempo é atualizado. Pego o parametro das
			configurações gerais do sistema verificando o tempo que os registro deve ser excluidos da tabela.
			*/
			function registra_visita($tipo){

					//CONEXÃO COM O BANCO DE DADOS
					$this->core->includeModel();
					$conexao = new conexao();

					//PEGO OS PARAMETROS
					$tabela1          = $this->getTabela_usuario_online($tipo); //Tabela usuario_online
					$campo_usuario_id = $this->getCampo_usuario_id($tipo); //Campo do banco usuario_id
					$id_usuario       = $this->getUsuario_id($tipo); //Usuário logado no sistema

					//PARO EXECUÇÃO SE O (id_usuario) FOR 1
					if($id_usuario == 1){
							return false;
							exit();
					}

					//VERIFICO SE TEM REGISTRO DO USUÁRIO NA TABELA (usuario_online)
					$conexao->setTable($tabela1);
					$conexao->setColuna($campo_usuario_id);
					$conexao->setWhere("{$campo_usuario_id} = {$id_usuario}");
					$status_reg = $conexao->Read();
					$conexao->limpo_campos();

					//ATUALIZO O TEMPO DA TABELA (usuario_online)
					if (count($status_reg) == 1) {
							$conexao->setTable($tabela1);
							$conexao->setColuna('hora')->setColuna('modificado');
							$conexao->setValue(date("Y-m-d H:i:s"))->setValue(date("Y-m-d H:i:s"));
							$conexao->setWhere("{$campo_usuario_id} = {$id_usuario}");
							$conexao->setLimit('1');
							$conexao->Update();
							$conexao->limpo_campos();
					}

					//INSIRO REGISTRO NA TABELA (usuario_online)
					if (count($status_reg) == 0) {
							$conexao->setTable($tabela1);
							$conexao->setColuna($campo_usuario_id)->setColuna('hora')->setColuna('IP')->setColuna('SO')->setColuna('navegador')->setColuna('idioma');
							$conexao->setValue($id_usuario)->setValue(date("Y-m-d H:i:s"))->setValue($this->getIp())->setValue($this->getSO())->setValue($this->getNavegador())->setValue($this->getIdioma());
							$conexao->Create();
							$conexao->limpo_campos();
					}

					//DELETO TODOS OS USUÁRIOS EXPIRADOS DA TABELA (usuario_online)
					$conexao->setTable($tabela1);
					$conexao->setWhere("(hora <= (NOW() - INTERVAL {$this->core->get_config('tempo_usuario_online')} MINUTE)) AND ({$campo_usuario_id} != {$id_usuario})");
					$conexao->Delete();
					$conexao->limpo_campos();

					//RETORNO TRUE
					return true;
			}

			//=================================================================
			/* DESTRUO REGISTRO DE USUÁRIO DAS TABELAS (usuario_online) e (usuario_tempo_sessao)
			Recebo por parametro a pasta da aplicação que destroi o
			registro das tabelas usuario_online	e usuario_tempo_sessao
			*/
			function destruo_visitante($tipo) {

					//CONEXÃO COM O BANCO DE DADOS
					$this->core->includeModel();
					$conexao = new conexao();

					//PEGO OS PARAMETROS
			        $tabela1          = $this->getTabela_usuario_online($tipo); //Tabela usuario_online
			        $tabela2          = $this->getTabela_usuario_tempo_sessao($tipo); //Tabela usuario_tempo_sessao
			        $campo_usuario_id = $this->getCampo_usuario_id($tipo); //Campo do banco usuario_id
					$id_usuario       = $this->getUsuario_id($tipo); //Usuário logado no sistema

					//DELETO O USUARIO DA TABELA (usuario_online)
					$conexao->setTable($tabela1);
					$conexao->setWhere("{$campo_usuario_id} = {$id_usuario}");
					$conexao->Delete();
					$conexao->limpo_campos();

					//DELETO O USUARIO DA TABELA (usuario_tempo_sessao)
					$conexao->setTable($tabela2);
					$conexao->setWhere("{$campo_usuario_id} = {$id_usuario}");
					$conexao->Delete();
					$conexao->limpo_campos();
			}



			//=================================================================
			/* CONTROLO O TEMPO DE SESSÃO (usuario_tempo_sessao)
			Recebo por parametro a pasta da aplicação e a página que será
			redirecionado quando o tempo da sessão expirar.
			*/
			function controlo_tempo_sessao($param,$header,$idusuario_externo = null,$destroi_sessao = null){

					//INSTANCIO CONEXAO
					$this->core->includeModel();
					$conexao = new conexao();
					$funcoes = new funcoes(); //função de criptografia e descriptografia

					//PEGO OS PARAMETROS
          			$tabela1          = $this->getTabela_usuario_tempo_sessao($param); //Tabela usuario_tempo_sessao
          			$campo_usuario_id = $this->getCampo_usuario_id($param); //Campo do banco usuario_id
					$id_usuario       = $this->getUsuario_id($param); //Usuário logado no sistema

					//ID USUARIO EXTERNO
					if(!empty($idusuario_externo)){
						if(!empty($destroi_sessao)){

							//VERIFICO SE TEMPO DA SESSÃO EXPIROU
							$valor_min = $this->core->get_config('tempo_sessao_'.$param) + 1;
							$data_anterior = mktime(date("H") , date("i") - $valor_min , date("s"), date("m"), date("d"), date("Y"));
							$data_ = date("Y-m-d H:i:s", $data_anterior);
							$conexao->setTable($tabela1);
							$conexao->setColuna('hora');
							$conexao->setValue($data_);
							$conexao->setWhere("{$campo_usuario_id} = {$idusuario_externo}");
							$conexao->setLimit('1');
							$conexao->Update();
							$conexao->limpo_campos();
							return true;
							exit();
						}
					}

					//PARO EXECUÇÃO SE O (id_usuario) FOR 1
					if($id_usuario == 1){
						return false;
						exit();
					}

					//VERIFICO SE TEMPO DA SESSÃO EXPIROU
					$conexao->setTable($tabela1);
					$conexao->setColuna('hora');
					$conexao->setWhere("{$campo_usuario_id} = {$id_usuario}");
					$exec = $conexao->Read();
					$conexao->limpo_campos();
					for($i=0;$i<count($exec);$i++){
						if(strtotime('now') >= strtotime('+'.$this->core->get_config('tempo_sessao_'.$param)." minute", strtotime($exec[$i]['hora']))){

							//DELETO REGISTRO DA TABELA (usuario_tempo_sessao)
							$conexao->setTable($tabela1);
							$conexao->setWhere("{$campo_usuario_id} = {$id_usuario}");
							$conexao->Delete();
							$conexao->limpo_campos();

							//DESTRUO SESSÃO E REDIRECIONO
							session_destroy();
							header('location: '.$header);
							exit();
						}
					}

					//VERIFICO SE TEM REGISTRO DO USUÁRIO NA TABELA (usuario_tempo_sessao)
					$conexao->setTable($tabela1);
					$conexao->setColuna($campo_usuario_id);
					$conexao->setWhere("{$campo_usuario_id} = {$id_usuario}");
					$status_reg = $conexao->Read();
					$conexao->limpo_campos();

					//ATUALIZO O TEMPO DA TABELA (usuario_tempo_sessao)
					if (count($status_reg) == 1) {
							$conexao->setTable($tabela1);
							$conexao->setColuna('hora')->setColuna('modificado');
							$conexao->setValue(date("Y-m-d H:i:s"))->setValue(date("Y-m-d H:i:s"));
							$conexao->setWhere("{$campo_usuario_id} = {$id_usuario}");
							$conexao->setLimit('1');
							$conexao->Update();
							$conexao->limpo_campos();
					}

					//INSIRO REGISTRO NA TABELA (usuario_tempo_sessao)
					if (count($status_reg) == 0) {
							$conexao->setTable($tabela1);
							$conexao->setColuna($campo_usuario_id)->setColuna('hora');
							$conexao->setValue($id_usuario)->setValue(date("Y-m-d H:i:s"));
							$conexao->Create();
							$conexao->limpo_campos();
					}

					//SETO VALORES NO COOKIE
					if (!isset($_COOKIE['nm_tmp'.$param])) { //Nome do usuário logado
						setcookie('nm_tmp'.$param, $funcoes->mycrypt($this->getNome_usuario($param)), time() + (720 * 60), '/','',''); //cookie duração de 12 horas
					}
					if (!isset($_COOKIE['eml_tmp'.$param])) { //Email do usuário logado
						setcookie('eml_tmp'.$param, $funcoes->mycrypt($this->getEmail_usuario($param)), time() + (720 * 60), '/','',''); //cookie  duração de 12 horas
					}
					if (!isset($_COOKIE['ft_tmp'.$param])) { //Foto do usuário logado
						setcookie('ft_tmp'.$param, $funcoes->mycrypt($this->getImg_perfil_usuario($param)), time() + (720 * 60), '/','',''); //cookie  duração de	12 horas
					}
			}



}
