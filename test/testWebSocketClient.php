<?php

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>WebSocket Client Example</title>
	<script src="../common/jquery/jquery-1.10.2.min.js"></script>

	<script>
		$(document).ready(function () {
			var ws = new WebSocket("ws://<?php echo $_SERVER['SERVER_NAME'] ?>:8090/tests/WebSocketServer.php");

			ws.onopen = function () {
				log("Connection established"); // Send the message 'Ping' to the server
			};

			ws.onerror = function (error) { log("Unknown WebSocket Error " + JSON.stringify(error)); };
			ws.onmessage = function (e) { log("< " + e.data); };
			ws.onclose = function () { log("Connection closed - Either the host or the client has lost connection"); }

			$("#quit").click(function () {
				log("Connection closed");
				ws.close(); ws = null;
			});

			$("#connect").click(function () {
				if (ws == null) {
					ws = new WebSocket("ws://<?php echo $_SERVER['SERVER_NAME'] ?>:8090/tests/WebSocketServer.php");
					ws.onopen = function () {
						log("Connection established"); // Send the message 'Ping' to the server
					};

					ws.onerror = function (error) { log("Unknown WebSocket Error " + JSON.stringify(error)); };
					ws.onmessage = function (e) { log("< " + e.data); };
					ws.onclose = function () { log("Connection closed - Either the host or the client has lost connection"); }
				} else {
					//
				}
				
			});
			function log(m) {
				$("#log").append(m + "<br />");
			}

			function send() {
				$Msg = $("#msg");
				if ($Msg.val() == "") return alert("Textarea is empty");

				try {
					ws.send($Msg.val()); log('> ' + $Msg.val());
				} catch (exception) {
					log(exception);
				}
				$Msg.val("");
			}

			$("#send").click(send);
			$("#msg").on("keydown", function (event) {
				if (event.keyCode == 13) send();
			});
		});
    </script>

	<style>
		html, body {
			font-family:monospace;
		}

		#log {
			width:500px;
			height:200px;
			border:1px solid black;
			overflow:auto;
		}

		#msg {
			width: 330px;
		}
	</style>
</head>

<body>
	<div id="log"></div>
	<input id="msg" type="text" />
	<button id="send">Send</button>
	<button id="quit">Quit</button>
	<button id="connect">reconnexion</button>
</body>
</html>
