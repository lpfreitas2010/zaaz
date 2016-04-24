<?php

	/**
	* Função que monta trechos de
	* códigos html pelo php
	* @author Fernando
	* @version 2.0.0
	**/

	//Classe
	class mont_html {

		//===========================================================
		//GET E SET ARRAY
		public $_array = array();
		public function get_array(){
			$array = ($this->_array);
			unset($this->_array);
			return $array;
		}
		public function set_array($name, $name2, $value){
			if(!isset($name)){
				$this->_array[$name2] = $value;
			}else{
				$this->_array[$name][$name2] = $value;
			}
			return $this;
		}

		//===========================================================
		//TRANSFORMO PARAMETROS SMARTY
		function parametros_smarty_variaveis($string,$string_sub = null){
			$string_pesq = array("[", "]", "bd:","sm:");
			if(!empty($string_sub)){
				$string_sub  = array('', '', '$i.',"$");
			}else{
				$string_sub  = array('{', '}', '$i.',"$");
			}
			return str_replace($string_pesq, $string_sub, $string);
		}

		//===========================================================
		//MONTO IF DE CONTEUDO
		function if_else_smarty(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$condicoes  = $array[$i]['condicoes'];
				$returnif   = $array[$i]['cod_if'];
				$returnelse = $array[$i]['cod_else'];

				//VERIFICO CAMPO
				if(!empty($condicoes)){
					$condicoes = $this->parametros_smarty_variaveis($condicoes,true);
				}
				if(!empty($returnif)){
					$returnif = $this->parametros_smarty_variaveis($returnif);
				}
				if(!empty($returnelse)){
					$returnelse = $this->parametros_smarty_variaveis($returnelse);
				}

				//MONTO IF ELSE
				if(!empty($returnelse)){
					$string = '
					{if '.$condicoes.' }
						'.$returnif.'
					{else}
						'.$returnelse.'
					{/if}
					';
				}else{
					$string = '
					{if '.$condicoes.'}
						'.$returnif.'
					{/if}
					';
				}
				//MONTO E RETORNO
				return $string;
			}
		}

		//===========================================================
		//RETORNO AÇÃO DE UMA LINHA
		function acao_linha_smarty(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$status                  = $array[$i]['status'];
				$class                   = $array[$i]['class'];
				$url                     = $array[$i]['url'];
				$outros                  = $array[$i]['outros'];
				$campo_ativar            = $array[$i]['campo_ativar'];
				$status_btn_detalhamento = $array[$i]['status_btn_detalhamento'];
				$status_btn_editar       = $array[$i]['status_btn_editar'];
				$status_btn_ativar       = $array[$i]['status_btn_ativar'];
				$url_btn_ativar          = $array[$i]['url_btn_ativar'];

				//STATUS TRUE
				if($status == true){

					//AÇÃO DETALHAMENTO
					if($class == 'btn_detalhamento'){
						if($status_btn_detalhamento == true){
							$condicoes = $this->parametros_smarty_variaveis('[bd:id]');
							$array = array('class' => 'btn_detalhamento {if $i.status_id == 2}cor-inativa-tr{/if}','outros' => "param_id=\"{$condicoes}\"");
						}
					}

					//AÇÃO EDITAR
					if($class == 'btn_editar'){
						if($status_btn_editar == true){
							$condicoes = $this->parametros_smarty_variaveis('[bd:id]');
							$array = array('class' => 'btn_editar {if $i.status_id == 2}cor-inativa-tr{/if}','outros' => "param_id=\"{$condicoes}\"");
						}
					}

					//AÇÃO ATIVAR
					if($class == 'btn_ativar_desativar'){
						if($status_btn_ativar == true){
							$condicoes = $this->parametros_smarty_variaveis('[bd:id]');
							$cond = '{if $i.status_id == 1}'.$url_btn_ativar.'&s=1{/if}{if $i.status_id == 2}'.$url_btn_ativar.'&s=2{/if}';
							$array = array('class' => 'btn_ativar {if $i.status_id == 2}cor-inativa-tr{/if}','outros' => "param_id=\"{$condicoes}\" param_url=\"".$cond."\"");
						}
					}

					//AÇÃO URL
					if($class == 'url'){
						$array = array('class' => 'link','outros' => 'link="'.$url.'"');
					}

					//AÇÃO LIVRE
					if($class == 'livre'){
						if($status_btn_ativar == true){
							$cond = '{if $i.'.$campo_ativar.' == 0} &id={$i.id}&s={$i.'.$campo_ativar.'} {/if}{if $i.'.$campo_ativar.' == 1} &id={$i.id}&s={$i.'.$campo_ativar.'}{/if}';
							$array = array('class' => 'funcao_aberta {if $i.'.$campo_ativar.' == 0}cor-inativa-tr{/if}','outros' => "{$outros} param=\"".$cond."\" ");
						}
					}

					//RETORNO
					return $array;
				}
			}
		}

		//===========================================================
		//RETORNO O HTML TD MONTADO
		function monto_html_td(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$td     = $array[$i]['td'];
				$class  = $array[$i]['class'];
				$outros = $array[$i]['outros'];

				//CLASSE
				if(!empty($class)){
					$class = ' class="'.$class.'" ';
				}
				//TD
				if(!empty($td)){
					$td = $this->parametros_smarty_variaveis($td);
				}
				//OUTROS
				if(!empty($outros)){
					$outros = $this->parametros_smarty_variaveis($outros);
				}
				$td_montado .= "<td {$class}{$outros}>{$td}</td>";
			}
			//MONTO E RETORNO
			return $td_montado;
		}

		//===========================================================
		//RETORNO O HTML DE UM LABEL COLORIDO
		function monto_html_label_color(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$texto       = $array[$i]['texto'];
				$class       = $array[$i]['class'];
				$class_icone = $array[$i]['class_icone'];

				//TEXTO
				if(!empty($texto)){
					$texto = $this->parametros_smarty_variaveis($texto);
				}
				//CLASSE ICONE
				if(!empty($class_icone)){
					$class_icone = '<i class="'.$class_icone.'"></i> ';
				}

				//MONTO E RETORNO
				return "<span class=\"label {$class}\">{$class_icone}{$texto}</span>";
			}
		}

		//===========================================================
		//RETORNO O HTML DE UM TEXTO
		function monto_html_texto(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$texto       = $array[$i]['texto'];
				$class_icone = $array[$i]['class_icone'];

				//TEXTO
				if(!empty($texto)){
					$texto = $this->parametros_smarty_variaveis($texto);
				}
				//CLASSE ICONE
				if(!empty($class_icone)){
					$class_icone = '<i class="'.$class_icone.'"></i> ';
				}
				//MONTO E RETORNO
				return "{$class_icone}{$texto}";
			}
		}

		//===========================================================
		//RETORNO O HTML DE UM LINK
		function monto_html_link(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$texto       = $array[$i]['texto'];
				$title       = $array[$i]['title'];
				$href        = $array[$i]['href'];
				$target      = $array[$i]['target'];
				$class       = $array[$i]['class'];
				$class_icone = $array[$i]['class_icone'];
				$outros      = $array[$i]['outros'];

				//TEXTO
				if(!empty($texto)){
					$texto = $this->parametros_smarty_variaveis($texto);
				}
				//TITLE
				if(!empty($title)){
					$title = ' title="'.$this->parametros_smarty_variaveis($title).'" ';
				}
				//HREF
				if(!empty($href)){
					$href = $this->parametros_smarty_variaveis($href);
				}
				//TARGET
				if(!empty($target)){
					$target = ' target="'.$target.'" ';
				}
				//CLASSE
				if(!empty($class)){
					$class = ' class="'.$class.'" ';
				}
				//CLASSE ICONE
				if(!empty($class_icone)){
					$class_icone = '<i class="'.$class_icone.'"></i> ';
				}
				//OUTROS
				if(!empty($outros)){
					$outros = ' '.$outros.' ';
				}
				//MONTO E RETORNO
				return "<a href=\"{$href}\"{$title}{$target}{$class}{$outros}>{$class_icone}{$texto}</a>";
			}
		}

		//===========================================================
		//RETORNO O HTML DE UMA IMAGEM
		function monto_html_img(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$class    = $array[$i]['class'];
				$src      = $array[$i]['src'];
				$alt      = $array[$i]['alt'];
				$width    = $array[$i]['width'];
				$height   = $array[$i]['height'];
				$lightbox = $array[$i]['lightbox'];

				//SRC
				if(!empty($src)){
					$src = $this->parametros_smarty_variaveis($src);
				}
				//ALT
				if(!empty($alt)){
					$alt = $this->parametros_smarty_variaveis($alt);
				}
				//CLASS
				if(!empty($class)){
					$class = ' class="'.$class.'" ';
				}
				//WIDTH
				if(empty($width)){
					$width = 60;
				}
				//HEIGHT
				if(!empty($height)){
					$height = " height=\"{$height}\" ";
				}else{
					$height = "";
				}

				//LIGHBOX
				if($lightbox == true){
					//MONTO E RETORNO
					return "<a href=\"{$src}\" data-lightbox=\"roadtrip\" title=\"Abrir imagem\"><img {$class} src=\"{$src}\" alt=\"{$alt}\" width=\"{$width}\"{$height}/></a>";
				}else{
					//MONTO E RETORNO
					return "<img {$class} src=\"{$src}\" alt=\"{$alt}\" width=\"{$width}\"{$height}/>";
				}
			}
		}

		//===========================================================
		//FILTROS SMARTY PHP
		function filtros_smarty(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$tipo  = $array[$i]['tipo'];
				$campo = $array[$i]['campo'];
				$valor = $array[$i]['valor_enc_texto'];

				//VERIFICO CAMPO
				if(!empty($campo)){
					$campo = $this->parametros_smarty_variaveis($campo,true);
				}

				//DATA
				if($tipo == 'data'){
					$string_montada = '{if '.$campo.' != "0000-00-00"  }{'.$campo.'|date_format:"%d/%m/%Y"} {/if}';
				}

				//HORA
				if($tipo == 'hora'){
					$string_montada = '{'.$campo.'|date_format:"%H:%M:%S"}';
				}

				//HORA MIN
				if($tipo == 'hora_min'){
					$string_montada = '{'.$campo.'|date_format:"%H:%M"}';
				}

				//DATA E HORA
				if($tipo == 'data_hora'){
					$string_montada = '{if '.$campo.' != "0000-00-00 00:00:00"  }{'.$campo.'|date_format:"%d/%m/%Y"} - {'.$campo.'|date_format:"%H:%M:%S"}{/if}';
				}

				//TEMPO
				if($tipo == 'tempo'){
					$string_montada = '{'.$campo.'|date_format:"tempo"}';
				}

				//IDADE
				if($tipo == 'idade'){
					$string_montada = '{'.$campo.'|date_format:"idade"}';
				}

				//DIA DA SEMANA
				if($tipo == 'dia_semana'){
					$string_montada = '{'.$campo.'|date_format:"dia_semana"}';
				}

				//DIA DA SEMANA HOJE
				if($tipo == 'dia_semana_hoje'){
					$string_montada = '{'.$campo.'|date_format:"dia_semana_hoje"}';
				}

				//MES
				if($tipo == 'mes'){
					$string_montada = '{'.$campo.'|date_format:"mes"}';
				}

				//DATA EXTENSO
				if($tipo == 'data_extenso'){
					$string_montada = '{'.$campo.'|date_format:"data_extenso"}';
				}

				//TEMPO E HORA
				if($tipo == 'tempo_hora'){
					$string_montada = '{if '.$campo.' != "0000-00-00 00:00:00" }{'.$campo.'|date_format:"tempo"} - {'.$campo.'|date_format:"%H:%M:%S"}{/if}';
				}

				//MAIUSCULO
				if($tipo == 'maiusculo'){
					$string_montada = '{'.$campo.'|upper}';
				}

				//MINUSCULO
				if($tipo == 'minusculo'){
					$string_montada = '{'.$campo.'|lower}';
				}

				//MAIUSCULO E MINUSCULO
				if($tipo == 'maiusc_minusc'){
					$string_montada = '{'.$campo.'|capitalize}';
				}

				//QUEBRA LINHA
				if($tipo == 'br_auto'){
					$string_montada = '{'.$campo.'|nl2br}';
				}

				//FORMATA NUMERO
				if($tipo == 'format_num'){
					$string_montada = '{'.$campo.'|number_format:2:",":"."}';
				}

				//ENCURTO TEXTO
				if($tipo == 'enc_texto' || !empty($valor)){
					$string_montada = '<span title="{'.$campo.'}">{'.$campo.'|truncate:'.$valor.':" ...":true}</span>';
					//MAIUSCULO
					if($tipo == 'maiusculo'){
						$string_montada = '<span class="text-uppercase">'.$string_montada.'</span>';
					}
					//MINUSCULO
					if($tipo == 'minusculo'){
						$string_montada = '<span class="text-lowercase">'.$string_montada.'</span>';
					}
					//MAIUSCULO E MINUSCULO
					if($tipo == 'maiusc_minusc'){
						$string_montada = '<span class="text-capitalize">'.$string_montada.'</span>';
					}
				}

				//RETORNO
				return $string_montada;
			}
		}

		//===========================================================
		//RETORNO O HTML INPUT
		function monto_html_input(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
                $input_class_tamanho = $array['input_class_tamanho'];
                $input_id            = $array['input_id'];
                $label_for_texto     = $array['label_for_texto'];
                $input_type          = $array['input_type'];
                $input_name          = $array['input_name'];
                $input_class         = $array['input_class'];
                $input_placeholder   = $array['input_placeholder'];
                $input_title         = $array['input_title'];
                $title_help          = $array['title_help'];
                $input_value         = $array['input_value'];
                $input_outros        = $array['input_outros'];
                $input_disabled      = $array['input_disabled'];
                $input_pagina        = $array['input_pagina'];
                $input_pagina_title  = $array['input_pagina_title'];
                $input_pagina_url    = $array['input_pagina_url'];
				$input_pagina_icon   = $array['input_pagina_icon'];
				$icon_in_input       = $array['icon_in_input'];
				$input_pagina_add_title = $array['input_pagina_add_title'];
				$input_pagina_add_id    = $array['input_pagina_add_id'];
				$maxlength              = $array['input_maxlength'];

				//MAXLENGHT
				if(!empty($maxlength)){
					$maxlength = ' maxlength="'.$maxlength.'" ';
				}
				//TITLE
				if(!empty($input_title)){
					$input_title = $this->parametros_smarty_variaveis($input_title);
					$tooltip = ' data-title="tooltip" data-placement="top" ';
				}
				//VALOR
				if(!empty($input_value)){
					$input_value = $this->parametros_smarty_variaveis($input_value);
				}
				if(!empty($title_help)){
					$title_help = '<i data-toggle="popover" data-content="'.$title_help.'" title="AJUDA!" data-placement="left" class="fa fa-question-circle pull-right font-media"></i>';
				}
				//INPUT PAGINA TITLE
				if(!empty($input_pagina_title)){
					$input_pagina_title = $this->parametros_smarty_variaveis($input_pagina_title);
					$tooltip_ = 'data-title="tooltip" data-placement="top"';
				}
				//INPUT PAGINA
				if(!empty($input_pagina_title)){
					$input_pagina = '<a style="margin-left:9px;" class="pull-right link_form" href="'.$input_pagina_url.'" target="_blank" '.$tooltip_.' title="'.$input_pagina_title.'"><i class="'.$input_pagina_icon.'"></i> '.$input_pagina.'</a>';
				}
				//ICON IN INPUT
				if(!empty($icon_in_input)){
					$icon_in_input = '<span class="'.$icon_in_input.' form-control-feedback-left2"></span>';
					$class_in_input = 'pad-icon-left';
				}
				//INPUT PAGINA TITLE ADD
				if(!empty($input_pagina_add_title)){
					$input_pagina_add_title = $this->parametros_smarty_variaveis($input_pagina_add_title);
					$tooltip_1 = 'data-title="tooltip" data-placement="top"';
				}
				//INPUT PÁGINA ADD
				if(!empty($input_pagina_add_id)){
					$input_pagina_add = '{if $param_modal_add == ""}<a modal="" data-toggle="modal" data-target="#'.$input_pagina_add_id.'" class="pull-right add_modal" href="#0" target="_blank" '.$tooltip_1.' title="'.$input_pagina_add_title.'"><i class="fa fa-plus"></i> </a>{/if}';
				}

				return '
				<div class="'.$input_class_tamanho.'">
				   <div class="form-group remove-error-input has-feedback '.$class_in_input.'" id="input_error_'.$input_id.'">
					   <label for="'.$input_id.'">'.$label_for_texto.'</label> '.$title_help.' '.$input_pagina.' '.$input_pagina_add.'
					   <input '.$maxlength.' '.$tooltip.' type="'.$input_type.'" name="'.$input_name.'" class="form-control '.$input_class.'" id="'.$input_id.'" placeholder="'.$input_placeholder.'" title="'.$input_title.'" value="'.$input_value.'" '.$input_outros.' '.$input_disabled.'>
					   '.$icon_in_input.'
					   <span id="icon_error_'.$input_id.'" class="glyphicon glyphicon-remove form-control-feedback oculto-icone-error hidden_area" aria-hidden="true"></span>
				   </div>
				<label id="msg_erro_'.$input_id.'" class="control-label msg_erro_form" for="inputError"></label>
			   </div>
			   ';
			}
		}

		//===========================================================
		//RETORNO O HTML INPUT HIDDEN
		function monto_html_input_hidden(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$input_id    = $array['input_id'];
				$input_name  = $array['input_name'];
				$input_value = $array['input_value'];

				//VALOR
				if(!empty($input_value)){
					$input_value = $this->parametros_smarty_variaveis($input_value);
				}

				return '
				<input type="hidden" name="'.$input_name.'"  id="'.$input_id.'"  value="'.$input_value.'" >
			   ';
			}
		}

		//===========================================================
		//RETORNO O HTML INPUT CHECKBOX
		function monto_html_input_checkbox(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
                $input_class_tamanho   = $array['input_class_tamanho'];
                $label_for_texto       = $array['label_for_texto'];
                $input_id              = $array['input_id'];
                $input_options         = array();
                (array) $input_options = (array) $array['input_options'];

				$monto .= '<div class="form-group '.$input_class_tamanho.' remove-error-input has-feedback" id="input_error_'.$input_id.'">';
				$monto .= '<label> '.$label_for_texto.'';
					if(is_array($input_options)){
						for ($i=0; $i <count($input_options) ; $i++) {
							if(!empty($input_options[$i]['input_title'])){
								$input_title = $this->parametros_smarty_variaveis($input_options[$i]['input_title']);
								$tooltip = 'data-title="tooltip" data-placement="top"';
							}
							if(!empty($input_options[$i]['input_value'])){
								$input_value = $this->parametros_smarty_variaveis($input_options[$i]['input_value']);
							}
							$monto .= '
							<label style="margin-right:15px;margin-bottom:8px;" for="'.$input_options[$i]['input_id'].'" title="'.$input_title.'" '.$tooltip.'>
								<input '.$input_options[$i]['input_checked'].' type="checkbox" name="'.$input_options[$i]['input_name'].'" class="mostra_area '.$input_options[$i]['input_class'].'" id="'.$input_options[$i]['input_id'].'" value="'.$input_options[$i]['input_value'].'"> '.$input_options[$i]['input_texto_checkbox'].'
							</label>';
						}
					}
				$monto .= '</label>';
				$monto .= '</div>';
				return $monto;
			}
		}

		//===========================================================
		//RETORNO O HTML INPUT RADIOBOX
		function monto_html_input_radiobox(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
                $input_class_tamanho   = $array['input_class_tamanho'];
                $label_for_texto       = $array['label_for_texto'];
                $input_id              = $array['input_id'];
                $input_options         = array();
                (array) $input_options = (array) $array['input_options'];

				$monto .= '<div class="form-group '.$input_class_tamanho.' remove-error-input has-feedback" id="input_error_'.$input_id.'">';
				$monto .= '<label> '.$label_for_texto.'';
					if(is_array($input_options)){
						for ($i=0; $i <count($input_options) ; $i++) {
							if(!empty($input_options[$i]['input_title'])){
								$input_title = $this->parametros_smarty_variaveis($input_options[$i]['input_title']);
								$tooltip = 'data-title="tooltip" data-placement="top"';
							}
							if(!empty($input_options[$i]['input_value'])){
								$input_value = $this->parametros_smarty_variaveis($input_options[$i]['input_value']);
							}
							$monto .= '
							<label for="'.$input_options[$i]['input_id'].'" title="'.$input_title.'" '.$tooltip.'>
								<input '.$input_options[$i]['input_checked'].' type="radio" name="'.$input_options[$i]['input_name'].'" class="mostra_area '.$input_options[$i]['input_class'].'" id="'.$input_options[$i]['input_id'].'" value="'.$input_options[$i]['input_value'].'"> '.$input_options[$i]['input_texto_radiobox'].'
							</label>';
						}
					}
				$monto .= '</label>';
				$monto .= '</div>';
				return $monto;
			}
		}

		//===========================================================
		//RETORNO O HTML INPUT FILE
		function monto_html_input_file(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$input_multiple           = $array['input_multiple'];
				$input_class_tamanho      = $array['input_class_tamanho'];
				$input_id                 = $array['input_id'];
				$label_for_texto          = $array['label_for_texto'];
				$input_name               = $array['input_name'];
				$input_class              = $array['input_class'];
				$input_placeholder        = $array['input_placeholder'];
				$input_title              = $array['input_title'];
				$input_value              = $array['input_value'];
				$input_outros             = $array['input_outros'];
				$input_disabled           = $array['input_disabled'];
				$title_help               = $array['title_help'];
				$editor_img_online        = $array['editor_img_online'];

				//TITLE
				if(!empty($input_title)){
					$input_title = $this->parametros_smarty_variaveis($input_title);
					$tooltip = ' data-title="tooltip" data-placement="top" ';
				}
				//VALOR
				if(!empty($input_value)){
					$input_value = $this->parametros_smarty_variaveis($input_value);
				}
				//FILE MULTIPLO
				if($input_multiple == true){
					$input_multiple = "[]";
					$param_multiple = "multiple";
				}else{
					$input_multiple = "";
					$param_multiple = "";
				}
				if(!empty($title_help)){
					$title_help = 'data-toggle="popover" data-content="'.$title_help.'" title="AJUDA!" data-placement="top"';
				}
				if(!empty($editor_img_online)){
					$editor_img_online = '<a href="https://pixlr.com/editor/" target="_blank"><i title="Abrir o Editor de Imagens Pixlr (Nova Janela)" class="fa fa-pencil pull-right font-media"></i></a>';
				}

				return '
				<div class="'.$input_class_tamanho.'">
				   <div class="form-group remove-error-input has-feedback" id="input_error_'.$input_id.'">
					   <label for="'.$input_id.'">'.$label_for_texto.'</label> '.$editor_img_online.'
					   <input '.$title_help.' type="file" name="'.$input_name.$input_multiple.'" class="form-control '.$input_class.'" id="'.$input_id.'" placeholder="'.$input_placeholder.'" value="'.$input_value.'" '.$input_outros.' '.$input_disabled.' '.$param_multiple.'>
						<span id="icon_error_'.$input_id.'" class="glyphicon glyphicon-remove form-control-feedback oculto-icone-error hidden_area" aria-hidden="true"></span>
				   </div>
				   	<label id="msg_erro_'.$input_id.'" class="control-label msg_erro_form" for="inputError"></label>
			   </div>
			   ';
			}
		}

		//===========================================================
		//RETORNO O HTML LISTAGEM DE ARQUIVOS
		function monto_html_list_arquivos(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$status_listagem_arquivos = $array['status_listagem_arquivos'];
				$param_controle_list_arq  = $array['param_controle_list_arq'];
				$url_listagem_arquivos    = $array['url_listagem_arquivos'];
				$tipo_listagem_arquivos   = $array['tipo_listagem_arquivos'];
				$titulo_listagem_arquivos = $array['titulo_listagem_arquivos'];
				$icone_doc                = $array['icone_doc'];
				$icone_img                = $array['icone_img'];
				$campo_input_file         = $array['campo_input_file'];
				$upload_multiplo          = $array['upload_multiplo'];

				//LISTAGEM DE ARQUIVOS
				if($status_listagem_arquivos == true){
					$listagem_arquivos = '
					<div class="row" upload_multiplo="'.$upload_multiplo.'" campo="'.$campo_input_file.'" icone="'.$icone_doc.'" icone2="'.$icone_img.'" titulo="'.$titulo_listagem_arquivos.'" id="carrego_listagem'.$param_controle_list_arq.'" url="'.$url_listagem_arquivos.'" tipo="'.$tipo_listagem_arquivos.'"></div>
					';
				}

				return ''.$listagem_arquivos.'';
			}
		}

		//===========================================================
		//RETORNO O HTML TEXTAREA
		function monto_html_textarea(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$input_class_tamanho = $array['input_class_tamanho'];
				$input_id            = $array['input_id'];
				$label_for_texto     = $array['label_for_texto'];
				$input_name          = $array['input_name'];
				$input_class         = $array['input_class'];
				$input_placeholder   = $array['input_placeholder'];
				$input_title         = $array['input_title'];
				$input_value         = $array['input_value'];
				$input_outros        = $array['input_outros'];
				$input_disabled      = $array['input_disabled'];
				$input_rows          = $array['input_rows'];
				$title_help          = $array['title_help'];

				//TITLE
				if(!empty($input_title)){
					$input_title = $this->parametros_smarty_variaveis($input_title);
					$tooltip = 'data-title="tooltip" data-placement="top"';
				}
				//VALOR
				if(!empty($input_value)){
					$input_value = $this->parametros_smarty_variaveis($input_value);
				}
				//TITLE HELP
				if(!empty($title_help)){
					$title_help = '<i data-toggle="popover" data-content="'.$title_help.'" title="AJUDA!" data-placement="left" class="fa fa-question-circle pull-right font-media"></i>';
				}

				return '
				<div class="'.$input_class_tamanho.'">
					<div class="form-group remove-error-input has-feedback" id="input_error_'.$input_id.'">
						<label for="'.$input_id.'">'.$label_for_texto.'</label> '.$title_help.'
						<textarea '.$tooltip.' name="'.$input_name.'" class="form-control '.$input_class.'" id="'.$input_id.'" placeholder="'.$input_placeholder.'" title="'.$input_title.'" rows="'.$input_rows.'" '.$input_outros.' '.$input_disabled.'>'.$input_value.'</textarea>
						<span id="icon_error_'.$input_id.'" class="glyphicon glyphicon-remove form-control-feedback oculto-icone-error hidden_area" aria-hidden="true"></span>
					</div>
					<label id="msg_erro_'.$input_id.'" class="control-label msg_erro_form" for="inputError"></label>
				</div>
			   ';
			}
		}

		//===========================================================
		//RETORNO O HTML TEXTAREA INLINE
		function monto_html_textarea_inline(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$input_class_tamanho = $array['input_class_tamanho'];
				$input_id            = $array['input_id'];
				$label_for_texto     = $array['label_for_texto'];
				$input_class         = $array['input_class'];
				$input_title         = $array['input_title'];
				$input_value         = $array['input_value'];
				$input_outros        = $array['input_outros'];
				$input_disabled      = $array['input_disabled'];
				$title_help          = $array['title_help'];

				//TITLE
				if(!empty($input_title)){
					$input_title = $this->parametros_smarty_variaveis($input_title);
					$tooltip = 'data-title="tooltip" data-placement="top"';
				}
				//VALOR
				if(!empty($input_value)){
					$input_value = $this->parametros_smarty_variaveis($input_value);
				}
				//TITLE HELP
				if(!empty($title_help)){
					$title_help = '<i data-toggle="popover" data-content="'.$title_help.'" title="AJUDA!" data-placement="left" class="fa fa-question-circle pull-right font-media"></i>';
				}

				return '
				<div class="'.$input_class_tamanho.'">
				   <div class="form-group remove-error-input" id="input_error_'.$input_id.'">
					   <label for="'.$input_id.'">'.$label_for_texto.'</label> '.$title_help.'
					   <div '.$tooltip.' id="'.$input_id.'" class="cke_editable '.$input_class.'" title="'.$input_title.'" '.$input_disabled.' '.$input_outros.'>'.$input_value.'</div>
				   </div>
				   <label id="msg_erro_'.$input_id.'" class="control-label msg_erro_form" for="inputError"></label>
			   </div>
			   ';
			}
		}

		//===========================================================
		//RETORNO O HTML SELECT
		function monto_html_select(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$input_class_tamanho    = $array['input_class_tamanho'];
				$input_id               = $array['input_id'];
				$label_for_texto        = $array['label_for_texto'];
				$input_name             = $array['input_name'];
				$input_class            = $array['input_class'];
				$input_title            = $array['input_title'];
				$title_help             = $array['title_help'];
				$input_value            = $array['input_value'];
				$input_value            = $array['input_value'];
				$input_outros           = $array['input_outros'];
				$input_disabled         = $array['input_disabled'];
				$input_text_select      = $array['input_text_select'];
				$input_options = array();
				(array) $input_options  = (array) $array['input_options'];
				$input_multiple         = $array['input_multiple'];
				$input_pagina           = $array['input_pagina'];
				$input_pagina_title     = $array['input_pagina_title'];
				$input_pagina_url       = $array['input_pagina_url'];
				$input_pagina_add_title = $array['input_pagina_add_title'];
				$input_pagina_add_id    = $array['input_pagina_add_id'];

				//TITLE
				if(!empty($input_title)){
					$input_title = $this->parametros_smarty_variaveis($input_title);
					$tooltip = 'data-title="tooltip" data-placement="top"';
				}
				//VALOR
				if(!empty($input_value)){
					$input_value = $this->parametros_smarty_variaveis($input_value);
				}
				//INPUT TEXT SELECT
				if(!empty($input_text_select)){
					$input_text_select = $this->parametros_smarty_variaveis($input_text_select);
				}

				//SELECT MULTIPLE
				if(!empty($input_multiple)){
					$input_multiple = "multiple";
					$param_mult     = "[]";
					$param_mult2    = "";
					$param_padrao = '';
				}else{
					$input_multiple = "";
					$param_mult     = "";
					$param_mult2    = "";
					$param_padrao = '<option value="select" '.$param_mult2.'>'.$input_text_select.'</option><optgroup label="----------"></optgroup>';

				}

				//INPUT PAGINA TITLE
				if(!empty($input_pagina_title)){
					$input_pagina_title = $this->parametros_smarty_variaveis($input_pagina_title);
					$tooltip_ = 'data-title="tooltip" data-placement="top"';
				}
				//INPUT PAGINA
				if(!empty($input_pagina_title)){
					$input_pagina = '{if $param_modal_add == ""}<a style="margin-left:5px;" class="pull-right link_form" href="'.$input_pagina_url.'" target="_blank" '.$tooltip_.' title="'.$input_pagina_title.'"><i class="fa fa-list"></i> '.$input_pagina.'</a>{/if}';
				}

				//INPUT PAGINA TITLE ADD
				if(!empty($input_pagina_add_title)){
					$input_pagina_add_title = $this->parametros_smarty_variaveis($input_pagina_add_title);
					$tooltip_1 = 'data-title="tooltip" data-placement="top"';
				}
				//INPUT PÁGINA ADD
				if(!empty($input_pagina_add_id)){
					$input_pagina_add = '{if $param_modal_add == ""}<a modal="" data-toggle="modal" data-target="#'.$input_pagina_add_id.'" class="pull-right add_modal" href="#0" target="_blank" '.$tooltip_1.' title="'.$input_pagina_add_title.'"><i class="fa fa-plus"></i> </a>{/if}';
				}
				if(!empty($title_help)){
					$title_help = '<i data-toggle="popover" data-content="'.$title_help.'" title="AJUDA!" data-placement="left" class="fa fa-question-circle pull-right font-media"></i>';
				}

				$monto .= '
				<div class="'.$input_class_tamanho.'">
				<div class="form-group remove-error-input has-feedback" id="input_error_'.$input_id.'">
					<label for="'.$input_id.'">'.$label_for_texto.' </label> '.$title_help.' '.$input_pagina.' '.$input_pagina_add.'
					<select '.$tooltip.' '.$input_multiple.' name="'.$input_name.$param_mult.'" class="form-control '.$input_class.'" id="'.$input_id.'" title="'.$input_title.'" '.$input_outros.' '.$input_disabled.'>
						'.$param_padrao.'    ';
						if(is_array($input_options)){
							for ($i=0; $i <count($input_options) ; $i++) {
								if($input_value == $input_options[$i]['id']){
									$monto .= '<option value="'.$input_options[$i]['id'].'" selected>'.$input_options[$i]['value'].'</option>';
								}else{
									$monto .= '<option value="'.$input_options[$i]['id'].'">'.$input_options[$i]['value'].'</option>';
								}
							}
						}
					$monto .= '
					</select><span id="icon_error_'.$input_id.'" class="glyphicon glyphicon-remove form-control-feedback oculto-icone-error hidden_area" aria-hidden="true"></span>
					<input type="hidden" class="clear_hidden" name="'.$input_name.'_id" id="'.$input_id.'_id"  value="">
					</div>
					<label id="msg_erro_'.$input_id.'" class="control-label msg_erro_form" for="inputError"></label>
				 </div>
			   ';
			   return $monto;
			}
		}

		//===========================================================
		//RETORNO O HTML MODAL DE ADICIONAR
		function monto_html_modal_add(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
                $input_id_area     = $array['input_id_area'];
                $url_pagina        = $array['url_pagina'];

				//TITLE
				if(!empty($input_title)){
					$input_title = $this->parametros_smarty_variaveis($input_title);
					$tooltip = ' data-title="tooltip" data-placement="top" ';
				}

				return '
				<div class="modal fade" id="'.$input_id_area.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
				  <div class="modal-dialog" role="document">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					  </div>
					  <div id="'.$input_id_area.'_area" url="'.$url_pagina.'/modal" class="modal-body modais">
					  </div>
					</div>
				  </div>
				</div>
			   ';
			}
		}

		//===========================================================
		//RETORNO O BTN
		function monto_html_btn_externo(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$href       = $array['href'];
				$icon_class = $array['icon_class'];
				$class      = $array['class'];
				$id         = $array['id'];
				$texto      = $array['texto'];
				$outros     = $array['outros'];
				$target     = $array['target'];

				if(!empty($target)){
					$target = 'target="'.$target.'"';
				}

				return '
				<a href="'.$href.'" id="'.$id.'" class="'.$class.'" '.$target.' '.$outros.' param_value=""><i class="'.$icon_class.'"></i> '.$texto.'</a>
			   ';
			}
		}

		//===========================================================
		//RETORNO O HTML ÁREA DESCRIÇÃO
		function monto_html_area_descricao(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$area_descricao_icon_class = $array['area_descricao_icon_class'];
				$area_descricao            = $array['area_descricao'];
				$class                     = $array['class'];

				//VALOR
				if(!empty($area_descricao)){
					$area_descricao = $this->parametros_smarty_variaveis($area_descricao);
				}

				return '
				<div class="col-md-12 area-descricao '.$class.'">
					<h3 class="subtitulo"><i class="'.$area_descricao_icon_class.'"></i> '.$area_descricao.'</h3>
					<div class="divider-sutitulo"></div>
				  </div>
			   ';
			}
		}

		//===========================================================
		//RETORNO O HTML ÁREA FORMS ADD EDD
		function monto_html_area_forms(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$titulo       = $array[$i]['titulo'];
				$icone_titulo = $array[$i]['icone_titulo'];
				$conteudo     = $array[$i]['conteudo'];
				$class_area   = $array[$i]['class_area'];

				//ICONE TITULO
				if(!empty($icone_titulo)){
					$icone_titulo = '<i class="'.$icone_titulo.'"></i> ';
				}

				$td_montado .= "<td {$class}{$outros}>{$td}</td>";
				$area_montada .= '
				<div class="row '.$class_area.'">
					<div class="col-md-12">
						<div class="box box-default">
							  <div class="box-header with-border">
									<h3 class="box-title">'.$icone_titulo.$titulo.'</h3>
							  </div>
							 <div class="box-body">
				';
				$area_montada .= $conteudo;
				$area_montada .= '
				</div></div></div></div>
				';
			}
			//MONTO E RETORNO
			return '{strip}'.$area_montada;
		}

		//===========================================================
		//RETORNO O HTML FORMS
		function monto_html_forms(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$area_montada .= $array[$i]['conteudo'];
			}
			//MONTO E RETORNO
			return '{strip}'.$area_montada;
		}

		//===========================================================
		//MONTO ÁREA COM MENSAGEM INFORMATIVA
		function monto_html_mensagem_informativa(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
				$id       = $array['id'];
				$class    = $array['class'];
				$titulo   = $array['titulo'];
				$mensagem = $array['mensagem'];
				$tipo     = $array['tipo'];

				if($tipo == 'danger'){
					$monto .= '
					<div id="'.$id.'" class="m-b-25 alert alert-danger alert-dismissible '.$class.'">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> '.$titulo.'</h4>
					'.$mensagem.'
					</div>';
				}
				if($tipo == 'info'){
					$monto .= '
					<div id="'.$id.'" class="m-b-25 alert alert-info alert-dismissible '.$class.'">
	                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                <h4><i class="icon fa fa-info"></i> '.$titulo.'</h4>
	                '.$mensagem.'
	              	</div>';
				}
				if($tipo == 'warning'){
					$monto .= '
					<div id="'.$id.'" class="m-b-25 alert alert-warning alert-dismissible '.$class.'">
	                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                <h4><i class="icon fa fa-warning"></i> '.$titulo.'</h4>
	                '.$mensagem.'
	                </div>';
				}
				if($tipo == 'success'){
					$monto .= '
					<div id="'.$id.'" class="m-b-25 alert alert-success alert-dismissible '.$class.'">
	                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                <h4><i class="icon fa fa-check"></i> '.$titulo.'</h4>
	                '.$mensagem.'
	                </div>';
				}
				return $monto;
			}
		}

		//===========================================================
		//MONTO ÁREA DE MOSTRO AREA RADIOBOX
		function monto_html_input_radiobox_area_hide(){
			$array = $this->get_array();
			for ($i=0; $i <count($array) ; $i++) {

				//PEGO OS DADOS
                $input_class_tamanho = $array['input_class_tamanho'];
                $label_for_texto     = $array['label_for_texto'];
                $input_id            = $array['input_id'];
                $input_options       = array();
				(array) $input_options  = (array) $array['input_options'];

				$monto .= '<div class="form-group '.$input_class_tamanho.' remove-error-input has-feedback" id="input_error_'.$input_id.'">';
				$monto .= '<label> '.$label_for_texto.'';
					if(is_array($input_options)){
						for ($i=0; $i <count($input_options) ; $i++) {
							if(!empty($input_options[$i]['input_title'])){
								$input_title = $this->parametros_smarty_variaveis($input_options[$i]['input_title']);
								$tooltip = 'data-title="tooltip" data-placement="top"';
							}
							if(!empty($input_options[$i]['input_value'])){
								$input_value = $this->parametros_smarty_variaveis($input_options[$i]['input_value']);
							}
							$monto .= '
							<label for="'.$input_options[$i]['input_id'].'" title="'.$input_title.'" '.$tooltip.'>
								<input '.$input_options[$i]['input_checked'].' type="radio" name="'.$input_options[$i]['input_name'].'" class="mostra_area '.$input_options[$i]['input_class'].'" show_area="'.$input_options[$i]['campo_show'].'" id="'.$input_options[$i]['input_id'].'" value="'.$input_options[$i]['input_value'].'"> '.$input_options[$i]['input_texto_radiobox'].'
							</label>';
						}
					}
				$monto .= '</label>';
				$monto .= '</div>';
				return $monto;
			}
		}


	}
