<?php
include("include/conn.php");

$uniqidStr = md5(uniqid(mt_rand()));

if(isset($_POST['btnfrgtpass']))
{
  $txtEmail = mysqli_real_escape_string($conn,$_POST['txtEmail']);

  $query = "select email from tbl_user_master where email='{$txtEmail}' and del='0'";
  $result = mysqli_query($conn,$query);
    
    if($res = mysqli_fetch_array($result))
    {
      $pwdquery = "update tbl_user_master set forgot_pass_identity='{$uniqidStr}' where email='{$txtEmail}' and del='0'";
        if(mysqli_query($conn,$pwdquery))
        {
            $txtEmail = $txtEmail;
            $mailSubject = "Reset Password";
            $message="<h1>Reset Password - Sky Coder</h1>
            You have requested a to reset your password,<br>
            Go to the <a href =\"http://pung.skyforcoding.com/admin/reset-password.php?fp_code=$uniqidStr\">Reset Password</a><br>
            For any query contact administrator.
            ";
            include("include/verify_mail.php");

            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal(
                                                  "Done",
                                                  "Your Password reset link send to your register email.",
                                                  "success"
                                                );';
            echo '}, 1000);</script>';
        }
        else
        {
            //echo "error";
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal(
                                                  "Oops...",
                                                  "Something went wrong",
                                                  "error"
                                                );';
            echo '}, 1000);</script>';
        }
    }
    else
    {
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal(
                                              "Oops...",
                                              "Email address not register yet",
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

    <title>Forgot Password</title>

    <?php include_once("include/head-section.php"); ?>
  </head>
  <body>

    <div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
      <div class=" card-box">
        <div class="panel-heading">
          <h3 class="text-center"> Reset Password </h3>
        </div>

        <div class="panel-body">
          <form method="post" action="forgot-password.php" role="form" class="text-center">
            <div class="alert alert-info alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                ×
              </button>
              Enter your <b>Email</b> and instructions will be sent to you!
            </div>
            <div class="form-group m-b-0">
              <div class="input-group">
                <input type="email" class="form-control" placeholder="Enter Email" required name="txtEmail">
                <span class="input-group-btn">
                  <button type="submit" name="btnfrgtpass" class="btn btn-pink w-sm waves-effect waves-light">
                    Reset
                  </button> 
                </span>
              </div>
            </div>

          </form>
        </div>
      </div>
      

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