<?php
require 'includes/autoloader.php';
session_start();
Authorization::checkAuthorization();

require 'includes/head.php'; 
?>

<body>	
	<header>	
		<nav class="navbar navbar-expand-lg navbar-light-yellow">
			<a class="navbar-brand" href="#"><img class="me-1 ms-1 d-inline-block align-middle" src="images/gold-ingots.png" alt="Gold bar" /><span class="text-uppercase font-weight-bold align-middle"> Budget Manager</span></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Button to open main menu options">
				<span class="navbar-toggler-icon">
					<i class="icon-menu"></i>
				</span>
			</button>
			<div class="collapse navbar-collapse" id="mainmenu">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item text-center"><a class="nav-link" href="addincome.html">Add income</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="addexpense.html">Add expense</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="balancereview.html">Review balance</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="#">Settings</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="login.html">Log out</a></li>
				</ul>
			</div>
		</nav>		
 	</header>

	
	<div class="container position-relative height-no-navbar">
		<main class="h-85">
			<article class="h-100 d-flex align-items-center justify-content-center">
				<div class="text-center p-1 w-75 bg-light-grey">
					<h1 class="fst-italic lh-lg font-size-scaled-from-45px fw-bolder font-color-black">Monitor you incomes and expenses in one application. Be aware and save your money!</h1>
				</div>
			</article>
		</main>
		<div class="row">
			<footer class="col-12 text-center position-absolute bottom-0 end-0">
				<a class="footer-link font-color-black" href="https://www.flaticon.com/free-icons/money" title="money icons" target="_blank">Money icons created by Freepik - Flaticon</a>.  
				<a class="footer-link font-color-black d-block d-sm-inline-block" href="https://pl.freepik.com/search?format=search&query=marmur&type=photo" target="_blank">Marmur image created by rawpixel.com - pl.freepik.com</a>
				<span class="font-color-black d-block">All rights reserved &copy; 2022. Thank you for your visit </span>    
			</footer>
		</div>
	</div>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>   
	
</body>
</html>