<?php
    /**
	 * Comparação em tempo de execulção de For e While em uma repetição simples e um contador  de execulção no final.
	 */ 
	 
	// Iniciamos o "contador"
	list($usec, $sec) = explode(' ', microtime());
	$script_start = (float) $sec + (float) $usec;
	  
	  
	//---Codigo
	 	  
	
	// Terminamos o "contador" e exibimos
	list($usec, $sec) = explode(' ', microtime());
	$script_end = (float) $sec + (float) $usec;
	$elapsed_time = round($script_end - $script_start, 5);
	//exibir mensagem
	echo 'Tempo decorrido: ', $elapsed_time, ' secs. Uso de memoria: ', round(((memory_get_peak_usage(true) / 1024) / 1024), 2), 'Mb';
?>