<?php
// nesta pagina esta usando o / atualiza_loja.php / pega_nome.php
include_once ("../service/crud.php");

$atualiza = new crud();
$nomeLoja = $_POST['loja'];
$sigla = "ORC_";

$atualiza -> setTablet("loja");
$atualiza -> setFields("lj_seqorc = lj_seqorc + 1");
$atualiza -> setCondicao("lj_fantasia = '$nomeLoja'");

if ($atualiza -> update()) {

	$atualiza -> setFields("lj_fantasia, lj_sigla, lj_seqorc");
	$atualiza -> setCondicao("lj_fantasia = '$nomeLoja'");
	$query = $atualiza -> select();

	while ($linhaLoja = mysql_fetch_assoc($query)) {
		$pegaSigla = $linhaLoja['lj_sigla'];
		$pegaSeqorc = $linhaLoja['lj_seqorc'];
		echo $sigla . $pegaSigla . $pegaSeqorc;
	}
}
?>