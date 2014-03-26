<?php
// nesta pagina esta usando o / grava_log.php
include_once ("../service/crud.php");
include_once ("../service/limpaDados.php");

$log = new crud();
$limpa = new limpaDados();
$logData = $limpa -> muda_data_en(date("d/m/Y"));
$logHora = date("H:i:s");
$logArquivo = "Tablet";
$logLogin = $_POST['orc_login'];
$logLoja = $_POST['orc_loja'];

$log -> setTablet("logacessos");
$log -> setFields("log_login, log_data, log_loja, log_hora, log_arquivo");
$log -> setDados("'$logLogin', '$logData', '$logLoja', '$logHora', '$logArquivo'");
$log -> insert();
?>