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
	class usuariosModel extends config_model_adm {

		//VARIAVEIS
		protected $usuario_id;
		protected $usuario_tipo_id;
		protected $nome;
		protected $email;
		protected $cpf;
		protected $senha;
		protected $img_perfil;
		protected $username;
		protected $usertelefone;
		protected $useremail;

		public function getUseremail(){
			return $this->useremail;
		}
		public function setUseremail($useremail){
			$this->useremail = $useremail;
			return $this;
		}
		public function getUsertelefone(){
			return $this->usertelefone;
		}
		public function setUsertelefone($usertelefone){
			$this->usertelefone = $usertelefone;
			return $this;
		}
		public function getUsername(){
			return $this->username;
		}
		public function setUsername($username){
			$this->username = $username;
			return $this;
		}
		public function getImg_perfil(){
	        return $this->img_perfil;
	    }
	    public function setImg_perfil($img_perfil){
	        $this->img_perfil = $img_perfil;
	        return $this;
	    }
		public function getSenha(){
			return $this->senha;
		}
		public function setSenha($senha){
			$this->senha = $senha;
			return $this;
		}
		public function getCpf(){
			return $this->cpf;
		}
		public function setCpf($cpf){
			$this->cpf = $cpf;
			return $this;
		}
		public function getEmail(){
			return $this->email;
		}
		public function setEmail($email){
			$this->email = $email;
			return $this;
		}
		public function getNome(){
			return $this->nome;
		}
		public function setNome($nome){
			$this->nome = $nome;
			return $this;
		}
		public function getUsuario_tipo_id(){
			return $this->usuario_tipo_id;
		}
		public function setUsuario_tipo_id($usuario_tipo_id){
			$this->usuario_tipo_id = $usuario_tipo_id;
			return $this;
		}
		public function getUsuario_id(){
			return $this->usuario_id;
		}
		public function setUsuario_id($usuario_id){
			$this->usuario_id = $usuario_id;
			return $this;
		}





//***************************************************************************************************************************************************************
//CARREGO PARAMETROS
//***************************************************************************************************************************************************************
		function carrego_parametros(){
			$this->setTabela('adm_usuario'); // Tabela ****-
		}





//***************************************************************************************************************************************************************
//VALIDO LOGIN
//***************************************************************************************************************************************************************
		function valido_login(){

			//MONTO O COMANDO
			$this->setTable('adm_usuario_auth');
			$this->setColuna('adm_usuario.nome')->setColuna('adm_usuario.email')->setColuna('adm_usuario.img_perfil')->setColuna('adm_usuario_auth.adm_usuario_id')->setColuna('adm_usuario_auth.useremail')->setColuna('adm_usuario_auth.username')->setColuna('adm_usuario_auth.usertelefone')->setColuna('adm_usuario_auth.senha');
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_auth.adm_usuario_id');

			//MONTO WHERE
			$where_montado = $this->return_params_mont(array(
			$this->return_where_int('adm_usuario_auth.status_id','=',1),
			$this->return_where_char('adm_usuario_auth.username','=',$this->getUsername()),
			$this->return_where_char('adm_usuario_auth.senha','=',$this->getSenha()),
			$this->return_where_char('adm_usuario_auth.token','=',' ')
			),"AND");
			$where_montado1 = $this->return_params_mont(array(
			$this->return_where_int('adm_usuario_auth.status_id','=',1),
			$this->return_where_char('adm_usuario_auth.useremail','=',$this->getUsername()),
			$this->return_where_char('adm_usuario_auth.senha','=',$this->getSenha()),
			$this->return_where_char('adm_usuario_auth.token','=',' ')
			),"AND");
			$where_montado2 = $this->return_params_mont(array(
			$this->return_where_int('adm_usuario_auth.status_id','=',1),
			$this->return_where_char('adm_usuario_auth.usertelefone','=',$this->getUsername()),
			$this->return_where_char('adm_usuario_auth.senha','=',$this->getSenha()),
			$this->return_where_char('adm_usuario_auth.token','=',' ')
		  ),"AND");
			$this->setWhere("{$where_montado} OR {$where_montado1} OR {$where_montado2}");

			//EXECUTO
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){

					//SETO DADOS
					$this->setUsuario_id($exec[$i]['adm_usuario_id']);
					$this->setNome($exec[$i]['nome']);
					$this->setUseremail($exec[$i]['useremail']);
					$this->setImg_perfil($exec[$i]['img_perfil']);

					//GRAVO ACESSO DO USUÁRIO
					$this->setTable('adm_usuario_auth');
					$this->setColuna('ultimo_acesso');
					$this->setValue(date("Y-m-d H:i:s"));
					$this->setWhere(" adm_usuario_id = ".$exec[$i]['adm_usuario_id']." ");
					$this->Update();
					$this->limpo_campos();
			}

			//RETORNO TRUE OU ERRO
			if(count($exec) == 1){
				return true;
			}else{
				return $this->get_msgError();
			}
		}





//***************************************************************************************************************************************************************
//VERIFICO SE TELEFONE, USERNAME OU EMAIL É VALIDO E RETORNO EMAIL
//***************************************************************************************************************************************************************
		function retorno_username_valido_email(){

			//MONTO O COMANDO
			$this->setTable('adm_usuario_auth');
			$this->setColuna('adm_usuario.email_notificacoes')->setColuna('adm_usuario.nome')->setColuna('adm_usuario_auth.adm_usuario_id')->setColuna('adm_usuario_auth.usertelefone');
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_auth.adm_usuario_id');

			//MONTO WHERE
			$where_montado = $this->return_params_mont(array(
			$this->return_where_char('adm_usuario_auth.username','=',$this->getUsername())
			),"AND");
			$where_montado1 = $this->return_params_mont(array(
			$this->return_where_char('adm_usuario_auth.useremail','=',$this->getUsername())
			),"AND");
			$where_montado2 = $this->return_params_mont(array(
			$this->return_where_char('adm_usuario_auth.usertelefone','=',$this->getUsername())
			),"AND");
			$this->setWhere("{$where_montado} OR {$where_montado1} OR {$where_montado2}");
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$this->setEmail($exec[$i]['email_notificacoes']);
				$this->setNome($exec[$i]['nome']);
				$this->setUsuario_id($exec[$i]['adm_usuario_id']);
				$this->setCampos('usertelefone',$exec[$i]['usertelefone']);
				$this->setCampos('id_user',$exec[$i]['adm_usuario_id']);
			}

			//RETORNO CAMPO OU ERRO
			if(count($exec) == 1){
				return true;
			}else{
				return $this->get_msgError();
			}
		}





//***************************************************************************************************************************************************************
//ALTERO A SENHA
//***************************************************************************************************************************************************************
		function altero_senha(){

			//MONTO O COMANDO
			$this->setTable('adm_usuario_auth');
			$this->setColuna('senha')->setColuna('ultima_mod_senha')->setColuna('modificado')->setColuna('token');
			$this->setValue($this->getSenha())->setValue(date("Y-m-d H:i:s"))->setValue(date("Y-m-d H:i:s"))->setValue('');
			$this->setWhere($this->return_where_int('adm_usuario_id','=',$this->getId()));
			$exec = $this->Update();
			$this->limpo_campos();

			//RETORNO CAMPO OU ERRO
			if(count($exec) == 1){
				return true;
			}else{
				return $this->get_msgError();
			}
		}



//***************************************************************************************************************************************************************
//ALTERO TOKEN
//***************************************************************************************************************************************************************
		function insiro_token(){

			//MONTO O COMANDO
			$this->setTable('adm_usuario_auth');
			$this->setColuna('modificado')->setColuna('token');
			$this->setValue(date("Y-m-d H:i:s"))->setValue($this->getCampos('token'));
			$this->setWhere($this->return_where_int('adm_usuario_id','=',$this->getId()));
			$exec = $this->Update();
			$this->limpo_campos();

			//RETORNO CAMPO OU ERRO
			if(count($exec) == 1){
				return true;
			}else{
				return $this->get_msgError();
			}
		}



//***************************************************************************************************************************************************************
//VALIDO TOKEN DO BANCO
//***************************************************************************************************************************************************************
		function valido_token(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_usuario_auth'); // Tabela
			$this->setColuna('token');
			$this->setWhere("adm_usuario_auth.adm_usuario_id = {$this->getId()} AND adm_usuario_auth.token = '".$this->getCampos('token')."' "); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) == 1){
				return true;
			}else{
				return false;
			}
		}



//***************************************************************************************************************************************************************
//VALIDO TOKEN DO BANCO VAZIO
//***************************************************************************************************************************************************************
		function valido_token_vazio(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_usuario_auth'); // Tabela
			$this->setColuna('token');
			$this->setWhere("adm_usuario_auth.adm_usuario_id = {$this->getId()} AND adm_usuario_auth.token != '' "); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) == 1){
				return true;
			}else{
				return false;
			}
		}



//***************************************************************************************************************************************************************
//INSIRO FORCE TOKEN USUARIO
//***************************************************************************************************************************************************************
		function force_senha(){

			//MONTO O COMANDO
			$this->setTable('adm_usuario_auth');
			$this->setColuna('force_senha')->setColuna('modificado');
			$this->setValue('true')->setValue(date("Y-m-d H:i:s"));
			$this->setWhere($this->return_where_int('adm_usuario_id','=',$this->getId()));
			$exec = $this->Update();
			$this->limpo_campos();

			//RETORNO CAMPO OU ERRO
			if(count($exec) == 1){
				return true;
			}else{
				return $this->get_msgError();
			}
		}


//***************************************************************************************************************************************************************
//RETORNO FORCE TOKEN USUARIO
//***************************************************************************************************************************************************************
		function return_force_senha(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_usuario_auth'); // Tabela
			$this->setColuna('force_senha');
			$this->setWhere("adm_usuario_auth.adm_usuario_id = {$this->getId()} AND adm_usuario_auth.force_senha = 'true' "); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) == 1){
				return true;
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



//***************************************************************************************************************************************************************
//LISTAGEM
//***************************************************************************************************************************************************************
		function listagem($limit = null){

			//===========================================================
			//RECEBO PARAMETROS
			$funcoes = new funcoes();
			$this->carrego_parametros(); // Carrego parametros
			$tabela = $this->getCampos('adm_usuario'); // Pego valor
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
				 ->setColuna($tabela.'.nome')
				 ->setColuna($tabela.'.data_nascimento')
				 ->setColuna($tabela.'.codigo')
				 ->setColuna($tabela.'.sexo')
				 ->setColuna($tabela.'.telefone')
				 ->setColuna($tabela.'.telefone2')
				 ->setColuna($tabela.'.email')
				 ->setColuna($tabela.'.email2')
				 ->setColuna($tabela.'.email_notificacoes')
				 ->setColuna($tabela.'.img_perfil')
				 ->setColuna($tabela.'.assinatura_email')
				 ->setColuna('adm_usuario_auth.username')
				 ->setColuna('adm_usuario_auth.status_id')
				 ->setColuna('adm_usuario_auth.usertelefone')
				 ->setColuna('adm_usuario_auth.useremail')
				 ->setColuna('adm_usuario_auth.ultimo_acesso')
				 ->setColuna('adm_usuario_auth.ultima_mod_senha')
				 ->setColuna('adm_usuario_auth.ultima_mod_username')
				 ->setColuna('config_status.status')
				 ->setColuna('adm_usuario_endereco.logradouro')
				 ->setColuna('adm_usuario_endereco.numero')
				 ->setColuna('adm_usuario_endereco.complemento')
				 ->setColuna('adm_usuario_endereco.bairro')
				 ->setColuna('adm_usuario_endereco.CEP')
				 ->setColuna('adm_usuario_endereco.referencia')
				 ->setColuna('local_estado.estado')
				 ->setColuna('local_cidade.cidade')
				 ->setColuna('adm_usuario_nivel.nivel')

				 ;

			//===========================================================
			//MONTO INNER JOIN ****-
			$this->setInner('
				INNER JOIN adm_usuario_auth ON adm_usuario_auth.adm_usuario_id = adm_usuario.id
				INNER JOIN adm_usuario_endereco ON adm_usuario_endereco.adm_usuario_id = adm_usuario.id
				INNER JOIN config_status ON config_status.id = adm_usuario_auth.status_id
				INNER JOIN local_estado ON local_estado.id = adm_usuario_endereco.estado_id
				INNER JOIN local_cidade ON local_cidade.id = adm_usuario_endereco.cidade_id
				INNER JOIN adm_usuario_nivel_usuario ON adm_usuario_nivel_usuario.adm_usuario_id = adm_usuario.id
				INNER JOIN adm_usuario_nivel ON adm_usuario_nivel.id = adm_usuario_nivel_usuario.adm_usuario_nivel_id
			 '); // Inner join

			//===========================================================
			//MONTO PESQUISA SIMPLES ****-
			if(!empty($query_pesquisa) && $query_pesquisa != "Pesquisa Avançada"){

				//CONDIÇÕES PERSONALIZADAS DE PESQUISA
				if($query_pesquisa == 'Status: Ativo'){
					$where = $this->return_where_char('adm_usuario_auth.status_id','=',1);
					$conf_personalizada = true;
				}
				if($query_pesquisa == 'Status: Inativo'){
					$where = $this->return_where_char('adm_usuario_auth.status_id','=',2);
					$conf_personalizada = true;
				}

				//PESQUISA SIMPLES
				if($conf_personalizada == false){
					$array_pesq[] = $this->return_where_int($tabela.'.id','=',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario.nome',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario.sexo',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario.email',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario.email2',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario.email_notificacoes',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario_auth.useremail',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('adm_usuario_auth.username',$query_pesquisa);
					$array_pesq[] = $this->return_where_int('adm_usuario_auth.usertelefone','=',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('local_estado.estado',$query_pesquisa);
					$array_pesq[] = $this->return_where_like('local_cidade.cidade',$query_pesquisa);
					$where = $this->return_params_mont($array_pesq,'OR',true);
				}

				//SETO O WHERE
				$this->setWhere($where);
			}


			//===========================================================
			//MONTO PESQUISA AVANÇADA ****-
			if(!empty($query_pesquisa_avancada) && $query_pesquisa == "Pesquisa Avançada"){

				//PERIODO DE DATA
				$array_pesq1[] = $this->return_where_data($query_pesquisa_avancada['periodo_tipo_data'],$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_de'],'Y-m-d'),$funcoes->conv_datahora($query_pesquisa_avancada['periodo_data_ate'],'Y-m-d'));
				$array_pesq1[] = $this->return_where_int('adm_usuario_auth.status_id','=',$query_pesquisa_avancada['pesq_avanc_status']);
				$array_pesq1[] = $this->return_where_like($tabela.'.nome',$query_pesquisa_avancada['pesq_avanc_nome']);
				$array_pesq1[] = $this->return_where_like($tabela.'.sexo',$query_pesquisa_avancada['pesq_avanc_sexo']);
				$array_pesq1[] = $this->return_where_like($tabela.'.data_nascimento',$funcoes->conv_datahora($query_pesquisa_avancada['pesq_avanc_data_nascimento'],'Y-m-d'));
				$array_pesq1[] = $this->return_where_like($tabela.'.telefone',$query_pesquisa_avancada['pesq_avanc_telefone']);
				$array_pesq1[] = $this->return_where_like($tabela.'.telefone2',$query_pesquisa_avancada['pesq_avanc_telefone2']);
				$array_pesq1[] = $this->return_where_like($tabela.'.email',$query_pesquisa_avancada['pesq_avanc_email']);
				$array_pesq1[] = $this->return_where_like($tabela.'.email2',$query_pesquisa_avancada['pesq_avanc_email2']);
				$array_pesq1[] = $this->return_where_int('adm_usuario_nivel_usuario.adm_usuario_nivel_id','=',$query_pesquisa_avancada['pesq_avanc_cargos']);
				$array_pesq1[] = $this->return_where_like('adm_usuario_auth.username',$query_pesquisa_avancada['pesq_avanc_username']);
				$array_pesq1[] = $this->return_where_char('adm_usuario_auth.usertelefone','=',$query_pesquisa_avancada['pesq_avanc_usertelefone']);
				$array_pesq1[] = $this->return_where_like('adm_usuario_auth.useremail',$query_pesquisa_avancada['pesq_avanc_useremail']);
				$array_pesq1[] = $this->return_where_like('adm_usuario_endereco.logradouro',$query_pesquisa_avancada['pesq_avanc_logradouro']);
				$array_pesq1[] = $this->return_where_int('adm_usuario_endereco.numero','=',$query_pesquisa_avancada['pesq_avanc_numero']);
				$array_pesq1[] = $this->return_where_like('adm_usuario_endereco.complemento',$query_pesquisa_avancada['pesq_avanc_complemento']);
				$array_pesq1[] = $this->return_where_like('adm_usuario_endereco.bairro',$query_pesquisa_avancada['pesq_avanc_bairro']);
				$array_pesq1[] = $this->return_where_like('adm_usuario_endereco.CEP',$query_pesquisa_avancada['pesq_avanc_CEP']);
				$array_pesq1[] = $this->return_where_like('local_estado.estado',$query_pesquisa_avancada['pesq_avanc_estado']);
				$array_pesq1[] = $this->return_where_like('local_cidade.cidade',$query_pesquisa_avancada['pesq_avanc_cidade']);
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
				//CAMPO NOME CRESCENTE
				if($order_by[$i] == 'NOME CRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.nome','ASC');
				}
				//CAMPO NOME DECRESCENTE
				if($order_by[$i] == 'NOME DECRESCENTE'){
					$array_orderby[] = $this->return_order_by($tabela.'.nome','DESC');
				}
				//CAMPO USERNAME CRESCENTE
				if($order_by[$i] == 'USERNAME CRESCENTE'){
					$array_orderby[] = $this->return_order_by('adm_usuario_auth.username','ASC');
				}
				//CAMPO USERNAME DECRESCENTE
				if($order_by[$i] == 'USERNAME DECRESCENTE'){
					$array_orderby[] = $this->return_order_by('adm_usuario_auth.username','DESC');
				}
				//CAMPO EMAIL CRESCENTE
				if($order_by[$i] == 'EMAIL CRESCENTE'){
					$array_orderby[] = $this->return_order_by('adm_usuario_auth.useremail','ASC');
				}
				//CAMPO EMAIL DECRESCENTE
				if($order_by[$i] == 'EMAIL DECRESCENTE'){
					$array_orderby[] = $this->return_order_by('adm_usuario_auth.useremail','DESC');
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
				//$array[] = $exec[$i];

				$array[] = array(
					'id'         => $exec[$i]['id'],
					'modificado' => $exec[$i]['modificado'],
					'criado'     => $exec[$i]['criado'],
					'nome'       => $exec[$i]['nome'],
					'data_nascimento' => $exec[$i]['data_nascimento'],
					'nivel'			  => $exec[$i]['nivel'],
					'codigo'          => $exec[$i]['codigo'],
					'sexo'            => $exec[$i]['sexo'],
					'telefone'        => $exec[$i]['telefone'],
					'telefone2'       => $exec[$i]['telefone2'],
					'email'           => $exec[$i]['email'],
					'email2'          => $exec[$i]['email2'],
					'email_notificacoes' => $exec[$i]['email_notificacoes'],
					'img_perfil'     => $exec[$i]['img_perfil'],
					'username'       => $exec[$i]['username'],
					'status_id'      => $exec[$i]['status_id'],
					'usertelefone'   => $exec[$i]['usertelefone'],
					'useremail'      => $exec[$i]['useremail'],
					'ultimo_acesso'  => $exec[$i]['ultimo_acesso'],
					'ultima_mod_senha'    => $exec[$i]['ultima_mod_senha'],
					'ultima_mod_username' => $exec[$i]['ultima_mod_username'],
					'logradouro'          => $exec[$i]['logradouro'],
					'status'              => $exec[$i]['status'],
					'numero'              => $exec[$i]['numero'],
					'complemento'         => $exec[$i]['complemento'],
					'bairro'              => $exec[$i]['bairro'],
					'CEP'                 => $exec[$i]['CEP'],
					'referencia'          => $exec[$i]['referencia'],
					'estado'              => $exec[$i]['estado'],
					'cidade'              => $exec[$i]['cidade'],
					'assinatura_email'    => $exec[$i]['assinatura_email'],
					'usuario_online'      => $this->usuario_online($exec[$i]['id']),

				);
			}
			return $array;
		}





//***************************************************************************************************************************************************************
//FUNÇÃO RETORNA OS USUARIOS ONLINE
//***************************************************************************************************************************************************************
		function usuario_online($id){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_usuario_online'); // Tabela
			$this->setColuna('*');
			$this->setWhere("adm_usuario_online.adm_usuario_id={$id}"); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) == 1){
				return 1;
			}else{
				return 2;
			}
		}





//***************************************************************************************************************************************************************
//FUNÇÃO SETO DADOS DO EDITAR RETORNA ARRAY
//***************************************************************************************************************************************************************
		function set_editar(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getTabela()); // Tabela
			$this->setColuna('adm_usuario.nome')
				->setColuna('adm_usuario.criado')
				->setColuna('adm_usuario.id')
				->setColuna('adm_usuario.sexo')
				->setColuna('adm_usuario.data_nascimento')
				->setColuna('adm_usuario.telefone')
				->setColuna('adm_usuario.telefone2')
				->setColuna('adm_usuario.email')
				->setColuna('adm_usuario.email2')
				->setColuna('adm_usuario.assinatura_email')
				->setColuna('adm_usuario_auth.ultimo_acesso')
				->setColuna('adm_usuario_auth.ultima_mod_senha')
				->setColuna('adm_usuario_auth.username')
				->setColuna('adm_usuario_auth.useremail')
				->setColuna('adm_usuario_auth.usertelefone')
				->setColuna('adm_usuario_endereco.logradouro')
				->setColuna('adm_usuario_endereco.numero')
				->setColuna('adm_usuario_endereco.complemento')
				->setColuna('adm_usuario_endereco.bairro')
				->setColuna('adm_usuario_endereco.CEP')
				->setColuna('adm_usuario_endereco.estado_id')
				->setColuna('adm_usuario_endereco.cidade_id');
			$this->setInner('
				INNER JOIN adm_usuario_auth ON adm_usuario_auth.adm_usuario_id = adm_usuario.id
				INNER JOIN adm_usuario_endereco ON adm_usuario_endereco.adm_usuario_id = adm_usuario.id
			'); // Inner join
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
//FUNÇÃO SETO DADOS DO EDITAR RETORNA ARRAY
//***************************************************************************************************************************************************************
		function set_editar2(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_usuario_nivel_usuario'); // Tabela
			$this->setColuna('*');
			$this->setWhere("adm_usuario_nivel_usuario.adm_usuario_id={$this->getId()}"); // Where ****-
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

			//TABELA ADM_USUARIO
			$img_perfil = $this->getCampos('img_perfil');
			if(empty($img_perfil)){
				$img_perfil = '';
			}
			$this->setTable($this->getTabela()); // Tabela
			$this->setColuna('nome')->setColuna('sexo')->setColuna('data_nascimento')->setColuna('telefone')->setColuna('telefone2')->setColuna('email')->setColuna('email2')->setColuna('email_notificacoes')->setColuna('assinatura_email')->setColuna('img_perfil'); // Colunas ****-
			$this->setValue($this->getCampos('nome'))->setValue($this->getCampos('sexo'))->setValue($this->getCampos('data_nascimento'))->setValue($this->getCampos('telefone'))->setValue($this->getCampos('telefone2'))->setValue($this->getCampos('email'))->setValue($this->getCampos('email2'))->setValue($this->getCampos('email_notificacoes'))->setValue($this->getCampos('assinatura_email'))->setValue($img_perfil);
			$exec = $this->Create();
			$this->limpo_campos();
			$id = $this->get_last_id();
			$this->setUltimo_id($this->get_last_id());

			//TABELA ADM_USUARIO_AUTH
			$this->setTable('adm_usuario_auth'); // Tabela
			$this->setColuna('adm_usuario_id')->setColuna('status_id')->setColuna('username')->setColuna('usertelefone')->setColuna('useremail')->setColuna('token'); // Colunas ****-
			$this->setValue($id)->setValue($this->getCampos('status_id'))->setValue($this->getCampos('username'))->setValue($this->getCampos('usertelefone'))->setValue($this->getCampos('useremail'))->setValue($this->getCampos('token')); //Valores ****-
			$exec2 = $this->Create();
			$this->limpo_campos();

			//TABELA ADM_USUARIO_ENDERECO
			$this->setTable('adm_usuario_endereco'); // Tabela
			$this->setColuna('adm_usuario_id')->setColuna('estado_id')->setColuna('cidade_id')->setColuna('logradouro')->setColuna('numero')->setColuna('complemento')->setColuna('bairro')->setColuna('CEP'); // Colunas ****-
			$this->setValue($id)->setValue($this->getCampos('estado_id'))->setValue($this->getCampos('cidade_id'))->setValue($this->getCampos('logradouro'))->setValue($this->getCampos('numero'))->setValue($this->getCampos('complemento'))->setValue($this->getCampos('bairro'))->setValue($this->getCampos('CEP')); //Valores ****-
			$exec3 = $this->Create();
			$this->limpo_campos();

			//ADICIONO CARGOS
			if($_SESSION['adm_id_cargo'] == 1 || $_SESSION['adm_id_cargo'] == 2 || $_SESSION['adm_id_cargo'] == 3) {
				$cargos = $this->getCampos('cargos');
				for ($i=0; $i <count($cargos) ; $i++) {
					$this->setTable('adm_usuario_nivel_usuario'); // Tabela
					$this->setColuna('adm_usuario_id')->setColuna('adm_usuario_nivel_id'); // Colunas ****-
					$this->setValue($id)->setValue($cargos[$i]); //Valores ****-
					$exec5 = $this->Create();
					$this->limpo_campos();
				}
			}

			//-----------------------------------
			//TABELA RESTRIÇÕES

			//QUERY QUE LISTA O NIVEL E PEGA O PADRÃO DEFAULT
			$this->setTable('adm_usuario_modulo_opcoes_default'); // Tabela
			$this->setColuna('*'); // Colunas ****-
			$exec4 = $this->Read();
			$this->limpo_campos();
			for($i0=0;$i0<count($exec4);$i0++){

				//INSIRO NA TABELA ADM_USUARIO_MODULO_OPCOES
				$this->setTable('adm_usuario_modulo_opcoes'); // Tabela
				$this->setColuna('adm_usuario_id')->setColuna('adm_usuario_modulo_id')->setColuna('adm_usuario_permissoes_acoes_id')->setColuna('opcoes'); // Colunas ****-
				$this->setValue($id)->setValue($exec4[$i0]['adm_usuario_modulo_id'])->setValue($exec4[$i0]['adm_usuario_permissoes_acoes_id'])->setValue($exec4[$i0]['opcoes']); //Valores ****-
				$exec5 = $this->Create();
				$this->limpo_campos();
			}

			//RETORNO
			if(count($exec) >= 1 && count($exec2) >= 1 && count($exec3) >= 1){
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

			//TABELA ADM_USUARIO
			$this->setTable($this->getTabela()); // Tabela
			$img_perfil = $this->getCampos('img_perfil');
			if(!empty($img_perfil)){
				$this->setColuna('nome')->setColuna('sexo')->setColuna('data_nascimento')->setColuna('telefone')->setColuna('telefone2')->setColuna('email')->setColuna('email2')->setColuna('email_notificacoes')->setColuna('assinatura_email')->setColuna('img_perfil')->setColuna('modificado'); // Colunas ****-
				$this->setValue($this->getCampos('nome'))->setValue($this->getCampos('sexo'))->setValue($this->getCampos('data_nascimento'))->setValue($this->getCampos('telefone'))->setValue($this->getCampos('telefone2'))->setValue($this->getCampos('email'))->setValue($this->getCampos('email2'))->setValue($this->getCampos('email_notificacoes'))->setValue($this->getCampos('assinatura_email'))->setValue($this->getCampos('img_perfil'))->setValue($this->getModificado());
			}else{
				$this->setColuna('nome')->setColuna('sexo')->setColuna('data_nascimento')->setColuna('telefone')->setColuna('telefone2')->setColuna('email')->setColuna('email2')->setColuna('email_notificacoes')->setColuna('assinatura_email')->setColuna('modificado'); // Colunas ****-
				$this->setValue($this->getCampos('nome'))->setValue($this->getCampos('sexo'))->setValue($this->getCampos('data_nascimento'))->setValue($this->getCampos('telefone'))->setValue($this->getCampos('telefone2'))->setValue($this->getCampos('email'))->setValue($this->getCampos('email2'))->setValue($this->getCampos('email_notificacoes'))->setValue($this->getCampos('assinatura_email'))->setValue($this->getModificado());
			}
			$this->setValue($this->getCampos('nome'))->setValue($this->getCampos('sexo'))->setValue($this->getCampos('data_nascimento'))->setValue($this->getCampos('telefone'))->setValue($this->getCampos('telefone2'))->setValue($this->getCampos('email'))->setValue($this->getCampos('email2'))->setValue($this->getCampos('email_notificacoes'))->setValue($this->getCampos('assinatura_email'))->setValue($this->getCampos('img_perfil'))->setValue($this->getModificado());
			$this->setWhere("id={$this->getId()}"); // ****-
			$exec = $this->Update();
			$this->limpo_campos();

			//TABELA ADM_USUARIO_AUTH
			$this->setTable('adm_usuario_auth'); // Tabela
			$senha_ = $this->getCampos('senha_normal');
			if(empty($senha_)){
				$this->setColuna('adm_usuario_id')->setColuna('username')->setColuna('usertelefone')->setColuna('useremail')->setColuna('modificado')->setColuna('ultima_mod_username'); // Colunas ****-
				$this->setValue($this->getId())->setValue($this->getCampos('username'))->setValue($this->getCampos('usertelefone'))->setValue($this->getCampos('useremail'))->setValue($this->getModificado())->setValue($this->getModificado()); //Valores ****-
			}else{
				$this->setColuna('adm_usuario_id')->setColuna('username')->setColuna('usertelefone')->setColuna('useremail')->setColuna('senha')->setColuna('ultima_mod_senha')->setColuna('modificado')->setColuna('ultima_mod_username'); // Colunas ****-
				$this->setValue($this->getId())->setValue($this->getCampos('username'))->setValue($this->getCampos('usertelefone'))->setValue($this->getCampos('useremail'))->setValue($this->getCampos('senha'))->setValue($this->getModificado())->setValue($this->getModificado())->setValue($this->getModificado()); //Valores ****-
			}
			$this->setWhere("adm_usuario_id={$this->getId()}"); // ****-
			$exec2 = $this->Update();
			$this->limpo_campos();

			//TABELA ADM_USUARIO_ENDERECO
			$this->setTable('adm_usuario_endereco'); // Tabela
			$this->setColuna('adm_usuario_id')->setColuna('estado_id')->setColuna('cidade_id')->setColuna('logradouro')->setColuna('numero')->setColuna('complemento')->setColuna('bairro')->setColuna('CEP')->setColuna('modificado'); // Colunas ****-
			$this->setValue($this->getId())->setValue($this->getCampos('estado_id'))->setValue($this->getCampos('cidade_id'))->setValue($this->getCampos('logradouro'))->setValue($this->getCampos('numero'))->setValue($this->getCampos('complemento'))->setValue($this->getCampos('bairro'))->setValue($this->getCampos('CEP'))->setValue($this->getModificado()); //Valores ****-
			$this->setWhere("adm_usuario_id={$this->getId()}"); // ****-
			$exec3 = $this->Update();
			$this->limpo_campos();

			//ALTERO CARGO USUARIO
			if($_SESSION['adm_id_cargo'] == 1 || $_SESSION['adm_id_cargo'] == 2 || $_SESSION['adm_id_cargo'] == 3) {
				$this->setTable('adm_usuario_nivel_usuario'); // Tabela
				$this->setColuna('adm_usuario_nivel_id'); // Colunas ****-
				$this->setValue($this->getCampos('cargos')); //Valores ****-
				$this->setWhere("adm_usuario_id={$this->getId()}"); // ****-
				$exec4 = $this->Update();
				$this->limpo_campos();
			}

			//RETORNO
			if(count($exec) >= 1 && count($exec2) >= 1 && count($exec3) >= 1){
				return true;
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





//***************************************************************************************************************************************************************
//FUNÇÃO DETALHAMENTO
//***************************************************************************************************************************************************************
		function detalhamento(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable($this->getTabela()); // Tabela
			$this
			->setColuna($this->getTabela().'.id')
			->setColuna($this->getTabela().'.modificado')
			->setColuna($this->getTabela().'.criado')
			->setColuna($this->getTabela().'.nome')
			->setColuna($this->getTabela().'.data_nascimento')
			->setColuna($this->getTabela().'.codigo')
			->setColuna($this->getTabela().'.sexo')
			->setColuna($this->getTabela().'.telefone')
			->setColuna($this->getTabela().'.telefone2')
			->setColuna($this->getTabela().'.email')
			->setColuna($this->getTabela().'.email2')
			->setColuna($this->getTabela().'.email_notificacoes')
			->setColuna($this->getTabela().'.img_perfil')
			->setColuna($this->getTabela().'.assinatura_email')
			->setColuna('adm_usuario_auth.username')
			->setColuna('adm_usuario_auth.status_id')
			->setColuna('adm_usuario_auth.usertelefone')
			->setColuna('adm_usuario_auth.useremail')
			->setColuna('adm_usuario_auth.ultimo_acesso')
			->setColuna('adm_usuario_auth.ultima_mod_senha')
			->setColuna('adm_usuario_auth.ultima_mod_username')
			->setColuna('config_status.status')
			->setColuna('adm_usuario_endereco.logradouro')
			->setColuna('adm_usuario_endereco.numero')
			->setColuna('adm_usuario_endereco.complemento')
			->setColuna('adm_usuario_endereco.bairro')
			->setColuna('adm_usuario_endereco.CEP')
			->setColuna('adm_usuario_endereco.referencia')
			->setColuna('local_estado.estado')
			->setColuna('local_cidade.cidade')
			->setColuna('adm_usuario_nivel.nivel')
			;
			$this->setInner('
				INNER JOIN adm_usuario_auth ON adm_usuario_auth.adm_usuario_id = adm_usuario.id
				INNER JOIN adm_usuario_endereco ON adm_usuario_endereco.adm_usuario_id = adm_usuario.id
				INNER JOIN config_status ON config_status.id = adm_usuario_auth.status_id
				INNER JOIN local_estado ON local_estado.id = adm_usuario_endereco.estado_id
				INNER JOIN local_cidade ON local_cidade.id = adm_usuario_endereco.cidade_id
				INNER JOIN adm_usuario_nivel_usuario ON adm_usuario_nivel_usuario.adm_usuario_id = adm_usuario.id
				INNER JOIN adm_usuario_nivel ON adm_usuario_nivel.id = adm_usuario_nivel_usuario.adm_usuario_nivel_id
			');
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
//FUNÇÃO DETALHAMENTO RESTRIÇÕES
//***************************************************************************************************************************************************************
		function detalhamento_restricoes(){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_usuario_modulo_opcoes'); // Tabela
			$this
			->setColuna('adm_usuario_modulo.modulo')
			->setColuna('adm_usuario_modulo.descricao')
			->setColuna('adm_usuario_modulo_opcoes.adm_usuario_modulo_id')
			;
			$this->setInner('
				INNER JOIN adm_usuario_modulo ON adm_usuario_modulo.id = adm_usuario_modulo_opcoes.adm_usuario_modulo_id
				INNER JOIN adm_usuario_permissoes_acoes ON adm_usuario_permissoes_acoes.id = adm_usuario_modulo_opcoes.adm_usuario_permissoes_acoes_id
			');
			$this->setWhere("adm_usuario_modulo_opcoes.adm_usuario_id={$this->getId()} AND adm_usuario_modulo_opcoes.opcoes = 1 AND adm_usuario_modulo_opcoes.adm_usuario_permissoes_acoes_id != 17"); // Where ****-
			$this->setGroup('adm_usuario_modulo_opcoes.adm_usuario_modulo_id');
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return $exec;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//FUNÇÃO DETALHAMENTO RESTRIÇÕES
//***************************************************************************************************************************************************************
		function detalhamento_restricoes2($id){
			$this->carrego_parametros(); // Carrego parametros
			$this->setTable('adm_usuario_modulo_opcoes'); // Tabela
			$this
			->setColuna('adm_usuario_modulo.modulo')
			->setColuna('adm_usuario_modulo.descricao')
			->setColuna('adm_usuario_permissoes_acoes.acoes')
			->setColuna('adm_usuario_modulo_opcoes.opcoes')
			;
			$this->setInner('
				INNER JOIN adm_usuario_modulo ON adm_usuario_modulo.id = adm_usuario_modulo_opcoes.adm_usuario_modulo_id
				INNER JOIN adm_usuario_permissoes_acoes ON adm_usuario_permissoes_acoes.id = adm_usuario_modulo_opcoes.adm_usuario_permissoes_acoes_id
			');
			$this->setWhere("adm_usuario_modulo_opcoes.adm_usuario_id={$this->getId()} AND adm_usuario_modulo_opcoes.opcoes = 1 AND adm_usuario_modulo_opcoes.adm_usuario_permissoes_acoes_id != 17 AND adm_usuario_modulo_opcoes.adm_usuario_modulo_id = {$id} "); // Where ****-
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec) >= 1){
				return $exec;
			}else{
				return false;
			}
		}




}
