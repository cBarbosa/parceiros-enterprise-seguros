
$(document).ready(function(){
    $("#div_sucesso").hide();
	$("#div_erro").hide();
	$("#div_loading").hide();
	$('#val-nascimento').mask('00/00/0000');
	
	//$('#modal-fade').modal({ keyboard: false });
	
	$("#btn-envia-email").click(function(e){
		e.preventDefault();
		$("#form-validation-reenv").submit();
	  //$('form[form-validation-reenv]').submit();
	});

	$("#btn-visualiza").click(function(e)
	{
		var url = "ajax/geracertificadoajax.php?val-cpfcliente=" + $('#val-cpf-reenv').val() +'&val-certificado='+ $('#val-certificado-reenv').val();
		window.open(url, "certificado", "toolbar=0");
		e.preventDefault();
	});
});

var FormValidationVenda = function() {

    return {
        init: function() {
            /*
             *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
             */

            /* Initialize Form Validation */
            $('#form-validation').validate({
                errorClass: 'help-block animation-pullUp', // You can change the animation class for a different entrance animation - check animations page
                errorElement: 'div',
                errorPlacement: function(error, e) {
                    e.parents('.form-group > div').append(error);
                },
                highlight: function(e) {
                    $(e).closest('.form-group').removeClass('has-success has-error').addClass('has-error');
                    $(e).closest('.help-block').remove();
                },
                success: function(e) {
                    // You can use the following if you would like to highlight with green color the input after successful validation!
                    e.closest('.form-group').removeClass('has-success has-error').addClass('has-success'); // e.closest('.form-group').removeClass('has-success has-error').addClass('has-success');
                    e.closest('.help-block').remove();
                },
                rules: {
                    'val-cpfcliente': {
                        required: true,
                        minlength: 11,
						digits: true
                    },
					'val-nome': {
                        required: true,
                        minlength: 10
                    },
					/*
                    'val-mail': {
                        required: true,
                        email: true
                    },*/
					'val-sexo': {
                        required: true
                    },
					'val-estadocivil': {
                        required: true
                    },
					'val-nascimento': {
                        required: true
                    },
					'val-cep': {
                        required: true,
						minlength: 8,
						maxlength: 8,
						digits: true
                    },
					'val-uf': {
                        required: true
                    },
					'val-logradouro': {
                        required: true
                    },
					'val-logradouron': {
                        required: true,
						digits: true
                    },
					'val-logradouroc': {
                        required: true
                    },
					'val-logradourob': {
                        required: true
                    },
					'val-evento':{
						required: 'Selecione o evento'
					},
					'val-ingresso': {
                        required: true
                    },
					'val-vingresso': {
                        required: true,
						number: true
                    }
                },
                messages: {
                    'val-cpfcliente': {
                        required: 'Por favor digite o CPF',
                        minlength: 'O CPF precisa conter ao menos 11 caracteres',
						digits: 'Neste campo somente numeros'
                    },
					'val-nome': {
                        required: 'Por favor digite o Nome',
                        minlength: 'O Nome precisa conter ao menos 10 caracteres'
                    },
					/*
                    'val-mail': 'Por favor digite um email valido',
					*/
					'val-sexo': 'Selecione o sexo!',
					'val-estadocivil': 'Selecione o estado civil!',
					'val-nascimento': 'Selecione a data de nascimento!',
					'val-cep':{
						required: 'Digite o CEP',
						minlength: 'O CEP precisa conter ao menos 8 caracteres',
						maxlength: 'O CEP precisa conter ao somente 8 caracteres',
						digits: 'Neste campo somente numeros'
					},
					'val-uf':{
						required: 'Selecione a UF do enrereco'
					},
					'val-logradouro': 'Digite o endereco',
					'val-logradouron': {
                        required: 'Digite o numero do endereco.',
                        digits: 'Neste campo somente numeros'
                    },
					'val-logradouroc': 'Digite a cidade',
					'val-logradourob': 'Digite o bairro',
					'val-evento':{
						required: 'Selecione o evento'
					},
					'val-ingresso': 'Digite o numero do ingresso',
					'val-vingresso': {
                        required: 'Digite o identificador do ingresso.',
                        number: 'Neste campo insira um valor valido. ex: 15.78'
                    }
                },
				submitHandler: function( form )	{
					$("html, body").animate({ scrollTop: 0 }, "slow");
					$("#div_loading").show();
					$("#div_row").hide();

					var dados = $( form ).serialize();
					var action = $("#form-validation").attr('action');

					var form_data = {
						'val-evento': $('#val-evento').val(),
						'val-vingresso': $('#val-vingresso').val(),
						'val-ingresso': $('#val-ingresso').val(),
						'val-mail': $('#val-mail').val(),
						'val-nome': $('#val-nome').val(),
						'val-cpfcliente': $('#val-cpfcliente').val(),
						'val-nascimento': $('#val-nascimento').val(),
						'val-estadocivil': $('#val-estadocivil').val(),
						'val-sexo': $('#val-sexo').val(),
						'val-logradouro': $('#val-logradouro').val(),
						'val-logradouro2': $('#val-logradouro2').val(),
						'val-logradouron': $('#val-logradouron').val(),
						'val-logradourob': $('#val-logradourob').val(),
						'val-logradouroc': $('#val-logradouroc').val(),
						'val-cep': $('#val-cep').val(),
						'val-uf': $('#val-uf').val(),
						is_ajax: 1
					};

					$.ajax({
						url: action,
						type: 'POST',
						data: form_data,
						datatype:'json',
						success: function(data, status)
						{
							if(data.CodRetorno == "0")
							{
								$("#texto_certificado").text(data.NumeroProposta);
								$("#texto_sorteio").text(data.NumeroSorte);
								$("#texto_ingresso").text(data.NumeroIngresso);
								
								$("#val-mail-reenv").val($("#val-mail").val());
								$("#val-cpf-reenv").val($('#val-cpfcliente').val());
								$("#val-certificado-reenv").val(data.NumeroProposta);

								$("#div_sucesso").show();
								$("#div_erro").hide();
								$('#modal-fade').modal("show");
								//$('#modal-fade').modal({ keyboard: false });
							} else {
								$("#div_sucesso").hide();
								$("#div_erro").show();
							}
						},
						error: function(xhr, desc, err){
							$("#div_sucesso").hide();
							$("#div_erro").show();
						}
					});
					$("#div_loading").hide();
					return false;
				}
            });
        }
    };
}();

var FormValidationReenvia = function() {

    return {
        init: function() {
            /*
             *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
             */
            /* Initialize Form Validation */
            $('#form-validation-reenv').validate({
                errorClass: 'help-block animation-pullUp', // You can change the animation class for a different entrance animation - check animations page
                errorElement: 'div',
                errorPlacement: function(error, e) {
                    e.parents('.form-group > div').append(error);
                },
                highlight: function(e) {
                    $(e).closest('.form-group').removeClass('has-success has-error').addClass('has-error');
                    $(e).closest('.help-block').remove();
                },
                success: function(e) {
                    // You can use the following if you would like to highlight with green color the input after successful validation!
                    e.closest('.form-group').removeClass('has-success has-error').addClass('has-success'); // e.closest('.form-group').removeClass('has-success has-error').addClass('has-success');
                    e.closest('.help-block').remove();
                },
                rules: {
                    'val-mail': {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    'val-mail': 'Por favor digite um email valido',
                },
				submitHandler: function( form )	{
					$("html, body").animate({ scrollTop: 0 }, "slow");

					var dados = $( form ).serialize();
					var action = $("#form-validation-reenv").attr('action');

					var form_data = {
						'val-email': $('#val-mail-reenv').val(),
						'val-cpfcliente': $('#val-cpf-reenv').val(),
						'val-certificado': $('#val-certificado-reenv').val(),
						is_ajax: 1
					};

					$.ajax({
						url: action,
						type: 'POST',
						data: form_data,
						datatype:'json',
						success: function(data, status)
						{
							if(data.CodRetorno == "0")
							{
								$.bootstrapGrowl('<h4><strong>Notificacao</strong></h4> <p>Email enviado com sucesso..</p>', {
									type: 'success',
									delay: 3000,
									allow_dismiss: true,
									offset: {from: 'top', amount: 20}
								});
							} else {
								$.bootstrapGrowl('<h4><strong>Notificacao</strong></h4> <p>Erro tentando enviar email..</p>', {
									type: 'danger',
									delay: 3000,
									allow_dismiss: true,
									offset: {from: 'top', amount: 20}
								});
							}
						},
						error: function(xhr, desc, err){
							$.bootstrapGrowl('<h4><strong>Notificacao</strong></h4> <p>Erro tentando enviar email..</p>', {
								type: 'danger',
								delay: 3000,
								allow_dismiss: true,
								offset: {from: 'top', amount: 20}
							});
						}
					});
					return false;
				}
            });
        }
    };
}();

function validaCpf(str){
    str = str.replace('.','');
    str = str.replace('.','');
    str = str.replace('-','');
 
    cpf = str;
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;
    if (cpf.length < 11)
        return false;
    for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1)){
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais){
        numeros = cpf.substring(0,9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
            soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        numeros = cpf.substring(0,10);
        soma = 0;
        for (i = 11; i > 1; i--)
            soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    }
    else
        return false;
}