<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profile</title>
</head>
<body>
<?php
    $emailErr = "";
    $passwdErr = "";
    $email = "";
    $passwd = "";
    $loggedIn = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //email
        if (empty($_POST["email"])) {
            $emailErr = "Email field is required";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }
        //password
        if (empty($_POST["passwd"])) {
            $passwdErr = "Please enter a strong password";
        } else {
            $passwd = test_input($_POST["passwd"]);
        }

        $conn = new mysqli("localhost", "root", "password", "users");
        if ($conn->connect_error) {
            die("Connection failed!" . $conn->connect_error . "\n");
        }
        $command = "select * from users where email='$email'";

        if (strlen($emailErr) + strlen($passwdErr) == 0) {
            $result = $conn->query($command);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    if($row["password"] == $passwd){
                        $id = $row['id'];
                        $loggedIn = true;
                        $fname = $row['first_name'];
                        $lname = $row['last_name'];
                        echo "<h1 class='greeting'>Hello, $fname</h1>";
                        echo "<div class='profilesection'><h1>User: $fname $lname</h1><h3>Your email is: $email</h3><br>";
                        echo "<a onClick=\"javascript: return confirm('Please confirm deletion');\" href='delete.php?email=$email'>Delete profile</a><br>";
                        echo "<a href='update.php?id=$id'>Update profile</a><br>";
                        echo "<a href='login.php'>Logout</a></div>";
                    }
                    else{
                        echo "Incorrect credentials";
                    }
                }
            } else {
                printf("No user found");
            }
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
