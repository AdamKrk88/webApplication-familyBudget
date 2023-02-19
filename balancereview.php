<?php
//require 'includes/autoloader.php';
//session_start();
//Authorization::checkAuthorization();

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
					<li class="nav-item text-center"><a class="nav-link active" href="#" aria-current="page">Review balance</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="#">Settings</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="includes/logout.php">Log out</a></li>
				</ul>
			</div>
		</nav>		
 	</header>
	
	<main>
		<article>
			<div class="container-fluid height-no-navbar-lg-xl">
				<div class="row">
					<!--
					<div class="col-lg-8 col-md-6 col-12 mt-2 order-2 order-md-1">
						<div class="row g-0 bg-light-grey">
							<div class="col-12 bg-dark-grey">
								<h3 class="font-color-black fw-bolder font-size-scaled-from-18px text-center mb-0 py-1">Expenses</h3>
							</div>
							<div class="col-xl-5 col-lg-6 col-md-12 col-sm-7 col-12">
								<div class="table-responsive d-flex align-items-start justify-content-between">
									<table class="table table-borderless font-size-scaled-from-13px font-color-black lh-1 w-auto mb-0">							
										<tbody>
											<tr>
												<th scope="row">Food</th>
												<td>100000000</td>
											</tr>
											<tr>
												<th scope="row">Transport</th>
												<td>200</td>								
											</tr>
											<tr>
												<th scope="row">Healthcare</th>
												<td>200</td>						
											</tr>
											<tr>
												<th scope="row">Hygiene</th>
												<td>200</td>										
											</tr>
											<tr>
												<th scope="row">Entertainment</th>
												<td>200</td>												
											</tr>
											<tr>
												<th scope="row">Training</th>
												<td>200</td>											
											</tr>
											<tr>
												<th scope="row">Savings</th>
												<td>200</td>											
											</tr>
											<tr>
												<th scope="row">Debt repayment</th>
												<td>200</td>																			
											</tr>
										</tbody>	
									</table>
		
									<table class="table table-borderless font-size-scaled-from-13px font-color-black lh-1 w-auto mb-0">				
										<tbody>
											<tr>
												<th scope="row">Home</th>
												<td>200</td>
											</tr>
											<tr>
												<th scope="row">Telecommunications</th>
												<td>200</td>
											</tr>
											<tr>
												<th scope="row">Clothes</th>
												<td>200</td>
											</tr>
											<tr>
												<th scope="row">Children</th>
												<td>200</td>
											</tr>
											<tr>
												<th scope="row">Trip</th>
												<td>200</td>
											</tr>
											<tr>
												<th scope="row">Book</th>
												<td>200</td>
											</tr>
											<tr>
												<th scope="row">Retirement savings</th>
												<td>200</td>
											</tr>
											<tr>
												<th scope="row">Donation</th>
												<td>200</td>	
											</tr>
											<tr>
												<th scope="row">Others</th>
												<td>200</td>	
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-xl-7 col-lg-6 col-md-12 col-sm-5 col-12 pt-4 pb-3 py-lg-0 py-sm-0 pt-md-2 align-self-center">
								<div class="row g-0 justify-content-xl-end justify-content-lg-between justify-content-md-center justify-content-sm-between  justify-content-center ">													
									<div class="col-xl-4 col-lg-12 col-md-4 col-sm-12 col-4 p-lg-1 p-sm-1 p-md-0 me-lg-3 me-md-0 mb-sm-2">
										<div class="piechart mx-auto" id="expenseChart"></div>	
									</div>	
									<div class="col-auto lh-1 align-self-center align-self-lg-start align-self-xl-center font-size-scaled-from-13px ps-lg-5 ps-xl-0 pe-xl-2 pe-lg-0 pe-md-2 pe-3 pe-sm-0 ps-sm-1 ps-md-0">
										<div>
											<div id="color-red" class="entry-color"></div>  
											<div class="entry-text">Food</div>  
										</div>
										<div>
											<div id="color-orange" class="entry-color"></div>  
											<div class="entry-text">Transport</div>  
										</div>
										<div>
											<div id="color-yellow" class="entry-color"></div>  
											<div class="entry-text">Healthcare</div>  
										</div>
										<div>
											<div id="color-green" class="entry-color"></div>  
											<div class="entry-text">Hygiene</div>  
										</div>
										<div>
											<div id="color-blue" class="entry-color"></div>  
											<div class="entry-text">Entertainment</div>  
										</div>
										<div>
											<div id="color-maroon" class="entry-color"></div>  
											<div class="entry-text">Training</div>  
										</div>
										<div>
											<div id="color-lime" class="entry-color"></div>  
											<div class="entry-text">Savings</div>  
										</div>
										<div>
											<div id="color-navy" class="entry-color"></div>  
											<div class="entry-text">Debt repayment</div>  
										</div>								
									</div>
									<div class="col-auto pe-1 pe-lg-3 offset-lg-1 offset-xl-0 lh-1 align-self-center align-self-xl-center font-size-scaled-from-13px align-self-lg-start">										
										<div>
											<div id="color-aqua" class="entry-color"></div>  
											<div class="entry-text">Home</div>  
										</div>
										<div>
											<div id="color-teal" class="entry-color"></div>  
											<div class="entry-text">Telecommunications</div>  
										</div>
										<div>
											<div id="color-olive" class="entry-color"></div>  
											<div class="entry-text">Clothes</div>  
										</div>
										<div>
											<div id="color-fuchsia" class="entry-color"></div>  
											<div class="entry-text">Children</div>  
										</div>
										<div>
											<div id="color-chocolate" class="entry-color"></div>  
											<div class="entry-text">Trip</div>  
										</div>
										<div>
											<div id="color-coral" class="entry-color"></div>  
											<div class="entry-text">Book</div>  
										</div>
										<div>
											<div id="color-firebrick" class="entry-color"></div>  
											<div class="entry-text">Retirement savings</div>  
										</div>
										<div>
											<div id="color-lightpink" class="entry-color"></div>  
											<div class="entry-text">Donation</div>  
										</div>
										<div>
											<div id="color-steelblue" class="entry-color"></div>  
											<div class="entry-text">Others</div>  
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					-->
					<div class="col-md-10 col-12 offset-md-1 mt-2">
						<div class="row g-0 bg-light-grey">
							<div class="col-3">   
								<form class="lh-1 d-flex flex-column" action="#" method="post">
									<label  class="form-label font-color-black font-size-scaled-from-18px fw-bolder bg-dark-grey w-100 py-1 ps-2" for="periodForBalanceSummary">Period</label>  
									<select class="form-select form-select-sm w-auto align-self-start fw-bold font-color-grey text-center ms-1" id="periodForBalanceSummary" name="period" aria-label="Period for balance review">
										<option value="1" selected>Current month</option>
										<option value="2">Previous month</option>
										<option value="3">Current year</option>
										<option value="4">Your choice</option>
									</select>
								</form>
								<div class="modal fade" id="boxToProvidePeriodForBalanceSummary" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dateRange" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title font-size-scaled-from-18px fw-bold" id="dateRange">Provide date range for balance review</h5>
												<button type="button" id="closeModalSymbol" class="btn-close  font-size-scaled-from-15px" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<form>
													<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-0 d-block text-center" for="startDate">From</label>
													<input class="form-control form-control-sm fw-bold font-color-grey text-center" type="date" name="startDate" id="startDate" min="2000-01-01" title="Please fill out this field" aria-label="Start date for balance review period" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
													<label class="form-label font-color-grey font-size-scaled-from-15px fw-bolder mb-0 d-block text-center mt-4" for="endDate">To</label>
													<input class="form-control form-control-sm fw-bold font-color-grey text-center" type="date" name="endDate" id="endDate" min="2000-01-01" title="Please fill out this field" aria-label="End date for balance review period" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')"/>
												</form>
											</div>
											<div class="modal-footer">
												<span id="errorMessage" class="flex-fill text-center fw-bold font-size-scaled-from-15px font-red"></span>
												<button type="button" id="closeModalButton" class="btn button-grey-color fw-bold font-size-scaled-from-15px" data-bs-dismiss="modal" aria-label="Close">Close</button>
												<button type="button" id="submitModalButton" class="btn button-grey-color fw-bold font-size-scaled-from-15px" aria-label="Save date range">Save the period</button>
											</div>
										</div>
									</div>
								</div>									
							</div> 
							<div class="col-3 text-center" style="font-size: 1rem;">  
								<h3 class="lh-1 font-color-black fw-bolder font-size-scaled-from-18px bg-dark-grey w-100 mt-0 py-1 form-label">Income</h3>
								<p class="font-size-scaled-from-15px bg-light-grey mb-0 d-inline-block align-middle">Click here</p>
							</div>   
							<div class="col-3 text-center" style="font-size: 1rem;">  
								<h3 class="lh-1 font-color-black fw-bolder font-size-scaled-from-18px bg-dark-grey w-100 mt-0 py-1 form-label">Expense</h3>
								<p class="font-size-scaled-from-15px bg-light-grey mb-0 d-inline-block align-middle">Click here</p>
							</div> 
							<div class="col-3 text-end" style="font-size: 1rem;">
								<h3 class="lh-1 font-color-black fw-bolder font-size-scaled-from-18px bg-dark-grey w-100 mt-0 py-1 pe-2 form-label">Balance</h3>
								<p class="font-size-scaled-from-15px bg-light-grey mb-0 d-inline-block align-middle pe-2">10000000</p>
							</div>
							<div class="col-12 text-center font-light-stronger-orange">
								<div class="underline py-1"></div>
								<p class="font-size-scaled-from-15px mb-0 mt-1">Congratulations. You are focused on efficiency in financial management</p>
							</div>
						</div>																								
					</div>		
	
					<!--
					<div class="col-lg-8 col-md-6 col-12 mt-lg-4 mt-2 order-3">
						<div class="row g-0 bg-light-grey">
							<div class="col-12 bg-dark-grey">
								<h3 class="font-color-black fw-bolder font-size-scaled-from-18px text-center mb-0 py-1">Incomes</h3>
							</div>
							<div class="col-xl-5 col-lg-6 col-md-12 col-sm-7 col-12">
								<div class="table-responsive h-100 d-flex align-items-center justify-content-center">
									<table class="table table-borderless font-size-scaled-from-13px font-color-black lh-1 w-auto mb-0">							
										<tbody>
											<tr>
												<th scope="row">Salary</th>
												<td>200</td>
											</tr>
											<tr>
												<th scope="row">Interest income</th>
												<td>200</td>								
											</tr>
											<tr>
												<th scope="row">Sale on Allegro</th>
												<td>200</td>						
											</tr>
											<tr>
												<th scope="row">Others</th>
												<td>200</td>										
											</tr>
										</tbody>	
									</table>
								</div>
							</div>
							<div class="col-xl-7 col-lg-6 col-md-12 col-sm-5 col-12 pt-4 pb-3 py-lg-0 py-sm-0 pt-md-2 align-self-center">
								<div class="row g-0 justify-content-center ">													
									<div class="col-xl-4 col-lg-12 col-md-4 col-sm-12 col-4 p-lg-1 p-sm-1 p-md-0 me-lg-3 me-md-0 mb-sm-2">
										<div class="piechart mx-auto" id="incomeChart"></div>	
									</div>	
									<div class="col-auto lh-1 align-self-center align-self-lg-start align-self-xl-center font-size-scaled-from-13px ps-lg-5 ps-xl-0 pe-xl-2 pe-lg-0 pe-md-3 pe-3 pe-sm-0 ps-sm-2 ps-md-0">
										<div>
											<div id="color-red" class="entry-color"></div>  
											<div class="entry-text">Salary</div>  
										</div>
										<div>
											<div id="color-orange" class="entry-color"></div>  
											<div class="entry-text">Interest income</div>  
										</div>
										<div>
											<div id="color-yellow" class="entry-color"></div>  
											<div class="entry-text">Sale on Allegro</div>  
										</div>
										<div>
											<div id="color-green" class="entry-color"></div>  
											<div class="entry-text">Others</div>  
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>																						
					-->
				</div>
			</div>
		</article>
	</main>
	
	<div class="container position-relative">
		<div class="row">
			<footer class="col-12 text-center footer-position">
				<a class="footer-link font-color-black" href="https://www.flaticon.com/free-icons/money" title="money icons" target="_blank">Money icons created by Freepik - Flaticon</a>.  
				<a class="footer-link font-color-black d-block d-sm-inline-block" href="https://pl.freepik.com/search?format=search&query=marmur&type=photo" target="_blank">Marmur image created by rawpixel.com - pl.freepik.com</a>
				<span class="font-color-black d-block">All rights reserved &copy; 2022. Thank you for your visit </span>    
			</footer>
		</div>
	</div>


	
	<script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>
	<script>
	var optionValuePrevious = 1;
	
	function getCurrentOption() {
		var optionValueCurrent = $(this).val();
		if (optionValueCurrent != 4) {
			optionValuePrevious = optionValueCurrent;
		}
	}
		
	$(document).ready(function(){
		$('#periodForBalanceSummary').click(getCurrentOption);
	
		$('#periodForBalanceSummary').change(function() { 
			var optionValueNew = $(this).val(); 
			if(optionValueNew=="4"){ 
				$('#boxToProvidePeriodForBalanceSummary').modal("show"); 
			}
		});
		
		$("#closeModalSymbol").click(function() {
			$('#periodForBalanceSummary').val(optionValuePrevious);
		});
		
		$("#closeModalButton").click(function() {
			$('#periodForBalanceSummary').val(optionValuePrevious);
		});
		
	});
	</script>
	
	<script>
	function addZeroToDayOrMonthIfNecessaryAndConvertToString(number) {
		if (number < 10) {
			number = "0" + number;
		}
		else {
			number = number.toString();
		}
		return number
	}
	
	function convertDateFromInputToNumber(dateObject) {		
		var dayNumber = dateObject.getDate();
		var day = addZeroToDayOrMonthIfNecessaryAndConvertToString(dayNumber);
		var monthNumber = dateObject.getMonth() + 1;
		var month = addZeroToDayOrMonthIfNecessaryAndConvertToString(monthNumber);
		var year = dateObject.getFullYear().toString();
		var dateAsString = year + month + day;
		return parseInt(dateAsString);
	}
	
	function clearModalBoxToDefault() {
		$("#startDate").removeAttr("style");
		$("#endDate").removeAttr("style");
		$("#errorMessage").html("");	
		$("#endDate").val("");
		$("#startDate").val("");
	}	
	
	function returnCurrentDateAsNumber() {
		var currentDateObject=new Date();
		var currentDateAsInteger = convertDateFromInputToNumber(currentDateObject);
		return currentDateAsInteger;	
	}	
	
	function checkDateInModal() {	
		var startDateValue =$("#startDate").val();
		var endDateValue = $("#endDate").val();
		if (startDateValue != "" && endDateValue != "") {
			var startDate = new Date($("#startDate").val());
			var endDate = new Date($("#endDate").val());
			var startDateAsInteger = convertDateFromInputToNumber(startDate);
			var endDateAsInteger = convertDateFromInputToNumber(endDate);
			var currentDateAsInteger = returnCurrentDateAsNumber();
			var backgroundColorForEndDateInput = $("#endDate").css("background-color");
			var backgroundColorForStartDateInput = $("#startDate").css("background-color");
		}
	
		if (startDateValue == "" || endDateValue == "") {
			$("#errorMessage").addClass("font-red").removeClass("font-green").html("Provide dates");
		}
		
		else if (startDateAsInteger > currentDateAsInteger && endDateAsInteger > currentDateAsInteger ) {
			$("#startDate").css("background-color","#ff8080");
			$("#endDate").css("background-color","#ff8080");
			$("#errorMessage").addClass("font-red").removeClass("font-green").html("Both dates greater than current date");
		}
		
		else if (startDateAsInteger > currentDateAsInteger) {
			$("#startDate").css("background-color","#ff8080");
			$("#endDate").removeAttr("style");
			$("#errorMessage").addClass("font-red").removeClass("font-green").html("Start date greater than current date");
		}
		
		else if (endDateAsInteger > currentDateAsInteger) {
			$("#endDate").css("background-color","#ff8080");
			$("#startDate").removeAttr("style");
			$("#errorMessage").addClass("font-red").removeClass("font-green").html("End date greater than current date");
		}
		
		else if (endDateAsInteger < startDateAsInteger) {
			$("#startDate").css("background-color","#ff8080");
			$("#endDate").removeAttr("style");
			$("#errorMessage").addClass("font-red").removeClass("font-green").html("Start date greater than end date");	
		}
		
		else if (endDateAsInteger >= startDateAsInteger && backgroundColorForEndDateInput == "rgb(255, 255, 255)" && backgroundColorForStartDateInput == "rgb(255, 255, 255)") {
			$("#errorMessage").html("");	
		}
		
		else if (endDateAsInteger >= startDateAsInteger && (backgroundColorForEndDateInput == "rgb(255, 128, 128)" || backgroundColorForStartDateInput == "rgb(255, 128, 128)")) {
			$("#startDate").removeAttr("style");
			$("#endDate").removeAttr("style");
			$("#errorMessage").addClass("font-green").removeClass("font-red");
			$("#errorMessage").html("Now it is correct");		
		}
	}
	
	
	$("#submitModalButton").click(checkDateInModal);
	$("#boxToProvidePeriodForBalanceSummary").on("hidden.bs.modal",clearModalBoxToDefault);
	</script>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>   

</body>
</html>