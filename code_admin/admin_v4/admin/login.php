<?php 
/*if(isset($_COOKIE['user']))
{
  header("Location:dashboard.php");
}*/

include("include/conn.php");

$selquery4Nav = "select * from tbl_app_details where id=1";
$selresult4Nav = mysqli_query($conn,$selquery4Nav);
$selres4Nav = mysqli_fetch_array($selresult4Nav);

if(isset($_POST['btnLogin']))
{
  $txtUser= $_POST['txtUser'];
  $txtPassSha= $_POST['txtPass'];

  $txtPass=sha1($txtPassSha);

  $query = "select * from tbl_user_master where uname='{$txtUser}' and password='{$txtPass}' and del='0' and account_status='1'";
  $result = mysqli_query($conn,$query);
  if($res = mysqli_fetch_array($result))
  {
      if($res['is_verify']=="0"){
        /*$_SESSION['log_msg'] = "User not verified. Please activate account";
          header('location:login.php');*/
          echo '<script type="text/javascript">';
          echo 'setTimeout(function () { swal(
                                                "Oops...",
                                                "Email id not verified! \n please check your mail and verified",
                                                "error"
                                              );';
          echo '}, 1000);</script>';
      }
      else{
        session_start();
        $_SESSION['user']=$txtUser;
        header("Location:index.php");
      }
      /*
      if($chk=="Yes")
      {
        setcookie("user",$txtUser,time()+60*60*24);
        header("Location:dashboard.php");
      }
      elseif($chk=="No")
      {
        session_start();
        $_SESSION['user']=$txtUser;
        header("Location:dashboard.php");
      }
      */

      
  } 
  else
  {
    //echo "<script>alert(\"Invaild Username or Password\");</script>";
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal(
                                          "Oops...",
                                          "Invalid username or Password",
                                          "error"
                                        );';
    echo '}, 1000);</script>';
  }
  
}


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title></title>

        <?php include_once("include/head-section.php"); ?>
        
    </head>
    <body>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
        	<div class=" card-box">
                <div class="panel-heading"> 
                    <h3 class="text-center"> Sign In to <strong class="text-custom"><?php echo $selres4Nav['app_name']; ?></strong> </h3>
                </div> 


                <div class="panel-body">
                    <form class="form-horizontal m-t-20" action="login.php" method="post">
                        
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" name="txtUser" type="text" required placeholder="Username">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name="txtPass" type="password" required placeholder="Password">
                            </div>
                        </div>

                        <div class="form-group text-center m-t-40">
                            <div class="col-xs-12">
                                <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" name="btnLogin" type="submit">Log In</button>
                            </div>
                        </div>

                        <div class="form-group m-t-30 m-b-0">
                            <div class="col-sm-12">
                                <a href="forgot-password.php" class="text-dark"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                            </div>
                        </div>
                    </form>             
                </div>   
            </div>                              
            <!-- <div class="row">
            	<div class="col-sm-12 text-center">
            		<p>Don't have an account? <a href="page-register.html" class="text-primary m-l-5"><b>Sign Up</b></a></p>
                </div>
            </div> -->
            
        </div>
        
        

        
    	<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <?php include_once("include/common_js.php"); ?>

        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
        	
	</body>
</html>