<?php

include_once("constantes.bd.php");

abstract class conexao {
	// variaveis para a conexao com o banco de dados
	protected $host, $user, $pass, $dba, $conn, $qr, $data, $totalFildes;
	//metodo que inicia automaticamento o metodo de connetion
	public function __construct() {
		$this -> host = HOST;
		$this -> user = USER;
		$this -> pass = PASS;
		$this -> dba = DBA;
		self::connect();
		//usando para executar automaticamente um metodo
	}
	
	//metodo para a conctar ao banco de dados
	protected function connect() {
		$this -> conn = mysql_connect($this -> host, $this -> user, $this -> pass);
		$this -> dba = mysql_select_db($this -> dba, $this -> conn);
	}

	//metodo para executar comandos SQL
	protected function exeSQL($sql) {
		$this -> qr = mysql_query($sql);
		return $this -> qr;
	}

	//metodo para executar a lista do banco de dados
	protected function listQr($qr) {
		$this -> data = mysql_fetch_assoc($qr);
		return $this -> data;
	}

	//metodo para listar a quantidade de dados do banco de dados
	protected function countData($qr) {
		$this -> totalFildes = mysql_num_rows($qr);
		return $this -> totalFildes;
	}

	//metodo para pegar os campos
	protected function numObj($qr) {
		$this -> totalFildes = mysql_fetch_object($qr);
		return $this -> totalFildes;
	}
}
?>