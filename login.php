<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
if (isset($_SESSION['loggedin'])) {
	header("location: event.php");

	exit;
}


require_once "config.php";

// Define variables and initialize with empty values
$code = $code_err = "";
$paid = $paid_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	// Check if code is empty
	if(empty(trim($_POST["code"]))){
		$code_err = "Please enter code.";
	} else{
		$code = trim($_POST["code"]);
	}

	// Validate credentials
	if(empty($code_err)){
		// Prepare a select statement
		$sql = "SELECT id, code FROM events WHERE code = ?";

		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_code);

			// Set parameters
			$param_code = $code;

			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				// Store result
				mysqli_stmt_store_result($stmt);

				// Check if code exists, if yes then verify password
				if(mysqli_stmt_num_rows($stmt) == 1){
					// Bind result variables
					mysqli_stmt_bind_result($stmt, $id, $code);
					if(mysqli_stmt_fetch($stmt)){
						// Password is correct, so start a new session
						session_start();

						// Store data in session variables
						$_SESSION["loggedin"] = true;
						$_SESSION["id"] = $id;
						$_SESSION["code"] = $code;
						$_SESSION["paid"] = $paid;

						// Redirect user to welcome page
						if ($paid == "1") {
							header("location: event.php");
						} else {
							$paid_err = "You have not paid for this event. Please contact us in messenger (m.me/princepiness) to pay.";
						}
					}
				} else{
					// Display an error message if code doesn't exist
					$code_err = "No account found with that code.";
				}
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}
		}

		// Close statement
		mysqli_stmt_close($stmt);
	}

	// Close connection
	mysqli_close($link);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>K4th Production - PPV Login</title>
	<link rel="stylesheet" href="style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<link rel="icon" type="image/x-icon" href="white.png">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col">
				<h1>Login to the event</h1>
				<p>Please fill in your code to login.</p>

				<?php
                if(!empty($login_err)){
                    echo '<div class="alert alert-danger">' . $code_err . '</div>';
                }

				if (!empty($paid_err)) {
					echo '<div class="alert alert-danger">' . $paid_err . '</div>';
				}
                ?>

				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<div class="form-group">
						<label>Code</label>
						<input type="text" name="code" class="form-control <?php echo (!empty($code_err)) ? 'is-invalid' : ''; ?>" value="<?php /*echo $code; */?>">
						<span class="invalid-feedback"><?php echo $code_err; ?></span>
					</div><br>
					<div class="form-group">
						<input type="submit" class="btn btn-primary" value="Login">
					</div><br>
					<p>Don't have a code?</p><br>
					<a href="register.php" class="btn btn-primary">Register</a>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
