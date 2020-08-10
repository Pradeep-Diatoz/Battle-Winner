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
$selquery = "select * from match_details where match_status=2 order by id";
$selresult = mysqli_query($conn,$selquery);

$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];

if(isset($_GET['WmatchId']))
{
  $matchId = $_GET['WmatchId'];
  
  $selquery1 = "select * from match_details where id={$matchId}";
  $getresult4 = mysqli_query($conn,$selquery1);
  $getres4 = mysqli_fetch_array($getresult4);

  $insquery2 = "select p.id, p.name, maxkill from participant_details p
                join (select max(kills) as maxKill from participant_details where match_id=$matchId) x on x.maxkill = p.kills where match_id=$matchId order by p.id desc";

  $getresult44 = mysqli_query($conn,$insquery2);


  /*UPDATE*/
  if(isset($_POST['btnUpdateKill']))
  {

    $txtTkills = mysqli_real_escape_string($conn,$_POST['txtTkills']);
    $recordId = mysqli_real_escape_string($conn,$_POST['recordId']);
    $prize = $txtTkills*$getres4['perKill'];

    //$did = $_GET['did'];
    $insquery = "update participant_details set kills=$txtTkills,prize=$prize where id={$recordId}";

    if(mysqli_query($conn,$insquery))
    {
      header("Location:create-result.php?matchId=".$matchId);
    }

  }

  if(isset($_POST['btnPrizeSave']))
  {
      //$txtId = mysqli_real_escape_string($conn,$_POST['txtId']);
      //$txtFscore = mysqli_real_escape_string($conn,$_POST['txtFscore']);

      for($i=0;$i<count($_POST['txtId']);$i++)
      {
          mysqli_query($conn,"update participant_details set
                  prize='{$_POST['txtFscore'][$i]}',
                  win=1
                  where id={$_POST['txtId'][$i]}");
      }
      
      $positionQuery="UPDATE participant_details t
                      INNER JOIN(
                        SELECT id,
                        prize,
                        @Rank := @Rank + 1 AS TeamRank
                        FROM participant_details
                        CROSS JOIN (SELECT @Rank:=0) Sub0
                        where match_id=$matchId
                          ORDER BY prize DESC
                        ) a ON a.id = t.id
                      SET t.position = a.teamRank";
      
      if(mysqli_query($conn,$positionQuery))
      {             
        header("Location:create-result.php?matchId=".$matchId);
      }

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

		<title>Winner and Prize Distribution</title>

    <?php include_once("include/head-section.php"); ?>

    <!-- DataTables -->
    <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <script language="JavaScript" type="text/javascript">
      function checkDelete(){
          return confirm('Are you sure you want to delete this User?');
      }
    </script>
    <style type="text/css">
      .flip-card {
        background-color: transparent;
        width: 115px;
        height: 30px;
        perspective: 1000px;
      }

      .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.6s;
        transform-style: preserve-3d;
        /*box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);*/
      }

      .flip-card:hover .flip-card-inner {
        transform: rotateY(180deg);
      }

      .flip-card-front, .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
      }

      .flip-card-front {
        background-color: transparent;
        color: black;
        z-index: 2;
      }

      .flip-card-back {
        background-color: transparent;
        color: black;
        transform: rotateY(180deg);
        z-index: 1;
      }
    </style>
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
                <?php if(isset($_GET['WmatchId'])) { ?>

                <?php 
                
                $selres4 = mysqli_num_rows($getresult44);
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
            <?php } else { ?>
            <section>
    					<!-- Page Content -->
              <div class="row">
                  <div class="col-sm-12">
                      <div class="card-box table-responsive">
                          <div class="row">
                              <div class="col-sm-10">
                                  <h4 class="m-t-0 header-title"><b><?php echo $getres4['title']; ?></b></h4>
                                  <p class="text-muted font-13">
                                      Winner List, Prize calculation and distribution..
                                  </p>
                              </div>
                              <div class="col-sm-2">
                                  <div class="m-t-0 text-right">
                                    <p>Winning Prize: <?php echo $getres4['winPrize']; ?></p>
                                    <p>Per Kill Amount: <?php echo $getres4['perKill']; ?></p>    
                                  </div>
                              </div>
                              <!-- <div class="col-sm-2">
                                  <div class="m-t-0 text-right">
                                      <a href="match-detail.php" class="btn btn-default waves-effect waves-light"><i class="fa fa-plus"></i> Add</a>
                                  </div>
                              </div> -->
                          </div>
                          <hr>
                          <div class="row">
                            <form method="post" action="winner-and-prize.php?WmatchId=<?php echo $_GET['WmatchId'];?>">
                              <?php while ($getres44 = mysqli_fetch_array($getresult44)){ ?>
                                <div class="col-sm-6">
                                  <div class="card-box">
                                    <div class="contact-card">
                                      <div class="member-info">
                                          <h4 class="m-t-0 m-b-10 header-title">Name: <b><?php echo $getres44['name']; ?></b></h4>
                                          <p class="text-muted">Total Kill: <?php $totalKillamt= $getres44['maxkill']*$getres4['perKill']; echo $getres44['maxkill']; ?> X <?php echo $getres4['perKill']; ?> = <?php echo $totalKillamt; ?></p>
                                          <p class="text-muted">Wining Prize: <?php $totalWinAmt=$getres4['winPrize']/$selres4; echo $getres4['winPrize']; ?>/<?php echo $selres4; ?> = <?php echo $totalWinAmt; ?></p>
                                          <p class="text-muted">Total Winning Prize: <?php $finalWinAmt=$totalKillamt+$totalWinAmt; echo round($finalWinAmt); ?> </p>
                                          <input type="hidden" name="txtId[]" value="<?php echo $getres44['id']; ?>">
                                          <input type="hidden" name="txtFscore[]" value="<?php echo round($finalWinAmt); ?>">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <?php } ?>
                            
                          </div>
                          <button class="btn btn-primary" type="submit" name="btnPrizeSave">Submit Prize Calculation</button>
                          </form>
                      </div>
                  </div>
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

    <!-- Modal -->
    <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel2">Update Information</h4>
          </div>

          <div class="modal-body">
              <form action="create-result.php?matchId=<?php echo $_GET['matchId'];?>" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="text" name="recordId" id="recordId" hidden value=""/>
                      <label for="txtTkills">Total Kills *</label>
                      <input type="number" max="100" name="txtTkills" placeholder="Enter Number of Kills" class="form-control" id="txtTkills">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group text-right m-b-0">
                      <button class="btn btn-primary waves-effect waves-light" type="submit" name="btnUpdateKill"> Update</button>
                    </div>
                  </div>
                </div>  
              </form>
          </div>

        </div><!-- modal-content -->
      </div><!-- modal-dialog -->
    </div><!-- modal -->

    <script>
        var resizefunc = [];
    </script>

    <!-- jQuery  -->
    <?php include_once("include/common_js.php"); ?>

    <script src="assets/js/jquery.core.js"></script>
    <script src="assets/js/jquery.app.js"></script>

    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="assets/plugins/parsleyjs/parsley.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
    <script src="assets/plugins/datatables/jszip.min.js"></script>
    <script src="assets/plugins/datatables/pdfmake.min.js"></script>
    <script src="assets/plugins/datatables/vfs_fonts.js"></script>
    <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatables/buttons.print.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.colVis.js"></script>
    <script src="assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>

    <script src="assets/pages/datatables.init.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').dataTable();
            $('#datatable-keytable').DataTable({keys: true});
            $('#datatable-responsive').DataTable();
            $('#datatable-colvid').DataTable({
                "dom": 'C<"clear">lfrtip',
                "colVis": {
                    "buttonText": "Change columns"
                }
            });
            $('#datatable-scroller').DataTable({
                ajax: "assets/plugins/datatables/json/scroller-demo.json",
                deferRender: true,
                scrollY: 380,
                scrollCollapse: true,
                scroller: true
            });
            var table = $('#datatable-fixed-header').DataTable({fixedHeader: true});
            var table = $('#datatable-fixed-col').DataTable({
                scrollY: "300px",
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                fixedColumns: {
                    leftColumns: 1,
                    rightColumns: 1
                }
            });
        });
        TableManageButtons.init();

    </script>
	   <script type="text/javascript">
        $(document).on("click", ".updateKill", function () {
             var myrecordId = $(this).data('id');
             var myrecordKills = $(this).data('kills');
             $(".modal-body #recordId").val( myrecordId );
             $(".modal-body #txtTkills").val( myrecordKills );
             // As pointed out in comments, 
             // it is unnecessary to have to manually call the modal.
             // $('#addBookDialog').modal('show');
        });
    </script>
	</body>
</html>