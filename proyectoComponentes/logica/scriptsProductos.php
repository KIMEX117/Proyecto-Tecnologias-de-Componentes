<!-- FORMATO PARA TABLA DE PRODUCTOS -->
<script>
	$(document).ready(function () {
		   $('#tablaProductos').DataTable({
		    "language": {
		    	"search"	: "Buscar",
		    	"lengthMenu": "Mostrar _MENU_ registros por página",
		    	"info"		: "Visualizando página _PAGE_ de _PAGES_"
		    }
		});
	});
</script>

<!-- TOASTR PARA MOSTRAR MENSAJE DE "PRODUCTO REGISTRADO EXITOSAMENTE" -->
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

<!-- MOSTRAR NUEVAMENTE MODAL DE "REGISTRO DE PRODUCTO NUEVO" EN CASO DE QUE ALGUN CAMPO QUEDARA VACÍO --> 	
<script>
	<?php 
	   	if (isset($_GET['errorRegistro'])) {
			if($_GET['errorRegistro'] == 1 || $_GET['errorRegistro'] == 2 || $_GET['errorRegistro'] == 3){	?>
				var modalRegistroProducto = new bootstrap.Modal(document.getElementById("registroProducto"));
				modalRegistroProducto.show();
	<?php	} 
		}
		if (isset($_GET['errorEditar'])) {
			if($_GET['errorEditar'] == 1){ ?>
				var modalEditarUsuario = new bootstrap.Modal(document.getElementById("editarProducto<?php echo $_GET['idProducto'] ?>"));
				modalEditarUsuario.show();
	<?php	} 
		} 
	?>
</script>