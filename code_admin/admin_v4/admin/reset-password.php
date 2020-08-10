<?php
include("include/conn.php");

if(isset($_GET['fp_code']))
{
    $fp_code = $_GET['fp_code'];

    $selquery="select forgot_pass_identity from tbl_user_master where forgot_pass_identity='$fp_code' and del='0'";
    $selquery1 = mysqli_query($conn,$selquery);
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Reset Password</title>

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
        <?php
          $selres = mysqli_num_rows($selquery1);
          if ($selres == 0) {
            echo "You may broken something!!!";
          }
          else
          {

              if(isset($_POST['btnResetpass']))
              {
                $txtNpass = $_POST['txtNpass'];
                $txtCpass = $_POST['txtCpass'];

                if ($txtNpass==$txtCpass) {
                  
                  $txtPass=sha1($txtCpass);

                  $pwdquery = "update tbl_user_master set password='{$txtPass}' where forgot_pass_identity='{$fp_code}' and del='0'";
                      if(mysqli_query($conn,$pwdquery))
                      {
                        echo '<script>
                                setTimeout(function() {
                                    swal({
                                        title: "Wow!",
                                        text: "Your password has been changed successfully.",
                                        type: "success"
                                    }, function() {
                                        window.location = "login.php";
                                    });
                                }, 1000);
                            </script>';

                      }
                }
                else
                {
                  echo '<script type="text/javascript">';
                  echo 'setTimeout(function () { swal(
                                                        "Oops...",
                                                        "Confirm Password must be same as new password",
                                                        "error"
                                                      );';
                  echo '}, 1000);</script>';
                }
                
              }
        ?>

          <form action="reset-password.php?fp_code=<?php echo $_GET['fp_code'];?>" method="post" data-parsley-validate novalidate>
              <div class="form-group">
                <label for="pass1">New Password</label>
                <input id="pass1" name="txtNpass" type="password" placeholder="Password" required class="form-control">
              </div>
              <div class="form-group">
                <label for="passWord2">Confirm Password</label>
                <input data-parsley-equalto="#pass1" name="txtCpass" type="password" required placeholder="Password" class="form-control" id="passWord2">
              </div>
              <br>
              <div class="form-group text-center m-b-0">
                <button class="btn btn-primary waves-effect waves-light" name="btnResetpass" type="submit">
                  Submit
                </button>
                <button type="reset" class="btn btn-default waves-effect waves-light m-l-5">
                  Cancel
                </button>
              </div>

          </form>

        <?php } ?>

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
    <script type="text/javascript" src="assets/plugins/parsleyjs/parsley.min.js"></script>
  </body>
</html>