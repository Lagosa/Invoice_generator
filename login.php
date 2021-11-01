<?php
  require "connectToDatabase.php";

  if(!isset($_POST['username']) || !isset($_POST['password']))
  {
    echo "Credentials not set!";
    exit();
  }

  $usernameEntered = $_POST['username'];
  $passwordEntered = $_POST['password'];

  $sql = "SELECT username,password FROM user_credentials";
  $query = mysqli_query($conn,$sql);
  $result = mysqli_fetch_array($query,MYSQLI_ASSOC);

  if($usernameEntered == $result['username'] && $passwordEntered == $result['password'])
  {
    session_regenerate_id();
    session_start();
    $_SESSION['loggedin'] = TRUE;
    header("Location: index.php");
  }else {
    header("Location: loginPage.html");
  }

 ?>
