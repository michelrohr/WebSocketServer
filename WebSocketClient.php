class WebSocketClient {
	// get according socket in WebSocketServer using $this->Sockets[$Client->ID]
	public
		$ID,
		$Headers	= null,
		$Handshake	= null,
		$timeCreated	= null;
	
	function __construct($Socket){
		$this->ID		= intval($Socket);
		$this->timeCreated	= time();
	}
}
