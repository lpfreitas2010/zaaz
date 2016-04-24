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

	//CLASSE
	class coreModel extends config_model_adm {


//***************************************************************************************************************************************************************
//RETORNO ANIVERSARIANTES DO DIA
//***************************************************************************************************************************************************************
		function retorno_aniversariantes_dia(){

			//MONTO O COMANDO
			$this->setTable('adm_usuario');
			$this->setColuna('adm_usuario.nome')->setColuna('adm_usuario.id')->setColuna('adm_usuario.data_nascimento');
			$this->setWhere('day(adm_usuario.data_nascimento) = day(CURDATE()) AND MONTH(adm_usuario.data_nascimento) = MONTH(CURDATE())');
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array[] = $exec[$i];
			}

			//RETORNO CAMPO OU ERRO
			if(count($exec)>=1){
				return $array;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//RETORNO DADOS DO USUARIO
//***************************************************************************************************************************************************************
		function retorno_dados_usuario_usuario(){

			//MONTO O COMANDO
			$this->setTable('adm_usuario');
			$this->setColuna('adm_usuario.nome')->setColuna('adm_usuario.id')->setColuna('adm_usuario.data_nascimento')->setColuna('adm_usuario.sexo')->setColuna('adm_usuario.codigo')->setColuna('adm_usuario.img_perfil');
			$this->setWhere($this->return_where_char('adm_usuario.id','=',$this->getId()));
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array[] = $exec[$i];
			}

			//RETORNO CAMPO OU ERRO
			if(count($exec) == 1){
				return $array;
			}else{
				return $this->get_msgError();
			}
		}


//***************************************************************************************************************************************************************
//VERIFICO RESTRIÇÕES DO SISTEMA
//***************************************************************************************************************************************************************
        function restricoes_sistema($adm_usuario_modulo_id = null) {

			//MONTO O COMANDO
			$this->setTable('adm_usuario_modulo_opcoes');
			$this->setColuna('adm_usuario_permissoes_acoes_id')->setColuna('adm_usuario_modulo_id')->setColuna('opcoes');
			if(empty($adm_usuario_modulo_id)){
				$this->setWhere(' adm_usuario_id = '.$this->getId().'  ');
			}else{
				$this->setWhere(' adm_usuario_modulo_id = '.$adm_usuario_modulo_id.' AND adm_usuario_id = '.$this->getId().'  ');
			}
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array[] = $exec[$i];
			}

			//RETORNO CAMPO OU ERRO
			if(count($exec) >= 1){
				return $array;
			}else{
				return $this->get_msgError();
			}
        }


//***************************************************************************************************************************************************************
//VERIFICO NOTIFICAÇÕES DO SISTEMA
//***************************************************************************************************************************************************************
        function verifico_notificacoes($param = null) {

			//MONTO O COMANDO
			$this->setTable('adm_usuario_notificaoes');
			$this->setColuna('adm_usuario.nome')->setColuna('adm_usuario_notificaoes.id')->setColuna('adm_usuario_notificaoes.class_icon')->setColuna('adm_usuario_notificaoes.status_id')->setColuna('adm_usuario_notificaoes.mensagem')->setColuna('adm_usuario_notificaoes.url_destino')->setColuna('adm_usuario_notificaoes.modificado')->setColuna('adm_usuario_notificaoes.criado');
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_notificaoes.adm_usuario_id'); // Inner join
			if(!empty($param)){
				$this->setWhere(' ((adm_usuario_notificaoes.adm_usuario_id = 1) OR (adm_usuario_notificaoes.adm_usuario_id = '.$this->getId().')) AND (adm_usuario_notificaoes.status_id = 2) ');
			}else{
				$this->setWhere('(adm_usuario_notificaoes.adm_usuario_id = 1) OR (adm_usuario_notificaoes.adm_usuario_id = '.$this->getId().')');
			}
			if(empty($param)){
				$this->setLimit('10');
			}
			$this->setOrder(' adm_usuario_notificaoes.criado DESC ');
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array[] = $exec[$i];
			}

			//RETORNO CAMPO OU ERRO
			if(count($exec) >= 1){
				return $array;
			}else{
				return $this->get_msgError();
			}
        }


//***************************************************************************************************************************************************************
//INSIRO NOTIFICAÇÕES DO SISTEMA
//***************************************************************************************************************************************************************
        function insiro_notificacao() {

			//VERIFICO SE JA TEM ANTES DE INSERIR
			$this->setTable('adm_usuario_notificaoes');
			$this->setColuna('*');
			$this->setWhere("adm_usuario_id = {$this->getCampos('adm_usuario_id')} AND mensagem = '{$this->getCampos('mensagem')}' AND url_destino = '{$this->getCampos('url_destino')}' ");
			$exec = $this->Read();
			$this->limpo_campos();
			if(count($exec)==0){

				//MONTO O COMANDO
				$texto_botao_acao = $this->getCampos('texto_botao_acao');
				if(empty($texto_botao_acao)){
					$texto_botao_acao = '';
				}
				$url_destino = $this->getCampos('url_destino');
				if(empty($url_destino)){
					$url_destino = '';
				}
				$this->setTable('adm_usuario_notificaoes');
				$this->setColuna('adm_usuario_id')->setColuna('status_id')->setColuna('class_icon')->setColuna('mensagem')->setColuna('url_destino')->setColuna('texto_botao_acao'); // Colunas ****-
				$this->setValue($this->getCampos('adm_usuario_id'))->setValue($this->getCampos('status_id'))->setValue($this->getCampos('class_icon'))->setValue($this->getCampos('mensagem'))->setValue($url_destino)->setValue($texto_botao_acao); //Valores ****-
				$exec = $this->Create();
				$this->limpo_campos();
				$this->setUltimo_id($this->get_last_id());
				if(count($exec) >= 1){
					return true;
				}else{
					return false;
				}

			}

        }


//***************************************************************************************************************************************************************
//ALTERO NOTIFICACAO DO SISTEMA
//***************************************************************************************************************************************************************
        function altero_notificacao() {

			//MONTO O COMANDO
			$this->setTable('adm_usuario_notificaoes');
			$this->setColuna('status_id')->setColuna('status_exibicao')->setColuna('modificado'); // Colunas ****-
			$this->setValue($this->getCampos('status_id'))->setValue($this->getCampos('status_exibicao'))->setValue($this->getModificado()); //Valores ****-
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
//VERIFICO NOTIFICAÇÕES DO SISTEMA POPUP
//***************************************************************************************************************************************************************
        function verifico_notificacoes_popup() {

			//MONTO O COMANDO
			$this->setTable('adm_usuario_notificaoes');
			$this->setColuna('adm_usuario.nome')->setColuna('adm_usuario_notificaoes.id')->setColuna('adm_usuario_notificaoes.class_icon')->setColuna('adm_usuario_notificaoes.status_id')->setColuna('adm_usuario_notificaoes.mensagem')->setColuna('adm_usuario_notificaoes.url_destino')->setColuna('adm_usuario_notificaoes.modificado')->setColuna('adm_usuario_notificaoes.criado')->setColuna('adm_usuario_notificaoes.texto_botao_acao');
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_notificaoes.adm_usuario_id'); // Inner join
			$this->setWhere('((adm_usuario_notificaoes.adm_usuario_id = 1) OR (adm_usuario_notificaoes.adm_usuario_id = '.$this->getId().')) AND (adm_usuario_notificaoes.status_id = 2 AND adm_usuario_notificaoes.status_exibicao = 0) ');
			$this->setOrder(' adm_usuario_notificaoes.criado DESC ');
			$this->setLimit('10');
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){

				//ARRAY COM MENSAGEM
                $array[] = $exec[$i];

				//MONTO O COMANDO
				$this->setTable('adm_usuario_notificaoes');
				$this->setColuna('status_exibicao')->setColuna('modificado'); // Colunas ****-
				$this->setValue($this->getCampos('status_exibicao'))->setValue($this->getModificado()); //Valores ****-
				$this->setWhere("id={$exec[$i]['id']}"); // ****-
				$exec1 = $this->Update();
				$this->limpo_campos();
			}
			if(count($exec1) >= 1){
				return $array;
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
//RETORNO CONFIGURAÇÕES DO CARGO
//***************************************************************************************************************************************************************
		function retorno_configs_admin2(){
			if(!empty($_SESSION['adm_id_cargo'])){
				$this->setTable('adm_usuario_nivel'); // Tabela
				$this->setColuna('*');
				$this->setWhere("id=".$_SESSION['adm_id_cargo'].""); // Where ****-
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


//***************************************************************************************************************************************************************
//RETORNO SMS ENVIADOS ADMIN
//***************************************************************************************************************************************************************
		function retorno_total_sms_adm($param){
			$this->setTable('adm_sms_enviados'); // Tabela
			$this->setColuna('count(*) total');
			if($param == 'dia'){
				$array_pesq1[] = $this->return_where_data('criado','CURDATE()','CURDATE() + INTERVAL 1 DAY','true');
				$array_pesq1[] = $this->return_where_char('status','=','000');
				$where = $this->return_params_mont($array_pesq1,'AND',true);
				$this->setWhere($where); // Where ****-
			}
			if($param == 'semana'){
				$array_pesq1[] = $this->return_where_data('criado','CURDATE() - INTERVAL 7 DAY','CURDATE()','true');
				$array_pesq1[] = $this->return_where_char('status','=','000');
				$where = $this->return_params_mont($array_pesq1,'AND',true);
				$this->setWhere($where); // Where ****-
			}
			if($param == 'mes'){
				$array_pesq1[] = $this->return_where_data('criado','CURDATE() - INTERVAL 30 DAY','CURDATE()','true');
				$array_pesq1[] = $this->return_where_char('status','=','000');
				$where = $this->return_params_mont($array_pesq1,'AND',true);
				$this->setWhere($where); // Where ****-
			}
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array = $exec[$i]['total'];
			}
			if(count($exec) >= 1){
				return $array;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//RETORNO EMAILS ENVIADOS ADMIN
//***************************************************************************************************************************************************************
		function retorno_total_email_adm($param){
			$this->setTable('adm_email_enviados'); // Tabela
			$this->setColuna('count(*) total');
			if($param == 'dia'){
				//$this->setWhere(" day(criado) = day(CURDATE()) AND MONTH(criado) = MONTH(CURDATE()) "); // Where ****-
				$array_pesq1[] = $this->return_where_data('criado','CURDATE()','CURDATE() + INTERVAL 1 DAY','true');
				$array_pesq1[] = $this->return_where_char('status','=','Enviado');
				$where = $this->return_params_mont($array_pesq1,'AND',true);
				$this->setWhere($where); // Where ****-
			}
			if($param == 'semana'){
				$array_pesq1[] = $this->return_where_data('criado','CURDATE() - INTERVAL 7 DAY','date_add(CURDATE(), interval 1 day)','true');
				$array_pesq1[] = $this->return_where_char('status','=','Enviado');
				$where = $this->return_params_mont($array_pesq1,'AND',true);
				$this->setWhere($where); // Where ****-
			}
			if($param == 'mes'){
				$array_pesq1[] = $this->return_where_data('criado','CURDATE() - INTERVAL 30 DAY','date_add(CURDATE(), interval 1 day)','true');
				$array_pesq1[] = $this->return_where_char('status','=','Enviado');
				$where = $this->return_params_mont($array_pesq1,'AND',true);
				$this->setWhere($where); // Where ****-
			}
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array = $exec[$i]['total'];
			}
			if(count($exec) >= 1){
				return $array;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//RETORNO TOTAL USUARIO ONLINE ADMIN
//***************************************************************************************************************************************************************
		function retorno_total_usuarios_online_adm(){
			$this->setTable('adm_usuario_online'); // Tabela
			$this->setColuna('count(*) total');
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array = $exec[$i]['total'];
			}
			if(count($exec) >= 1){
				return $array;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//RETORNO TOTAL NOTIFICACOES NÃO LIDAS ADMIN
//***************************************************************************************************************************************************************
		function retorno_total_notificacoes_adm(){
			$this->setTable('adm_usuario_notificaoes'); // Tabela
			$this->setColuna('count(*) total');
			if($_SESSION['adm_id_cargo'] == 1 || $_SESSION['adm_id_cargo'] == 2 || $_SESSION['adm_id_cargo'] == 3) {
				$where  = "";
			}else{
				$where = "AND ((adm_usuario_notificaoes.adm_usuario_id = 1) OR (adm_usuario_notificaoes.adm_usuario_id = ".$_SESSION['adm_id_user'].")) ";
			}
			$this->setWhere('(adm_usuario_notificaoes.status_id = 2 ) '.$where.'');
			$exec = $this->Read();
			$this->limpo_campos();
			for($i=0;$i<count($exec);$i++){
				$array = $exec[$i]['total'];
			}
			if(count($exec) >= 1){
				return $array;
			}else{
				return false;
			}
		}


//***************************************************************************************************************************************************************
//RETORNO LISTAGEM DE USUARIOS ONLINE ADMIN
//***************************************************************************************************************************************************************
		function retorno_listagem_usuarios_online_adm(){
			$this->setTable('adm_usuario_online'); // Tabela
			$this->setColuna('adm_usuario.id')->setColuna('adm_usuario.nome')->setColuna('adm_usuario.img_perfil');
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_online.adm_usuario_id'); // Inner join
			$this->setOrder('adm_usuario.nome ASC');
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
//RETORNO LISTAGEM DE NOTIFICAÇÕES NAO LIDAS ADMIN
//***************************************************************************************************************************************************************
		function retorno_listagem_notificacoes_adm(){
			$this->setTable('adm_usuario_notificaoes'); // Tabela
			$this->setColuna('adm_usuario.id')->setColuna('adm_usuario.nome')->setColuna('adm_usuario.img_perfil')->setColuna('adm_usuario_notificaoes.mensagem')->setColuna('adm_usuario_notificaoes.url_destino')->setColuna('adm_usuario_notificaoes.criado');
			$this->setInner('INNER JOIN adm_usuario ON adm_usuario.id = adm_usuario_notificaoes.adm_usuario_id'); // Inner join
			$this->setLimit(10);
			$this->setOrder('adm_usuario_notificaoes.criado DESC');
			if($_SESSION['adm_id_cargo'] == 1 || $_SESSION['adm_id_cargo'] == 2 || $_SESSION['adm_id_cargo'] == 3) {
				$where  = "";
			}else{
				$where = "AND ((adm_usuario_notificaoes.adm_usuario_id = 1) OR (adm_usuario_notificaoes.adm_usuario_id = ".$_SESSION['adm_id_user'].")) ";
			}
			$this->setWhere('(adm_usuario_notificaoes.status_id = 2 ) '.$where.'');
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
//FUNÇÃO GENÉRICA QUE RETORNA STRING
//***************************************************************************************************************************************************************
		function select_simples_retorna_array_mont(){
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
				$array[] = $exec[$i][$this->getCampos('campo_valor')];
			}
			if(count($exec) >= 1){
				return $array;
			}else{
				return false;
			}
		}


   }
