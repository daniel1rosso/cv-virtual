<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $secret = '6LdHBykqAAAAAE613eifQsTZ4xg54i7-4Sz1pNk9';
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_response = file_get_contents($recaptcha_url . '?secret=' . $secret . '&response=' . $response . '&remoteip=' . $remoteip);
    $recaptcha_data = json_decode($recaptcha_response);

    if ($recaptcha_data->success) {
        // Recibir datos del formulario
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $mensaje = $_POST['mensaje'];
        $otroMedio = $_POST['otroMedio'];
    
        // Construir el mensaje
        $mensaje_telegram = "Nuevo mensaje de contacto:\n\n";
        $mensaje_telegram .= "Email: $email\n";
        $mensaje_telegram .= "Teléfono: $telefono\n";
        $mensaje_telegram .= "Mensaje: $mensaje\n";
        $mensaje_telegram .= "Otro Medio de Comunicación: $otroMedio";
    
        // Enviar el mensaje a través de la API de Telegram
        $telegram_bot_token = '6829407257:AAEb2VfsH_1AlZQkPQlpOVcDOgYHN5oI_BA';
        $chat_id = '1238026457';
        $url = "https://api.telegram.org/bot$telegram_bot_token/sendMessage";
        $data = array('chat_id' => $chat_id, 'text' => $mensaje_telegram);
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
    
        // Redireccionar de vuelta al formulario
        header('Location: index.html');
        exit();
    } else {
        echo 'Por favor, completa el CAPTCHA correctamente.';
    }
}
?>
