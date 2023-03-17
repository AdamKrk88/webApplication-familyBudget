<?php
require 'includes/autoloader.php';
session_start();
//Authorization::checkAuthorization();
//echo Date::isPreviousMonthDate();

$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$connection = $database->getConnectionToDatabase();
$categoryTotalAmountValue = Expense::getCategoryAndRelatedAmount($connection, $_SESSION['userId'], "Date", "isCurrentMonthDate");
$expense = Expense::getTotalExpense($connection, $_SESSION['userId'], "Date", "isCurrentMonthDate");
$categoryTotalAmountValueLength = count($categoryTotalAmountValue);
$balance = Budget::getTotalBalance($connection, $_SESSION['userId'], "Date", "isCurrentMonthDate");
$numberOfCategoriesInFirstTable = floor(count($categoryTotalAmountValue) / 2) + count($categoryTotalAmountValue) % 2;
$numberOfCategoriesInSecondTable = floor(count($categoryTotalAmountValue) / 2);

require 'includes/headCharsetLang.php';  
require 'includes/headMetaTitleLink.php';
?>

<body onresize="resizeFontsForPieChart()">	
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
								<p class="font-size-scaled-from-15px bg-light-grey mb-0 d-inline-block align-middle" id="switcher-incomeLink-presentedInformation">Click <a class="font-light-stronger-orange link-registration-income-expense" id="linkToPresentIncomes" href="">here</a></p>
							</div>   
							<div class="col-3 text-center" style="font-size: 1rem;">  
								<h3 class="lh-1 font-color-black fw-bolder font-size-scaled-from-18px bg-dark-grey w-100 mt-0 py-1 form-label">Expense</h3>
								<p class="font-size-scaled-from-15px bg-light-grey mb-0 d-inline-block align-middle" id="switcher-expenseLink-presentedInformation">Presented</p>
							</div> 
							<div class="col-3 text-end" style="font-size: 1rem;">
								<h3 class="lh-1 font-color-black fw-bolder font-size-scaled-from-18px bg-dark-grey w-100 mt-0 py-1 pe-2 form-label">Balance</h3>
								<p class="font-size-scaled-from-15px bg-light-grey mb-0 d-inline-block align-middle pe-2" id="total-balance"><?= $balance; ?></p>
							</div>
							<div class="col-12 text-center font-orange">
								<div class="underline py-1"></div>
								<p class="font-size-scaled-from-15px mb-0 mt-1" id="balance-comment"><?php if($balance > 0): ?>Congratulations. You are focused on efficiency in financial management<?php elseif($balance < 0): ?>Your balance is below zero. Review your budget<?php else: ?>Balance is equal to zero<?php endif; ?></p>
							</div>
						</div>																								
					</div>
					
					<div class="col-md-10 col-12 offset-md-1 mt-4">
						<div class="row g-0 bg-light-grey">
							<div class="col-12 bg-dark-grey">
								<h3 class="font-color-black fw-bolder font-size-scaled-from-18px text-center mb-0 py-1 position-relative" id="presented-table-name">Expenses</h3>
							</div>
							<div class="col-12">
								<?php if(!empty($categoryTotalAmountValue)): ?>
								<div class="row g-0">
									<div class="col-4">
										<div class="table-responsive d-flex align-items-start justify-content-between">	
											<table class="table table-borderless font-size-scaled-from-15px font-color-black lh-1 w-auto mb-0">							
												<tbody>
													<?php for($i = 0; $i < count($categoryTotalAmountValue); $i += 2): ?>
													<tr>
														<th scope="row" id="th<?= $i; ?>"><?= $categoryTotalAmountValue[$i][0]; ?></th>
														<td id="td<?= $i; ?>"><?= $categoryTotalAmountValue[$i][1]; ?></td>
													</tr>
													<?php endfor; ?>
													<?php for($i = 2 * $numberOfCategoriesInFirstTable; $i < 18; $i += 2): ?>
													<tr>
														<th scope="row" class="p-0" id="th<?= $i; ?>"></th>
														<td class="p-0" id="td<?= $i; ?>"></td>
													</tr>
													<?php endfor; ?>
												</tbody>	
											</table>
										</div>
									</div>

									<div id="myChartDiv" class="col-4 p-0">
										<canvas id="myChart"></canvas>
									</div>

									<div class="col-3 offset-1">
										<div class="table-responsive d-flex align-items-start justify-content-end">
						
											<table class="table table-borderless font-size-scaled-from-15px font-color-black lh-1 w-auto mb-0">				
												<tbody>
													<?php for($i = 1; $i < count($categoryTotalAmountValue); $i += 2): ?>
													<tr>
														<th scope="row" id="th<?= $i; ?>"><?= $categoryTotalAmountValue[$i][0]; ?></th>
														<td id="td<?= $i; ?>"><?= $categoryTotalAmountValue[$i][1]; ?></td>
													</tr>
													<?php endfor; ?>
													<?php for($i = 2 * $numberOfCategoriesInSecondTable + 1; $i < 18; $i += 2): ?>
													<tr>	
														<th scope="row" class="p-0" id="th<?= $i; ?>"></th>
														<td class="p-0" id="td<?= $i; ?>"></td>
													</tr>
													<?php endfor; ?>
												</tbody>
											</table>
										</div>	
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
	

				</div>
			</div>
			
		</article>
	</main>
<!--	<div id='tester'></div>    -->
	<div class="container position-relative">
		<div class="row">
			<footer class="col-12 text-center footer-position">
				<a class="footer-link font-color-black" href="https://www.flaticon.com/free-icons/money" title="money icons" target="_blank">Money icons created by Freepik - Flaticon</a>.  
				<a class="footer-link font-color-black d-block d-sm-inline-block" href="https://pl.freepik.com/search?format=search&query=marmur&type=photo" target="_blank">Marmur image created by rawpixel.com - pl.freepik.com</a>
				<span class="font-color-black d-block">All rights reserved &copy; 2022. Thank you for your visit </span>    
			</footer>
		</div>
	</div>

	

<!--	<script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script> -->
	<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>   
<!--	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.2.1/chart.min.js"></script>   -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.2.1/chart.umd.min.js"></script>   

<!--	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>   -->
<!--	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>  -->
	


	<!--
										<div class="position-absolute top-0 start-50 translate-middle-x">
											<canvas id="myChart"></canvas>
										</div>
	-->
	
<!--	<script type="text/javascript" src="js/PieChart.js"></script>   

	<script>
	$(function() {
		$('#source').pieChart('#target', 'Pie Chart Title');
	});
	</script>
-->

<script>

	var optionValuePrevious = 1;
	var startDateValue ="0";
	var endDateValue = "0";
	var counterForClickEventRelease = 0;
	var firstOptionClicked = 0;
//	const screenWidthPieChartHeight = provideScreenWidthAndPieChartHeight();
	var resizingCounter = 0;
	var screenResized = false;

	var pieChart = new Chart($("#myChart"), {
        type: 'pie',
		options: {
			responsive: true,
			maintainAspectRatio: false,
			aspectRatio: 1,
			plugins: {
				legend: {
					display: true
				},
				paddingBelowLegends: false,
				tooltip: {
					enabled: true,
					callbacks: {
						label: (item) => item.parsed + '%'
					}
				}
			}       	
		},
		data: {
            labels: displayCategories(),
            datasets: [
                {
                    backgroundColor: displayBackgroundColor(),	
                    data: displayPercentagesForCategories(),
                }
            ]
        }			
        });

	const screenWidthPieChartHeight = provideScreenWidthAndPieChartHeight();
	
	function displayCategories() {
		var numberOfCategories = parseInt("<?= $categoryTotalAmountValueLength; ?>");

		var categoryTotalAmountValue = <?= json_encode($categoryTotalAmountValue); ?>;
		var categories = [];
	//	alert(categoryTotalAmountValue[0][0]);

		for (let i = 0; i < numberOfCategories; i++) {
			categories[i] = categoryTotalAmountValue[i][0];
		}

		return categories;
	}

	   
	function displayBackgroundColor() {
		var numberOfCategories = parseInt("<?= $categoryTotalAmountValueLength; ?>");

		var colors = [];
	//	alert(categoryTotalAmountValue[0][0]);

		for (let i = 0; i < numberOfCategories; i++) {
			switch(i) {
				case 0:
					colors[i] = "#ffccff";
					break;
				case 1:
					colors[i] = "#bf80ff";
					break;
				case 2:
					colors[i] = "#ff80ff";
					break;
				case 3:
					colors[i] = "#df9fbf";
					break;
				case 4:
					colors[i] = "#ff80bf";
					break;
				case 5:
					colors[i] = "#ff80aa";
					break;
				case 6:
					colors[i] = "#df9f9f";
					break;
				case 7:
					colors[i] = "#ff8080";
					break;
				case 8:
					colors[i] = "#ffbf80";
					break;
				case 9:
					colors[i] = "#ffdf80";
					break;
				case 10:
					colors[i] = "#dfff80";
					break;
				case 11:
					colors[i] = "#80ff80";
					break;
				case 12:
					colors[i] = "#80ffe5";
					break;
				case 13:
					colors[i] = "#80ccff";
					break;
				case 14:
					colors[i] = "#8080ff";
					break;
				case 15:
					colors[i] = "#b3b3cc";
					break;
				case 16:
					colors[i] = "#9fbfdf";
					break;
				case 17:
					colors[i] = "#80bfff";
			}
		}
		
		return colors;
	}

	function displayPercentagesForCategories() {
		var numberOfCategories = parseInt("<?= $categoryTotalAmountValueLength; ?>");
		var totalExpense = parseFloat("<?= $expense; ?>");

		var categoryTotalAmountValue = <?= json_encode($categoryTotalAmountValue); ?>;
		var percentagePerCategory = 0;
		var percentages = [];
	//	alert(categoryTotalAmountValue[0][0]);
	//	alert(totalExpense);
		for (let i = 0; i < numberOfCategories; i++) {
			percentagePerCategory = (parseFloat(categoryTotalAmountValue[i][1]) / totalExpense) * 100;
			percentages[i] = percentagePerCategory.toFixed(2);
		}
	//	alert(percentages);
		return percentages;
	}

//	displayPercentagesForCategories();
		
		// Pie chart

//	var ctx = $("#myChart").get(0);

/* Piechart  *****************************

	var pieChart = new Chart($("#myChart"), {
        type: 'pie',
		options: {
    //        legend: { display: true },
     //       indexAxis: 'x',
		
			plugins: {
        //		datalabels: {
          //  		formatter: (value) => {
           //     let sum = 0;
            //   let dataArr = ctx.chart.data.datasets;
            //    dataArr.map(data => {
            //        sum += data;
            //    });
              //  let percentage = dataArr + "%";
              //  return percentage;
		//			}
				//}	
           // }

				tooltip: {
					enabled: true,
           		//	intersect: false,
           		//	mode: 'nearest',
		
					callbacks: {
						label: (item) => item.parsed + '%'
				/*		labelColor: function(context) {
                        return {
                            borderColor: 'rgb(0, 0, 255)',
                            backgroundColor: 'rgb(255, 0, 0)',
                            borderWidth: 2,
                            borderDash: [2, 2],
                            borderRadius: 2,
                        };
                    },
                    labelTextColor: function(context) {
                        return '#543453';
                    }
*/

/*
					label: ((tooltipItem) => {
					
					
							return  tooltipItem.parsed + '%';
						
    		
						})
*/
					/*	
						label: function(tooltipItem, data) {
						//	alert('rerer');
			return data['labels'][tooltipItem['index']] + ': ' + data['datasets'][0]['data'][tooltipItem['index']] + '%';
						
    		
						}
					*/

/*					}
				}
			}
        	
		},
		data: {
            labels: displayCategories(),
            datasets: [
                {
            //      label: "Technology Learned by Students",
                    backgroundColor: displayBackgroundColor(),	
                    data: displayPercentagesForCategories(),
                }
            ]
        }			
        });

 ************************* */
		//backgroundColor: ["#FFC0CB", "#0000FF",
		//"#00FFFF", "#FFA500", "#FF7F50","#FF0000"],	

	
	String.prototype.left = function(n) {
    return this.substring(0, n);
	}
	
	function getCurrentOption() {
		var optionValueCurrent = $(this).val();
		if (optionValueCurrent != 4) {
			optionValuePrevious = optionValueCurrent;
		}
	}

	function getFirstClickOption(that) {
		
		if (counterForClickEventRelease % 2 == 1) {
			
		firstOptionClicked = $(that).val();
	//	alert(firstOptionClicked);
		}
	}

	function getSelectedOptionFromDropDownList(that) {
		return $(that).children("option:selected").val(); 
	}

	function isElementEmpty(element) {
      return !$.trim(element.html())
  	}

	function isElementHasDisabledAttributeOn(attribute) {
		if (typeof(attribute) !== 'undefined' && attribute !== false) {
			return true;
		} 
		return false;
	}

	function switchIncomeExpenseSummary(fileName, timePeriodSelectedByUser, isFromDropDownList, isModal, startDateFromModal ='0', endDateFromModal = '0') {
	  	$.ajax({
			url: "/includes/" + fileName + ".php",   
			type: 'get',
			data: {
				'timePeriod': timePeriodSelectedByUser, 
				'isModal': isModal,
				'startDateFromModal': startDateFromModal,
				'endDateFromModal': endDateFromModal
			},
			success: function(incomeOrExpenseData) {
				var json = JSON.parse(incomeOrExpenseData);
				var numberOfIncomeOrExpenseCategories = Object.keys(json).length;
			//	alert(Object.keys(json).length);
				var checkIfPaddingIsAdded = false;
			//	alert('I am here');
				if (!isFromDropDownList) {
					if (fileName == "incomesPresentation") {
						$('#presented-table-name').html('Incomes <span class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-13px py-1 pe-2" id="date-for-your-choice"></span>');
					//	alert(startDateFromModal);
					//	alert(endDateFromModal);
					//alert(typeof(startDateFromModal));
				//	$('#date-for-your-choice').html('tralal');
						if (startDateFromModal != '0' && endDateFromModal != '0' && (startDateFromModal == endDateFromModal)) {
							$('#date-for-your-choice').html('one day  ' + startDateFromModal);
						}
						else if (startDateFromModal != '0' && endDateFromModal != '0') {
							$('#date-for-your-choice').html('from  ' + startDateFromModal + '  to  ' + endDateFromModal);
						}
						else {
							$('#date-for-your-choice').html('');
						}
						$('#switcher-incomeLink-presentedInformation').html('Presented');
						$('#switcher-expenseLink-presentedInformation').html('Click <a class="font-light-stronger-orange link-registration-income-expense" id="linkToPresentExpenses" href="">here</a>');
					}
					else if (fileName == "expensesPresentation") {
						$('#presented-table-name').html('Expenses <span class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-13px py-1 pe-2" id="date-for-your-choice"></span>');
					//	alert(startDateFromModal);
					//	alert(endDateFromModal);
						if (startDateFromModal != '0' && endDateFromModal != '0' && (startDateFromModal == endDateFromModal)) {
							$('#date-for-your-choice').html('one day  ' + startDateFromModal);
						}
						else if (startDateFromModal != '0' && endDateFromModal != '0') {
							$('#date-for-your-choice').html('from  ' + startDateFromModal + '  to  ' + endDateFromModal);
						}
						else {
							$('#date-for-your-choice').html('');
						}
						$('#switcher-expenseLink-presentedInformation').html('Presented');
						$('#switcher-incomeLink-presentedInformation').html('Click <a class="font-light-stronger-orange link-registration-income-expense" id="linkToPresentIncomes" href="">here</a>');
					}
				}
				else if (isFromDropDownList && !isModal) {
					$('#date-for-your-choice').html('');
				}

				if (isModal) {
					if (fileName == "incomesPresentation") {
						$('#presented-table-name').html('Incomes <span class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-13px py-1 pe-2" id="date-for-your-choice"></span>');
				
						if (startDateFromModal != '0' && endDateFromModal != '0' && (startDateFromModal == endDateFromModal)) {
							$('#date-for-your-choice').html('one day  ' + startDateFromModal);
						}
						else if (startDateFromModal != '0' && endDateFromModal != '0') {
							$('#date-for-your-choice').html('from  ' + startDateFromModal + '  to  ' + endDateFromModal);
						}
						else {
							$('#date-for-your-choice').html('');
						}
					}

					else if (fileName == "expensesPresentation") {
						$('#presented-table-name').html('Expenses <span class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-13px py-1 pe-2" id="date-for-your-choice"></span>');
			
						if (startDateFromModal != '0' && endDateFromModal != '0' && (startDateFromModal == endDateFromModal)) {
							$('#date-for-your-choice').html('one day  ' + startDateFromModal);
						}
						else if (startDateFromModal != '0' && endDateFromModal != '0') {
							$('#date-for-your-choice').html('from  ' + startDateFromModal + '  to  ' + endDateFromModal);
						}
						else {
							$('#date-for-your-choice').html('');
						}
					}
				}

				for (let i = 0; i < 18; i++) {
					if (i < numberOfIncomeOrExpenseCategories) {
						$('#th' + i).html(json[i][0]);
						$('#td' + i).html(json[i][1]); 
						checkIfPaddingIsAdded = $('#th' + i).hasClass("p-0");
					//	alert(checkIfPaddingIsAdded);
						if (checkIfPaddingIsAdded) {
							$('#th' + i).removeClass('p-0');
							$('#td' + i).removeClass('p-0');
						}
					}
					else {
						$('#th' + i).html("");
						$('#td' + i).html(""); 
						checkIfPaddingIsAdded = $('#th' + i).hasClass("p-0");
						if (!checkIfPaddingIsAdded) {
							$('#th' + i).addClass('p-0');
							$('#td' + i).addClass('p-0');
						}
					}
				}
			}
		});
	}

	function getTotalBalance(timePeriodSelectedByUser, isModal, startDateFromModal ='0', endDateFromModal = '0') {
		$.ajax({
			url: "/includes/totalBalance.php",   
			type: 'get',
			data: {
				'timePeriod': timePeriodSelectedByUser, 
				'isModal': isModal,
				'startDateFromModal': startDateFromModal,
				'endDateFromModal': endDateFromModal
			},
			success: function(balance) {
				var json = JSON.parse(balance);
				$('#total-balance').html(json);
				if (json > 0) {
					$('#balance-comment').html('Congratulations. You are focused on efficiency in financial management');
				}
				else if(json < 0) {
					$('#balance-comment').html('Your balance is below zero. Review your budget');
				}
				else {
					$('#balance-comment').html('Balance is equal to zero');
				}
				//alert(typeof(json));
			}
		});
	}

	function updatePieChart(pieChart, expenseOrIncome, timePeriodSelectedByUser, isModal, startDateFromModal = "0", endDateFromModal = "0") {

		$.ajax({
			async: false,
			url: "/includes/pieChartUpdate.php",   
			type: 'get',
			data: {
				'expenseOrIncome': expenseOrIncome,
				'timePeriod': timePeriodSelectedByUser, 
				'isModal': isModal,
				'startDateFromModal': startDateFromModal,
				'endDateFromModal': endDateFromModal
			},
			success: function(dataToUpdatePieChart) {
				var json = JSON.parse(dataToUpdatePieChart);
			//	alert(json);
				
				pieChart.data['labels'] = json['incomeCategories'];
				pieChart.data['datasets'][0]['backgroundColor'] = json['backgroundColorForPieChart'];
				pieChart.data['datasets'][0]['data'] = json['percentagePerCategory'];

				pieChart.update();
				
		//data['datasets'][0]['data'][tooltipItem['index']]
			}
		});
	}	

//below you can copy


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
		startDateValue =$("#startDate").val();  //tutaj
		endDateValue = $("#endDate").val();
		if (startDateValue != "" && endDateValue != "") {
			var startDate = new Date($("#startDate").val());
			var endDate = new Date($("#endDate").val());
			var startDateAsInteger = convertDateFromInputToNumber(startDate);
			var endDateAsInteger = convertDateFromInputToNumber(endDate);
			var currentDateAsInteger = returnCurrentDateAsNumber();
			var backgroundColorForEndDateInput = $("#endDate").css("background-color");
			var backgroundColorForStartDateInput = $("#startDate").css("background-color");
		}
	



		if (startDateValue == "" || endDateValue == "") { //tutaj
			$("#errorMessage").addClass("font-red").removeClass("font-green").html("Provide dates");
			return false;
		}
		
		else if (startDateAsInteger > currentDateAsInteger && endDateAsInteger > currentDateAsInteger ) {
			$("#startDate").css("background-color","#ff8080");
			$("#endDate").css("background-color","#ff8080");
			$("#errorMessage").addClass("font-red").removeClass("font-green").html("Both dates greater than current date");
			return false;
		}
		
		else if (startDateAsInteger > currentDateAsInteger) {
			$("#startDate").css("background-color","#ff8080");
			$("#endDate").removeAttr("style");
			$("#errorMessage").addClass("font-red").removeClass("font-green").html("Start date greater than current date");
			return false;
		}
		
		else if (endDateAsInteger > currentDateAsInteger) {
			$("#endDate").css("background-color","#ff8080");
			$("#startDate").removeAttr("style");
			$("#errorMessage").addClass("font-red").removeClass("font-green").html("End date greater than current date");
			return false;
		}
		
		else if (endDateAsInteger < startDateAsInteger) {
			$("#startDate").css("background-color","#ff8080");
			$("#endDate").removeAttr("style");
			$("#errorMessage").addClass("font-red").removeClass("font-green").html("Start date greater than end date");	
			return false;
		}
		
/*
		if (!$("#errorMessage").text() =="") {
			return false;
		}
*/

		if (endDateAsInteger >= startDateAsInteger && backgroundColorForEndDateInput == "rgb(255, 255, 255)" && backgroundColorForStartDateInput == "rgb(255, 255, 255)") {
		//	setTimeout(() => {alert('Hi')}, 3000);
			$("#errorMessage").addClass("font-green").removeClass("font-red").html("Correct");	

	}
		
		else if (endDateAsInteger >= startDateAsInteger && (backgroundColorForEndDateInput == "rgb(255, 128, 128)" || backgroundColorForStartDateInput == "rgb(255, 128, 128)")) {
			$("#startDate").removeAttr("style");
			$("#endDate").removeAttr("style");
			$("#errorMessage").addClass("font-green").removeClass("font-red").html("Now it is correct");
		//	$("#errorMessage").html("Now it is correct. Wait 3 sec");		
		}

		return true;
	}

	function resizeFontsForPieChart() {
	
		if (window.outerWidth >= 1200) {
			Chart.defaults.font.size = 13;
			pieChart.options['plugins']['legend']['display'] = true;
			
			
			$('#myChartDiv').css('height',screenWidthPieChartHeight[1]);
			
		

		}
		
		if (window.outerWidth >= 768 && window.outerWidth < 1200) {
			Chart.defaults.font.size = 12;
			pieChart.options['plugins']['legend']['display'] = true;
		
			
			$('#myChartDiv').css('height',screenWidthPieChartHeight[1]);
			
		

		}

		if (window.outerWidth < 768) {
			Chart.defaults.font.size = 11;
			pieChart.options['plugins']['legend']['display'] = false;
			
			$('#myChartDiv').css('height',screenWidthPieChartHeight[1]/2);
			
		

		}

		pieChart.update();

	}

	function provideScreenWidthAndPieChartHeight() {
			return [$(window).width(), $('#myChartDiv').height()];
	}


/*

pieChart.data['labels'] = json['incomeCategories'];
				pieChart.data['datasets'][0]['backgroundColor'] = json['backgroundColorForPieChart'];
				pieChart.data['datasets'][0]['data'] = json['per

*/




/*
		$("#switcher-expenseLink-presentedInformation").on("click","#linkToPresentExpenses", function(e) {
			e.preventDefault();
			switchIncomeExpenseSummary('expensesPresentation');
		}); 

		$("#switcher-incomeLink-presentedInformation").on("click", "#linkToPresentIncomes", function(e) {
			e.preventDefault();
			switchIncomeExpenseSummary('incomesPresentation');
		});  

*/

/*
		$("div p#switcher-expenseLink-presentedInformation > a#linkToPresentExpenses").on("click", function(e) {
			e.preventDefault();
			switchIncomeExpenseSummary('expensesPresentation');
		}); 

		$("div p#switcher-incomeLink-presentedInformation > a#linkToPresentIncomes").on("click", function(e) {
			e.preventDefault();
			switchIncomeExpenseSummary('incomesPresentation');
		});  
*/
		/*
		$('#linkToPresentIncomes').click(function(e) {
		//	e.preventDefault();
			switchIncomeExpenseSummary('incomesPresentation',e);
		});  

		$('#linkToPresentExpenses').click(function(e) {
			//e.preventDefault();
			switchIncomeExpenseSummary('expensesPresentation',e);
		}); 
*/
		
	
	
	$(document).ready(function(){

		if (window.outerWidth >= 1200) {
			Chart.defaults.font.size = 13;
		//	pieChart.options['plugins']['legend']['display'] = true;
		}
		
		if (window.outerWidth >= 768 && window.outerWidth < 1200) {
			Chart.defaults.font.size = 12;
		//	pieChart.options['plugins']['legend']['display'] = true;
		}
/*
		if (window.outerWidth == 768) {
			$("#submitModalButton").removeAttr("disabled");
		} 
*/
		if (window.outerWidth < 768) {
			Chart.defaults.font.size = 11;
			pieChart.options['plugins']['legend']['display'] = false;
			$('#myChartDiv').css('height',screenWidthPieChartHeight[1]/2);
		//	pieChart.options['aspectRatio'] = 1;
		//	alert(pieChart.options['aspectRatio']);
			pieChart.update();
		}



/*
		var pieChart = new Chart($("#myChart"), {
        type: 'pie',
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				tooltip: {
					enabled: true,
					callbacks: {
						label: (item) => item.parsed + '%'
					}
				}
			}       	
		},
		data: {
            labels: displayCategories(),
            datasets: [
                {
                    backgroundColor: displayBackgroundColor(),	
                    data: displayPercentagesForCategories(),
                }
            ]
        }			
        });
*/
	//	Chart.defaults.font.size = 10;

	//	updatePieChart(pieChart);
		


/*		$('#linkToPresentIncomes').click(function(e) {
		//	e.preventDefault();
			switchIncomeExpenseSummary('incomesPresentation',e);
		});  

		$('#linkToPresentExpenses').click(function(e) {
			//e.preventDefault();
			switchIncomeExpenseSummary('expensesPresentation',e);
		}); 
*/
	//	$('#periodForBalanceSummary').click(getCurrentOption);
	
	//	$('#boxToProvidePeriodForBalanceSummary').unbind('change');
		$('#periodForBalanceSummary').change(function() { 
			var optionValueNew = getSelectedOptionFromDropDownList(this);
			var typeOfDataReviewed = $('#presented-table-name').text().left(8).trim();
		//	alert(typeOfDataReviewed);
		//	if (optionValueNew == "1" || optionValueNew == "2" || optionValueNew == "3") {
				startDateValue ="0";
				endDateValue = "0";
				if (optionValueNew == "1" && typeOfDataReviewed== "Expenses") {
					switchIncomeExpenseSummary('expensesPresentation', 'isCurrentMonthDate', true, false);
					getTotalBalance('isCurrentMonthDate', false);
					updatePieChart(pieChart, 'Expense', 'isCurrentMonthDate', false);
					// (timePeriodSelectedByUser, isModal, startDateFromModal ='0', endDateFromModal = '0')
				}
				else if (optionValueNew == "2" && typeOfDataReviewed== "Expenses") {
					switchIncomeExpenseSummary('expensesPresentation', 'isPreviousMonthDate', true, false);
					getTotalBalance('isPreviousMonthDate', false);
					updatePieChart(pieChart, 'Expense', 'isPreviousMonthDate', false);
				}
				else if (optionValueNew == "3" && typeOfDataReviewed== "Expenses") {
					switchIncomeExpenseSummary('expensesPresentation', 'isCurrentYearDate', true, false);
					getTotalBalance('isCurrentYearDate', false);
					updatePieChart(pieChart, 'Expense', 'isCurrentYearDate', false);
				}
				else if (optionValueNew == "1" && typeOfDataReviewed== "Incomes") {
					switchIncomeExpenseSummary('incomesPresentation', 'isCurrentMonthDate', true, false);
					getTotalBalance('isCurrentMonthDate', false);
					updatePieChart(pieChart, 'Income', 'isCurrentMonthDate', false);
				}
				else if (optionValueNew == "2" && typeOfDataReviewed== "Incomes") {
					switchIncomeExpenseSummary('incomesPresentation', 'isPreviousMonthDate', true, false);
					getTotalBalance('isPreviousMonthDate', false);
					updatePieChart(pieChart, 'Income', 'isPreviousMonthDate', false);
				}
				else if (optionValueNew == "3" && typeOfDataReviewed== "Incomes") {
					switchIncomeExpenseSummary('incomesPresentation', 'isCurrentYearDate', true, false);
					getTotalBalance('isCurrentYearDate', false);
					updatePieChart(pieChart, 'Income', 'isCurrentYearDate', false);
				}
	//		}
			//var optionValueNew = $(this).children("option:selected").val(); 
		//	alert(typeOfDataReviewed);
	//		if(optionValueNew == "4"){ 
				//$('#boxToProvidePeriodForBalanceSummary').unbind('change');
				
		//		$('#boxToProvidePeriodForBalanceSummary').modal("show"); 
			//	var startDateFromModal = $('#startDate').val();
			//	var endDateFromModal = $('#endDate').val();
		//	}
		});

		$('#periodForBalanceSummary').click(function() {
		//	getCurrentOption();
			//alert('tralala');	
			var currentOptionSelected = $(this).val();
		//	alert(currentOptionSelected);	
			counterForClickEventRelease++;
			getFirstClickOption(this);
		//	alert('tralala');   //firstOptionClicked
		
			if (currentOptionSelected == "4" && counterForClickEventRelease > 1) {
				$('#boxToProvidePeriodForBalanceSummary').modal("show");
				
				const disabledAttributeForButtonsInModal = [$("#closeModalSymbol").attr("disabled"), $("#closeModalButton").attr("disabled"), $("#submitModalButton").attr("disabled"), $("#startDate").attr("disabled"), $("#endDate").attr("disabled")];
		//		$("#closeModalButton").attr("disabled", true);
		//		$("#submitModalButton").attr("disabled", true);
				for (let i = 0; i < disabledAttributeForButtonsInModal.length; i++ ) {
					if (isElementHasDisabledAttributeOn(disabledAttributeForButtonsInModal[i])) {
							switch (i) {
								case 0:
									$("#closeModalSymbol").removeAttr("disabled");
									break;
								case 1:
									$("#closeModalButton").removeAttr("disabled");
									break;
								case 2:
									$("#submitModalButton").removeAttr("disabled");
									break;
								case 3:
									$("#startDate").removeAttr("disabled");
									break;
								case 4:
									$("#endDate").removeAttr("disabled");
							}
					}
				}

				counterForClickEventRelease = 0;
				
		//		alert(typeof($("#closeModalSymbol").attr("disabled")));
		//		$("#closeModalButton").attr("disabled", true);
		//		$("#submitModalButton").attr("disabled", true);

			}
		});
		
		$("#closeModalSymbol").click(function() {
		//	if ($('#periodForBalanceSummary').children("option:selected").val() != '4') {
	//		if () {
			$('#periodForBalanceSummary').val(firstOptionClicked);
	//		}
		//	}
		});
		
		$("#closeModalButton").click(function() {
		//	if ($('#periodForBalanceSummary').children("option:selected").val() != '4') {
	//		if () {
			$('#periodForBalanceSummary').val(firstOptionClicked);   //optionValuePrevious
	//		}
		//	}
		});


		$("#switcher-expenseLink-presentedInformation").on("click","#linkToPresentExpenses", function(e) {
			e.preventDefault();
			var selectedOptionFromDropDownList = getSelectedOptionFromDropDownList('#periodForBalanceSummary');
		//	alert(selectedOptionFromDropDownList);
			if (selectedOptionFromDropDownList == "1") {
				switchIncomeExpenseSummary('expensesPresentation', 'isCurrentMonthDate', false, false);
				updatePieChart(pieChart, 'Expense', 'isCurrentMonthDate', false);
			}
			else if (selectedOptionFromDropDownList == "2") {
				switchIncomeExpenseSummary('expensesPresentation', 'isPreviousMonthDate', false, false);
				updatePieChart(pieChart, 'Expense', 'isPreviousMonthDate', false);
			}
			else if (selectedOptionFromDropDownList == "3") {
				switchIncomeExpenseSummary('expensesPresentation', 'isCurrentYearDate', false, false);
				updatePieChart(pieChart, 'Expense', 'isCurrentYearDate', false);
			}
			else if (selectedOptionFromDropDownList == "4") {
		//		startDateValue =$("#startDate").val();   //tutaj
		//		endDateValue = $("#endDate").val();
				switchIncomeExpenseSummary('expensesPresentation', 'isTimePeriodProvidedByUser', false, true, startDateValue, endDateValue);
				updatePieChart(pieChart, 'Expense', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
			}
		}); 

		$("#switcher-incomeLink-presentedInformation").on("click", "#linkToPresentIncomes", function(e) {
			e.preventDefault();
			var selectedOptionFromDropDownList = getSelectedOptionFromDropDownList('#periodForBalanceSummary');

			if (selectedOptionFromDropDownList == "1") {
				switchIncomeExpenseSummary('incomesPresentation', 'isCurrentMonthDate', false, false);
				updatePieChart(pieChart, 'Income', 'isCurrentMonthDate', false);
			//	updatePieChart(pieChart, expenseOrIncome, timePeriodSelectedByUser, isModal, startDateFromModal = "0", endDateFromModal = "0")	
			}
			else if (selectedOptionFromDropDownList == "2") {
				switchIncomeExpenseSummary('incomesPresentation', 'isPreviousMonthDate', false, false);
				updatePieChart(pieChart, 'Income', 'isPreviousMonthDate', false);
			}
			else if (selectedOptionFromDropDownList == "3") {
				switchIncomeExpenseSummary('incomesPresentation', 'isCurrentYearDate', false, false);
				updatePieChart(pieChart, 'Income', 'isCurrentYearDate', false);
			}
			else if (selectedOptionFromDropDownList == "4") {
			//	startDateValue =$("#startDate").val();   //tutaj
			//	endDateValue = $("#endDate").val();
				switchIncomeExpenseSummary('incomesPresentation', 'isTimePeriodProvidedByUser', false, true, startDateValue, endDateValue);
				updatePieChart(pieChart, 'Income', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
			}
		});  


/*
		for (let i = 0; i < 18; i++) {
			let thElement = $('#th' + i);
			let tdElement = $('#td' + i); 

			if (isElementEmpty(thElement) && isElementEmpty(tdElement)) {
				thElement.addClass('p-0');
				tdElement.addClass('p-0');
			}
		}
*/
/* Click <a class="font-light-stronger-orange link-registration-income-expense" href="#">here</a> */

/*
		$.ajax({
			type: "POST",
			url: "/includes/insertExpensePartOne.php",
			data: $('#firstForm').serialize(),
		}).done(function() {
			$.ajax({
				type: "POST",
				url: "/includes/insertExpensePartTwo.php",
				data: $('#secondForm').serialize(),
				success: function(errorMessage) {
					if(!errorMessage) {
						$('#expenseRegisterConfirmation > p').html('Expense is registered successfully. Click <a href=\"addexpense.php\" class=\"font-light-orange link-registration-income-expense\">here</a> to insert next one');
						$('#buttonToSubmitForm').prop('disabled', true);
					}
					else {
						var json = JSON.parse(errorMessage);
						$('#expenseRegisterConfirmation > p').html(json);
					}
				}});
			});
*/




		


	
//here was script tag closing and open ones

	
	$("#submitModalButton").click(function () {
		if (checkDateInModal()) {
		//	startDateValue =$("#startDate").val();   //tutaj
		//	endDateValue = $("#endDate").val();
		//	alert(startDateValue);
		$("#closeModalSymbol").attr("disabled", true);
		$("#closeModalButton").attr("disabled", true);
		$("#submitModalButton").attr("disabled", true);
		$("#startDate").attr("disabled", true);
		$("#endDate").attr("disabled", true);
			var typeOfDataReviewed = $('#presented-table-name').text().left(8).trim();
			//$('#boxToProvidePeriodForBalanceSummary').modal("hide"); 

			$('#boxToProvidePeriodForBalanceSummary').delay(2000).queue(function() { 
  				$(this).modal("hide");
  
  				if (typeOfDataReviewed == "Expenses") {
					switchIncomeExpenseSummary('expensesPresentation', 'isTimePeriodProvidedByUser', true, true, startDateValue, endDateValue); 
					getTotalBalance('isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
					updatePieChart(pieChart, 'Expense', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
					// (timePeriodSelectedByUser, isModal, startDateFromModal ='0', endDateFromModal = '0')
				}
				else if (typeOfDataReviewed == "Incomes") {
					switchIncomeExpenseSummary('incomesPresentation', 'isTimePeriodProvidedByUser', true, true, startDateValue, endDateValue);
					getTotalBalance('isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
					updatePieChart(pieChart, 'Income', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
				}
  				
				$(this).dequeue(); 
			});
/*
			if (typeOfDataReviewed == "Expenses") {
				switchIncomeExpenseSummary('expensesPresentation', 'isTimePeriodProvidedByUser', true, true, startDateValue, endDateValue);   //tutaj
			}
			else if (typeOfDataReviewed == "Incomes") {
				switchIncomeExpenseSummary('incomesPresentation', 'isTimePeriodProvidedByUser', true, true, startDateValue, endDateValue);
			}
			*/
		}
	});

	$("#boxToProvidePeriodForBalanceSummary").on("hidden.bs.modal",clearModalBoxToDefault);
/*
	function resizeFontsForPieChart() {
		if (window.outerWidth >= 1200) {
			Chart.defaults.font.size = 13;
		}
			
		if (window.outerWidth >= 768 && window.outerWidth < 1200) {
			Chart.defaults.font.size = 12;
		}

		if (window.outerWidth < 768) {
			Chart.defaults.font.size = 11;
		}

		pieChart.update();

	}
*/


	});




	</script>
<!--	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>   
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.2.1/chart.min.js"></script> 
-->
</body>
</html>