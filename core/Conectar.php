<?php
class Conectar{
    private $driver;
   
    public function __construct() {
        $this->driver="pgsql";
    }
    public function conexion(){
       
        if($this->driver=="pgsql" || $this->driver==null){
            
             $con = pg_connect("host=localhost port=5432 dbname=supermercado user=postgres password=@Maycol2021");
            
        	if(!$con){
        		echo "No se puedo Conectar a la Base";
        		exit();
        	} else {
        		
        	}
       
        }
        
        return $con;
	
    }
    
   
}
?>
