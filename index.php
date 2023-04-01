<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  $fnameErr = "";
  $lnameErr = "";
  $emailErr = "";
  $passwdErr = "";
  $cnfpwdErr = "";
  $email = "";
  $fname = "";
  $lname = "";
  $passwd = "";
  $cnfpwd = "";
  $loggedIn = false;
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //first name
    if (empty($_POST["fname"])) {
      $fnameErr = "First Name Field is required";
    } else {
      $fname = test_input($_POST["fname"]);
      if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
        $fnameErr = "Only letters and white space allowed";
      }
    }
    //last name
    if (empty($_POST["lname"])) {
      $lnameErr = "Last Name Field is required";
    } else {
      $lname = test_input($_POST["lname"]);
      if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
        $lnameErr = "Only letters and white space allowed";
      }
    }
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
    if (strlen($_POST["passwd"])<=3) {
      $passwdErr = "Please enter a strong password";
    } else {
      $passwd = test_input($_POST["passwd"]);
    }
    //confirm password
    if ($_POST["passwd"] != $_POST["cnfpwd"]) {
      $cnfpwdErr = "Passwords does not match";
    } else {
      $cnfpwd = test_input($_POST["cnfpwd"]);
    }

    // connect and store data
    $conn = new mysqli("localhost", "root", "password", "users");
    if ($conn->connect_error) {
      die("Connection failed!" . $conn->connect_error . "\n");
    }
    // verify unique email
    $emailCheck = "select email from users where email = '$email'";
    $result = $conn->query($emailCheck);
    if ($result->num_rows > 0) {
      $emailErr = "Email already exists";
    }

    $command = "insert into users(first_name, last_name, email, password) values ('$fname', '$lname', '$email', '$passwd')";

    if(strlen($fnameErr) + strlen($lnameErr) + strlen($emailErr) + strlen($passwdErr) + strlen($cnfpwdErr) == 0){
      if ($conn->query($command)) {
        echo "New record created!";
        $loggedIn = true;
      } else {
        printf("Error message: %s\n", $conn->error);
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
  <h1>User registration</h1>
  Go to <a href="login.php">login</a> page
  <form action="" method="post" class="form">
    <div class="form-section">
      <label for="fname">First name</label>
      <input type="text" id="fname" name="fname">
      <span class="error"><?php echo $fnameErr ?></span>
    </div>
    <div class="form-section">
      <label for="lname">Last name</label>
      <input type="text" id="lname" name="lname">
      <span class="error"><?php echo $lnameErr ?></span>
    </div>
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
    <div class="form-section">
      <label for="cnfpwd">Confirm Password</label>
      <input type="password" id="cnfpwd" name="cnfpwd">
      <span class="error"><?php echo $cnfpwdErr ?></span>
    </div>
    <input type="submit" value="Register" class="submitbtn">
  </form>
  <?php
  if (strlen($fnameErr) + strlen($lnameErr) + strlen($emailErr) + strlen($passwdErr) + strlen($cnfpwdErr) == 0 && $loggedIn) {
    echo "registration successful";
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
