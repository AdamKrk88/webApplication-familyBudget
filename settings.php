<?php
require 'includes/autoloader.php';
session_start();
//Authorization::checkAuthorization();

if (!isset($_POST['name']) && !isset($_POST['email']) && !isset($_POST['password'])) {
	$_SESSION['successMessage'] = [];
	unset($_SESSION['successMessage']);
}

$customizeQueryStringValue = $_GET['customize'] ?? false;

if ($customizeQueryStringValue) {
	$allowedCustomizeOptions = ["User", "Expense", "Income"];
	$isAllowedCustomizePresent = in_array($customizeQueryStringValue, $allowedCustomizeOptions, TRUE);
	if ($isAllowedCustomizePresent) {
	//	$successMessage = ["Name changed", "Email changed", "Password changed"];
		switch ($customizeQueryStringValue) {
			case "User":
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					$user = new User();
					$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
					$connection = $database->getConnectionToDatabase();
					
					if (isset($_POST['name'])) {
						$user->name = $_POST['name'];
						if ($user->validateName()) {
							try {
								$sql = "UPDATE user
										SET name = :name
										WHERE id = :id";
								$stmt = $connection->prepare($sql);
								$stmt->bindValue(':name', $user->name, PDO::PARAM_STR);
								$stmt->bindValue(':id', $_SESSION['userId'], PDO::PARAM_INT);
								$stmt->execute();

								$_SESSION['successMessage'][0] = 'Name changed';
							}
							catch(PDOException $e) {
								echo $e->getMessage();
								exit;
							}
							//var_dump(strlen($_POST['name']));
							//username change
						}
					}

					elseif (isset($_POST['email'])) {
						$user->email = $_POST['email'];
						if ($user->validateEmail()) {
							try {
								$sql = "UPDATE user
										SET email = :email
										WHERE id = :id";
								$stmt = $connection->prepare($sql);
								$stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
								$stmt->bindValue(':id', $_SESSION['userId'], PDO::PARAM_INT);
								$stmt->execute();

								$_SESSION['successMessage'][1] = 'Email changed';
							}
							catch(PDOException $e) {
								echo $e->getMessage();
								exit;
							}
						}
					}

					elseif (isset($_POST['password'])) {
						$user->password = $_POST['password'];
						if ($user->validatePassword()) {
							$user->password = password_hash($user->password, PASSWORD_DEFAULT);
							try {
								$sql = "UPDATE user
										SET password = :password
										WHERE id = :id";
								$stmt = $connection->prepare($sql);
								$stmt->bindValue(':password', $user->password, PDO::PARAM_STR);
								$stmt->bindValue(':id', $_SESSION['userId'], PDO::PARAM_INT);
								$stmt->execute();

								$_SESSION['successMessage'][2] = 'Password changed';
							}
							catch(PDOException $e) {
								echo $e->getMessage();
								exit;
							}
						}
					}

					//	$_SESSION['userId']
				}
				break;
			case "Expense":
				$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
				$connection = $database->getConnectionToDatabase();
				$categories = Expense::getCategories($connection, $_SESSION['userId']);
				$payments = Expense::getPayments($connection, $_SESSION['userId']);
				$allExpenses = Expense::getAllExpenses($connection, $_SESSION['userId']);
				break;
			case "Income":
				$database = new Database(DB_HOST,DB_NAME,DB_USER,DB_PASS);
				$connection = $database->getConnectionToDatabase();
				$categories = Income::getCategories($connection, $_SESSION['userId']);
				$allIncomes = Income::getAllIncomes($connection, $_SESSION['userId']);
				break;
		}
	}
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
								<h2 class="font-color-black fw-bolder font-size-scaled-from-30px position-relative m-0 py-1">User
									<a class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 pe-2 fst-italic" href="settings.php">Back</a>
									<p class="position-absolute top-50 start-0 translate-middle-y font-size-scaled-from-15px font-orange py-1 ps-2" id="messageForUser"><?php 
											if (isset($_POST['name']) || isset($_POST['email']) || isset($_POST['password'])) { 
												if (!empty($user->errors) && !isset($_SESSION['successMessage'])) {
													echo $user->errors[0];
												}
												elseif (!empty($user->errors) && isset($_SESSION['successMessage'])) {
													echo $user->errors[0] . ". ";
												}	
												else {
													if (isset($_POST['name'])) {
														echo $_SESSION['successMessage'][0] . ". ";
													}
													elseif (isset($_POST['email'])) {
														echo $_SESSION['successMessage'][1] . ". ";
													}
													elseif (isset($_POST['password'])) {
														echo $_SESSION['successMessage'][2] . ". ";
													}
												}
											
										?><a class="link-registration-income-expense font-light-orange fst-italic" href="settings.php?customize=User"><?php if (isset($_SESSION['successMessage'])) {echo "Reload";} ?></a>
										<?php } ?>
									</p>
								</h2>
								<div class="underline"></div>	
								
								<form class="lh-1 bg-medium-light-grey highlight-option" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="name-change">Name</label>
										</div>
										<div class="col-6 py-1">
											<?php if (!isset($_SESSION['successMessage'][0])): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey" type="text" name="name" id="name-change" title="Please fill out to change username" aria-label="username change" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<?php elseif (isset($_SESSION['successMessage'][0])): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey" type="text" aria-label="username change" disabled />	
											<?php endif; ?>
										</div>
										<div class="col-3 py-1">
											<?php if (!isset($_SESSION['successMessage'][0])): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" id="nameChangeButton" type="submit" aria-label="Name change button">Change</button>	
											<?php elseif (isset($_SESSION['successMessage'][0])): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Name change button" disabled>Change</button>	
											<?php endif; ?>
										</div>
									</div>
								</form> 

								<form class="lh-1 highlight-option" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="email-change">Email</label>
										</div>
										<div class="col-6 py-1">
											<?php if (!isset($_SESSION['successMessage'][1])): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey" type="email" name="email" id="email-change" title="Please fill out to change email" aria-label="email change" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<?php elseif (isset($_SESSION['successMessage'][1])): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey" type="email" aria-label="email change" disabled />
											<?php endif; ?>
										</div>
										<div class="col-3 py-1">
											<?php if (!isset($_SESSION['successMessage'][1])): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" id="emailChangeButton" type="submit" aria-label="Email change button">Change</button>	
											<?php elseif (isset($_SESSION['successMessage'][1])): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Email change button" disabled>Change</button>
											<?php endif; ?>
										</div>
									</div>
								</form> 

								<form class="lh-1 bg-medium-light-grey highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="password-change">Password</label>
										</div>
										<div class="col-6 py-1">
											<?php if (!isset($_SESSION['successMessage'][2])): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey" type="password" name="password" id="password-change" title="Please fill out to change password" aria-label="password change" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<?php elseif (isset($_SESSION['successMessage'][2])): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey" type="password" aria-label="password change" disabled/>
											<?php endif; ?>
										</div>
										<div class="col-3 py-1">
											<?php if (!isset($_SESSION['successMessage'][2])): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" id="passwordChangeButton" type="submit" aria-label="Password change button">Change</button>	
											<?php elseif (isset($_SESSION['successMessage'][2])): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Password change button" disabled>Change</button>
											<?php endif; ?>
										</div>
									</div>
								</form>
								<?php if (!isset($_SESSION['successMessage'][2])): ?>
								<div class="position-relative">
									<p class="form-text text-muted font-size-scaled-from-13px fst-italic position-absolute">Password must contain at least one uppercase letter, one lowercase letter, one number and one special character. Length at least 10 characters</p>
								</div>
								<?php endif; ?>
								<?php elseif ($isAllowedCustomizePresent && $customizeQueryStringValue === "Expense"): ?>
								<h2 class="font-color-black fw-bolder font-size-scaled-from-30px position-relative m-0 py-1">Expense<a class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 pe-2 fst-italic" href="settings.php">Back</a></h2>
								<div class="underline"></div>	
							
								<form class="lh-1 bg-medium-light-grey highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="add-category-expense">Category</label>
										</div>
										<div class="col-6 py-1">
											<input class="form-control form-control-sm fw-bold font-color-grey w-50 mx-auto" type="text" name="add-category-expense" id="add-category-expense" title="Please fill out to add category" aria-label="add category for expense" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
										</div>
										<div class="col-3 py-1">
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to add category for expense">Add to the list</button>	
										</div>
									</div>
								</form> 

								<form class="lh-1 highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="remove-category-expense">Category</label>
										</div>
										<div class="col-6 py-1">	
											<?php if (!empty($categories)): ?>
											<select class="form-select form-select-sm w-auto d-inline-block fw-bold font-color-grey text-center" id="remove-category-expense" name="remove-category-expense" aria-label="Category options that can be removed">
												<?php foreach ($categories as $category): ?>																				
												<option value="<?= $category['category']; ?>"><?= $category['category']; ?></option>
												<?php endforeach; ?>										
											</select>
											<?php else: ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No categories available</p>
											<?php endif; ?>
										</div>
										<div class="col-3 py-1">
											<?php if (!empty($categories)): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to remove category for expense">Remove from the list</button>	
											<?php else: ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to remove category for expense" disabled>Remove from the list</button>	
											<?php endif; ?>
										</div>
									</div>
								</form> 

								<form class="lh-1 bg-medium-light-grey highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="add-payment-expense">Payment</label>
										</div>
										<div class="col-6 py-1">
											<input class="form-control form-control-sm fw-bold font-color-grey w-50 mx-auto" type="text" name="add-payment-expense" id="add-payment-expense" title="Please fill out to add payment method" aria-label="add payment option for expense" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
										</div>
										<div class="col-3 py-1">
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to add payment option for expense">Add to the list</button>	
										</div>
									</div>
								</form> 

								<form class="lh-1 highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="remove-payment-expense">Payment</label>
										</div>
										<div class="col-6 py-1">	
											<?php if (!empty($payments)): ?>
											<select class="form-select form-select-sm w-auto d-inline-block fw-bold font-color-grey text-center" id="remove-payment-expense" name="remove-payment-expense" aria-label="Payment options that can be removed">
												<?php foreach ($payments as $payment): ?>
												<option value="<?= $payment['payment']; ?>"><?= $payment['payment']; ?></option>
												<?php endforeach; ?>									
											</select>
											<?php else: ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No payments method available</p>
											<?php endif; ?>
										</div>
										<div class="col-3 py-1">
											<?php if (!empty($payments)): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to remove payment option for expense">Remove from the list</button>	
											<?php else: ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to remove payment option for expense" disabled>Remove from the list</button>	
											<?php endif; ?>
										</div>
									</div>
								</form>

								<form class="lh-1 bg-medium-light-grey highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="remove-expense">ID</label>
										</div>
										<div class="col-6 py-1">
											<?php if (!empty($allExpenses)): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey w-25 mx-auto" type="text" name="remove-expense" id="remove-expense" title="Please fill out to remove expense" aria-label="remove expense" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<?php else: ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No expense registered</p>
											<?php endif; ?>
										</div>
										<div class="col-3 py-1">
											<?php if (!empty($allExpenses)): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to remove expense">Remove expense</button>	
											<?php else: ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to remove expense" disabled>Remove expense</button>		
											<?php endif; ?>
										</div>
									</div>
								</form> 

								<form class="lh-1 highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder d-block py-2 h-100 mb-2" for="edit-expense-id-comment">ID</label>
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder d-block py-2 h-100" for="edit-expense-comment">Comment</label>
										</div>
										<div class="col-6 py-1">
											<?php if (!empty($allExpenses)): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey w-25 mx-auto mb-2" type="text" name="edit-expense-id-comment" id="edit-expense-id-comment" title="Please fill out to edit expense" aria-label="ID of expense to be edited" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<input class="form-control form-control-sm fw-bold font-color-grey" type="text" name="edit-expense-comment" id="edit-expense-comment" title="Please fill out to edit expense" aria-label="Update of comment for expense" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<?php else: ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No expense registered</p>
											<?php endif; ?>
										</div>
										<div class="col-3 py-1 align-self-stretch">
											<?php if (!empty($allExpenses)): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to edit expense with the comment">Edit comment for expense</button>	
											<?php else: ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to edit expense with the comment" disabled>Edit comment for expense</button>	
											<?php endif; ?>
										</div>
									</div>
								</form> 

								<form class="lh-1 bg-medium-light-grey highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder d-block py-2 h-100 mb-2" for="edit-expense-id-category">ID</label>
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder d-block py-2 h-100" for="edit-expense-category">Category</label>
										</div>
										<div class="col-6 py-1">
											<?php if (!empty($categories) && !empty($allExpenses)): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey w-25 mx-auto mb-2" type="text" name="edit-expense-id-category" id="edit-expense-id-category" title="Please fill out to edit expense" aria-label="ID of expense to be edited" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<select class="form-select form-select-sm w-auto d-inline-block fw-bold font-color-grey text-center" id="edit-expense-category" name="edit-expense-category" aria-label="Category to be updated for expense">
												<?php foreach ($categories as $category): ?>																				
												<option value="<?= $category['category']; ?>"><?= $category['category']; ?></option>
												<?php endforeach; ?>										
											</select>
											<?php elseif (empty($allExpenses)): ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No expense registered</p>
											<?php else: ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No categories available</p>
											<?php endif; ?>
										</div>
										<div class="col-3 py-1 align-self-stretch">
											<?php if (!empty($categories) && !empty($allExpenses)): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to edit expense with the category">Edit category for expense</button>	
											<?php else: ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to edit expense with the category" disabled>Edit category for expense</button>	
											<?php endif; ?>
										</div>
									</div>
								</form> 

								<?php elseif ($isAllowedCustomizePresent && $customizeQueryStringValue === "Income"): ?>
								<h2 class="font-color-black fw-bolder font-size-scaled-from-30px position-relative m-0 py-1">Income<a class="position-absolute top-50 end-0 translate-middle-y font-size-scaled-from-15px link-registration-income-expense font-color-black py-1 pe-2 fst-italic" href="settings.php">Back</a></h2>
								<div class="underline"></div>	

								<form class="lh-1 bg-medium-light-grey highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="add-category-income">Category</label>
										</div>
										<div class="col-6 py-1">
											<input class="form-control form-control-sm fw-bold font-color-grey w-50 mx-auto" type="text" name="add-category-income" id="add-category-income" title="Please fill out to add category" aria-label="add category for income" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
										</div>
										<div class="col-3 py-1">
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to add category for income">Add to the list</button>	
										</div>
									</div>
								</form> 

								<form class="lh-1 highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="remove-category-income">Category</label>
										</div>
										<div class="col-6 py-1">
											<?php if (!empty($categories)): ?>
											<select class="form-select form-select-sm w-auto d-inline-block fw-bold font-color-grey text-center" id="remove-category-income" name="remove-category-income" aria-label="Category options that can be removed">
												<?php foreach ($categories as $category): ?>																				
												<option value="<?= $category['category']; ?>"><?= $category['category']; ?></option>
												<?php endforeach; ?>										
											</select>
											<?php else: ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No categories available</p>
											<?php endif; ?>
										</div>
										<div class="col-3 py-1">
											<?php if (!empty($categories)): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to remove category for income">Remove from the list</button>	
											<?php else: ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to remove category for income" disabled>Remove from the list</button>		
											<?php endif; ?>
										</div>
									</div>
								</form> 

								<form class="lh-1 bg-medium-light-grey highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder py-2 h-100" for="remove-income">ID</label>
										</div>
										<div class="col-6 py-1">
											<?php if (!empty($allIncomes)): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey w-25 mx-auto" type="text" name="remove-income" id="remove-income" title="Please fill out to remove income" aria-label="remove income" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<?php else: ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No income registered</p>
											<?php endif; ?>
										</div>
										<div class="col-3 py-1">
											<?php if (!empty($allIncomes)): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to remove income">Remove income</button>	
											<?php else: ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to remove income" disabled>Remove income</button>		
											<?php endif; ?>
										</div>
									</div>
								</form> 

								<form class="lh-1 highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder d-block py-2 h-100 mb-2" for="edit-income-id-comment">ID</label>
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder d-block py-2 h-100" for="edit-income-comment">Comment</label>
										</div>
										<div class="col-6 py-1">
											<?php if (!empty($allIncomes)): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey w-25 mx-auto mb-2" type="text" name="edit-income-id-comment" id="edit-income-id-comment" title="Please fill out to edit income" aria-label="ID of income to be edited" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<input class="form-control form-control-sm fw-bold font-color-grey" type="text" name="edit-income-comment" id="edit-income-comment" title="Please fill out to edit income" aria-label="Update of comment for income" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<?php else: ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No income registered</p>
											<?php endif; ?>
										</div>
										<div class="col-3 py-1 align-self-stretch">
											<?php if (!empty($allIncomes)): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to edit income with the comment">Edit comment for income</button>	
											<?php else: ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to edit income with the comment" disabled>Edit comment for income</button>
											<?php endif; ?>
										</div>
									</div>
								</form> 

								<form class="lh-1 bg-medium-light-grey highlight-option" action="" method="post">	
									<div class="form-group row align-items-center" style="font-size: 1rem;">
										<div class="col-3 py-1">
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder d-block py-2 h-100 mb-2" for="edit-income-id-category">ID</label>
											<label class="font-color-grey font-size-scaled-from-15px fw-bolder d-block py-2 h-100" for="edit-income-category">Category</label>
										</div>
										<div class="col-6 py-1">
											<?php if (!empty($categories) && !empty($allIncomes)): ?>
											<input class="form-control form-control-sm fw-bold font-color-grey w-25 mx-auto mb-2" type="text" name="edit-income-id-category" id="edit-income-id-category" title="Please fill out to edit income" aria-label="ID of income to be edited" required oninvalid="this.setCustomValidity('Please fill out this field')" oninput="this.setCustomValidity('')" />
											<select class="form-select form-select-sm w-auto d-inline-block fw-bold font-color-grey text-center" id="edit-income-category" name="edit-income-category" aria-label="Category to be updated for income">
												<?php foreach ($categories as $category): ?>																				
												<option value="<?= $category['category']; ?>"><?= $category['category']; ?></option>
												<?php endforeach; ?>										
											</select>
											<?php elseif (empty($allIncomes)): ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No income registered</p>
											<?php else: ?>
											<p class="text-center font-orange font-size-scaled-from-15px mb-0">No categories available</p>
											<?php endif; ?>
										</div>
										<div class="col-3 py-1 align-self-stretch">
											<?php if (!empty($categories) && !empty($allIncomes)): ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to edit income with the category">Edit category for income</button>	
											<?php else: ?>
											<button class="btn button-grey-color fw-bold font-size-scaled-from-15px lh-1 px-2 h-100 w-95" type="submit" aria-label="Button to edit income with the category" disabled>Edit category for income</button>
											<?php endif; ?>
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

	<script>
	
	function isElementEmpty(element) {
      return !$.trim(element.html());
  	}

	$(document).ready(function(){
		$("#nameChangeButton").click(function() {
			if (!isElementEmpty($("#messageForUser"))) {
			$("#messageForUser").html('');
			}
		});
		
		$("#emailChangeButton").click(function() {
			if (!isElementEmpty($("#messageForUser"))) {
			$("#messageForUser").html('');
			}
		});

		$("#passwordChangeButton").click(function() {
			if (!isElementEmpty($("#messageForUser"))) {
			$("#messageForUser").html('');
			}
		});
	
	});   

	</script>
</body>
</html>