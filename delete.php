<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Profile</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    $email = "";
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        //email
        if (empty($_GET["email"])) {
            $emailErr = "Email field is required";
        } else {
            $email = test_input($_GET["email"]);
        }
        $conn = new mysqli("localhost", "root", "password", "users");
        if ($conn->connect_error) {
            die("Connection failed!" . $conn->connect_error . "\n");
        }
        $command = "delete from users where email='$email'";

        if ($conn->query($command)) {
            echo "Record deleted successfully.";
        } else {
            echo "Error deleting record: " . $conn->error. "Command: $command";
        }
        $conn->close();
    }
    // strips data of random spaces
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>
</body>

</html>


<!-- mysql> create table users (
    -> userid int not null auto_increment primary key,
    -> first_name varchar(30),
    -> last_name varchar(30),
    -> email varchar(40),
    -> password varchar(40)); -->
