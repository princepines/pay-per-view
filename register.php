<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
// initialize dotenv and variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$messenger_link= '<a href="'. $_ENV['MESSENGERLINK'] .'" target="_blank">'. $_ENV['MESSENGERLINK'] .'</a>';

//config
require 'config.php';

$firstname_err = $lastname_err = $email_err = $phone_err = "";
$code_confirm = $instructions = "";
$grade_course_err = $school_err = "";
$parent_name_err = $parent_phone_err = "";

// function to clean input data
function test_input($data)
{
    $data = trim($data); // Strip unnecessary characters (extra space, tab, newline)
    $data = stripslashes($data); // Remove backslashes (\)
    $data = htmlspecialchars($data); // converts special characters to HTML entities
    return $data;
}

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
    if (empty($_POST["course"])) {
        $grade_course_err = "Preffered Strand/course is required";
    } else {
        $grade_course = test_input($_POST["course"]);
        // check if grade level and strand/course is valid
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $grade_course)) {
            $grade_course_err = "Only letters, numbers and white space allowed";
        }
    }
    if (empty($_POST["school"])) {
        $school_err = "School is required";
    } else {
        $school = test_input($_POST["school"]);
        // check if school is valid
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $school)) {
            $school_err = "Only letters, numbers and white space allowed";
        }
    }

    if (empty($_POST["parent_name"])) {
        $parent_name_err = "Parent/Guardian's name is required";
    } else {
        $parent_name = test_input($_POST["parent_name"]);
        // check if parent name is valid
        if (!preg_match("/^[a-zA-Z-' ]*$/", $parent_name)) {
            $parent_name_err = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["parent_phone"])) {
        $parent_phone_err = "Parent/Guardian's contact number is required";
    } else {
        $parent_phone = test_input($_POST["parent_phone"]);
        // check if parent phone number is valid
        if (!preg_match("/^[0-9]*$/", $parent_phone)) {
            $parent_phone_err = "Only numbers allowed";
        }
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
     $sql = "INSERT INTO events (code, firstname, lastname, email, phone, paid, device_once, pref_course, school, parent_name, parent_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

     if ($stmt = mysqli_prepare($mysqli, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssssssssss", $param_code, $param_firstname, $param_lastname, $param_email, $param_phone, $param_paid, $param_device_once, $param_grade_course, $param_school, $param_parent_name, $param_parent_phone);

        // Set parameters
        $param_code = generateRandomString();
        $param_firstname = $firstname;
        $param_lastname = $lastname;
        $param_email = $email;
        $param_phone = $phone;
        $param_paid = "0";
        $param_device_once = "0";
        $param_grade_course = $grade_course;
        $param_school = $school;
        $param_parent_name = $parent_name;
        $param_parent_phone = $parent_phone;

        

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to login page
            $code_confirm = "Thank you for registering, your code is " . $param_code . ". Please follow the instructions below.";
            $instructions = "Please pay 30php to ". $_ENV['GCASHNUM'] ." (". $_ENV['GCASHNAME'] .") [GCash] and send a screenshot to ". $messenger_link .", with your code and proof of payment, and your code will be activated.";
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
        <div class="row">
            <div class="col">
                <h1>Register for Pay-Per-View</h1>
                <p>Please fill this form to create an account.</p>

                <?php
                if (!empty($firstname_err)) {
                    echo '<div class="alert alert-danger">' . $firstname_err . '</div>';
                }

                if (!empty($lastname_err)) {
                    echo '<div class="alert alert-danger">' . $lastname_err . '</div>';
                }

                if (!empty($email_err)) {
                    echo '<div class="alert alert-danger">' . $email_err . '</div>';
                }

                if (!empty($phone_err)) {
                    echo '<div class="alert alert-danger">' . $phone_err . '</div>';
                }

                if (!empty($code_confirm)) {
                    echo '<div class="alert alert-success">' . $code_confirm . '</div>';
                }

                if (!empty($instructions)) {
                    echo '<div class="alert alert-info">' . $instructions . '</div>';
                }
                ?>

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
                        <label> Your Contact Number</label>
                        <input type="text" name="phone" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label>Full Name of School</label>
                        <input type="text" name="school" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label>Preffered Strand/Course in Informatics Northgate</label>
                        <input type="text" name="course" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label>Parent/Guardian's Name</label>
                        <input type="text" name="parent_name" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label>Parent/Guardian's Contact Number</label>
                        <input type="text" name="parent_phone" class="form-control ">
                    </div>
                    <br>
                    <div class="form-group">
                        <input type="submit" class="btn btn-dark" value="Submit">
                        <input type="reset" class="btn btn-secondary ml-2" value="Reset">
                        <button type="button" class="btn btn-secondary ml-2" onclick="window.location.href='login.php'">Go back to Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>