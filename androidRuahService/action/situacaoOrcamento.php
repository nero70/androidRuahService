<?php
// essa pagina esta usando / grava_orc_situacao.php / atualiza_orc_pesquisa.php
include_once ('../service/crud.php');

$situacao = new crud();
$tipoAltera = "1";
$orcProd = "cd - 88";
$orcEsc1 = "COR LACA IMBUIA";
$orcGrupo = "GR 2";
$orcEsc2 = "56000/442";
$loja = "LOJA SANTOS DUMONT-CE";
$atualSitua = "F";
$login = "dante";
$cliente = "nero master";
$fone = "8888888";
// em outra situacao
$qtd = "1";
$total = "200";

$situacao -> setTablet("loja");
$situacao -> setFields("lj_fantasia, lj_cod");
$situacao -> setCondicao("lj_fantasia = '$loja'");
$query = $situacao -> select();
while ($linhaL = mysql_fetch_assoc($query)) {
	$loja = $linhaL['lj_cod'];
}

$situacao -> setTablet("produtos, escala, progrupo, cores");
$situacao -> setFields("pro_cod, pro_descabv, esc_cod, esc_descabv, prog_cod, prog_descabv, cor_cod, cor_descabv");
$situacao -> setCondicao("pro_descabv = '$orcProd' AND esc_descabv = '$orcEsc1' AND prog_descabv = '$orcGrupo' AND cor_descabv = '$orcEsc2'");
$query = $situacao -> selectMult();
while($linhaP = mysql_fetch_array($query)) {
	$orcProd = $linhaP['pro_cod'];
	$orcEsc1 = $linhaP['esc_cod'];
	$orcGrupo = $linhaP['prog_cod'];
	$orcEsc2 = $linhaP['cor_cod'];
}

// atualizando os dados
$situacao -> setTablet("orcamento");
$situacao -> setCondicao("orc_prod = '$orcProd' AND orc_escala1 = '$orcEsc1' AND orc_grupo = '$orcGrupo' AND orc_escala2 = '$orcEsc2' AND orc_login = '$login' AND orc_loja = '$loja' AND orc_situacao = 'A'");
// aqui e caso seja para atualizar com o nome do cliente e fone e situação
if ($tipoAltera == "0") {
 	$situacao -> setFields("orc_situacao = '$atualSitua', orc_cliente = '$cliente', orc_fone = '$fone'");
 }
// aqui e caso seja para atualiza so o preço quantidade e total do produto
if ($tipoAltera == "1") {
 	$situacao -> setFields("orc_qtd = '$qtd', orc_valor = '$total'");
}
$situacao->update();
?>