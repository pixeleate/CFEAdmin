$(document).ready(function() {
	$.getJSON( 'http://pixeleate.com/propuestacfe/api/v1/CFEQuejasDash', function(data) { 
        $.each( data.fallas, function(i, falla) {

            $('#accordion').append('<div class="panel panel-default"><div class="panel-heading" role="tab" id="heading'+i+'"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse'+i+'" aria-expanded="true" aria-controls="collapseOne"> Número de Folio: ' + falla.folio + '</a></h4></div><div id="collapse'+i+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'+i+'"><div class="panel-body">'+'<div>Estatus: '+falla.estatusQueja +'</div>'+'<div>fecha recibido: '+falla.fechaReporte +'</div>'+'<div>No. servicio: '+falla.no_servicio +'</div>'+'<div>fecha proceso: '+falla.fechaResuelta +'</div><hr>'+'<div class=desc>Descripción: '+falla.descripcion +'</div><a id=status data-folio='+falla.folio+' data-servicio='+falla.no_servicio +' data-estatus="'+falla.estatusFalla +'"  data-fecha="'+falla.fechaReporte +'" href=""><input id=change-status type="button" value="Cambiar estatus" /></a>'+'</div></div></div>')

          
        });
      });

	$('div').on('click','#status', function(event) {
		event.preventDefault();
		/* Act on the event */

		var folio = $(this).data('folio');
		var no_servicio = $(this).data('servicio');
		var fecha_reporte = $(this).data('fecha');
		var estatus = $(this).data('estatus');

		console.log(folio, no_servicio, fecha_reporte, estatus);

		$.ajax({
			url: 'statusfallas.php',
			type: 'POST',
			data: {f: folio, n: no_servicio, d: fecha_reporte, e: estatus }
		})
		.done(function(response) {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
		return false;
	});
});