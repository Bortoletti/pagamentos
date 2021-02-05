<?php

$url_logo = "http://www.deltafin.com.br/o2pagamentos/assets/img/backgrounds/logo.png";

$htmlv = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, shrink-to-fit=no'>
    <title>O2 Pagamentos - Redefinição de Senha</title>
    <meta name='description' content='Aplicação destinada a automação de cobrança.'>
    <link rel='stylesheet' href='assets/bootstrap/css/bootstrap.min.css'>
    <link rel='stylesheet' href='assets/css/style.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i'>
    <link rel='stylesheet' href='assets/fonts/fontawesome-all.min.css'>
    <link rel='stylesheet' href='assets/fonts/font-awesome.min.css'>
    <link rel='stylesheet' href='assets/fonts/ionicons.min.css'>
    <link rel='stylesheet' href='assets/fonts/fontawesome5-overrides.min.css'>
    <link rel='stylesheet' href='assets/css/Login-Form-Clean.css'>
    <script src='https://cdn.jsdelivr.net/npm/vue'></script><!--vue-->
</head>
<body>
    <div class='container'>
        <div style='background: #0f9d58;color: white;padding: 20px;margin-top: 20px;margin-bottom: 20px;text-align: left;'>
            <b>Aten&ccedil;&atilde;o:</b> Este &eacute; um email autom&aacute;tico. Favor n&atilde;o responder.
        </div>
        <div style='margin-bottom: 20px;'>
            <img src='" . $url_logo . "'>
        </div>            
        <div>                
            <b style='font-size: 20px;'>Ol&aacute;,</b><br>
            <b style='font-size: 20px;'>" . $msg->nome . "</b><br>
            <p>Recebemos sua solita&ccedil;&atilde;o para que sua senha fosse redefinida em nosso <b>Sistema de Cobran&ccedil;as
            da O2 Pagamentos</b>. Segue logo abaixo sua nova senha provis&oacute;ria para que voc&ecirc; esteja
            novamente possibilitado a acessar nosso sistema.</p>            
            <div style='background: #0f9d58;color: white;padding: 20px;margin-bottom: 20px;'>
                <b>Senha atual:</b> " . $msg->senha . " 
            </div>
            <p>Se esta redefini&ccedil;&atilde;o de senha n&atilde;o foi solicitada por voc&ecirc;, ignore este e-mail.</p>
            <p>Se precisar de ajuda, entre em contato com o administrador,</p> 
            <b>O2 Pagamentos<b><br>
            <b>Sistema de Cobran&ccedil;as<b>
        </div>            
    </div><!--container-->
    <script src='assets/js/jquery.min.js'></script>
    <script src='assets/bootstrap/js/bootstrap.min.js'></script>
    <script src='assets/js/chart.min.js'></script>
    <script src='assets/js/bs-init.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js'></script>
    <script src='assets/js/theme.js'></script>
</body>
</html> ";

?>
