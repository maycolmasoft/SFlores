$(document).ready(function(){
	
	load_temp_solicitud(1);
});

var anio = (new Date).getFullYear();

$("#numero_factura_compra").inputmask('999-999-999999999',{placeholder: ""});

$("#numero_autorizacion_factura").inputmask('9999999999',{placeholder: ""});

$("#mod_precio_producto").inputmask({
	 alias: "decimal",	
	 digits: 2,
	 digitsOptional: true,
	 groupSeparator: ",",
	 autoGroup:true,
	 placeholder: "",
	 allowMinus: false,
	 integerDigits: '5',
	 defaultValue: "00.00",
	 prefix: "$"
	 });


$("#fecha_compra").inputmask({
	 alias: "date",
	 yearrange: { 'minyear': '1990','maxyear': anio },	 
	 placeholder: "dd/mm/yyyy"
	 });



$('#agregar_nuevo').on('show.bs.modal', function (event) {
load_productos(1);
  var modal = $(this)
  modal.find('.modal-title').text('Listado Productos')

});

$('#mod_agregar_producto').on('show.bs.modal', function (event) {
	carga_grupos();
	carga_unidad_medida()
	carga_bodegas()
  var modal = $(this);

});

$("#id_proveedor").focus(function() {
	console.log('fg')
	  $("#mensaje_proveedor").fadeOut("slow")
});

/**
 * para autocomplte de proveedores
 * @param pagina
 * @returns json
 */
$( "#proveedor" ).autocomplete({

	source: "index.php?controller=MovimientosInv&action=busca_proveedor",
	minLength: 4,
    select: function (event, ui) {
       // Set selection          
       $('#id_proveedor').val(ui.item.id);
       $('#proveedor').val(ui.item.value); // save selected id to input
       $('#nombre_proveedor').val(ui.item.nombre);
       $('#datos_proveedor').show();
       //console.log(ui.item.nombre);
       return false;
    },focus: function(event, ui) { 
        var text = ui.item.value; 
        $('#proveedor').val();            
        return false; 
    } 
}).focusout(function() {
	$.ajax({
		url:'index.php?controller=MovimientosInv&action=busca_proveedor',
		type:'POST',
		dataType:'json',
		data:{term:$('#proveedor').val()}
	}).done(function(respuesta){
		//console.log(respuesta[0].id);
		if(respuesta[0].id>0){				
			$('#id_proveedor').val(respuesta[0].id);
           $('#proveedor').val(respuesta[0].value); // save selected id to input
           $('#nombre_proveedor').val(respuesta[0].nombre);
           $('#datos_proveedor').show();
		}else{$('#datos_proveedor').hide(); $('#id_proveedor').val('0');  $('#proveedor').val('').focus();}
	});
});


function load_productos(pagina){

	var search=$("#search_productos").val();
   
    $("#load_productos_registrados").fadeIn('slow');
    
    $.ajax({
            beforeSend: function(objeto){
              $("#load_productos_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=MovimientosInv&action=consulta_productos&search='+search,
            type: 'POST',
            data: {action:'ajax', page:pagina},
            success: function(x){
              $("#productos_registrados").html(x);
              $("#load_productos_registrados").html("");
              $("#tabla_productos").tablesorter(); 
              
            },
           error: function(jqXHR,estado,error){
             $("#users_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
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
	var precio = document.getElementById('pecio_producto_'+id).value;
	if (isNaN(precio))
	{
		document.getElementById('pecio_producto_'+id).focus();
		swal('Esto no es un numero');
	return false;
	}
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=MovimientosInv&action=insertar_temporal_compras',
        data: "id_productos="+id+"&cantidad="+cantidad+"&precio_u="+precio,
    	 beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	  },
        success: function(datos){
    		$("#resultados").html(datos);
    		pone_cantidad();
    		carga_resultados_temp();
    	}
	});
}

function eliminar_producto (id)
{
	
	$.ajax({
        type: "POST",
    url: 'index.php?controller=MovimientosInv&action=eliminar_producto',
    data: "id_temp_compras="+id,
	 beforeSend: function(objeto){
		$("#resultados").html("Mensaje: Cargando...");

		pone_cantidad();

		carga_resultados_temp();
		
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
             url: 'index.php?controller=MovimientosInv&action=trae_temporal',
     type: 'POST',
     data: con_datos,
     success: function(x){
       $("#resultados").html(x);
       pone_cantidad();
       $("#tabla_temporal").tablesorter(); 
       carga_resultados_temp();
       
     },
    error: function(jqXHR,estado,error){
      $("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
        }
      });


}
	   
function carga_resultados_temp(pagina){
	  
   $.ajax({
             beforeSend: function(objeto){
               
             },
             url: 'index.php?controller=MovimientosInv&action=resultados_temp',
             type: 'POST',
             data: {},
             success: function(x){
            	 $("#resultados_totales").html();
            	 $("#resultados_totales").html(x);
 				//$("#resultados_totales").append(x);
                 
             },
            error: function(jqXHR,estado,error){
              $("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
            }
          });


}

function carga_grupos(){
	  
    $.ajax({
        beforeSend: function(objeto){
          
        },
        url: 'index.php?controller=Grupos&action=carga_grupos',
        type: 'POST',
        data: {},
        dataType:'json',
        success: function(respuesta){
        	$("#mod_id_grupo").empty()
        	$("#mod_id_grupo").append("<option value= \"0\" >--Seleccione--</option>");
        	$.each(respuesta, function(index, value) {
 		 			$("#mod_id_grupo").append("<option value= " +value.id_grupos +" >" + value.nombre_grupos  + "</option>");	
            		 });            
        },
        error: function(jqXHR,estado,error){
         //$("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
        }
    });
    

}

function carga_bodegas(){
	  
    $.ajax({
        beforeSend: function(objeto){
          
        },
        url: 'index.php?controller=Bodegas&action=carga_grupos',
        type: 'POST',
        data: {},
        dataType:'json',
        success: function(respuesta){
        	$("#mod_id_bodegas").empty()
        	$("#mod_id_bodegas").append("<option value= \"0\" >--Seleccione--</option>");
        	$.each(respuesta, function(index, value) {
 		 			$("#mod_id_bodegas").append("<option value= " +value.id_bodegas +" >" + value.nombre_bodegas  + "</option>");	
            		 });            
        },
        error: function(jqXHR,estado,error){
         //$("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
        }
    });
    

}

function carga_unidad_medida(){

	  $.ajax({
	        beforeSend: function(objeto){
	          
	        },
	        url: 'index.php?controller=Grupos&action=carga_unidadmedida',
	        type: 'POST',
	        data: {},
	        dataType:'json',
	        success: function(respuesta){
	        	$("#mod_unidad_medida").empty()
	        	$("#mod_unidad_medida").append("<option value= \"0\" >--Seleccione--</option>");
	        	$.each(respuesta, function(index, value) {
			 			$("#mod_unidad_medida").append("<option value= " +value.id_unidad_medida +" >" + value.nombre_unidad_medida  + "</option>");	
	        		 });  
	            
	        },
	       error: function(jqXHR,estado,error){
	         //$("#resultados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
	       }
	  });


}

function pone_cantidad(){
	//console.log('ingreso');
	//console.log($('#total_query_compras').length);
	if ($('#total_query_compras').length) {
		$('#cantidad_compra').val($('#total_query_compras').val());
	}
}


/**
 * FORMULARIO PARA AGREGAR LA COMPRA
 * @param event
 * @returns
 */
 $( "#frm_guardacompra" ).submit(function( event ) {
	 
	 var tiempo = tiempo || 1000;
	 
	 if(document.getElementById('cantidad_compra').value =='' || document.getElementById('cantidad_compra').value==0)
	 {
    	swal({
  		  title: "Compras",
  		  text: "No ha ingresado productos a la compra",
  		  icon: "warning",
  		  button: "Aceptar",
  		});
		
	 }

	 if(document.getElementById('id_proveedor').value ==''){
			$('#mensaje_proveedor').text('Ingrese Proveedor');
			$("#mensaje_proveedor").fadeIn("slow"); //Muestra mensaje de error
			$("html, body").animate({ scrollTop: $(mensaje_proveedor).offset().top-150 }, tiempo);
	        return false;
		}

	 if(document.getElementById('fecha_compra').value ==''){
			$('#mensaje_fecha_compra').text('Ingrese fecha');
			$("#mensaje_fecha_compra").fadeIn("slow"); //Muestra mensaje de error
			$("html, body").animate({ scrollTop: $(mensaje_fecha_compra).offset().top-150 }, tiempo);
	        return false;
		}
	 
	 if(document.getElementById('numero_factura_compra').value ==''){
			$('#mensaje_numero_factura').text('Ingrese Numero Factura');
			$("#mensaje_numero_factura").fadeIn("slow"); //Muestra mensaje de error
			$("html, body").animate({ scrollTop: $(mensaje_numero_factura).offset().top-150 }, tiempo);
	        return false;
		}
		
	if(document.getElementById('numero_autorizacion_factura').value ==''){
		$('#mensaje_autorizacion_factura').text('Ingrese Numero Autorizacion');
		$("#mensaje_autorizacion_factura").fadeIn("slow"); //Muestra mensaje de error
		$("html, body").animate({ scrollTop: $(mensaje_autorizacion_factura).offset().top-150 }, tiempo);
        return false;
	}
		
	
    	 var parametros = $(this).serialize();
    	 
    	 $.ajax({
    		 beforeSend:function(){},
    		 url:'index.php?controller=MovimientosInv&action=insertacompra',
    		 type:'POST',
    		 data:'action=ajax&'+parametros,
    		 dataType: 'json',
    		 success: function(respuesta){
        		console.log(respuesta);
    			 if(respuesta.success==1){
    				 $("#frm_guardacompra")[0].reset();
 	            		swal({
    	            		  title: "Compra",
    	            		  text: respuesta.mensaje,
    	            		  icon: "success",
    	            		  button: "Aceptar",
    	            		});
    					
    	                }else{
    	                	$("#frm_guardacompra")[0].reset();
    	                	swal({
    	              		  title: "Compra",
    	              		  text: respuesta.mensaje,
    	              		  icon: "warning",
    	              		  button: "Aceptar",
    	              		});
    	             }
    			 load_temp_solicitud(1);
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
    	 })
	
	 event.preventDefault(); 
 });

/*FORMULARIO DE REGISTRAR PRODUCTO*/
 $( "#frm_guardar_producto" ).submit(function( event ) {
	//console.log('ingresa->1\n');
	var parametros = $(this).serialize();	
	$.ajax({
        beforeSend: function(objeto){
          
        },
        url: 'index.php?controller=Productos&action=inserta_producto',
        type: 'POST',
        data: parametros,
        dataType:'json',
        success: function(respuesta){

            if(respuesta.success==1){
            	$("#frm_guardar_producto")[0].reset();
            	swal({
            		  title: "Productos",
            		  text: respuesta.mensaje,
            		  icon: "success",
            		  button: "Aceptar",
            		});
				
                }else{
                	$("#frm_guardar_producto")[0].reset();
                	swal({
              		  title: "Productos",
              		  text: respuesta.mensaje,
              		  icon: "warning",
              		  button: "Aceptar",
              		});
                    }
        	     
        },
        error: function(xhr,estado,error){
        	console.log(xhr.responseText);
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
	  
	});


           // Campos Vacíos
		    // cada vez que se cambia el valor del combo
		    /*$(document).ready(function(){
		    
		    $("#Guardarrr").click(function() 
			{
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var id_proveedor = $("#id_proveedor").val();
		    	var fecha_compra = $("#fecha_compra").val();
		    	var numero_factura_compra = $("#numero_factura_compra").val();
		    	var numero_autorizacion_factura = $("#numero_autorizacion_factura").val();
		    	var estado_compra = $("#estado_compra").val();
		    	
		    	
		    	
		    	
		    	if (id_proveedor == "")
		    	{
			    	
		    		$("#mensaje_proveedor").text("Introduzca Un Proveedor");
		    		$("#mensaje_proveedor").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_proveedor").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (fecha_compra == "")
		    	{
			    	
		    		$("#mensaje_numero_compra").text("Introduzca Una fecha");
		    		$("#mensaje_numero_compra").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_numero_compra").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (numero_factura_compra == "")
		    	{
			    	
		    		$("#mensaje_numero_factura").text("Introduzca Un número de factura");
		    		$("#mensaje_numero_factura").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_numero_factura").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (numero_autorizacion_factura == "")
		    	{
			    	
		    		$("#mensaje_autorizacion_factura").text("Introduzca Un N° de Autorización");
		    		$("#mensaje_autorizacion_factura").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_autorizacion_factura").fadeOut("slow"); //Muestra mensaje de error
		            
				}   

		    	if (estado_compra == "")
		    	{
			    	
		    		$("#mensaje_estado_compra").text("Introduzca Un Estado");
		    		$("#mensaje_estado_compra").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_estado_compra").fadeOut("slow"); //Muestra mensaje de error
		            
				}   
		     

		    	
			}); 

		}); */
 
  $( "#proveedor" ).focus(function() {
  $("#mensaje_proveedor").fadeOut("slow");
});
   
$( "#fecha_compra" ).focus(function() {
  $("#mensaje_fecha_compra").fadeOut("slow");
});

$( "#numero_factura_compra" ).focus(function() {
  $("#mensaje_numero_factura").fadeOut("slow");
    });

$( "#numero_autorizacion_factura" ).focus(function() {
  $("#mensaje_autorizacion_factura").fadeOut("slow");
    });

$( "#estado_compra" ).focus(function() {
  $("#mensaje_estado_compra").fadeOut("slow");
});
