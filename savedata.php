<?php
$name=$_POST['fullname'];
$number=$_POST['mobileno'];
$email=$_POST['emailid'];
$age=$_POST['age'];
$gender=$_POST['gender'];
$blood_group=$_POST['blood'];
$address=$_POST['address'];
//$conn=mysqli_connect("localhost","root","","blood_bank_database") or die("Connection error");
$conn=mysqli_connect("blood-bank-database.c3qqg6c0q2ew.ap-south-1.rds.amazonaws.com","admin","Aashin2101","blood_bank_database") or die("Connection error");
$sql= "INSERT INTO donor_details(donor_name,donor_number,donor_mail,donor_age,donor_gender,donor_blood,donor_address) values('{$name}','{$number}','{$email}','{$age}','{$gender}','{$blood_group}','{$address}')";
$result=mysqli_query($conn,$sql) or die("query unsuccessful.");

//header("Location: http://localhost/BDMS/home.php");
header("Location: http://13.233.199.209/BDMS/home.php");
mysqli_close($conn);
 ?>
