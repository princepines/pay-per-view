<?php

// input validation before going to action.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["firstname"])) {
        $firstname_err = "First name is required";
    } else {
        $firstname = test_input($_POST["firstname"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z-' ]*$/", $firstname)) {
            $firstname_err = "Only letters and white space allowed";
        }
    }
    if (empty($_POST["lastname"])) {
        $lastname_err = "Last name is required";
    } else {
        $lastname = test_input($_POST["lastname"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lastname)) {
            $lastname_err = "Only letters and white space allowed";
        }
    }
    if (empty($_POST["email"])) {
        $email_err = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format";
        }
    }
    if (empty($_POST["phone"])) {
        $phone_err = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        // check if phone number is valid
        if (!preg_match("/^[0-9]*$/", $phone)) {
            $phone_err = "Only numbers allowed";
        }
    }

    // function to clean input data
function test_input($data)
{
    $data = trim($data); // Strip unnecessary characters (extra space, tab, newline)
    $data = stripslashes($data); // Remove backslashes (\)
    $data = htmlspecialchars($data); // converts special characters to HTML entities
    return $data;
}

// generate function for code
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, $charactersLength - 1)];
    }
    return $code;
}

// submit data to action.php
if (empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($phone_err)) {
     // Prepare an insert statement
     $sql = "INSERT INTO events (code, firstname, lastname, email, phone, paid) VALUES (?, ?, ?, ?, ?, ?)";

     if ($stmt = mysqli_prepare($mysqli, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssss", $param_code, $param_firstname, $param_lastname, $param_email, $param_phone, $param_paid);

        // Set parameters
        $param_code = generateRandomString();
        $param_firstname = $firstname;
        $param_lastname = $lastname;
        $param_email = $email;
        $param_phone = $phone;
        $param_paid = "0";

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to login page
            header("location: event.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }
     }

        // Close statement
        mysqli_stmt_close($stmt);
    }
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