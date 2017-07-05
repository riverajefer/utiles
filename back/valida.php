<?php
// llamada de clases
require "gump.class.php"; // clase para validar campos
require 'phpmailer/PHPMailerAutoload.php'; // clase para envio de correo

$validator = new GUMP();
$mail      = new PHPMailer;


$_POST = $validator->sanitize($_POST); // You don't have to sanitize, but it's safest to do so.

// Reglas de validación
$rules = array(
	'nombre'     => 'required|max_len,100',
	'email'    => 'required|valid_email|max_len,100',
	'mensaje'  => 'max_len,500'
);


// filtros 
$filters = array(
	'nombre' 	   => 'trim|sanitize_string',
	'email'    => 'trim|sanitize_email',	
	'mensaje'  => 'trim|sanitize_string',
);


$_POST = $validator->filter($_POST, $filters);

$validated = $validator->validate($_POST, $rules);



// Check if validation was successful
if($validated === TRUE)
{
	// construimos el msg, a enviar por email
	$html = '
	<h2>Contacto Tus Kids</h2>
	<ul>
		<li>Nombre:     <strong>'.$_POST['nombre'].'</strong></li>
		<li>Email:      <strong>'.$_POST['email'].'</strong></li>
		<li>Mensaje:    <strong>'.$_POST['mensaje'].'</strong></li>
	</ul>
	<hr>
	<br>';


	$mail->setFrom($_POST['email'], $_POST['nombre']);

	$mail->addAddress('riverajefer@gmail.com');
	//$mail->addAddress('yoana1563@Hotmail.com');

	$mail->Subject = 'Contacto Tus Kids';
	$mail->msgHTML($html);

	$mail->CharSet = 'UTF-8';
	
	if (!$mail->send()) {
	    echo "fallo";
	}else {

		echo "ok";
	}
	exit;
}
else
{	// si falla la validación, enviamos al cliente los errores
	echo $validator->get_readable_errors(true);	
}

