$(document).ready(function(){
	
	loadDetalleFactura();
		
});

$('#agregar_nuevo').on('show.bs.modal', function (event) {
	load_productos(1);
	  var modal = $(this)
	  modal.find('.modal-title').text('Listado Productos')

	});

$('#frm_registraFactura').on('submit',function(event){
	
	event.preventDefault();
})

function loadDetalleFactura(pagina=1){
	
	$.ajax({
		type: "POST",
        url: 'index.php?controller=MovimientosInv&action=detalleFactura',
        data: {page:pagina,comprobante_id:$('#id_comprobante').val()},
    	beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	  },
	}).done(function(data){
		$('#detalle_factura').html(data);
	}).fail(function(){
		
	})
	
}

function add_producto(id)
{
	var cantidad=document.getElementById('cantidad_'+id).value;
	//Inicia validacion
	if (isNaN(cantidad)){
		alert('Esto no es un numero');
		document.getElementById('cantidad_'+id).focus();
		return false;
	}
	
	var precio = document.getElementById('pecio_producto_'+id).value;
	
	if (isNaN(precio)){
		document.getElementById('pecio_producto_'+id).focus();
		swal('Esto no es un numero');
	return false;
	}
	
	var comprobante = document.getElementById('id_comprobante').value;
	
	if(comprobante==0){
		return false;
	}
	
	$.ajax({
        type: "POST",
        url: 'index.php?controller=MovimientosInv&action=insertaDetalleFactura',
        data: "id_productos="+id+"&cantidad="+cantidad+"&precio_u="+precio+"&comprobante_id="+$('#id_comprobante').val(),
    	beforeSend: function(objeto){
    		/*$("#resultados").html("Mensaje: Cargando...");*/
    	},
    	dataType:'json'
    	}).done(function(data){
    		
    		loadDetalleFactura();
    		
    		
    	}).fail(function(xhr,status,error){
    		var err = xhr.responseText;
    		console.log(err);
    		loadDetalleFactura();
    	});
    	  
}

var anio = (new Date).getFullYear();

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

$('#mod_agregar_producto').on('show.bs.modal', function (event) {
	carga_grupos();
	carga_unidad_medida()
	carga_bodegas()
  var modal = $(this);

});



function load_productos(pagina){

	var search=$("#search_productos").val();
   
    $("#load_productos_registrados").fadeIn('slow');
    
    $.ajax({
            beforeSend: function(objeto){
              $("#load_productos_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
            },
            url: 'index.php?controller=MovimientosInv&action=buscarProductosRegistroMaterial&search='+search,
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

function eliminar_producto (id)
{
	
	$.ajax({
        type: "POST",
	    url: 'index.php?controller=MovimientosInv&action=eliminaTempFactura',
	    data: "id_temp_factura="+id,
		dataType:'json'
	}).done(function(data){
		
		loadDetalleFactura();
		
	}).fail(function(xhr,status,error){
		var err = xhr.responseText
		
		console.log(err)
		
		loadDetalleFactura();
		
	})
    
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

$( "#numero_autorizacion_factura" ).focus(function() {
  $("#mensaje_autorizacion_factura").fadeOut("slow");
    });

$( "#estado_compra" ).focus(function() {
  $("#mensaje_estado_compra").fadeOut("slow");
});


/********* PARA EL INGRESO DE FACTURAS COMO MOVIMIENTO *********************/

/**
 * FUNCION QUE ACTIVA AL HACER SUBMIT
 * @param event
 * @returns
 */
$('#frm_registraFactura').on('submit',function(event){
	
	var valComprobante = document.getElementById("valor_factura").value;
	
	var cantidadDetalle = ($('#total_query_factura').length>0)?$('#total_query_factura').val():0;
	
	var valorDetalle = ($('#total_suma_detalle').length>0)?$('#total_suma_detalle').val():0;
	
	if(cantidadDetalle==0){   	
		imprimeMensaje('No ha ingresado productos a la compra');
		return false;		
	 }
	
	if( parseFloat(valComprobante) != parseFloat(valorDetalle) ){		
		imprimeMensaje('VALOR FACTURA NO COINCIDE');		
		return false;
	}
	
	var parametros={
			peticion:'ajax',
			comprobante_id : $('#id_comprobante').val(),
			fecha_compra : $('#fecha_factura').val(),
			cantidad_factura :cantidadDetalle
	}
	
	$.ajax({
		url:'index.php?controller=MovimientosInv&action=RegistraFactura',
		type:'POST',
		dataType:'json',
		data:parametros,
		beforeSend:function(){}
		}).done(function(data){
			
			if(data.mensaje > 0){
				
				swal({
					text:"Factura se encuentra registrada",
					icon:"success",
					title:"INFO",
					buttons:{Aceptar:"Aceptar"}
					}).then((value) => {
					  switch (value) {					 
					    case "Aceptar":
					    	window.open('index.php?controller=MovimientosInv&action=IngresoMateriales','_self')
					      break;
					    default:
					    	return false
					  }
					});			
				
			}
			
		}).fail(function(xhr,status,error){
			var err = xhr.responseText
			console.log(err);
			loadDetalleFactura();
		})
	
	
	event.preventDefault()
})

/*********************************UTILITARIOS************************************/
function imprimeMensaje(mtext=''){swal({icon: "info", title: "INFO", button: "Aceptar", text: mtext });}
function imprimeMensajeOk(mtext=''){swal({icon: "success", title: "INFO", button: "Aceptar", text: mtext });}

function pone_cantidad(){
	
	if ($('#total_query_compras').length) {
		$('#cantidad_compra').val($('#total_query_compras').val());
	}
}
