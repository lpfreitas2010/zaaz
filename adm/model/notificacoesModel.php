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
	class notificacoesModel extends config_model_adm {


//***************************************************************************************************************************************************************
//CARREGO PARAMETROS
//***************************************************************************************************************************************************************
		function carrego_parametros(){
			$this->setTabela('adm_usuario_notificaoes'); // Tabela ****-
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
				 ->setColuna($tabela.'.class_icon')
				 ->setColuna($tabela.'.mensagem')
				 ->setColuna($tabela.'.url_destino')
				 ->setColuna($tabela.'.status_id')
				 ->setColuna('config_status.status')
				 ->setColuna('adm_usuario.nome')
				 ->setColuna('adm_usuario.img_perfil')
				 ;

			//===========================================================
			//MONTO INNER JOIN ****-
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_notificaoes.adm_usuario_id INNER JOIN config_status ON config_status.id = adm_usuario_notificaoes.status_id '); // Inner join

			//WHERE PADRÃO
			if($_SESSION['adm_id_cargo'] == 1 || $_SESSION['adm_id_cargo'] == 2) {
				$where  = "";
				$where_ = "";
			}else{
				$where = "((adm_usuario_notificaoes.adm_usuario_id = 1) OR (adm_usuario_notificaoes.adm_usuario_id = ".$this->getId().")) ";
				$where_ = $where.' AND ';
			}

			//===========================================================
			//MONTO PESQUISA SIMPLES ****-
			if(!empty($query_pesquisa) && $query_pesquisa != "Pesquisa Avançada"){

				//CONDIÇÕES PERSONALIZADAS DE PESQUISA
				if($query_pesquisa == 'Status: Lido'){
					$where2 = $this->return_where_int($tabela.'.status_id','=',1);
					$where = $where_.'  ('.$where2.')';
					$conf_personalizada = true;
				}
				if($query_pesquisa == 'Status: Não Lido'){
					$where2 = $this->return_where_int($tabela.'.status_id','=',2);
					$where = $where_.'  ('.$where2.')';
					$conf_personalizada = true;
				}
				if($query_pesquisa == 'Minhas Notificações'){
					$array_pesq2_[] = $this->return_where_int($tabela.'.adm_usuario_id','=',1);
					$array_pesq2_[] = $this->return_where_int($tabela.'.adm_usuario_id','=',$_SESSION['adm_id_user']);
					$where2 = $this->return_params_mont($array_pesq2_,'OR',true);
					$where = $where_.'  ('.$where2.')';
					$conf_personalizada = true;
				}

				//PESQUISA SIMPLES
				if($conf_personalizada == false){
					$array_pesq[] = $this->return_where_int($tabela.'.id','=',$query_pesquisa);
					$array_pesq[] = $this->return_where_like($tabela.'.mensagem',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario.nome',$query_pesquisa);
					$where2 = $this->return_params_mont($array_pesq,'OR',true);
					$where = $where_.'  ('.$where2.')';
				}
			}

			//===========================================================
			//MONTO PESQUISA AVANÇADA ****-
			if(!empty($query_pesquisa_avancada) && $query_pesquisa == "Pesquisa Avançada"){

				//PERIODO DE DATA
				$array_pesq1[] = $this->return_where_data($tabela.'.'.$query_pesquisa_avancada['periodo_tipo_data'],$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_de'],'Y-m-d'),$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_ate'],'Y-m-d'));
				$where2 = $this->return_params_mont($array_pesq1,'AND',true);
				$where = $where_.'  ('.$where2.')';
			}

			//SETO O WHERE
			$this->setWhere($where);

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
					$array_orderby[] = $this->return_order_by('config_status.status','ASC');
				}
				//CAMPO STATUS DECRESCENTE
				if($order_by[$i] == 'STATUS DECRESCENTE'){
					$array_orderby[] = $this->return_order_by('config_status.status','DESC');
				}
				//CAMPO MENSAGEM CRESCENTE
				if($order_by[$i] == ' MENSAGEM CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.mensagem','ASC');
				}
				//CAMPO MENSAGEM DECRESCENTE
				if($order_by[$i] == ' MENSAGEM DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.mensagem','DESC');
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
			$this->setColuna('adm_usuario_id')->setColuna('status_id')->setColuna('status_exibicao')->setColuna('class_icon')->setColuna('mensagem')->setColuna('url_destino'); // Colunas ****-
			$this->setValue($this->getCampos('adm_usuario_id'))->setValue($this->getCampos('status_id'))->setValue($this->getCampos('status_exibicao'))->setValue($this->getCampos('class_icon'))->setValue($this->getCampos('mensagem'))->setValue($this->getCampos('url_destino')); //Valores ****-
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
			$this->setColuna('adm_usuario_id')->setColuna('status_id')->setColuna('status_exibicao')->setColuna('class_icon')->setColuna('mensagem')->setColuna('url_destino'); // Colunas ****-
			$this->setValue($this->getCampos('adm_usuario_id'))->setValue($this->getCampos('status_id'))->setValue($this->getCampos('status_exibicao'))->setValue($this->getCampos('class_icon'))->setValue($this->getCampos('mensagem'))->setValue($this->getCampos('url_destino')); //Valores ****-
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
			$this->setColuna($this->getTabela().'.id')
				 ->setColuna($this->getTabela().'.modificado')
				 ->setColuna($this->getTabela().'.criado')
				 ->setColuna($this->getTabela().'.class_icon')
				 ->setColuna($this->getTabela().'.mensagem')
				 ->setColuna($this->getTabela().'.url_destino')
				 ->setColuna($this->getTabela().'.status_id')
				 ->setColuna('adm_usuario.nome')
				 ->setColuna('adm_usuario.img_perfil')
				 ->setColuna('config_status.status');
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_notificaoes.adm_usuario_id INNER JOIN config_status ON config_status.id = adm_usuario_notificaoes.status_id '); // Inner join
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
