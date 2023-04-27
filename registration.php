<?php

require 'includes/headCharsetLang.php';  
require 'includes/headMetaTitleLink.php';
require 'includes/headerLoginRegister.php'; ?>
	
	<div class="container height-no-navbar">
		<div class="row h-85 align-items-center">
			<main>
				<article>		
					<header>
						<div class="col-lg-5 col-md-6 col-sm-7 bg-light-grey p-2 mx-auto">	
							<h2 class="font-color-black fw-bolder font-size-scaled-from-30px position-relative m-0 ">Register<a class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 pe-2 fst-italic" href="index.php">Back to Login</a></h2>
						</div>
					</header>
					<div class="col-lg-5 col-md-6 col-sm-7 bg-light-grey px-2 pb-2 mx-auto">	
						<div class="underline"></div>   
						<form class="lh-1" action="includes/register.php" method="post">		
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
			<footer class="col-12 text-center position-absolute bottom-0 end-0">
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