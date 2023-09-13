<?php
class UsuariosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public function index(){
	
	session_start();
	
	if (isset($_SESSION['nombre_usuarios']) )
	{
		
		$resultSet="";
		
		$usuarios = new UsuariosModel();

		$nombre_controladores = "Usuarios";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $usuarios->getPermisosEditar("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
		    $resultEdit = "";
		    
		    $resultRolPrincipal = array();		    
		    $resEstado = array();
		    $resEstado = $usuarios->getCondiciones('*','estado',"tabla_estado='USUARIOS'",'id_estado');
		   
		    $rol=new UsuariosModel();
		   
		    if($id_rol==1){
		        //administrador
		        $resultRol = $rol->getCondiciones('*','rol',"1=1",'nombre_rol');
		        
		    }else{
		        //todos solo veras los participes
		        $resultRol = $rol->getCondiciones('*','rol',"id_rol=2",'nombre_rol');
		        
		    }
		    
		    $entidad= new UsuariosModel();
		    $resultEntidad= $entidad->getCondiciones('*','entidades',"1=1",'nombre_entidades');
		    
		
				if (isset ($_GET["id_usuarios"])   )
				{
					
				    $columnas = "  usuarios.id_usuarios,
            	    usuarios.cedula_usuarios,
            	    usuarios.nombre_usuarios,
            	    usuarios.apellidos_usuarios,
                    usuarios.usuario_usuarios,
                    usuarios.fecha_nacimiento_usuarios,
            	    claves.clave_claves,
            	    claves.clave_n_claves,
                    claves.caduca_claves,
            	    usuarios.telefono_usuarios,
            	    usuarios.celular_usuarios,
            	    usuarios.correo_usuarios,
            	    rol.id_rol,
            	    rol.nombre_rol,
            	    usuarios.fotografia_usuarios,
            	    usuarios.creado,
                    usuarios.id_estado,
                    eu.nombre_estado,
                    e.id_entidades,
                    e.nombre_entidades";
				    
				    $tablas = " public.usuarios INNER JOIN public.claves ON claves.id_usuarios = usuarios.id_usuarios
                    INNER JOIN public.estado ON estado.id_estado = claves.id_estado
                    INNER JOIN public.estado eu ON eu.id_estado = usuarios.id_estado
                    LEFT JOIN public.rol ON rol.id_rol = usuarios.id_rol
                    inner join public.entidades e on usuarios.id_entidades=e.id_entidades";
				    
				    $where = "estado.nombre_estado = 'ACTIVO'";
					
					$_id_usuarios = $_GET["id_usuarios"];
					$where .= " and usuarios.id_usuarios = '$_id_usuarios' "; 
					
					$id="usuarios.id_usuarios";
					
					$resultEdit = $usuarios->getCondiciones($columnas ,$tablas ,$where, $id); 
					
									
				}
				
			    
				
				$this->view_Administracion("Usuarios",array(
				    "resultSet"=>$resultSet, "resultRol"=>$resultRol, "resultEdit" =>$resultEdit,
				    "resEstado"=>$resEstado, "resultEntidad"=>$resultEntidad
			
				));
			
		}
		else
		{
		    $this->view_Administracion("Error",array(
					"resultado"=>"No tiene Permisos de Acceso a Usuarios"
		
			));
		
		}
		
	
    	}
    	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
		
	}
	
	
	public function InsertaUsuarios(){
			
		session_start();
		$resultado = null;
		$usuarios=new UsuariosModel();
		$_array_roles=array();	
		
		
		/*para la consulta de catalogos*/		
		$claves = null; $claves = new UsuariosModel();		
		
		if (isset(  $_SESSION['nombre_usuarios']) )
		{
		    
	
    		if (isset ($_POST["cedula_usuarios"]))
    		{
    		   
    			$_cedula_usuarios     = rtrim(ltrim($_POST["cedula_usuarios"]));
    			$_nombre_usuarios     = rtrim(ltrim($_POST["nombre_usuarios"]));
    			$_apellidos_usuario     = rtrim(ltrim($_POST["apellidos_usuarios"]));
    			$_fecha_nacimiento_usuarios = $_POST['fecha_nacimiento_usuarios'];
    			$_usuario_usuarios    = rtrim(ltrim($_POST['usuario_usuarios']));
    			$_clave_usuarios      = $usuarios->encriptar(rtrim(ltrim($_POST["clave_usuarios"])));
    			$_clave_n_usuarios    = rtrim(ltrim($_POST["clave_usuarios"]));
    			$_telefono_usuarios   = $_POST["telefono_usuarios"];
    			$_celular_usuarios    = $_POST["celular_usuarios"];
    			$_correo_usuarios     = $_POST["correo_usuarios"];
    		    $_id_rol_principal    = $_POST["id_rol_principal"];
    		    $_array_roles         = isset($_POST["lista_roles"])?$_POST["lista_roles"]:array();
    		    $_id_estado           = $_POST["id_estado"];		    
    		    $_id_usuarios         = $_POST["id_usuarios"];
    		    $_id_entidades        = $_POST["id_entidades"];
    		    
    		    $_caduca_clave        = isset($_POST['caduca_clave'])?$_POST['caduca_clave']:"0";
    		    $_cambiar_clave       = isset($_POST['cambiar_clave'])?$_POST['cambiar_clave']:"0";
    		    
    		    
    		    //para la imagen del usuario
    		    $imagen_usuarios='';
    		    
    		    		    
    		    if ($_FILES['fotografia_usuarios']['tmp_name']!="")
    		    {
    		        $directorio = $_SERVER['DOCUMENT_ROOT'].'/SFlores/fotografias_usuarios/';
    		        
    		        $nombre = $_FILES['fotografia_usuarios']['name'];
    		        $tipo = $_FILES['fotografia_usuarios']['type'];
    		        $tamano = $_FILES['fotografia_usuarios']['size'];
    		        
    		        move_uploaded_file($_FILES['fotografia_usuarios']['tmp_name'],$directorio.$nombre);
    		        $data = file_get_contents($directorio.$nombre);
    		        $imagen_usuarios = pg_escape_bytea($data);
    		        		        
    		    }else{
    		        

					if($_id_usuarios>0){
					
    		        $resultPart=$usuarios->getCondiciones("fotografia_usuarios", "usuarios", "id_usuarios='$_id_usuarios'", "id_usuarios");
    		        }else{
						$resultPart="";
					}
					
					
    		        if(!empty($resultPart)){
    		            
    		            
    		            if(!empty($resultPart[0]->fotografia_usuarios)){
    		               
    		                $imagen_usuarios    =$resultPart[0]->fotografia_usuarios;
    		                
    		            }else{
    		                
    		                $directorio = dirname(__FILE__).'\..\view\images\usuario.jpg';
    		                
    		                if( is_file( $directorio )){
    		                    $data = file_get_contents($directorio);
    		                    $imagen_usuarios = pg_escape_bytea($data);
    		                }
    		                
    		            } 
    		        }else{
    		        
            		        $directorio = dirname(__FILE__).'\..\view\images\usuario.jpg';
            		        
            		        if( is_file( $directorio )){
            		            $data = file_get_contents($directorio);
            		            $imagen_usuarios = pg_escape_bytea($data);
            		        } 
    		        
    		        } 
    		        
    		    }
    		    
    		    
    		    
    		    //para fecha de insersion clave
    		    $clave_fecha_hoy = date("Y-m-d");		    
    		    $clave_fecha_siguiente_mes = date("Y-m-d",strtotime($clave_fecha_hoy."+ 1 month"));
    		    
    		    $_clave_caduca="0";
    		    
    		    if((int)$_caduca_clave ==1 || $_caduca_clave=="on"){
    		        
    		        $_clave_caduca="1";
    		        
    		    }
    		    
    		    if($_id_usuarios>0){
    		        //para actualizacion de usuarios
    		        
    		        $cambio_clave ="0";
    		        
    		        if((int)$_cambiar_clave ==1 || $_cambiar_clave=="on"){
    		            
    		            $cambio_clave="1";
    		            
    		        }
    		        		        
    		        $funcion = "ins_usuarios";
    		        $parametros = "'$_cedula_usuarios',
    		    				   '$_nombre_usuarios',
                                   '$_apellidos_usuario',
                                   '$_correo_usuarios',
                                   '$_celular_usuarios',
    		    	               '$_telefono_usuarios',
    		    	               '$_fecha_nacimiento_usuarios',
    		    	               '$_usuario_usuarios',
    		    	               '$_id_estado',
    		    	               '$imagen_usuarios',
                                   '$_id_rol_principal',
                                   '$_clave_usuarios',
                                   '$_clave_n_usuarios',
                                   '$clave_fecha_hoy',
                                   '$clave_fecha_siguiente_mes',
                                   '$_clave_caduca',
                                   '$cambio_clave',
                                    '$_id_entidades'";
    		        $usuarios->setFuncion($funcion);
    		        $usuarios->setParametros($parametros);
    		        
    		        
    		        $resultado=$usuarios->llamafuncion();
    		        
    		        $respuesta = '';
    		        
    		        if(!empty($resultado) && count($resultado)){
    		            
    		            foreach ($resultado[0] as $k => $v)
    		            {
    		                $respuesta=$v;
    		            }
    		            
    		            if (strpos($respuesta, 'OK') !== false) {
    		                
    		                echo json_encode(array('success'=>1,'mensaje'=>$respuesta));
    		            }else{
    		                echo json_encode(array('success'=>0,'mensaje'=>$respuesta));
    		            }
    		            
    		        }
    		        
    		        
    		    }else{
    		        
    		        //para insertado de usuarios
    		        /*no hay cambio de clave*/
    		        $cambioclave = 0;
    		        
    		        $funcion = "ins_usuarios";
    		        $parametros = "'$_cedula_usuarios',
    		    				   '$_nombre_usuarios',
                                   '$_apellidos_usuario',
                                   '$_correo_usuarios',
                                   '$_celular_usuarios',
    		    	               '$_telefono_usuarios',
    		    	               '$_fecha_nacimiento_usuarios',
    		    	               '$_usuario_usuarios',
    		    	               '$_id_estado',
    		    	               '$imagen_usuarios',
                                   '$_id_rol_principal',
                                   '$_clave_usuarios',
                                   '$_clave_n_usuarios',
                                   '$clave_fecha_hoy',
                                   '$clave_fecha_siguiente_mes',
                                   '$_clave_caduca',
                                   '$cambioclave',
                                    '$_id_entidades'";
    		        $usuarios->setFuncion($funcion);
    		        $usuarios->setParametros($parametros);
    		        
    		        
    		        $resultado=$usuarios->llamafuncion();
    		        
    		        $respuesta = '';
    		        
    		        if(!empty($resultado) && count($resultado)){
    		            
    		            foreach ($resultado[0] as $k => $v)
    		            {
    		                $respuesta=$v;
    		            }
    		            
    		            if (strpos($respuesta, 'OK') !== false) {
    		               
    		                echo json_encode(array('success'=>1,'mensaje'=>$respuesta));
    		            }else{
    		                echo json_encode(array('success'=>0,'mensaje'=>$respuesta));
    		            }
    		            
    		        }
    		            
    		        
    		    }		    
    		   
    		}
		
	   }else{
	       
	       echo json_encode(array('success'=>0,'mensaje'=>'Session Caducada vuelva a Ingresar'));
	   		   	
	   }
	   
	}
	
	public function borrarId()
	{
	    session_start();
	    $id_usuario_on = (int)$_SESSION['id_usuarios'];
	    $usuarios = null; $usuarios= new UsuariosModel();
	    
		if(isset($_GET["id_usuarios"]))
		{
			$id_usuario=(int)$_GET["id_usuarios"];
			
			if($id_usuario_on!=$id_usuario){
			    
			    //estado_usuario
			    $whereestado = "nombre_estado='INACTIVO' AND  tabla_estado='USUARIOS'";
			    $resultEstado = $usuarios->getCondiciones('id_estado' ,'public.estado' , $whereestado , 'id_estado');
			    $estado_usuarios = $resultEstado[0]->id_estado;
			   
			    $colval = "id_estado='$estado_usuarios'";
			    $tabla = "usuarios";
			    $where = "id_usuarios = '$id_usuario'";
			    
			    $resultado=$usuarios->UpdateBy($colval, $tabla, $where);
			    
			}
			
		}
	
		$this->redirect("Usuarios", "index");
	}
	

	
	public function resetear_clave_inicio()
	{
		session_start();
		$_usuario_usuario = "";
		$_clave_usuario = "";
		$usuarios = new UsuariosModel();
		$error = FALSE;
	
	
		$mensaje = "";
	
		if (isset($_POST['cedula_usuarios']))
		{
			$_cedula_usuarios = $_POST['cedula_usuarios'];
	
			$where = "cedula_usuarios = '$_cedula_usuarios'   ";
			$resultUsu = $usuarios->getBy($where);
				
			if(!empty($resultUsu))
			{
	
				foreach ($resultUsu as $res){
						
				    $id_usuarios=$res->id_usuarios;
					$correo_usuario=$res->correo_usuarios;
					$id_estado=$res->id_estado;
					$nombre_usuario   = $res->nombre_usuarios;
				}
	
	
				$cadena = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
				$longitudCadena=strlen($cadena);
				$pass = "";
				$longitudPass=10;
				for($i=1 ; $i<=$longitudPass ; $i++){
					$pos=rand(0,$longitudCadena-1);
					$pass .= substr($cadena,$pos,1);
				}
				$_clave_usuario= $pass;
				$_encryp_pass = $usuarios->encriptar($_clave_usuario);
					
			}
	
			if ($_clave_usuario == "")
			{
				$mensaje = "Este Usuario no existe resgistrado en nuestro sistema.";
	
				$error = TRUE;
	
	
			}
			else
			{
				
				if($id_estado==1){
				    
				    
				    $query1 = " SELECT id_estado FROM estado WHERE tabla_estado='CLAVES' AND nombre_estado='INACTIVO'";
				    $resultado_estado  = $usuarios->enviaquery($query1);
				    
				    
				    if(!empty($resultado_estado)){
				        $id_estado_claves_inactivo= $resultado_estado[0]->id_estado;
				    }
				    
				    
				    $usuarios->UpdateBy("id_estado='$id_estado_claves_inactivo'", "claves", "id_usuarios='$id_usuarios'");
				    
				    
				    $query2 = " SELECT id_estado FROM estado WHERE tabla_estado='CLAVES' AND nombre_estado='ACTIVO'";
				    $var_id_estado_clave  = $usuarios->enviaquery($query2);
				    
				    if(!empty($var_id_estado_clave)){
				        $id_estado_claves_activo= $var_id_estado_clave[0]->id_estado;
				    }
				    
				    $clave_fecha_hoy = date("Y-m-d");
				    $clave_fecha_siguiente_mes = date("Y-m-d",strtotime($clave_fecha_hoy."+ 5 year"));
				    
				    
				    //INSERCION DE NUEVA CLAVE
				    
				    $insert_1= "
				    INSERT INTO claves(
				        id_usuarios,
				        clave_claves,
				        fecha_registro_claves,
				        clave_n_claves,
				        fecha_caducidad_claves,
				        caduca_claves,
				        id_estado,
				        estado_claves)
				        VALUES (
				            '$id_usuarios',
				            '$_encryp_pass',
				            '$clave_fecha_hoy',
				            '$_clave_usuario',
				            '$clave_fecha_siguiente_mes',
				            'FALSE',
				            '$id_estado_claves_activo',
				            1
				            )";
				    
				    
				    $result_insert  = $usuarios->enviaquery($insert_1);
				    
					
				$cabeceras = "MIME-Version: 1.0 \r\n";
				$cabeceras .= "Content-type: text/html; charset=utf-8 \r\n";
				$cabeceras.= "From: maycol@masoft.net \r\n";
				$destino="$correo_usuario";
				$asunto="Claves de Acceso";
				$fecha=date("d/m/y");
				$hora=date("H:i:s");
	
	
				$resumen="
				<table rules='all'>
				<tr><td WIDTH='1000' HEIGHT='50'><center><img src='http://localhost:4000/SFlores/view/images/logo.png' WIDTH='300' HEIGHT='120'/></center></td></tr>
				</tabla>
			
				<p><table rules='all'></p>
				<tr style='background: #FFFFFF'><td WIDTH='1000' align='center'><b> TUS DATOS DE ACCESO SON: </b></td></tr>
				<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>Usuario:</b> $_cedula_usuarios</td></tr>
				<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>Clave Temporal:</b> $_clave_usuario </td></tr>
				</tabla>
				<p><table rules='all'></p>
				<tr style='background:#1C1C1C'><td WIDTH='1000' HEIGHT='50' align='center'><font color='white'>Maycol - <a href='http://www.google.com.ec'><FONT COLOR='#7acb5a'>www.maycolflores.com.ec</FONT></a> - Copyright © 2022-</font></td></tr>
				</table>
				";
	
	
				if(mail("$destino","Claves de Acceso Maycol","$resumen","$cabeceras"))
				{
					$mensaje = "Te hemos enviado un correo electrónico a $correo_usuario con tus datos de acceso.";
						
	
				}else{
					$mensaje = "No se pudo enviar el correo con la información. Intentelo nuevamente.";
					$error = TRUE;
	
				}
			
				}else{
					
					
					$error = TRUE;
					$mensaje = "Hola $nombre_usuario tu usuario se encuentra inactivo.";
						
						
					$this->view_Administracion("Login",array(
							"resultSet"=>"$mensaje", "error"=>$error
					));
						
						
					die();
					
				}
				
			}
			 
			$this->view_Administracion("Login",array(
					"resultSet"=>"$mensaje", "error"=>$error
			));
			 
			 
			die();
			
		}else{
			
			$mensaje = "Ingresa tu cedula para recuperar tu clave.";
			$error = TRUE;
		}
	
	
	
		$this->view_Administracion("ResetUsuariosInicio",array(
				"resultSet"=>$mensaje , "error"=>$error
		));
	
	}
	
	public function Inicio(){
	
		session_start();
		
		$this->view_Administracion("Login",array(
				"allusers"=>""
		));
	}
    
    
    public function Login(){
    
    	session_destroy();
    	$usuarios=new UsuariosModel();
    
    	//Conseguimos todos los usuarios
    	$allusers=$usuarios->getLogin();
    	 
    	//Cargamos la vista index y l e pasamos valores
    	$this->view_Administracion("Login",array(
    			"allusers"=>$allusers
    	));
    }
    
    
    public function Bienvenida(){
    
    	session_start();
    	
    	if(isset($_SESSION['id_usuarios']))
    	{
    		$_usuario=$_SESSION['nombre_usuarios'];
    		$_id_rol=$_SESSION['id_rol'];
    		
    		if($_id_rol==1){
    		    
    		    
    		    $this->view_Administracion("BienvenidaAdmin",array(
    		        "allusers"=>$_usuario
    		    ));
    		    
    		    die();
    		    
    		}else{
    		    
    		    $this->view_Administracion("Bienvenida",array(
    		        "allusers"=>$_usuario
    		    ));
    		    
    		    die();
    		}
    		
    		 
    	}else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
    }
    
    
    public function Loguear(){
        
    	$error=FALSE;
    	if (isset($_POST["usuario"]) && ($_POST["clave"] ) )
    	{
    	
    		
    	    
    		$usuarios=new UsuariosModel();
    		$_usuario = $_POST["usuario"];
    		$_clave =   $usuarios->encriptar($_POST["clave"]);
    		
    		 
    		
    		
    		$columnas="usuarios.id_usuarios,
                      usuarios.cedula_usuarios, 
                      usuarios.nombre_usuarios, 
                      usuarios.apellidos_usuarios, 
                      usuarios.correo_usuarios, 
                      usuarios.celular_usuarios, 
                      usuarios.telefono_usuarios, 
                      usuarios.fecha_nacimiento_usuarios, 
                      usuarios.usuario_usuarios,
                      usuarios.id_estado,
                      usuarios.id_entidades,  
                      claves.clave_claves, 
                      claves.estado_claves,
                      rol.id_rol,
                      estado.nombre_estado";
    		
    		$tablas="public.claves, 
                      public.usuarios,
                     public.rol,
                     public.estado";
    		
    		$where="usuarios.id_usuarios = claves.id_usuarios
                    AND rol.id_rol = usuarios.id_rol 
                    AND estado.id_estado = usuarios.id_estado
                    AND usuarios.id_estado=1
                    AND claves.estado_claves=1
                    AND claves.id_estado=7
                    AND usuarios.cedula_usuarios='$_usuario' AND claves.clave_claves='$_clave' ";
    		
    		$id="usuarios.cedula_usuarios";
    		
    		$result=$usuarios->getCondiciones($columnas, $tablas, $where, $id);
    		
    		$id_usuarios=0;
    		$usuario_usuarios = "";
    		$id_rol  = "";
    		$nombre_usuarios = "";
    		$apellido_usuarios = "";
    		$correo_usuarios = "";
    		$estado_usuarios=0;
    		$ip_usuarios = "";
    		$id_entidades = 0;
    		
    		if ( !empty($result) )
    		{ 
    			foreach($result as $res) 
    			{
    				$id_usuarios  = $res->id_usuarios;
    				$usuario_usuarios  = $res->usuario_usuarios;
	    			$id_rol           = $res->id_rol;
	    			$nombre_usuarios   = $res->nombre_usuarios;
	    			$apellido_usuarios   = $res->apellidos_usuarios;
	    			$correo_usuarios   = $res->correo_usuarios;
	    			$estado_usuarios       = $res->id_estado;
	    			$cedula_usuarios        = $res->cedula_usuarios;
	    			$id_entidades           = $res->id_entidades;
    			}	
    			
    			if($estado_usuarios==1){
    				
    				
    				//obtengo ip
    				$ip_usuarios = $usuarios->getRealIP();
    				 
    				///registro sesion
    				$usuarios->registrarSesion($id_usuarios, $usuario_usuarios, $id_rol, $nombre_usuarios, $apellido_usuarios, $correo_usuarios, $ip_usuarios, $cedula_usuarios, $id_entidades);
    				 
    				
    				$_id_rol=$_SESSION['id_rol'];    				
    				$usuarios->MenuDinamico($_id_rol);
    				
    				//inserto en la tabla
    				$_id_usuario = $_SESSION['id_usuarios'];
    				$sesiones = new UsuariosModel();
    				$funcion = "ins_sesiones";
    				$parametros = " '$_id_usuario' ,'$ip_usuarios' ";
    				$sesiones->setFuncion($funcion);
    				$sesiones->setParametros($parametros);
    				$resultado=$sesiones->Insert();
    				
    				
    				 
    				
    				if($_id_rol==1){
    					

    				    $this->view_Administracion("BienvenidaAdmin",array(
    							""=>""
    					));
    					
    					die();
    					
    				}else{
    				    
    				    $this->view_Administracion("Bienvenida",array(
    				        ""=>""
    				    ));
    				    
    				    die();
    				    
    				}
    				
    				
    			}else{
    				
    				
    				$error = TRUE;
    				$mensaje = "Hola $nombre_usuarios $apellido_usuarios tu usuario se encuentra inactivo.";
    				 
    				 
    				$this->view_Administracion("Login",array(
    						"resultSet"=>"$mensaje", "error"=>$error
    				));
    				 
    				 
    				die();
    			}
    			
    			
    		}
    		else
    		{
    			$error = TRUE;
    			$mensaje = "Este Usuario no existe resgistrado en nuestro sistema.";
    			
    			
    			$this->view_Administracion("Login",array(
	    				"resultSet"=>"$mensaje", "error"=>$error
	    		));
	    		
	    		
	    		die();
    		}
    		
    	} 
    	else
    	{
    		    $error = TRUE;
    			$mensaje = "Ingrese su cedula y su clave.";
    			
    			
    			$this->view_Administracion("Login",array(
	    				"resultSet"=>"$mensaje", "error"=>$error
	    		));
	    		
	    		
	    		die();
    		
    	}
    	
    }

    
    
    public function  sesion_caducada()
    {
    	session_start();
    	session_destroy();
    
    	$error = TRUE;
	    $mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
	    	
	    $this->view_Administracion("Login",array(
	    		"resultSet"=>"$mensaje", "error"=>$error
	    ));
	    	
	    die();
	    		
    
    }
    
    
	public function  cerrar_sesion ()
	{
		session_start();
		session_destroy();
		
		$error = TRUE;
		$mensaje = "Te has desconectado de nuestro sistema.";
		 
		 
		$this->view_Administracion("Login",array(
				"resultSet"=>"$mensaje", "error"=>$error
		));
		 
		 
		die();
		
		
	}
	
	
	public function  actualizo_perfil ()
	{
		session_start();
		session_destroy();
	
		$error = FALSE;
		$mensaje = "Actualizaste tus datos, vuelve a iniciar sesión.";	
			
		$this->view_Administracion("Login",array(
				"resultSet"=>"$mensaje", "error"=>$error
		));
			
			
		die();
	
	
	}
	
	
	
	
	
	
	
	
	
		
	public function paginate($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_usuarios(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_usuarios(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_usuarios(1)'>1</a></li>";
		}
		// interval
		if($page>($adjacents+2)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// pages
	
		$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
		$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
		for($i=$pmin; $i<=$pmax; $i++) {
			if($i==$page) {
				$out.= "<li class='active'><a>$i</a></li>";
			}else if($i==1) {
				$out.= "<li><a href='javascript:void(0);' onclick='load_usuarios(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_usuarios(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_usuarios($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_usuarios(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	
	//////////////////////////////////////////////BUSQUEDA DE USUARIOS///////////////////////////////////////
	/*ACTIVOS*/
	public function consulta_usuarios_activos(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $usuarios = new UsuariosModel();
	    $where_to="";
	    $columnas = " usuarios.id_usuarios,
					  usuarios.cedula_usuarios,
					  usuarios.nombre_usuarios,
                      usuarios.apellidos_usuarios,
					  claves.clave_claves,
					  claves.clave_n_claves,
					  usuarios.telefono_usuarios,
					  usuarios.celular_usuarios,
					  usuarios.correo_usuarios,
                      usuarios.id_estado,
					  rol.id_rol,
					  rol.nombre_rol,
					  usuarios.fotografia_usuarios,
					  usuarios.creado,
                      estado.nombre_estado,
                      e.id_entidades,
                      e.nombre_entidades";  
	    
	    $tablas = "public.usuarios 
            INNER JOIN public.claves ON claves.id_usuarios = usuarios.id_usuarios
            LEFT JOIN public.rol ON rol.id_rol = usuarios.id_rol
            INNER JOIN public.estado ON estado.id_estado = usuarios.id_estado
            INNER JOIN public.estado ec ON ec.id_estado = claves.id_estado
            INNER JOIN public.entidades e on usuarios.id_entidades=e.id_entidades";
	    
	    
	    $where    = "estado.nombre_estado = 'ACTIVO'
                AND estado.tabla_estado = 'USUARIOS'
                AND ec.nombre_estado = 'ACTIVO'";
	    
	    $id       = "usuarios.id_usuarios";
	    
	    
	    
	    if($id_rol==1){
	        //administrador
	        $where.="";
	    }else{
	        //todos solo veras los participes
	       // $where.=" and rol.id_rol=2";
	    }
	    
	   
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	       	        
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (usuarios.cedula_usuarios ILIKE '%".$search."%' OR usuarios.nombre_usuarios ILIKE '%".$search."%' OR usuarios.correo_usuarios ILIKE '%".$search."%' OR rol.nombre_rol ILIKE '%".$search."%' OR e.nombre_entidades ILIKE '%".$search."%'  )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	       
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	       	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:425px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_usuarios' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombres</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Teléfono</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Celular</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Correo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Rol</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            
	            if($id_rol==1){
	                
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                
	            }else{
	            
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                
	            }
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_usuarios.'&id_nombre=id_usuarios&tabla=usuarios&campo=fotografia_usuarios" width="80" height="60"></td>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellidos_usuarios.' '.$res->nombre_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->telefono_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->celular_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->correo_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_entidades.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_rol.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
	                 
	                
	                
	                if($id_rol==1){
	                    
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=index&id_usuarios='.$res->id_usuarios.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=borrarId&id_usuarios='.$res->id_usuarios.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
	                    
	                }else{
	                    
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=index&id_usuarios='.$res->id_usuarios.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
	                    
	                    
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_usuarios("index.php", $page, $total_pages, $adjacents,"load_usuarios").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	public function consulta_usuarios_inactivos(){
	    
	    session_start();
	    $id_rol=$_SESSION["id_rol"];
	    
	    $usuarios = new UsuariosModel();
	    $where_to="";
	    $columnas = " usuarios.id_usuarios,
					  usuarios.cedula_usuarios,
					  usuarios.nombre_usuarios,
                      usuarios.apellidos_usuarios,
					  usuarios.telefono_usuarios,
					  usuarios.celular_usuarios,
					  usuarios.correo_usuarios,
					  rol.id_rol,
					  rol.nombre_rol,
					  usuarios.fotografia_usuarios,
					  usuarios.creado,
                      estado.nombre_estado,
                      e.id_entidades,
                      e.nombre_entidades";
	    
	    $tablas = "public.usuarios
                    LEFT JOIN public.rol ON rol.id_rol=usuarios.id_rol
                    INNER JOIN public.estado ON estado.id_estado = usuarios.id_estado
                    INNER JOIN public.entidades e on usuarios.id_entidades=e.id_entidades";
	    
	    
	    $where    = "estado.nombre_estado = 'INACTIVO'";
	    
	    $id       = "usuarios.id_usuarios";
	    
	    
	    
	    
	    
	    if($id_rol==1){
	        //administrador
	        $where.="";
	    }else{
	        //todos solo veras los participes
	       // $where.=" and rol.id_rol=2";
	    }
	    
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    
	    
	    if($action == 'ajax')
	    {
	       
	        
	        if(!empty($search)){
	            
	            
	            $where1=" AND (usuarios.cedula_usuarios ILIKE '%".$search."%' OR usuarios.nombre_usuarios ILIKE '%".$search."%' OR usuarios.correo_usuarios ILIKE '%".$search."%' OR rol.nombre_rol ILIKE '%".$search."%' OR e.nombre_entidades ILIKE '%".$search."%'  )";
	            
	            $where_to=$where.$where1;
	        }else{
	            
	            $where_to=$where;
	            
	        }
	        
	        $html="";
	        $resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 10; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
	        $count_query   = $cantidadResult;
	        $total_pages = ceil($cantidadResult/$per_page);
	        
	        
	        if($cantidadResult>0)
	        {
	            
	            $html.='<div class="pull-left" style="margin-left:15px;">';
	            $html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
	            $html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
	            $html.='</div>';
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<section style="height:425px; overflow-y:scroll;">';
	            $html.= "<table id='tabla_usuarios_inactivos' class='tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Nombres</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Teléfono</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Celular</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Correo</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Entidad</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Rol</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
	            
	            if($id_rol==1){
	                
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                
	            }else{
	                
	                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	                
	            }
	            
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            
	            $i=0;
	            
	            foreach ($resultSet as $res)
	            {
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;"><img src="view/Administracion/DevuelveImagenView.php?id_valor='.$res->id_usuarios.'&id_nombre=id_usuarios&tabla=usuarios&campo=fotografia_usuarios" width="80" height="60"></td>';
	                $html.='<td style="font-size: 11px;">'.$i.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->cedula_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->apellidos_usuarios.' '.$res->nombre_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->telefono_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->celular_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->correo_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_entidades.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_rol.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
	                   
	                if($id_rol==1){
	                    
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=index&id_usuarios='.$res->id_usuarios.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
	                    	                    
	                }else{
	                    
	                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=index&id_usuarios='.$res->id_usuarios.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
	                    
	                }
	                
	                $html.='</tr>';
	            }
	            
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_usuarios("index.php", $page, $total_pages, $adjacents,"load_usuarios_inactivos").'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	public function paginate_usuarios($reload, $page, $tpages, $adjacents,$funcion='') {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>1</a></li>";
	    }
	    // interval
	    if($page>($adjacents+2)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // pages
	    
	    $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	    $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	    for($i=$pmin; $i<=$pmax; $i++) {
	        if($i==$page) {
	            $out.= "<li class='active'><a>$i</a></li>";
	        }else if($i==1) {
	            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	//////////////////////////////////////////////CAMBIO DE CLAVE////////////////////////////////////////////
	
	public function ajax_caducaclave(){
	    
	    
	    if(isset($_POST['clave_usuarios']) && isset($_POST['id_usuarios']) ){
	       
	        if($_POST['id_usuarios']!=""){
	            
	            $claves = null; $claves = new ClavesModel();
	            
	            $id_usuario = $_POST['id_usuarios'];
	            $clave_nueva = $_POST['clave_usuarios'];
	            
	            $rsClaves = $claves->getBy("id_usuarios='$id_usuario' AND clave_n_claves='$clave_nueva'");
	            
	            if(!empty($rsClaves))
	            {
	                echo "clave ya fue utilizada con este usuario !Favor cambiar!";
	            }
	            
	        }
	        
	    }
	}
	
	
    public function ajax_validacedula(){
        
        $usuarios = null; $usuarios= new UsuariosModel();
        
        if(isset($_POST['cedula']) && $_POST['cedula']!=""){
            
            $cedula_usuarios = $_POST['cedula'];
            
            $rsUsuarios = $usuarios->getBy(" cedula_usuarios = '$cedula_usuarios'");
            
            if(!empty($rsUsuarios)){
                echo "Cedula!  ya se encuentra registrada...";
            }
        }
	    
	}
	
	public function AutocompleteCedula(){
	    
	    
	    session_start();
	    $usuarios = new UsuariosModel();
	    
	    $id_rol=$_SESSION["id_rol"];
	    
	    
	    if(isset($_GET['term'])){
	        
	        $cedula_usuarios = $_GET['term'];
	        
	        
	        
	        if($id_rol==1){
	            //administrador
	            $resultSet=$usuarios->getBy("cedula_usuarios LIKE '$cedula_usuarios%'");
	            
	        }else{
	            //todos solo veras los participes
	            $resultSet=$usuarios->getBy("cedula_usuarios LIKE '$cedula_usuarios%' and id_rol=2");
	            
	        }
	        
	        
	        
	        $respuesta = array();
	        
	        if(!empty($resultSet)){
	            
	            if(count($resultSet)>0){
	                
	                foreach ($resultSet as $res){
	                    
	                    $_cls_usuarios = new stdClass;
	                    $_cls_usuarios->id=$res->id_usuarios;
	                    $_cls_usuarios->value=$res->cedula_usuarios;
	                    $_cls_usuarios->label=$res->cedula_usuarios.' | '.$res->usuario_usuarios;
	                    $_cls_usuarios->nombre=$res->nombre_usuarios;
	                    
	                    $respuesta[] = $_cls_usuarios;
	                }
	                
	                echo json_encode($respuesta);
	            }
	            
	        }else{
	            echo '[{"id":0,"value":"sin datos"}]';
	        }
	        
	    }else{
	        
	        $cedula_usuarios = (isset($_POST['term']))?$_POST['term']:'';
	        
	        $columna = "  usuarios.id_usuarios,
            	    usuarios.cedula_usuarios,
            	    usuarios.nombre_usuarios,
            	    usuarios.apellidos_usuarios,
                    usuarios.usuario_usuarios,
                    usuarios.fecha_nacimiento_usuarios,
            	    claves.clave_claves,
            	    claves.clave_n_claves,
                    claves.caduca_claves,
            	    usuarios.telefono_usuarios,
            	    usuarios.celular_usuarios,
            	    usuarios.correo_usuarios,
            	    rol.id_rol,
            	    rol.nombre_rol,
            	    usuarios.fotografia_usuarios,
            	    usuarios.creado,
                    usuarios.id_estado,
                    eu.nombre_estado,
                    e.id_entidades,
                    e.nombre_entidades";
	        
	        $tablas = " public.usuarios INNER JOIN public.claves ON claves.id_usuarios = usuarios.id_usuarios
                    INNER JOIN public.estado ON estado.id_estado = claves.id_estado
                    INNER JOIN public.estado eu ON eu.id_estado = usuarios.id_estado
                    LEFT JOIN public.rol ON rol.id_rol = usuarios.id_rol
                    inner join public.entidades e on usuarios.id_entidades=e.id_entidades";
	        
	        $where = "estado.nombre_estado = 'ACTIVO'
                    AND usuarios.cedula_usuarios = '$cedula_usuarios'";
	        
	        $resultSet=$usuarios->getCondiciones($columna,$tablas,$where,"usuarios.cedula_usuarios");
	        	        
	        $respuesta = new stdClass();
	        
	        if(!empty($resultSet)){
	            
	            $respuesta->id_usuarios = $resultSet[0]->id_usuarios;
	            $respuesta->cedula_usuarios = $resultSet[0]->cedula_usuarios;
	            $respuesta->nombre_usuarios = $resultSet[0]->nombre_usuarios;
	            $respuesta->apellidos_usuarios = $resultSet[0]->apellidos_usuarios;
	            $respuesta->usuario_usuarios = $resultSet[0]->usuario_usuarios;
	            $respuesta->fecha_nacimiento_usuarios = $resultSet[0]->fecha_nacimiento_usuarios;
	            $respuesta->clave_claves = $resultSet[0]->clave_claves;
	            $respuesta->clave_n_claves = $resultSet[0]->clave_n_claves;
	            $respuesta->telefono_usuarios = $resultSet[0]->telefono_usuarios;
	            $respuesta->celular_usuarios = $resultSet[0]->celular_usuarios;
	            $respuesta->correo_usuarios = $resultSet[0]->correo_usuarios;
	            $respuesta->caduca_claves = $resultSet[0]->caduca_claves;
	            $respuesta->id_rol = $resultSet[0]->id_rol;
	            $respuesta->nombre_rol = $resultSet[0]->nombre_rol;
	            $respuesta->id_estado = $resultSet[0]->id_estado;
	            $respuesta->fotografia_usuarios = $resultSet[0]->fotografia_usuarios;	
	            $respuesta->id_entidades = $resultSet[0]->id_entidades;	    
	              
	            
	        }
	    
	        echo json_encode($respuesta);
	       
	    }
	    
	    
	    	    
	}
	
	
	///////////////////////////////////////////////////// DESCARGA DE DOCUMENTOS/////////////////////////////
	
	
	
	public function inicializar(){
		
		session_start();				
		$this->view_Administracion("Documentos",array(
					"resultSet"=>""
		));
	
	}
	
	
	
	public function home(){
	
		session_start();
		$this->view_Administracion("Home",array(
				"resultSet"=>""
		));
	
	}
	
	
	
	
	public function Actualiza()
	{
	    session_start();
	    
	  
	    
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        
	        $usuarios = new UsuariosModel();
	        
	        $resultEdit = "";
	        $resultRol = "";
	        $resEstado="";
	        $_id_usuario = $_SESSION['id_usuarios'];
	        
	        
	        $columnas = "usuarios.id_usuarios,
                        usuarios.cedula_usuarios,
                        usuarios.nombre_usuarios,
                        usuarios.apellidos_usuarios,
                        usuarios.telefono_usuarios,
                        usuarios.celular_usuarios,
                        usuarios.correo_usuarios,
                        claves.clave_n_claves,
                        claves.caduca_claves,
                        usuarios.fotografia_usuarios,
                        usuarios.creado,
                        usuarios.fecha_nacimiento_usuarios,
                        usuarios.usuario_usuarios,
                        eusuarios.nombre_estado,
                        usuarios.id_estado,
                        rol.id_rol,
                        rol.nombre_rol,
                        e.id_entidades,
                        e.nombre_entidades";
	        
	        $tablas = "public.usuarios
                        INNER JOIN public.claves ON usuarios.id_usuarios = claves.id_usuarios
                        LEFT JOIN public.rol ON usuarios.id_rol = rol.id_rol
                        INNER JOIN public.estado ON estado.id_estado = claves.id_estado
                        AND estado.tabla_estado = 'CLAVES' AND estado.nombre_estado='ACTIVO'
                        INNER JOIN public.estado eusuarios ON eusuarios.id_estado = usuarios.id_estado
                        inner join public.entidades e on usuarios.id_entidades=e.id_entidades";
	        
	        $id       = "usuarios.id_usuarios";
	        
	        $where    = " usuarios.id_usuarios = '$_id_usuario' ";
	        $resultEdit = $usuarios->getCondiciones($columnas ,$tablas ,$where, $id); 
	        
	        if(!empty($resultEdit)){
	            
	            
	            $_id_rol	   =$resultEdit[0]->id_rol;
	            $_id_estado	   =$resultEdit[0]->id_estado;
	            $_id_entidades	   =$resultEdit[0]->id_entidades;
	            
	            $rol=new RolesModel();
	            $resultRol = $rol->getCondiciones('*','rol',"id_rol='$_id_rol'",'nombre_rol');
	            
	            $resEstado = array();
	            $resEstado = $usuarios->getCondiciones('*','estado',"tabla_estado='USUARIOS' and id_estado='$_id_estado'",'id_estado');
	            
	            $entidades=new EntidadesModel();
	            $resultEntidad = $entidades->getCondiciones('*','entidades',"id_entidades='$_id_entidades'",'nombre_entidades');
	            
	            
	        }
	        
	        
	        
	            $this->view_Administracion("ActualizarUsuarios",array(
	                "resultEdit" =>$resultEdit, "resultRol"=>$resultRol, "resEstado"=>$resEstado
	                , "resultEntidad"=>$resultEntidad
	                
	            ));
	            
	       
	        
	        
	    }
	    else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        
	    }
	    
	}
	
	
	
	
	
	
}


?>
