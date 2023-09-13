<?php
class EntidadBase{
    private $table;
    private $db;
    private $conectar;
    
    
    public function __construct($table) {
        $this->table=(string) $table;
        
        require_once 'Conectar.php';
        $this->conectar=new Conectar();
        $this->db=$this->conectar->conexion();

        $this->con=$this->getConetar()->conexion();
     }
    
     
    public function fluent(){
    	return $this->fluent;
    }
    
    public function con(){
    	return $this->con;
    }
    
    
    public function getCondiciones_Grupo_Having($columnas ,$tablas , $where, $grupo, $having){
        
        $query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where GROUP BY $grupo HAVING $having");
        $resultSet = array();
        while ($row = pg_fetch_object($query)) {
            $resultSet[]=$row;
        }
        
        return $resultSet;
    }
    
    public function getCondiciones_Grupo_Having_Limit($columnas ,$tablas , $where, $grupo, $having, $limit){
        
        $query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where GROUP BY $grupo HAVING $having $limit");
        $resultSet = array();
        while ($row = pg_fetch_object($query)) {
            $resultSet[]=$row;
        }
        
        return $resultSet;
    }
    
    public function getConetar(){
        return $this->conectar;
    }
    
    public function db(){
        return $this->db;
    }
    
    /***
     * dc 2019-05-17
     * desc: para empezar una trasaccion en pg
     * @return resource
     */
    public function beginTran(){
        
        $pg_query = pg_query($this->con,"BEGIN");        
        
        return $pg_query;
       
    }
    
    /***
     * dc 2019-05-17
     * desc: para finalizar una trasaccion en pg
     * @return resource
     */
    public function endTran($trans="ROLLBACK"){
        
        @$pg_query = pg_query($this->con,$trans);
        
        pg_close(); 
        
        return $pg_query;
        
    }
    
    public function getNuevo($secuencia){
    
    	$query=pg_query($this->con, "SELECT NEXTVAL('$secuencia')");
    	 
    	$resultSet = array();
    	 
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    	return $resultSet;
    }
    
    public function getAll($id){
        
    	$query=pg_query($this->con, "SELECT * FROM $this->table ORDER BY $id ASC");
    	$resultSet = array();
    	
           while ($row = pg_fetch_object($query)) {
             $resultSet[]=$row;
           }
        return $resultSet;
    }
    
    function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
            
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
                
                return $_SERVER['REMOTE_ADDR'];
    }
    
    
    
    public function getContador($contador){
    
    	$query=pg_query($this->con, "SELECT $contador FROM $this->table ");
    	$resultSet = array();
    	 
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    	return $resultSet;
    }
    
    public function getCantidad($columna,$tabla,$where){
    
    	//parametro $columna puede ser todo (*) o una columna especifica
    	$query=pg_query($this->con, "SELECT COUNT($columna) AS total FROM $tabla WHERE $where ");
    	$resultSet = array();
    
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    	return $resultSet;
    }    
    
    
    public function getById($id){
    	
    	$query=pg_query($this->con, "SELECT * FROM $this->table WHERE id=$id");
        $resultSet = array();
    	
           while ($row = pg_fetch_object($query)) {
             $resultSet[]=$row;
           }
        return $resultSet;
    }
    
    public function getBy($where){
    	
    	$query=pg_query($this->con, "SELECT * FROM $this->table WHERE   $where ");
        $resultSet = array();
    	
           while ($row = pg_fetch_object($query)) {
             $resultSet[]=$row;
           }
        return $resultSet;
    }
    
    
    public function deleteById($id){
    	
        $query=pg_query($this->con,"DELETE FROM $this->table WHERE $id"); 
        return $query;
    }
    
    public function deleteByWhere($where){
        
        try
        {
            $query=pg_query($this->con,"DELETE FROM $this->table WHERE $where ");
        }
        catch (Exception $Ex)
        {
            
            
        }
        
        return $query;
    }
    
    public function deleteBy($column,$value){

    	try 
    	{
    		$query=pg_query($this->con,"DELETE FROM $this->table WHERE $column='$value' ");
    	}
    	catch (Exception $Ex)
    	{
    		
    		
    	} 
    	
        return $query;
    }
    
    public function eliminarBy($column,$value){
        
        $cantidadAfectada = null;
        
        try{
            
            $query=pg_query($this->con,"DELETE FROM $this->table WHERE $column='$value' ");
            
            if( $query === false )
                throw new Exception( "Error PostgreSQL ".pg_last_error() );
            
            $cantidadAfectada = pg_affected_rows($query);
            
        }catch (Exception $Ex){
            
            $cantidadAfectada=null;
        }
        
       
        return $cantidadAfectada;
    }
    
    
    
    public function eliminarFila($tabla,$where){
        
        $cantidadAfectada = null;
        
        try{
            
            $query=pg_query($this->con,"DELETE FROM $tabla WHERE $where ");
            
            if( $query === false )
                throw new Exception( "Error PostgreSQL ".pg_last_error() );
                
                $cantidadAfectada = pg_affected_rows($query);
                
        }catch (Exception $Ex){
            
            $cantidadAfectada=null;
        }
        
        
        return $cantidadAfectada;
    }
    
    public function eliminarByColumn($table,$column,$value){
        
        $cantidadAfectada = null;
        
        $query=pg_query($this->con,"DELETE FROM $table WHERE $column='$value' ");
        
        if($query){
            
            $cantidadAfectada = pg_affected_rows($query);
            
        }
        
        return $cantidadAfectada;
    }
    

    public function getCondiciones($columnas ,$tablas , $where, $id){
    	
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  ASC");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    public function getCondicionesFunciones($columnas ,$tablas){
        
        $query=pg_query($this->con, "SELECT $columnas FROM $tablas");
        $resultSet = array();
        while ($row = pg_fetch_object($query)) {
            $resultSet[]=$row;
        }
        
        return $resultSet;
    }
    
    public function getCondicionesValorMayor($columnas ,$tablas , $where){
    	 
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    
    public function getCondicionesmenosid($columnas ,$tablas , $where){
        
        $query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where");
        $resultSet = array();
        while ($row = pg_fetch_object($query)) {
            $resultSet[]=$row;
        }
        
        return $resultSet;
    }
    
    
    
    public function getCondicionesDesc($columnas ,$tablas , $where, $id){
    	 
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  DESC");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    
    
 
   
    
    public function getCondicionesDescLimit($columnas ,$tablas , $where, $id, $limit){
        
        $query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  DESC LIMIT $limit");
        $resultSet = array();
        while ($row = pg_fetch_object($query)) {
            $resultSet[]=$row;
        }
        
        return $resultSet;
    }
    
    
    
    
    public function getCondicionesSinOrden($columnas ,$tablas , $where, $limit){
        
        $query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where $limit");
        $resultSet = array();
        while ($row = pg_fetch_object($query)) {
            $resultSet[]=$row;
        }
        
        return $resultSet;
    }
    
    
    public function getCondiciones_grupo($columnas ,$tablas , $where, $grupo, $id){
    	 
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where GROUP BY $grupo ORDER BY $id  ASC");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
  
    
    public function getCondicionesGrupCondiOrderPag($columnas ,$tablas , $where, $grupo, $condicion, $id, $limit){
        
        $query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where GROUP BY $grupo HAVING $condicion ORDER BY $id  ASC  $limit");
        $resultSet = array();
        while ($row = pg_fetch_object($query)) {
            $resultSet[]=$row;
        }
        
        return $resultSet;
    }
    
    public function getCondicionesPag($columnas ,$tablas , $where, $id, $limit){
    	 
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  ASC  $limit");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    
      
    
    public function getCondicionesPagDesc($columnas ,$tablas , $where, $id, $limit){
    
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  DESC  $limit");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    
    public function UpdateBy($colval ,$tabla , $where){
    	try 
    	{ 
    	     $query=pg_query($this->con, "UPDATE $tabla SET  $colval   WHERE $where ");
    	     
    	}
    	catch (Exception  $Ex)
    	{
    		
    		
    	}
    }
    
    public function ActualizarBy($colval ,$tabla , $where){
        try{
            
            $query=pg_query($this->con, "UPDATE $tabla SET  $colval   WHERE $where ");
            
            if( $query === false )
                throw new Exception("valor nulor");
            
            return pg_affected_rows($query);
            
        }catch (Exception  $Ex){
            return -1;
            
        }
    }
    
    public function editBy($colval ,$tabla , $where){
        
        $cantidadAfectada = null;
        
        $query=pg_query($this->con,"UPDATE $tabla SET  $colval   WHERE $where ");
        
        if(!$query){
            
            $cantidadAfectada = pg_last_error();
            
        }else{
            
            $cantidadAfectada = pg_affected_rows($query);
        }
        
        return $cantidadAfectada;
        
    }
    
    
    
    public function getByPDF($columnas, $tabla , $where){
    
    	if ($tabla == "")
    	{
    		$query=pg_query($this->con, "SELECT $columnas FROM $this->table WHERE   $where ");
    	}
    	else
    	{
    		$query=pg_query($this->con, "SELECT $columnas FROM $tabla WHERE   $where ");
    	}
    	
    	return $query;
    }
    
    public function getCondicionesPDF($columnas ,$tablas , $where, $id){
    	 
    	$query=pg_query($this->con, "SELECT $columnas FROM $tablas WHERE $where ORDER BY $id  ASC");
    
    	return $query;
    }
    
    public function getSumaColumna($columna,$tabla,$where){
        
        //parametro $columna puede ser todo (*) o una columna especifica
        $query=pg_query($this->con, "SELECT SUM($columna) AS suma FROM $tabla WHERE $where ");
        $resultSet = array();
        
        while ($row = pg_fetch_object($query)) {
            $resultSet[]=$row;
        }
        return $resultSet;
    }   
    
    
    
    /*
     * Aqui podemos montarnos un monton de métodos que nos ayuden
     * a hacer operaciones con la base de datos de la entidad
     */
    
    public function encriptar1($cadena){
    	$key='rominajasonrosabal';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
    	$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
    	return $encrypted; //Devuelve el string encriptado
    
    }
	
	public function urlDecodicada($string) {
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, urlencode($string));
    }
    
    public function desencriptar1($cadena){
    	$key='rominajasonrosabal';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
    	$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cadena), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    	return $decrypted;  //Devuelve el string desencriptado
    }
    
	
	 public function encriptar($valor){
    	
		$clave  = 'rominajasonrosabal';

		//Metodo de encriptación
		$method = 'aes-256-cbc';

		// Puedes generar una diferente usando la funcion $getIV()
		$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");


		 /*
		 Encripta el contenido de la variable, enviada como parametro.
		  */
		 $encriptar = openssl_encrypt ($valor, $method, $clave, false, $iv);
		

		return $encriptar; //Devuelve el string encriptado
    
    }
	
	
	  public function desencriptar($valor){


		$clave  = 'rominajasonrosabal';

		//Metodo de encriptación
		$method = 'aes-256-cbc';

		// Puedes generar una diferente usando la funcion $getIV()
		$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

		
			 $encrypted_data = base64_decode($valor);
			  $desencriptar =  openssl_decrypt($valor, $method, $clave, false, $iv);
		

    	return $desencriptar;  //Devuelve el string desencriptado
    }
	
	
    public function registrarSesion($id_usuarios, $usuario_usuarios, $id_rol, $nombre_usuarios, $apellido_usuarios, $correo_usuarios, $ip_usuarios, $cedula_usuarios, $id_entidades)
    {
    	session_start();
    	$_SESSION["cedula_usuarios"]=$cedula_usuarios;
    	$_SESSION["id_usuarios"]=$id_usuarios;
    	$_SESSION["usuario_usuarios"]=$usuario_usuarios;
    	$_SESSION["id_rol"]=$id_rol;
    	$_SESSION["nombre_usuarios"]=$nombre_usuarios;
    	$_SESSION["apellidos_usuarios"]=$apellido_usuarios;
    	$_SESSION["correo_usuarios"]=$correo_usuarios;
    	$_SESSION["ip_usuarios"]=$ip_usuarios; 	
    	$_SESSION["id_entidades"]=$id_entidades;
    	
    	if (substr($ip_usuarios, 0, 3) == "192" )
    	{
    		$_SESSION["tipo_usuario"]="usuario_local";
    	}
    	else   ///usuarios externo 
    	{
    		
    		$_SESSION["tipo_usuario"]="usuario_externo";
    	}
    		
    	
    }
    
    
    
    public function registrarSesionParticipe($cedula_participe)
    {
    	
    	$_SESSION["cedula_participe"]=$cedula_participe;
    	
    	 
    }
    
    
    public function getPermisosVer($where){
    	 
    	$query=pg_query($this->con, "SELECT permisos_rol.ver_permisos_rol FROM public.controladores, public.permisos_rol WHERE  controladores.id_controladores = permisos_rol.id_controladores AND  ver_permisos_rol = 'TRUE'   AND   $where ");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }

    
    public function getPermisosEditar($where){
    
    	$query=pg_query($this->con, "SELECT permisos_rol.editar_permisos_rol FROM public.controladores, public.permisos_rol WHERE  controladores.id_controladores = permisos_rol.id_controladores AND  editar_permisos_rol = 'TRUE'   AND   $where ");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    

    public function getPermisosBorrar($where){
    
    	$query=pg_query($this->con, "SELECT permisos_rol.borrar_permisos_rol FROM public.controladores, public.permisos_rol WHERE  controladores.id_controladores = permisos_rol.id_controladores AND  borrar_permisos_rol = 'TRUE'   AND   $where ");
    	$resultSet = array();
    	while ($row = pg_fetch_object($query)) {
    		$resultSet[]=$row;
    	}
    
    	return $resultSet;
    }
    
    
    
    
    public function  EnviarMailSolCred($correo_participe, $id_usuario, $_nombres_solicitante_datos_personales, $_apellidos_solicitante_datos_personales){
    	
    	$usuarios = new UsuariosModel();
    	$where = "id_usuarios = '$id_usuario'";
    	$resultUsu = $usuarios->getBy($where);
    	
    	if(!empty($resultUsu))
    	{
    	
    		foreach ($resultUsu as $res){
    	
    			$correo_usuario   =$res->correo_usuarios;
    				$nombre_usuario   = $res->nombre_usuarios.' '.$res->apellidos_usuarios;
    		
    		}
    	
    		$cabeceras = "MIME-Version: 1.0 \r\n";
    		$cabeceras .= "Content-type: text/html; charset=utf-8 \r\n";
    		$cabeceras .= "From: $correo_usuario \r\n";
    		$destino="$correo_participe";
    		$asunto="Solicitud de Prestamo (FCPC-GADPP)";
    		$fecha=date("d/m/y");
    		$hora=date("H:i:s");
    		
    		
    		$resumen="
    		<table rules='all'>
    		<tr><td WIDTH='1000' HEIGHT='50'><center><img src='http://34.239.169.3/erp_riesgos/view/images/logo.png' WIDTH='300' HEIGHT='120'/></center></td></tr>
    		</tabla>
    		<p><table rules='all'></p>
    		<tr style='background: #FFFFFF;'><td  WIDTH='1000' align='center'><b>Estimado Participe $_apellidos_solicitante_datos_personales $_nombres_solicitante_datos_personales</b></td></tr></p>
    		<tr style='background: #FFFFFF;'><td  WIDTH='1000' align='justify'>Envieme la siguiente información para agilizar el proceso de su solicitud de prestamo.</td></tr>
    		</tabla>
    		
    		<p><table rules='all'></p>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>1.-</b> 3 últimos roles de pago firmados por su entidad pagadora.</td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>2.-</b> Certificado de tiempo de servicio.</td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>3.-</b> Copia de cédula y papeleta de votación (7 febrero 2021).</td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>4.-</b> Copia planilla de servicio básico (Actualizada).</td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>5.-</b> Copia de libreta de ahorros.</td></tr>
    		</tabla>
    		
    		
    		<p><table rules='all'></p>
    		<tr style='background: #FFFFFF'><td WIDTH='1000' align='center'><b> TU OFICIAL DE CRÉDITO ASIGNADO ES: </b></td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>Nombre:</b> $nombre_usuario</td></tr>
    		<tr style='background: #FFFFFF;'><td WIDTH='1000' > <b>Correo:</b> $correo_usuario </td></tr>
    		</tabla>
    		<p><table rules='all'></p>
    		<tr style='background:#1C1C1C'><td WIDTH='1000' HEIGHT='50' align='center'><font color='white'>FCPC-GADPP - <a href='https://www.fondogadpp.org/'><FONT COLOR='#7acb5a'>www.fondogadpp.org</FONT></a> - Copyright © 2021-</font></td></tr>
    		</table>
    		";
    		
    		mail("$destino","Solicitud de Prestamo (FCPC-GADPP)","$resumen","$cabeceras");
    		
    			
    	}
    	
    	
    }
    
    
    
    
    
    
    
    public function MenuDinamico($_id_rol)
    {
    	$resultPermisos=array();
    	$perimisos_rol = new UsuariosModel();
    	 
    	$columnas="controladores.nombre_controladores,
				  permisos_rol.id_rol,
				  permisos_rol.ver_permisos_rol";
    	 
    	$tablas="public.permisos_rol,
  				 public.controladores";
    	 
    	$where="controladores.id_controladores = permisos_rol.id_controladores
    	AND permisos_rol.ver_permisos_rol=TRUE AND permisos_rol.id_rol='$_id_rol'";
    	 
    	$id="controladores.id_controladores";
    	 
    	$resultPermisos = $perimisos_rol->getCondiciones($columnas, $tablas, $where, $id);
    	 
    	$_SESSION['controladores']=$resultPermisos;
    }
    
   
    
    
    public function numtoletras($xcifra)
    {
        $xarray = array(0 => "Cero",
            1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
            "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
            "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
            100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
        );
        
        $xcifra = trim($xcifra);
        $xlength = strlen($xcifra);
        $xpos_punto = strpos($xcifra, ".");
        $xaux_int = $xcifra;
        $xdecimales = "00";
        if (!($xpos_punto === false)) {
            if ($xpos_punto == 0) {
                $xcifra = "0" . $xcifra;
                $xpos_punto = strpos($xcifra, ".");
            }
            $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
            $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
        }
        
        $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
        $xcadena = "";
        for ($xz = 0; $xz < 3; $xz++) {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = 0;
            $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
            $xexit = true; // bandera para controlar el ciclo del While
            while ($xexit) {
                if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                    break; // termina el ciclo
                }
                
                $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
                $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
                for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                    switch ($xy) {
                        case 1: // checa las centenas
                            if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                                
                            } else {
                                $key = (int) substr($xaux, 0, 3);
                                if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                    $xseek = $xarray[$key];
                                    $xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                    if (substr($xaux, 0, 3) == 100)
                                        $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                        else
                                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                            $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                                }
                                else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                    $key = (int) substr($xaux, 0, 1) * 100;
                                    $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 0, 3) < 100)
                            break;
                        case 2: // checa las decenas (con la misma lógica que las centenas)
                            if (substr($xaux, 1, 2) < 10) {
                                
                            } else {
                                $key = (int) substr($xaux, 1, 2);
                                if (TRUE === array_key_exists($key, $xarray)) {
                                    $xseek = $xarray[$key];
                                    $xsub = $this->subfijo($xaux);
                                    if (substr($xaux, 1, 2) == 20)
                                        $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                        else
                                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                            $xy = 3;
                                }
                                else {
                                    $key = (int) substr($xaux, 1, 1) * 10;
                                    $xseek = $xarray[$key];
                                    if (20 == substr($xaux, 1, 1) * 10)
                                        $xcadena = " " . $xcadena . " " . $xseek;
                                        else
                                            $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 1, 2) < 10)
                            break;
                        case 3: // checa las unidades
                            if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                                
                            } else {
                                $key = (int) substr($xaux, 2, 1);
                                $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                                $xsub = $this->subfijo($xaux);
                                $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                            } // ENDIF (substr($xaux, 2, 1) < 1)
                            break;
                    } // END SWITCH
                } // END FOR
                $xi = $xi + 3;
            } // ENDDO
            
            if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
                $xcadena.= " DE";
                
                if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
                    $xcadena.= " DE";
                    
                    // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
                    if (trim($xaux) != "") {
                        switch ($xz) {
                            case 0:
                                if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                                    $xcadena.= "UN BILLON ";
                                    else
                                        $xcadena.= " BILLONES ";
                                        break;
                            case 1:
                                if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                                    $xcadena.= "UN MILLON ";
                                    else
                                        $xcadena.= " MILLONES ";
                                        break;
                            case 2:
                                if ($xcifra < 1) {
                                    $xcadena = "CERO DOLARES $xdecimales/100";
                                }
                                if ($xcifra >= 1 && $xcifra < 2) {
                                    $xcadena = "UN DOLAR $xdecimales/100";
                                }
                                if ($xcifra >= 2) {
                                    $xcadena.= " DOLARES $xdecimales/100"; //
                                }
                                break;
                        } // endswitch ($xz)
                    } // ENDIF (trim($xaux) != "")
                    // ------------------      en este caso, para México se usa esta leyenda     ----------------
                    $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
                    $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
                    $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
                    $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
                    $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
                    $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
                    $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
        } // ENDFOR ($xz)
        return trim($xcadena);
    }
    
    // END FUNCTION
    
    public function subfijo($xx)
    { 
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
            if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
                $xsub = "MIL";
                return $xsub;
    }
    
    
    
    
     

    
    
    public function devuelve_seguro_desgravamen($saldo_capital){
    	
    	$seguro=0;
    	$seguro = ((0.16/1000)*$saldo_capital)*1.04;
    	
    	return round($seguro,2);
    
    }
    
    public function devuelve_seguro_icendio($avaluo_bien, $lastday){
    	 
    	$seguro=0;
    	$seguro = ((($avaluo_bien * 0.0015)/365) * $lastday) * 1.04;
    	return $seguro;
    
    }
    
    
    public function ultimo_dia_mes_actual() {
    	$month = date('m');
    	$year = date('Y');
    	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
    
    	return date('d-m-Y', mktime(0,0,0, $month, $day, $year));
    }
    
    public function ultimo_dia_mes_fecha($fecha) {
    	$month = $fecha('m');
    	$year = $fecha('Y');
    	$day = $fecha("d", mktime(0,0,0, $month+1, 0, $year));
    
    	return date('d-m-Y', mktime(0,0,0, $month, $day, $year));
    }
    /** Actual month first day **/
    public function primer_dia_mes_actual() {
    	$month = date('m');
    	$year = date('Y');
    	return date('d-m-Y', mktime(0,0,0, $month, 1, $year));
    }
    
    public function devuelve_interes_ord_x_capital($_dias, $_capital, $_tasa) {
	 	$interes = 0;
	 	
	 	$_tasa_interes = $_tasa / 100;   //0.09
    	$interes_mensual = $_tasa_interes / 12;  //0.0075
	    $interes_diario=$interes_mensual /30;    //0.00025
	    $interes_concesion=$interes_diario* $_dias *$_capital;
	    $interes_concesion=round($interes_concesion,2);
	  
	 //   echo "Interes mensual: " . $interes_mensual;
	 //   echo "Interes diario: " . $interes_diario;
	    
	    return $interes=$interes_concesion;
    }
    
    /***
     * @desc paginacion modificada para mostrar numero de brecha exacto 
     * @author dc 2020/03/31
     */
    function allpaginate($reload, $page, $tpages, $adjacents, $funcion = "") {
        
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
        
        //$out .= "<li><a>"."pagina es ".$page." y su brecha es "." $adjacents "."</a></li>";
        
        // pages
        
        $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
        $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
        
        /* dc 2020/03/31*/
        if( ( $pmax - $pmin) > $adjacents ){
            $auxmin = ( $page-ceil(($adjacents/2)-1) );
            $pmin = ( $auxmin  < 1 ) ? 1: $auxmin ;
            $auxmax = ($page+ceil(($adjacents/2)-1) );
            $pmax = ( $auxmax > $tpages ) ? $tpages : $auxmax ;
            
        }
        
        //$out .= "<li><a>"."minimo es ".$pmin." y su maximo es "." $pmax "."</a></li>";
        
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
        }else if( $pmax < ( $tpages -1 )   ){ /* dc 2020/03/31*/
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
        }else if( $pmax < ( $tpages )   ){ /* dc 2020/03/31*/
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
    
  

}
?>
