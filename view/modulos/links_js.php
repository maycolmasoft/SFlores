
    
      <script src="//code.jquery.com/jquery-1.10.2.js"></script>
      <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
      
      
    <script src="view/bootstrap/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="view/bootstrap/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="view/bootstrap/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="view/bootstrap/dist/js/adminlte.min.js"></script>
    <script src="view/bootstrap/dist/js/demo.js"></script>
    
    <script src="view/bootstrap/otros/datatables/datatables.min.js" >  </script>
    <script src="view/bootstrap/otros/table-sorter/jquery.tablesorter.min.js" >  </script>
    <!-- para los mensajes en alert sweetalert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" >  </script>

	
	 <script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: true,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                dom: '<"html5buttons">lfrtipB',
              // dom: 'lfrtipB',
    
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Reporte'},
                    {extend: 'pdf', title: 'Reporte'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });

        function generaTabla(ObjTabla){	
        	
        	$("#"+ObjTabla).DataTable({
        		paging: false,
                scrollX: true,
        		searching: false,
                pageLength: 10,
                responsive: true,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                dom: '<"html5buttons">lfrtipB',      
                buttons: [ ],
                language: {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
                    "infoEmpty": "Mostrando 0 de 0 de 0 Registros",           
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }

            });
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
        	window.location.href = "index.php?controller=Usuarios&action=cerrar_sesion";
        }, 6000);
      });

    $(document).scroll(function(){
        //Establezco la última fecha cuando moví el cursor
        mouseMove = new Date();
        /* Llamo a esta función para que ejecute una acción pasado x tiempo
         después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
        inactividad(function(){
        	window.location.href = "index.php?controller=Usuarios&action=cerrar_sesion";
        }, 6000);
      });

      $(document).keydown(function(){
          //Establezco la última fecha cuando moví el cursor
          mouseMove = new Date();
          /* Llamo a esta función para que ejecute una acción pasado x tiempo
           después de haber dejado de mover el mouse (en este caso pasado 3 seg) */
          inactividad(function(){
          	window.location.href = "index.php?controller=Usuarios&action=cerrar_sesion";
          }, 6000);
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
        }, 50);
      }

   </script>
   
   