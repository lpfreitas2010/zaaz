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
	class adm_usuario_onlineModel extends config_model_adm {


//***************************************************************************************************************************************************************
//CARREGO PARAMETROS
//***************************************************************************************************************************************************************
		function carrego_parametros(){
			$this->setTabela('adm_usuario_online'); // Tabela ****-
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
				 ->setColuna($tabela.'.hora')
				 ->setColuna($tabela.'.IP')
				 ->setColuna($tabela.'.SO')
				 ->setColuna($tabela.'.navegador')
				 ->setColuna($tabela.'.idioma')
				 ->setColuna('adm_usuario.nome')
				 ->setColuna('adm_usuario.img_perfil')
				 ->setColuna('(adm_usuario.id) as usuario_id')
				 ;

			//===========================================================
			//MONTO INNER JOIN ****-
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_online.adm_usuario_id'); // Inner join

			//===========================================================
			//MONTO PESQUISA SIMPLES ****-
			if(!empty($query_pesquisa) && $query_pesquisa != "Pesquisa Avançada"){

				//CONDIÇÕES PERSONALIZADAS DE PESQUISA
				$param1         = 'IP: '; // parametro personalizado
				$status_param1  = $funcoes->localizo_string($query_pesquisa, $param1);
				$query_pesquisa = $funcoes->substituo_strings($param1,'',$query_pesquisa);
				if($status_param1 !== false){
					$where = $this->return_where_like($tabela.'.IP',$query_pesquisa);
					$conf_personalizada = true;
				}
				$param1         = 'Hora: '; // parametro personalizado
				$status_param1  = $funcoes->localizo_string($query_pesquisa, $param1);
				$query_pesquisa = $funcoes->substituo_strings($param1,'',$query_pesquisa);
				if($status_param1 !== false){
					$where = $this->return_where_like($tabela.'.hora',$query_pesquisa);
					$conf_personalizada = true;
				}
				$param1         = 'SO: '; // parametro personalizado
				$status_param1  = $funcoes->localizo_string($query_pesquisa, $param1);
				$query_pesquisa = $funcoes->substituo_strings($param1,'',$query_pesquisa);
				if($status_param1 !== false){
					$where = $this->return_where_like($tabela.'.SO',$query_pesquisa);
					$conf_personalizada = true;
				}
				$param1         = 'Navegador: '; // parametro personalizado
				$status_param1  = $funcoes->localizo_string($query_pesquisa, $param1);
				$query_pesquisa = $funcoes->substituo_strings($param1,'',$query_pesquisa);
				if($status_param1 !== false){
					$where = $this->return_where_like($tabela.'.navegador',$query_pesquisa);
					$conf_personalizada = true;
				}
				$param1         = 'Idioma: '; // parametro personalizado
				$status_param1  = $funcoes->localizo_string($query_pesquisa, $param1);
				$query_pesquisa = $funcoes->substituo_strings($param1,'',$query_pesquisa);
				if($status_param1 !== false){
					$where = $this->return_where_like($tabela.'.idioma',$query_pesquisa);
					$conf_personalizada = true;
				}

				//PESQUISA SIMPLES
				if($conf_personalizada == false){
					$array_pesq[] = $this->return_where_int($tabela.'.id','=',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario.nome',$query_pesquisa);
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
				$array_pesq1[] = $this->return_where_like('adm_usuario.nome',$query_pesquisa_avancada['pesq_avanc_usuario']);
				$array_pesq1[] = $this->return_where_like($tabela.'.hora',$query_pesquisa_avancada['pesq_avanc_hora']);
				$array_pesq1[] = $this->return_where_like($tabela.'.IP',$query_pesquisa_avancada['pesq_avanc_ip']);
				$array_pesq1[] = $this->return_where_like($tabela.'.SO',$query_pesquisa_avancada['pesq_avanc_so']);
				$array_pesq1[] = $this->return_where_like($tabela.'.navegador',$query_pesquisa_avancada['pesq_avanc_navegador']);
				$array_pesq1[] = $this->return_where_like($tabela.'.idioma',$query_pesquisa_avancada['pesq_avanc_idioma']);
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
				//CAMPO USUARIO CRESCENTE
				if($order_by[$i] == 'USUARIO CRESCENTE'){
					$array_orderby[] = $this->return_order_by('adm_usuario.nome','ASC');
				}
				//CAMPO USUARIO DECRESCENTE
				if($order_by[$i] == 'USUARIO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by('adm_usuario.nome','DESC');
				}
				//CAMPO HORA CRESCENTE
				if($order_by[$i] == 'ACAO CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.hora','ASC');
				}
				//CAMPO HORA DECRESCENTE
				if($order_by[$i] == 'ACAO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.hora','DESC');
				}
				//CAMPO IP CRESCENTE
				if($order_by[$i] == 'IP CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.IP','ASC');
				}
				//CAMPO IP DECRESCENTE
				if($order_by[$i] == 'IP DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.IP','DESC');
				}
				//CAMPO SO CRESCENTE
				if($order_by[$i] == 'SO CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.SO','ASC');
				}
				//CAMPO SO DECRESCENTE
				if($order_by[$i] == 'SO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.SO','DESC');
				}
				//CAMPO NAVEGADOR CRESCENTE
				if($order_by[$i] == 'NAVEGADOR CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.navegador','ASC');
				}
				//CAMPO NAVEGADOR DECRESCENTE
				if($order_by[$i] == 'NAVEGADOR DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.navegador','DESC');
				}
				//CAMPO IDIOMA CRESCENTE
				if($order_by[$i] == 'IDIOMA CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.idioma','ASC');
				}
				//CAMPO IDIOMA DECRESCENTE
				if($order_by[$i] == 'IDIOMA DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.idioma','DESC');
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
			$this->setColuna('url')->setColuna('adm_usuario_id'); // Colunas ****-
			$this->setValue($this->getCampos('url'))->setValue(1); //Valores ****-
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
			$this->setColuna($this->getTabela().'.id')
				 ->setColuna($this->getTabela().'.modificado')
				 ->setColuna($this->getTabela().'.criado')
				 ->setColuna($this->getTabela().'.hora')
				 ->setColuna($this->getTabela().'.IP')
				 ->setColuna($this->getTabela().'.SO')
				 ->setColuna($this->getTabela().'.navegador')
				 ->setColuna($this->getTabela().'.idioma')
				 ->setColuna('adm_usuario.img_perfil')
				 ->setColuna('adm_usuario.nome');
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_online.adm_usuario_id'); // Inner join
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
