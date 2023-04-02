<?php
require 'includes/autoloader.php';
session_start();
//Authorization::checkAuthorization();

$customizeQueryStringValue = $_GET['customize'] ?? false;

if ($customizeQueryStringValue) {
	$allowedCustomizeOptions = ["User", "Expense", "Income"];
	$isAllowedCustomizePresent = in_array($customizeQueryStringValue, $allowedCustomizeOptions, TRUE);
}
else {
	$isAllowedCustomizePresent = false;
}

//var_dump($isAllowedCustomizePresent);
require 'includes/headCharsetLang.php';  
require 'includes/headMetaTitleLink.php';
?>

<body>	
	<header>	
		<nav class="navbar navbar-expand-lg navbar-light-yellow">
			<a class="navbar-brand" href="index.html"><img class="me-1 ms-1 d-inline-block align-middle" src="images/gold-ingots.png" alt="Gold bar" /><span class="text-uppercase font-weight-bold align-middle"> Budget Manager</span></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Button to open main menu options">
				<span class="navbar-toggler-icon">
					<i class="icon-menu"></i>
				</span>
			</button>
			<div class="collapse navbar-collapse" id="mainmenu">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item text-center"><a class="nav-link" href="addincome.php">Add income</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="addexpense.php">Add expense</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="balancereview.php">Review balance</a></li>
					<li class="nav-item text-center"><a class="nav-link active" href="settings.php" aria-current="page">Settings</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="includes/logout.php">Log out</a></li>
				</ul>
			</div>
		</nav>		
 	</header>

    <main>
        <article>
            <div class="container height-no-navbar">
				<div class ="row g-0 h-85 align-items-center justify-content-center">
					<div class="col-lg-8 col-md-10 col-sm-12 bg-light-grey">
						<div class="row">
							<div class="col-12 text-center">
								<?php if (!$isAllowedCustomizePresent): ?>
								<h2 class="font-color-black fw-bolder font-size-scaled-from-30px">Customize your application</h2>
								<div class="underline"></div>
								<ul class="navbar-nav flex-row font-size-scaled-from-18px">
									<li class="nav-item text-center flex-even settings-option-style"><a class="nav-link" href="settings.php?customize=User">User</a></li>
									<li class="nav-item text-center flex-even settings-option-style"><a class="nav-link" href="settings.php?customize=Expense">Expense</a></li>
									<li class="nav-item text-center flex-even settings-option-style"><a class="nav-link" href="settings.php?customize=Income">Income</a></li>
								</ul>
								<?php elseif ($isAllowedCustomizePresent && $customizeQueryStringValue === "User"): ?>
								<h2 class="font-color-black fw-bolder font-size-scaled-from-30px">User</h2>
								<div class="underline"></div>	
								
									<form class="lh-1" action="" method="post">	
										<div class="form-group row align-items-center" style="font-size: 1rem;">
											<div class="col-3 py-1">
												<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="name-change">Name</label>
											</div>
											<div class="col-6 py-1">
												<input class="form-control form-control-sm fw-bold font-color-grey" type="text" name="name" id="name-change" title="Please fill out to change username" aria-label="username change" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											</div>
											<div class="col-3 py-1">
												<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 h-100" type="submit" aria-label="Name change button">Change</button>	
											</div>
										</div>
									</form> 

									<form class="lh-1" action="" method="post">	
										<div class="form-group row align-items-center" style="font-size: 1rem;">
											<div class="col-3 py-1">
												<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="email-change">Email</label>
											</div>
											<div class="col-6 py-1">
												<input class="form-control form-control-sm fw-bold font-color-grey" type="email" name="email" id="email-change" title="Please fill out to change email" aria-label="email change" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											</div>
											<div class="col-3 py-1">
												<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 h-100" type="submit" aria-label="Email change button">Change</button>	
											</div>
										</div>
									</form> 

									<form class="lh-1" action="" method="post">	
										<div class="form-group row align-items-center" style="font-size: 1rem;">
											<div class="col-3 py-1">
												<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="password-change">Password</label>
											</div>
											<div class="col-6 py-1">
												<input class="form-control form-control-sm fw-bold font-color-grey" type="password" name="password" id="password-change" title="Please fill out to change password" aria-label="password change" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											</div>
											<div class="col-3 py-1">
												<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 h-100" type="submit" aria-label="Password change button">Change</button>	
											</div>
										</div>
									</form> 

									<?php elseif ($isAllowedCustomizePresent && $customizeQueryStringValue === "Expense"): ?>
									<h2 class="font-color-black fw-bolder font-size-scaled-from-30px">Expense</h2>
									<div class="underline"></div>	

									<form class="lh-1" action="" method="post">	
										<div class="form-group row align-items-center" style="font-size: 1rem;">
											<div class="col-3 py-1">
												<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="add-category-expense">Category</label>
											</div>
											<div class="col-6 py-1">
												<input class="form-control form-control-sm fw-bold font-color-grey" type="text" name="add-category-expense" id="add-category-expense" title="Please fill out to add category" aria-label="add category for expense" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											</div>
											<div class="col-3 py-1">
												<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 h-100" type="submit" aria-label="Button to add category for expense">Add</button>	
											</div>
										</div>
									</form> 
								
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
            </div>
        </article>
    </main>

    <div class="container position-relative">
		<div class="row">
			<footer class="col-12 text-center position-absolute bottom-0 end-0">
				<a class="footer-link font-color-black" href="https://www.flaticon.com/free-icons/money" title="money icons" target="_blank">Money icons created by Freepik - Flaticon</a>.  
				<a class="footer-link font-color-black d-block d-sm-inline-block" href="https://pl.freepik.com/search?format=search&query=marmur&type=photo" target="_blank">Marmur image created by rawpixel.com - pl.freepik.com</a>
				<span class="font-color-black d-block">All rights reserved &copy; 2022. Thank you for your visit </span>    
			</footer>
		</div>
	</div>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>   

</body>
</html>