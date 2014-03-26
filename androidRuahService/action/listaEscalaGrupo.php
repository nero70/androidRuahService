<?php

include_once("../service/crud.php");

$lista = new crud();
//$nomeProduto = $_POST['produto'];
$nomeProduto = "cd - 88";
$listaDados = array();
$valores;

$lista -> setTablet("produtos");
$lista -> setFields("pro_cod, pro_descabv");
$lista -> setCondicao("pro_descabv = '$nomeProduto'");
$query = $lista -> select();
while ($linhaP = mysql_fetch_assoc($query)) {
	$nomeProduto = $linhaP['pro_cod'];
}

$lista -> setTablet("filtropvescalas, escala");
$lista -> setFields("esc_descabv, esc_cod, fpve_escala, fpve_tipo, fpve_prod, foralinha");
$lista -> setCondicao("fpve_escala = esc_cod AND fpve_tipo = '1' AND fpve_prod = '$nomeProduto' AND foralinha <> 'S' ORDER BY esc_descabv");
$query = $lista -> selectMult();

if($lista -> getTotalNum() <= 0) {
	$query = $lista -> selectAll();
	while ($linhaEs = mysql_fetch_assoc($query)) {
		$valores = $linhaEs['esc_descabv'];
		$listaDados['valores'][] = array('escala' => $valores);
	}
} else {
	while ($linhaE = mysql_fetch_assoc($query)) {
		$valores = $linhaE['esc_descabv'];
		$listaDados['valores'][] = array('escala' => $valores);
	}
}

echo json_encode($listaDados);

?>