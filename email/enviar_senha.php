<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//include("./default.php");
include("../conf/conexao.php");
include("./_setup.php"); // variaveis para configurar Servidor de e-mail.

//================
class Mensagem
{
  public $nome = '';
  public $senha = '';
  public $fl_ativo = '';
  public $status = '';
  public $log = '';
}

$msg = new Mensagem();


// Inclui o arquivo class.phpmailer.php localizado na pasta class
require_once("./class.phpmailer.php");

$emailParam = isset( $_REQUEST['email'] )?$_REQUEST['email']:'';

if( $emailParam == '')
{
  $msg->status = '999-E-mail invalido';
  print(  json_encode( $msg ) );
  return;

}

//================


try {
  $msg->status='';
  //============ ===== =====================

  $sql  = "SELECT nome, senha, fl_ativo ";
  $sql .= " FROM public.cob_usuario ";
  $sql .= " where email = '$emailParam' ";
// print( $sql );
// return;
  $result = $conn->query( $sql );
  $rows = $result->fetchAll();

  foreach( $rows as $r)
  {
    $msg->nome = $r['nome'];
    $msg->senha = $r['senha'];
    $msg->fl_ativo = $r['fl_ativo'];
    $msg->status = '100-Ok';
  }	  

}
catch(PDOException $e) {
  $msg->status='999-FALHA';
  $msg->log .= "Error: " . $e->getMessage();
}

if( $msg->status != '100-Ok')
{
  $msg->status = 'E-mail Invalido, falha: ' . $msg->status ;
  print(  json_encode( $msg ) );
  return;

}


//================================================================
// HTML DO CORPO DO E-MAIL
// VARIAVEL HTMLV É CRIADA E CONTEUDO PROCESSADO ESTA NELA.
//================================================================


include('enviar_senha_html.php');
//print( $htmlv );
//return;


//---------------------------------------------------
$titulov = "O2 Pagamentos - Recuperar Senha"; 
// $msgv = "<h1>Teste de Email</h1><p>Senha: " . $msg->senha;
$msgv = $htmlv;

//================================================================
//  INICIO DO E-MAIL
//================================================================
// Inicia a classe PHPMailer
$mail = new PHPMailer(true);


//------------------------------------------------------
// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(); // Define que a mensagem será SMTP
 
try {
    // configurar servidor de e-mail, ver _setup.php
    //-------------------------------------------------------------
     // $mail->Host = 'smtplw.com.br'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
     // $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
     // $mail->Port       = 587; //  Usar 587 porta SMTP
     // $mail->Username = 'deltasystems'; // Usuário do servidor SMTP (endereço de email)
     // $mail->Password = 'EKWyHtmn1786'; // Senha do servidor SMTP (senha do email usado)

     
     $mail->Host = $setup_Host; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
     $mail->SMTPAuth   = $setup_SMTPAuth;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
     $mail->Port       = $setup_Port; //  Usar 587 porta SMTP
     $mail->Username = $setup_Username; // Usuário do servidor SMTP (endereço de email)
     $mail->Password = $setup_Password; // Senha do servidor SMTP (senha do email usado)

     //Define o remetente
     // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
     
     // $mail->SetFrom('o2@o2pagamentos.com.br'); //Seu e-mail
     // $mail->SetFrom( 'deltafin@deltasystems.com.br' ); //Seu e-mail
     
     $mail->SetFrom( $setup_EmailFrom ); //Seu e-mail
     
     
     
     //$mail->AddReplyTo('flavio@deltasystems.com.br', 'AddReplyTo - Flávio '); //Seu e-mail
     
 
     //Define os destinatário(s)
     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
     // $mail->AddAddress('flavio@deltasystems.com.br', 'Teste email');
 	   $mail->AddAddress( $emailParam, 'info');
 
     //Campos abaixo são opcionais 
     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
     //$mail->AddCC('ruineto11@gmail.com', 'Rui'); // Copia
     $mail->AddBCC( $setup_EmailBCC, 'copia backup'); // Cópia Oculta
     //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo
 
 
     //Define o Titulo do email
     $mail->Subject = $titulov; //Assunto do e-mail
 
     //Define o corpo do email
     $mail->MsgHTML( $msgv ); 
 
     ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
     //$mail->MsgHTML(file_get_contents('arquivo.html'));
 
     $mail->Send();

     // echo "Mensagem enviada com sucesso</p>\n"; 
    $msg->status='E-mail Enviado';
}
catch (phpmailerException $e) 
{
      //echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
      $msg->status = $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
}



header( 'Content-type: application/json' );
print(  json_encode( $msg ) );


?>
