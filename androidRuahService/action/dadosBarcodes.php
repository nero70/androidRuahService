<?php
//essa pagina esta usando as pagina / saldos_barcode.php / preco_final.php / pesq_img.php / cod_nome_cod.php / grava_log.php
include_once ("../service/crud.php");
$dados = new crud();
//$data = muda_data_en(date("d/m/Y"));
$data = date("d/m/Y");
$hora = date("H:i:s");
$numeroBarcode = $_POST['barcode'];
$nomeLoja = $_POST['loja'];
$nomeCliente = $_POST['cliente'];
$loginUsuario = $_POST['usuario'];
$codigoProduto;
$codigoBarcode;
$codigoLoja;
$numCalco;
$barcEscala1;
$barcGrupo;
$barcEscala2;
$listaSaldos = array();
$listaPrecos = array();
$pegaImagem = array();

$dados -> setTablet("produtos, loja, barcodes");
$dados -> setFields("pro_fototermica, pro_descabv, pro_cod, lj_estado, lj_fantasia, lj_cod, bc_codbarra, bc_prod, bc_escala1, bc_grupo, bc_escala2");
$dados -> setCondicao("bc_prod = pro_cod AND lj_fantasia = '$nomeLoja' AND bc_codbarra = '$numeroBarcode'");
$query = $dados -> selectMult();

while ($linhaP = mysql_fetch_array($query)) {
	$linkImagem = $linhaP['pro_fototermica'];
	$nomeProduto = $linhaP['pro_descabv'];
	$codigoLoja = $linhaP['lj_cod'];
	$codigoProduto = $linhaP['pro_cod'];
	$codigoBarcode = $linhaP['bc_prod'];
	$barcEscala1 = $linhaP['bc_escala1'];
	$barcGrupo = $linhaP['bc_grupo'];
	$barcEscala2 = $linhaP['bc_escala2'];
	$linkImagem = "http://www.jmdec.com.br/ruah/imagens/etiquetas/termica/" . $linkImagem;

	$pegaImagem['imagem'][] = array("img" => $linkImagem, "nomeProduto" => $nomeProduto);
}
echo json_encode($pegaImagem);

$dados -> setTablet("saldos, cores, escala, progrupo");
$dados -> setFields("sal_estreal, cor_descabv, esc_descabv, prog_descabv");
$dados -> setCondicao("sal_escala = esc_cod AND sal_cor = cor_cod AND sal_progrupo = prog_cod AND sal_loja = '$codigoLoja' AND sal_cod = '$codigoBarcode' AND esc_cod = '$barcEscala1' AND cor_cod = '$barcEscala2' AND prog_cod = '$barcGrupo'");
$query = $dados -> selectMult();

while ($linhaS = mysql_fetch_array($query)) {
	$saldoReal = $linhaS['sal_estreal'];
	$grupo = $linhaS['esc_descabv'];
	$escala = $linhaS['prog_descabv'];
	$cor = $linhaS['cor_descabv'];	
	
	$listaSaldos['saldos'][] = array("grupo" => $grupo, "escala" => $escala, "cor" => $cor, "saldoreal" => $saldoReal);
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