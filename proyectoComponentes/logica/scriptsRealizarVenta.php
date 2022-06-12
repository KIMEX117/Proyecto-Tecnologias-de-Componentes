<!-- MOSTRAR Y OCULTAR INPUT DE PAGO DEL CLIENTE AL ELEGIR ENTRE EFECTIVO O TARJETA -->
<script>
	function showDiv(divId, element){
    	document.getElementById(divId).style.display = element.value == "Efectivo" ? 'block' : 'none';
	}
</script>

<!-- TOASTR DE AÑADIDO EXITOSAMENTE, NO HAY STOCK, CARRITO VACIADO Y VENTA FINALIZADA" -->
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
		toastr.success("El producto se añadió exitosamente al carrito." , "AVISO");
	<?php endif ?>
	<?php if(isset($_GET['error'])): ?>
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
		toastr.warning("No es posible añadir este producto, no hay stock suficiente." , "AVISO");
	<?php endif ?>
	<?php if(isset($_GET['action'])): ?>
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
		toastr.info("Se ha vaciado el carrito de compras." , "AVISO");
	<?php endif ?>
	<?php if(isset($_GET['successVenta'])): ?>
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
		toastr.success("La venta se realizó correctamente." , "AVISO");
	<?php endif ?>
</script>

<!-- MOSTRAR NUEVAMENTE MODAL DE CARRITO --> 	
<script>
	<?php	
		if (isset($_GET['removeProduct'])) {
			if($_GET['removeProduct'] == true){ ?>
				var modalCarrito = new bootstrap.Modal(document.getElementById("mostrarCarrito"));
				modalCarrito.show();
	<?php	} 
		} 
	?>
	<?php	
		if (isset($_GET['errorVenta'])) {
			if($_GET['errorVenta'] == 1){ ?>
				var modalCarrito2 = new bootstrap.Modal(document.getElementById("mostrarCarrito"));
				modalCarrito2.show();
	<?php	} 
		} 
	?>
</script>