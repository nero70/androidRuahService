<?php
// nesta pagina usa as pagina / pesq_img.php / saldos_real / preco_fina.php / grava_log_pesquisa.php
include_once ("../service/crud.php");

$dados = new crud();
//$data = muda_data_en(date("d/m/Y"));
$data = date("d/m/Y");
$hora = date("H:i:s");
$nomeProduto = $_POST['produto'];
$nomeLoja = $_POST['loja'];
$nomeCliente = $_POST['cliente'];
$loginUsuario = $_POST['usuario'];
$codigoProduto;
$siglaLoja;
$codigoLoja;
$numCalco;
$listaSaldos = array();
$listaPrecos = array();
$pegaImagem = array();

$dados -> setTablet("produtos, loja");
$dados -> setFields("pro_fototermica, pro_descabv, pro_cod, lj_estado, lj_fantasia, lj_cod");
$dados -> setCondicao("pro_descabv = '$nomeProduto' AND lj_fantasia = '$nomeLoja'");
$query = $dados -> select();

while ($linhaP = mysql_fetch_assoc($query)) {
	$linkImagem = $linhaP['pro_fototermica'];
	$codigoProduto = $linhaP['pro_cod'];
	$siglaLoja = $linhaP['lj_estado'];
	$codigoLoja = $linhaP['lj_cod'];
	$linkImagem = "http://www.jmdec.com.br/ruah/imagens/etiquetas/termica/" . $linkImagem;

	$pegaImagem['imagem'][] = array("img" => $linkImagem);
}

echo json_encode($pegaImagem);
$dados -> setTablet("saldos, cores, escala, progrupo, loja");
$dados -> setFields("sal_cod, sal_cor, sal_progrupo, sal_estreal, sal_escala, cor_descabv, cor_cod, esc_cod, esc_descabv, lj_sigla, prog_cod, prog_descabv");
$dados -> setCondicao("sal_loja = lj_cod AND sal_escala = esc_cod AND sal_cor = cor_cod AND sal_progrupo = prog_cod AND sal_cod = '$codigoProduto' AND lj_estado = '$siglaLoja' ORDER BY lj_sigla");
$query = $dados -> selectMult();

while ($linhaS = mysql_fetch_array($query)) {
	$grupo = $linhaS['prog_descabv'];
	$escala = $linhaS['esc_descabv'];
	$cor = $linhaS['cor_descabv'];
	$saldoReal = $linhaS['sal_estreal'];
	$ljSigla = $linhaS['lj_sigla'];
	if($saldoReal > 0) {
		$listaSaldos['saldos'][] = array("grupo" => $grupo, "escala" => $escala, "cor" => $cor, "saldoreal" => $saldoReal, "sigla" => $ljSigla);		
	}
}
echo json_encode($listaSaldos);
$dados -> setTablet("loja, regioes");
$dados -> setFields("reg_coef, reg_num, lj_regiao, lj_cod");
$dados -> setCondicao("lj_regiao = reg_num AND lj_cod = '$codigoLoja'");
if ($dados -> getTotalNum() > 0) {
	$linhaCa = $dados -> linhaObj();
	$numCalco = $linhaCa -> reg_coef;
}

$dados -> setTablet("produtos, precos");
$dados -> setFields("pro_cod, pro_descabv, pre_prod, pro_tipotabela, pre_grupo, pre_precocusto, pre_tipovidro, pre_acabamento, pro_fabricante, pro_subgrupo");
$dados -> setCondicao("pre_prod = pro_cod AND pro_cod = '$codigoProduto' AND 1 LIMIT 0,6");
$query = $dados -> selectMult();

while ($linhaPr = mysql_fetch_array($query)) {
	$prodValor = $linhaPr['pre_precocusto'];
	$fabrica = $linhaPr['pro_fabricante'];
	$subgrup = $linhaPr['pro_subgrupo'];
	$pgrupo = $linhaPr['pre_grupo'];
	$tipoTab = $linhaPr['pro_tipotabela'];
	$tipoVid = $linhaPr['pre_tipovidro'];
	$tipoAcab = $linhaPr['pre_acabamento'];

	$marcacaofab = '1';
	$marcacaosgr = '1';

	$dados -> setTablet("fabricante, subgrupo");
	$dados -> setFields("fab_marcacaoprecos, sgr_marcacaoprecos");
	$dados -> setCondicao("fab_cod = '$fabrica' AND sgr_cod = '$subgrup'");
	if ($dados -> getTotalNum() > 0) {
		$linhaCa = $dados -> linhaObj();
		$marcacaofab = $linhaCa -> fab_marcacaoprecos;
		$marcacaosgr = $linhaCa -> sgr_marcacaoprecos;
	}
	$precoFinal = ceil($prodValor * $numCalco * $marcacaofab * $marcacaosgr);
	switch($tipoTab) {
		case 1 :
			$listaPrecos['precoTotal'][] = array('grupo' => 'Gr' . $pgrupo, 'preco' => $precoFinal);
			break;
		case 2 :
			$listaPrecos['precoTotal'][] = array('grupo' => 'Gr' . $pgrupo, 'preco' => $precoFinal);
			break;
		case 3 :
			$nomeMol;
			if ($tipoAcab == "LAC") {
				$nomeMol = "LACA";
			} else if ($tipoAcab == "LAM") {
				$nomeMol = "LAMINA";
			} else {
				$nomeMol = "VERNIZ";
			}
			$listaPrecos['precoTotal'][] = array('grupo' => $nomeMol, 'preco' => $precoFinal);
			break;
		case 4 :
			$listaPrecos['precoTotal'][] = array('grupo' => 'Gr' . $pgrupo, 'preco' => $precoFinal);
			break;
		case 5 :
			$listaPrecos['precoTotal'][] = array('grupo' => 'VERNIZ', 'preco' => $precoFinal);
			break;
		case 6 :
			$listaPrecos['precoTotal'][] = array('grupo' => 'LACA', 'preco' => $precoFinal);
			break;
		case 7 :
			$nomeGrupo = ($pgrupo == 1 || $pgrupo == 2 || $pgrupo == 3) ? "GRUPO A" : "GRUPO B";
			$listaPrecos['precoTotal'][] = array('grupo' => $nomeGrupo, 'preco' => $precoFinal);
			break;
		case 8 :
			$listaPrecos['precoTotal'][] = array('grupo' => $tipoVid, 'preco' => $precoFinal);
			break;
	}
}

echo json_encode($listaPrecos);

$dados -> setTablet("logpesquisa");
$dados -> setFields("lp_login, lp_data, lp_hora, lp_loja, lp_prod, lp_cliente");
$dados -> setDados("'$loginUsuario', '$data', '$hora', '$codigoLoja', '$codigoProduto', '$nomeCliente'");
$dados -> insert();
?>