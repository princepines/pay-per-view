<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
// initialize dotenv and variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$messenger_link= '<a href="'. $_ENV['MESSENGERLINK'] .'" target="_blank">'. $_ENV['MESSENGERLINK'] .'</a>';

// We need to use sessions, so you should always start sessions using the below code.
session_start();
if (isset($_SESSION['loggedin'])) {
	header("location: event.php");

	exit;
}

require "config.php";

// Define variables and initialize with empty values
$code = $code_err = "";
$paid_err = "";

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

		if($stmt = mysqli_prepare($mysqli, $sql)){
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
						// get paid status
						$sql2 = "SELECT paid FROM events WHERE code = '$code'";
						$result = mysqli_query($mysqli, $sql2);
						$row = mysqli_fetch_assoc($result);
						
						$sql3 = "SELECT device_once FROM events WHERE code = '$code'";
						$result2 = mysqli_query($mysqli, $sql3);
						$row2 = mysqli_fetch_assoc($result2);

						// Store data in session variables
						$_SESSION["loggedin"] = true;
						$_SESSION["id"] = $id;
						$_SESSION["code"] = $code;
						$_SESSION["paid"] = $row['paid'];
						$_SESSION["device_once"] = $row2['device_once'];
						$_SESSION['timestamp'] = time(); //set new timestamp

						// Redirect user to welcome page
						if ($_SESSION["paid"] == "1") {
							if($_SESSION["device_once"] == "1") {
								$_SESSION["loggedin"] = false;
								$_SESSION["id"] = "";
								$_SESSION["code"] = "";
								$_SESSION["paid"] = "";
								$_SESSION["device_once"] = "";
								session_abort();
								$paid_err = "You have already logged in. Please contact us in messenger (". $messenger_link .") to reset your code.";
							} else {
								$sql4 = "UPDATE events SET device_once = '1' WHERE code = '$code'";
								$result3 = mysqli_query($mysqli, $sql4);
								session_start();
								header("location: event.php");
							}
						} else {
							session_abort();
							$paid_err = "You have not paid for this event. Please contact us in messenger (". $messenger_link .") to pay.";
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
	mysqli_close($mysqli);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>K4th Production - PPV Login</title>
    <link rel="stylesheet" href="style.css?v=1.1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="icon" type="image/x-icon" href="white.png">
	<script type='text/javascript' src="restrict.js"></script>
</head>

<body>
<!-- Messenger Chat Plugin Code -->
<div id="fb-root"></div>

<!-- Your Chat Plugin code -->
<div id="fb-customer-chat" class="fb-customerchat">
</div>

<script>
  var chatbox = document.getElementById('fb-customer-chat');
  chatbox.setAttribute("page_id", "114546254986031");
  chatbox.setAttribute("attribution", "biz_inbox");
</script>

<!-- Your SDK code -->
<script>
  window.fbAsyncInit = function() {
	FB.init({
	  xfbml            : true,
	  version          : 'v17.0'
	});
  };

  (function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
	fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
    <div class="container">
        <div class="row g-0">
            <img src="poster.png" alt="K4th Production">
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
                        <input type="text" name="code"
                            class="form-control <?php echo (!empty($code_err)) ? 'is-invalid' : ''; ?>"
                            value="<?php /*echo $code; */?>">
                        <span class="invalid-feedback"><?php echo $code_err; ?></span>
                    </div><br>
                    <div class="form-group">
                        <input type="submit" class="btn btn-dark" value="Login">
                    </div><br>
                    <h4>Don't have a code?</h4>
                    <a href="register.php" class="btn btn-dark">Register</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>