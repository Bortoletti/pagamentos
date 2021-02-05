<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");



$emailTo = isset( $_REQUEST['emailp'] )?$_REQUEST['emailp']:'lbortoletti@gmail.com';

// Inclui o arquivo class.phpmailer.php localizado na pasta class
require_once("./class.phpmailer.php");

// Inicia a classe PHPMailer
$mail = new PHPMailer(true);

//---------------------------------------------------
$titulov = "Teste de Email."; 
$msgv = "<h1>Teste de Email</h1>";

//------------------------------------------------------
// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(); // Define que a mensagem será SMTP
 
try {
     $mail->Host = 'smtplw.com.br'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
     $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
     $mail->Port       = 587; //  Usar 587 porta SMTP
     $mail->Username = 'deltasystems'; // Usuário do servidor SMTP (endereço de email)
     $mail->Password = 'EKWyHtmn1786'; // Senha do servidor SMTP (senha do email usado)
 
     //Define o remetente
     // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
     $mail->SetFrom('deltafin@deltasystems.com.br'); //Seu e-mail
     //$mail->AddReplyTo('flavio@deltasystems.com.br', 'AddReplyTo - Flávio '); //Seu e-mail
     $mail->Subject = $titulov; //Assunto do e-mail
 
 
     //Define os destinatário(s)
     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
     // $mail->AddAddress('flavio@deltasystems.com.br', 'Teste email');
	 $mail->AddAddress( $emailTo, 'Reset de E-Mail');
 
     //Campos abaixo são opcionais 
     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
     //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
     //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
     //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo
 
 
     //Define o corpo do email
     $mail->MsgHTML( $msgv ); 
 
     ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
     //$mail->MsgHTML(file_get_contents('arquivo.html'));
 
     $mail->Send();
     echo "Mensagem enviada com sucesso</p>\n";
 
    //caso apresente algum erro é apresentado abaixo com essa exceção.
    }catch (phpmailerException $e) {
      echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
}
?>
