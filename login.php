<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
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
            $passwdErr = "Please enter the password";
        } else {
            $passwd = test_input($_POST["passwd"]);
        }

        $conn = new mysqli("localhost", "root", "password", "users");
        if ($conn->connect_error) {
            die("Connection failed!" . $conn->connect_error . "\n");
        }
        $command = "select password from users where email='$email'";

        if (strlen($emailErr) + strlen($passwdErr) == 0) {
            $result = $conn->query($command);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    if($row["password"] == $passwd){
                        $loggedIn = true;
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
    <h1>User Login</h1>
    Go to <a href="index.php">Registration</a> page
    <form action="/account.php" method="post" class="form">
        <div class="form-section">
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
            <span class="error"><?php echo $emailErr ?></span>
        </div>
        <div class="form-section">
            <label for="passwd">Password</label>
            <input type="password" id="passwd" name="passwd">
            <span class="error"><?php echo $passwdErr ?></span>
        </div>
        <input type="submit" value="Login" class="submitbtn">
    </form>
    <?php
    if (strlen($emailErr) + strlen($passwdErr) == 0 && $loggedIn) {
        echo "Login successful";
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
