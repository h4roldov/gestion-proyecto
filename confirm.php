<?php
	function redirect() {
		header('Location: registro.php');
		exit();
	}

	if (!isset($_GET['email']) || !isset($_GET['token'])) {
		redirect();
	} else {
		$con = new mysqli('localhost', 'root', '', 'test');

		$email = $con->real_escape_string($_GET['email']);
		$token = $con->real_escape_string($_GET['token']);

		$sql = $con->query("SELECT id FROM usuario WHERE email='$email' AND token='$token' AND emailConfirmado=0");

		if ($sql->num_rows > 0) {
			$con->query("UPDATE usuario SET emailConfirmado=1, token='' WHERE email='$email'");
			echo 'Tu correo ha sido verificado!';
		} else
			redirect();
	}
?>