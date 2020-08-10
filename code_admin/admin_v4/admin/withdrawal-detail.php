<?php
include("include/conn.php");
include("include/security.php");
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
$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];

/*Users*/
$selquery15 = "select user_id,fname,lname from tbl_user_master where del='0' and account_status='1'";
$selres15 = mysqli_query($conn,$selquery15);

if(isset($_GET['withdrawId']))
{
  $withdrawId = $_GET['withdrawId'];
  
  $getquery1 = "select W.*,U.fname,U.lname,U.mobile from transaction_details as W left join user_details as U on U.id=W.user_id where W.id={$withdrawId}";
  $getresult1 = mysqli_query($conn,$getquery1);
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

		<title>Withdrawal Details</title>

    <link href="assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="assets/plugins/clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

    <?php include_once("include/head-section.php"); ?>

    <link href="assets/plugins/custombox/css/custombox.css" rel="stylesheet">

    <style type="text/css">
        .modal.left .modal-dialog,
        .modal.right .modal-dialog {
          position: fixed;
          margin: auto;
          width: 60%;
          height: 100%;
          -webkit-transform: translate3d(0%, 0, 0);
              -ms-transform: translate3d(0%, 0, 0);
               -o-transform: translate3d(0%, 0, 0);
                  transform: translate3d(0%, 0, 0);
        }

        .modal.left .modal-content,
        .modal.right .modal-content {
          height: 100%;
          overflow-y: auto;
        }
        
        .modal.left .modal-body,
        .modal.right .modal-body {
          padding: 15px 15px 80px;
        }

      /*Left*/
        .modal.left.fade .modal-dialog{
          left: -320px;
          -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
             -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
               -o-transition: opacity 0.3s linear, left 0.3s ease-out;
                  transition: opacity 0.3s linear, left 0.3s ease-out;
        }
        
        .modal.left.fade.in .modal-dialog{
          left: 0;
        }
              
      /*Right*/
        .modal.right.fade .modal-dialog {
          top: 60px;
          right: -320px;
          -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
             -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
               -o-transition: opacity 0.3s linear, right 0.3s ease-out;
                  transition: opacity 0.3s linear, right 0.3s ease-out;
        }
        
        .modal.right.fade.in .modal-dialog {
          right: 0;
        }

      /* ----- MODAL STYLE ----- */
        .modal-content {
          border-radius: 0;
          border: none;
        }

        .modal-header {
          border-bottom-color: #EEEEEE;
          background-color: #FAFAFA;
        }
        .row-eq-height {
          display: -webkit-box;
          display: -webkit-flex;
          display: -ms-flexbox;
          display: flex;
          flex-wrap: wrap;
        }
    </style>
    <script language="JavaScript" type="text/javascript">
      function checkDelete(){
          return confirm('Are you sure you want to delete this Company Permanently? \nAll the data associated with this company will be deleted.');
      }
    </script>
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
            <section>
                <?php if(isset($_GET['withdrawId'])) { ?>

                <?php 
                
                $selres4 = mysqli_num_rows($getresult1);
                if ($selres4 == 0) {
              
                  //echo"<script>alert(\"You have entered a wrong url\");</script>";
                  
                ?>
                <div class="wrapper-page">
                  <div class="ex-page-content text-center">
                    <div class="text-error">
                      <span class="text-primary">4</span><i class="ti-face-sad text-pink"></i><span class="text-info">4</span>
                    </div>
                    <h2>Whoo0ps! Page not found</h2>
                    <br>
                    <p class="text-muted">
                      This page cannot found or is missing.
                    </p>
                    <p class="text-muted">
                      Use the navigation above or the button below to get back and track.
                    </p>
                    <br>
                    <a class="btn btn-default waves-effect waves-light" href="index.php"> Return Home</a>

                  </div>
                </div>

            </section>
            <?php } else { 
              $getres1 = mysqli_fetch_array($getresult1); 
            ?>
            <section>
              <!-- Page-Title -->
                
    					<!-- Page Content -->
                <div class="row">
                  <div class="col-md-8">
                      <div class="card-box">
                          User Name:<h4 class="text-uppercase font-600"> <?php echo $getres1['fname']." ".$getres1['lname']; ?></h4>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="card-box">
                          Payment Status:<h4 class="text-uppercase font-600"> <?php if ($getres1['status']==0){ echo "<span class=\"text-warning\">Pending</span>"; } else if ($getres1['status']==1) { echo "<span style=\"color:green;\">Completed</span>";} else if ($getres1['status']==2) { echo "<span style=\"color:red;\">Rejected</span>"; } else { echo "Unknown Error!"; } ?></h4>
                      </div>
                  </div>
                </div>
                
                <div class="row row-eq-height">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Personal Details </h4>
                          <hr>
                          <p class="text-muted font-13 m-b-30">
                              <p class="text-muted"><strong>Register Name :</strong> <span class="m-l-15"><?php echo $getres1['fname']." ".$getres1['lname']; ?></span></p>
                              <p class="text-muted"><strong>Mobile Number :</strong> <span class="m-l-15"><?php echo $getres1['mobile']; ?></span></p>
                              <p class="text-muted"><strong>Withdraw Coin :</strong> <span class="m-l-15"><?php echo $getres1['coins']; ?></span></p>
                              <p class="text-muted"><strong>Withdrawal Amount :</strong> <span class="m-l-15"><?php echo $getres1['amount']; ?></span></p>
                              <p class="text-muted"><strong>Requested On :</strong> <span class="m-l-15"><?php echo $getres1['date']; ?></span></p>
                          </p>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Account Details</h4>
                          <hr>
                          <p class="text-muted font-13 m-b-30">
                              <p class="text-muted"><strong>Wallet :</strong> <span class="m-l-15"><?php echo $getres1['wallet']; ?></span></p>
                              <p class="text-muted"><strong>Account Holder Name :</strong> <span class="m-l-15"><?php echo $getres1['account_holder_name']; ?></span></p>
                              <p class="text-muted"><strong>Account Id :</strong> <span class="m-l-15"><?php echo $getres1['account_holder_id']; ?></span></p>
                          </p>
                      </div>
                    </div>
                    <!-- <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Order Details</h4>
                          <hr>
                          <p class="text-muted font-13 m-b-30">
                              <p class="text-muted"><strong>Order Id :</strong> <span class="m-l-15"><?php //echo $getres1['order_id']; ?></span></p>
                          </p>
                      </div>
                    </div> -->
                </div>

              <!-- /Page Content -->
            </section>

            <?php } } ?>
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

    <script type="text/javascript">
      $.fn.modal.Constructor.prototype.enforceFocus = function() {};
    </script>

    <script type="text/javascript" src="assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>

    <!-- jQuery  -->
    <script src="assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
    <script src="assets/plugins/counterup/jquery.counterup.min.js"></script>

    <script src="assets/pages/jquery.widgets.js"></script>

    <script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>

    <script src="assets/plugins/moment/moment.js"></script>
    <script src="assets/plugins/timepicker/bootstrap-timepicker.js"></script>
    <script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/plugins/clockpicker/js/bootstrap-clockpicker.min.js"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    
    <script src="assets/pages/jquery.form-pickers.init.js"></script>
    
    <!-- <script type="text/javascript" src="assets/pages/jquery.form-advanced.init.js"></script> -->
    
    <!-- <script type="text/javascript" src="assets/plugins/parsleyjs/parsley.min.js"></script> -->

    <script src="assets/js/jquery.core.js"></script>
    <script src="assets/js/jquery.app.js"></script>
    <!-- Modal-Effect -->
    <script src="assets/plugins/custombox/js/custombox.min.js"></script>
    <script src="assets/plugins/custombox/js/legacy.min.js"></script>
	</body>
</html>