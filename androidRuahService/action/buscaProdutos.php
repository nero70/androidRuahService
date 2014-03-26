<?php
// nesta pagina esta usando o busca_lista.php
include_once ("../service/crud.php");

$produtos = new crud();
$nomeProduto = "cd";
$listaProdutos = array();

$produtos -> setTablet("produtos");
$produtos -> setFields("pro_descabv,pro_cod");
$produtos -> setCondicao("pro_descabv LIKE '%$nomeProduto%' LIMIT 0,14");
$query = $produtos -> select();

while ($linhaP = mysql_fetch_assoc($query)) {
	$pegaNome = $linhaP['pro_descabv'];

	$listaProdutos['produtos'][] = array("nome" => $pegaNome);
}

echo json_encode($listaProdutos);
?>