<!doctype html>

<?php
include('./conf/conexao.php');
// https://www.deltafin.com.br/cobranca/cartao/checkout.php?p=i3zwa9399793naz38
if( ! isset( $_GET['idp'] ) ){
	print('Invalido'); 
	return;
}


// LER PARAMETROS
//=======================================================================
$agencia = '';
$url     = '';

$sql = " select agencia, host_url from cob_parceiro ";

 
$rs = $conn->prepare( $sql );
$rs->execute();

$result = $rs->fetchAll();
// print_r($result);
foreach( $result as $r )
{
	$agencia = $r['agencia'];
	$url     = $r['host_url'];
}



// LER TOTAL da DIVIDA
//=======================================================================
$sql = "
SELECT nome
, ( select d.nome_comunicacao 
      from cob_empresa d 
      where d.id_empresa = i.id_empresa ) as razao
, sum( vl_saldo ) as valor
from cob_lancamento i
where id_lancamento = "  . $_GET['idp'] . " group by id_empresa, nome "; 

// print( $sql );
// return;

$razao = '';
$nome = '';
$valor = 0;
 
$rs = $conn->prepare( $sql );
$rs->execute();

$result = $rs->fetchAll();
// print_r($result);
foreach( $result as $r )
{

  $razao = $r['razao'];
  $nome  = $r['nome'];;
  $valor = $r['valor'];
 
}





?>


<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="cache-control"   content="no-cache" />
    <meta http-equiv="expires" content = "Mon, 22 jul 2006 11:12:01 GMT" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Pagamento DeltaFIN</title>
  </head>
  <body>

  <div class="container">



<div class="container-fluid">

<form name='frm' id='frm' method='POST' action='../pjbank/pagamentoCartao.php'>
	<input type='hidden' id='valorPagarFld' value='0.00'>



	<br>Sacado
	<br><div id='nomeCliente'><?=$nome;?></div>
	<br>Divida a Pagar
  <br><div id='valorApagar'>R$<?=$valor;?></div>
  <br><div><?=$razao;?></div>
<hr>

  <div class="form-group">
    <label for="cartaoValorPagarFld">Valor a Pagar*</label>
    <input type="text" class="form-control" name='cartaoValorPagarFld' id="cartaoValorPagarFld" aria-describedby="emailHelp" placeholder='R$999.99'>
  </div>
	
  <div class="form-group">
    <label for="cartaoNrFld">Nr. Cartao*</label>
    <input type="text" class="form-control" name='cartaoNrFld' id="cartaoNrFld" aria-describedby="emailHelp" placeholder='9999-9999-9999-9999'>
  </div>

  <div class="form-group">
    <label for="cartaoNomeFld">Nome no Cartao*</label>
    <input type="text" class="form-control" name='cartaoNomeFld' id="cartaoNomeFld" aria-describedby="emailHelp" placeholder='NOME DO CARTAO'>
  </div>

		


  <div class="form-group">
    <label for="cartaoVenctoMesFld">Mes de Vencimento*</label>
    <select class="form-control" id="cartaoVenctoMesFld" name="cartaoVenctoMesFld">
      <option>01</option>
      <option>02</option>
      <option>03</option>
      <option>04</option>
      <option>05</option>
      <option>06</option>
      <option>07</option>
      <option>08</option>
      <option>09</option>
      <option>10</option>
      <option>11</option>
      <option>12</option>
    </select>
  </div>
	
  <div class="form-group">
    <label for="cartaoVenctoAnoFld">Ano de Vencimento*</label>
    <input type="text" class="form-control" name='cartaoVenctoAnoFld' id="cartaoVenctoAnoFld" aria-describedby="emailHelp" placeholder='2020'>
  </div>
	
  <div class="form-group">
    <label for="cartaoCvvFld">CVV*</label>
    <input type="text" class="form-control" name='cartaoCvvFld' id="cartaoCvvFld" aria-describedby="emailHelp" placeholder='999'>
  </div>


 <div class="form-group">
    <label for="cartaoCpfFld">CPF*</label>
    <input type="text" class="form-control" name='cartaoCpfFld' id="cartaoCpfFld" aria-describedby="emailHelp" placeholder='999.999.999-99'>
  </div>

  <div class="form-group">
    <label for="cartaoCelularFld">Celular*</label>
    <input type="text" class="form-control" name='cartaoCelularFld' id="cartaoCelularFld" aria-describedby="emailHelp" placeholder='(11) 9.9999-9999'>
  </div>
	
  <div class="form-group">
    <label for="cartaoEmailFld">Email*</label>
    <input type="email" class="form-control" name='cartaoEmailFld' id="cartaoEmailFld" aria-describedby="emailHelp" placeholder='nome@email.com.br'>
  </div>


	

  <br>
<button type="button" id='cmdProcessarx' class="btn btn-primary">PROCESSAR</button>
</form>

</div>

</div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>





</body>

</html>