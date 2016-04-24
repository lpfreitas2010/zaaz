<?php

	/**
	* Classe que realiza o backup do BD e de pastas
	*
	* @author Fernando
	* @version 1.0
	**/

	//=================================================================
	//INCLUO ESTRUTURA
	require_once (dirname(dirname((dirname(__FILE__))))).'/libs/core/core.php';
	//=================================================================

	//Classe
	class backup  {


		//*********************************************************************************
		//BACKUP DO BANCO DE DADOS
		//*********************************************************************************
		function backup_database_tables(){

			//INSTANCIO CORE
			$core = new core();

			//SERVIDOR
			if($core->get_config('servidor_ativo') === "servidor"){
				$dbuser  = $core->get_config('default','user_db');
				$dbpass  = $core->get_config('default','password_db');
				$dbhost  = $core->get_config('default','host_db');
				$dbname  = $core->get_config('default','database_db');
			}
			//LOCAL
			if($core->get_config('servidor_ativo') === "local"){
				$dbuser  = $core->get_config('local','user_db');
				$dbpass  = $core->get_config('local','password_db');
				$dbhost  = $core->get_config('local','host_db');
				$dbname  = $core->get_config('local','database_db');
			}

		   // db connect
		   $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

		   // file header stuff
		   $output = "-- PHP MySQL Dump\n--\n";
		   $output .= "-- Host: $dbhost\n";
		   $output .= "-- Generated: " . date("r", time()) . "\n";
		   $output .= "-- PHP Version: " . phpversion() . "\n\n";
		   $output .= "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n\n";
		   $output .= "--\n-- Database: `$dbname`\n--\n";

		   // get all table names in db and stuff them into an array
		   $tables = array();
		   $stmt = $pdo->query("SHOW TABLES");
		   while($row = $stmt->fetch(PDO::FETCH_NUM)){
			   $tables[] = $row[0];
		   }

		   // process each table in the db
		   foreach($tables as $table){
			   $fields = "";
			   $sep2 = "";
			   $output .= "\n-- " . str_repeat("-", 60) . "\n\n";
			   $output .= "--\n-- Table structure for table `$table`\n--\n\n";
			   // get table create info
			  // $stmt = $pdo->query("SHOW CREATE TABLE $table");
			   //$row = $stmt->fetch(PDO::FETCH_NUM);
			   //$output.= $row[1].";\n\n";
			   // get table data
			   $output .= "--\n-- Dumping data for table `$table`\n--\n\n";
			   $stmt = $pdo->query("SELECT * FROM $table");
			   while($row = $stmt->fetch(PDO::FETCH_OBJ)){
				   // runs once per table - create the INSERT INTO clause
				   if($fields == ""){
					   $fields = "INSERT INTO `$table` (";
					   $sep = "";
					   // grab each field name
					   foreach($row as $col => $val){
						   $fields .= $sep . "`$col`";
						   $sep = ", ";
					   }
					   $fields .= ") VALUES";
					   $output .= $fields . "\n";
				   }
				   // grab table data
				   $sep = "";
				   $output .= $sep2 . "(";
				   foreach($row as $col => $val){
					   // add slashes to field content
					   $val = addslashes($val);
					   // replace stuff that needs replacing
					   $search = array("\'", "\n", "\r");
					   $replace = array("''", "\\n", "\\r");
					   $val = str_replace($search, $replace, $val);
					   $output .= $sep . "'$val'";
					   $sep = ", ";
				   }
				   // terminate row data
				   $output .= ")";
				   $sep2 = ",\n";
			   }
			   // terminate insert data
			   $output .= ";\n";
		   }
		   // output file to browser
		   header('Content-Description: File Transfer');
		   header('Content-type: application/octet-stream');
		   header('Content-Disposition: attachment; filename=' . $dbname . '.sql');
		   header('Content-Transfer-Encoding: binary');
		   header('Content-Length: ' . strlen($output));
		   header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		   header('Expires: 0');
		   header('Pragma: public');
		   echo $output;
		 }


		 //*********************************************************************************
	     //BACKUP DE DIRETORIOS E ARQUIVOS
	     //*********************************************************************************
		 function backup_paste_files($pasta){
		 	set_time_limit(0);
		 	$zip          = new ZipArchive();
			$rootPath     = realpath($pasta.'/');
			$nome_arquivo = $pasta.'/backup_files_'.md5(date('d/m/y H:i:s')).'.zip';
			$zip->open($nome_arquivo, ZipArchive::CREATE | ZipArchive::OVERWRITE);
			$files = new RecursiveIteratorIterator(
			    new RecursiveDirectoryIterator($rootPath),
			    RecursiveIteratorIterator::LEAVES_ONLY
			);
			foreach ($files as $name => $file){
			    if (!$file->isDir()){
			        $filePath     = $file->getRealPath();
			        $relativePath = substr($filePath, strlen($rootPath) + 1);
			        $zip->addFile($filePath, $relativePath);
			    }
			}
			$zip->close();
			//DOWNLOAD
			header("Content-type: application/zip");
			header("Content-length: " .filesize($nome_arquivo));
			header("Content-Disposition: attachment; filename=$nome_arquivo");
			header ("Pragma: no-cache");
            header("Expires: 0");
			readfile($nome_arquivo);
			unlink($nome_arquivo);
		 }


	}
