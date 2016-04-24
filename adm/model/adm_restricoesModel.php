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
	class adm_restricoesModel extends config_model_adm {


//***************************************************************************************************************************************************************
//CARREGO PARAMETROS
//***************************************************************************************************************************************************************
		function carrego_parametros(){
			$this->setTabela('adm_usuario_modulo_opcoes'); // Tabela ****-
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
				 ->setColuna($tabela.'.opcoes')
				 ->setColuna('adm_usuario.nome')
				 ->setColuna('adm_usuario.img_perfil')
				 ->setColuna('adm_usuario_modulo.modulo')
				 ->setColuna('adm_usuario_permissoes_acoes.acoes')
				 ;

			//===========================================================
			//MONTO INNER JOIN ****-
			$this->setInner(' INNER JOIN adm_usuario ON adm_usuario.id = '.$tabela.'.adm_usuario_id  INNER JOIN adm_usuario_nivel_usuario ON adm_usuario_nivel_usuario.adm_usuario_id = '.$tabela.'.adm_usuario_id   INNER JOIN adm_usuario_nivel ON adm_usuario_nivel.id = adm_usuario_nivel_usuario.adm_usuario_nivel_id  INNER JOIN adm_usuario_modulo ON adm_usuario_modulo.id = '.$tabela.'.adm_usuario_modulo_id  INNER JOIN adm_usuario_permissoes_acoes ON adm_usuario_permissoes_acoes.id = '.$tabela.'.adm_usuario_permissoes_acoes_id  '); // Inner join

			//TRATO RESTRIÇÃO DO CARGO 2
			if($_SESSION['adm_id_cargo'] == 2) {

				//SETO O WHERE
				$where_1 = " (adm_usuario_nivel_usuario.adm_usuario_nivel_id > 2 )";
				$this->setWhere($where_1);
			}

			//TRATO RESTRIÇÃO DO CARGO 3
			if($_SESSION['adm_id_cargo'] == 3) {

				//SETO O WHERE
				$where_1 = " (adm_usuario_nivel_usuario.adm_usuario_nivel_id > 3 )";
				$this->setWhere($where_1);
			}

			//===========================================================
			//MONTO PESQUISA SIMPLES ****-
			if(!empty($query_pesquisa) && $query_pesquisa != "Pesquisa Avançada"){

				//CONDIÇÕES PERSONALIZADAS DE PESQUISA
				if($query_pesquisa == 'Status: Permitido'){
					$where = $this->return_where_char($tabela.'.opcoes','=',1);
					$conf_personalizada = true;
				}
				if($query_pesquisa == 'Status: Não Permitido'){
					$where = " {$tabela}.opcoes = 0 ";
					$conf_personalizada = true;
				}
				$param1         = 'ID Usuário: '; // parametro personalizado
			    $status_param1  = $funcoes->localizo_string($query_pesquisa, $param1);
			    $query_pesquisa = $funcoes->substituo_strings($param1,'',$query_pesquisa);
			    if($status_param1 !== false){
			        $where = $this->return_where_int($tabela.'.adm_usuario_id','=',$query_pesquisa);
			        $conf_personalizada = true;
			    }

				//PESQUISA SIMPLES
				if($conf_personalizada == false){
					$array_pesq[] = $this->return_where_int($tabela.'.id','=',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario.nome',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario_modulo.modulo',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario_permissoes_acoes.acoes',$query_pesquisa);
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
				$array_pesq1[] = $this->return_where_int($tabela.'.adm_usuario_modulo_id','=',$query_pesquisa_avancada['pesq_avanc_modulo']);
				$array_pesq1[] = $this->return_where_int($tabela.'.adm_usuario_permissoes_acoes_id','=',$query_pesquisa_avancada['pesq_avanc_acoes']);
				$array_pesq1[] = $this->return_where_int($tabela.'.opcoes','=',$query_pesquisa_avancada['pesq_avanc_status']);
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
				if($order_by[$i] == 'CADASTRO CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.criado','ASC');
				}
				//CAMPO CADASTRO DECRESCENTE
				if($order_by[$i] == 'CADASTRO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.criado','DESC');
				}
				//CAMPO EDITADO CRESCENTE
				if($order_by[$i] == 'EDITADO CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.modificado','ASC');
				}
				//CAMPO EDITADO DECRESCENTE
				if($order_by[$i] == 'EDITADO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.modificado','DESC');
				}
				//CAMPO USUÁRIO CRESCENTE
				if($order_by[$i] == 'USUÁRIO CRESCENTE'){
					$array_orderby[] = $this->return_order_by('adm_usuario.nome','ASC');
				}
				//CAMPO USUÁRIO DECRESCENTE
				if($order_by[$i] == 'USUÁRIO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by('adm_usuario.nome','DESC');
				}
				//CAMPO MODULO CRESCENTE
				if($order_by[$i] == 'MODULO CRESCENTE'){
					$array_orderby[] = $this->return_order_by('adm_usuario_modulo.modulo','ASC');
				}
				//CAMPO MODULO DECRESCENTE
				if($order_by[$i] == 'MODULO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by('adm_usuario_modulo.modulo','DESC');
				}
				//CAMPO ACAO CRESCENTE
				if($order_by[$i] == 'ACAO CRESCENTE'){
					$array_orderby[] = $this->return_order_by('FIELD(adm_usuario_modulo_opcoes.adm_usuario_permissoes_acoes_id, "17")','DESC');
					$array_orderby[] = $this->return_order_by('adm_usuario_modulo_opcoes.adm_usuario_permissoes_acoes_id','ASC');
				}
				//CAMPO ACAO DECRESCENTE
				if($order_by[$i] == 'ACAO DECRESCENTE'){
					$array_orderby[] = $this->return_order_by('FIELD(adm_usuario_modulo_opcoes.adm_usuario_permissoes_acoes_id, "17"','DESC');
					$array_orderby[] = $this->return_order_by('adm_usuario_modulo_opcoes.adm_usuario_permissoes_acoes_id','DESC');
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

		}


//***************************************************************************************************************************************************************
//FUNÇÃO EDITAR PERMISSAO
//***************************************************************************************************************************************************************
		function editar_permissao(){
			$this->carrego_parametros(); // Carrego parametros

			//RETORNO DADOS DO ID
			$this->setTable($this->getTabela()); // Tabela
			$this->setColuna('adm_usuario_id')->setColuna('adm_usuario_modulo_id')->setColuna('adm_usuario_permissoes_acoes_id')->setColuna('opcoes'); // Colunas ****-
			$this->setWhere(" id={$this->getId()} "); // Where
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$adm_usuario_id                  = $exec[$i]['adm_usuario_id'];
				$adm_usuario_modulo_id           = $exec[$i]['adm_usuario_modulo_id'];
				$adm_usuario_permissoes_acoes_id = $exec[$i]['adm_usuario_permissoes_acoes_id'];
			}

			//------------------------------------------------------
			//NÃO POSSO ALTERAR MINHA PERMISSAO SE NÃO FOR DESENVOLVEDOR
			if($this->retorna_cargo_usuario($adm_usuario_id) != 1){
				if($adm_usuario_id == $_SESSION['adm_id_user']){
					return false;
					exit;
				}
			}

			//------------------------------------------------------
			//SE FOR USUARIO IGUAL A 2 (ADMINISTRADOR)
			if($this->retorna_cargo_usuario($_SESSION['adm_id_user']) == 2){

				//MODULO 1 NÃO POSSO ALTERAR O MÓDULO 1
				if($adm_usuario_modulo_id == 1){
					return false;
					exit;
				}

				//CARGO == 1 ou 2 NÃO POSSO ALTERAR OUTRO (ADMINISTRADOR OU DESENVOLVEDOR)
				if($this->retorna_cargo_usuario($adm_usuario_id) == 1 || $this->retorna_cargo_usuario($adm_usuario_id) == 2){
					return false;
					exit;
				}
			}

			//------------------------------------------------------
			//SE FOR USUARIO IGUAL A 3 (GERENTE)
			if($this->retorna_cargo_usuario($_SESSION['adm_id_user']) == 3){

				//MODULO 1 NÃO POSSO ALTERAR O MÓDULO 1
				if($adm_usuario_modulo_id == 1){
					return false;
					exit;
				}

				//CARGO == 1 ou 2 NÃO POSSO ALTERAR OUTRO (ADMINISTRADOR OU DESENVOLVEDOR)
				if($this->retorna_cargo_usuario($adm_usuario_id) == 1 || $this->retorna_cargo_usuario($adm_usuario_id) == 2 || $this->retorna_cargo_usuario($adm_usuario_id) == 3){
					return false;
					exit;
				}
			}

			//------------------------------------------------------
			//SE FOR USUARIO IGUAL A 4 OU MAIOR (OPERACIONAIS)
			if($this->retorna_cargo_usuario($_SESSION['adm_id_user']) >=4){
				return false;
				exit;
			}

			//------------------------------------------------------
			//SE FOR CARGO FOR IGUAL A 4 OU MAIOR BARRO ALTERAÇÃO DE ALGUNS MODULOS
			if($this->retorna_cargo_usuario($adm_usuario_id) >= 4){
				//MODULO 1 NÃO POSSO ALTERAR O MÓDULO 1
				if($adm_usuario_modulo_id == 1){
					return false;
					exit;
				}
				//MODULO 2 NÃO POSSO ALTERAR O MÓDULO 2
				if($adm_usuario_modulo_id == 2){
					return false;
					exit;
				}
				//MODULO 3 NÃO POSSO ALTERAR O MÓDULO 3
				if($adm_usuario_modulo_id == 3){
					return false;
					exit;
				}
				//MODULO 5 NÃO POSSO ALTERAR O MÓDULO 5
				if($adm_usuario_modulo_id == 5){
					return false;
					exit;
				}
			}


			//RETORNO DADOS DO ID E ALTERO SE FOR AÇÕA 17
			$this->setTable($this->getTabela()); // Tabela
			$this->setColuna('id')->setColuna('opcoes'); // Colunas ****-
			$this->setWhere(" adm_usuario_permissoes_acoes_id = 17 AND adm_usuario_id = {$adm_usuario_id} AND adm_usuario_modulo_id = {$adm_usuario_modulo_id} "); // Where
			$exec2 = $this->Read();
			$this->limpo_campos();
			for($i2=0;$i2<count($exec2);$i2++){
				$id_interface = $exec2[$i2]['id'];
				if($exec2[$i2]['opcoes']==0){
					$this->setTable($this->getTabela()); // Tabela
					$this->setColuna('opcoes'); // Colunas ****-
					$this->setValue($this->getCampos('opcoes')); //Valores ****-
					$this->setWhere("id={$exec2[$i2]['id']}"); // ****-
					$exec3 = $this->Update();
					$this->limpo_campos();

				}
			}

			//ALTERO PERMISSÃO LINHA
			$this->setTable($this->getTabela()); // Tabela
			$this->setColuna('opcoes')->setColuna('modificado'); // Colunas ****-
			$this->setValue($this->getCampos('opcoes'))->setValue($this->getModificado()); //Valores ****-
			$this->setWhere("id={$this->getId()}"); // ****-
			$exec = $this->Update();
			$this->limpo_campos();

			//ALTERO INTERFACE PARA NAO PERMITIDO SE NÃO TIVER NADA
			$this->setTable($this->getTabela()); // Tabela
			$this->setColuna('id')->setColuna('opcoes'); // Colunas ****-
			$this->setWhere(" adm_usuario_permissoes_acoes_id != 17 AND adm_usuario_id = {$adm_usuario_id} AND adm_usuario_modulo_id = {$adm_usuario_modulo_id}  "); // Where
			$exec4 = $this->Read();
			$this->limpo_campos();
			$cont = 0;
			for($i4=0;$i4<count($exec4);$i4++){
				if($exec4[$i4]['opcoes']==0){
					$cont = $cont + 1;
				}
			}
			if($cont == count($exec4)){
				$this->setTable($this->getTabela()); // Tabela
				$this->setColuna('opcoes'); // Colunas ****-
				$this->setValue(0); //Valores ****-
				$this->setWhere("id={$id_interface}"); // ****-
				$exec5 = $this->Update();
				$this->limpo_campos();
			}

			//SE FOR CLICADO INTERFACE PERMITO TUDO OU NÃO
			if($adm_usuario_permissoes_acoes_id == 17){
				$this->setTable($this->getTabela()); // Tabela
				$this->setColuna('id')->setColuna('adm_usuario_id')->setColuna('adm_usuario_modulo_id')->setColuna('adm_usuario_permissoes_acoes_id')->setColuna('opcoes'); // Colunas ****-
				$this->setWhere(" adm_usuario_modulo_id={$adm_usuario_modulo_id} AND adm_usuario_id = {$adm_usuario_id}"); // Where
				$exec = $this->Read();
				$this->limpo_campos();
				for($i=0;$i<count($exec);$i++){
					$this->setTable($this->getTabela()); // Tabela
					$this->setColuna('opcoes'); // Colunas ****-
					$this->setValue($this->getCampos('opcoes')); //Valores ****-
					$this->setWhere("id={$exec[$i]['id']}"); // ****-
					$exec5 = $this->Update();
					$this->limpo_campos();
				}
			}


			if(count($exec) >= 1){
				return true;
			}else{
				return false;
			}
		}



//***************************************************************************************************************************************************************
//FUNÇÃO RETORNA O CARGO DO USUARIO
//***************************************************************************************************************************************************************
		function retorna_cargo_usuario($id){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_usuario_nivel_usuario'); // Tabela
			$this->setColuna('adm_usuario_nivel_id'); // Colunas ****-
			$this->setInner(); // Inner ****-
			$this->setWhere("adm_usuario_id = {$id} "); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return $exec[0]['adm_usuario_nivel_id'];
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
