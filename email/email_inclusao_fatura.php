<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../conf/conexao.php");
include("../conf/util.php");
include("./_setup.php"); // variaveis para configurar Servidor de e-mail.

function wlog( $msgv )
{
  // $log = "\n# " . date_format( date_create()  , 'd/m/Y H:i:s') . "; $msgv ";  
  // $arq = "../logs/email_fatura_". date_format( date_create()  , 'YmdH') . "0000.log";  
  // file_put_contents( $arq, $log, FILE_APPEND );
  logar( 'email_inclusao_fatura', $msgv );
}

//======================================================
// PARAMETROS
//======================================================
$idp = ( isset( $_REQUEST['id'] ) )?$_REQUEST['id']:0;

if( $idp == 0 )
{
  print( "Nada a enviar ");
  return;
}

$emailDestinatario = 'suporte@deltasystems.com.br';
//======================================================
// CLASSE PARA SER USADA NA MENSAGEM DE E-MAIL
//======================================================
class Mensagem
{
  public $status = '100-OK';
  public $log = '';
  public $nome = '';
  public $cpf = '';
  public $email = '';
  public $parcelas = '';
  public $vl_total = '';
  public $descricao = '';
  public $nr_referencia  = ''; 
  public $id_lancamento = '';
  public $email_empresa = '';
}

$msg = new Mensagem();


// Inclui o arquivo class.phpmailer.php localizado na pasta class
require_once("./class.phpmailer.php");



//======================================================
// QUERY DE PARAMETROS 
//======================================================


try {
  $msg->status='';
  //============ ===== =====================


  $sql  = 'select nome, cpf, email, parcelas, vl_total, descricao, nr_referencia , id_lancamento ';
  $sql .= ", ( select d.email from cob_empresa d where d.id_empresa = i.id_empresa ) as email_empresa ";
  $sql .= 'from cob_lancamento i ';
  $sql .= 'where id_lancamento = '. $idp;
  // print( $sql ); 
  // return;

  $result = $conn->query( $sql );
  $rows = $result->fetchAll();

  $listaEmails = array();

  foreach( $rows as $r)
  {
    $msg->status = '100';
    $msg->nome = $r['nome'];
    $msg->cpf = $r['cpf'];
    $msg->email = $r['email'];
    $msg->parcelas = $r['parcelas'];
    $msg->vl_total = $r['vl_total'];
    $msg->nr_referencia = $r['nr_referencia'];
    $msg->id_lancamento = $r['id_lancamento'];
    $msg->$email_empresa = $r['email_empresa'];

    $listaEmails[] = $r['email_empresa'];
    $listaEmails[] = $r['email'];
  }	  

}
catch(PDOException $e) {
  $msg->status='999-FALHA';
  $msg->log .= "Error: " . $e->getMessage();
}

if( $msg->status != '100')
{
  $msg->status = 'E-mail Invalido, falha: ' . $msg->status ;
  print(  json_encode( $msg ) );
  return;

}


//================================================================
// HTML DO CORPO DO E-MAIL
// VARIAVEL HTMLV É CRIADA E CONTEUDO PROCESSADO ESTA NELA.
//================================================================
$emailDestinatario = $msg->$email_empresa;


include('email_inclusao_fatura_html.php');
//print( $htmlv );
//return;


//---------------------------------------------------
$titulov = "O2 Pagamentos - Nova Fatura - " . $msg->nome . ' - ' . $msg->id_lancamento ; 
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

foreach( $listaEmails as $itemEmail )
{
  $emailDestinatario = $itemEmail;

  wlog( "Enviar: " . $itemEmail  );

  try 
  {
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
        $mail->AddAddress( $emailDestinatario, 'Destino');
   
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
      wlog( 'Concluido');
  }
  catch (phpmailerException $e) 
  {
        //echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
        $msg->status = 'Falhou email: ' . $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
        wlog( 'Falhou email: ' . $e->errorMessage() );
  }
}
 




header( 'Content-type: application/json' );
print(  json_encode( $msg ) );
wlog( json_encode( $msg )  );

?>
