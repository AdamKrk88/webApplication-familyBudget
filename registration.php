<?php
session_start();
require 'includes/autoloader.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$user = new User();
	$user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
	$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
	$connection = $database->getConnectionToDatabase();
	
	if ($user->validateRegistration() && !$user->checkIfUserExistInDatabase($connection)) {
		$user->password = password_hash($user->password, PASSWORD_DEFAULT);
		$user->insertUserIntoDatabase($connection);
		session_regenerate_id(true);
		$_SESSION['is_redirect_after_registration'] = true;
		Url::redirect('index.php');
	}

	require 'includes/headCharsetLang.php';  
	require 'includes/headMetaTitleLink.php';
	require 'includes/headerLoginRegister.php';

?>

<div class="container web-content">
	<div class="content-wrapper">
		<div class="row initialHeightForContent align-items-center">
			<div class="col-sm-6">
				<div class="row">
					<main>
						<article>		
							<header> 
								<div class="col-lg-10 col-md-10 col-sm-12 bg-light-grey p-2">	
									<h2 class="font-color-black fw-bolder font-size-scaled-from-30px position-relative m-0">Register<a class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 pe-2 fst-italic" href="index.php">Back to Login</a></h2>
								</div>
							</header>
							<div class="col-lg-10 col-md-10 col-sm-12 bg-light-grey px-2 pb-2">	
								<div class="underline"></div>   
								<form class="lh-1" method="post">		
									<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1" for="name">Name</label>
									<input class="form-control form-control-sm fw-bold font-color-grey" type="text" name="name" id="name" title="Please fill out this field" aria-label="Name input for user registration" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />		
									<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1" for="email">Email address</label>
									<input class="form-control form-control-sm fw-bold font-color-grey" type="email" name="email" id="email" title="Please fill out this field" aria-label="Email address input for user registration" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />		
									<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1" for="password">Password</label>
									<input class="form-control form-control-sm fw-bold font-color-grey" type="password" name="password" id="password" title="Please fill out this field" aria-label="Password input for user registration" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />			
									<div class="d-grid mt-5">
										<button class="btn button-grey-color fw-bold font-size-scaled-from-15px" type="submit" aria-label="Register button">Register</button>
									</div>			
								</form>
							</div>
						</article>
					</main>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="row">
					<section>
						<header>
							<div class="col-lg-10 col-md-10 col-sm-12 bg-light-grey p-2">	
								<h2 class="font-color-black fw-bolder font-size-scaled-from-30px m-0">Error</h2>
							</div>
						</header>
						<div class="col-lg-10 col-md-10 col-sm-12 bg-light-grey px-2 pb-2">	
							<div class="underline"></div> 
							<ul class="list-group list-group-flush font-size-scaled-from-15px">
							<?php foreach($user->errors as $error): ?>
								<li class="list-group-item bg-light-grey px-0 py-1"><?=$error; ?></li>
							<?php endforeach; ?>
							</ul>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
<?php 
}
else {
	require 'includes/headCharsetLang.php';
	require 'includes/headMetaTitleLink.php';
	require 'includes/headerLoginRegister.php'; ?> 
<div class="container web-content">
	<main class="content-wrapper">
		<div class="row initialHeightForContent align-items-center">
			<article>		
				<header>
					<div class="col-lg-5 col-md-6 col-sm-7 bg-light-grey p-2 mx-auto">	
						<h2 class="font-color-black fw-bolder font-size-scaled-from-30px position-relative m-0 ">Register<a class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 pe-2 fst-italic" href="index.php">Back to Login</a></h2>
					</div>
				</header>
				<div class="col-lg-5 col-md-6 col-sm-7 bg-light-grey px-2 pb-2 mx-auto">	
					<div class="underline"></div>   
					<form class="lh-1" action="" method="post">		
						<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1" for="name">Name</label>
						<input class="form-control form-control-sm fw-bold font-color-grey" type="text" name="name" id="name" title="Please fill out this field" aria-label="Name input for user registration" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />		
						<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1" for="email">Email address</label>
						<input class="form-control form-control-sm fw-bold font-color-grey" type="email" name="email" id="email" title="Please fill out this field" aria-label="Email address input for user registration" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />		
						<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1" for="password">Password</label>
						<input class="form-control form-control-sm fw-bold font-color-grey" type="password" name="password" id="password" title="Please fill out this field" aria-label="Password input for user registration" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />			
						<div class="d-grid mt-5">
							<button class="btn button-grey-color fw-bold font-size-scaled-from-15px" type="submit" aria-label="Register button">Register</button>
						</div>			
					</form>
				</div>	
			</article>
		</div>
	</main>
<?php
}?>
	<div class="row">	
		<footer class="col-12 text-center footer-budget">
			<a class="footer-link font-color-black" href="https://www.flaticon.com/free-icons/money" title="money icons" target="_blank">Money icons created by Freepik - Flaticon</a>.  
			<a class="footer-link font-color-black d-block d-sm-inline-block" href="https://pl.freepik.com/search?format=search&query=marmur&type=photo" target="_blank">Marmur image created by rawpixel.com - pl.freepik.com</a>
			<span class="font-color-black d-block">All rights reserved &copy; 2023. Thank you for your visit </span>    
		</footer>
	</div>
</div>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script> 

</body>
</html>