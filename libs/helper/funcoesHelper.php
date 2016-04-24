<?php

	/**
	* Funções gerais do sistema
	*
	* @author Fernando
	* @version 2.0.0
	**/

	//=================================================================
	//INCLUO ESTRUTURA
	require_once (dirname(dirname((dirname(__FILE__))))).'/libs/core/core.php';
	//=================================================================

	//Classe
	class funcoes {

	    //Variaveis
		public $_data        = array();
		public $_dados       = array();
	    protected $_errors   = array();
	    protected $_pattern  = array();
	    protected $_messages = array();
		public $_array       = array();
		private $core;

	    //Construtor
	    public function __construct() {
			$this->core = new core();
	        $this->define_pattern();
	    }

//***************************************************************************************************************************************************************
//FUNÇÕES DO CORE DO SISTEMA
//@author Fernando Zueet
//***************************************************************************************************************************************************************

	    //Pego valor de array
	    public function get_array(){
			$array = ($this->_array);
			unset($this->_array);
	        return $array;
	    }

	    //Seto valor de array
	    public function set_array($name, $name2, $value){
			if(!isset($name)){
				$this->_array[$name2] = $value;
			}else{
				$this->_array[$name][$name2] = $value;
			}
			return $this;
	    }


	    //Verifico o tamanho do array com as mensagens do sistema
	    public function get_number_validators_methods(){
	        return count($this->_messages);
	    }

	    //Seto mensagem do sistema no array de mensagens
	    public function set_message($name, $value){
	        if (array_key_exists($name, $this->_messages)){
	            $this->_messages[$name] = $value;
	        }
	    }
	    //Pego a mensagem do array de mensagens
	    public function get_messages($param = false){
	        if ($param){
	            return $this->_messages[$param];
	        }
	        return $this->_messages;
	    }

	    //Retorno mensagem do sistema (parametro)
	    public function get_msg_error($param,$array){
	    	return vsprintf($this->_messages[$param],$array);
	    }





//***************************************************************************************************************************************************************
//FUNÇÕES DE VALIDAÇÃO DE FORMULÁRIO
//@author Rafael Wendel Pinheiro - Alterado por Fernando Zueet
//https://github.com/rafaelwendel/DataValidator
//***************************************************************************************************************************************************************

	    //Seto dados para validação
	    public function set($descricao, $name, $value){
	        $this->_data['name']      = $name;
	        $this->_data['value']     = $value;
	        $this->_data['descricao'] = $descricao;
	        return $this;
	    }

	    //Define the pattern of name of error messages
	    public function define_pattern($prefix = '', $sufix = ''){
	        $this->_pattern['prefix'] = $prefix;
	        $this->_pattern['sufix']  = $sufix;
	    }

	    //Set a error of the invalid data
	    protected function set_error($error){
	        $this->_errors[$this->_pattern['prefix'] . $this->_data['name'] . $this->_pattern['sufix']][] = $error;
	    }

	    //Validate the data
	    public function validate(){
	        return (count($this->_errors) > 0 ? false : true);
	    }

	    //The messages of invalid data
	    public function get_errors($param = false){
	        if ($param){
	            if(isset($this->_errors[$this->_pattern['prefix'] . $param . $this->_pattern['sufix']])){
	                return $this->_errors[$this->_pattern['prefix'] . $param . $this->_pattern['sufix']];
	            }
	            else{
	                return false;
	            }
	        }
	        return $this->_errors;
	    }

	    //Verifico se campo esta ou não preenchido
	    public function is_required_test(){
	    	if (trim($this->_data['value']) === ""){
	            return false;
	        }else{
	        	return true;
	        }
	    }

	    //Verifico se tem Erro em campo e já retorno o erro montado em 1 comando só
	    public function get_errors_inline($param){
	    	$erro        = null;
	    	$total_erros = count($param);
	    	if($total_erros >= 1){
	    		for ($i=0; $i < count($param) ; $i++) {
				  $erro .= $param[$i]."<br />";
				}
				return $erro;
	    	}
	    }

	    //is_required
	    public function is_required(){
	        if (trim($this->_data['value']) === ""){
	            $this->set_error($this->core->get_msg_array('is_required', $this->_data['descricao']));
	        }
	        return $this;
	    }

	    //min_length
	    public function min_length($length, $inclusive = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = ($inclusive === true ? strlen($this->_data['value']) >= $length : strlen($this->_data['value']) > $length);
		        if (!$verify){
		            $this->set_error($this->core->get_msg_array('min_length', "{$this->_data['descricao']},{$length}"));
		        }
	        }
            return $this;
	    }

	    //max_length
	    public function max_length($length, $inclusive = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = ($inclusive === true ? strlen($this->_data['value']) <= $length : strlen($this->_data['value']) < $length);
		        if (!$verify){
								$this->set_error($this->core->get_msg_array('max_length', "{$this->_data['descricao']},{$length}"));
		        }
	    	}
	        return $this;
	    }

	    //between_length
	    public function between_length($min, $max){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if(strlen($this->_data['value']) < $min || strlen($this->_data['value']) > $max){
								$this->set_error($this->core->get_msg_array('between_length', "{$this->_data['descricao']},{$min},{$max}"));
		        }
	    	}
	        return $this;
	    }

	    //min_value
	    public function min_value($value, $inclusive = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = ($inclusive === true ? !is_numeric($this->_data['value']) || $this->_data['value'] >= $value : !is_numeric($this->_data['value']) || $this->_data['value'] > $value);
		        if (!$verify){
					$this->set_error($this->core->get_msg_array('min_value', "{$this->_data['descricao']},{$value}"));
		        }
	    	}
	        return $this;
	    }

	    //max_value
	    public function max_value($value, $inclusive = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = ($inclusive === true ? !is_numeric($this->_data['value']) || $this->_data['value'] <= $value : !is_numeric($this->_data['value']) || $this->_data['value'] < $value);
		        if (!$verify){
								$this->set_error($this->core->get_msg_array('max_value', "{$this->_data['descricao']},{$value}"));
		        }
	    	}
	        return $this;
	    }

	    //between_values
	    public function between_values($min_value, $max_value){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if(!is_numeric($this->_data['value']) || (($this->_data['value'] < $min_value || $this->_data['value'] > $max_value ))){
								$this->set_error($this->core->get_msg_array('between_values', "{$this->_data['descricao']},{$min_value},{$max_value}"));
		        }
	    	}
	        return $this;
	    }

	    //is_email
	    public function is_email($param = null){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if (filter_var($this->_data['value'], FILTER_VALIDATE_EMAIL) === false) {
								$this->set_error($this->core->get_msg_array('is_email', $this->_data['value']));
		        }else{
		        	if(!empty($param)){
		        		if($this->valido_email_serv($this->_data['value']) === false){
									$this->set_error($this->core->get_msg_array('is_email', $this->_data['value']));
		        		}
		        	}
		        }
	    	}
	    	return $this;
	    }

	    //is_url
	    public function is_url(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if (filter_var($this->_data['value'], FILTER_VALIDATE_URL) === false) {
								$this->set_error($this->core->get_msg_array('is_url', $this->_data['value']));
		        }
	    	}
	        return $this;
	    }

	    //is_slug
	    public function is_slug(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = true;

		        if (strstr($input, '--')) {
		            $verify = false;
		        }
		        if (!preg_match('@^[0-9a-z\-]+$@', $input)) {
		            $verify = false;
		        }
		        if (preg_match('@^-|-$@', $input)){
		            $verify = false;
		        }
		        if(!$verify){
								$this->set_error($this->core->get_msg_array('is_slug', $this->_data['value']));
		        }
	    	}
	        return $this;
	    }

	    //is_num
	    public function is_num(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if (!is_numeric($this->_data['value'])){
								$this->set_error($this->core->get_msg_array('is_num', $this->_data['value']));
		        }
	    	}
	        return $this;
	    }

	    //is_integer
	    public function is_integer(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if (!is_numeric($this->_data['value']) && (int) $this->_data['value'] != $this->_data['value']){
								$this->set_error($this->core->get_msg_array('is_integer', $this->_data['value']));
		        }
	    	}
	        return $this;
	    }

	    //is_float
	    public function is_float(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if (!is_float(filter_var($this->_data['value'], FILTER_VALIDATE_FLOAT))){
								$this->set_error($this->core->get_msg_array('is_float', $this->_data['value']));
		        }
	    	}
	        return $this;
	    }

	    //is_string
	    public function is_string(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if(!is_string($this->_data['value'])){
								$this->set_error($this->core->get_msg_array('is_string', $this->_data['value']));
		        }
	    	}
	        return $this;
	    }

	    //is_boolean
	    public function is_boolean(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if(!is_bool($this->_data['value'])){
								$this->set_error($this->core->get_msg_array('is_boolean', $this->_data['value']));
		        }
	    	}
	        return $this;
	    }

	    //is_obj
	    public function is_obj(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if(!is_object($this->_data['value'])){
								$this->set_error($this->core->get_msg_array('is_obj', $this->_data['name']));
		        }
	    	}
	        return $this;
	    }

	    //is_instance_of
	    public function is_instance_of($class){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if(!($this->_data['value'] instanceof $class)){
								$this->set_error($this->core->get_msg_array('is_instance_of', "{$this->_data['name']},{$class}"));
		        }
	    	}
	        return $this;
	    }

	    //is_arr
	    public function is_arr(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if(!is_array($this->_data['value'])){
								$this->set_error($this->core->get_msg_array('is_arr', $this->_data['descricao']));
		        }
	    	}
	        return $this;
	    }

	    //is_directory
	    public function is_directory(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = is_string($this->_data['value']) && is_dir($this->_data['value']);
		        if(!$verify){
								$this->set_error($this->core->get_msg_array('is_directory', $this->_data['value']));
		        }
	    	}
	        return $this;
	    }

	    //is_equals
	    public function is_equals($value, $identical = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = ($identical === true ? $this->_data['value'] === $value : strtolower($this->_data['value']) == strtolower($value));
		        if(!$verify){
								$this->set_error($this->core->get_msg_array('is_equals', "{$this->_data['descricao']},{$value}"));
		        }
	    	}
	        return $this;
	    }

	    //is_not_equals
	    public function is_not_equals($value, $identical = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = ($identical === true ? $this->_data['value'] !== $value : strtolower($this->_data['value']) != strtolower($value));
		        if(!$verify){
								$this->set_error($this->core->get_msg_array('is_not_equals', "{$this->_data['descricao']},{$value}"));
		        }
	    	}
	        return $this;
	    }

	    //is_cpf
	    public function is_cpf(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = true;

		        $c = preg_replace('/\D/', '', $this->_data['value']);

		        if (strlen($c) != 11)
		            $verify = false;

		        if (preg_match("/^{$c[0]}{11}$/", $c))
		            $verify = false;

		        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

		        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n))
		            $verify = false;

		        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

		        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n))
		            $verify = false;

		        if(!$verify){
								$this->set_error($this->core->get_msg_array('is_cpf', $this->_data['value']));
		        }
	        }
	        return $this;
	    }

	    //is_cnpj
	    public function is_cnpj(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = true;

		        $c = preg_replace('/\D/', '', $this->_data['value']);
		        $b = array(6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);

		        if (strlen($c) != 14)
		            $verify = false;
		        for ($i = 0, $n = 0; $i < 12; $n += $c[$i] * $b[++$i]);

		        if ($c[12] != ((($n %= 11) < 2) ? 0 : 11 - $n))
		            $verify = false;

		        for ($i = 0, $n = 0; $i <= 12; $n += $c[$i] * $b[$i++]);

		        if ($c[13] != ((($n %= 11) < 2) ? 0 : 11 - $n))
		            $verify = false;

		        if(!$verify){
								$this->set_error($this->core->get_msg_array('is_cnpj', $this->_data['value']));
		        }
	        }
	        return $this;
	    }

	    //contains
	    public function contains($values, $separator = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if(!is_array($values)){
		            if(!$separator || is_null($values)){
		                $values = array();
		            }
		            else{
		                $values = explode($separator, $values);
		            }
		        }

		        if(!in_array($this->_data['value'], $values)){
		            $valuess = implode(', ', $values);
								$this->set_error($this->core->get_msg_array('contains', "{$this->_data['descricao']},{$valuess}"));
		        }
	    	}
	        return $this;
	    }

	    //not_contains
	    public function not_contains($values, $separator = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if(!is_array($values)){
		            if(!$separator || is_null($values)){
		                $values = array();
		            }
		            else{
		                $values = explode($separator, $values);
		            }
		        }

		        if(in_array($this->_data['value'], $values)){
								$valuess = implode(', ', $values);
								$this->set_error($this->core->get_msg_array('not_contains', "{$this->_data['descricao']},{$valuess}"));
		        }
	    	}
	        return $this;
	    }

	    //is_lowercase
	    public function is_lowercase(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if($this->_data['value'] !== mb_strtolower($this->_data['value'], mb_detect_encoding($this->_data['value']))){
								$this->set_error($this->core->get_msg_array('is_lowercase', $this->_data['descricao']));
		        }
	    	}
	        return $this;
	    }

	    //is_uppercase
	    public function is_uppercase(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if($this->_data['value'] !== mb_strtoupper($this->_data['value'], mb_detect_encoding($this->_data['value']))){
								$this->set_error($this->core->get_msg_array('is_uppercase', $this->_data['descricao']));
		        }
	    	}
	        return $this;
	    }

	    //is_multiple
	    public function is_multiple($value){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        if($value == 0){
		            $verify = ($this->_data['value'] == 0);
		        }
		        else{
		            $verify = ($this->_data['value'] % $value == 0);
		        }
		        if(!$verify){
								$this->set_error($this->core->get_msg_array('is_multiple', "{$this->_data['value']},{$values}"));
		        }
	    	}
	        return $this;
	    }

	    //is_positive
	    public function is_positive($inclusive = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = ($inclusive === true ? ($this->_data['value'] >= 0) : ($this->_data['value'] > 0));
		        if(!$verify){
								$this->set_error($this->core->get_msg_array('is_positive', $this->_data['descricao']));
		        }
	    	}
	        return $this;
	    }

	    //is_negative
	    public function is_negative($inclusive = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = ($inclusive === true ? ($this->_data['value'] <= 0) : ($this->_data['value'] < 0));
		        if(!$verify){
								$this->set_error($this->core->get_msg_array('is_negative', $this->_data['descricao']));
		        }
	    	}
	        return $this;
	    }

	    //is_date
	    public function is_date($format = null){
	    	if($this->is_required_test() === true){ //Campo preenchido
				$data = explode("/", $this->_data['value']);
				if(!checkdate($data[1], $data[0], $data[2])){
					$this->set_error($this->core->get_msg_array('is_date', $this->_data['value']));
				}
	    	}
	        return $this;
	    }

			//is maioridade
			public function is_maioridade($idade_a){
				if($this->is_required_test() === true){ //Campo preenchido
						$data_atual  = date(Y);
						$data_antiga = substr($this->_data['value'], -4);
						$idade = $data_atual - $data_antiga;
						if($idade < $idade_a){
								$this->set_error($this->core->get_msg_array('is_maioridade', $idade_a));
						}
				}
				return $this;
			}

	    //generic_alpha_num
	    protected function generic_alpha_num($string_format, $additional = ''){
	        $this->_data['value'] = (string) $this->_data['value'];
	        $clean_input = str_replace(str_split($additional), '', $this->_data['value']);
	        return ($clean_input !== $this->_data['value'] && $clean_input === '') || preg_match($string_format, $clean_input);
	    }

	    //is_alpha
	    public function is_alpha($additional = ''){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $string_format = '/^(\s|[a-zA-Z])*$/';
		        if(!$this->generic_alpha_num($string_format, $additional)){
								$this->set_error($this->core->get_msg_array('is_alpha', $this->_data['descricao']));
		        }
	    	}
	        return $this;
	    }

	    //is_alpha_num
	    public function is_alpha_num($additional = ''){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $string_format = '/^(\s|[a-zA-Z0-9])*$/';
		        if(!$this->generic_alpha_num($string_format, $additional)){
								$this->set_error($this->core->get_msg_array('is_alpha_num', $this->_data['descricao']));
		        }
	    	}
	        return $this;
	    }

	    //no_whitespaces
	    public function no_whitespaces(){
	    	if($this->is_required_test() === true){ //Campo preenchido
		        $verify = is_null($this->_data['value']) || preg_match('#^\S+$#', $this->_data['value']);
		        if(!$verify){
								$this->set_error($this->core->get_msg_array('no_whitespaces', $this->_data['descricao']));
		        }
	    	}
	        return $this;
	    }

		//is_time_hm
	    public function is_time_hm($param = null){
	    	if($this->is_required_test() === true){ //Campo preenchido
	    		if($param != "zero_ok"){
		    		if($this->_data['value'] === "00:00"){
							$this->set_error($this->core->get_msg_array('is_time_hm', $this->_data['value']));
		    		}
	    		}
		    	if(!ereg("^([0-1][0-9]|[2][0-3]):[0-5][0-9]$", $this->_data['value'])){
					 $this->set_error($this->core->get_msg_array('is_time_hm', $this->_data['value']));
				}
			}
			return $this;
	    }

		//is_time_hms
	    public function is_time_hms($param = null){
	    	if($this->is_required_test() === true){ //Campo preenchido
	    		if($param != "zero_ok"){
	    			if($this->_data['value'] === "00:00:00"){
							$this->set_error($this->core->get_msg_array('is_time_hms', $this->_data['value']));
		    		}
	    		}
		    	if(!ereg("^([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])[\:]([0-5][0-9])$", $this->_data['value'])){
					 $this->set_error($this->core->get_msg_array('is_time_hms', $this->_data['value']));
				}
			}
			return $this;
	    }

	    //is_password_num_let
	    public function is_password_num_let(){
	    	if($this->is_required_test() === true){ //Campo preenchido
	    		if (!preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z]).*$/", $this->_data['value'])) {
						 $this->set_error($this->core->get_msg_array('is_password_num_let', $this->_data['value']));
	    		}
	    	}
			return $this;
	    }

	    //is_IP
	    public function is_IP(){
	    	if($this->is_required_test() === true){ //Campo preenchido
	    		if (!eregi("^([0-9]){1,3}.([0-9]){1,3}.([0-9]){1,3}.([0-9]){1,3}$", $this->_data['value'])) {
						 $this->set_error($this->core->get_msg_array('is_IP', $this->_data['value']));
	    		}
	    	}
			return $this;
	    }

	    //is_telefone
	    public function is_telefone($inclusive = false){
	    	if($this->is_required_test() === true){ //Campo preenchido
	    		$telefone_limpo = $this->substituo_strings(' ','',$this->_data['value']);
	    		$telefone_limpo = $this->substituo_strings('(','',$telefone_limpo);
	    		$telefone_limpo = $this->substituo_strings(')','',$telefone_limpo);
	    		$telefone_limpo = $this->substituo_strings('-','',$telefone_limpo);
	    		$max    = 0;
	    		$min    = 0;
	    		$numero = 0;
		        if (strlen($telefone_limpo) < 10){
		        	$min = 1;
		        }
	    		if (strlen($telefone_limpo) > 11){
		        	$max = 1;
		        }
			    if(!is_numeric($telefone_limpo)){
			    	$numero = 1;
			    }
		        if( ($min === 1) || ($max === 1) || ($numero === 1)){
							$this->set_error($this->core->get_msg_array('is_telefone', $this->_data['value']));
		        }
	    	}
			return $this;
	    }

		//is_CEP
	    public function is_CEP($correios = null){
	    	if($this->is_required_test() === true){ //Campo preenchido
	    		if (!eregi("^[0-9]{5}-[0-9]{3}$", $this->_data['value'])) {
						 $this->set_error($this->core->get_msg_array('is_CEP', $this->_data['value']));
	    		}
	    		if($correios === null){
	    			$cidade = $this->retorno_dados_CEP($this->_data['value']);
	    			if(empty($cidade['cidade'])){ //Válido CEP correios
							$this->set_error($this->core->get_msg_array('is_CEP_invalid', $this->_data['value']));
		    		}
	    		}
	    	}
			return $this;
	    }

	    //is_captcha_google
	    public function is_captcha_google($recaptcha_secret){
	        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$this->_data['value']);
	        $response = json_decode($response, true);
	        if($response["success"] === false){
							$this->set_error($this->core->get_msg_array('is_captcha_google', $this->_data['value']));
	        }
	    	return $this;
	    }

		//is_unique
	    public function is_unique($param){
	    	if($this->is_required_test() === true){ //Campo preenchido
		    	if($param === true){
								$this->set_error($this->core->get_msg_array('is_unique', $this->_data['descricao']));
		        }
	    	}
	    	return $this;
	    }

		//is_date_validate_past
	    public function is_date_validate_past($date){
	    	if($this->is_required_test() === true){ //Campo preenchido
	    		$date      = $this->conv_datahora($date,'Y-m-d');
	    		$date_comp = $this->conv_datahora($this->_data['value'],'Y-m-d');
	    		if(strtotime($date_comp) < strtotime($date)) {
						$this->set_error($this->core->get_msg_array('is_date_validate_past', $this->_data['value']));
	    		}
	    	}
	    	return $this;
	    }

	    //is_required_upload
	    public function is_required_upload(){
    		if(empty($this->_data['value']['name'])){
					$this->set_error($this->core->get_msg_array('empty_file_up', $this->_data['descricao']));
    		}
	    	return $this;
	    }

		//is_compare_campo
	    public function is_compare_campo($param,$descricao2){
	    	if($this->is_required_test() === true){ //Campo preenchido
	    		if($param != $this->_data['value']){
						$this->set_error($this->core->get_msg_array('is_compare_campo', "{$this->_data['descricao']},{$descricao2}"));
	    		}
    		}
	    	return $this;
	    }





//***************************************************************************************************************************************************************
//FUNÇÕES GERAIS DO FRAMEWORK
//***************************************************************************************************************************************************************

		//===========================================================
	    //IMPORTO DADOS DE ARQUIVO CSV
 		function importa_csv($caminho){
		    $handle = fopen($caminho, "r");
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$matriz[] = $data;
			}
		    fclose($handle);
		    return $matriz;
 		}

		//===========================================================
	    //VALIDO EMAIL SERVIDOR ENVIO EMAIL DE TESTE
	    function valido_email_serv($email){
	    	$mail = new email();
		    $mail->setEmail_from($_SESSION['Username_smtp']);
		    $mail->setNome_remetente('');
			$destin = explode(',',$email);
			foreach ($destin as &$value) {
				$destinatario_a[] = $value;
			}
		    $mail->setEmail_send($destinatario_a);
		    $mail->setEmail_resposta($_SESSION['Username_smtp']);
		    $mail->setNome_resposta('');
		    $mail->setAssunto('Validação de e-mail');  //Assunto
		    $mail->setConteudo('Seu e-mail foi validado com sucesso em nosso sistema.'); //Conteúdo
		    $exec_email = $mail->envio_email_phpmailer();
		    if($exec_email === true){
		    	return true;
		    }else{
		    	return false;
		    }
	    }

		//===========================================================
		//FORMATO STRING BYTE
		function formatBytes($size,$decimals = 0,$extensao = null){
		    $unit = array(
		        '0' => 'Byte',
		        '1' => 'KB',
		        '2' => 'MB',
		        '3' => 'GB',
		        '4' => 'TB',
		        '5' => 'PB',
		        '6' => 'EB',
		        '7' => 'ZB',
		        '8' => 'YB'
		    );
		    for($i = 0; $size >= 1024 && $i <= count($unit); $i++){
		        $size = $size/1024;
		    }
		    if(!empty($extensao)){
		    	return round($size, $decimals).' '.$unit[$i];
		    }else{
		    	return round($size, $decimals);
		    }
		}

		//===========================================================
	    //SUBSTITUO STRINGS POR OUTRAS
		function substituo_strings($procuro,$substituo,$string_original){
			return str_replace($procuro, $substituo, $string_original);
		}

		//===========================================================
		//LOCALIZO PALAVRA STRING
		function localizo_string($mystring, $findme){
			return strpos($mystring, $findme);
		}

		//===========================================================
		//RETIRO POSIÇÃO DO ARRAY
		function array_retira_a_posicao_informada ($array, $pos){
		    if (array_key_exists ($pos, $array))
		        unset ($array[$pos]);
			    return $array;
		}

		//===========================================================
		//PROTEJO DADOS (ANTI INJECTION)
		function anti_injection($sql,$param = null) {
			if(empty($param)){
				if(is_array($sql)){
					for ($i=0; $i < count($sql); $i++) {
						$sql1[] = preg_replace(("/(having|like|null|from|select|insert|'|union|order by|limit|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "", $sql[$i]);
						$sql1[] = trim($sql[$i]);
						$sql1[] = strip_tags($sql[$i]);
						$sql1[] = addslashes($sql[$i]);
						$sql1[] = htmlspecialchars($sql[$i]);
					}
					return array_unique($sql1);
				}else{
					$sql = preg_replace(("/(having|like|null|from|select|insert|'|union|order by|limit|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "", $sql);
					$sql = trim($sql);
					$sql = strip_tags($sql);
					$sql = addslashes($sql);
					$sql = htmlspecialchars($sql);
					return $sql;
				}
			}else{
				if(is_array($sql)){
					for ($i=0; $i < count($sql); $i++) {
                        $sql1[]        = preg_replace(("/(having|like|null|from|select|insert|'|union|order by|limit|delete|where|drop table|show tables|\*|--|\\\\)/"), "", $sql[$i]);
                        //$sql1[]        = trim($sql[$i]);
                        //$sql1[]        = addslashes($sql[$i]);
                        $ter_excluidos = array("<?php", "?>", "<?"); //Tiro tags php
                        $novos_ter     = array("","","");
                        $sql1[]        = str_replace($ter_excluidos, $novos_ter, $sql[$i]);
					}
					return array_unique($sql1);
				}else{
					$sql           = preg_replace(("/(having|like|null|from|select|insert|'|union|order by|limit|delete|where|drop table|show tables|\*|--|\\\\)/"), "", $sql);
					//$sql           = trim($sql);
					//$sql           = addslashes($sql);
					$ter_excluidos = array("<?php", "?>", "<?"); //Tiro tags php
					$novos_ter     = array("","","");
					$sql           = str_replace($ter_excluidos, $novos_ter, $sql);
					return $sql;
				}
			}
	    }

		//===========================================================
		//ENCURTO TEXTO E ADICIONO ...
		function encurtarTexto($texto,$tamPermitido) {
		   if (strlen($texto) > $tamPermitido)
			  return substr($texto, 0, $tamPermitido) . "...";
		   else
			  return $texto;
		}

		//===========================================================
		//REMOVO PALAVRAS DE STRING
		function remover_palavras($input,$substitui,$array_palavras = array(),$palavras_unicas = true){
			$resultado = array(); //Criar o array de saída
			foreach($array_entrada as $palavra){
				if(!in_array($palavra,$array_palavras) && ($palavras_unica ? !in_array($palavra,$resultado) : true)){
					$resultado[] = $palavra;
				}
			}
			return implode($substitui,$resultado);
		}

		//===========================================================
		//FUNÇÃO PARA DEIXAR STRING EM MAIUSCULO OU MINUSCULO
		function conv_string($term, $tp) {
			if ($tp == "1")
				return strtr(strtoupper($term),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
			elseif ($tp == "0")
				return strtr(strtolower($term),"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß","àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
			elseif ($tp == "2"){
				$term = strtr(strtolower($term),"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß","àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
				return ucwords(($term));
			}
		}

		//===========================================================
		//FUNÇÃO QUE VERIFICA A QTD DE DIAS ENTRE DUAS DATAS
		function dif_dias_datas($data_inicial,$data_final){
			$data_inicial = $this->converteData($data_inicial);
			$data_final   = $this->converteData($data_final);
			$time_inicial = strtotime($data_inicial);
			$time_final   = strtotime($data_final);
			$diferenca    = $time_final - $time_inicial;
			// Calcula a diferença de dias
			return (int)floor( $diferenca / (60 * 60 * 24));
		}

		//===========================================================
		//FUNÇÃO QUE ADICIONA OU SUBTRAI DIAS MESES OU ANOS DE UMA DATA
		function calculo_data($data,$anoConta,$mesConta,$diaConta){
			$ano = substr($data,0,4);
		    $mes = substr($data,5,2);
		    $dia = substr($data,8,2);
		    return $this->converteData(date('Y-m-d',mktime (0, 0, 0, $mes+($mesConta), $dia+($diaConta), $ano+($anoConta))));
		}

		//===========================================================
		//IDENTIFICO SISTEMA OPERACIONAL DO USUÁRIO
		function identifico_so(){
			$core = new core();
			$core->includeHelper('navegador_so');
			$navegador = new navegador_so();
			$navegador_ = (array) $navegador->detectaSo();
			return $navegador_['so']['versao'];
		}

		//===========================================================
		//IDENTIFICO NAVEGADOR
		function identifico_navegador(){
			  $core = new core();
			  $core->includeHelper('navegador_so');
			  $navegador = new navegador_so();
			  $navegador_ = (array) $navegador->detectaNavegador();
			  return $navegador_['navegador']['navegador'].' '.$navegador_['navegador']['versao'];
		}

		//===========================================================
		//REDIRECIONO PÁGINA
		function redireciono_pagina($pagina,$tipo,$tempo = null){
			if($tipo == 1){ //Redireciono por Header
				header('Location: '.$pagina.'');
				exit();
			}
			if($tipo == 2){ //Redireciono por Hmtl
				if(empty($tempo)){
					$tempo = 0;
				}
				echo '<meta http-equiv="REFRESH" content="'.$tempo.';url='.$pagina.'">';
				exit();
			}
		}

		//===========================================================
		//BUSCO DADOS DE CEP
		function retorno_dados_CEP($cep){

			//parametros passados pela URL
			$postCorreios = "CEP=".$cep."&Metodo=listaLogradouro&TipoConsulta=cep";

			//url para fazer a requisicao
			$cURL = curl_init("http://www.buscacep.correios.com.br/servicos/dnec/consultaLogradouroAction.do");

			//seta opcoes para fazer a requisicao
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cURL, CURLOPT_HEADER, false);
			curl_setopt($cURL, CURLOPT_POST, true);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $postCorreios);

			//faz a requisicao e retorna o conteudo do endereco
			$saida = curl_exec($cURL);
			curl_close($cURL);// encerra e retorna os dados
			$saida = utf8_encode($saida); // codifica conteudo para utf-8
			$saida2 = utf8_encode($saida);
			$saida2 = utf8_decode($saida2);
			$saida2 = str_replace('/>', '></td>', $saida2);
			$campoTabela = "";

			//pega apenas o conteudo das tds e transforma em um array
			preg_match_all('@<td (.*?)<\/td>@i', $saida2, $campoTabela);

			//Pego conteúdo
			$rua    = strip_tags($campoTabela[0][0]); // rua
			$bairro = strip_tags($campoTabela[0][1]); // bairro
			$cidade = strip_tags($campoTabela[0][2]); // cidade
			$estado = strip_tags($campoTabela[0][3]); // estado
			$cep    = strip_tags($campoTabela[0][4]); // cep

			//Monto Array
			$array = array(
				'rua'    => $rua,
				'bairro' => $bairro,
				'cidade' => $cidade,
				'estado' => $estado,
				'cep'    => $cep,
			);
			return $array;
		}

		//===========================================================
		//RETORNO TEMPO DE UMA DATA E HORA
		function retorno_tempo_post($data){
			  $data_atual = mktime(); // data atual em segundos

			  //separamos as partes da data
			  list($ano,$mes,$dia)  = explode("-",$data);
			  list($dia,$hora)      = explode(" ",$dia);
			  list($hora,$min,$seg) = explode(":",$hora);

			  //transformamos a data do banco em segundos usando a função mktime()
			  $data_banco = mktime($hora,$min,$seg,$mes,$dia,$ano);
			  $diferenca  = $data_atual - $data_banco; // subtraímos a data atual menos a data do banco em segundos
			  $minutos    = $diferenca/60; // dividimos os segundos por 60 para transformá-los em minutos
			  $horas      = $diferenca/3600; // dividimos os segundos por 3600 para transformá-los em horas
			  $dias       = $diferenca/86400; // dividimos os segundos por 86400 para transformá-los em dias

			  //abaixo fazemos verificações para definir a mensagem a ser mostrada.
			  if($minutos < 1){ // se a tiver passado de 0 a 60 segundos
			  	 $diferenca = "Há alguns segundos";
			  } elseif($minutos > 1 && $horas < 1) { // se tiver passado de 1 a 60 minutos
			  if(floor($minutos) == 1 or floor($horas) == 1){ $s = ''; } else { $s = 's'; } // plural ou singular de minuto
			  	 $diferenca = "Há ".floor($minutos)." minuto".$s;
			  } elseif($horas <= 24) { // se tiver passado de 1 a 24 horas
			  if(floor($horas) == 1){ $s = ''; } else { $s = 's'; } // plural ou singular de hora
			  	 $diferenca = "Há ".floor($horas)." hora".$s;
			  } elseif($dias <= 2){ // se tiver passado um dia
			  	 $diferenca = "Ontem";
			  } elseif($dias <= 7){ // se tiver passado 6 dias
			  	 $diferenca = "Há ".floor($dias)." dias";
			  } elseif($dias <= 8){ // se tiver passado uma semana (7 dias)
			  	 $diferenca = "Há uma semana";
			  } else {
			  	 $diferenca = "Há ".floor($dias)." dias";//$diferenca = date("d/m/Y",$data_banco);
			  }
			  return $diferenca;
		}

		//===========================================================
		//RETORNO O MêS ATUAL
		function retorno_mes($data = null){
			$meses = array(
				'01' => 'Janeiro',
				'02' => 'Fevereiro',
				'03' => 'Março',
				'04' => 'Abril',
				'05' => 'Maio',
				'06' => 'Junho',
				'07' => 'Julho',
				'08' => 'Agosto',
				'09' => 'Setembro',
				'10' => 'Outubro',
				'11' => 'Novembro',
				'12' => 'Dezembro'
			);
			if(empty($data)){ //dia atual
				return $meses[date("m")];
			}else{
				$data = $this->substituo_strings('/','-',$data);
				$data = date('m', strtotime($data));
				return $meses[$data];
			}
		}

		//===========================================================
		//RETORNO O DIA DA SEMANA
		function retorno_dia_semana($data = null){
			$diasemana = array('Domingo','Segunda-feira','Terça-feira','Quarta-feira', 'Quinta-feira','Sexta-feira','Sábado');
			if(empty($data)){ //dia atual
				return $diasemana[date('w')];
			}else{
				$data = date('w', strtotime($data));
				return $diasemana[$data];
			}
		}

		//===========================================================
		//RETORNO A DATA POR EXTENSO
		function retorno_data_por_extenso($data = null){
			if(empty($data)){
                $dia_se = $this->retorno_dia_semana();
                $dia    = date('d');
                $mes    = $this->retorno_mes();
                $ano    = date('Y');
			}else{
				$data_americano = date('Y-m-d', strtotime($data));
                $dia_se = $this->retorno_dia_semana($data_americano);
                $dia    = date('d', strtotime($data));
                $mes    = $this->retorno_mes($data);
                $ano    = date('Y', strtotime($data));
			}
			return $dia_se.", ".$dia." de ".$mes." ".$ano;
		}

		//===========================================================
		//RETORNO SAUDAÇÃO BOA TARDE, BOA NOITE
		function retorno_saudacao($hora = null){
			if(empty($hora)){
				$hr = date("H");
			}else{
				$hr = $hora;
			}
			if($hr >= 12 && $hr < 18) {
				return "Boa tarde!";
			}
			else if ($hr >= 0 && $hr <12 ){
				return "Bom dia!";
			}
			else {
				return "Boa noite!";
			}
		}

		//===========================================================
		//FUNÇÃO CONVERTER DATA, DATA E HORA OU HORA
		function conv_datahora($data,$format){
		   if(!empty($data) && !empty($format)){
			   $data = $this->substituo_strings('/','-',$data);
			   return date($format, strtotime($data));
		   }
		}
		function valida_data($data){
			$data = split("[-,/]", $data);
			if(!checkdate($data[1], $data[0], $data[2]) and !checkdate($data[1], $data[2], $data[0])) {
				return false;
			}
			return true;
		}
		function converteData($data){
			if(valida_data($data)) {
				return implode(!strstr($data, '/') ? "/" : "-", array_reverse(explode(!strstr($data, '/') ? "-" : "/", $data)));
			}
		}

		//===========================================================
		//FUNCTION MONTO SAUDAÇÃO EX: Boa tarde! Segunda-feira, 10 Março de 2015
		function retorno_data_saudacao(){
			return $this->retorno_dia_semana().', '.date('d').' '.$this->retorno_mes().' de '.date('Y');
		}

		//===========================================================
		//FUNÇÃO MOSTRO DIA MES ANO E HORA DE UMA DATA
		function retorno_data_escrita($date,$hora = null){
			if(empty($hora)){
				return $this->retorno_dia_semana($date).', '.$this->conv_datahora($date,"d").' '.$this->retorno_mes($date).' de '.$this->conv_datahora($date,"Y");
			}else{
				return $this->retorno_dia_semana($date).', '.$this->conv_datahora($date,"d").' '.$this->retorno_mes($date).' de '.$this->conv_datahora($date,"Y").' - '.$this->conv_datahora($date,"g:i A");
			}
		}

		//===========================================================
		//FUNÇÃO PARA SOMA DE TEMPOS
		function soma_tempos($times,$return = null){
			$seconds = 0;
			foreach ( $times as $time ){
			   list( $g, $i, $s ) = explode( ':', $time );
			   $seconds += $g * 3600;
			   $seconds += $i * 60;
			   $seconds += $s;
			}
			$hours   = floor( $seconds / 3600 );
			$seconds -= $hours * 3600;
			$minutes = floor( $seconds / 60 );
			$seconds -= $minutes * 60;
			if(empty($return)){
				return "{$hours}:{$minutes}:{$seconds}";
			}else{
				if($return === "H:i:s"){
					return "{$hours}:{$minutes}:{$seconds}";
				}
				if($return === "H:i"){
					return "{$hours}:{$minutes}";
				}
				if($return === "i"){
					return "{$minutes}";
				}
				if($return === "H"){
					return "{$hours}";
				}
			}
		}

		//===========================================================
		//FUNÇÃO PARA SUBTRAÇÃO DE TEMPOS
		function subtraio_tempos($times,$return = null){
			$seconds = 0;
			foreach ( $times as $time ){
			   list( $g, $i, $s ) = explode( ':', $time );
			   $seconds += $g * 3600;
			   $seconds += $i * 60;
			   $seconds += $s;
			}
			$hours   = floor( $seconds / 3600 );
			$seconds += $hours * 3600;
			$minutes = floor( $seconds / 60 );
			$seconds += $minutes * 60;
			if(empty($return)){
				return "{$hours}:{$minutes}:{$seconds}";
			}else{
				if($return === "H:i:s"){
					return "{$hours}:{$minutes}:{$seconds}";
				}
				if($return === "H:i"){
					return "{$hours}:{$minutes}";
				}
				if($return === "H"){
					return "{$hours}";
				}
			}
		}

		function subtraio_tempos2($data1,$data2){
			$data_login = strtotime($data1);
			$data_logout = strtotime($data2);
			$tempo_con = mktime(date('H', $data_logout) - date('H', $data_login), date('i', $data_logout) - date('i', $data_login), date('s', $data_logout) - date('s', $data_login));
			return date('H:i:s', $tempo_con);
		}

		//===========================================================
		//FUNÇÃO GERO TOKEN ALEATÓRIO
		function gero_token(){
			$data          = date('d/m/Y');
			$hora          = date('h:m:s');
			$num_aleatorio = rand(1,1000);
			return md5($hora.$data.$num_aleatorio.$hora);
		}

		//===========================================================
		//GERO SENHA ALEATORIA
		function gero_senha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
			$lmin       = 'abcdefghijklmnopqrstuvwxyz';
			$lmai       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$num        = '1234567890';
			$simb       = '!@#$%*-';
			$retorno    = '';
			$caracteres = '';
			$caracteres .= $lmin;
			if ($maiusculas) $caracteres .= $lmai;
			if ($numeros) $caracteres    .= $num;
			if ($simbolos) $caracteres   .= $simb;
			$len = strlen($caracteres);
			for ($n = 1; $n <= $tamanho; $n++) {
				$rand     = mt_rand(1, $len);
				$retorno .= $caracteres[$rand-1];
			}
			return $retorno;
		}

		//===========================================================
		//MENSAGEM DE ERRO E SUCESSO SITE
		function monto_msg_site($param,$mensagem){
			if($param == 1){ //Erro
				return '<span class="mensagem-not-erro"> <span class="glyphicon glyphicon-remove"></span> '.$mensagem.'</span>';
			}
			if($param == 2){ //Sucesso
				return '<span class="mensagem-not-sucesso"> <span class="glyphicon glyphicon-ok"></span> '.$mensagem.'</span>';
			}
		}

		//===========================================================
		//FUNÇÃO QUE CONTROLA O TEMPO DE REQUISIÇÃO DE UMA AÇÃO
		function controla_tempo_requisicao($contador,$sessao_tempo,$num_tentativas_bloq_requisicoes,$tempo_bloq_requisicoes){
			$_SESSION[$contador] = $_SESSION[$contador] + 1; //contador
			if($_SESSION[$contador] > $num_tentativas_bloq_requisicoes){ //faço a verificação
				$intervalo = $tempo_bloq_requisicoes; //segundos
				$agora     = time(); //agora
				if (isset($_SESSION[$sessao_tempo]) and ($_SESSION[$sessao_tempo] > ($agora - $intervalo))) {
					echo $this->monto_msg_site(1,$this->get_msg_array('barra_tempo_requisicao',array(''))); //Mensagem de erro
					return true;
				}
				$_SESSION[$sessao_tempo] = $agora; //Seto novos valores de tempo de requisição
				$_SESSION[$contador] = 0; //Zero contador
				return false;
			}
		}

		//===========================================================
		//AUTENTICO USUÁRIO
		function auth_usuario($app,$param,$pagina = null,$pagina_historico = null){
			if($_SESSION[$app.'_auth_status'] != $param){
				if(isset($pagina_historico)){
					$_SESSION[$app.'_page_historico'] = $pagina_historico;
				}
				if(!empty($pagina)){
					$this->redireciono_pagina($pagina,1);
					exit();
				}else{
					return $param;
				}
			}
		}

		//===========================================================
		//DESTRUO A SESSÃO DO USUÁRIO
		function destroy_usuario($pagina = null){
			session_destroy();
			if(!empty($pagina)){
				$this->redireciono_pagina($pagina,1);
				exit();
			}
		}

		//===========================================================
		//PEGO AS METATAGS DE UMA PÁGINA EXTERNA
		function pego_meta_tags($url){

				//CHAMO PÁGINA
				$timeout = 5;
				$useragent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_VERBOSE, true);
				$data = curl_exec($ch);
				curl_close($ch);

				//PEGO O HTML
				$html = $data;
				$doc = new DOMDocument();
				$doc->loadHTML($html);

				//PEGO OS TAGS
				$nodes = $doc->getElementsByTagName('title');
				$title = $nodes->item(0)->nodeValue;
				$metas = $doc->getElementsByTagName('meta');
				for ($i = 0; $i < $metas->length; $i++) {
						$meta = $metas->item($i);
						if($meta->getAttribute('name') == 'description'){
							$description = $meta->getAttribute('content');
						}
						if(empty($description)){
							if($meta->getAttribute('name') == 'Description'){
								$description = $meta->getAttribute('content');
							}
						}
						if($meta->getAttribute('name') == 'keywords'){
							$keywords = $meta->getAttribute('content');
						}
						if($meta->getAttribute('property') == 'og:title'){
							$ogtitle = $meta->getAttribute('content');
						}
						if($meta->getAttribute('property') == 'og:site_name'){
							$ogsite_name = $meta->getAttribute('content');
						}
						if($meta->getAttribute('property') == 'og:description'){
							$ogdescription = $meta->getAttribute('content');
						}
						if($meta->getAttribute('property') == 'og:image'){
							$ogimage = $meta->getAttribute('content');
						}
				}

				//MONTO ARRAY
				$array = array(
						'title'         => $title,
						'description'   => $description,
						'keywords'      => $keywords,
						'ogtitle'       => $ogtitle,
						'ogsite_name'   => $ogsite_name,
						'ogdescription' => $ogdescription,
						'ogimage'       => $ogimage,
				);

				//RETORNO
				return $array;
		}

		//===========================================================
		//FUNÇÃO VÁLIDA URL NO SERVIDOR
		function valida_url( $link ){
			    $partes_url = @parse_url( $link );
			    if ( empty( $partes_url["host"] ) ) return( false );
			    if ( !empty( $partes_url["path"] ) ){
			        $path_documento = $partes_url["path"];
			    }
			    else{
			        $path_documento = "/";
			    }
			    if ( !empty( $partes_url["query"] ) ) {
			        $path_documento .= "?" . $partes_url["query"];
			    }
			    $host = $partes_url["host"];
			    $porta = $partes_url["port"];
			    if (empty( $porta ) ) $porta = "80";
			    $socket = @fsockopen( $host, $porta, $errno, $errstr, 30 );
			    if (!$socket) {
			        return(false);
			    }
			    else{
			        fwrite ($socket, "HEAD ".$path_documento." HTTP/1.0\r\nHost: $host\r\n\r\n");
			        $http_response = fgets( $socket, 22 );

			        $pos = null;
			        $pos = strpos($http_response, "200 OK");
			        if ( !empty($pos) )
			        {
			            fclose( $socket );
			            return(true);
			        }
			        else{
						if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $link)) {
						   return(true);
						}
						else {
						   return(false);
						}
			        }
			    }
 			}

		 //===========================================================
		 //FUNÇÃO QUE VÁLIDA URL DO YOUTUBE
		 function valida_url_youtube($URL){
			 if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $URL)) {
					 $delimiter = "youtube.com";
					 $pieces = explode($delimiter, $URL);
					 if($pieces[0] == $URL){
						 return false;
					 }	else{
						 return true;
					 }
			 }
			 else {
					return false;
			 }
		 }

		 //===========================================================
		 //FUNÇÃO GERA SAIDA JSON NA TELA
		 function gera_saida_json($array){
			echo json_encode($array);
		 }

		 //===========================================================
		 //MENSAGEM DE ERRO JSON
		 function return_msg_json($tipo,$mensagem,$redireciono = null,$tempo = null,$limpo = null,$id = null){
			 if($tipo == "erro"){
				$this->gera_saida_json(array('erro' => $mensagem));
			 }
			 if($tipo == "sucesso"){
				 $this->gera_saida_json(array('sucesso' => $mensagem));
			 }
			 if($tipo == "sucesso_retorno_id"){
				$this->gera_saida_json(array('sucesso' => $mensagem, 'id' => $id));
			}
			 if($tipo == "sucesso_limpo"){
				$this->gera_saida_json(array('sucesso' => $mensagem, 'limpo_campo' => $limpo));
			}
			if($tipo == "sucesso_redireciona"){
				$this->gera_saida_json(array('sucesso' => $mensagem, 'redireciono' => $redireciono));
			}
			if($tipo == "sucesso_redireciona_tempo"){
				$this->gera_saida_json(array('sucesso' => $mensagem, 'redireciono' => $redireciono, 'tempo' => $tempo));
			}
		 }

		 //===========================================================
		 //FUNÇÃO QUE IDENTIFICA E CONVERTE STRING PARA UTF8 OU ISO
		 function iso_utf8_converter($string){
				$charset = mb_detect_encoding($string.'x', 'UTF-8, ISO-8859-1');
				if($charset != "UTF-8"){
					return utf8_decode($string);
				}else{
					return $string;
				}
			}

			//===========================================================
			//CRIO PATH ATÉ A PASTA CONTROLLER
			function monto_path_controller_comp($app,$controller_montado,$cmd = null,$path_raiz = null){
				if(!empty($cmd)){
					if(!empty($path_raiz)){
						return "{$this->core->get_config('dir_controller')}/{$controller_montado}{$this->core->get_config('suf_controller')}.php?cmd={$cmd}";
					}else{
						return "{$this->core->get_config('dir_raiz_http')}{$app}/{$this->core->get_config('dir_controller')}/{$controller_montado}{$this->core->get_config('suf_controller')}.php?cmd={$cmd}";
					}
				}else{
					if(!empty($path_raiz)){
						return "{$this->core->get_config('dir_controller')}/{$controller_montado}{$this->core->get_config('suf_controller')}.php";
					}else{
						return "{$this->core->get_config('dir_raiz_http')}{$app}/{$this->core->get_config('dir_controller')}/{$controller_montado}{$this->core->get_config('suf_controller')}.php";
					}
				}
			}

			//===========================================================
			//CRIO PATH ATÉ A PASTA CONTROLLER CORE CONTROLLER
			function monto_path_controller_comp_core($app,$controller_montado,$cmd = null,$path_raiz = null){
				if(!empty($cmd)){
					if(!empty($path_raiz)){
						return "{$this->core->get_config('dir_controller')}/{$controller_montado}{$this->core->get_config('suf_controller')}.php?cmd_core={$cmd}";
					}else{
						return "{$this->core->get_config('dir_raiz_http')}{$app}/{$this->core->get_config('dir_controller')}/{$controller_montado}{$this->core->get_config('suf_controller')}.php?cmd_core={$cmd}";
					}
				}else{
					if(!empty($path_raiz)){
						return "{$this->core->get_config('dir_controller')}/{$controller_montado}{$this->core->get_config('suf_controller')}.php";
					}else{
						return "{$this->core->get_config('dir_raiz_http')}{$app}/{$this->core->get_config('dir_controller')}/{$controller_montado}{$this->core->get_config('suf_controller')}.php";
					}
				}
			}

			//===========================================================
			//FUNÇÃO DE CRIPTOGRAFIA
			function mycrypt($string){

				 # chave de criptografia
			   $key = hash('sha256', $this->core->get_config('chave_criptografia'), true);

			   # create a random IV to use with CBC encoding
			   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			   $iv      = mcrypt_create_iv($iv_size, MCRYPT_RAND);

			   # creates a cipher text compatible with AES (Rijndael block size = 128)
			   # to keep the text confidential
			   # only suitable for encoded input that never ends with value 00h
			   # (because of default zero padding)
			   $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_CBC, $iv);

			   # prepend the IV for it to be available for decryption
			   $ciphertext = $iv.$ciphertext;

			   # encode the resulting cipher text so it can be represented by a string
			   $ciphertext_base64 = rtrim(strtr(base64_encode($ciphertext), '+/', '|_'), '='); //padrap é -_
				 return $ciphertext_base64;
			}

			//===========================================================
			//FUNÇÃO DE DESCRIPTOGRAFIA
			function decrypt($string){

				 # chave de criptografia
				 $key = hash('sha256', $this->core->get_config('chave_criptografia'), true);

				 #string
				 $ciphertext_dec = base64_decode(str_pad(strtr($string, '|_', '+/'), strlen($string) % 4, '=', STR_PAD_RIGHT)); //padrap é -_

				 # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
				 $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
				 $iv_dec = substr($ciphertext_dec, 0, $iv_size);

				 # retrieves the cipher text (everything except the $iv_size in the front)
				 $ciphertext_dec = substr($ciphertext_dec, $iv_size);

				 # may remove 00h valued characters from end of plain text
				 $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
				 $plaintext_dec = rtrim($plaintext_dec, "\0\4");
				 return $plaintext_dec;
			}

		//===========================================================
		//FUNÇÃO PARA CRIAR ARQUIVOS PHTML
		function crio_arquivo_phtml($caminho,$texto){
			$arquivo = fopen($caminho.'.phtml','w');

			//verificamos se foi criado
			if ($arquivo == false) die('Não foi possível criar o arquivo. <br /> <strong>'.$caminho.'</strong> ');

			//escrevemos no arquivo
			fwrite($arquivo, $texto);

			//Fechamos o arquivo após escrever nele
			fclose($arquivo);
		}

		//===========================================================
		//FUNÇÃO EXPORTO ARQUIVO PARA CSV
		function exp_array_para_csv(array &$array,$filename){

		   // desabilitar cache
		   $now = gmdate("D, d M Y H:i:s");
		   header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		   header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		   header("Last-Modified: {$now} GMT");

		   // forçar download
		   header("Content-Type: application/force-download");
		   header("Content-Type: application/octet-stream");
		   header("Content-Type: application/download");

		   // disposição do texto / codificação
		   header("Content-Disposition: attachment;filename={$filename}");
		   header("Content-Transfer-Encoding: binary");

		   if (count($array) == 0) {
		     echo null;
		   }
		   ob_start();
		   $df = fopen("php://output", 'w');
		   fputcsv($df, array_keys(reset($array)));
		   foreach ($array as $row) {
		      fputcsv($df, $row);
		   }
		   fclose($df);
		   echo ob_get_clean();
		}

		//===========================================================
		//RETIRO CONTRA BARRAS
		function stripslashes_deep($value){
		    $value = is_array($value) ?
		                array_map('stripslashes_deep', $value) :
		                stripslashes($value);

		    return $value;
		}

		//===========================================================
		//RETORNO ANOS
		function calc_idade($aniversario, $curr = 'now') {
			date_default_timezone_set( 'America/Sao_Paulo' );
			$year_curr = date("Y", strtotime($curr));
			$days = !($year_curr % 4) || !($year_curr % 400) & ($year_curr % 100) ? 366: 355;
			list($d, $m, $y) = explode('/', $aniversario);
			return floor(((strtotime($curr) - mktime(0, 0, 0, $m, $d, $y)) / 86400) / $days);
		}

		//GERO CÓDIGO NÚMÉRICO
		function gero_cod_numerico($tamanho = null){
			if(empty($tamanho)){
				return mt_rand(1000, 9000);
			}else{
				if($tamanho = 5){
					return mt_rand(10000, 90000);
				}
				if($tamanho = 6){
					return mt_rand(100000, 900000);
				}
				if($tamanho = 7){
					return mt_rand(1000000, 9000000);
				}
			}
		}



	}
