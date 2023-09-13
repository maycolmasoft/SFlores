
<?php 


$controladores=$_SESSION['controladores'];
 function getcontrolador($controlador,$controladores){
 	$display="display:none";
 	
 	if (!empty($controladores))
 	{
 	foreach ($controladores as $res)
 	{
 		if($res->nombre_controladores==$controlador)
 		{
 			$display= "display:block";
 			break;
 			
 		}
 	}
 	}
 	
 	return $display;
 }
 
?>



   <ul class="sidebar-menu" data-widget="tree">
       <li class="header">MAIN NAVIGATION</li>
	   
       
        <li class="treeview"  style="<?php echo getcontrolador("MenuAdministracion",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-user"></i> <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("MenuAdministracion",$controladores) ?>"  >
                  <a href="#">
                    <i class="fa fa-folder-open-o"></i> <span>Mantenimiento</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    
                <li style="<?php echo getcontrolador("Usuarios",$controladores) ?>"><a href="index.php?controller=Usuarios&action=index"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                <li style="<?php echo getcontrolador("Clientes",$controladores) ?>"><a href="index.php?controller=Clientes&action=index"><i class="fa fa-circle-o"></i> Clientes</a></li>
             	<li style="<?php echo getcontrolador("Controladores",$controladores) ?>"><a href="index.php?controller=Controladores&action=index"><i class="fa fa-circle-o"></i> Controladores</a></li>
                <li style="<?php echo getcontrolador("Roles",$controladores) ?>"><a href="index.php?controller=Roles&action=index"><i class="fa fa-circle-o"></i> Roles de Usuario</a></li>
                <li style="<?php echo getcontrolador("PermisosRoles",$controladores) ?>"><a href="index.php?controller=PermisosRoles&action=index"><i class="fa fa-circle-o"></i> Permisos Roles</a></li>
                <li style="<?php echo getcontrolador("Estados",$controladores) ?>"><a href="index.php?controller=Estados&action=index"><i class="fa fa-circle-o"></i>Estados</a></li>
                <li style="<?php echo getcontrolador("Proveedores",$controladores) ?>"><a href="index.php?controller=Proveedores&action=index"><i class="fa fa-circle-o"></i> Proveedores</a></li>
         	  
              
                  </ul>
                </li>
             
      	 	</ul>
			
      	</li>
      
	   <li class="treeview"  style="<?php echo getcontrolador("MenuAlmacen",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-user"></i> <span>Almacen</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("MenuAlmacen",$controladores) ?>"  >
                  <a href="#">
                    <i class="fa fa-folder-open-o"></i> <span>Mantenimiento</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                  <li style="<?php echo getcontrolador("Productos",$controladores) ?>"><a href="index.php?controller=Productos&action=index"><i class="fa fa-circle-o"></i> Productos</a></li>
                  <li style="<?php echo getcontrolador("LineaProductos",$controladores) ?>"><a href="index.php?controller=LineaProductos&action=index"><i class="fa fa-circle-o"></i> Línea Productos</a></li>
                  <li style="<?php echo getcontrolador("TipoProductos",$controladores) ?>"><a href="index.php?controller=TipoProductos&action=index"><i class="fa fa-circle-o"></i> Tipo Productos</a></li>
                  </ul>
                </li>
             
      	 	</ul>
			
      	</li>
      	
		  <li class="treeview"  style="<?php echo getcontrolador("MenuVentas",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-user"></i> <span>Ventas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
                  <li style="<?php echo getcontrolador("Caja",$controladores) ?>"><a href="index.php?controller=Caja&action=index"><i class="fa fa-circle-o"></i> Administrar Caja</a></li>
                  <li style="<?php echo getcontrolador("PuntoVentas",$controladores) ?>"><a href="index.php?controller=PuntoVentas&action=index"><i class="fa fa-circle-o"></i> Punto de Venta</a></li>
             
      	 	</ul>
			
      	</li>
		
		
		 <li class="treeview"  style="<?php echo getcontrolador("MenuCompras",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-user"></i> <span>Compras</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
                  <li style="<?php echo getcontrolador("Compras",$controladores) ?>"><a href="index.php?controller=Compras&action=index"><i class="fa fa-circle-o"></i> Compras</a></li>
             
      	 	</ul>
			
      	</li>
		
      
         <li class="treeview"  style="<?php echo getcontrolador("MenuEncuestas",$controladores) ?>"  >
          <a href="#">
            <i class="glyphicon glyphicon-user"></i> <span>Encuestas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li class="treeview"  style="<?php echo getcontrolador("MenuEncuestasProcesos",$controladores) ?>"  >
                  <a href="#">
                    <i class="fa fa-folder-open-o"></i> <span>Procesos</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                 	 <li style="<?php echo getcontrolador("Encuestas",$controladores) ?>"><a href="index.php?controller=Encuesta&action=index"><i class="fa fa-circle-o"></i> Registrar Encuestas</a></li>
                  </ul>
                </li>
             
           <!--  <li class="treeview"  style="<?php echo getcontrolador("MenusupermercadosConsultas",$controladores) ?>"  >
                  <a href="#">
                    <i class="fa fa-folder-open-o"></i> <span>Consultas</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                 	 <li style="<?php echo getcontrolador("Encuestas",$controladores) ?>"><a href="index.php?controller=Encuesta&action=index_search"><i class="fa fa-circle-o"></i> Consultar Encuestas</a></li>
                  </ul>
                </li>
                -->
                
                 <li class="treeview"  style="<?php echo getcontrolador("MenuEncuestasReportes",$controladores) ?>"  >
                  <a href="#">
                    <i class="fa fa-folder-open-o"></i> <span>Reporte</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                 	 <li style="<?php echo getcontrolador("Encuestas",$controladores) ?>"><a href="index.php?controller=Geoposicionamiento&action=index_report"><i class="fa fa-circle-o"></i> Mapa</a></li>
                     <li style="<?php echo getcontrolador("Encuestas",$controladores) ?>"><a href="index.php?controller=Encuesta&action=index_excel"><i class="fa fa-circle-o"></i> Excel</a></li>
                                   
				 </ul>
                </li>
               
      	 	</ul>
      	 	
      	</li>
 
      

    </ul>
    