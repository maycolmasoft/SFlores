<?php
class ControladorBase{

    public function __construct() {
        require_once 'EntidadBase.php';
        require_once 'ModeloBase.php';
        
        //Incluir todos los modelos
        foreach(glob("model/*.php") as $file){
            require_once $file;
        }
    }
    
    //Plugins y funcionalidades
    
    public function view($vista,$datos){
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor; 
        }
        
        require_once 'core/AyudaVistas.php';
        $helper=new AyudaVistas();
    
        require_once 'view/'.$vista.'View.php';
    }
	
     public function view_ServiciosOnline($vista,$datos){
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor;
        }
        
        require_once 'core/AyudaVistas.php';
        $helper=new AyudaVistas();
        
        require_once 'view/ServiciosOnline/'.$vista.'View.php';
    }
	
	
	
	
    public function view_Administracion($vista,$datos){
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor;
        }
        
        require_once 'core/AyudaVistas.php';
        $helper=new AyudaVistas();
        
        require_once 'view/Administracion/'.$vista.'View.php';
    }
    
    
  
    
    public function report($vista,$datos){
    	foreach ($datos as $id_assoc => $valor) {
    		${$id_assoc}=$valor;
    	}
    
    	require_once 'core/AyudaVistas.php';
    	$helper=new AyudaVistas();
    
    	require_once 'view/reportes/'.$vista.'Report.php';
    }

    
    public function redirect($controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO){
        header("Location:index.php?controller=".$controlador."&action=".$accion);
    }
    
    public function verReporte($vista,$datos){
    	foreach ($datos as $id_assoc => $valor) {
    		${$id_assoc}=$valor;
    	}
    
    	require_once 'view/reportes/'.$vista.'Rpt.php';
    }
    
    
}
?>
