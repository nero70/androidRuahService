<?php

class limpaDados {

	// retira todos os acentos e troca por letras
	public function limpadados($valores) {
		$str = $valores;
		$map = array('á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C');
		$str = strtr($str, $map);
		return $str;
	}

	//mudar data de 2002/00/00 para 2002-00-00; formato americano
	public function muda_data_en($data) {
		$aux = explode("/", $data);
		$c = array_reverse($aux);
		$data = implode("-", $c);
		return $data;
	}

}
?>