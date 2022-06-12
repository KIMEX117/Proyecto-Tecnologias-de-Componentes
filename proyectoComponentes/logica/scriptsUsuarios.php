<!-- FORMATO PARA TABLA DE USUARIOS -->
<script>
	$(document).ready(function () {
	    $('#tablaUsuarios').DataTable({
	    	"language": {
	    		"search"	: "Buscar",
	    		"lengthMenu": "Mostrar _MENU_ registros por página",
	    		"info"		: "Visualizando página _PAGE_ de _PAGES_"
	    	}
	    });
	});
</script>

<!-- TOASTR PARA MOSTRAR MENSAJE DE "USUARIO REGISTRADO EXITOSAMENTE" -->
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

<!-- MOSTRAR NUEVAMENTE MODALS EN CASO DE ALGUN ERROR --> 	
<script>
	<?php 
    	if (isset($_GET['errorRegistro'])) {
			if($_GET['errorRegistro'] == 1 || $_GET['errorRegistro'] == 2 || $_GET['errorRegistro'] == 3 || $_GET['errorRegistro'] == 4){	?>
				var modalRegistroUsuario = new bootstrap.Modal(document.getElementById("registroUsuario"));
				modalRegistroUsuario.show();
	<?php	} 
		}
		if (isset($_GET['errorEditar'])) {
			if($_GET['errorEditar'] == 1 || $_GET['errorEditar'] == 2){ ?>
				var modalEditarUsuario = new bootstrap.Modal(document.getElementById("editarUsuario<?php echo $_GET['idUsuario'] ?>"));
				modalEditarUsuario.show();
	<?php	} 
		} 
	?>
</script>