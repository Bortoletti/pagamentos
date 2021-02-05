<?php
$url_logo = 'http://www.deltafin.com.br/o2pagamentos/assets/img/backgrounds/logo.png';


$htmlv = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, shrink-to-fit=no'>
    <title>O2 Pagamentos - Processamento de Fatura</title>
    <meta name='description' content='Aplicação destinada a automação de cobrança.'>
    <link rel='stylesheet' href='assets/bootstrap/css/bootstrap.min.css'>
    <link rel='stylesheet' href='assets/css/style.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i'>
    <link rel='stylesheet' href='assets/fonts/fontawesome-all.min.css'>
    <link rel='stylesheet' href='assets/fonts/font-awesome.min.css'>
    <link rel='stylesheet' href='assets/fonts/ionicons.min.css'>
    <link rel='stylesheet' href='assets/fonts/fontawesome5-overrides.min.css'>
    <link rel='stylesheet' href='assets/css/Login-Form-Clean.css'>
</head>
<body>
    <div class='container'>
        <div style='background: #0f9d58;color: white;padding: 20px;
                    margin-top: 20px;margin-bottom: 30px;text-align: left;'>
                    <b>Aten&ccedil;&atilde;o:</b> Este &eacute; um email autom&aacute;tico. Favor n&atilde;o responder.
        </div>
        <div style='margin-bottom: 30px;'>
            <img src='" . $url_logo . "'>
        </div>            
        <div>                
            <h4 style='font-size: 20px;'>Ol&aacute;,</h4><br>
            <b style='font-size: 20px;'>" . $msg->nome . "</b><br>
            
            <p>A sua fatura foi processada com sucesso em nosso <b>Sistema de Cobran&ccedil;as da O2 Pagamentos</b>.</p>
            
            <div style='background: #0f9d58;color: white;padding: 20px;margin-bottom: 20px;'>
                <b>C&oacute;digo digit&aacute;vel do boleto:</b> 
                <p>" . $digitavelboleto . "</p>
                <p><a href='" . $urlcustomboleto . "' target='_blank'>Ver Boleto</a></p>
            </div>
            
            <p>Se voc&ecirc; n&atilde;o realizou um pedido de inclus&atilde;o de uma nova fatura no <b>Sistema de Cobran&ccedil;as da 
            O2 Pagamentos</b>, pode ignorar o conte&uacute;do deste e-mail.</p>
            <b>O2 Pagamentos</b><br>
            <b>Sistema de Cobran&ccedil;as</b>
        </div>            
    </div><!--container-->
    <script src='assets/js/jquery.min.js'></script>
    <script src='assets/bootstrap/js/bootstrap.min.js'></script>
    <script src='assets/js/chart.min.js'></script>
    <script src='assets/js/bs-init.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js'></script>
    <script src='assets/js/theme.js'></script>
    <script src='https://unpkg.com/axios/dist/axios.min.js'></script><!--axios-->
</body>
</html> ";