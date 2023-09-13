$(document).ready(function(){
	carga_solicitud();
	carga_solicitud_entregada()
	carga_solicitud_rechazada()
	notificacionProductos()
	
});

function carga_solicitud(pagina){

    var search=$("#buscador_solicitud").val();
    var con_datos={
    		  action:'ajax',
    		  page:pagina,
    		  buscador:search
    		  };
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_productos").fadeIn('slow');
               $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=MovimientosInv&action=carga_solicitud',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados_solicitud").html(x);
               $("#load_solicitud").html("");
               $("#tabla_salidas").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}

function carga_solicitud_entregada(pagina){

    var search=$("#buscador_solicitud_entregada").val();
    var con_datos={
    		  action:'ajax',
    		  page:pagina,
    		  buscador:search
    		  };
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_productos").fadeIn('slow');
               $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=MovimientosInv&action=carga_solicitud_entregada',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados_solicitud_entregada").html(x);
               $("#load_solicitud_entregada").html("");
               $("#tabla_salidas_entregada").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}

function carga_solicitud_rechazada(pagina){

    var search=$("#buscador_solicitud_rechazada").val();
    var con_datos={
    		  action:'ajax',
    		  page:pagina,
    		  buscador:search
    		  };
   
   $.ajax({
             beforeSend: function(objeto){
               $("#load_productos").fadeIn('slow');
               $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
             },
             url: 'index.php?controller=MovimientosInv&action=carga_solicitud_rechazada',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados_solicitud_rechazada").html(x);
               $("#load_solicitud_rechazada").html("");
               $("#tabla_salidas_rechazada").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });
}



function load_productos_solicitud(pagina){

   var search=$("#buscador_productos").val();
    var con_datos={
			  action:'ajax',
			  page:pagina,
			  buscador:search
			  };
  
  $.ajax({
            beforeSend: function(objeto){
              $("#load_productos").fadeIn('slow');
              $("#load_productos").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=SolicitudCabeza&action=ajax_trae_productos',
            type: 'POST',
            data: con_datos,
            success: function(x){
              $("#productos_inventario").html(x);
              $("#load_productos").html("");
              $("#tabla_productos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#productos_inventario").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
           }
         });


   }


function agregar_producto (id)
{
	var cantidad=document.getElementById('cantidad_'+id).value;
	//Inicia validacion
	if (isNaN(cantidad))
	{
	alert('Esto no es un numero');
	document.getElementById('cantidad_'+id).focus();
	return false;
	}
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=SolicitudCabeza&action=insertar_producto',
        data: "id_productos="+id+"&cantidad="+cantidad,
    	 beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	  },
        success: function(datos){
    		$("#resultados").html(datos);
    	}
	});
}

function eliminar_producto (id)
{
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=SolicitudCabeza&action=eliminar_producto',
        data: "id_solicitud="+id,
    	 beforeSend: function(objeto){
    		$("#resultados").html("Mensaje: Cargando...");
    	  },
        success: function(datos){
    		$("#resultados").html(datos);
    	}
	});
}

function load_temp_solicitud(pagina){
  
     var con_datos={
			  page:pagina
			  };
   
   $.ajax({
             beforeSend: function(objeto){
               
             },
             url: 'index.php?controller=SolicitudCabeza&action=trae_temporal',
             type: 'POST',
             data: con_datos,
             success: function(x){
               $("#resultados").html(x);
               $("#tabla_temporal").tablesorter(); 
               
             },
            error: function(jqXHR,estado,error){
              $("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });


   }

function notificacionProductos(){
	
	let $ObjNotificacion = $(".notifications-menu");
	let $cantidadNotificacion = $ObjNotificacion.find("a>span");
	let $ulDetalle = $ObjNotificacion.find("ul.dropdown-menu");	
	
	$.ajax({
		url:"index.php?controller=Productos&action=notificacionProductos",
		type:"POST",
		dataType:"json",
		data:null
	}).done(function(x){
		if( x.respuesta == 1 ){    			
			$ulDetalle.append(x.htmlNotificacion);
			$cantidadNotificacion.text(x.cantidadNotificaciones);
		}
		
		//
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		console.log('revisar boot/otros/movimientos_salida .. notificacionProductos')
	})
	
}