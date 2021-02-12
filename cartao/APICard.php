<?php
include("../conf/conexao.php");

$APIbasic = base64_encode("zpk_test_EzCkzFFKibGQU6HFq7EYVuxI".":"."Zoop@2020");
$APImkt = "3249465a7753536b62545a6a684b0000";

class toCreateSyncBuyer {
    var $comprador = '';
    var $cartao = '';
}

class User {
    var $cpf = '';
    var $email = '';
    var $nome = '';
    var $celular = '';
    var $cep = '';
    var $endereco = '';
    var $complemento = '';
    var $numero = '';
    var $bairro = '';
    var $cidade = '';
    var $uf = '';
    var $card_nome = '';
    var $card_number = '';
    var $card_venc = '';
    var $card_cvv = '';
}

$usuario = new User();
$infos = new toCreateSyncBuyer();


$usuario->cpf =         isset($_REQUEST['cpf'])?       preg_replace('/[^0-9]/', '', $_REQUEST['cpf']):campoObrigatorio("CPF");
$usuario->email =       isset($_REQUEST['email'])?                        $_REQUEST['email']:campoObrigatorio("E-MAIL");
$usuario->nome =        isset($_REQUEST['nome'])?                          $_REQUEST['nome']:campoObrigatorio("NOME");
$usuario->celular =     isset($_REQUEST['celular'])?  preg_replace('/[^0-9]/', '',$_REQUEST['celular']):campoObrigatorio("CELULAR");
$usuario->cep =         isset($_REQUEST['cep'])?      preg_replace('/[^0-9]/', '',$_REQUEST['cep']):campoObrigatorio("CEP");
$usuario->endereco =    isset($_REQUEST['endereco'])?              $_REQUEST['endereco']:campoObrigatorio("ENDEREÇO");
$usuario->complemento = isset($_REQUEST['complemento'])?              $_REQUEST['complemento']:" ";
$usuario->numero =      isset($_REQUEST['numero'])?                    $_REQUEST['numero']:campoObrigatorio("NÚMERO");
$usuario->bairro =      isset($_REQUEST['bairro'])?                    $_REQUEST['bairro']:campoObrigatorio("BAIRRO");
$usuario->cidade =      isset($_REQUEST['cidade'])?                    $_REQUEST['cidade']:campoObrigatorio("CIDADE");
$usuario->uf     =      isset($_REQUEST['uf'])?                                $_REQUEST['uf']:campoObrigatorio("UF");
$usuario->card_nome   = isset($_REQUEST['card_nome'])?   $_REQUEST['card_nome']:campoObrigatorio("TITULAR DO CARTÃO");
$usuario->card_number = isset($_REQUEST['card_number'])?$_REQUEST['card_number']:campoObrigatorio("NÚMERO DO CARTÃO");
$usuario->card_venc =   isset($_REQUEST['card_venc'])?explode("/",$_REQUEST['card_venc']):campoObrigatorio("DATA DE VENCIMENTO");
$usuario->card_cvv =    isset($_REQUEST['card_cvv'])?                   $_REQUEST['card_cvv']:campoObrigatorio("CVV");

$nome_verificacao = (substr(strstr($usuario->nome," "), 1) == null)?campoObrigatorio("NOME COMPLETO"):"";

$card = array(
  'holder_name' => $usuario->card_nome
, 'expiration_month' => $usuario->card_venc[0]
, 'expiration_year' => $usuario->card_venc[1]
, 'card_number' => $usuario->card_number
, 'security_code' => $usuario->card_cvv
);

$dados = array('address' => array( 
    'line1' => $usuario->endereco
    , 'line2' => $usuario->numero
    , 'line3' => $usuario->bairro
    , 'neighborhood' => $usuario->complemento
    , 'city' => $usuario->cidade
    , 'state' => $usuario->uf
    , 'postal_code' => preg_replace('/[^0-9]/', '', $usuario->cep)
    , 'country_code' => 'BR' 
    )
, 'first_name' => strstr($usuario->nome, ' ', true)
, 'last_name' => substr(strstr($usuario->nome," "), 1)
, 'email' => $usuario->email
, 'phone_number' => $usuario->celular
, 'taxpayer_id' => $usuario->cpf
);

if(checarComprador($conn, $usuario->cpf, $infos, $usuario)){
    if(gerarToken(json_encode($card), $APImkt, $APIbasic, $infos)){
        associarCobranca($infos, $APImkt, $APIbasic);
    }
}

/*
FUNÇÕES
*/

function checarComprador($conn, $cpf, $infos, $usuario){

    $sql = "select id_cliente, chave_integracao from cob_cliente where cpf = '$cpf'";
    $result = $conn->query( $sql );

    if($result->rowCount() > 0){
        $rows = $result->fetchAll();
        foreach( $rows as $r)
        {
            $infos->comprador = isset($r['chave_integracao']) ? $r['chave_integracao']: compradorAPI($conn, $r['id_cliente'], $GLOBALS['APImkt'], $GLOBALS['APIbasic'], $usuario);
        }
        if($infos->comprador){
            return true;
        }else{
            return false;
        }
    }else{
        $infos->comprador = compradorAPI($conn, null, $GLOBALS['APImkt'], $GLOBALS['APIbasic'], $usuario);
        if($infos->comprador){
            return true;
        }else{
            return false;
        }
    }
}

function compradorAPI($conn, $id_cliente, $APImkt, $APIbasic, $usuario){
        $id_empresa = 1;
        $ch = curl_init();
  
        curl_setopt($ch, CURLOPT_URL, "https://api.zoop.ws/v1/marketplaces/$APImkt/buyers");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($GLOBALS['dados']));
  
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = "Authorization: Basic $APIbasic";
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $result = json_decode($result, true);

        $APIbuyerCreate = isset($result['id'])?$result['id']:false;

        if($APIbuyerCreate){
            if($id_cliente == null){
                //NEXT SEQUENCE
                $sql = "select nextval('cob_cliente_seq') as id ";
                $result = $conn->query( $sql );
                $rows = $result->fetchAll();
                foreach( $rows as $r)
                {
                $id_cliente = $r['id'];
                }

                $sql = "INSERT INTO cob_cliente(" ;
                    $sql .= " id_cliente ";
                    $sql .= " , id_empresa ";
                    $sql .= " , nome ";
                    $sql .= " , cpf ";
                    $sql .= " , celular ";
                    $sql .= " , email ";
                    $sql .= " , cep ";
                    $sql .= " , endereco ";
                    $sql .= " , numero ";
                    $sql .= " , complemento ";
                    $sql .= " , bairro ";
                    $sql .= " , uf ";
                    $sql .= " , cidade ";
                    $sql .= " , sobrenome ";
                    $sql .= " , chave_integracao ";
                    $sql .= " , st_cliente ";
                    $sql .= ") values( ";
                    $sql .= " " . $id_cliente ;
                    $sql .= " , " . $id_empresa ;
                    $sql .= " , '" . strstr($usuario->nome, ' ', true). "'" ;
                    $sql .= " , '" . $usuario->cpf . "'" ;
                    $sql .= " , '" . $usuario->celular . "'" ;
                    $sql .= " , '" . $usuario->email . "'" ;
                    $sql .= " , '" . $usuario->cep . "'" ;
                    $sql .= " , '" . $usuario->endereco . "'" ;
                    $sql .= " , '" . $usuario->numero . "'" ;
                    $sql .= " , '" . $usuario->complemento . "'" ;
                    $sql .= " , '" . $usuario->bairro . "'" ;
                    $sql .= " , '" . $usuario->uf . "'" ;
                    $sql .= " , '" . $usuario->cidade . "'" ;
                    $sql .= " , '" . substr(strstr($usuario->nome," "), 1) . "'" ;
                    $sql .= " , '" . $APIbuyerCreate . "'" ;
                    $sql .= " , 'INTEGRADO'" ;
                $sql .= " )";
            
                $rs = $conn->prepare($sql);
                $rs->execute();
            }else{
                    $sql  = "update cob_cliente set ";
                    $sql .= "  chave_integracao = '" . $APIbuyerCreate . "'";
                    $sql .= ", st_cliente =  'INTEGRADO' ";
                    $sql .= " where id_cliente =  " . $id_cliente;
                    $rs2 = $conn->prepare( $sql );
                    $rs2->execute();
            }

            return $APIbuyerCreate;
        }else{
            return null;
        }
         
}


function gerarToken($dados, $APImkt, $APIbasic, $infos){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.zoop.ws/v1/marketplaces/$APImkt/cards/tokens");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);

    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = "Authorization: Basic $APIbasic";
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    $result = json_decode($result, true);

     if(isset($result['id'])){
        $infos->cartao = $result['id'];
        return true;
     }else if($result['error']){
        echo $result['error']['message'];
     }  
}


function associarCobranca($infos, $APImkt, $APIbasic){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.zoop.ws/v1/marketplaces/$APImkt/cards");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"token\":\"$infos->cartao\",\"customer\":\"$infos->comprador\"}");
    
    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = "Authorization: Basic $APIbasic";
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    $result = json_decode($result, true);

    echo "OK";
}

function campoObrigatorio($campo){
    //echo "Falta $campo";
    exit();
}

?>