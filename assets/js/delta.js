$(document).ready(function(){

	// if( sessionStorage.pagina ){ sessionStorage.pagina = parseInt( sessionStorage.pagina ) + 1; }
	if( ! sessionStorage.idEmpresa ){ sessionStorage.idEmpresa = 1; }
	if( ! sessionStorage.idCliente ){ sessionStorage.idCliente = 0; }


  $('#modalFatura').modal('show');	


  $('#btnNovaFatura').click(
    function()
    {
      $('#modalFatura').modal('show');		
    } );
    
console.log('Inicio de Aplicacao.');




//===================================================================================
// DASHBOARD
//===================================================================================
function getDashboard()
{
  var urlv = "http://www.deltafin.com.br/cobranca/json/getDashboard.php";  
  console.log( urlv );
  // var htmlv = ''; // formatar comando html
  
  $.getJSON( urlv, { "idEmpresa" : sessionStorage.idEmpresa } )
    .done( function( result ){
  
      if( result.mensagem.indexOf("999") != -1 ){
        alert( "Falha ao acessar o Banco de dados.", "DELTAFIN" );
    	  return;
      }
      
      for( i=0;i<result.itens.length;i++ )
      {
        var reg = JSON.parse( JSON.stringify( result.itens[i] ) );
        // console.log( reg.filial );

       // htmlv += '<a onClick="filialFiltro( \'' + reg.cod_loja + '\')" href="" class="list-group-item">';
       // htmlv += '<i class="fa fa-comment fa-fw"> ' + reg.filial + "</i>" ;
       // htmlv += '<span class="pull-right text-muted small"><em></em></span></a>';
  
  
        $('#carteiraView').html( reg.carteira  );
        $('#vencidosView').html( reg.vencidos );  
        $('#protestadosView').html( reg.protestados );
        $('#inadimplenciaView').html( reg.inadimplencia );

  
      }
      
  // $("#viewFiliais").html( htmlv ); 
    } ); // DONE 
  

	return false;
}
  

//===================================================================================
// DEVEDORES
//===================================================================================
  function getDevedores( idDevedorP )
  {

  var urlv = "http://www.deltafin.com.br/cobranca/json/getDevedores.php";
  
  console.log( urlv );
  // var htmlv = ''; // formatar comando html

  
  $.getJSON( urlv, { "idEmpresa" : sessionStorage.idEmpresa } ) 
     .done( function( result ){
  
      if( result.mensagem.indexOf("999") != -1 ){
        alert( "Devedores: Falha ao acessar o Banco de dados.", "DELTAFIN" );
    	  return;
      }
		  var htmlv = "<table   id='devedoresTable'> ";
		  htmlv += " <thead>";
		  htmlv += " <tr>";
		  htmlv += " <th>Nome</th>";
		  htmlv += " <th>Parcelas</th>";
		  htmlv += " <th>Vencidas</th>";
		  htmlv += " <th>Saldo</th>";
		  htmlv += " </tr>";
		  htmlv += " </thead>";
		  htmlv += " <tbody>";
        
      for( i=0;i<result.itens.length;i++ )
      {
        var reg = JSON.parse( JSON.stringify( result.itens[i] ) );
        // console.log( reg.filial );

  
        var nomeV       = reg.nome;
        var faturasV    = reg.faturas;
        var atrasadasV  = reg.atrasadas;
        var saldoV      = reg.saldo;
        
        if( i == 0){
        	 getParcelas( reg.id, nomeV );
        }
        
        htmlv += " <tr> ";
        htmlv += " <td><i class='fa fa-search-plus'></i>" + nomeV + "</td>";
        htmlv += " <td>" + faturasV + "</td>";
        htmlv += " <td>" + atrasadasV + "</td>";
        // onClick='abrirCliente(" + reg.id + ");'
        htmlv += " <td class='text-right' ><a  class='clienteClasse' nomeTitulo='" + nomeV + "' chave='" + reg.id + "'  ><span>" + saldoV + "</span></a></td>";
        htmlv += " </tr>";
      }
      htmlv += "            </tbody>";
      htmlv += "</table>";

      $("#devedoresView").html( htmlv ); 
    } ); // DONE 

    return false;
  }

//===================================================================================
// PARCELAS
//===================================================================================


  function getParcelas( idClienteP, titulop )
  {
  	sessionStorage.idCliente = idClienteP;
	
	  var urlv = "http://www.deltafin.com.br/cobranca/json/getParcelas.php";
	  
	  console.log( urlv );
	  // var htmlv = ''; // formatar comando html
	  
	  $.getJSON( urlv
	    , { "idEmpresa" : sessionStorage.idEmpresa, "idCliente" : sessionStorage.idCliente } )
	     .done( function( result ){
	      console.log("Faturas...");
	      if( result.mensagem.indexOf("999") != -1 ){
	        alert( "Parcelas: Falha ao acessar o Banco de dados.", "DELTAFIN" );
	    	  return;
	      }
			  var htmlv = "        <table  id='parcelasTable'> ";
			  htmlv += " <thead>";
			  htmlv += " <tr>";
			  htmlv += "   <th>Vencimento</th>";
			  htmlv += "   <th>Parcela</th>";
			  htmlv += "   <th>Boleto</th>";
			  htmlv += "   <th>Saldo</th>";
			  htmlv += " </tr>";
			  htmlv += " </thead>";
			  htmlv += " <tbody>";      
			  
			  var totalv = 0;
	      for( i=0;i<result.itens.length;i++ )
	      {
	        var reg = JSON.parse( JSON.stringify( result.itens[i] ) );
	        // console.log( reg.filial );
	
	        
	        htmlv += " <tr> ";
	        htmlv += " <td><i class='fa fa-search-plus'></i>" + reg.dt_vencto + "</td>";
	        htmlv += " <td class='text-center' >" + reg.parcela + "</td>";
	        htmlv += " <td  class='text-center' ><a target='_blank' href='"+reg.boleto_url+"'>" + reg.boleto + "</a></td>";
	        htmlv += " <td class='text-right' >" + reg.saldo.toLocaleString( 'en-pt', {style:'currency', currency:'BRL'} )  + "</td>";
	        htmlv += " </tr>";
	        
	        totalv += parseFloat( reg.saldo );
	      }
	      htmlv += "</tbody>";
	      htmlv += "<tfooter>";
	      htmlv += "  <tr>";
	      htmlv += "    <td colspan='3'>Total</td>";
	      htmlv += "    <td><span class='price'>" + totalv.toLocaleString( 'en-pt', {style:'currency', currency:'BRL'} ) + "</span></td>";
	      htmlv += "  </tr>";
	      htmlv += "</tfooter>";
	      htmlv += "</table>";
	
	      $("#parcelasView").html( htmlv ); 
	      $('#tituloFatura').html( 'Faturas - ' + titulop );


	    } ); // DONE 
  } // function
  
 $('body').delegate('.clienteClasse', 'click'
 , function(){ 
    	// alert('teste = ' + $(this).attr('chave')); 
    	getParcelas( $(this).attr('chave') , $(this).attr('nomeTitulo') );
 	 }
 );

//===================================================================================
// PARCELAS
//===================================================================================
  $('#carteiraView').html('0');
  
  $('#vencidosView').html('0');
  
  $('#protestadosView').html('R$0');
  $('#inadimplenciaView').html( '0' );

  getDashboard();
  getDevedores();

});


