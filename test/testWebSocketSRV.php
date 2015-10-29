<?php

include ("../../WebSocketServer.php");
class customServer extends WebSocketServer{
	
	function onData($SocketID, $M){
		$this->Log("Client #$SocketID > $M");
		$this->Write($SocketID, $M);
		foreach ($this->Clients as $idClient=>$itemClient) {
			
			$this->Write($this->Sockets[$idClient], "copie de #$SocketID > $M");
		}
	}
	
}

//$Address	= "127.0.0.1";
$address    = $_SERVER['SERVER_NAME'];
$Port		= 8090;

// test si service actif MAIS linux seulement (pour l'instant)
$isService = shell_exec("nc -z -w1 $Address $Port &> /dev/null; echo $?;");


if (php_sapi_name() === 'cli' and $isService == 1) {
	
	// création du service
	$customServer = new customServer($Address, $Port);
	$customServer->Start();
	
} elseif(php_sapi_name() != 'cli' and $isService == 1) {
	
	// adaptation nécessaire pour Windows
	$script = '/usr/bin/php -f ' . __FILE__ . ' ';
	$priority = 10;
	
	//$pid = shell_exec("nohup nice -n $priority $script 1> /dev/null 2> /dev/null & echo $!");
	//$pid = shell_exec("/usr/bin/nohup $script 1> /dev/null 2> /dev/null & echo $!");
	$pid = shell_exec("$script 1> /dev/null 2> /dev/null & echo $!");
	echo $pid, "<br>";
	
	//exec("ps $pid", $process_state);
	
	// seulement pour linux (pour l'instant)
	$logService = "ps -e -o pid,command | grep -v grep | grep $script";
	exec( $logService, $process_state );
	list($pid) = explode(' ', $process_state[0]);
	
	echo "Service actif; pid=[$pid]; adresse=[$Address]; port=[$Port] <br>";
		
} elseif (php_sapi_name() != 'cli' and $isService == 0) {
	
	$script = __FILE__;
	
	// seulement pour linux (pour l'instant)
	$logService = "ps -e -o pid,command | grep -v grep | grep $script";
	exec( $logService, $process_state );
	list($pid) = explode(' ', trim( $process_state[0]) );
	
	echo "Service actif; pid=[$pid]; adresse=[$Address]; port=[$Port] <br>";
	
} else {
	
	echo "Service inactif.";
	
}
