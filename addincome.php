<?php
require 'includes/autoloader.php';
session_start();
//Authorization::checkAuthorization();

//require 'includes/head.php'; 
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<noscript>
  		<meta http-equiv="refresh" content="0;url=noscript.php" />
	</noscript>
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>Budget Manager</title>
	<meta name="description" content="Monitor your incomes and expenses - manage your budget and save money" />
	<meta name="keywords" content="money, budget, income, expense" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous"> 	
	<link rel="stylesheet" href="../css/style.css" type="text/css" />
	<link rel="stylesheet" href="../css/fontello.css" type="text/css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
</head>


<body>
	<header>	
		<nav class="navbar navbar-expand-lg navbar-light-yellow">
			<a class="navbar-brand" id="logoForPage" href="index.html"><img class="me-1 ms-1 d-inline-block align-middle" src="images/gold-ingots.png" alt="Gold bar" /><span class="text-uppercase font-weight-bold align-middle"> Budget Manager</span></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Button to open main menu options">
				<span class="navbar-toggler-icon">
					<i class="icon-menu"></i>
				</span>
			</button>
			<div class="collapse navbar-collapse" id="mainmenu">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item text-center"><a class="nav-link active" href="#" aria-current="page">Add income</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="addexpense.html">Add expense</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="balancereview.html">Review balance</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="#">Settings</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="includes/logout.php">Log out</a></li>
				</ul>
			</div>
		</nav>		
 	</header>
	
	
	<main>
		<div class="container height-no-navbar">
			<article class="h-100">
				<div class ="row g-0 h-85 align-items-center justify-content-center">
					<div class="col-lg-8  col-md-10 col-sm-12 bg-light-grey position-relative">  
						<div class="row">
							<div class="col-12 position-absolute top-0 start-50 p-0 text-center translate-middle" id="incomeRegisterConfirmation">
								<p class="font-color-grey mb-5"></p>
							</div>
							<div class="col-md-6 align-self-center pt-1 pt-md-0 pt-md-2">
								<header>
									<h2 class="font-color-black fw-bolder font-size-scaled-from-30px me-0 my-0 ms-4 text-md-start text-center">Add income</h2>
								</header>
							</div>
							<div class="col-md-6">
								<form class="lh-1 d-flex" action="" method="post" id="firstForm">
									<div class="w-50 px-2">
										<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1" for="amount">Amount</label>
										<input class="form-control form-control-sm fw-bold font-color-grey text-center ps-4" type="number" name="amount" id="amount" step="0.01" title="Please fill out this field" aria-label="Income expressed in the number as your benefit" />							
									</div>
									<div class="w-50 px-2">
										<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1" for="date">Date</label>
										<input class="form-control form-control-sm fw-bold font-color-grey text-center" type="date" name="date" id="date" min="2000-01-01" title="Please fill out this field" aria-label="Date of your income registration" />
									</div>
								</form>   
							</div>

							<div class="col-sm-12 py-2" style="font-size: 1rem;">
								<div class="underline"></div>
								<form class="lh-1 d-flex flex-column pt-2" action="" method="post" id="secondForm">
									<div class="text-center">
										<label  class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-1 me-2" for="categoryOptions">Category</label>  
										<select class="form-select form-select-sm w-auto d-inline-block fw-bold font-color-grey text-center" id="categoryOptions" name="category" aria-label="Category options for income">
											<option value="Salary" selected>Salary</option>
											<option value="Interest income">Interest income</option>
											<option value="Sale on Allegro">Sale on Allegro</option>
											<option value="Others">Others</option>
										</select>
									</div>
									<div class="d-inline-flex p-2 align-items-center">
										<label class="font-color-grey font-size-scaled-from-15px fw-bolder me-2 " for="comment">Comment (optional)</label>
										<div class="flex-fill">
											<input class="form-control form-control-sm fw-bold font-color-grey" type="text" name="comment" id="comment" title="Optional comment" aria-label="Optional comment" />
										</div>
									</div>
								</form>
								<div class="underline"></div>
								<div class="d-flex flex-column">
									<div class="btn-customized-group px-2" role="group">
										<button class="w-50 btn button-grey-color fw-bold font-size-scaled-from-15px mt-2 me-1" id="buttonToSubmitForm" type="submit" aria-label="Add income">Add</button>
										<a class="w-50 btn button-grey-color fw-bold font-size-scaled-from-15px mt-2 ms-1" href="index.html">Cancel</a>
									</div>
								</div>								
							</div>
						</div>
					</div>				
				</div>	
			</article>
		</div>
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
	
<!--	<script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>   -->
	<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script> 
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	
	<script>

	$(document).ready(function() {
		const amountInput = $('#amount');
		const dateInput = $('#date');
		var isRequiredFieldsBlank;
	
		amountInput.attr('min','0.01');
		dateInput.attr('min','2020-01-01');
		dateInput.attr('max','2099-12-31');

		amountInput.get(0).oninput = function() {
			this.value = this.value.replace(/[e\+\-]/gi, "");
		}; 

		amountInput.keypress(function(e) {
			if (e.which < 48 || e.which > 57) {
				e.preventDefault();
			}
		});

		dateInput.keypress(function(e) {
			e.preventDefault();
		});
		

		$('#buttonToSubmitForm').click(function() {
			amountInput.get(0).required = false;
			dateInput.get(0).required = false;
			isRequiredFieldsBlank = false;
			$('#logoForPage').focus();
			
			if(amountInput.val() =='' || amountInput.val().length - 1 == 0) {
				$('#incomeRegisterConfirmation > p').html('');
				amountInput.get(0).required = true;
				amountInput.get(0).oninput = function() {this.setCustomValidity('');};
				amountInput.get(0).oninvalid = function() {this.setCustomValidity('Please fill out this field');};
				amountInput.get(0).reportValidity();
				isRequiredFieldsBlank = true;
			}
			else if(dateInput.val() =='' || dateInput.val().length == 0) {
				$('#incomeRegisterConfirmation > p').html('');
				dateInput.get(0).required = true;
				dateInput.get(0).oninput = function() {this.setCustomValidity('');};
				dateInput.get(0).oninvalid = function() {this.setCustomValidity('Please fill out this field');};
				dateInput.get(0).reportValidity();
				isRequiredFieldsBlank = true;
			}
			
			if (!isRequiredFieldsBlank) {
				$.ajax({
					type: "POST",
					url: "/includes/insertIncomePartOne.php",
					data: $('#firstForm').serialize(),
				}).done(function() {
					$.ajax({
						type: "POST",
						url: "/includes/insertIncomePartTwo.php",
						data: $('#secondForm').serialize(),
						success: function(errorMessage) {
							if(!errorMessage) {
								$('#incomeRegisterConfirmation > p').html('Income is registered successfully. Click <a href=\"addincome.php\" class=\"font-light-orange link-registration-income-expense\">here</a> to insert next one');
								$('#buttonToSubmitForm').prop('disabled', true);
							}
							else {
								var json = JSON.parse(errorMessage);
								$('#incomeRegisterConfirmation > p').html(json);
							}
						}});
					});
			}  
		});   
	});

	</script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>   
	
</body>
</html>