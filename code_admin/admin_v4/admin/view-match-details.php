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

$selqueryI = "select * from tbl_image";
$selresI = mysqli_query($conn,$selqueryI);

$selqueryR = "select * from tbl_rules";
$selresR = mysqli_query($conn,$selqueryR);

/*Users*/
$selquery15 = "select user_id,fname,lname from tbl_user_master where del='0' and account_status='1'";
$selres15 = mysqli_query($conn,$selquery15);

if(isset($_GET['matchId']))
{
  $matchId = $_GET['matchId'];
  
  $getquery1 = "select * from match_details as MD 
  left join room_details as RD on RD.match_id=MD.id
  left join tbl_image as I on I.img_id=MD.imgCover
  left join tbl_rules as R on R.rule_id=MD.matchRules
  where MD.id={$matchId}";
  $getresult1 = mysqli_query($conn,$getquery1);  

  $countQuery = "select count(id) as total_joined from participant_details where match_id=$matchId";
  $selresCpati = mysqli_query($conn,$countQuery);
  $resCparti = mysqli_fetch_array($selresCpati);
}

if(isset($_GET['did']))
{
  $delDate = date("Y-m-d H:i:s");
  $did = $_GET['did'];
  $insquery = "update tbl_company set del='1', del_by='$userid', date_del='$delDate' where company_id={$did}";

  if(mysqli_query($conn,$insquery))
  {
    header("Location:company-list.php");
  }
  else
  {
    //echo $insquery;
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal(
                                          "Oops...",
                                          "Something went wrong !!",
                                          "error"
                                        );';
    echo '}, 1000);</script>';
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

    <title>View Match</title>

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
                <?php if(isset($_GET['matchId'])) { ?>

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
                          Match Title:<h4 class="text-uppercase font-600"> <?php echo $getres1['title']; ?></h4>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="card-box">
                          Match Status:<h4 class="text-uppercase font-600"> <?php if ($getres1['match_status']==0){ echo "Upcoming"; } else if ($getres1['match_status']==1) { echo "Ongoing";} else if ($getres1['match_status']==2) { echo "Finished"; } else { echo "Unknown Error!"; } ?></h4>
                      </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="card-box widget-inline">
                      <div class="row">
                        <div class="col-lg-3 col-sm-6">
                          <div class="widget-inline-box text-center">
                            <h3><i class="text-primary md md-attach-money"></i> <b data-plugin="counterup">
                              <?php echo $getres1['winPrize']; ?>                              
                            </b></h3>
                            <h4 class="text-muted">Winning Prize</h4>
                          </div>
                        </div>
                        
                        <div class="col-lg-3 col-sm-6">
                          <div class="widget-inline-box text-center">
                            <h3><i class="text-custom md md-style"></i> <b data-plugin="counterup"><?php echo $getres1['perKill']; ?></b></h3>
                            <h4 class="text-muted">Prize/Kill</h4>
                          </div>
                        </div>
                        
                        <div class="col-lg-3 col-sm-6">
                          <div class="widget-inline-box text-center">
                            <h3><i class="text-pink md md-keyboard-tab"></i> <b data-plugin="counterup"><?php echo $getres1['entryFee']; ?></b></h3>
                            <h4 class="text-muted">Entry Fee</h4>
                          </div>
                        </div>
                        
                        <div class="col-lg-3 col-sm-6">
                          <div class="widget-inline-box text-center b-0">
                            <a href="participant-list.php?matchId=<?php echo $getres1['match_id']; ?>"><h3><i class="text-purple md md-account-child"></i> <b data-plugin="counterup"><?php echo $resCparti['total_joined']; ?></b></h3></a>
                            <a href="participant-list.php?matchId=<?php echo $getres1['match_id']; ?>"><h4 class="text-muted">Total Participant</h4></a>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row row-eq-height">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Match Details</h4>
                          <hr>
                          <p class="text-muted font-13 m-b-30">
                              <p class="text-muted"><strong>Time :</strong> <span class="m-l-15"><?php echo $getres1['time']; ?></span></p>
                              <p class="text-muted"><strong>Version :</strong> <span class="m-l-15"><?php echo $getres1['version']; ?></span></p>
                              <p class="text-muted"><strong>Map :</strong> <span class="m-l-15"><?php echo $getres1['map']; ?></span></p>
                              <p class="text-muted"><strong>Is Private Match :</strong> <span class="m-l-15"><?php echo $getres1['isPrivateMatch']; ?></span></p>
                              <?php if($getres1['isPrivateMatch'] == 'yes') { ?>
                                <p class="text-muted"><strong>Is Private Match :</strong> <span class="m-l-15"><?php echo $getres1['private_match_code']; ?></span></p>
                              <?php } ?>
                          </p>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Room Details </h4>
                          <hr>
                          <p class="text-muted font-13 m-b-30">
                              <p class="text-muted"><strong>Room ID :</strong> <span class="m-l-15"><?php echo $getres1['room_id']; ?></span></p>
                              <p class="text-muted"><strong>Room Password :</strong> <span class="m-l-15"><?php echo $getres1['room_pass']; ?></span></p>
                              <p class="text-muted"><strong>Room Status :</strong> <span class="m-l-15"><?php if ($getres1['room_status']==0) { echo "Hide"; } else if ($getres1['room_status']==1) { echo "Show"; } else { echo "Unknown Error!"; }  ?></span></p>
                              <p class="text-muted"><strong>Room Size :</strong> <span class="m-l-15"><?php echo $getres1['room_size']; ?></span></p>
                          </p>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Other Details</h4>
                          <hr>
                          <p class="text-muted font-13 m-b-30">
                              <p class="text-muted"><strong>Match Type :</strong> <span class="m-l-15"><?php echo $getres1['matchType']; ?></span></p>
                              <p class="text-muted"><strong>Entry Type :</strong> <span class="m-l-15"><?php echo $getres1['entryType']; ?></span></p>
                              <p class="text-muted"><strong>Sponsored By :</strong> <span class="m-l-15"><?php echo $getres1['sponsoredBy']; ?></span></p>
                              <p class="text-muted"><strong>Created On :</strong> <span class="m-l-15"><?php echo $getres1['created']; ?></span></p>
                          </p>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Description/Rules/Policy</h4>
                          <hr>
                          <!-- <p class="text-muted font-13 m-b-30">
                              <?php //echo $getres1['matchNotes']; ?>
                          </p> -->
                          <p class="text-muted font-13 m-b-30"><strong>Description :</strong> <span class="m-l-15"><?php if(strlen($getres1['matchNotes'])>=400) { echo substr($getres1['matchNotes'],0,450)."..." ?> <a href="#custom-modal" class="" data-animation="fadein" data-plugin="custommodal" data-overlaySpeed="200" data-overlayColor="#36404a"> Read More</a> <?php  } else { echo $getres1['matchNotes']; } ?></span></p>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Spectate Video</h4>
                          <hr>
                          <p class="text-muted font-13 m-b-30">
                              <iframe id="video" width="440" height="135" src="<?php echo $getres1['spectateURL']; ?>" frameborder="0" allowfullscreen></iframe>
                          </p>
                      </div>
                    </div>
                    <!-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Banner Image</h4>
                          <hr>
                          <p class="text-muted font-13 m-b-30">
                              <img src="<?php //echo $getres1['imgBanner']; ?>" />
                          </p>
                      </div>
                    </div> -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Match Rules</h4>
                          <hr>
                          <p class="text-muted font-13 m-b-30">
                              <?php echo $getres1['rule_title']; ?>
                              <?php echo $getres1['rules']; ?>
                          </p>
                      </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="card-box">
                          <h4 class="text-uppercase font-600">Cover Image</h4>
                          <hr>
                          <p class="text-muted font-13 m-b-30">
                              <img src="<?php echo $getres1['image']; ?>" />
                          </p>
                      </div>
                    </div>
                </div>

              <!-- /Page Content -->
            </section>

            <!-- Modal -->
            <div id="custom-modal" class="modal-demo">
                <button type="button" class="close" onclick="Custombox.close();">
                    <span>&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="custom-modal-title">Match Description</h4>
                <div class="custom-modal-text">
                    <?php echo $getres1['matchNotes']; ?>
                </div>
            </div>

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