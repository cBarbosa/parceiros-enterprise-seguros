var TEXTO_BT_IMPRIMIR = ('<p align="center"><a href="#" class="btn btn-effect-ripple btn-primary" id="btn-visualiza">Imprimir o Certificado</a></p>');

 $(document).ready(function(){
	//$('#modal-fade').modal({ keyboard: false });
	
	$('#val-nascimento').mask('00/00/0000');
	$('#val-dataexpedicao').mask('00/00/0000');

    $("#div_sucesso").hide();
	$("#div_erro").hide();
	$("#lbl-premiocalc").hide();
	
	$("#btn-envia-email").click(function(e){
		e.preventDefault();
		$("#form-validation-reenv").submit();
	});
	
	$("#btn-comunica-cliente").click(function(e){
		e.preventDefault();
		var comunicou = false;
		if($("#form-validation-reenv input[type='radio']:checked").val()=="option1")
		{
			comunicou = true;
			$("#form-validation-reenv").submit();
		}
		
		if($("#form-validation-reenv input[type='radio']:checked").val()=="option2")
		{
			comunicou = true;
			var url = "ajax/geradocumentoajax.php?val-cpfcliente=" + $('#val-cpf-reenv').val() +'&val-certificado='+ $('#val-certificado-reenv').val();
			window.open(url, "documento", "toolbar=0");
			$('div.modal-body').html(TEXTO_BT_IMPRIMIR +'<P>'+ acentuarAlerts('Comunicação de Boas Vindas baixada pra impressão.')+ '</P>');
			$('#btn-comunica-cliente').hide();
			e.preventDefault();
		}
		if(!comunicou)
		{
			window.alert(acentuarAlerts(acentuarAlerts("Favor escolher uma opção de comunicação de boas vindas.")));
		}
	});

	$("#val-vingresso").blur(function(e){
		calculaValorPremio();
	});
	
	$("#val-tipoingresso").change(function(e){
		calculaValorPremio();
	});

	$("#btn-visualiza").click(function(e)
	{
		var url = "ajax/geracertificadoajax.php?val-cpfcliente=" + $('#val-cpf-reenv').val() +'&val-certificado='+ $('#val-certificado-reenv').val();
		window.open(url, "certificado", "toolbar=0");
		//$('div.modal-header').html('ACAO INDISPONIVEL');
		//$('div.modal-body').html('<P>Download do certificado ja foi finalizado.</P>');
		e.preventDefault();
	});
	
	$("#btn-boasvindas").click(function(e)
	{
		var url = "ajax/geradocumentoajax.php?val-cpfcliente=" + $('#val-cpf-reenv').val() +'&val-certificado='+ $('#val-certificado-reenv').val();
		window.open(url, "documento", "toolbar=0");
		//$('div.modal-header').html('ACAO INDISPONIVEL');
		//$('div.modal-body').html('<P>Comunicação de Boas Vindas baixada pra impressão</P>');
		$('div.modal-body').html(TEXTO_BT_IMPRIMIR + '<P>'+ acentuarAlerts('Comunicação de Boas Vindas baixada pra impressão.')+ '</P>');
		$('#btn-comunica-cliente').hide();
		e.preventDefault();
	});
	
	$("#btn-seleciona-end").click(function(e){
		e.preventDefault();
		SelecionaEndereco($("#mtp_endereco").val());
	});
	
	$("#val-evento").change(function () {
        var vle = this.value;
        //var firstDropVal = $('#pick').val();
		var form_data = {
			'idevento': vle,
			is_ajax: 1
		};
		
		$.ajax({
			url: "ajax/gettipoingressoajax.php",
			type: 'POST',
			data: form_data,
			datatype:'json',
			success: function(data, status)
			{
				if(data.codigo == "0")
				{
					var options = ""; var value = "";

					$.each(data.arrs, function(index, item) {
						value = item.id +'|'+ item.percentual;
						options += '<option value="' + value +'">'+ item.descricao +'</option>';
					});
					$("#val-tipoingresso").html(options);
				} else {
					$("#val-tipoingresso").html("");
					$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>Sem tipo de ingresso..</p>', {
					type: 'danger',
					delay: 3000,
					allow_dismiss: true,
					offset: {from: 'top', amount: 20}
				});
				}
			},
			error: function(xhr, desc, err){
				$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>Sem tipo de ingresso..</p>', {
					type: 'danger',
					delay: 3000,
					allow_dismiss: true,
					offset: {from: 'top', amount: 20}
				});
			}
		});
    });

	$("#btn-pesquisar").click(function(e){
		e.preventDefault();
		
		if($('#val-cpfcliente').val().length<11)
		{
			window.alert("cpf invalido");
			return false;
		}

		var form_data = {
			'cpfcliente': $('#val-cpfcliente').val(),
			is_ajax: 1
		};
		
		$('#val-nome').attr('readonly', false);
		$('#val-mail').attr('readonly', false);
		$('#val-sexo').attr('readonly', false);
		$('#val-estadocivil').attr('readonly', false);
		$('#val-nascimento').attr('readonly', false);
		
		$.ajax({
			url: "ajax/getclienteajax.php",
			type: 'POST',
			data: form_data,
			datatype:'json',
			success: function(data, status)
			{
				if(data.CodRetorno == "0")
				{
					var options = ""; var descr = ""; var value = "";
					$("#val-nome").val(data.cliente.nome);
					$("#val-mail").val(data.cliente.email);
					$("#val-sexo").val(data.cliente.sexo);
					$("#val-estadocivil").val(data.cliente.estadocivil);
					$("#val-nascimento").val(data.cliente.datanascimento);
					
					$("#val-documento").val(data.cliente.rg);
					$("#val-expeditor").val(data.cliente.orgaoexpeditor);
					$("#val-ufexpeditor").val(data.cliente.idufrg);
					$("#val-dataexpedicao").val(data.cliente.dataexpedicaorg);
					$.each(data.cliente.ends, function(index, item) {
						value = item.cep +'|'+ item.logradouro +'|'+ item.numero +'|'+ item.complemento +'|'+ item.bairro +'|'+ item.cidade +'|'+ item.iduf;
						descr = 'CEP: ' + item.cep + ', '+ item.logradouro +' '+ item.numero +', '+ item.complemento +', '+ item.bairro +', '+ item.cidade;
						options += '<option value="' + value +'">'+ descr +'</option>';
					});
					$("#mtp_endereco").html(options);
					$('#modal-fade1').modal({ keyboard: false });
				} else {
					$("#val-nome").val("");
					$("#val-mail").val("");
					$("#val-sexo").val("");
					$("#val-estadocivil").val("");
					$("#val-nascimento").val("");
					$("#val-documento").val();
					$("#val-expeditor").val();
					$("#val-ufexpeditor").val();
					$("#val-dataexpedicao").val();
					$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>'+ acentuarAlerts(data.Mensagem) +'</p>', {
						type: 'danger',
						delay: 3000,
						allow_dismiss: true,
						offset: {from: 'top', amount: 20}
					});
				}
			},
			error: function(xhr, desc, err){
				$("#val-nome").val("");
				$("#val-mail").val("");
				$("#val-sexo").val("");
				$("#val-estadocivil").val("");
				$("#val-nascimento").val("");
				$("#val-documento").val();
				$("#val-expeditor").val();
				$("#val-ufexpeditor").val();
				$("#val-dataexpedicao").val();
				$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>'+ acentuarAlerts("Cliente não encontrado...") +'</p>', {
					type: 'danger',
					delay: 3000,
					allow_dismiss: true,
					offset: {from: 'top', amount: 20}
				});
			}
		});
	});

	/*
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
	*/
});

var FormsWizard = function() {

    return {
        init: function() {
            /*
             *  Jquery Wizard, Check out more examples and documentation at http://www.thecodemine.org
             *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
             */

            /* Set default wizard options */
            var wizardOptions = {
				back: ':reset',
				next: ':submit',
				textSubmit: 'Finalizar',
				textNext: 'Proximo',
				textBack: 'Voltar',
                focusFirstInput: true,
                disableUIStyles: true,
                inDuration: 0,
                outDuration: 0
            };

            /* Initialize Clickable Wizard */
            var clickableWizard = $('#clickable-wizard');

            clickableWizard.formwizard(wizardOptions);

            $('.clickable-steps a').on('click', function(){
                var gotostep = $(this).data('gotostep');
                clickableWizard.formwizard('show', gotostep);
            });
			
			/* Initialize Form Validation */
            $('#clickable-wizard').validate({

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
                    'val-mail': {
                        email: true
                    },
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
					'val-tipoingresso':{
						required: 'Selecione o tipo de ingresso'
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
                    'val-mail': 'Por favor digite um email valido',
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
					'val-tipoingresso':{
						required: 'Selecione o tipo de ingresso'
					},
					'val-ingresso': 'Digite o numero do ingresso',
					'val-vingresso': {
                        required: 'Digite o identificador do ingresso.',
                        number: 'Neste campo insira um valor valido. ex: 15.78'
                    }
                },
				submitHandler: function( form )	{
					$("html, body").animate({ scrollTop: 0 }, "slow");
					//$("#div_loading").show();
					$("#clickable-wizard").hide();

					var dados = $( form ).serialize();
					var action = $('#clickable-wizard').attr('action');

					var form_data = {
						'val-tipoingresso': $('#val-tipoingresso').val(),
						'val-evento': $('#val-evento').val(),
						'val-vingresso': $('#val-vingresso').val(),
						'val-ingresso': $('#val-ingresso').val(),
						'val-mail': $('#val-mail').val(),
						'val-nome': $('#val-nome').val(),
						'val-cpfcliente': $('#val-cpfcliente').val(),
						'val-nascimento': $('#val-nascimento').val(),
						'val-estadocivil': $('#val-estadocivil').val(),
						'val-sexo': $('#val-sexo').val(),
						'val-documento': $('#val-documento').val(),
						'val-expeditor': $('#val-expeditor').val(),
						'val-ufexpeditor': $('#val-ufexpeditor').val(),
						'val-dataexpedicao': $('#val-dataexpedicao').val(),
						'val-logradouro': $('#val-logradouro').val(),
						'val-logradouro2': $('#val-logradouro2').val(),
						'val-logradouron': $('#val-logradouron').val(),
						'val-logradourob': $('#val-logradourob').val(),
						'val-logradouroc': $('#val-logradouroc').val(),
						'val-cep': $('#val-cep').val(),
						'val-uf': $('#val-uf').val(),
						'val-premiocalc': $('#lbl_vlrpremiocalc').text(),
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
								$("#texto_premio").text(data.ValorPremio);
								$("#texto_sorteio").text(data.NumeroSorte);
								$("#texto_serie").text(data.NumeroSerieSorte);
								$("#texto_ingresso").text(data.NumeroIngresso);
								
								$("#val-mail-reenv").val($("#val-mail").val());
								$("#val-cpf-reenv").val($('#val-cpfcliente').val());
								$("#val-certificado-reenv").val(data.NumeroProposta);

								$("#div_sucesso").show();
								$("#div_erro").hide();
								//$('#modal-fade').modal("show");
								$('#modal-fade').modal({ keyboard: false });
								
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
					$(".block").hide();
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
								$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>Email enviado com sucesso..</p>', {
									type: 'success',
									delay: 3000,
									allow_dismiss: true,
									offset: {from: 'top', amount: 20}
								});
								//$('div.modal-header').html('ACAO INDISPONIVEL');
								$('div.modal-body').html(TEXTO_BT_IMPRIMIR + '<P>'+ acentuarAlerts('Comunicação de boas vindas enviada por email.')+ '</P>');
								$('#btn-comunica-cliente').hide();
							} else {
								$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>Erro tentando enviar email..</p>', {
									type: 'danger',
									delay: 3000,
									allow_dismiss: true,
									offset: {from: 'top', amount: 20}
								});
							}
						},
						error: function(xhr, desc, err){
							$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>Erro tentando enviar email..</p>', {
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

function SelecionaEndereco(value)	{
	arr = value[0].split('|');
	$("#val-cep").val(arr[0]);
	$("#val-logradouro").val(arr[1]);
	$("#val-logradouron").val(arr[2]);
	$("#val-logradouro2").val(arr[3]);
	$("#val-logradouroc").val(arr[4]);
	$("#val-logradourob").val(arr[5]);
	$("#val-uf").val(arr[6]);
	$('#modal-fade1').modal('hide');
}

function calculaValorPremio()	{
	$("#lbl_vlrpremiocalc").text('0.00');
	var pct = $("#val-tipoingresso").val().split('|');
	var vlr = parseFloat((parseFloat(pct[1])/100)*parseFloat($("#val-vingresso").val()) ).toFixed(2);
	if(!isNaN(vlr))
		$("#lbl_vlrpremiocalc").text(vlr.toString());
}

function acentuarAlerts(mensagem)
{
	//Paulo Tolentino
	//Usar dessa forma: alert(acentuarAlerts('teste de acentuação, essência, carência, âê.'));
	//

	mensagem = mensagem.replace('á', '\u00e1');
	mensagem = mensagem.replace('à', '\u00e0');
	mensagem = mensagem.replace('â', '\u00e2');
	mensagem = mensagem.replace('ã', '\u00e3');
	mensagem = mensagem.replace('ä', '\u00e4');
	mensagem = mensagem.replace('Á', '\u00c1');
	mensagem = mensagem.replace('À', '\u00c0');
	mensagem = mensagem.replace('Â', '\u00c2');
	mensagem = mensagem.replace('Ã', '\u00c3');
	mensagem = mensagem.replace('Ä', '\u00c4');
	mensagem = mensagem.replace('é', '\u00e9');
	mensagem = mensagem.replace('è', '\u00e8');
	mensagem = mensagem.replace('ê', '\u00ea');
	mensagem = mensagem.replace('ê', '\u00ea');
	mensagem = mensagem.replace('É', '\u00c9');
	mensagem = mensagem.replace('È', '\u00c8');
	mensagem = mensagem.replace('Ê', '\u00ca');
	mensagem = mensagem.replace('Ë', '\u00cb');
	mensagem = mensagem.replace('í', '\u00ed');
	mensagem = mensagem.replace('ì', '\u00ec');
	mensagem = mensagem.replace('î', '\u00ee');
	mensagem = mensagem.replace('ï', '\u00ef');
	mensagem = mensagem.replace('Í', '\u00cd');
	mensagem = mensagem.replace('Ì', '\u00cc');
	mensagem = mensagem.replace('Î', '\u00ce');
	mensagem = mensagem.replace('Ï', '\u00cf');
	mensagem = mensagem.replace('ó', '\u00f3');
	mensagem = mensagem.replace('ò', '\u00f2');
	mensagem = mensagem.replace('ô', '\u00f4');
	mensagem = mensagem.replace('õ', '\u00f5');
	mensagem = mensagem.replace('ö', '\u00f6');
	mensagem = mensagem.replace('Ó', '\u00d3');
	mensagem = mensagem.replace('Ò', '\u00d2');
	mensagem = mensagem.replace('Ô', '\u00d4');
	mensagem = mensagem.replace('Õ', '\u00d5');
	mensagem = mensagem.replace('Ö', '\u00d6');
	mensagem = mensagem.replace('ú', '\u00fa');
	mensagem = mensagem.replace('ù', '\u00f9');
	mensagem = mensagem.replace('û', '\u00fb');
	mensagem = mensagem.replace('ü', '\u00fc');
	mensagem = mensagem.replace('Ú', '\u00da');
	mensagem = mensagem.replace('Ù', '\u00d9');
	mensagem = mensagem.replace('Û', '\u00db');
	mensagem = mensagem.replace('ç', '\u00e7');
	mensagem = mensagem.replace('Ç', '\u00c7');
	mensagem = mensagem.replace('ñ', '\u00f1');
	mensagem = mensagem.replace('Ñ', '\u00d1');
	mensagem = mensagem.replace('&', '\u0026');
	mensagem = mensagem.replace('\'', '\u0027');

	return mensagem;
}