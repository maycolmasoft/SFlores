<?php
$id_valor = 0;
$id_nombre = 0;
$tabla = "";
$campo = "";

if (isset ($_GET["tabla"]))
{
	$tabla = $_GET["tabla"];

}
if (isset ($_GET["campo"]))
{
	$campo = $_GET["campo"];

}
if (isset ($_GET["id_nombre"]))
{
	$id_nombre = $_GET["id_nombre"];

}
if (isset ($_GET["id_valor"]))
{
	$id_valor = $_GET["id_valor"];

}
$image = "";
$conn =  pg_connect("host=localhost port=5432 dbname=supermercado user=postgres password=@Maycol2021");
   
if(!$conn)
{
	echo  "No se pudo conectar";
	
}
else 
{

		
		$res = pg_query($conn, "SELECT ".$campo." FROM ".$tabla." WHERE ".$id_nombre." = '$id_valor' ");
		
	
		if (!empty($res))
		{
				$raw = pg_fetch_result($res, $campo );
				
				if($raw)
				{
					header('Content-type: image/png');
					echo pg_unescape_bytea($raw);
				}else 
				{
					$archivo=$_SERVER['DOCUMENT_ROOT'].'/SFlores/view/images/'.'nodisponible.jpg';
				
					header("Content-type: image/jpeg");
					header("Content-length: ".filesize($archivo));
					header("Content-Disposition: inline; filename=$archivo");
					readfile($archivo);
				}
				
			
		}else 
		{
			$archivo=$_SERVER['DOCUMENT_ROOT'].'/SFlores/view/images/'.'nodisponible.jpg';
			
			header("Content-type: image/jpeg");
			header("Content-length: ".filesize($archivo));
			header("Content-Disposition: inline; filename=$archivo");
			readfile($archivo);
		}
	
	pg_close($conn);
	
}



?>

