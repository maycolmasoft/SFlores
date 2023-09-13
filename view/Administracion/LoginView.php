<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="view/bootstrap/otros/login/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="view/bootstrap/otros/login/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="view/bootstrap/otros/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="view/bootstrap/otros/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="view/bootstrap/otros/login/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="view/bootstrap/otros/login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="view/bootstrap/otros/login/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="view/bootstrap/otros/login/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="view/bootstrap/otros/login/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="view/bootstrap/otros/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="view/bootstrap/otros/login/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(view/bootstrap/otros/login/images/bg-01.jpg);" >
				</div>

				<form class="login100-form validate-form" action="<?php echo $helper->url("Usuarios","Loguear"); ?>" method="post" >
					
					<div class="wrap-input100 validate-input m-b-26" data-validate="Ingrese Cedula">
						<span class="label-input100"><b>Usuario:</b></span>
						<input class="input100" type="text" name="usuario" id="usuario" placeholder="cedula..">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Ingrese Password">
						<span class="label-input100"><b>Clave:</b></span>
						<input class="input100" type="password" name="clave" id="clave" placeholder="clave..">
						<span class="focus-input100"></span>
					</div>

					<div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Recuerdame
							</label>
						</div>

						<div>
						<a class="txt1" href="<?php echo $helper->url("Usuarios","resetear_clave_inicio"); ?>">Olvidó su Contraseña?</a>
							
						</div>
					</div>

					<div class="container-login100-form-btn">
						<input  type="submit" id="Guardar" class="login100-form-btn" value="Ingresar">
						
					</div>
					 
					
				</form>
				
				<?php if (isset($resultSet)) {?>
							<?php if ($resultSet != "") {?>
						    <?php if ($error == TRUE) {?>
								    
								    <div class="col-lg-12 col-md-12 col-xs-12">
								 	<div class="alert alert-danger" role="alert" style="text-align:center;"><?php echo $resultSet; ?></div>
								 	</div>
								 	
								 <?php } else {?>
								   	
								    <div class="col-lg-12 col-md-12 col-xs-12">	
								    <div class="alert alert-success" role="alert" style="text-align:center;"><?php echo $resultSet; ?></div>
								    </div>
								   
								    
								  
								    
								 <?php sleep(5); ?>
				     
				     			 <?php }?>
							
					        <?php } ?>
					        <?php } ?> 
			</div>
			
		</div>
	</div>
	                       
	
<!--===============================================================================================-->
	<script src="view/bootstrap/otros/login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="view/bootstrap/otros/login/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="view/bootstrap/otros/login/vendor/bootstrap/js/popper.js"></script>
	<script src="view/bootstrap/otros/login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="view/bootstrap/otros/login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="view/bootstrap/otros/login/vendor/daterangepicker/moment.min.js"></script>
	<script src="view/bootstrap/otros/login/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="view/bootstrap/otros/login/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="view/bootstrap/otros/login/js/main.js"></script>

</body>
</html>