<?php
require 'includes/autoloader.php';
session_start();
Authorization::checkAuthorization();

$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
$connection = $database->getConnectionToDatabase();
$categoryTotalAmountValue = Expense::getCategoryAndRelatedAmount($connection, $_SESSION['userId'], "Date", "isCurrentMonthDate");
$expense = Expense::getTotalExpense($connection, $_SESSION['userId'], "Date", "isCurrentMonthDate");
$categoryTotalAmountValueLength = count($categoryTotalAmountValue);
$balance = Budget::getTotalBalance($connection, $_SESSION['userId'], "Date", "isCurrentMonthDate");
$numberOfCategoriesInFirstTable = floor(count($categoryTotalAmountValue) / 2) + count($categoryTotalAmountValue) % 2;
$numberOfCategoriesInSecondTable = floor(count($categoryTotalAmountValue) / 2);

require 'includes/headCharsetLang.php';  
require 'includes/noscriptTagInHead.php';
require 'includes/headMetaTitleLink.php';
?>

<body onresize="resizeFontsForPieChart()">	
	<header>	
		<nav class="navbar navbar-expand-lg navbar-light-yellow">
			<a class="navbar-brand" href="menu.php"><img class="me-1 ms-1 d-inline-block align-middle" src="images/gold-ingots.png" alt="Gold bar" /><span class="text-uppercase font-weight-bold font-size-scaled-from-30px-navbar align-middle"> Budget Manager</span></a>
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
					<li class="nav-item text-center"><a class="nav-link" href="settings.php">Settings</a></li>
					<li class="nav-item text-center"><a class="nav-link" href="logout.php">Log out</a></li>
				</ul>
			</div>
		</nav>		
 	</header>
	
	<main>
		<article>
			
			<div class="container-fluid height-no-navbar">
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
								<h3 class="font-color-black fw-bolder font-size-scaled-from-18px text-center mb-0 py-1 position-relative" id="presented-table-name">Expenses<a class="position-absolute top-50 start-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 ps-2" href="" id="chart-list-switcher"><em>Chart / List Switcher</em></a></h3>
							</div>
							<div class="col-12">
								<p class="font-color-black font-size-scaled-from-15px mb-0 mt-0 text-center mt-1" id="noDataComment"><?php if(empty($categoryTotalAmountValue)): ?> Nothing to show <?php endif; ?></p>
						
								<div class="table-responsive">	
									<table class="table table-borderless font-size-scaled-from-15px font-color-black mb-0 text-center">							
										<thead>
											<tr>
												<th scope="col" class="p-0 fw-bolder" style="width:10%" id="id-list"></th>
												<th scope="col" class="p-0 fw-bolder" style="width:15%" id="date-list"></th>
												<th scope="col" class="p-0 fw-bolder" style="width:16%" id="category-list"></th>
												<th scope="col" class="p-0 fw-bolder" style="width:16%" id="payment-list"></th>
												<th scope="col" class="p-0 fw-bolder" style="width:30%" id="comment-list"></th>
												<th scope="col" class="p-0 fw-bolder" style="width:13%" id="amount-list"></th>
											</tr>
										</thead>	

										<tbody>
											<?php for($i = 0; $i < 7; $i++): ?>
											<tr>
												<th scope="row" id="th-id-<?= $i; ?>" class="p-0"></th>
												<td id="td-date-<?= $i; ?>" class="p-0"></td>
												<td id="td-category-<?= $i; ?>" class="p-0"></td>
												<td id="td-payment-<?= $i; ?>" class="p-0"></td>
												<td id="td-comment-<?= $i; ?>" class="p-0"></td>
												<td id="td-amount-<?= $i; ?>" class="p-0"></td>
											</tr>
											<?php endfor; ?>
										</tbody>	
									</table>
								</div>


								<div class="row g-0">
									<div class="col-4">
										<div class="table-responsive d-flex align-items-start justify-content-between mt-1">	
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
										<?php if(!empty($categoryTotalAmountValue)): ?>
										<canvas id="myChart"></canvas>
										<?php endif; ?>    
									</div>

									<div class="col-3 offset-1">
										<div class="table-responsive d-flex align-items-start justify-content-end mt-1">
						
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
							</div>
						</div>
						<div class="row g-0">
							<div class="col-6 text-center font-size-scaled-from-15px bg-dark-grey" id="previous-link-div">
								<a class="link-registration-income-expense font-color-black fst-italic fw-bolder" id="previous-link" href=""></a>
							</div>
							<div class="col-6 text-center font-size-scaled-from-15px bg-dark-grey" id="next-link-div">
								<a class="link-registration-income-expense font-color-black fst-italic fw-bolder" id="next-link" href=""></a>
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
				<span class="font-color-black d-block">All rights reserved &copy; 2023. Thank you for your visit </span>    
			</footer>
		</div>
	</div>


	<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>   

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.2.1/chart.umd.min.js"></script>   

	<script>

	var optionValuePrevious = 1;
	var startDateValue ="0";
	var endDateValue = "0";
	var counterForClickEventRelease = 0;
	var firstOptionClicked = 0;
	var screenWidthPieChartHeight = [];
	var resizingCounter = 0;
	var screenResized = false;
	var pieChart;
	var dataToDisplayList = [];
	

	if (checkIfDataForPieChartExist()) {
		screenWidthPieChartHeight = createPieChart(true);
		numberOfExpenseCategories = <?= $categoryTotalAmountValueLength; ?>;
	
		if (numberOfExpenseCategories > 9) {
			pieChart.options['plugins']['legend']['display'] = false;
			pieChart.update();
		}

		if (window.outerWidth >= 1200) {
			Chart.defaults.font.size = 13;
		}
		
		if (window.outerWidth >= 768 && window.outerWidth < 1200) {
			Chart.defaults.font.size = 12;
			if (numberOfExpenseCategories > 9 && window.outerWidth < 1000) {
				$('#myChartDiv').css('height',2*screenWidthPieChartHeight[1]/3);
			}
		}

		if (window.outerWidth < 768) {
			Chart.defaults.font.size = 11;
			
			if (numberOfExpenseCategories <= 9) {
				pieChart.options['plugins']['legend']['display'] = false;
			}
			
			$('#myChartDiv').css('height',screenWidthPieChartHeight[1]/2);
			pieChart.update();
		}
	}

	function createPieChart(isCallOnload) {
		if($('#myChart').length === 0) {
			$('#myChartDiv').html("<canvas id='myChart'></canvas>");
		}

		pieChart = new Chart($("#myChart"), {
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
            labels: displayCategories(isCallOnload),
            datasets: [
                {
                    backgroundColor: displayBackgroundColor(isCallOnload),	
                    data: displayPercentagesForCategories(isCallOnload),
                }
            ]
        }			
        });

		return provideScreenWidthAndPieChartHeight();
	}

	function checkIfDataForPieChartExist() {
		var categoryTotalAmountValue = <?= json_encode($categoryTotalAmountValue); ?>;
		if (Array.isArray(categoryTotalAmountValue) && categoryTotalAmountValue.length) {
			return true;
		}

		return false;
	}

	function recoverPieChartHeight() {
		if (pieChart.data['labels'].length > 9) {
			pieChart.options['plugins']['legend']['display'] = false;
		}
		else if (pieChart.data['labels'].length <= 9 && window.outerWidth >= 768) {
			pieChart.options['plugins']['legend']['display'] = true;
		}
		
		if (window.outerWidth >= 1200) {
			$('#myChartDiv').css('height',screenWidthPieChartHeight[1]);		
		}
			
		if (window.outerWidth >= 768 && window.outerWidth < 1200) {
			if (pieChart.data['labels'].length > 9 && window.outerWidth < 1000) {
				$('#myChartDiv').css('height',2*screenWidthPieChartHeight[1]/3);
			}
			else if (pieChart.data['labels'].length > 9 && window.outerWidth >= 1000) {
				$('#myChartDiv').css('height',screenWidthPieChartHeight[1]);
			}
			else {
				$('#myChartDiv').css('height',screenWidthPieChartHeight[1]);
			}
		}

		if (window.outerWidth < 768) {	
			$('#myChartDiv').css('height',screenWidthPieChartHeight[1]/2);
			pieChart.options['plugins']['legend']['display'] = false;	
		}

		pieChart.update();
	}
	
	function displayCategories(isCallOnload) {
		var categories = [];

		if (isCallOnload) {
			var numberOfCategories = parseInt("<?= $categoryTotalAmountValueLength; ?>");
			var categoryTotalAmountValue = <?= json_encode($categoryTotalAmountValue); ?>;

			for (let i = 0; i < numberOfCategories; i++) {
				categories[i] = categoryTotalAmountValue[i][0];
			}

			return categories;
		}
		else {
			return categories;
		}
	}
 
	function displayBackgroundColor(isCallOnload) {
		var colors = [];
		
		if (isCallOnload) {
			var numberOfCategories = parseInt("<?= $categoryTotalAmountValueLength; ?>");

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
		else {
			return colors;
		}
	}

	function displayPercentagesForCategories(isCallOnload) {
		var percentages = [];
		
		if (isCallOnload) {
			var numberOfCategories = parseInt("<?= $categoryTotalAmountValueLength; ?>");
			var totalExpense = parseFloat("<?= $expense; ?>");

			var categoryTotalAmountValue = <?= json_encode($categoryTotalAmountValue); ?>;
			var percentagePerCategory = 0;
			
			for (let i = 0; i < numberOfCategories; i++) {
				percentagePerCategory = (parseFloat(categoryTotalAmountValue[i][1]) / totalExpense) * 100;
				percentages[i] = percentagePerCategory.toFixed(2);
			}

			return percentages;
		}
		else {
			return percentages;
		}
	}

	
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

	function switchIncomeExpenseSummary(fileName, timePeriodSelectedByUser, isFromDropDownList, isFromChartListSwitcher, isModal, startDateFromModal ='0', endDateFromModal = '0') {
	  	$.ajax({
			async: false,
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
				var checkIfPaddingIsAdded = false;
			
				if (!isFromDropDownList && !isFromChartListSwitcher) {
					if (fileName == "incomesPresentation") {
						$('#presented-table-name').html('Incomes <span class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-13px py-1 pe-2" id="date-for-your-choice"></span><a class="position-absolute top-50 start-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 ps-2" href="" id="chart-list-switcher"><em>Chart / List Switcher</em></a>');
					
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
						$('#presented-table-name').html('Expenses <span class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-13px py-1 pe-2" id="date-for-your-choice"></span><a class="position-absolute top-50 start-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 ps-2" href="" id="chart-list-switcher"><em>Chart / List Switcher</em></a>');
				
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

				if (isModal && !isFromChartListSwitcher) {
					if (fileName == "incomesPresentation") {
						$('#presented-table-name').html('Incomes <span class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-13px py-1 pe-2" id="date-for-your-choice"></span><a class="position-absolute top-50 start-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 ps-2" href="" id="chart-list-switcher"><em>Chart / List Switcher</em></a>');
				
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
						$('#presented-table-name').html('Expenses <span class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-13px py-1 pe-2" id="date-for-your-choice"></span><a class="position-absolute top-50 start-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 ps-2" href="" id="chart-list-switcher"><em>Chart / List Switcher</em></a>');
			
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

				if (Array.isArray(json) && json.length) {
					$('#noDataComment').html('');
					if (pieChart === undefined) {
						screenWidthPieChartHeight = createPieChart(false);
					}

					for (let i = 0; i < 18; i++) {
						if (i < numberOfIncomeOrExpenseCategories) {
							$('#th' + i).html(json[i][0]);
							$('#td' + i).html(json[i][1]); 
							checkIfPaddingIsAdded = $('#th' + i).hasClass("p-0");
						
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
				else {
					$('#noDataComment').html('Nothing to show');
					for (let i = 0; i < 18; i++) {
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
		}).fail(function(incomeOrExpenseData) {
			alert("Something went wrong. Error");
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
			}
		}).fail(function(balance) {
			alert("Something went wrong. Error");
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
			
				pieChart.data['labels'] = json['incomeCategories'];
				pieChart.data['datasets'][0]['backgroundColor'] = json['backgroundColorForPieChart'];
				pieChart.data['datasets'][0]['data'] = json['percentagePerCategory'];

				pieChart.update();
				
				if (Array.isArray(json['incomeCategories']) && json['incomeCategories'].length === 0) {
					$('#myChartDiv').css('height', 0);
				}
				else {
					recoverPieChartHeight();
				}		
			}
		}).fail(function(dataToUpdatePieChart) {
			alert("Something went wrong. Error");
		});
	}	

	function cleanList() {
		var typeOfDataReviewed = $('#presented-table-name').text().left(8).trim();
		$('#id-list').html("");
		$('#date-list').html("");
		$('#category-list').html("");
		$('#comment-list').html("");
		$('#amount-list').html("");
		
		if (typeOfDataReviewed == "Expenses") {
			$('#payment-list').html("");

			for (let i = 0; i < dataToDisplayList.length && i < 7; i++) {
				$("#th-id-" + i).addClass('p-0').html("");
				$("#td-date-" + i).addClass('p-0').html("");
				$("#td-category-" + i).addClass('p-0').html("");
				$("#td-payment-" + i).addClass('p-0').html("");
				$("#td-comment-" + i).addClass('p-0').html("");
				$("#td-amount-" + i).addClass('p-0').html("");
			}
		}
		else {
			for (let i = 0; i < dataToDisplayList.length && i < 7; i++) {
				$("#th-id-" + i).addClass('p-0').html("");
				$("#td-date-" + i).addClass('p-0').html("");
				$("#td-category-" + i).addClass('p-0').html("");
				$("#td-comment-" + i).addClass('p-0').html("");
				$("#td-amount-" + i).addClass('p-0').html("");
			}
		}
	}

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
		startDateValue =$("#startDate").val();  
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
	
		if (startDateValue == "" || endDateValue == "") { 
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
		
		if (endDateAsInteger >= startDateAsInteger && backgroundColorForEndDateInput == "rgb(255, 255, 255)" && backgroundColorForStartDateInput == "rgb(255, 255, 255)") {
			$("#errorMessage").addClass("font-green").removeClass("font-red").html("Correct");	
		}
		
		else if (endDateAsInteger >= startDateAsInteger && (backgroundColorForEndDateInput == "rgb(255, 128, 128)" || backgroundColorForStartDateInput == "rgb(255, 128, 128)")) {
			$("#startDate").removeAttr("style");
			$("#endDate").removeAttr("style");
			$("#errorMessage").addClass("font-green").removeClass("font-red").html("Now it is correct");	
		}

		return true;
	}

	function capitalizeFirstLetter(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

	function provideNumberOfCategories(arrayOfUserIncomesOrExpenses) {
		var arrayLength = arrayOfUserIncomesOrExpenses.length;
		var categories = [];
		if (Array.isArray(arrayOfUserIncomesOrExpenses) && arrayOfUserIncomesOrExpenses.length) {
			categories[0] = arrayOfUserIncomesOrExpenses[0]['category'];

			for (let i = 1; i < arrayLength; i++) {
				
				if($.inArray(arrayOfUserIncomesOrExpenses[i]['category'], categories) === -1) {
					categories.push(arrayOfUserIncomesOrExpenses[i]['category']);
				}
			}
		}

		return categories.length;
	}

	function getOnePage(expenseOrIncome, timePeriodSelectedByUser, isModal, startDateFromModal = '0', endDateFromModal = '0') {
		$.ajax({
			async: false,
			url: "/includes/firstPageOfList.php",   
			type: 'get',
			data: {
				'expenseOrIncome': expenseOrIncome,
				'timePeriod': timePeriodSelectedByUser, 
				'isModal': isModal,
				'startDateFromModal': startDateFromModal,
				'endDateFromModal': endDateFromModal
			}, 
			success: function(dataToUpdateFirstPage) {
				var json = JSON.parse(dataToUpdateFirstPage);
			
				if (Array.isArray(json) && json.length) {
					dataToDisplayList = json;
					var numberOfCategories = provideNumberOfCategories(json);
					var tableHeader = Object.keys(json[0]);
				
					pieChart.data['labels'] = [];
					pieChart.data.datasets[0]['backgroundColor'] = [];
					pieChart.data.datasets[0]['data'] = [];
					pieChart.update();
					$('#myChartDiv').css('height', 0);	
					$("#myChart").remove();

					for (let i = 0; i < numberOfCategories; i++) {
						$('#th' + i).html("");
						$('#td' + i).html(""); 
						$('#th' + i).addClass('p-0');
						$('#td' + i).addClass('p-0');
					}

					if (expenseOrIncome == "Expense") {
						if (!$('#payment-list').length) {
							$('#category-list').after('<th scope="col" class="p-0 fw-bolder" style="width:16%" id="payment-list"></th>');
							$('#category-list').css('width', '16%');
							$('#comment-list').css('width', '30%');
							$('#amount-list').css('width', '13%');
							
							var className = "";
							for (let i = 0; i < 7; i++) {
								className = 'td-payment-' + i;
								$("#td-category-" + i).after('<td id=' + className + ' class="p-0"></td>');
							}
						}
						$('#id-list').html(tableHeader[0].toUpperCase());
						$('#date-list').html(capitalizeFirstLetter(tableHeader[1]));
						$('#category-list').html(capitalizeFirstLetter(tableHeader[2]));
						$('#payment-list').html(capitalizeFirstLetter(tableHeader[3]));
						$('#comment-list').html(capitalizeFirstLetter(tableHeader[4]));
						$('#amount-list').html(capitalizeFirstLetter(tableHeader[5]));

						for (let i = 0; i < json.length && i < 7; i++) {
							$("#th-id-" + i).removeClass('p-0').html(json[i]['id']);
							$("#td-date-" + i).removeClass('p-0').html(json[i]['date']);
							$("#td-category-" + i).removeClass('p-0').html(json[i]['category']);
							$("#td-payment-" + i).removeClass('p-0').html(json[i]['payment']);
							$("#td-comment-" + i).removeClass('p-0').html(json[i]['comment']);
							$("#td-amount-" + i).removeClass('p-0').html(json[i]['amount']);
						}
					}
					else if (expenseOrIncome == "Income") {
						if ($('#payment-list').length) {
							
							for (let i = 0; i < 7; i++) {
								$("#td-payment-" + i).remove();
							}
							
							$('#payment-list').remove();

							$('#category-list').css('width', '19%');
							$('#comment-list').css('width', '40%');
							$('#amount-list').css('width', '16%');
						}

						$('#id-list').html(tableHeader[0].toUpperCase());
						$('#date-list').html(capitalizeFirstLetter(tableHeader[1]));
						$('#category-list').html(capitalizeFirstLetter(tableHeader[2]));
						$('#comment-list').html(capitalizeFirstLetter(tableHeader[3]));
						$('#amount-list').html(capitalizeFirstLetter(tableHeader[4]));

						for (let i = 0; i < json.length && i < 7; i++) {
							$("#th-id-" + i).removeClass('p-0').html(json[i]['id']);
							$("#td-date-" + i).removeClass('p-0').html(json[i]['date']);
							$("#td-category-" + i).removeClass('p-0').html(json[i]['category']);
							$("#td-comment-" + i).removeClass('p-0').html(json[i]['comment']);
							$("#td-amount-" + i).removeClass('p-0').html(json[i]['amount']);
						}	
					}
					
					if (json.length > 7) {
						$('#next-link').html('Next');
					}
				}
			}
		}).fail(function(dataToUpdateFirstPage) {
			alert("Something went wrong. Error");
		});
	}
	
	function updateInformationForNextPageInTheList(itemPositionOnTheList, numberOfItemsToBeUpdated, isExpenseList) {
		if (isExpenseList) {
			for (let i = 0, j = itemPositionOnTheList; i < numberOfItemsToBeUpdated; i++) {
				$("#th-id-" + i).html(dataToDisplayList[j]['id']);
				$("#td-date-" + i).html(dataToDisplayList[j]['date']);
				$("#td-category-" + i).html(dataToDisplayList[j]['category']);
				$("#td-payment-" + i).html(dataToDisplayList[j]['payment']);
				$("#td-comment-" + i).html(dataToDisplayList[j]['comment']);
				$("#td-amount-" + i).html(dataToDisplayList[j]['amount']);
				j++;
			}
		}
		else {
			for (let i = 0, j = itemPositionOnTheList; i < numberOfItemsToBeUpdated; i++) {
				$("#th-id-" + i).html(dataToDisplayList[j]['id']);
				$("#td-date-" + i).html(dataToDisplayList[j]['date']);
				$("#td-category-" + i).html(dataToDisplayList[j]['category']);
				$("#td-comment-" + i).html(dataToDisplayList[j]['comment']);
				$("#td-amount-" + i).html(dataToDisplayList[j]['amount']);
				j++;
			}
		}

		if (numberOfItemsToBeUpdated < 7 && isExpenseList) {
			for (let i = numberOfItemsToBeUpdated; i < 7; i++) {
				$("#th-id-" + i).addClass('p-0').html("");
				$("#td-date-" + i).addClass('p-0').html("");
				$("#td-category-" + i).addClass('p-0').html("");
				$("#td-payment-" + i).addClass('p-0').html("");
				$("#td-comment-" + i).addClass('p-0').html("");
				$("#td-amount-" + i).addClass('p-0').html("");
			}
		}
		else if (numberOfItemsToBeUpdated < 7) {
			for (let i = numberOfItemsToBeUpdated; i < 7; i++) {
				$("#th-id-" + i).addClass('p-0').html("");
				$("#td-date-" + i).addClass('p-0').html("");
				$("#td-category-" + i).addClass('p-0').html("");
				$("#td-comment-" + i).addClass('p-0').html("");
				$("#td-amount-" + i).addClass('p-0').html("");
			}
		}
	}

	function updateInformationForPreviousPageInTheList(itemPositionOnTheList, numberOfItemsToBeRestored, isExpenseList) {
		if (numberOfItemsToBeRestored > 0 && isExpenseList) {
			for (let i = 7 - numberOfItemsToBeRestored; i < 7; i++) {
				$("#th-id-" + i).removeClass('p-0');
				$("#td-date-" + i).removeClass('p-0');
				$("#td-category-" + i).removeClass('p-0');
				$("#td-payment-" + i).removeClass('p-0');
				$("#td-comment-" + i).removeClass('p-0');
				$("#td-amount-" + i).removeClass('p-0');
			}
		}
		else if (numberOfItemsToBeRestored > 0) {
			for (let i = 7 - numberOfItemsToBeRestored; i < 7; i++) {
				$("#th-id-" + i).removeClass('p-0');
				$("#td-date-" + i).removeClass('p-0');
				$("#td-category-" + i).removeClass('p-0');
				$("#td-comment-" + i).removeClass('p-0');
				$("#td-amount-" + i).removeClass('p-0');
			}
		}

		if (isExpenseList) {
			for (let i = 0, j = itemPositionOnTheList; i < 7; i++) {
				$("#th-id-" + i).html(dataToDisplayList[j]['id']);
				$("#td-date-" + i).html(dataToDisplayList[j]['date']);
				$("#td-category-" + i).html(dataToDisplayList[j]['category']);
				$("#td-payment-" + i).html(dataToDisplayList[j]['payment']);
				$("#td-comment-" + i).html(dataToDisplayList[j]['comment']);
				$("#td-amount-" + i).html(dataToDisplayList[j]['amount']);
				j++;
			}
		}
		else {
			for (let i = 0, j = itemPositionOnTheList; i < 7; i++) {
				$("#th-id-" + i).html(dataToDisplayList[j]['id']);
				$("#td-date-" + i).html(dataToDisplayList[j]['date']);
				$("#td-category-" + i).html(dataToDisplayList[j]['category']);
				$("#td-comment-" + i).html(dataToDisplayList[j]['comment']);
				$("#td-amount-" + i).html(dataToDisplayList[j]['amount']);
				j++;
			}
		}
	}

	function resizeFontsForPieChart() {
		if (pieChart !== undefined && pieChart !== null) {
			if (window.outerWidth >= 1200) {
				Chart.defaults.font.size = 13;
				if (pieChart.data['labels'].length <= 9) {
					pieChart.options['plugins']['legend']['display'] = true;
				}
				if (Array.isArray(pieChart.data['labels']) && pieChart.data['labels'].length) {	
					$('#myChartDiv').css('height',screenWidthPieChartHeight[1]);
				}
			}
			
			if (window.outerWidth >= 768 && window.outerWidth < 1200) {
				Chart.defaults.font.size = 12;
				if (pieChart.data['labels'].length <= 9) {
					pieChart.options['plugins']['legend']['display'] = true;
				}
				if (pieChart.data['labels'].length > 9 && window.outerWidth < 1000) {
					$('#myChartDiv').css('height',2*screenWidthPieChartHeight[1]/3);
				}
				if (Array.isArray(pieChart.data['labels']) && pieChart.data['labels'].length <= 9) {	
					$('#myChartDiv').css('height',screenWidthPieChartHeight[1]);
				}
				if (pieChart.data['labels'].length > 9 && window.outerWidth >= 1000) {	
					$('#myChartDiv').css('height',screenWidthPieChartHeight[1]);
				}
			}

			if (window.outerWidth < 768) {
				Chart.defaults.font.size = 11;
				pieChart.options['plugins']['legend']['display'] = false;
				if (Array.isArray(pieChart.data['labels']) && pieChart.data['labels'].length) {	
					$('#myChartDiv').css('height',screenWidthPieChartHeight[1]/2);
				}
			}

			pieChart.update();
		}
	}

	function provideScreenWidthAndPieChartHeight() {
			return [$(window).width(), $('#myChartDiv').height()];
	}

	
	
	$(document).ready(function(){

		$('#periodForBalanceSummary').change(function() { 
			var optionValueNew = getSelectedOptionFromDropDownList(this);
			var typeOfDataReviewed = $('#presented-table-name').text().left(8).trim();
			var isPieChartDisplayed = isElementEmpty($('#id-list'));

			if (!isPieChartDisplayed && optionValueNew != "4") {
				cleanList();
				createPieChart(false);
			}

			if (!isElementEmpty($('#next-link'))) {
				$('#next-link').html('');
			}

			if (!isElementEmpty($('#previous-link'))) {
				$('#previous-link').html('');
			}
		
			startDateValue ="0";
			endDateValue = "0";
		
			if (optionValueNew == "1" && typeOfDataReviewed== "Expenses") {
				switchIncomeExpenseSummary('expensesPresentation', 'isCurrentMonthDate', true, false, false);
				getTotalBalance('isCurrentMonthDate', false);
				updatePieChart(pieChart, 'Expense', 'isCurrentMonthDate', false);
				
			}
			else if (optionValueNew == "2" && typeOfDataReviewed== "Expenses") {
				switchIncomeExpenseSummary('expensesPresentation', 'isPreviousMonthDate', true, false, false);
				getTotalBalance('isPreviousMonthDate', false);
				updatePieChart(pieChart, 'Expense', 'isPreviousMonthDate', false);
			}
			else if (optionValueNew == "3" && typeOfDataReviewed== "Expenses") {
				switchIncomeExpenseSummary('expensesPresentation', 'isCurrentYearDate', true, false, false);
				getTotalBalance('isCurrentYearDate', false);
				updatePieChart(pieChart, 'Expense', 'isCurrentYearDate', false);
			}
			else if (optionValueNew == "1" && typeOfDataReviewed== "Incomes") {
				switchIncomeExpenseSummary('incomesPresentation', 'isCurrentMonthDate', true, false, false);
				getTotalBalance('isCurrentMonthDate', false);
				updatePieChart(pieChart, 'Income', 'isCurrentMonthDate', false);
			}
			else if (optionValueNew == "2" && typeOfDataReviewed== "Incomes") {
				switchIncomeExpenseSummary('incomesPresentation', 'isPreviousMonthDate', true, false, false);
				getTotalBalance('isPreviousMonthDate', false);
				updatePieChart(pieChart, 'Income', 'isPreviousMonthDate', false);
			}
			else if (optionValueNew == "3" && typeOfDataReviewed== "Incomes") {
				switchIncomeExpenseSummary('incomesPresentation', 'isCurrentYearDate', true, false, false);
				getTotalBalance('isCurrentYearDate', false);
				updatePieChart(pieChart, 'Income', 'isCurrentYearDate', false);
			}
		});

		$('#periodForBalanceSummary').click(function() {
			var currentOptionSelected = $(this).val();
			
			counterForClickEventRelease++;
			getFirstClickOption(this);
		
			if (currentOptionSelected == "4" && counterForClickEventRelease > 1) {
				$('#boxToProvidePeriodForBalanceSummary').modal("show");
				const disabledAttributeForButtonsInModal = [$("#closeModalSymbol").attr("disabled"), $("#closeModalButton").attr("disabled"), $("#submitModalButton").attr("disabled"), $("#startDate").attr("disabled"), $("#endDate").attr("disabled")];
		
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
			}
		});
		
		$("#closeModalSymbol").click(function() {
			$('#periodForBalanceSummary').val(firstOptionClicked);
		});
		
		$("#closeModalButton").click(function() {
			$('#periodForBalanceSummary').val(firstOptionClicked);  
		});

		$("#switcher-expenseLink-presentedInformation").on("click","#linkToPresentExpenses", function(e) {
			e.preventDefault();
			var selectedOptionFromDropDownList = getSelectedOptionFromDropDownList('#periodForBalanceSummary');
			var isPieChartDisplayed = isElementEmpty($('#id-list'));

			if (!isPieChartDisplayed) {
				cleanList();
				createPieChart(false);
			}

			if (!isElementEmpty($('#next-link'))) {
				$('#next-link').html('');
			}

			if (!isElementEmpty($('#previous-link'))) {
				$('#previous-link').html('');
			}

			if (selectedOptionFromDropDownList == "1") {
				switchIncomeExpenseSummary('expensesPresentation', 'isCurrentMonthDate', false, false, false);
				updatePieChart(pieChart, 'Expense', 'isCurrentMonthDate', false);
			}
			else if (selectedOptionFromDropDownList == "2") {
				switchIncomeExpenseSummary('expensesPresentation', 'isPreviousMonthDate', false, false, false);
				updatePieChart(pieChart, 'Expense', 'isPreviousMonthDate', false);
			}
			else if (selectedOptionFromDropDownList == "3") {
				switchIncomeExpenseSummary('expensesPresentation', 'isCurrentYearDate', false, false, false);
				updatePieChart(pieChart, 'Expense', 'isCurrentYearDate', false);
			}
			else if (selectedOptionFromDropDownList == "4") {
				switchIncomeExpenseSummary('expensesPresentation', 'isTimePeriodProvidedByUser', false, false, true, startDateValue, endDateValue);
				updatePieChart(pieChart, 'Expense', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
			}
		}); 

		$("#switcher-incomeLink-presentedInformation").on("click", "#linkToPresentIncomes", function(e) {
			e.preventDefault();
			var selectedOptionFromDropDownList = getSelectedOptionFromDropDownList('#periodForBalanceSummary');
			var isPieChartDisplayed = isElementEmpty($('#id-list'));

			if (!isPieChartDisplayed) {
				cleanList();
				createPieChart(false);
			}

			if (!isElementEmpty($('#next-link'))) {
				$('#next-link').html('');
			}

			if (!isElementEmpty($('#previous-link'))) {
				$('#previous-link').html('');
			}

			if (selectedOptionFromDropDownList == "1") {
				switchIncomeExpenseSummary('incomesPresentation', 'isCurrentMonthDate', false, false, false);
				updatePieChart(pieChart, 'Income', 'isCurrentMonthDate', false);
			}
			else if (selectedOptionFromDropDownList == "2") {
				switchIncomeExpenseSummary('incomesPresentation', 'isPreviousMonthDate', false, false, false);
				updatePieChart(pieChart, 'Income', 'isPreviousMonthDate', false);
			}
			else if (selectedOptionFromDropDownList == "3") {
				switchIncomeExpenseSummary('incomesPresentation', 'isCurrentYearDate', false, false, false);
				updatePieChart(pieChart, 'Income', 'isCurrentYearDate', false);
			}
			else if (selectedOptionFromDropDownList == "4") {
				switchIncomeExpenseSummary('incomesPresentation', 'isTimePeriodProvidedByUser', false, false, true, startDateValue, endDateValue);
				updatePieChart(pieChart, 'Income', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
			}
		});

		$("#presented-table-name").on("click","#chart-list-switcher", function(e) {
			e.preventDefault(); 
			var typeOfDataReviewed = $('#presented-table-name').text().left(8).trim();
			var selectedOptionFromDropDownList = getSelectedOptionFromDropDownList('#periodForBalanceSummary');
			var isPieChartDisplayed = isElementEmpty($('#id-list'));
			
			if (isPieChartDisplayed) {
				if (selectedOptionFromDropDownList == "1" && typeOfDataReviewed== "Expenses") {
					getOnePage('Expense', 'isCurrentMonthDate', false);
				}
				else if (selectedOptionFromDropDownList == "2" && typeOfDataReviewed== "Expenses") {
					getOnePage('Expense', 'isPreviousMonthDate', false);
				}
				else if (selectedOptionFromDropDownList == "3" && typeOfDataReviewed== "Expenses") {
					getOnePage('Expense', 'isCurrentYearDate', false);
				}
				else if (selectedOptionFromDropDownList == "4" && typeOfDataReviewed== "Expenses") {
					getOnePage('Expense', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
				}
				else if (selectedOptionFromDropDownList == "1" && typeOfDataReviewed== "Incomes") {
					getOnePage('Income', 'isCurrentMonthDate', false);
				}
				else if (selectedOptionFromDropDownList == "2" && typeOfDataReviewed== "Incomes") {
					getOnePage('Income', 'isPreviousMonthDate', false);
				}
				else if (selectedOptionFromDropDownList == "3" && typeOfDataReviewed== "Incomes") {
					getOnePage('Income', 'isCurrentYearDate', false);
				}
				else if (selectedOptionFromDropDownList == "4" && typeOfDataReviewed== "Incomes") {
					getOnePage('Income', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
				}
			}
			else {
				cleanList();
				createPieChart(false);

				if (!isElementEmpty($('#next-link'))) {
					$('#next-link').html('');
				}

				if (!isElementEmpty($('#previous-link'))) {
					$('#previous-link').html('');
				}

				if (selectedOptionFromDropDownList == "1" && typeOfDataReviewed== "Expenses") {
					switchIncomeExpenseSummary('expensesPresentation', 'isCurrentMonthDate', false, true, false);
					updatePieChart(pieChart, 'Expense', 'isCurrentMonthDate', false);
				}
				else if (selectedOptionFromDropDownList == "2" && typeOfDataReviewed== "Expenses") {
					switchIncomeExpenseSummary('expensesPresentation', 'isPreviousMonthDate', false, true, false);
					updatePieChart(pieChart, 'Expense', 'isPreviousMonthDate', false);
				}
				else if (selectedOptionFromDropDownList == "3" && typeOfDataReviewed== "Expenses") {
					switchIncomeExpenseSummary('expensesPresentation', 'isCurrentYearDate', false, true, false);
					updatePieChart(pieChart, 'Expense', 'isCurrentYearDate', false);
				}
				else if (selectedOptionFromDropDownList == "4" && typeOfDataReviewed== "Expenses") {
					switchIncomeExpenseSummary('expensesPresentation', 'isTimePeriodProvidedByUser', false, true, true, startDateValue, endDateValue);
					updatePieChart(pieChart, 'Expense', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
				}
				else if (selectedOptionFromDropDownList == "1" && typeOfDataReviewed== "Incomes") {
					switchIncomeExpenseSummary('incomesPresentation', 'isCurrentMonthDate', false, true, false);
					updatePieChart(pieChart, 'Income', 'isCurrentMonthDate', false);
				}
				else if (selectedOptionFromDropDownList == "2" && typeOfDataReviewed== "Incomes") {
					switchIncomeExpenseSummary('incomesPresentation', 'isPreviousMonthDate', false, true, false);
					updatePieChart(pieChart, 'Income', 'isPreviousMonthDate', false);
				}
				else if (selectedOptionFromDropDownList == "3" && typeOfDataReviewed== "Incomes") {
					switchIncomeExpenseSummary('incomesPresentation', 'isCurrentYearDate', false, true, false);
					updatePieChart(pieChart, 'Income', 'isCurrentYearDate', false);
				}
				else if (selectedOptionFromDropDownList == "4" && typeOfDataReviewed== "Incomes") {
					switchIncomeExpenseSummary('incomesPresentation', 'isTimePeriodProvidedByUser', false, true, true, startDateValue, endDateValue);
					updatePieChart(pieChart, 'Income', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
				}	
			}
		});

		$('#next-link').click(function(e) {
			e.preventDefault();
			var numberOfPages = Math.ceil(dataToDisplayList.length / 7);
			var firstIdOnThePage = $('#th-id-0').text();
			var indexOfFirstIdOnTheCurrentPageInArray = dataToDisplayList.indexOf(dataToDisplayList.find(function(obj) {return obj['id'] === firstIdOnThePage}));
			var indexOfFirstIdOnTheNextPage = indexOfFirstIdOnTheCurrentPageInArray + 7;
			var currentPageNumber = (indexOfFirstIdOnTheCurrentPageInArray / 7) + 1;
			var isExpenseList = ($('#presented-table-name').text().left(8).trim() === "Expenses") ? true : false;
			var numberOfAllItems = dataToDisplayList.length;
			
			if (currentPageNumber + 1 < numberOfPages) {
				updateInformationForNextPageInTheList(indexOfFirstIdOnTheNextPage, 7, isExpenseList);
			}
			else if (currentPageNumber + 1 == numberOfPages) {
				$('#next-link').html('');
				var numberOfItemsOnLastPage =  (numberOfAllItems - (numberOfPages - 1) * 7); 
				updateInformationForNextPageInTheList(indexOfFirstIdOnTheNextPage, numberOfItemsOnLastPage, isExpenseList);
			}

			if (isElementEmpty($('#previous-link'))) {
				$('#previous-link').html('Previous');
			}	
		});

		$('#previous-link').click(function(e) {
			e.preventDefault();
			var numberOfPages = Math.ceil(dataToDisplayList.length / 7);
			var firstIdOnThePage = $('#th-id-0').text();
			var indexOfFirstIdOnTheCurrentPageInArray = dataToDisplayList.indexOf(dataToDisplayList.find(function(obj) {return obj['id'] === firstIdOnThePage}));
			var indexOfFirstIdOnThePreviousdPage = indexOfFirstIdOnTheCurrentPageInArray - 7;
			var currentPageNumber = (indexOfFirstIdOnTheCurrentPageInArray / 7) + 1;
			var isExpenseList = ($('#presented-table-name').text().left(8).trim() === "Expenses") ? true : false;
			var numberOfAllItems = dataToDisplayList.length;

			if (currentPageNumber != numberOfPages) {
				updateInformationForPreviousPageInTheList(indexOfFirstIdOnThePreviousdPage, 0, isExpenseList);
			}
			else if (currentPageNumber == numberOfPages) {
				var numberOfItemsToBeRestored = (numberOfPages * 7) - numberOfAllItems;
				updateInformationForPreviousPageInTheList(indexOfFirstIdOnThePreviousdPage, numberOfItemsToBeRestored, isExpenseList);
				$('#next-link').html('Next');
			}

			if (currentPageNumber - 1 == 1) {
				$('#previous-link').html('');
			}
		});

		$("#submitModalButton").click(function () {
			if (checkDateInModal()) {
				$("#closeModalSymbol").attr("disabled", true);
				$("#closeModalButton").attr("disabled", true);
				$("#submitModalButton").attr("disabled", true);
				$("#startDate").attr("disabled", true);
				$("#endDate").attr("disabled", true);

				var isPieChartDisplayed = isElementEmpty($('#id-list'));
				var typeOfDataReviewed = $('#presented-table-name').text().left(8).trim();
			
				$('#boxToProvidePeriodForBalanceSummary').delay(2000).queue(function() { 
					if (!isPieChartDisplayed) {
						cleanList();
						createPieChart(false);
					}

					if (!isElementEmpty($('#next-link'))) {
						$('#next-link').html('');
					}

					if (!isElementEmpty($('#previous-link'))) {
						$('#previous-link').html('');
					}

					$(this).modal("hide");
	
					if (typeOfDataReviewed == "Expenses") {
						switchIncomeExpenseSummary('expensesPresentation', 'isTimePeriodProvidedByUser', true, false, true, startDateValue, endDateValue); 
						getTotalBalance('isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
						updatePieChart(pieChart, 'Expense', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
					}
					else if (typeOfDataReviewed == "Incomes") {
						switchIncomeExpenseSummary('incomesPresentation', 'isTimePeriodProvidedByUser', true, false, true, startDateValue, endDateValue);
						getTotalBalance('isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
						updatePieChart(pieChart, 'Income', 'isTimePeriodProvidedByUser', true, startDateValue, endDateValue);
					}
					
					$(this).dequeue(); 
				});
			}
		});

	$("#boxToProvidePeriodForBalanceSummary").on("hidden.bs.modal",clearModalBoxToDefault);

	});
	</script>

</body>
</html>