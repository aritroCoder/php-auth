<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Profile</title>
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
  $id = -1;
  $email = "";
  $fname = "";
  $lname = "";
  $passwd = "";
  $cnfpwd = "";
  $loggedIn = false;
  if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $conn = new mysqli("localhost", "root", "password", "users");
    if ($conn->connect_error) {
      die("Connection failed!" . $conn->connect_error . "\n");
    }
    $id = $_GET['id'];
    $command = "select * from users where id='$id'";
    if ($result = $conn->query($command)) {
      $row = $result->fetch_assoc();
      $fname = $row['first_name'];
      $lname = $row['last_name'];
      $email = $row['email'];
      $passwd = $row['password'];
    }
  } 
  else if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    if (empty($_POST["passwd"])) {
      $passwdErr = "Please enter a strong password";
    } else {
      $passwd = test_input($_POST["passwd"]);
    }

    // connect and store data
    $conn = new mysqli("localhost", "root", "password", "users");
    if ($conn->connect_error) {
      die("Connection failed!" . $conn->connect_error . "\n");
    }
    $id = $_POST['id'];
    $command = "update users set first_name='$fname', last_name='$lname', email='$email', password='$passwd' where id='$id'";

    if(strlen($fnameErr) + strlen($lnameErr) + strlen($emailErr) + strlen($passwdErr) + strlen($cnfpwdErr) == 0){
      if ($conn->query($command)) {
        echo "Record Updated!<br>";
        echo "Go to <a href='login.php'>Login</a> page.";
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
  <h1>User profile update</h1>
  <form action="" method="post" class="form">
    <div class="form-section">
      <label for="fname">First name</label>
      <input type="text" id="fname" name="fname" value="<?php echo($fname) ?>">
      <span class="error"><?php echo $fnameErr ?></span>
    </div>
    <div class="form-section">
      <label for="lname">Last name</label>
      <input type="text" id="lname" name="lname" value="<?php echo($lname) ?>">
      <span class="error"><?php echo $lnameErr ?></span>
    </div>
    <div class="form-section">
      <label for="email">Email</label>
      <input type="email" id="email" name="email"  value="<?php echo($email) ?>">
      <span class="error"><?php echo $emailErr ?></span>
    </div>
    <div class="form-section">
      <label for="passwd">Password</label>
      <input type="text" id="passwd" name="passwd" value="<?php echo($passwd) ?>">
      <span class="error"><?php echo $passwdErr ?></span>
    </div>
    <div class="form-section">
      <input type="text" id="id" name="id" value="<?php echo($id) ?>">
    </div>
    <input type="submit" value="Update" class="submitbtn">
  </form>
</body>

</html>


<!-- mysql> create table users (
    -> userid int not null auto_increment primary key,
    -> first_name varchar(30),
    -> last_name varchar(30),
    -> email varchar(40),
    -> password varchar(40)); -->
