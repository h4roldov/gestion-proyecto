<?php
				use PHPMailer\PHPMailer\PHPMailer;
				use PHPMailer\PHPMailer\Exception;
	$msg = "";

	if (isset($_POST['submit'])) {

		$con = new mysqli('localhost', 'root', '', 'test');

		if ($con->connect_error) {
   			die("Connection failed: " . $conn->connect_error);
   			$msg=$conn->connect_error;
		} 

		$name = $con->real_escape_string($_POST['nombre']);
		$email = $con->real_escape_string($_POST['email']);
		$password = $con->real_escape_string($_POST['password']);
		$cPassword = $con->real_escape_string($_POST['cPassword']);

		if ($name == "" || $email == "" || $password != $cPassword)
			$msg = "Revise los datos!";
		else {
			$sql = $con->query("SELECT id FROM usuario WHERE email='$email';");
			if ($sql->num_rows > 0) {
				$msg = "Este correo ya esta registrado!";
			} else {
				$token = 'qwertzuiopasdfghjklyxcvbnmQWERTZUIOPASDFGHJKLYXCVBNM0123456789!$/()*';
				$token = str_shuffle($token);
				$token = substr($token, 0, 10);

				$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
				$sql = "INSERT INTO usuario (nombre,email,password,emailConfirmado,token) VALUES ('$name', '$email', '$hashedPassword', 0, '$token');";

				if ($con->query($sql) === TRUE) {
    				$msg = "Agregado";
				} else {
    				$msg = "Error: " . $sql . "<br>" . $con->error;
				}

				//Load Composer's autoloader
				require 'vendor/autoload.php';

				$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
				try {
				    //Server settings
				   // $mail->SMTPDebug = 1;                                 // Enable verbose debug output
				    $mail->isSMTP();                                      // Set mailer to use SMTP
				    $mail->Host = 'smtp.sendgrid.net';  				  // Specify main and backup SMTP servers
				    $mail->SMTPAuth = true;                               // Enable SMTP authentication
				    $mail->Username = 'apikey';                 // SMTP username
				    $mail->Password = '';                           // SMTP password
				    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				    $mail->Port = 587;                                    // TCP port to connect to

				    //Recipients
				    $mail->setFrom('no-responder@tutores.com', 'Bienvenido a TUTORIAS UBB');
				    $mail->addAddress($email, $name);     // Add a recipient
				    /*$mail->addAddress('ellen@example.com');               // Name is optional
				    $mail->addReplyTo('info@example.com', 'Information');
				    $mail->addCC('cc@example.com');
				    $mail->addBCC('bcc@example.com');*/

				    //Attachments
				    /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
				    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
					*/
				    //Content
				    $mail->isHTML(true);                                  // Set email format to HTML
				    $mail->Subject = 'Verifica tu cuenta de TUTORIAS UBB';
				    $mail->Body    = "
                    Para validar tu cuenta has click en el siguiente enlace:<br><br>
                    
                    <a href='localhost/gestion/confirm.php?email=$email&token=$token'>Verificar mi cuenta TUTORES UBB</a>";
				    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				    $mail->send();
				    $msg = 'Verifica tu correo';
				} catch (Exception $e) {
				    $msg = 'Correo de verificación no se pudo enviar. Intenta de nuevo.'//' Error: '. $mail->ErrorInfo;
				}

			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Registro Tutor</title>
	<meta name="description" content="">
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  	<link rel="manifest" href="site.webmanifest">
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/main.css">
</head>
<body>

	<header>	
		<h3>Registrate como tutor</h3>	
	</header>

	<div class="contenedor datos-personales">
		<div class="contenedor-form">
			<div class="equis">	</div>
			
			<div class="form-tutor">
				<div class="mensaje">
				<p><?php if ($msg != "") echo $msg?></p>
				</div>
				<form action="registro.php" method="POST" class="validar-info">
					<div class="input">
						<!--<label for="nombre">Nombre</label>-->
						<input type="text" name="nombre" placeholder="Nombre" required>	
					</div>
						
					<div class="input">
						<!--<label for="apellido">Apellido</label>-->
						<input type="text" name="apellido" placeholder="Apellido" required>
					</div>
					<div class="input">
						<!--<label for="email">Email</label>-->
						<input type="email" name="email" placeholder="E-mail" required>
					</div>
					<div class="input">
						<!--<label for="contraseña">Contraseña</label>-->
						<input type="password" name="password" placeholder="Contraseña" required>
					</div>
					<div class="input">
						<!--<label for="contraseña">Contraseña</label>-->
						<input type="password" name="cPassword" placeholder="Repetir Contraseña" required>
					</div>

					<input type="submit" name="submit" value="Registrarse" class="boton">

				</form>

			</div>
		</div>
	</div>

	<footer>		
	</footer>
</body>


</html>
