<?php 
session_start();

if(isset($_SESSION['user']))
{
    require('DATA_CONFIG.php');
    require('rn84d6NJhjE.php');
}
else
{
  if(isset($_COOKIE['user']))
  {
    $user= $_COOKIE['user'];
  }
  else
  {
    header("Location:login");
  }
}

?>