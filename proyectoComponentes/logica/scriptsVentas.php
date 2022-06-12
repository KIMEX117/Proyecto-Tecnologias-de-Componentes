<!-- FORMATO PARA TABLAS-->
<script>
	$(document).ready(function () {
	    $('#tablaVentas').DataTable({
	    	"language": {
	    		"search"	: "Buscar",
	    		"lengthMenu": "Mostrar _MENU_ registros por página",
	    		"info"		: "Visualizando página _PAGE_ de _PAGES_"
	    	}
	    });
	    $('#tablaDetalleVentas').DataTable({
	    	"language": {
	    		"search"	: "Buscar",
	    		"lengthMenu": "Mostrar _MENU_ registros por página",
	    		"info"		: "Visualizando página _PAGE_ de _PAGES_"
	    	}
	    });
	    $('#tablaPagos').DataTable({
	    	"language": {
	    		"search"	: "Buscar",
	    		"lengthMenu": "Mostrar _MENU_ registros por página",
	    		"info"		: "Visualizando página _PAGE_ de _PAGES_"
	    	}
	    });
	});
</script>

<!-- TOASTR PARA MOSTRAR MENSAJE DE "ACCIÓN REALIZADA EXITOSAMENTE" -->
<script>
	<?php if(isset($_GET['success'])): ?>
			toastr.options = {
			  	"closeButton": false,
				"debug": false,
				"newestOnTop": false,
				"progressBar": true,
				"positionClass": "toast-top-right",
				"preventDuplicates": true,
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "5000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};
		toastr.success("Acción realizada exitosamente." , "AVISO");
	<?php endif ?>
</script>