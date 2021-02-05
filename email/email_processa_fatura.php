<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../conf/conexao.php");
include("../conf/util.php");
include("./_setup.php"); // variaveis para configurar Servidor de e-mail.

$fileLog = 'email_fatura';
logar( $fileLog, '**************************  EMAIL  ***********************************' );

logar( $fileLog, __FILE__ );
//======================================================
// PARAMETROS
//======================================================
$idp = ( isset( $_REQUEST['id'] ) )?$_REQUEST['id']:0;

if( $idp == 0 )
{
  $logar( $fileLog, "Nada a enviar ");
  exit;
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
  public $boleto_url = ''; 
  public $boleto_digitavel = '';
}



$msg = new Mensagem();


// Inclui o arquivo class.phpmailer.php localizado na pasta class
require_once("./class.phpmailer.php");


function cryptJupter($id){
 return base64_encode($id. "/". md5(uniqid(rand(), true)));
}
//======================================================
// QUERY DE PARAMETROS 
//======================================================


try {
  $msg->status='';
  //============ ===== =====================
  $sql  = '  SELECT id_lancamento, id_pai, id_empresa, cpf, nome, celular, email, ';
  $sql .= 'cep, id_cliente, dt_vencto, parcelas, nr_referencia, descricao, ';
  $sql .= 'vl_total, vl_saldo, desconto_vl, desconto_pc, desconto_dias, ';
  $sql .= 'desconto_dt, mora_vl, mora_perc, juros_dia_atraso_vl, boleto_url,'; 
  $sql .= 'boleto_id, boleto_digitavel, endereco, numero, complemento, bairro, ';
  $sql .= 'uf, cidade, st_lancamento, dt_inclusao, ts_inclusao, boleto_url_original';
  $sql .= ", ( select d.email from cob_empresa d where d.id_empresa = i.id_empresa ) as email_empresa ";
  $sql .= 'from cob_lancamento i ';
  $sql .= 'where id_lancamento = '. $idp;

  $msg->log =  $sql ; 
  logar( $fileLog,  $sql );
  // return;

  $result = $conn->query( $sql );
  $rows = $result->fetchAll();

  $listaEmails = array();

  foreach( $rows as $r)
  {
    $listaEmails[] = $r['email'];
    $listaEmails[] = $r['email_empresa'];

    $msg->status = '100';
    $msg->nome = $r['nome'];
    $msg->cpf = $r['cpf'];
    $msg->email = $r['email'];
    $msg->parcelas = $r['parcelas'];
    $msg->vl_total = $r['vl_total'];
    $msg->nr_referencia = $r['nr_referencia'];
    $msg->id_lancamento = $r['id_lancamento'];
    $msg->$email_empresa = $r['email_empresa'];
    $msg->$boleto_url = $r['boleto_url'];
    $msg->$boleto_digitavel = $r['boleto_digitavel'];
    $emailDestinatario = $r['email'];
    $urlboleto = $r['boleto_url'];
    $digitavelboleto = $r['boleto_digitavel'];
    $urlcustomboleto = "//www.deltasystems.com.br/o2/boletos2/boleto_itau.php?id=". cryptJupter($r['id_lancamento']);
    $urlcustomboleto = "https://www.deltasystems.com.br/o2/boletos2/boleto_itau.php?id=". $r['id_lancamento'] ;

    logar( $fileLog,  'Email destinatario: ' . $msg->email );
    logar( $fileLog,  'Email Empresa: ' . $msg->$email_empresa );
  }	  

}
catch(PDOException $e) {
  $msg->status='999-FALHA';
  $msg->log .= "Error: " . $e->getMessage();
  header( 'Content-type: application/json' );
 logar( $fileLog,  json_encode( $msg ) );
}

if( $msg->status != '100')
{
  $msg->status = 'SQL Invalido, falha: ' . $msg->status ;
  logar( $fileLog,   json_encode( $msg ) );
  logar( $fileLog,  'SQL Invalido, falha: ' . json_encode( $msg ) );
  exit;

}


//================================================================
// HTML DO CORPO DO E-MAIL
// VARIAVEL HTMLV É CRIADA E CONTEUDO PROCESSADO ESTA NELA.
//================================================================
//$emailDestinatario = $msg->$email;


include('email_processa_fatura_html.php');



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

foreach( $listaEmails as $emailItem )
{
  $emailDestinatario = $emailItem;

  logar( $fileLog,  '==========   ENVIAR EMAIL   =========== '  );
  logar( $fileLog,  'Email Destinatario: ' . $emailItem );

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
       
       logar( $fileLog, 'API Email Destinatario: ' . $emailDestinatario );   
       logar( $fileLog,  'API Email  CC: ' . $msg->email_empresa );
       logar( $fileLog,  'API Email BCC: ' . $setup_EmailBCC );
   
       //Define os destinatário(s)
       //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
       // $mail->AddAddress('flavio@deltasystems.com.br', 'Teste email');
        $mail->AddAddress( $emailDestinatario, 'Destino');
       //Campos abaixo são opcionais 
       //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
       //$mail->AddCC( $msg->email_empresa, 'Cobranca'); // Copia
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
      logar( $fileLog,   'Enviado.' );
  }
  catch (phpmailerException $e) 
  {
        //echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
        $msg->status = 'Falhou email: ' . $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
       logar( $fileLog,   $e->errorMessage()  );
  }
  
} // fim dos emails



header( 'Content-type: application/json' );
logar( $fileLog,  json_encode( $msg ) );



?>
