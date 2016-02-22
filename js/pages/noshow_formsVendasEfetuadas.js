/*
 *  Document   : uiTables.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Tables page
 */

$(document).ready(function(){
	$("#div_erro").hide();

	$('a i.gi-disk_save').click(function(e)	{
		var certificado	= $(this).parent().attr('certificado');
		var cpfcliente	= $(this).parent().attr('cpf');
		var url = "ajax/geracertificadoajax.php?val-cpfcliente=" + cpfcliente +'&val-certificado='+ certificado;
		window.open(url, "certificado", "toolbar=0");
		e.preventDefault();
	});
	
	$('a i.gi-send').click(function(e)	{
		var form_data = {
			'val-certificado': $(this).parent().attr('certificado'),
			'val-cpfcliente': $(this).parent().attr('cpf'),
			is_ajax: 1
		};
		var EMAIL = $(this).parent().attr('email');
		var CERTIFICADO = $(this).parent().attr('certificado');
		var CPF = $(this).parent().attr('cpf');
		
		$.ajax({
			url: "ajax/getcomunicavenda.php",
			type: 'POST',
			data: form_data,
			datatype:'json',
			success: function(data, status)
			{
				if(data.CodRetorno == "0")
				{
					$('#modal-fade').modal({ keyboard: false });
					$('#val-mail').val(EMAIL);
					$('#val-certificado').val(CERTIFICADO);
					$('#val-cpf').val(CPF);
					$("#texto_certificado").text(CERTIFICADO);
					$("#texto_cpf").text(CPF);
				} else {
					$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>'+ data.Mensagem +'</p>', {
						type: 'danger',
						delay: 3000,
						allow_dismiss: true,
						offset: {from: 'top', amount: 20}
					});
				}
			},
			error: function(xhr, desc, err){
				$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>'+ acentuarAlerts("Erro inesperado...") +'</p>', {
					type: 'danger',
					delay: 3000,
					allow_dismiss: true,
					offset: {from: 'top', amount: 20}
				});
			}
		});
	});
	
	$("#btn-comunica-cliente").click(function(e){
		e.preventDefault();
		var comunicou = false;
		if($("#form-comunica input[type='radio']:checked").val()=="option1")
		{
			comunicou = true;
			var form_data = {
				'val-certificado': $('#val-certificado').val(),
				'val-cpfcliente': $('#val-cpf').val(),
				'val-email': $('#val-mail').val(),
				is_ajax: 1
			};
			//$("#form-comunica").submit();
			
			$.ajax({
				url: $('#form-comunica').attr('action'),
				type: 'POST',
				data: form_data,
				datatype:'json',
				success: function(data, status)
				{
					if(data.CodRetorno == "0")
					{
						$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>'+ acentuarAlerts("Email de comunicação enviado com sucesso...")+'</p>', {
							type: 'success',
							delay: 3000,
							allow_dismiss: true,
							offset: {from: 'top', amount: 20}
						});
					} else {
						$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>'+ data.Mensagem +'</p>', {
							type: 'danger',
							delay: 3000,
							allow_dismiss: true,
							offset: {from: 'top', amount: 20}
						});
					}
					$('#modal-fade').modal('hide');
				},
				error: function(xhr, desc, err){
					$.bootstrapGrowl('<h4><strong>'+ acentuarAlerts("Notificação") +'</strong></h4> <p>'+ acentuarAlerts("Erro não especificado...") +'</p>', {
						type: 'danger',
						delay: 3000,
						allow_dismiss: true,
						offset: {from: 'top', amount: 20}
					});
				}
			});
		}
		
		if($("#form-comunica input[type='radio']:checked").val()=="option2")
		{
			comunicou = true;
			var url = "ajax/geradocumentoajax.php?val-cpfcliente=" + $('#val-cpf').val() +'&val-certificado='+ $('#val-certificado').val();
			window.open(url, "documento", "toolbar=0");
			$('#modal-fade').modal('hide');
			e.preventDefault();
		}
		if(!comunicou)
		{
			window.alert(acentuarAlerts(acentuarAlerts("Favor escolher uma opção de comunicação de boas vindas.")));
		}
	});
});

var UiTables = function() {

    return {
        init: function() {
            /* Initialize Bootstrap Datatables Integration */
            App.datatables();

            /* Initialize Datatables */
            $('#example-datatable').dataTable({
                "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 4 ] } ],
                "iDisplayLength": 30,
                "aLengthMenu": [[10, 30, 50], [10, 30, 50]]
            });

            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Pesquisar...');

            /* Select/Deselect all checkboxes in tables */
            $('thead input:checkbox').click(function() {
                var checkedStatus   = $(this).prop('checked');
                var table           = $(this).closest('table');

                $('tbody input:checkbox', table).each(function() {
                    $(this).prop('checked', checkedStatus);
                });
            });

            /* Table Styles Switcher */
            var genTable        = $('#general-table');
            var styleBorders    = $('#style-borders');

            $('#style-default').on('click', function(){
                styleBorders.find('.btn').removeClass('active');
                $(this).addClass('active');

                genTable.removeClass('table-bordered').removeClass('table-borderless');
            });

            $('#style-bordered').on('click', function(){
                styleBorders.find('.btn').removeClass('active');
                $(this).addClass('active');

                genTable.removeClass('table-borderless').addClass('table-bordered');
            });

            $('#style-borderless').on('click', function(){
                styleBorders.find('.btn').removeClass('active');
                $(this).addClass('active');

                genTable.removeClass('table-bordered').addClass('table-borderless');
            });

            $('#style-striped').on('click', function() {
                $(this).toggleClass('active');

                if ($(this).hasClass('active')) {
                    genTable.addClass('table-striped');
                } else {
                    genTable.removeClass('table-striped');
                }
            });

            $('#style-condensed').on('click', function() {
                $(this).toggleClass('active');

                if ($(this).hasClass('active')) {
                    genTable.addClass('table-condensed');
                } else {
                    genTable.removeClass('table-condensed');
                }
            });

            $('#style-hover').on('click', function() {
                $(this).toggleClass('active');

                if ($(this).hasClass('active')) {
                    genTable.addClass('table-hover');
                } else {
                    genTable.removeClass('table-hover');
                }
            });
        }
    };
}();

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