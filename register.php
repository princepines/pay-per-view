<?php

require_once "config.php";

// Define variables and initialize with empty values
$firstname = $lastname = $email = $phone = "";
$firstname_err = $lastname_err = $email_err = $phone_err = "";
$code = $code_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate firstname
    if(empty(trim($_POST["firstname"]))){
        $firstname_err = "Please enter a firstname.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM events WHERE firstname = ?";

        if($stmt = mysqli_prepare($mysqli, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_firstname);

            // Set parameters
            $param_firstname = trim($_POST["firstname"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $firstname_err = "This firstname is already taken.";
                } else{
                    $firstname = trim($_POST["firstname"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate lastname
    if(empty(trim($_POST["lastname"]))){
        $lastname_err = "Please enter a lastname.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM events WHERE lastname = ?";

        if($stmt = mysqli_prepare($mysqli, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_lastname);

            // Set parameters
            $param_lastname = trim($_POST["lastname"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $lastname_err = "This lastname is already taken.";
                } else{
                    $lastname = trim($_POST["lastname"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM events WHERE email = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            // Set parameters
            $param_email = trim($_POST["email"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }

    // create function for the code generator
    function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        // $randomString = '';
        // for ($i = 0; $i < $length; $i++) {
        //     $randomString .= $characters[rand(0, $charactersLength - 1)];
        // }
        // return $randomString;
        return substr(str_shuffle($characters), 0, $length);
    }

    // store the code in variable and databse using mysqli_stmt_store_result
    $code = generateRandomString();
    $sql = "SELECT id FROM events WHERE code = ?";
    if($stmt = mysqli_prepare($mysqli, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_code);
        // Set parameters
        $param_code = $code;
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                //regenerate code
                $code = generateRandomString();
            } else{
                $code_err = "You have been registered. Your code is: " . $code;
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }


    // Validate phone
    if(empty(trim($_POST["phone"]))){
        $phone_err = "Please enter a phone.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM events WHERE phone = ?";
        if($stmt = mysqli_prepare($mysqli, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_phone);
            // Set parameters
            $param_phone = trim($_POST["phone"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $phone_err = "This phone is already taken.";
                } else{
                    $phone = trim($_POST["phone"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }

    // set code as non-paid table
    $sql = "UPDATE events SET paid = '0' WHERE code = '$code'";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>K4th Production - PPV Register</title>
	<link rel="stylesheet" href="style.css?v=1.1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="icon" type="image/x-icon" href="white.png">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Register for Pay-Per-View</h1>
                <p>Please fill this form to create an account.</p>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="firstname" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-control ">
                    </div><br>
                    <div class="form-group">
                        <input type="submit" class="btn btn-dark" value="Submit">
                        <input type="reset" class="btn btn-secondary ml-2" value="Reset">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>