<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include( '../conf/util.php' );
include( './_setup.php' );

$fileLog = 'enviar_email';
logar( $fileLog, '****   Inicio   ******');

$emailTo = isset( $_REQUEST['email'] )?$_REQUEST['email']:'lbortoletti@gmail.com';
$titulov = isset( $_REQUEST['titulo'] )?$_REQUEST['titulo']:"Teste de Email."; 
$msgv    = isset( $_REQUEST['msg'] )?$_REQUEST['msg']:"<h1>Teste de Email</h1>";

logar( $fileLog,  'email: ' . $emailTo );
logar( $fileLog,  'titulov: ' . $titulov );
logar( $fileLog,  'msgv: ' . $msgv );


// Inclui o arquivo class.phpmailer.php localizado na pasta class
require_once("./class.phpmailer.php");

// Inicia a classe PHPMailer
$mail = new PHPMailer(true);

//---------------------------------------------------


//------------------------------------------------------
// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(); // Define que a mensagem será SMTP
 
try {
     $mail->Host       = $setup_Host; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
     $mail->SMTPAuth   = $setup_SMTPAuth;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
     $mail->Port       = $setup_Port; //  Usar 587 porta SMTP
     $mail->Username   = $setup_Username; // Usuário do servidor SMTP (endereço de email)
     $mail->Password   = $setup_Password; // Senha do servidor SMTP (senha do email usado)



     //Define o remetente
     // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
     $mail->SetFrom( $setup_EmailFrom  ); //Seu e-mail
     //$mail->AddReplyTo('flavio@deltasystems.com.br', 'AddReplyTo - Flávio '); //Seu e-mail
     $mail->Subject = $titulov; //Assunto do e-mail
 
 
     //Define os destinatário(s)
     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
     // $mail->AddAddress('flavio@deltasystems.com.br', 'Teste email');
	   $mail->AddAddress( $emailTo, 'Destinatario');
 
     //Campos abaixo são opcionais 
     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
     //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
     $mail->AddBCC( $setup_EmailBCC, 'Destinatario2`'); // Cópia Oculta
     //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo
 
 
     //Define o corpo do email
     $mail->MsgHTML( $msgv ); 
 
     ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
     //$mail->MsgHTML(file_get_contents('arquivo.html'));
 
     $mail->Send();
     // echo "Mensagem enviada com sucesso</p>\n";
     logar( $fileLog,  'Mensagem enviada com sucesso'  );
 
    //caso apresente algum erro é apresentado abaixo com essa exceção.
    }catch (phpmailerException $e) {
      // echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
      logar( $fileLog,  'Falha: ' . $e->errorMessage() );
}

     logar( $fileLog,  '************  FIM   **************'  );

?>
