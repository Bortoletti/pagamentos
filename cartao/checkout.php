<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="DELTASYSTEMS">
    <title>Pagamento com Cartão de Crédito</title>
    <link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Principal CSS do Bootstrap -->
    <!-- Estilos customizados para esse template <link href="form-validation.css" rel="stylesheet"> -->
    <!-- VUE <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->
</head>
<!--head-->

<body style="background-image:url(../boleto/images/bg-banner.jpg)">
    <div id='appVue' class="bg-light mx-md-5 mt-md-5 mx-1 mt-2">
        <div class="container">
            <form id='formCheckout' name='formCheckout' action='../checkout_processar.php' class="needs-validation" novalidate>

            <div class="row">
          <!-- ====================================================================
                                    PAGAMENTOS (INÍCIO)
             style="width: 40px; position: absolute; right: 11px; top: -15px;
          ========================================================================= -->
                <div class="col-md-4 order-md-3 mb-4">
                    <img src="../boleto/images/logo.jpg" 
                    alt="logo" >
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Pagamento</span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">Mensalidade</h6>
                                <small class="text-muted">Curso de testes 1/2</small>
                            </div>
                            <span class="text-muted">R$12</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (BRL)</span>
                            <strong>R$20</strong>
                        </li>
                    </ul>

                </div>
                <!--col-md-4 order-md-2 mb-4-->
                <!-- ====================================================================
                                            PAGAMENTOS (FIM)
                ========================================================================= -->     
                <!-- ====================================================================
                                        CARTÃO (INÍCIO)
              ========================================================================= -->
                <div class="col-md-11 order-md-1 mb-4">
                    <h4 class="mb-3">Cartão</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cc-nome">Titular do cartão <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" id="ccnome" autocomplete="off">
                            <small class="text-muted">Nome completo, como mostrado no cartão.</small>
                            <span name="feedback11" style="width: 100%;margin-top: .25rem;font-size: 80%;display: block;"></span>
                            <!--feedback-->
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cc-numero">Número do cartão <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ccnumero" autocomplete="off">
                            <span name="feedback12" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                            <!--feedback-->
                            <img id="bandeira" style="position: absolute; right: -30px; top: 44px; width: 36px; display:none;" src="./assets/bandeiras/logo_amex.jpg">
                        </div>
                    </div>
                    <!--row-->
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="cc-expiracao">Vencimento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ccvencimento" autocomplete="off">
                            <span name="feedback13" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                            <!--feedback-->
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="cc-cvv">CVV <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cccvv" autocomplete="off">
                            <span name="feedback14" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                            <!--feedback-->
                        </div>
                    </div>
                    <!--row-->


                    <hr class="mb-4">

                    <button id="checkoutBtn" onClick='checkoutClick()' class="btn btn-primary btn-lg btn-block" type="button">Checkout</button>

                    <!--form-->
                </div>
                <!-- ====================================================================
                                        CARTÃO (FIM)
                ========================================================================= -->
                <!--col-md-8 order-md-1-->
           
            <!-- ====================================================================
                                      CLIENTE (INÍCIO)
            ========================================================================= -->
                <div class="col-md-8 order-md-2">
                    <h4 class="mb-3">Cliente</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="primeiroNome">CPF <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cpf" autocomplete="off">
                                <span name="feedback1" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                                <!--feedback-->
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" value="<?php echo isset($_REQUEST['email'])?$_REQUEST['email'] : " ";?>" autocomplete="off">
                                <span name="feedback2" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                                <!--feedback-->
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="primeiroNome">Nome <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nome" autocomplete="off">
                                <span name="feedback3" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                                <!--feedback-->
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="celular">Celular <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="celular" autocomplete="off">
                                <span name="feedback4" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                                <!--feedback-->
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cep">CEP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cep" autocomplete="off">
                                <span name="feedback5" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                                <!--feedback-->
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endereco">Endereço <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="endereco" autocomplete="off">
                                <span name="feedback6" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                                <!--feedback-->
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="endereco">Número <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="numero" autocomplete="off">
                                <span name="feedback7" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                                <!--feedback-->
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Complemento">Complemento</label>
                                <input type="text" class="form-control" id="complemento" autocomplete="off">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bairro">Bairro <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="bairro" autocomplete="off">
                                <span name="feedback8" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                                <!--feedback-->
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bairro">Cidade <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cidade" autocomplete="off">
                                <span name="feedback9" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                                <!--feedback-->
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estado">UF <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" id="uf" autocomplete="off">
                                <span name="feedback10" style="width: 100%;margin-top: .25rem;font-size: 80%;"></span>
                                <!--feedback-->
                            </div>
                        </div>
                        <!--row-->
                </div>
                <!--col-md-8 order-md-1-->
                <!-- ====================================================================
                                        CLIENTE (FIM)
              ========================================================================= -->
            </div>
            <!--row-->
            </form>

            <footer class="mt-5 pt-5 text-muted text-center text-small">
                <p class="mb-1">Copyright &copy; Delta Systems 2020</p>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="#">Privacidade</a></li>
                    <li class="list-inline-item"><a href="#">Termos</a></li>
                    <li class="list-inline-item"><a href="#">Suporte</a></li>
                </ul>
            </footer>
            <!--footer-->
        </div>
        <!--container-->
    </div>
    <!--app-->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!--jquery-->
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <!--vue.js-->
    <script src="../../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="../../assets/js/jquery.mask.min.js"></script>
    <script src="kateti-library.js"></script>
    <script src="checkout.js"></script>
    <script>
        var params = window.location.search.replace("?k=", "");
        //window.alert( 'Parametros = ' + params );
        appVuewv = new Vue({
            el: '#appVue',
            data: {
                //nome : 'teste de nome'
            }
        });
    </script>

<script>

function checkoutClick()
{
    console.log( 'Enviar Formulario' );

  document.getElementById('formCheckout').submit();

}

</script>
</body>
<!--body-->

</html>