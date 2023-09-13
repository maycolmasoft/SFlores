
$(document).ready( function (){

	/*pone_espera();*/
	load_usuarios(1);
	load_usuarios_inactivos(1);
	var ct="Usuarios Activos";
	$('[data-mask]').inputmask();
	   
})
	
	   
$('.nav-tabs a').on('shown.bs.tab', function(e){
   var currentTab = $(e.target).text();
   ct=currentTab;
   //console.log(currentTab);
});


$('#link_agregar_rol').click(function() { 
	copiarOpcion($('#id_rol option:selected').clone(), "#lista_roles");
});


$("#btExportar").click(function(){
	
var fecha = "<?php echo $DateString?>";

var activeTab = ct;

//console.log(activeTab);

if (activeTab == "Usuarios Activos")
{
var arreglo_usuarios= new Array();
$("table#tabla_usuarios tr").each(
function(){
	var arrayOfThisRow = [];
    var tableData = $(this).find('td');
    if (tableData.length > 0) {
        tableData.each(function() { arrayOfThisRow.push($(this).text()); });
        arreglo_usuarios.push(arrayOfThisRow);
    }
			}
		);

   //console.log(arreglo_usuarios);
   var docdescarga ="data:application/vnd.ms-excel; charset=utf-8,"
   docdescarga +=" \tCedula\tNombre\tTelefono\tCelular\tCorreo\tRol\tEstado\n";
   var len = arreglo_usuarios.length;
   for (var i=0; i<len; i++)
   {
	   for (var j=1; j<9; j++)
	   {
		   docdescarga +=arreglo_usuarios[i][j];
		   if(j!=8) docdescarga += "\t";			    	   
	   }
   docdescarga += "\n";
	
	   }
   //console.log(docdescarga);

    if (len>0)
    {

   var encodeUri = encodeURI(docdescarga);
	var link = document.createElement("a");
link.setAttribute("href", encodeUri);
var nombre_de_arch = "ReporteUsuariosActivos"+fecha+".xls";
link.setAttribute("download", nombre_de_arch);
document.body.appendChild(link); // Required for FF

link.click();
}
else
{
    alert("No hay información para descargar");
	    }
}

else
{
	var arreglo_usuarios_inact= new Array();
	$("table#tabla_usuarios_inactivos tr").each(
function(){
	var arrayOfThisRow = [];
    var tableData = $(this).find('td');
    if (tableData.length > 0) {
        tableData.each(function() { arrayOfThisRow.push($(this).text()); });
        arreglo_usuarios_inact.push(arrayOfThisRow);
    }
			}
		);

  // console.log(arreglo_usuarios_inact);
   var docdescarga ="data:application/vnd.ms-excel; charset=utf-8,"
	   docdescarga +=" \tCedula\tNombre\tTelefono\tCelular\tCorreo\tRol\tEstado\n";
   var len = arreglo_usuarios_inact.length;
   for (var i=0; i<len; i++)
   {
	   for (var j=1; j<9; j++)
	   {
		   docdescarga +=arreglo_usuarios[i][j];
		   if(j!=8) docdescarga += "\t";			    	   
    	   }
	   docdescarga += "\n";
	
	   }
   if (len>0)
    {

   var encodeUri = encodeURI(docdescarga);
	//console.log(encodeUri);
	var link = document.createElement("a");
	link.setAttribute("href", encodeUri);
	var nombre_de_arch = "ReporteUsuariosInactivos"+fecha+".xls";
	link.setAttribute("download", nombre_de_arch);
	document.body.appendChild(link); // Required for FF

	link.click();
    }
    else
    {
	    alert("No hay información para descargar");
	    }
   

}
});

	    $('#link_agregar_roles').click(function() { 
	        $('#id_rol option').each(function() {
	            copiarOpcion($(this).clone(), "#lista_roles");
	        }); 
	    });

	    $('#link_eliminar_rol').click(function() { 
	        $('#lista_roles option:selected').remove(); 
	    });

	    $('#link_eliminar_roles').click(function() { 
	        $('#lista_roles option').each(function() {
	            $(this).remove(); 
	        }); 
	    });

	    $('#id_rol_principal').change(function() { 
	    	copiarOpcion($('#id_rol_principal option:selected').clone(), "#lista_roles");
	    });

	   

	    $(".caducaclave").blur(function(){
			var clave = $("#clave_usuarios").val();
			var _id_usuarios = $("#id_usuarios").val();

			if($('#cambiar_clave').is(':checked')){
    			$.ajax({
    	            beforeSend: function(objeto){
    	              $("#resultadosjq").html('...');
    	            },
    	            url: 'index.php?controller=Usuarios&action=ajax_caducaclave',
    	            type: 'POST',
    	            data: {clave_usuarios:clave,id_usuarios:_id_usuarios},
    	            success: function(x){
    	             if(x.trim()!=""){
    	            	 	$("#mensaje_clave_usuarios").text(x);
    			    		$("#mensaje_clave_usuarios").fadeIn("slow");
        	            	 $("#clave_usuarios").val("");
        	            	 $("#clave_usuarios_r").val("");
    	                 }
    	            },
    	           error: function(jqXHR,estado,error){
    	             $("#resultadosjq").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
    	           }
    	         });
			}
    	        
	   });

		$('#cambiar_clave').change(
			    function(){				    
			        if (this.checked) {

				           $('#clave_usuarios').removeAttr("readonly");
				           $('#clave_usuarios_r').removeAttr("readonly");
				           $('#clave_usuarios').val("");
				           $('#clave_usuarios_r').val("");
			        }else{
			        	$('#clave_usuarios').attr("readonly","readonly");
				        $('#clave_usuarios_r').attr("readonly","readonly");
				        $('#clave_usuarios').val($('#codigo_clave').val());
				        $('#clave_usuarios_r').val($('#codigo_clave').val());
				        }
			    });

		
});

    function copiarOpcion(opcion, destino) {
        var valor = $(opcion).val();
        if (($(destino + " option[value=" + valor + "] ").length == 0) && valor != 0 ) {
            $(opcion).appendTo(destino);
        }
    }

    function selecionarTodos(){
    	$("#lista_roles option").each(function(){
	      $(this).attr("selected", true);
		 });
     }
    

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

        	   
   function load_usuarios(pagina){

	   var search=$("#search").val();
       var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
     $("#load_registrados").fadeIn('slow');
     
     $.ajax({
               beforeSend: function(objeto){
                 $("#load_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
               },
               url: 'index.php?controller=Usuarios&action=consulta_usuarios_activos&search='+search,
               type: 'POST',
               data: con_datos,
               success: function(x){
                 $("#users_registrados").html(x);
                 $("#load_registrados").html("");
                 $("#tabla_usuarios").tablesorter(); 
                 
               },
              error: function(jqXHR,estado,error){
                $("#users_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
              }
            });


	   }

   function load_usuarios_inactivos(pagina){

	   var search=$("#search_inactivos").val();
       var con_datos={
				  action:'ajax',
				  page:pagina
				  };
		  
     $("#load_inactivos_registrados").fadeIn('slow');
     
     $.ajax({
               beforeSend: function(objeto){
                 $("#load_inactivos_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>');
               },
               url: 'index.php?controller=Usuarios&action=consulta_usuarios_inactivos&search='+search,
               type: 'POST',
               data: con_datos,
               success: function(x){
                 $("#users_inactivos_registrados").html(x);
                 $("#load_inactivos_registrados").html("");
                 $("#tabla_usuarios_inactivos").tablesorter(); 
                 
               },
              error: function(jqXHR,estado,error){
                $("#users_inactivos_registrados").html("Ocurrio un error al cargar la informacion de Usuarios..."+estado+"    "+error);
              }
            });


	   }

  

   
</script>

<script type="text/javascript">


$(document).ready(function(){

	

            var cedula_usuarios = $("#cedula_usuarios").val();

            if(cedula_usuarios>0){

             }else{
       		
			$( "#cedula_usuarios" ).autocomplete({

				source: "<?php echo $helper->url("Usuarios","AutocompleteCedula"); ?>",
  				minLength: 4
			});

			$("#cedula_usuarios").focusout(function(){
				validarcedula();
				$.ajax({
					url:'<?php echo $helper->url("Usuarios","AutocompleteDevuelveNombres"); ?>',
					type:'POST',
					dataType:'json',
					data:{cedula_usuarios:$('#cedula_usuarios').val()}
				}).done(function(respuesta){

					$('#id_usuarios').val(respuesta.id_usuarios);					
					$('#nombre_usuarios').val(respuesta.nombre_usuarios);
					$('#apellidos_usuarios').val(respuesta.apellidos_usuarios);
					$('#usuario_usuarios').val(respuesta.usuario_usuarios);
					$('#fecha_nacimiento_usuarios').val(respuesta.fecha_nacimiento_usuarios);
					$('#celular_usuarios').val(respuesta.celular_usuarios);
					$('#telefono_usuarios').val(respuesta.telefono_usuarios);
					$('#correo_usuarios').val(respuesta.correo_usuarios);					
					$('#codigo_clave').val(respuesta.clave_n_claves);

					if(respuesta.id_rol>0){
						$('#id_rol_principal option[value='+respuesta.id_rol+']').attr('selected','selected');
						}

					if(respuesta.estado_usuarios>0){
						$('#id_estado option[value='+respuesta.estado_usuarios+']').attr('selected','selected');
						}

					if(respuesta.caduca_claves=='t'){
						
						$('#caduca_clave').attr('checked','checked');
					}

					if( typeof respuesta.clave_n_usuarios !== "undefined"){
						$('#clave_usuarios').val(respuesta.clave_n_claves).attr('readonly','readonly');
						$('#clave_usuarios_r').val(respuesta.clave_n_claves).attr('readonly','readonly');
						$('#lbl_cambiar_clave').text("Cambiar Clave:  ");
						$('#cambiar_clave').show();
							
							
						}

					

                    if(respuesta.privilegios.length>0){
                    	 $('#lista_roles').empty();
                    	 $.each(respuesta.privilegios, function(k, v) {
                    		 $('#lista_roles').append("<option value= " +v.id_rol +" >" + v.nombre_rol  + "</option>");
                 		   
                    	});
					}
					
					
					
				
    			}).fail(function(respuesta) {

    				$('#id_usuarios').val("");
					$('#nombre_usuarios').val("");
					$('#apellidos_usuarios').val("");
					$('#usuario_usuarios').val("");
					$('#fecha_nacimiento_usuarios').val("");
					$('#celular_usuarios').val("");
					$('#telefono_usuarios').val("");
					$('#correo_usuarios').val("");
					$('#clave_usuarios').val("");
					$('#clave_usuarios_r').val("");
					    			    
    			  });
				 
				
			});  
            }

            
            
			
		});


 </script>
        
        
         <script type="text/javascript" >
		    // cada vez que se cambia el valor del combo
		    $(document).ready(function(){

			    
		    $("#Cancelar").click(function() 
			{
			 $("#cedula_usuarios").val("");
		     $("#nombre_usuarios").val("");
		     $("#clave_usuarios").val("");
		     $("#clave_usuarios_r").val("");
		     $("#telefono_usuarios").val("");
		     $("#celular_usuarios").val("");
		     $("#correo_usuarios").val("");
		     $("#id_rol").val("");
		     $("#id_estado").val("");
		     $("#fotografia_usuarios").val("");
		     $("#id_usuarios").val("");
		     
		    }); 
		    }); 
			</script>
        
        
        
        
         
        <script  type="text/javascript">
		    // cada vez que se cambia el valor del combo
	    $(document).ready(function(){

		    $("#Guardar").click(function() 
			{
		    	selecionarTodos();
		    	
		    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

		    	var cedula_usuarios = $("#cedula_usuarios").val();
		    	var nombre_usuarios = $("#nombre_usuarios").val();
		    			    	
		    	var id_rol  = $("#id_rol").val();
		    	var id_estado  = $("#id_estado").val();
		    	var id_rol_principal = $("#id_rol_principal").val();
		    	
		    	
		    	if (cedula_usuarios == "")
		    	{
			    	
		    		$("#mensaje_cedula_usuarios").text("Introduzca Identificación");
		    		$("#mensaje_cedula_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_cedula_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}    
				
		    	if (nombre_usuarios == "")
		    	{
			    	
		    		$("#mensaje_nombre_usuarios").text("Introduzca un Nombre");
		    		$("#mensaje_nombre_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_nombre_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}

		    	if ($("#apellidos_usuarios").val() == "")
		    	{
			    	
		    		$("#mensaje_apellido_usuarios").text("Introduzca un apellido");
		    		$("#mensaje_apellido_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_apellido_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}

				/*para input fecha nacimiento*/				
				if ($("#fecha_nacimiento_usuarios").val() == "")
		    	{
			    	
		    		$("#mensaje_fecha_nacimiento_usuarios").text("Introduzca fecha Nacimiento");
		    		$("#mensaje_fecha_nacimiento_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_fecha_nacimiento_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}

				/*para input usuario*/				
				if ($("#usuario_usuarios").val() == "")
		    	{
			    	
		    		$("#mensaje_usuario_usuarios").text("Introduzca Nombre de Usuario");
		    		$("#mensaje_usuario_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_usuario_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}

				/*para input celular*/				
				if ($("#celular_usuarios").val() == "")
		    	{
			    	
		    		$("#mensaje_celular_usuarios").text("Introduzca Celular");
		    		$("#mensaje_celular_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_celular_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}

				/* input correos */
				
		    	if ($("#correo_usuarios") == "")
		    	{
			    	
		    		$("#mensaje_correo_usuarios").text("Introduzca un correo");
		    		$("#mensaje_correo_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else if (regex.test($('#correo_usuarios').val().trim()))
		    	{
		    		$("#mensaje_correo_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    	else 
		    	{
		    		$("#mensaje_correo_usuarios").text("Introduzca un correo Valido");
		    		$("#mensaje_correo_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;	
			    }
		    	
		    	    	
			
		    	if ($("#clave_usuarios").val() == "")
		    	{
		    		
		    		$("#mensaje_clave_usuarios").text("Introduzca una Clave");
		    		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }else if ($("#clave_usuarios").val().length!=4){
			    	$("#mensaje_clave_usuarios").text("Introduzca minimo 4 números");
		    		$("#mensaje_clave_usuarios").fadeIn("slow"); //Muestra mensaje de error
		            return false;
				}
		    	else 
		    	{
		    		$("#mensaje_clave_usuarios").fadeOut("slow"); //Muestra mensaje de error
		            
				}
		    	

		    	if ($("#clave_usuarios_r").val() == "")
		    	{
		    		
		    		$("#mensaje_clave_usuarios_r").text("Introduzca una Clave");
		    		$("#mensaje_clave_usuarios_r").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else 
		    	{
		    		$("#mensaje_clave_usuarios_r").fadeOut("slow"); 
		            
				}
		    	
		    	if ($("#clave_usuarios").val() != $("#clave_usuarios_r").val())
		    	{
			    	
		    		$("#mensaje_clave_usuarios_r").text("Claves no Coinciden");
		    		$("#mensaje_clave_usuarios_r").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else
		    	{
		    		$("#mensaje_clave_usuarios_r").fadeOut("slow"); 
			        
		    	}	

		    	/*para input_select estado usuario*/
		    	var id_estado = $("#id_estado").val();
		    	if (id_estado == 0 )
		    	{
			    	
		    		$("#mensaje_id_estados").text("Seleccione un Estado");
		    		$("#mensaje_id_estados").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else
		    	{
		    		$("#mensaje_id_estados").fadeOut("slow"); 
			        
		    	}	

		    	if (id_rol_principal == 0 )
		    	{
			    	
		    		$("#mensaje_id_rol_principal").text("Seleccione Rol Principal");
		    		$("#mensaje_id_rol_principal").fadeIn("slow"); //Muestra mensaje de error
		            return false;
			    }
		    	else
		    	{
		    		$("#mensaje_id_rol_principal").fadeOut("slow"); 
			        
		    	}	
					    

			}); 


		        $( "#cedula_usuarios" ).focus(function() {
				  $("#mensaje_cedula_usuarios").fadeOut("slow");
			    });
				
				$( "#nombre_usuarios" ).focus(function() {
					$("#mensaje_nombre_usuarios").fadeOut("slow");
    			});

				$( "#apellidos_usuarios" ).focus(function() {
					$("#mensaje_apellido_usuarios").fadeOut("slow");
    			});

				$( "#fecha_nacimiento_usuarios" ).focus(function() {
					$("#mensaje_fecha_nacimiento_usuarios").fadeOut("slow");
    			});
    			
				$( "#clave_usuarios" ).focus(function() {
					$("#mensaje_clave_usuarios").fadeOut("slow");
    			});
				$( "#clave_usuarios_r" ).focus(function() {
					$("#mensaje_clave_usuarios_r").fadeOut("slow");
    			});
				
				$( "#celular_usuarios" ).focus(function() {
					$("#mensaje_celular_usuarios").fadeOut("slow");
    			});
				
				$( "#correo_usuarios" ).focus(function() {
					$("#mensaje_correo_usuarios").fadeOut("slow");
    			});
    			
				$("#id_rol_principal").focus(function() {
					$("#mensaje_id_rol_principal").fadeOut("slow"); 
    			});
			
				
		      
				    
		}); 

	</script>
	
	<script type="text/javascript">
      function validarcedula() {
        var cad = document.getElementById("cedula_usuarios").value.trim();
        var total = 0;
        var longitud = cad.length;
        var longcheck = longitud - 1;

        if (cad !== "" && longitud === 10){
          for(i = 0; i < longcheck; i++){
            if (i%2 === 0) {
              var aux = cad.charAt(i) * 2;
              if (aux > 9) aux -= 9;
              total += aux;
            } else {
              total += parseInt(cad.charAt(i)); // parseInt o concatenará en lugar de sumar
            }
          }

          total = total % 10 ? 10 - total % 10 : 0;

          if (cad.charAt(longitud-1) == total) {
        	  $("#cedula_usuarios").val(cad);
          }else{
        	  $("#mensaje_cedula_usuarios").text("Introduzca Identificación Valida");
	    	$("#mensaje_cedula_usuarios").fadeIn("slow");
        	  document.getElementById("cedula_usuarios").focus();
        	  $("#cedula_usuarios").val("");
        	  
          }
        }
      }
    </script>
	
        
        
        
        
    <script type="text/javascript" >   
    function numeros(e){
        
        key = e.keyCode || e.which;
        tecla = String.fromCharCode(key).toLowerCase();
        letras = "0123456789";
        especiales = [8,37,39,46];
     
        tecla_especial = false
        for(var i in especiales){
        if(key == especiales[i]){
         tecla_especial = true;
         break;
            } 
        }
     
        if(letras.indexOf(tecla)==-1 && !tecla_especial)
            return false;
     }
    </script> 
    
    <script type="text/javascript">
    var interval, mouseMove;

    $(document).mousemove(function(){
        //Establezco la última fecha cuando moví el cursor
        mouseMove = new Date();
        /* Llamo a esta función para que ejecute una acción pasado x tiempo
         después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
        inactividad(function(){
        	window.location.href = "index.php?controller=Usuarios&amp;action=cerrar_sesion";
        }, 600);
      });

    $(document).scroll(function(){
        //Establezco la última fecha cuando moví el cursor
        mouseMove = new Date();
        /* Llamo a esta función para que ejecute una acción pasado x tiempo
         después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
        inactividad(function(){
        	window.location.href = "index.php?controller=Usuarios&amp;action=cerrar_sesion";
        }, 600);
      });

      $(document).keydown(function(){
          //Establezco la última fecha cuando moví el cursor
          mouseMove = new Date();
          /* Llamo a esta función para que ejecute una acción pasado x tiempo
           después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
          inactividad(function(){
          	window.location.href = "index.php?controller=Usuarios&amp;action=cerrar_sesion";
          }, 600);
        });

     

      /* Función creada para ejecutar una acción (callback), al pasar x segundos 
         (seconds) de haber dejado de mover el cursor */
      var inactividad = function(callback, seconds){
        //Elimino el intervalo para que no se ejecuten varias instancias
        clearInterval(interval);
        //Creo el intervalo
        interval = setInterval(function(){
           //Hora actual
           var now = new Date();
           //Diferencia entre la hora actual y la última vez que se movió el cursor
           var diff = (now.getTime()-mouseMove.getTime())/1000;
           //Si la diferencia es mayor o igual al tiempo que pasastes por parámetro
           if(diff >= seconds){
            //Borro el intervalo
            clearInterval(interval);
            //Ejecuto la función que será llamada al pasar el tiempo de inactividad
            callback();          
           }
        }, 200);
      }
    </script>

	
	
	<script src="view/bootstrap/otros/inputmask_bundle/jquery.inputmask.bundle.js"></script>
       <script>
      $(document).ready(function(){
      $(".cantidades1").inputmask();
      });
	  </script>
 	
 	
 	
             