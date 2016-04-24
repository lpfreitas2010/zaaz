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
	class adm_feedbackModel extends config_model_adm {


//***************************************************************************************************************************************************************
//CARREGO PARAMETROS
//***************************************************************************************************************************************************************
		function carrego_parametros(){
			$this->setTabela('adm_feedback'); // Tabela ****-
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
				 ->setColuna($tabela.'.area')
				 ->setColuna($tabela.'.tipo')
				 ->setColuna($tabela.'.mensagem')
				 ->setColuna($tabela.'.anexo')
				 ->setColuna($tabela.'.status_id')
				 ->setColuna('config_status.status')
				 ->setColuna('adm_usuario.nome')
				 ->setColuna('adm_usuario.img_perfil')
				 ;

			//===========================================================
			//MONTO INNER JOIN ****-
			$this->setInner(' INNER JOIN adm_usuario ON adm_usuario.id = adm_feedback.adm_usuario_id INNER JOIN config_status ON config_status.id = adm_feedback.status_id '); // Inner join

			//===========================================================
			//MONTO WHERE DEFAULT ****-
			if($_SESSION['adm_id_cargo'] == 1 || $_SESSION['adm_id_cargo'] == 2 || $_SESSION['adm_id_cargo'] == 3) {
				$where = "";
				$this->setWhere($where);
			}else{
				$where = "(adm_feedback.adm_usuario_id = ".$_SESSION['adm_id_user'].")";
				$this->setWhere($where);
				$where2 = $where .' AND ';
			}

			//===========================================================
			//MONTO PESQUISA SIMPLES ****-
			if(!empty($query_pesquisa) && $query_pesquisa != "Pesquisa Avançada"){

				//CONDIÇÕES PERSONALIZADAS DE PESQUISA
				if($query_pesquisa == 'Status: Visto'){
					$where = $this->return_where_int($tabela.'.status_id','=',1);
					$where = $where2.' '.$where;
					$conf_personalizada = true;
				}
				if($query_pesquisa == 'Status: Não Visto'){
					$where = $this->return_where_int($tabela.'.status_id','=',2);
					$where = $where2.' '.$where;
					$conf_personalizada = true;
				}

				//PESQUISA SIMPLES
				if($conf_personalizada == false){
					$array_pesq[] = $this->return_where_int($tabela.'.id','=',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario.nome',$query_pesquisa);
					$array_pesq[] = $this->return_where_like($tabela.'.area',$query_pesquisa);
					$array_pesq[] = $this->return_where_like($tabela.'.tipo',$query_pesquisa);
					$array_pesq[] = $this->return_where_like($tabela.'.mensagem',$query_pesquisa);
					$where = $this->return_params_mont($array_pesq,'OR',true);
					$where = $where2.' '.$where;
				}

				//SETO O WHERE
				$this->setWhere($where);
			}

			//===========================================================
			//MONTO PESQUISA AVANÇADA ****-
			if(!empty($query_pesquisa_avancada) && $query_pesquisa == "Pesquisa Avançada"){

				//PERIODO DE DATA
				$array_pesq1[] = $this->return_where_data($tabela.'.'.$query_pesquisa_avancada['periodo_tipo_data'],$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_de'],'Y-m-d'),$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_ate'],'Y-m-d'));
				$where = $this->return_params_mont($array_pesq1,'AND',true);
				$where = $where2.' '.$where;

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

			//ANEXOS
			$url_imagem = $this->getCampos('url_imagem');
			if(count($url_imagem)>=1){
				$anexos = 'true';
			}else{
				$anexos = '';
			}

			//ADMFEEDBACK
			$this->setTable($this->getTabela()); // Tabela
			$this->setColuna('adm_usuario_id')->setColuna('status_id')->setColuna('area')->setColuna('tipo')->setColuna('mensagem')->setColuna('anexo'); // Colunas ****-
			$this->setValue($this->getCampos('adm_usuario_id'))->setValue($this->getCampos('status_id'))->setValue($this->getCampos('area'))->setValue($this->getCampos('tipo'))->setValue($this->getCampos('mensagem'))->setValue($anexos); //Valores ****-
			$exec = $this->Create();
			$this->limpo_campos();
			$this->setUltimo_id($this->get_last_id());
			$ult_id = $this->get_last_id();

			//IMAGENS
			if(count($url_imagem)>=1){
				for ($i=0; $i <count($url_imagem) ; $i++) {
					$this->setTable('adm_feedback_imagens'); // Tabela
					$this->setColuna('adm_feedback_id')->setColuna('url_imagem'); // Colunas ****-
					$this->setValue($ult_id)->setValue($url_imagem[$i]); //Valores ****-
					$exec = $this->Create();
					$this->limpo_campos();
				}
			}

			//RETORNO
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
			$this->setColuna('modificado')->setColuna('url'); // Colunas ****-
			$this->setValue($this->getModificado())->setValue($this->getCampos('url')); //Valores ****-
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
			$this->setColuna($this->getTabela().'.id') // padrão
				 ->setColuna($this->getTabela().'.modificado') // padrão
				 ->setColuna($this->getTabela().'.criado') // padrão
				 ->setColuna($this->getTabela().'.area')
				 ->setColuna($this->getTabela().'.tipo')
				 ->setColuna($this->getTabela().'.mensagem')
				 ->setColuna($this->getTabela().'.anexo')
				 ->setColuna($this->getTabela().'.status_id')
				 ->setColuna($this->getTabela().'.adm_usuario_id')
				 ->setColuna('config_status.status')
				 ->setColuna('adm_usuario.nome')
				 ->setColuna('adm_usuario.img_perfil')
				 ;
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_feedback.adm_usuario_id INNER JOIN config_status ON config_status.id = adm_feedback.status_id  '); // Inner ****-
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
//FUNÇÃO DETALHAMENTO IMAGENS DO FEEDBACK
//***************************************************************************************************************************************************************
		function detalhamento2(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_feedback_imagens'); // Tabela
			$this->setColuna('*');
			$this->setWhere("adm_feedback_imagens.adm_feedback_id={$this->getId()}"); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return $exec;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//FUNÇÃO RETORNA OS USUARIOS ADMINISTRADORES E DESENVOLVEDORES
//***************************************************************************************************************************************************************
		function retorno_usuarios_adm_desen(){
			$this->setTable('adm_usuario_nivel_usuario'); // Tabela
			$this->setColuna('adm_usuario_id');
			$this->setWhere("adm_usuario_nivel_usuario.adm_usuario_nivel_id = 1"); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return $exec;
			}else{
				return false;
			}
		}

//***************************************************************************************************************************************************************
//FUNÇÃO RETORNA OS USUARIOS ADMINISTRADORES E DESENVOLVEDORES
//***************************************************************************************************************************************************************
		function retorno_usuarios_adm_desen1(){
			$this->setTable('adm_usuario_nivel_usuario'); // Tabela
			$this->setColuna('adm_usuario_auth.usertelefone')->setColuna('adm_usuario_auth.useremail');
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_nivel_usuario.adm_usuario_id  INNER JOIN adm_usuario_auth ON adm_usuario_auth.adm_usuario_id = adm_usuario_nivel_usuario.adm_usuario_id'); // Inner ****-
			$this->setWhere("adm_usuario_nivel_usuario.adm_usuario_nivel_id = 1 "); // Where ****-
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
