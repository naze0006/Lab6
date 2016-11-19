<?php 
include "./Lab6Common/Header.php";
include "./Lab6Common/Constants.php";
session_start();
if (isset($_SESSION['student'])) {
   session_destroy();
   echo "<br> <p> you are logged out successufuly!</p>";
} 
   echo "<br/><p><a href='Index.php'>login</a></p>";
   
   include "./Lab6Common/Footer.php";
 ?>