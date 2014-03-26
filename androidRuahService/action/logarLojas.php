<?php
// neste caso esta usando o / lojar.php / esc_loja.php / pega_nome.php
include_once ("../service/crud.php");

$acesso = new crud();
$login = $_POST['login'];
$senha = $_POST['senha'];

$acesso -> setTablet("acessos, acessosloja, loja");
$acesso -> setFields("ace_login, ace_senha, acel_login, acel_loja, lj_cod, lj_fantasia");
$acesso -> setCondicao("ace_login = '$login' AND ace_senha = '$senha'");
$query = $acesso -> select();

if (mysql_num_rows($query) > 0) {

	$acesso -> setCondicao("ace_login = '$login' AND ace_login = acel_login AND acel_loja = lj_cod");
	$query = $acesso -> selectMult();

	while ($linhaL = mysql_fetch_assoc($query)) {
		$nomeLoja = $linhaL['lj_fantasia'];
		$codeLoja = $linhaL['lj_cod'];
		$lojasJson["nomeLojas"][] = array("loja" => $nomeLoja, "cod" => $codeLoja);
	}

	echo $jsonLoja = json_encode($lojasJson);

} else {
	echo 0;
}
?>