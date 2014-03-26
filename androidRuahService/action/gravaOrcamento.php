<?php
// essa pagina esta usando o grava_orcamento.php
include_once ("../service/crud.php");

$orcamento = new crud();
$data = date("d/m/Y");
$hora = date("H:i:s");
$orcProd = "cd - 83";
$orcEsc1 = "COR LACA IMBUIA";
$orcGrupo = "GR 4";
$orcEsc2 = "56000/442";
$orcQtd = "1";
$orcValor = "439";
$situacao = "A";
$loja = "LOJA CABEDELO-PB";
$login = "nero";
$email = "dante@gmail.com";
$cliente = "dante";
$fone = "90909090900";
$sigla = "ORC_";

$orcamento -> setTablet("loja");
$orcamento -> setFields("lj_fantasia, lj_cod, lj_sigla, lj_seqorc");
$orcamento -> setCondicao("lj_fantasia = '$loja'");
$query = $orcamento -> select();
while ($linhaL = mysql_fetch_assoc($query)) {
	$sigla .= $linhaL['lj_sigla'];
	$sigla .= $linhaL['lj_seqorc'];
	$loja = $linhaL['lj_cod'];
}

$orcamento -> setTablet("produtos, escala, progrupo, cores");
$orcamento -> setFields("pro_cod, pro_descabv, esc_cod, esc_descabv, prog_cod, prog_descabv, cor_cod, cor_descabv");
$orcamento -> setCondicao("pro_descabv = '$orcProd' AND esc_descabv = '$orcEsc1' AND prog_descabv = '$orcGrupo' AND cor_descabv = '$orcEsc2'");
$query = $orcamento -> selectMult();
while ($linhaP = mysql_fetch_array($query)) {
	$orcProd = $linhaP['pro_cod'];
	$orcEsc1 = $linhaP['esc_cod'];
	$orcGrupo = $linhaP['prog_cod'];
	$orcEsc2 = $linhaP['cor_cod'];
}

$orcamento -> setTablet("orcamento");
$orcamento -> setFields("orc_num, orc_loja, orc_data, orc_hora, orc_login, orc_prod, orc_escala1, orc_grupo, orc_escala2, orc_qtd, orc_valor, orc_situacao, orc_cliente, orc_fone, orc_email");
$orcamento -> setDados("'$sigla', '$loja', '$data', '$hora', '$login', '$orcProd', '$orcEsc1', '$orcGrupo', '$orcEsc2', '$orcQtd', '$orcValor', '$situacao', '$cliente', '$fone', '$email'");
$orcamento -> insert();

?>