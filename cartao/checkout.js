/*
==============================================================================
                VALIDAÇÕES DO FORMULÁRIO DE CHECKOUT (INÍCIO) 
                $("#id-generico").change(function(){ ... })
==============================================================================
*/

    // VALIDAÇÃO EMAIL (INÍCIO)
    $("#email").change(function(){
        
        let email = $(this).val();
        var el1 = $('#email');

        if(email == ""){

            el1.css('border', '1px solid #ccc');
            el1.html('');
            el1.removeClass('is-valid');
            el1.addClass('is-invalid');
            el1.parent().find('[name=feedback1]').html('Por favor, insira um endereço de e-mail válido, para atualizações de entrega.').css('color', '#e74a3b');
            el1.css('border', '1px solid #e74a3b');

            //return false;
        } else {

            el1.css('border', '1px solid #ccc');
            el1.html('');
            el1.removeClass('is-invalid');
            el1.addClass('is-valid');
            el1.parent().find('[name=feedback16]').html('Preenchido.').css('color', '#1cc88a');
            el1.css('border', '1px solid #1cc88a');

            //return true;
        }
    }); // VALIDAÇÃO EMAIL (FIM)

/*
==============================================================================
                VALIDAÇÕES DO FORMULÁRIO DE CHECKOUT (FIM) 
                $("#id-generico").change(function(){ ... })
==============================================================================
*/
