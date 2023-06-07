<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>About Us</title>
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
    <?php require 'nav.php';?>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>About this Project</h1>
                <p>This project is made by K4th Production, Princepines Programming Group for the TechSPARKS IBMLT PPV
                    event.</p>
                <p>It is made using PHP, MySQL, and HLS.js.</p>

                <h2>Key Contributor Peoples</h2>
                <ul>
                    <li>Frizth Lyco L. Tatierra <a href="https://github.com/Lycol50" target="_blank">(princepines)</a>
                    </li>
                </ul>
                <h3>Open-source Libraries Used</h3>
                <ul>
                    <li>PHP 8</li>
                    <li>MySQL</li>
                    <li>HLS.js</li>
                    <li>Bootstrap 5</li>
                    <li>Chatango</li>
                </ul>
                <h3>I am being hosted on Amazon EC2</h3>
            </div>
        </div>
    </div>
</body>

</html>