<?php

include_once ("../service/crud.php");
include_once ("../service/limpaDados.php");

$acesso = new crud();
$limpa = new limpaDados();

$login = "alexandre";
$senha = "123";

/*************para inserir****************/
//$cadastra->setFields("login, senha");
//$cadastra->setDados("'nero', '12345678'");
//$cadastra->insert();
/*************para deletar****************/
//$cadastra->setId("id");
//$cadastra->setValueId("5");
//$cadastra->delete();
/*************para atualizar****************/
//$cadastra->setFields("login='dan', senha= '123' ");
//$cadastra->setId("id");
//$cadastra->setValueId("1");
//$cadastra->update();

/***********para fazer busca********************/
$acesso -> setTablet("acessos");
$acesso -> setFields("ace_login, ace_senha, ace_nome");
$acesso -> setCondicao("ace_login = '$login' AND ace_senha = '$senha'");
$query = $acesso -> select();

while ($linha = mysql_fetch_array($query)) {
	
	$nome = $linha['ace_nome'];
	$nome = $limpa -> limpadados("Álexandre Estima Seabra");
	
	echo $nome;
}
/*******pegando a quantidade de dados cadastrado no campo**********/
//$cadastra->setId("id");
//echo $cadastra->getTotalData();
//echo $cadastra->somaValores();

?>