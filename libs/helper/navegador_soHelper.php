<?php

/*

VERSÃO:			1.2
AUTOR:			Moisés de Lima
ATUALIZAÇÃO:	03/01/2015
FUNÇÃO:			Detectar o navegador e sua versão e retornar se for solicitado.

*/

class navegador_so{

	public $UltimasVersoes	= array(
		'Firefox'				=> array('18', 'http://br.mozdev.org/firefox/download/'),
		'Chrome' 				=> array('30', 'https://www.google.com/intl/pt-BR/chrome/browser/?hl=pt-BR'),
		'Chromium'				=> array('30', 'https://www.google.com/intl/pt-BR/chrome/browser/?hl=pt-BR'),
		'Internet Explorer' 	=> array('10', 'http://windows.microsoft.com/pt-BR/internet-explorer/downloads/ie'),
		'Safari' 				=> array('4', 'http://appldnld.apple.com/Safari5/041-5487.20120509.INU8B/SafariSetup.exe'),
		'Opera' 				=> array('12', 'http://www.opera.com/download/')
	);

	public $user_agent;

	public $navegador 	= array(
		'navegador' => 'Desconhecido',
		'versao' => 'Desconhecido'
	);

	public $so 			= array(
			'desenvolvedor' => 'Desconhecido',
			'so' => 'Desconhecido',
			'versao' => 'Desconhecido'
		);

	public $mobile = false;

	function __construct(){

		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];

	}

	function detectaSo($user_agent = false){

		if($user_agent === false){
			$user_agent = $this->user_agent;
		}

		// SE FOR LINUX
		if(preg_match("|Linux|", $user_agent)){
			$this->so['desenvolvedor'] = 'Linux';
			$this->so['so'] = 'Linux';
			$this->so['versao'] = 'Desconhecido';
			$this->mobile = false;

			// SE FOR UBUNTU
			if(preg_match("|buntu|", $user_agent)){

				$this->so['so'] = 'Ubuntu';

				// TENTA ENCONTRAR VERSÃO
				preg_match("|buntu\/([0-9]+.[0-9]+)|", $user_agent, $preg_match_saida);

				// SE ENCONTRA VERSÃO
				if(isset($preg_match_saida[1])){
					$this->so['versao'] = $preg_match_saida[1];
				}

			}

			// SE FOR Debian
			if(preg_match("|Debian|", $user_agent)){
				$this->so['so'] = 'Debian';
			}
			// SE FOR Suse
			if(preg_match("|Suse|", $user_agent)){
				$this->so['so'] = 'Suse';
			}
		}

		// SE FOR DA MICROSOFT
		if(preg_match("|indows|", $user_agent)){

			$this->so['desenvolvedor'] = 'Microsoft';
			$this->so['so'] = 'Windows';
			$this->so['versao'] = 'Windows';
			$this->mobile = false;

			// WINDOWS 3.1
			if(preg_match("|Windows 3.1|", $user_agent)){
				$this->so['versao'] = 'Windows 3.1';
			}
			// WINDOWS 95
			if(preg_match("|Windows 95|", $user_agent)){
				$this->so['versao'] = 'Windows 95';
			}
			// WINDOWS 98
			if(preg_match("|Windows 98|", $user_agent)){
				$this->so['versao'] = 'Windows 98';
			}
			// WINDOWS 2000
			if(preg_match("|NT 5.0|", $user_agent) or preg_match("|Windows 2000|", $user_agent)){
				$this->so['versao'] = 'Windows 2000';
			}
			// WINDOWS ME
			if(preg_match("|Windows ME|", $user_agent)){
				$this->so['versao'] = 'Windows ME';
			}
			// WINDOWS CE
			if(preg_match("|Windows CE|", $user_agent)){
				$this->so['versao'] = 'Windows CE';
			}
			// WINDOWS XP
			if(preg_match("|NT 5.1|", $user_agent) or preg_match("|NT 5.2|", $user_agent) or preg_match("|Windows XP|", $user_agent)){
				$this->so['versao'] = 'Windows XP';
			}
			// Windows Vista
			if(preg_match("|NT 6.0|", $user_agent)){
				$this->so['versao'] = 'Windows Vista';
			}
			// Windows 7
			if(preg_match("|NT 6.1|", $user_agent)){
				$this->so['versao'] = 'Windows 7';
			}
			// Windows 8
			if(preg_match("|NT 6.2|", $user_agent)){
				$this->so['versao'] = 'Windows 8';
			}
			// Windows 8.1
			if(preg_match("|NT 6.3|", $user_agent)){
				$this->so['versao'] = 'Windows 8.1';
			}
			// Windows 10
			if(preg_match("|NT 10.0|", $user_agent)){
				$this->so['versao'] = 'Windows 10';
			}

		}

		// SE FOR APPLE Macintosh
		if(preg_match("|Macintosh|", $user_agent)){
			$this->so['desenvolvedor'] = 'Apple';
			$this->so['so'] = 'Mac';
			$this->so['versao'] = 'Desconhecido';
			$this->mobile = false;
		}

		// SE FOR ANDROID
		if(preg_match("|Android|", $user_agent)){
			$this->so['desenvolvedor'] = 'Google';
			$this->so['so'] = 'Android';
			$this->so['versao'] = 'Desconhecido';
			$this->mobile = true;

			// ANDROID 1.6
			if(preg_match("|Android 1.6|", $user_agent)){
				$this->so['versao'] = 'Android 1.6';
			}
			// ANDROID 2.1
			if(preg_match("|Android 2.1|", $user_agent)){
				$this->so['versao'] = 'Android 2.1';
			}
			// ANDROID 2.2
			if(preg_match("|Android 2.2|", $user_agent)){
				$this->so['versao'] = 'Android 2.2';
			}
			// ANDROID 2.3
			if(preg_match("|Android 2.3|", $user_agent)){
				$this->so['versao'] = 'Android 2.3';
			}
			// ANDROID 3.0
			if(preg_match("|Android 3.0|", $user_agent)){
				$this->so['versao'] = 'Android 3.0';
			}
			// ANDROID 4.0
			if(preg_match("|Android 4.0|", $user_agent)){
				$this->so['versao'] = 'Android 4.0';
			}
			// ANDROID 4.1
			if(preg_match("|Android 4.1|", $user_agent)){
				$this->so['versao'] = 'Android 4.1';
			}
			// ANDROID 4.4
			if(preg_match("|Android 4.4|", $user_agent)){
				$this->so['versao'] = 'Android 4.4';
			}
			// ANDROID 5.0
			if(preg_match("|Android 5.0|", $user_agent)){
				$this->so['versao'] = 'Android 5.0';
			}
		}

		// SE FOR WINDOWS PHONE
		if(preg_match("|Windows Phone 8.1|", $user_agent)){
			$this->so['desenvolvedor'] = 'Microsoft';
			$this->so['so'] = 'Windows Phone';
			$this->so['versao'] = 'Windows Phone 8.1';
			$this->mobile = true;
		}elseif(preg_match("|Windows Phone 10|", $user_agent)){
			$this->so['desenvolvedor'] = 'Microsoft';
			$this->so['so'] = 'Windows Phone';
			$this->so['versao'] = 'Windows Phone 10';
			$this->mobile = true;
		}elseif(preg_match("|Windows Phone 8|", $user_agent)){
			$this->so['desenvolvedor'] = 'Microsoft';
			$this->so['so'] = 'Windows Phone';
			$this->so['versao'] = 'Windows Phone 8';
			$this->mobile = true;
		}elseif(preg_match("|Windows Phone 7.5|", $user_agent)){
			$this->so['desenvolvedor'] = 'Microsoft';
			$this->so['so'] = 'Windows Phone';
			$this->so['versao'] = 'Windows Phone 7.5';
			$this->mobile = true;
		}elseif(preg_match("|Windows Phone OS 7.5|", $user_agent)){
			$this->so['desenvolvedor'] = 'Microsoft';
			$this->so['so'] = 'Windows Phone';
			$this->so['versao'] = 'Windows Phone 7.5';
			$this->mobile = true;
		}elseif(preg_match("|Windows Phone 7|", $user_agent)){
			$this->so['desenvolvedor'] = 'Microsoft';
			$this->so['so'] = 'Windows Phone';
			$this->so['versao'] = 'Windows Phone 7';
			$this->mobile = true;
		}elseif(preg_match("|Windows Phone OS 7|", $user_agent)){
			$this->so['desenvolvedor'] = 'Microsoft';
			$this->so['so'] = 'Windows Phone';
			$this->so['versao'] = 'Windows Phone 7';
			$this->mobile = true;
		}

		// SE FOR IPHONE
		if(preg_match("|iPhone|", $user_agent)){
			$this->so['desenvolvedor'] = 'Apple';
			$this->so['so'] = 'iPhone';
			$this->so['versao'] = 'Desconhecido';
			$this->mobile = true;
		}

		// SE FOR IPAD
		if(preg_match("|iPad|", $user_agent)){
			$this->so['desenvolvedor'] = 'Apple';
			$this->so['so'] = 'iPad';
			$this->so['versao'] = 'Desconhecido';
			$this->mobile = true;
		}

		// SE FOR IPOD
		if(preg_match("|iPod|", $user_agent)){
			$this->so['desenvolvedor'] = 'Apple';
			$this->so['so'] = 'iPod';
			$this->so['versao'] = 'Desconhecido';
			$this->mobile = true;
		}

		return $this;

	}

	function detectaNavegador($user_agent = false){

		if($user_agent === false){
			$user_agent = $this->user_agent;
		}

		// PARA INTERNET EXPLORER
		if(preg_match("|MSIE|", $user_agent) == true and preg_match("|Opera|", $user_agent) != true){

			$this->navegador['navegador'] = 'Internet Explorer';

			preg_match_all("|MSIE ([0-9.]+)|", $user_agent, $versao);

			if(!isset($versao[1][0])){
				$versao[1][0] = '0.1';
			}

			$this->navegador['versao'] = $versao[1][0];

			return $this;
		}

		// PARA INTERNET EXPLORER MOBILE
		if(preg_match("|IEMobile|", $user_agent) == true){

			$this->navegador['navegador'] = 'IEMobile';

			preg_match_all("|IEMobile\/([0-9.]+)|", $user_agent, $versao);

			if(!isset($versao[1][0])){
				$versao[1][0] = '0.1';
			}

			$this->navegador['versao'] = $versao[1][0];

			return $this;
		}

		// PARA FIREFOX
		if(preg_match("|Firefox|", $user_agent) == true and preg_match("|Opera|", $user_agent) != true){

			$this->navegador['navegador'] = 'Firefox';

			preg_match_all("|Firefox/([0-9.]+)|", $user_agent, $versao);

			if(!isset($versao[1][0])){
				$versao[1][0] = '0.1';
			}

			$this->navegador['versao'] = $versao[1][0];

			return $this;
		}

		// PARA GOOGLE CHROME
		if(preg_match("|Chrome|", $user_agent) == true and preg_match("|Chromium|", $user_agent) != true){

			$this->navegador['navegador'] = 'Chrome';

			preg_match_all("|Chrome/([0-9.]+)|", $user_agent, $versao);

			if(!isset($versao[1][0])){

				$versao[1][0] = '0.1';

			}

			$this->navegador['versao'] = $versao[1][0];

			return $this;

		}

		// PARA GOOGLE CHROMIUM
		if(preg_match("|Chromium|", $user_agent) == true){

			$this->navegador['navegador'] = 'Chromium';

			preg_match_all("|Chrome/([0-9.]+)|", $user_agent, $versao);

			if(!isset($versao[1][0])){

				$versao[1][0] = '0.1';

			}

			$this->navegador['versao'] = $versao[1][0];

			return $this;
		}

		// PARA OPERA
		if(preg_match("|Opera|", $user_agent) == true){

			// ENCONTRA VERSÃO
			preg_match_all("|Version/([0-9.]+)|", $user_agent, $versao);

			if(@$versao[1][0] < 10){
				preg_match_all("|Opera/([0-9.]+)|", $user_agent, $versao);
			}

			if(!@$versao[1][0]){

				preg_match_all("|Opera ([0-9.]+)|", $user_agent, $versao);

			}

			if(!@$versao[1][0]){
				$versao[1][0] = '0.1';
			}

			$this->navegador['navegador'] = 'Opera';

			$this->navegador['versao'] = $versao[1][0];

			return $this;

		}

		// PARA APPLE SAFARI
		if(preg_match("|Safari|", $user_agent) == true and preg_match("|Chrome|", $user_agent) != true){

			$this->navegador['navegador'] = 'Safari';

			// ENCONTRA VERSÃO
			preg_match("|Version/([0-9.]+)|", $user_agent, $preg_match_saida);

			// SE NÃO ENCONTRAR
			if(!isset($preg_match_saida[1])){
				$this->navegador['versao'] = 'desconhecida';
			}else{
				$this->navegador['versao'] = $preg_match_saida[1];
			}

			return $this;

		}

	}

}
