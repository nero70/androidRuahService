<?php

// incluindo a pagina de conexao
include_once ("conexao.php");

class crud extends conexao {

	private $sql, $table, $fields, $dados, $status, $id, $valueId, $condicao;

	//envia o nome da tabela a ser usada no class
	public function setTablet($t) {
		$this -> table = $t;
	}

	//envia os campos a ser usado na class
	public function setFields($f) {
		$this -> fields = $f;
	}

	//os dados ou valores a ser usado na class
	public function setDados($d) {
		$this -> dados = $d;
	}

	// passando o valor da condicao
	public function setCondicao($condicao) {
		$this -> condicao = $condicao;
	}

	//envia o campo de pesquisa normalmente o codigo
	public function setId($id) {
		$this -> id = $id;
	}

	//envia os dados a ser pesquisados ou cadastrados
	public function setValueId($valueId) {
		$this -> valueId = $valueId;
	}

	//mostra a mensagem na tela
	public function getStatus() {
		return $this -> status;
	}

	//metodo que inseri no banco de dados
	public function insert() {
		$this -> sql = "INSERT INTO $this->table ($this->fields) VALUES($this->dados) ";
		if(self::exeSQL($this -> sql)) {
			return true;
		}
	}

	//metodo para deletar valores no banco de dados
	public function delete() {
		$this -> sql = "DELETE FROM $this->table WHERE $this->id = '$this->valueId' ";
		if(self::exeSQL($this -> sql)) {
			return true;
		}
	}
	
	//metodo para atualizar os valores do banco de dados
	public function update() {
		$this -> sql = "UPDATE $this->table  SET $this->fields WHERE $this->condicao ";
		if(self::exeSQL($this -> sql)) {
			return true;
		}
	}

	// metodo para fazer pesquisas
	public function select() {
		$this -> sql = "SELECT $this->fields FROM $this->table WHERE $this->condicao";
		if ($this -> qr = self::exeSQL($this -> sql)) {
			return $this -> qr;
		}
	}
	
	// metodo para fazer pesquisa e retornar todos os dados
	public function selectAll() {
		$this -> sql = "SELECT $this->fields FROM $this->table";
		if ($this -> qr = self::exeSQL($this -> sql)) {
			return $this -> qr;
		}
	}
	
	// metodo para fazer pesquisas com varias tablelas
	public function selectMult() {
		$this -> sql = "SELECT DISTINCT $this->fields FROM $this->table WHERE $this->condicao";
		if ($this -> qr = self::exeSQL($this -> sql)) {
			return $this -> qr;
		}
	}
	
	// metodo que busca a quantidade de valores em um campo no banco de dados
	public function getTotalNum() {
		$this -> sql = "SELECT $this->fields FROM $this->table WHERE $this->condicao";
		$this -> qr = self::exeSQL($this -> sql);
		return self::countData($this -> qr);
	}
	
	// metodo para pegar um campo da tabela
	public function linhaObj() {
		return self::numObj($this -> qr);
	}
}
?>