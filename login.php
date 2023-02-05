<?php
session_start();
require 'includes/autoloader.php';

if (isset($_SESSION['userLogged'])) {
	if($_SESSION['userLogged']) {
		Authorization::destroySessionCompletely();
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$user = new User();
	$nameOrEmail = $_POST['nameOrEmail'];
    $user->password = $_POST['password'];
	$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	$connection = $database->getConnectionToDatabase();
	$_SESSION['userLogged'] = false;
	
	if ($user->validateLogin($nameOrEmail)) {
		$nameOrEmail = $user->test_input($nameOrEmail);
		if ($user->identifyUserInDatabase($connection, $nameOrEmail)) {
			session_regenerate_id(true);
			$_SESSION['userLogged'] = true;
			Url::redirect('index.php');
		};
	}
}

require 'includes/head.php'; 
require 'includes/headerLoginRegister.php'; ?>

	<div class="container">
		<div class="row">
			<main>
				<article>		
					<header>     
						<div class="col-lg-8 col-md-10 offset-lg-2 offset-md-1 mt-4-dot-5">
							<h1 class="fw-bolder font-color-black font-size-scaled-from-45px text-center">Welcome to Budget Manager</h1>
							<?php if (!isset($_SESSION['is_redirect_after_registration']) && !isset($_SESSION['userLogged'])): ?>
							<p class="text-center font-color-grey">Please fill the form below to log in. If you don't have account in our service, you can create it by clicking below and follow instruction on registration side</p> 
							<?php elseif (isset($_SESSION['is_redirect_after_registration'])): ?>
							<p class="text-center font-orange">Your registration is successful. You can log in</p> 	
							<?php elseif (isset($_SESSION['userLogged']) && empty($user->errors)): ?>	
							<p class="text-center font-orange">Login data incorrect. Please try again</p>
							<?php elseif (isset($_SESSION['userLogged']) && !empty($user->errors)):
							foreach($user->errors as $error): ?><p class="text-center font-orange mb-0"><?php echo $error;?></p><?php endforeach; ?><div class="mb-3"></div><?php endif; ?>
						</div>
					</header> 
					<div class="col-lg-5 col-md-6 col-sm-7 bg-light-grey mx-auto p-2 mt-2">
						<h2 class="font-color-black fw-bolder font-size-scaled-from-30px mb-1">Log in</h2>
						<?php if (!isset($_SESSION['is_redirect_after_registration'])): ?>
						<p class="font-color-black"><span class="font-light-orange">Do not have an account?</span> Create one <a class="font-color-black link-registration" href="registration.php">here </a></p>
						<?php endif; ?>
						<form class="lh-1" method="post">
							<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1" for="nameOrEmail">Name or email address</label>
							<input class="form-control form-control-sm fw-bold font-color-grey" type="text" name="nameOrEmail" id="nameOrEmail" title="Please fill out this field" aria-label="Name or email address input for login" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
							<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1" for="password">Password</label>
							<input class="form-control form-control-sm fw-bold font-color-grey" type="password" name="password" id="password" title="Please fill out this field" aria-label="Password input for login" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
							<div class="d-grid mt-5">
								<button class="btn button-grey-color fw-bold font-size-scaled-from-15px" type="submit" aria-label="Log in">Log in</button>
							</div>
						</form>
					</div>
				</article>
			</main>
			<footer class="col-12 text-center position-absolute bottom-0 end-0">
				<a class="footer-link font-color-black" href="https://www.flaticon.com/free-icons/money" title="money icons" target="_blank">Money icons created by Freepik - Flaticon</a>.  
				<a class="footer-link font-color-black d-block d-sm-inline-block" href="https://pl.freepik.com/search?format=search&query=marmur&type=photo" target="_blank">Marmur image created by rawpixel.com - pl.freepik.com</a>
				<span class="font-color-black d-block">All rights reserved &copy; 2022. Thank you for your visit </span>    
			</footer>
		</div>
	</div>	
	
	<?php
	if (isset($_SESSION['is_redirect_after_registration']) || isset($_SESSION['userLogged'])) {
	Authorization::destroySessionCompletely();
	}
	?>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>   

</body>
</html>