$(document).ready(function(){
	
});

function rechazar_producto(id){
	
	var cantidad=document.getElementById('cantidad_producto_'+id).value;
	
	//return false
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=MovimientosInv&action=rechazaproducto',
        data: "id_temp_salida="+id,
        dataType:'json',
    	 beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	  },
        success: function(datos){
        	swal(datos.mensaje);
        	//$('#cantidad_producto_'+id).parent('tr').hide()
        	$('#cantidad_producto_'+id).parents('tr:first').css({"color": "red", "border": "2px solid red"})
        	console.log($('#cantidad_producto_'+id).parent().parent('tr'))
        	//console.log(id);
        	console.log($('#cantidad_producto_'+id).parents('tr:first').children('td:first').text())
    	},
    	error: function(xhr,estado,error){    			 
			 //console.log(xhr.responseText);
			 var err=xhr.responseText
			 
			 swal({
        		  title: "Error",
        		  text: "Error conectar con el Servidor \n "+err,
        		  icon: "error",
        		  button: "Aceptar",
        		});
	        }
	});
}

function aprobar_producto(id){

	var cantidad=document.getElementById('cantidad_producto_'+id).value;
	//Inicia validacion
	if (isNaN(cantidad))
	{
		swal('no es cantidad')
    	document.getElementById('cantidad_producto_'+id).focus();
    	return false;
	}
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=MovimientosInv&action=apruebaproducto',
        data: "fila=1&id_temp_salida="+id+"&cantidad="+cantidad,
        dataType:'json',
    	 beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	  },
        success: function(datos){
    		swal(datos.mensaje);
    	}
	});
}

$('.enviarsalida').on('click',function(){
	$('#btnForm').val($(this).val())
	//console.log($(this).val())
})

$('#frm_agrega_salida').on('submit',function(event){
	
	var parametros = $(this).serialize();	
	
	//console.log('accion=ajax&'+parametros)
	
	var btnform = document.getElementById('btnForm').value
	
	$.ajax({
        beforeSend: function(objeto){
          
        },
        url: 'index.php?controller=MovimientosInv&action=inserta_salida',
        type: 'POST',
        data: 'accion=ajax&'+parametros,
        dataType:'json',
        success: function(respuesta){
        	
        	//console.log(respuesta)
        	
        	//$("#frm_guardar_producto")[0].reset();
        	var valrespuesta = respuesta.mensaje.includes("ok")||false
        	
            if(valrespuesta){
            	
            	swal({
            		  title: "Salidas",
            		  text: respuesta.mensaje,
            		  icon: "success",
            		  buttons: {Aceptar: {text: "Aceptar",value: "ok" }}
            		})
            		.then((value) => {
        			  switch (value) {            			  
        			    case "ok":
        			    	window.location="index.php?controller=MovimientosInv&action=indexsalida"
        			      break;            			 
        			  }
            		});
				
                }else{
                	
                	swal({
              		  title: "Salidas",
              		  text: respuesta.mensaje,
              		  icon: "warning",
              		  button: "Aceptar",
              		  closeOnClickOutside: false,
              		});
                }
        	
        	
        		//setTimeout("redireccionarPagina()", 5000);
        
        	     
        },
        error: function(xhr,estado,error){
        	//console.log(xhr.responseText);
			 var err=xhr.responseText
			 
			 swal({
        		  title: "Error",
        		  text: "Error conectar con el Servidor \n "+err,
        		  icon: "error",
        		  button: "Aceptar",
        		});
        }
    });
	
	event.preventDefault();	
})

function redireccionarPagina() {
	window.location="index.php?controller=MovimientosInv&action=indexsalida"
}