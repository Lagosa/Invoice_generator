<?php

// credentials
  $serverName = "localhost";
  $username = "root";
  $password = "";
  $database = "firme";

// establishing connection
  $conn = mysqli_connect($serverName,$username,$password,$database);

// check if the connection was made
  if(!$conn)
  {
    die("Connection to the database failed:" . mysqli_connect_error());
  }
?>
