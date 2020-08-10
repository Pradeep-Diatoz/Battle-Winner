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

      $getquery44 = "select * from tbl_about";
      $getresult44 = mysqli_query($conn,$getquery44);
      $getres5 = mysqli_fetch_array($getresult44);

      if(isset($_GET['id']))
      {
        $id = $_GET['id'];
        $getquery = "select * from tbl_about where id={$id}";
        $getresult = mysqli_query($conn,$getquery);
        $getres = mysqli_fetch_array($getresult);
      }
      if(isset($_POST['btnSave']))
      {
        $txtDesc=mysqli_real_escape_string($conn,$_POST['txtDesc']);
        $txtCdate=date("Y-m-d H:m:s");
        
        $insquery = "insert into tbl_about values(null,'{$txtDesc}','{$userid}','{$txtCdate}',null,null)";
        if(mysqli_query($conn,$insquery))
        {
          header("Location:about-us.php");
        }
        else
        {
          echo $insquery;
        }


      }


      if(isset($_POST['btnUpdate']))
      {
        $txtDesc=mysqli_real_escape_string($conn,$_POST['txtDesc']);
        $txtMdate=date("Y-m-d H:m:s");

        
        $updquery = "update tbl_about set content='{$txtDesc}',modify_by='{$userid}',date_modify='{$txtMdate}' where id = $id";
        
        if(mysqli_query($conn,$updquery))
        {
          header("Location:about-us.php");
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

		<title>About Us</title>

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
                  
                  <h4 class="m-t-0 header-title"><b>About Us</b></h4>
                  <p class="text-muted font-13 m-b-30">
                      Details regarding Website.
                  </p>
                  <?php if(isset($_GET['id'])) { ?>
                  <form action="about-us.php?id=<?php echo $_GET['id'];?>" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="txtMtime">Modify Date</label>
                          <input id="txtMtime" name="" type="text" value="<?php echo date("d-m-Y")?>" readonly class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="txtVersion">Modify By</label>
                          <input type="text" name="" value="<?php echo $getres4['0']." ".$getres4['1']; ?>" readonly class="form-control" id="txtVersion">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="txtMdesc">Match Description*</label>
                          <textarea class="form-control" data-height="200" name="txtDesc" id="txtDesc" required><?php echo $getres['content']?></textarea>
                          <script>
                              CKEDITOR.replace( 'txtDesc' );
                          </script>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group text-right m-b-0">
                          <button class="btn btn-primary waves-effect waves-light" type="submit" name="btnUpdate" id="btnUpdate" > Update</button>
                          <!-- <a href="user-list.php" class="btn btn-default waves-effect waves-light m-l-5"> Cancel</a> -->
                          <a href="javascript:void(0);" class="btn btn-default waves-effect waves-light" onclick="history.back();"> Cancel</a>
                        </div>
                      </div>

                    </div>
                  </form>
                  <?php } else { ?>
                    <?php echo $getres5['content']; ?>
                    <!-- /About us content here -->
                    <a href="about-us.php?id=<?php echo $getres5['0'];?>" class="btn btn-success"> Edit Content</a>
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