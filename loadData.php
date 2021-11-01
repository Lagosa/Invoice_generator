<?php
 include "connectToDatabase.php";

$nrOrd = "";
$CIF = "";
$office = "";
$county = "";
$bankAccount = "";
$bank = "";

$firmName = "";
if(isset($_GET['firmName']))
{
  $firmName .= $_GET['firmName'];
}

$sql = "SELECT * FROM firm_data WHERE NAME = '".$firmName."'";
$query = mysqli_query($conn,$sql);

$firmData = mysqli_fetch_array($query);
$nrOrd = $firmData['NR_ORD'];
$CIF = $firmData['CIF'];
$office = $firmData['OFFICE'];
$county = $firmData['COUNTY'];
$bankAccount = $firmData['BANK_ACCOUNT'];
$bank = $firmData['BANK'];

//header("Location: index.php");
?>
