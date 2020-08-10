<?php

include("include/security.php");
include("include/conn.php");

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$code}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer {$personalToken}",
        "User-Agent: {$userAgent}"
    )
));

$response = @curl_exec($ch);

$body = @json_decode($response);

if (isset($body->item->name)) {

    $id = $body->item->id;
    $name = $body->item->name;

    if($id == 23898180) {
$getquery4 = "select fname,lname,user_id from tbl_user_master where uname='$user'";
$getresult4 = mysqli_query($conn,$getquery4);
$getres4 = mysqli_fetch_array($getresult4);
$userid = $getres4['user_id'];

$getquery44 = "select * from tbl_contact";
$getresult44 = mysqli_query($conn,$getquery44);
$getres5 = mysqli_fetch_array($getresult44);

if(isset($_GET['id']))
{
  $id = $_GET['id'];
  $getquery = "select * from tbl_contact where contact_id={$id}";
  $getresult = mysqli_query($conn,$getquery);
  $getres = mysqli_fetch_array($getresult);
}
if(isset($_POST['btnSave']))
{
  $txtMsg=mysqli_real_escape_string($conn,$_POST['txtMsg']);
  $txtCnumber=mysqli_real_escape_string($conn,$_POST['txtCnumber']);
  $txtEmail=mysqli_real_escape_string($conn,$_POST['txtEmail']);
  $txtAddress=mysqli_real_escape_string($conn,$_POST['txtAddress']);
  $txtOther=mysqli_real_escape_string($conn,$_POST['txtOther']);
  $txtWhatsapp=mysqli_real_escape_string($conn,$_POST['txtWhatsapp']);
  $txtMsngr=mysqli_real_escape_string($conn,$_POST['txtMsngr']);
  $txtfb=mysqli_real_escape_string($conn,$_POST['txtfb']);
  $txtInstagram=mysqli_real_escape_string($conn,$_POST['txtInstagram']);
  $txtTwitter=mysqli_real_escape_string($conn,$_POST['txtTwitter']);
  $txtYoutube=mysqli_real_escape_string($conn,$_POST['txtYoutube']);
  $txtCdate=date("Y-m-d H:m:s");
  
  $insquery = "insert into tbl_contact values(null,'{$txtMsg}','{$txtCnumber}','{$txtEmail}','{$txtAddress}','{$txtOther}','{$txtCdate}','{$userid}',null,null)";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:contact-us.php");
  }
  else
  {
    echo $insquery;
  }

}

if(isset($_POST['btnUpdate']))
{
  $txtMsg=mysqli_real_escape_string($conn,$_POST['txtMsg']);
  $txtCnumber=mysqli_real_escape_string($conn,$_POST['txtCnumber']);
  $txtEmail=mysqli_real_escape_string($conn,$_POST['txtEmail']);
  $txtAddress=mysqli_real_escape_string($conn,$_POST['txtAddress']);
  $txtOther=mysqli_real_escape_string($conn,$_POST['txtOther']);
  $txtWhatsapp=mysqli_real_escape_string($conn,$_POST['txtWhatsapp']);
  $txtMsngr=mysqli_real_escape_string($conn,$_POST['txtMsngr']);
  $txtfb=mysqli_real_escape_string($conn,$_POST['txtfb']);
  $txtInstagram=mysqli_real_escape_string($conn,$_POST['txtInstagram']);
  $txtTwitter=mysqli_real_escape_string($conn,$_POST['txtTwitter']);
  $txtYoutube=mysqli_real_escape_string($conn,$_POST['txtYoutube']);
  $txtMdate=date("Y-m-d H:m:s");

  
  $updquery = "update tbl_contact set title='{$txtMsg}',phone='{$txtCnumber}',email='{$txtEmail}',address='{$txtAddress}',other='{$txtOther}',modify_by='{$userid}',date_modify='{$txtMdate}',whatsapp_no='$txtWhatsapp', messenger_id='$txtMsngr',fb_follow='$txtfb',ig_follow='$txtInstagram',twitter_follow='$txtTwitter', youtube_follow='$txtYoutube' where contact_id = $id";
  
  if(mysqli_query($conn,$updquery))
  {
    header("Location:contact-us.php");
  }
  else
  {
    echo $updquery;
  }

}

} else {
        header("location:error.php");
      exit;
    }
}
else
{
    header("location:error.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Contact Us</title>

    <?php include_once("include/head-section.php"); ?>
    <script src="https://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>
    
  </head>

  <body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">

      <!-- topbar and sidebar -->
      <?php include_once("include/navbar.php"); ?>

      <!-- ============================================================== -->
      <!-- Start right Content here -->
      <!-- ============================================================== -->
      <div class="content-page">
        <!-- Start content -->
        <div class="content">
          <div class="container">

            <!-- Page Content -->
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card-box">
                  
                  <h4 class="m-t-0 header-title"><b>Contact Us</b></h4>
                  <p class="text-muted font-13 m-b-30">
                      Details regarding Website.
                  </p>
                  <?php if(isset($_GET['id'])) { ?>
                    <form action="contact-us.php?id=<?php echo $_GET['id'];?>" method="post" enctype="multipart/form-data">
                      <fieldset>
                        <div class="row">
                          <div class="form-group">
                            <div class="col-md-3 col-sm-12">
                              <label>Date</label>
                                          <input type="text" class="form-control" value="<?php echo date("d-m-Y")?>" readonly>
                            </div>
                            <div class="col-md-3 col-sm-12">
                              <label>Modify By</label>
                                          <input type="text" class="form-control" value="<?php echo $getres4['fname']." ".$getres4['lname']; ?>" readonly>
                            </div>
                            <div class="col-md-12">
                              <label>Message</label>
                                        <input type="text" class="form-control" value="<?php echo $getres['1']?>" required name="txtMsg">
                                      </div>
                                      <div class="col-md-4">
                              <label>Contact Number</label>
                                        <input type="text" class="form-control" value="<?php echo $getres['2']?>" required name="txtCnumber">
                                      </div>
                                      <div class="col-md-4">
                              <label>Email Address</label>
                                        <input type="text" class="form-control" value="<?php echo $getres['3']?>" required name="txtEmail">
                                      </div>
                                      <div class="col-md-12">
                              <label>Address</label>
                                        <textarea class="form-control" required name="txtAddress"><?php echo $getres['4']?></textarea>
                                      </div>
                                      <div class="col-md-12">
                              <label>YouTube Channel</label>
                                        <input type="text" class="form-control" name="txtOther" id="txtOther" value="<?php echo $getres['5']?>">
                                      </div>
                                      <div class="col-md-12">
                              <label>Whatsapp No</label>
                                        <input type="text" class="form-control" value="<?php echo $getres['whatsapp_no']?>" required name="txtWhatsapp">
                                      </div>
                                      <div class="col-md-12">
                              <label>Messenger Id</label>
                                        <input type="text" class="form-control" value="<?php echo $getres['messenger_id']?>" required name="txtMsngr">
                                      </div>
                                      <div class="col-md-12">
                              <label>Facebook Link</label>
                                        <input type="text" class="form-control" value="<?php echo $getres['fb_follow']?>" required name="txtfb">
                                      </div>
                                      <div class="col-md-12">
                              <label>Instagram Link</label>
                                        <input type="text" class="form-control" value="<?php echo $getres['ig_follow']?>" required name="txtInstagram">
                                      </div>
                                      <div class="col-md-12">
                              <label>Twitter Link</label>
                                        <input type="text" class="form-control" value="<?php echo $getres['twitter_follow']?>" required name="txtTwitter">
                                      </div>
                                      <div class="col-md-12">
                              <label>Youtube Link</label>
                                        <input type="text" class="form-control" value="<?php echo $getres['youtube_follow']?>" required name="txtYoutube">
                                      </div>
                          </div>
                        </div>

                      </fieldset>
                      <br>
                      <div class="row">
                        <div class="col-md-12">
                          <button type="submit" name="btnUpdate" class="btn btn-success">Update</button>
                                    <a href="contact-us.php" class="btn btn-danger">Cancel</a>
                        </div>
                      </div>

                    </form>
                  <?php } else { ?> 
                    
                    <!-- Contact us content here -->
                    <b>Title Line : <?php echo $getres5['title']; ?></b>
                    <br><br>
                    Phone Number : <?php echo $getres5['phone']; ?>
                    <br><br>
                    Email : <?php echo $getres5['email']; ?>
                    <br><br>
                    Address : <?php echo $getres5['address']; ?>
                    <br><br>
                    WhatsApp No : <?php echo $getres5['whatsapp_no']; ?>
                    <br><br>
                    Messenger Id : <?php echo $getres5['messenger_id']; ?>
                    <br><br>
                    Facebook Link : <?php echo $getres5['fb_follow']; ?>
                    <br><br>
                    Instagram Link : <?php echo $getres5['ig_follow']; ?>
                    <br><br>
                    Twitter Link : <?php echo $getres5['twitter_follow']; ?>
                    <br><br>
                    YouTube Link : <?php echo $getres5['youtube_follow']; ?>
                    <br><br>
                    YouTube Channel : <?php echo $getres5['other']; ?>
                    <br><br>
                    <!-- /Contact us content here -->
                    <a href="contact-us.php?id=<?php echo $getres5['0'];?>" class="btn btn-success"> Edit Content</a>
                  <?php } ?>
                </div>
              </div>
            </div>
            <!-- /Page Content -->

          </div> <!-- container -->
                               
        </div> <!-- content -->

        <?php include_once("include/footer.php"); ?>

      </div>
      <!-- ============================================================== -->
      <!-- End Right content here -->
      <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

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