<?php

	/**
    * Upload de Arquivos
    *
    * @author Fernando
    * @version 2.0.0
    *
    * Dependencias: helper/funcoesHelper.php - inc/WideImage/WideImage.php
    *
    **/

    //=================================================================
	//INCLUDE
	require_once (dirname(dirname((dirname(__FILE__))))).'/libs/core/core.php';
	//=================================================================

    //Classe
	class upload{

		//Variáveis
		public $arquivo;
		public $pasta;
		public $tipo_arquivo;
		public $nome_arquivo;
		public $nome_arquivo_return;
		public $valido_dimensoes;
		public $upload_multiplo;
		public $redimensiono;
		public $tamanho;
		public $width;
		public $height;
		public $msg_erro;
		public $width_p;
		public $height_p;
		public $res_p;
		public $width_m;
		public $height_m;
		public $res_m;
		public $width_g;
		public $height_g;
		public $res_g;
		public $pasta_files;

		//Variavel de Controle
		private $funcoes;
		private $core;

		//Construct
		function __construct() {
			$this->core = new core(); //Instancio Includes
			$this->funcoes = new funcoes();  //Instancio Funções
		}

		/**
	    * Gets the value of pasta_files.
	    *
	    * @return mixed
	    */
	    public function getPasta_files()
	    {
	        return $this->core->get_config('dir_files_comp');
	    }

	    /**
	     * Gets the value of arquivo.
	     *
	     * @return mixed
	     */
	    public function getArquivo($param) /* tmp_name, error, type, name */
	    {
	    	 return $this->arquivo[''.$param.''];
	    }

	    /**
	     * Sets the value of arquivo.
	     *
	     * @param mixed $arquivo the arquivo
	     *
	     * @return self
	     */
	    public function setArquivo($arquivo)
	    {
	        $this->arquivo = $arquivo;

	        return $this;
	    }

	    /**
	     * Gets the value of pasta.
	     *
	     * @return mixed
	     */
	    public function getPasta()
	    {
	        return $this->pasta;
	    }

	    /**
	     * Sets the value of pasta.
	     *
	     * @param mixed $pasta the pasta
	     *
	     * @return self
	     */
	    public function setPasta($pasta)
	    {
	        $this->pasta = $pasta;

	        return $this;
	    }

	    /**
	     * Gets the value of tipo_arquivo.
	     *
	     * @return mixed
	     */
	    public function getTipo_arquivo()
	    {
	        return $this->tipo_arquivo;
	    }

	    /**
	     * Sets the value of tipo_arquivo.
	     *
	     * @param mixed $tipo_arquivo the tipo_arquivo
	     *
	     * @return self
	     */
	    public function setTipo_arquivo($tipo_arquivo)
	    {
	        $this->tipo_arquivo = $tipo_arquivo;

	        return $this;
	    }

	    /**
	     * Gets the value of nome_arquivo.
	     *
	     * @return mixed
	     */
	    public function getNome_arquivo()
	    {
	        return $this->nome_arquivo;
	    }

	    /**
	     * Sets the value of nome_arquivo.
	     *
	     * @param mixed $nome_arquivo the nome_arquivo
	     *
	     * @return self
	     */
	    public function setNome_arquivo($nome_arquivo)
	    {
	        $this->nome_arquivo = $nome_arquivo;

	        return $this;
	    }

	    /**
	     * Gets the value of valido_dimensoes.
	     *
	     * @return mixed
	     */
	    public function getValido_dimensoes()
	    {
	        return $this->valido_dimensoes;
	    }

	    /**
	     * Sets the value of valido_dimensoes.
	     *
	     * @param mixed $valido_dimensoes the valido_dimensoes
	     *
	     * @return self
	     */
	    public function setValido_dimensoes($valido_dimensoes)
	    {
	        $this->valido_dimensoes = $valido_dimensoes;

	        return $this;
	    }

	    /**
	     * Gets the value of upload_multiplo.
	     *
	     * @return mixed
	     */
	    public function getUpload_multiplo()
	    {
	        return $this->upload_multiplo;
	    }

	    /**
	     * Sets the value of upload_multiplo.
	     *
	     * @param mixed $upload_multiplo the upload_multiplo
	     *
	     * @return self
	     */
	    public function setUpload_multiplo($upload_multiplo)
	    {
	        $this->upload_multiplo = $upload_multiplo;

	        return $this;
	    }

	    /**
	     * Gets the value of width.
	     *
	     * @return mixed
	     */
	    public function getWidth()
	    {
	        return $this->width;
	    }

	    /**
	     * Sets the value of width.
	     *
	     * @param mixed $width the width
	     *
	     * @return self
	     */
	    public function setWidth($width)
	    {
	        $this->width = $width;

	        return $this;
	    }

	    /**
	     * Gets the value of height.
	     *
	     * @return mixed
	     */
	    public function getHeight()
	    {
	        return $this->height;
	    }

	    /**
	     * Sets the value of height.
	     *
	     * @param mixed $height the height
	     *
	     * @return self
	     */
	    public function setHeight($height)
	    {
	        $this->height = $height;

	        return $this;
	    }

	    /**
	     * Gets the value of msg_erro.
	     *
	     * @return mixed
	     */
	    public function getMsg_erro()
	    {
	        return $this->msg_erro;
	    }

	    /**
	     * Sets the value of msg_erro.
	     *
	     * @param mixed $msg_erro the msg_erro
	     *
	     * @return self
	     */
	    public function setMsg_erro($msg_erro)
	    {
	        $this->msg_erro[] = $msg_erro;

	        return $this;
	    }

	    /**
	     * Gets the value of width_p.
	     *
	     * @return mixed
	     */
	    public function getWidth_p()
	    {
	        return $this->width_p;
	    }

	    /**
	     * Sets the value of width_p.
	     *
	     * @param mixed $width_p the width_p
	     *
	     * @return self
	     */
	    public function setWidth_p($width_p)
	    {
	        $this->width_p = $width_p;

	        return $this;
	    }

	    /**
	     * Gets the value of height_p.
	     *
	     * @return mixed
	     */
	    public function getHeight_p()
	    {
	        return $this->height_p;
	    }

	    /**
	     * Sets the value of height_p.
	     *
	     * @param mixed $height_p the height_p
	     *
	     * @return self
	     */
	    public function setHeight_p($height_p)
	    {
	        $this->height_p = $height_p;

	        return $this;
	    }

	    /**
	     * Gets the value of res_p.
	     *
	     * @return mixed
	     */
	    public function getRes_p()
	    {
	        return $this->res_p;
	    }

	    /**
	     * Sets the value of res_p.
	     *
	     * @param mixed $res_p the res_p
	     *
	     * @return self
	     */
	    public function setRes_p($res_p)
	    {
	        $this->res_p = $res_p;

	        return $this;
	    }

	    /**
	     * Gets the value of width_m.
	     *
	     * @return mixed
	     */
	    public function getWidth_m()
	    {
	        return $this->width_m;
	    }

	    /**
	     * Sets the value of width_m.
	     *
	     * @param mixed $width_m the width_m
	     *
	     * @return self
	     */
	    public function setWidth_m($width_m)
	    {
	        $this->width_m = $width_m;

	        return $this;
	    }

	    /**
	     * Gets the value of height_m.
	     *
	     * @return mixed
	     */
	    public function getHeight_m()
	    {
	        return $this->height_m;
	    }

	    /**
	     * Sets the value of height_m.
	     *
	     * @param mixed $height_m the height_m
	     *
	     * @return self
	     */
	    public function setHeight_m($height_m)
	    {
	        $this->height_m = $height_m;

	        return $this;
	    }

	    /**
	     * Gets the value of res_m.
	     *
	     * @return mixed
	     */
	    public function getRes_m()
	    {
	        return $this->res_m;
	    }

	    /**
	     * Sets the value of res_m.
	     *
	     * @param mixed $res_m the res_m
	     *
	     * @return self
	     */
	    public function setRes_m($res_m)
	    {
	        $this->res_m = $res_m;

	        return $this;
	    }

	    /**
	     * Gets the value of width_g.
	     *
	     * @return mixed
	     */
	    public function getWidth_g()
	    {
	        return $this->width_g;
	    }

	    /**
	     * Sets the value of width_g.
	     *
	     * @param mixed $width_g the width_g
	     *
	     * @return self
	     */
	    public function setWidth_g($width_g)
	    {
	        $this->width_g = $width_g;

	        return $this;
	    }

	    /**
	     * Gets the value of height_g.
	     *
	     * @return mixed
	     */
	    public function getHeight_g()
	    {
	        return $this->height_g;
	    }

	    /**
	     * Sets the value of height_g.
	     *
	     * @param mixed $height_g the height_g
	     *
	     * @return self
	     */
	    public function setHeight_g($height_g)
	    {
	        $this->height_g = $height_g;

	        return $this;
	    }

	    /**
	     * Gets the value of res_g.
	     *
	     * @return mixed
	     */
	    public function getRes_g()
	    {
	        return $this->res_g;
	    }

	    /**
	     * Sets the value of res_g.
	     *
	     * @param mixed $res_g the res_g
	     *
	     * @return self
	     */
	    public function setRes_g($res_g)
	    {
	        $this->res_g = $res_g;

	        return $this;
	    }

	    /**
	     * Gets the value of redimensiono.
	     *
	     * @return mixed
	     */
	    public function getRedimensiono()
	    {
	        return $this->redimensiono;
	    }

	    /**
	     * Sets the value of redimensiono.
	     *
	     * @param mixed $redimensiono the redimensiono
	     *
	     * @return self
	     */
	    public function setRedimensiono($redimensiono)
	    {
	        $this->redimensiono = $redimensiono;

	        return $this;
	    }

	    /**
	     * Gets the value of tamanho.
	     *
	     * @return mixed
	     */
	    public function getTamanho()
	    {
	        return (1024 * 1024) * ($this->tamanho);
	    }

	    /**
	     * Sets the value of tamanho.
	     *
	     * @param mixed $tamanho the tamanho
	     *
	     * @return self
	     */
	    public function setTamanho($tamanho)
	    {
	        $this->tamanho = $tamanho;

	        return $this;
	    }

	    /**
	     * Gets the value of nome_arquivo_array.
	     *
	     * @return mixed
	     */
	    public function getNome_arquivo_return()
	    {
	        return $this->nome_arquivo_return;
	    }

	    /**
	     * Sets the value of nome_arquivo_array.
	     *
	     * @param mixed $nome_arquivo_array the nome_arquivo_array
	     *
	     * @return self
	     */
	    public function setNome_arquivo_return($nome_arquivo_return)
	    {
	        $this->nome_arquivo_return[] = $nome_arquivo_return;

	        return $this;
	    }


	    //*********************************************************************************
		//VERIFICO SE ARQUIVO FOI ENVIADO
		//Retorno true ou false
		//*********************************************************************************
	    public function valido_file_empty($mult = null){

	    	//VALIDAÇÃO MULTIPLA ****************
	    	if($mult === true){
	    		$count = count($this->getArquivo('name'));
				for ($key=0; $key < $count ; $key++) {
		    		$arquivo = $this->getArquivo('name');
		    		$arquivo = $arquivo[$key];
		    	}
		    	if(empty($arquivo)){
	    			return true;
	    		}else{
	    			return false;
	    		}
	    	}

	    	//VALIDAÇÃO SIMPLES ****************
	    	if($mult != true){
	    		$arquivo = $this->getArquivo('name');
		    	if(empty($arquivo)){
	    			return true;
	    		}else{
	    			return false;
	    		}
	    	}
	    }


	    //*********************************************************************************
		//VERIFICO SE PASTA EXISTE
		//Crio pasta se não existir
		//*********************************************************************************
	    public function valido_pasta_upload(){
    		$pasta = $this->core->get_config('dir_files_comp')."/".$this->getPasta().'/';
 			if (!is_dir($pasta)){
				mkdir($pasta, 0777, true); //crio pasta
 			}
	    }


	    //*********************************************************************************
		//VALIDO OS ERROS
		//Seto erro em array
		//*********************************************************************************
	    public function valido_erros_arquivo($key = null,$mult = null){

	    	//VALIDAÇÃO MULTIPLA ****************
	    	if($mult === true){

	    		//VARIAVEIS
		    	$tamanho    = $this->getTamanho();
		    	$size       = $this->getArquivo('size');
		    	$name       = $this->getArquivo('name');
		    	$error_file = $this->getArquivo('error');
		    	$size       = $size[$key];
		    	$name       = $name[$key];
		    	$error_file = $error_file[$key];
		    	$tamanho_mb = $this->funcoes->formatBytes($tamanho,2,true);

		    	//VÁLIDO TAMANHO DO ARQUIVO
		    	if ($size > $tamanho){
		    		$return  = $this->core->get_msg_array('file_exceeded_up', "{$name},{$tamanho_mb}" );
					  $this->setMsg_erro($return);
		    	}
				//ERROS BÁSICOS
				switch ($error_file) {
					case 1: //Tamanho máximo arquivo
						$return  = $this->core->get_msg_array('file_exceeded_up', "{$name},{$tamanho_mb}" );
						$this->setMsg_erro($return);
						break;
					case 3: //Upload feito parcialmente
						$return  = $this->core->get_msg_array('file_error_up', $name);
						$this->setMsg_erro($return);
						break;
				}
	    	}

	    	//VALIDAÇÃO SIMPLES ****************
	    	if($mult != true){

	    		//VARIAVEIS
		    	$tamanho    = $this->getTamanho();
		    	$size       = $this->getArquivo('size');
		    	$name       = $this->getArquivo('name');
		    	$error_file = $this->getArquivo('error');
		    	$tamanho_mb = $this->funcoes->formatBytes($tamanho,2,true);

		    	//VÁLIDO TAMANHO DO ARQUIVO
		    	if ($size > $tamanho){
		    		$return  = $this->core->get_msg_array('file_exceeded_up', "{$name},{$tamanho_mb}" );
					$this->setMsg_erro($return);
		    	}
				//ERROS BÁSICOS
				switch ($error_file) {
					case 1: //Tamanho máximo arquivo
						$return  = $this->core->get_msg_array('file_exceeded_up', "{$name},{$tamanho_mb}" );
						$this->setMsg_erro($return);
						break;
					case 3: //Upload feito parcialmente
						$return  = $this->core->get_msg_array('file_error_up',$name);
						$this->setMsg_erro($return);
						break;
				}
	    	}
	    }


	    //*********************************************************************************
		//VALIDO TIPO DE ARQUIVO
		//Seto erro em array
		//*********************************************************************************
	    public function valido_tipo_arquivo($key = null,$mult = null){

	    	//VALIDAÇÃO MULTIPLA ****************
	    	if($mult === true){

	    		//DADOS
		    	$tipo_arquivo   = $this->getTipo_arquivo();
		    	$arquivo        = $this->getArquivo('type');
		    	$name           = $this->getArquivo('name');
		    	$arquivo        = $arquivo[$key];
		    	$name           = $name[$key];
		    	$pasta_original = $this->getPasta();

		    	//TIPOS
				switch ($tipo_arquivo) {
	 				case 'jpg':
	 					if ($arquivo == "image/jpeg" || $arquivo == "image/pjpeg"){
	 						$this->setPasta($pasta_original);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'gif':
	 					if ($arquivo == "image/gif"){
	 						$this->setPasta($pasta_original);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'png':
	 					if ($arquivo == "image/png"){
	 						$this->setPasta($pasta_original);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'doc':
	 					if ($arquivo == "application/msword" || $arquivo == "application/msword" || $arquivo == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
	 						$this->setPasta($pasta_original);
	 						$this->setValido_dimensoes(false);
	 						$this->setRedimensiono(false);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'xls':
	 					if ($arquivo == "application/excel" || $arquivo == "application/vnd.ms-excel" || $arquivo == "application/x-excel" || $arquivo == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
	 						$this->setPasta($pasta_original);
	 						$this->setValido_dimensoes(false);
	 						$this->setRedimensiono(false);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'pdf':
	 					if ($arquivo == "application/pdf"){
	 						$this->setPasta($pasta_original);
	 						$this->setValido_dimensoes(false);
	 						$this->setRedimensiono(false);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'todos_documentos': //pdf,doc e xls
	 					if ($arquivo_type == "application/msword" || $arquivo_type == "application/msword" || $arquivo_type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $arquivo_type == "application/pdf" || $arquivo_type == "application/excel" || $arquivo_type == "application/vnd.ms-excel" || $arquivo_type == "application/x-excel" || $arquivo_type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
	 						$this->setPasta($pasta_original);
	 						$this->setValido_dimensoes(false);
	 						$this->setRedimensiono(false);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'todas_imagens': //jpg, gif e png
	 					if ($arquivo_type == "image/png" || $arquivo_type == "image/gif" || $arquivo_type == "image/jpeg" || $arquivo_type == "image/pjpeg"){
	 						$this->setPasta($pasta_original);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'tudo': //tudo sem restrição
							$this->setPasta($pasta_original);
							$this->setValido_dimensoes(false);
							$this->setRedimensiono(false);
		 					$erro_formato = 0;
	 					break;
				}

				//FORMATO DE IMAGEM INVÁLIDO
				if($erro_formato == 1){
					$return  = $this->core->get_msg_array('file_block_up',$name);
					$this->setMsg_erro($return);
				}
	    	}

	    	//VALIDAÇÃO SIMPLES ****************
	    	if($mult != true){

	    		//DADOS
		    	$tipo_arquivo   = $this->getTipo_arquivo();
		    	$arquivo        = $this->getArquivo('type');
		    	$name           = $this->getArquivo('name');
		    	$pasta_original = $this->getPasta();

		    	//TIPOS
				switch ($tipo_arquivo) {
	 				case 'jpg':
	 					if ($arquivo == "image/jpeg" || $arquivo == "image/pjpeg"){
	 						$this->setPasta($pasta_original);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'gif':
	 					if ($arquivo == "image/gif"){
	 						$this->setPasta($pasta_original);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'png':
	 					if ($arquivo == "image/png"){
	 						$this->setPasta($pasta_original);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'doc':
	 					if ($arquivo == "application/msword" || $arquivo == "application/msword" || $arquivo == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
	 						$this->setPasta($pasta_original);
	 						$this->setValido_dimensoes(false);
	 						$this->setRedimensiono(false);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'xls':
	 					if ($arquivo == "application/excel" || $arquivo == "application/vnd.ms-excel" || $arquivo == "application/x-excel" || $arquivo == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
	 						$this->setPasta($pasta_original);
	 						$this->setValido_dimensoes(false);
	 						$this->setRedimensiono(false);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'pdf':
	 					if ($arquivo == "application/pdf"){
	 						$this->setPasta($pasta_original);
	 						$this->setValido_dimensoes(false);
	 						$this->setRedimensiono(false);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'todos_documentos': //pdf,doc e xls
	 					if ($arquivo_type == "application/msword" || $arquivo_type == "application/msword" || $arquivo_type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $arquivo_type == "application/pdf" || $arquivo_type == "application/excel" || $arquivo_type == "application/vnd.ms-excel" || $arquivo_type == "application/x-excel" || $arquivo_type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
	 						$this->setPasta($pasta_original);
	 						$this->setValido_dimensoes(false);
	 						$this->setRedimensiono(false);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'todas_imagens': //jpg, gif e png
	 					if ($arquivo_type == "image/png" || $arquivo_type == "image/gif" || $arquivo_type == "image/jpeg" || $arquivo_type == "image/pjpeg"){
	 						$this->setPasta($pasta_original);
			 				$erro_formato = 0;
			 			}else{
			 				$erro_formato = 1;
			 			}
	 					break;
	 				case 'tudo': //tudo sem restrição
							$this->setPasta($pasta_original);
							$this->setValido_dimensoes(false);
							$this->setRedimensiono(false);
		 					$erro_formato = 0;
	 					break;
				}

				//FORMATO DE IMAGEM INVÁLIDO
				if($erro_formato == 1){
					$return  = $this->core->get_msg_array('file_block_up',$name);
					$this->setMsg_erro($return);
				}
	    	}
	    }


	    //*********************************************************************************
		//VÁLIDO DIMENSÕES DE IMAGEM
		//Seto erro em array
		//*********************************************************************************
	    public function valido_dimensoes_img($key = null,$mult = null){

	    	//VALIDAÇÃO MULTIPLA ****************
	    	if($mult === true){

	    		//VARIAVEIS
		    	$valido_dimensoes = $this->getValido_dimensoes();
		    	$arquivo_temp     = $this->getArquivo('tmp_name');
		    	$arquivo_name     = $this->getArquivo('name');
		    	$arquivo_temp     = $arquivo_temp[$key];
		    	$arquivo_name     = $arquivo_name[$key];
		    	$width_n          = $this->getWidth();
		    	$height_n         = $this->getHeight();

	    		//VALIDO AS DIMENSÕES DA IMAGEM
	 			if($valido_dimensoes === true){
		 			@list($width, $height, $type, $attr) = getimagesize($arquivo_temp);
		 			$pers = 0;
		 			//SE WIDTH FOR ZERO - TRAVO HEIGHT
		 			if($width_n === 0 && $height_n != 0){
		 				if ($height != $height_n){
		 					$return  = $this->core->get_msg_array('img_heigth_incorrect_up', "{$arquivo_name},{$height_n}" );
							$this->setMsg_erro($return);
						}
						$pers = 1;
		 			}
		 			//SE HEIGHT FOR ZERO - TRAVO WIDTH
		 			if($height_n === 0 && $width_n != 0){
		 				if ($width != $width_n){
		 					$return  = $this->core->get_msg_array('img_width_incorrect_up', "{$arquivo_name},{$width_n}" );
							$this->setMsg_erro($return);
						}
						$pers = 1;
		 			}
	 				//TRAVO DIMENSOES
	 				if($pers === 0){
	 					if ($width != $width_n || $height != $height_n){
							$return  = $this->core->get_msg_array('img_dimen_incorrect_up', "{$arquivo_name},{$width_n},{$height_n}" );
							$this->setMsg_erro($return);
						}
	 				}
				}
	    	}

	    	//VALIDAÇÃO SIMPLES ****************
	    	if($mult != true){

	    		//VARIAVEIS
		    	$valido_dimensoes = $this->getValido_dimensoes();
		    	$arquivo_temp     = $this->getArquivo('tmp_name');
		    	$arquivo_name     = $this->getArquivo('name');
		    	$width_n          = $this->getWidth();
		    	$height_n         = $this->getHeight();

	    		//VALIDO AS DIMENSÕES DA IMAGEM
	 			if($valido_dimensoes === true){
		 			@list($width, $height, $type, $attr) = getimagesize($arquivo_temp);
		 			$pers = 0;
		 			//SE WIDTH FOR ZERO - TRAVO HEIGHT
		 			if($width_n === 0 && $height_n != 0){
		 				if ($height != $height_n){
		 					$return  = $this->core->get_msg_array('img_heigth_incorrect_up', "{$arquivo_name},{$height_n}" );
							$this->setMsg_erro($return);
						}
						$pers = 1;
		 			}
		 			//SE HEIGHT FOR ZERO - TRAVO WIDTH
		 			if($height_n === 0 && $width_n != 0){
		 				if ($width != $width_n){
		 					$return  = $this->core->get_msg_array('img_width_incorrect_up', "{$arquivo_name},{$width_n}" );
							$this->setMsg_erro($return);
						}
						$pers = 1;
		 			}
	 				//TRAVO DIMENSOES
	 				if($pers === 0){
	 					if ($width != $width_n || $height != $height_n){
							$return  = $this->core->get_msg_array('img_dimen_incorrect_up', "{$arquivo_name},{$width_n},{$height_n}" );
							$this->setMsg_erro($return);
						}
	 				}
				}
	    	}
	    }


	    //*********************************************************************************
		//MONTO O NOME DO ARQUIVO
		//Seto o novo nome em nome_arquivo
		//*********************************************************************************
	    public function monto_nome_arquivo($key = null,$mult = null){

	    	//VALIDAÇÃO MULTIPLA ****************
	    	if($mult === true){

	    		//VARIAVEIS
	    		$novo_nome    = null;
		    	$arquivo_name = $this->getArquivo('name');
		    	$nome_arquivo = $this->getNome_arquivo();
		    	$arquivo_name = $arquivo_name[$key];
		    	$nome_arquivo = $nome_arquivo[$key];
		    	if(!empty($nome_arquivo[$key])){ $nome_arquivo[$key] = $nome_arquivo[$key].'_'; } //Coloco _ se tiver nome o arquivo
	 			$extensao      = strtolower(end(explode('.', $arquivo_name))); //Pego a extensão do arquivo
	 			$num_aleatorio = rand(1,9999); //Número Aleatório
	 			$novo_nome     = $nome_arquivo.md5($arquivo_name.$num_aleatorio).'.'.$extensao; //gera um nome unico para a imagem

	 			//NOME DO ARQUIVO
	 			$this->setNome_arquivo($novo_nome);
	    	}

	    	//VALIDAÇÃO SIMPLES ****************
	    	if($mult != true){

	    		//VARIAVEIS
		    	$arquivo_name = $this->getArquivo('name');
		    	$nome_arquivo = $this->getNome_arquivo();
		    	if(!empty($nome_arquivo)){ $nome_arquivo = $nome_arquivo.'_'; } //Coloco _ se tiver nome o arquivo
	 			$extensao      = strtolower(end(explode('.', $arquivo_name))); //Pego a extensão do arquivo
	 			$num_aleatorio = rand(1,9999); //Número Aleatório
	 			$novo_nome     = $nome_arquivo.md5($arquivo_name.$num_aleatorio).'.'.$extensao; //gera um nome unico para a imagem

	 			//NOME DO ARQUIVO
	 			$this->setNome_arquivo($novo_nome);
	    	}
	    }


	    //*********************************************************************************
		//VÁLIDO UPLOAD
		//Seto mensagem de erro em array
		//*********************************************************************************
	    public function valido_upload($mult = null){

	    	//VALIDAÇÃO MULTIPLA ****************
	    	if($mult === true){
	    	    $count = count($this->getArquivo('name')); //PERCORRO ARRAY PARA VALIDAÇÃO
				for ($key=0; $key < $count ; $key++) {
			     	$this->valido_erros_arquivo($key,true); //Válido erros
			     	$this->valido_tipo_arquivo($key,true);  //Tipo de Arquivo
			    	$this->valido_dimensoes_img($key,true); //Dimensões imagem
			    }
	    	}

	    	//VALIDAÇÃO SIMPLES ****************
	    	if($mult != true){
    			$this->valido_pasta_upload();  //Pasta
		    	$this->valido_erros_arquivo(); //Válido erros
		    	$this->valido_tipo_arquivo();  //Tipo de Arquivo
		    	$this->valido_dimensoes_img(); //Dimensões imagem
	    	}
	    }


	    //*********************************************************************************
		//UPLOAD DO ARQUIVO SIMPLES
		//Seto nome dos arquivos enviados em array
		//*********************************************************************************
	    public function upload_file(){

	    	//ARQUIVO ENVIADO
	    	if($this->valido_file_empty() === false){

	    		//VARIAVEIS
	    		$count           = count($this->getArquivo('name'));
	    		$upload_multiplo = $this->getUpload_multiplo();

	    		//VÁLIDO TENTATIVA DE ENVIAR MAIS DE 1 ARQUIVO
	    		if($count > 1 && $upload_multiplo === false){
	    			$return  = $this->core->get_msg_array('error_limit_up');
					$this->setMsg_erro($return);
	    		}

	    		//UPLOAD SIMPLES
 				if($count === 1 && $upload_multiplo === false){

		    		//VALIDAÇÕES
			    	$this->valido_upload();

			    	//MONTO DIRETÓRIOS PARA UPLOAD
			    	$this->monto_nome_arquivo(); //Nome Arquivo
			    	$pasta        = $this->getPasta(); //Monto pasta
			    	$destino      = $this->getPasta_files()."/".$pasta.'/'.$this->getNome_arquivo(); //Diretório com arquivo
			    	$arquivo_temp = $this->getArquivo('tmp_name'); //Arquivo na pasta temp do servidor

			    	//VERIFICO SE NÃO TEM NENHUM ERRO
			    	$total_erros = count($this->getMsg_erro());
			    	if($total_erros === 0){

						//UPLOAD DO ARQUIVO
						if (move_uploaded_file($arquivo_temp, $destino) == true){

							//REDIMENSIONO IMAGENS
							$redimensiono = $this->getRedimensiono();
							if($redimensiono === true){
								$this->redimensiona_imagens();
							}

							//SETO NOME DO ARQUIVO ARRAY
							$this->setNome_arquivo_return($this->getNome_arquivo());

						}else{
							$arquivoo = $this->getArquivo('name');
							$return  = $this->core->get_msg_array('error_upload_file_paste', "{$arquivoo},{$destino}" );
							$this->setMsg_erro($return);
							return false; //Erro no upload
						}
					}

				}else{
					$this->upload_files(); //UPLOAD MULTIPLO
				}
	    	}
	    }


	    //*********************************************************************************
		//UPLOAD DOS ARQUIVOS MULTIPLO
		//Seto nome dos arquivos enviados em array
		//*********************************************************************************
	    public function upload_files(){

	    	//ARQUIVO ENVIADO
	    	if($this->valido_file_empty(true) === false){

	    		//VARIAVEIS ESTATICAS
	    		$pasta   = $this->getPasta(); //Monto pasta
	    		$this->valido_pasta_upload(); //Pasta

	    		//VALIDAÇÕES
			    $this->valido_upload(true);

			   	//VERIFICO SE NÃO TEM NENHUM ERRO
			    $total_erros = count($this->getMsg_erro());

			    //PERCORRO O ARRAY FAÇO O UPLOAD
			    $count = count($this->getArquivo('name')); //PERCORRO ARRAY PARA VALIDAÇÃO
			    if($total_erros === 0){
			   		for ($key=0; $key < $count ; $key++) {

			   			//MONTO DIRETÓRIOS PARA UPLOAD
				    	$this->monto_nome_arquivo($key,true); //Nome Arquivo
				    	$nome_arquivo = $this->getNome_arquivo();
				    	$destino      = $this->getPasta_files()."/".$pasta."/".$nome_arquivo; //Diretório com arquivo
				    	$arquivo_temp = $this->getArquivo('tmp_name'); //Arquivo na pasta temp do servidor
				    	$arquivo_temp = $arquivo_temp[$key]; //Arquivo na pasta temp do servidor

						//UPLOAD DO ARQUIVO
						if (move_uploaded_file($arquivo_temp, $destino) == true){

							//REDIMENSIONO IMAGENS
							$redimensiono = $this->getRedimensiono();
							if($redimensiono === true){
								$this->redimensiona_imagens($key,true);
							}

							//SETO NOMES DOS ARQUIVOS NO ARRAY
							$this->setNome_arquivo_return($nome_arquivo);

						}else{
							$arquivoo = $this->getArquivo('name');
							$return  = $this->core->get_msg_array('error_upload_file_paste', "{$arquivoo},{$destino}" );
							$this->setMsg_erro($return);
						}
					}
				}
			}
		}


	    //*********************************************************************************
		//REDIMENSIONO IMAGENS
		//Redimensiono imagens com 3 opções de tamanho pequeno, médio e grande
		//*********************************************************************************
		public function redimensiona_imagens(){

			//INCLUDES
			$this->core->includeInc('WideImage/WideImage.php');

    	    //VARIAVEIS
			$width_p     = $this->getWidth_p();
			$height_p    = $this->getHeight_p();
			$res_p       = $this->getRes_p();
			$width_m     = $this->getWidth_m();
			$height_m    = $this->getHeight_m();
			$res_m       = $this->getRes_m();
			$width_g     = $this->getWidth_g();
			$height_g    = $this->getHeight_g();
			$res_g       = $this->getRes_g();
			$pasta       = $this->core->get_config('dir_files_comp')."/".$this->getPasta().'/';
			$link_imagem = $this->getNome_arquivo();
			$img_padrao  = $pasta.$this->getNome_arquivo(); //Diretório com arquivo

 			if(!empty($width_p) && !empty($height_p) && !empty($res_p)){ //Verifico imagens pequenas
 				$imagem_p = true;
 				if (!is_dir($pasta.'p')){ //Verifico se pasta existe
 					mkdir($pasta.'p', 0777, true); //crio pasta
 				}
 			}
 			if(!empty($width_m) && !empty($height_m) && !empty($res_m)){ //Verifico imagens médias
 				$imagem_m = true;
 				if (!is_dir($pasta.'m')){ //Verifico se pasta existe
 					mkdir($pasta.'m', 0777, true); //crio pasta
 				}
 			}
 			if(!empty($width_g) && !empty($height_g) && !empty($res_g)){ //Verifico imagens grandes
 				$imagem_g = true;
 				if (!is_dir($pasta.'g')){ //Verifico se pasta existe
 					mkdir($pasta.'g', 0777, true); //crio pasta
 				}
 			}

			//REDIMENSIONO IMAGENS P
			if($imagem_p == true){
				$img_p = WideImage::loadFromFile($img_padrao); //pego imagem da pasta
				$img_p = $img_p->resize($width_p, $height_p, 'outside'); //redimensiono imagem
				$img_p->saveToFile(''.$pasta.'p/'.$link_imagem.'',$res_p); //salvo nova imagem
			}
			//REDIMENSIONO IMAGENS M
			if($imagem_m == true){
				$img_m = WideImage::loadFromFile($img_padrao); //pego imagem da pasta
				$img_m = $img_m->resize($width_m, $height_m, 'outside'); //redimensiono imagem
				$img_m->saveToFile(''.$pasta.'m/'.$link_imagem.'',$res_m); //salvo nova imagem
			}
			//REDIMENSIONO IMAGENS G
			if($imagem_g == true){
				$img_g = WideImage::loadFromFile($img_padrao);  //pego imagem da pasta
				$img_g = $img_g->resize($width_g, $height_g, 'outside'); //redimensiono imagem
				$img_g->saveToFile(''.$pasta.'g/'.$link_imagem.'',$res_g); //salvo nova imagem
			}

			//DELETA IMAGEM DA PASTA TEMP
			unlink($pasta.'/'.$link_imagem);
    	}



		//*********************************************************************************
		//CROP DE IMAGENS
		//Crop de uma imagem
		//*********************************************************************************
		public function crop_imagem($imagem_o,$imagem_f,$x,$y,$w,$h){

			//INCLUDES
			$this->core->includeInc('WideImage/WideImage.php');

			//CROP
	        $img_crop = WideImage::loadFromFile($this->core->get_config('dir_files_comp').$imagem_o); //pego imagem da pasta
	        $img_crop = $img_crop->crop($x, $y, $w, $h); //corto imagem
	        $img_crop->saveToFile($this->core->get_config('dir_files_comp').$imagem_f, 90); //salvo nova imagem
		}



		//*********************************************************************************
		//RETORNO WIDTH IMAGEM
		//*********************************************************************************
		public function return_width_img($pasta){
			$imnfo = getimagesize($this->core->get_config('dir_files_comp').$pasta); //Pego dimensões da imagem
			return $imnfo[0]; //width da imagem
		}




	}
