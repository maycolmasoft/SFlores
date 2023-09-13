
$(document).ready( function (){
	   //pone_espera();
	load_productos_solicitud(1);
	load_temp_solicitud(1);
   
});

   function pone_espera(){

	   $.blockUI({ 
			message: '<h4><img src="view/images/load.gif" /> Espere por favor, estamos procesando su requerimiento...</h4>',
		css: { 
	        border: 'none', 
	        padding: '15px', 
	        backgroundColor: '#000', 
	        '-webkit-border-radius': '10px', 
	        '-moz-border-radius': '10px', 
	        opacity: .5, 
	        color: '#fff',
		           
	        		}
    });
	
    setTimeout($.unblockUI, 3000); 
    
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

