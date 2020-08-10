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
$selqueryU = "select * from user_details order by id desc";
$selresultU = mysqli_query($conn,$selqueryU);

$selquery = "select * from match_details where isPrivateMatch='yes' order by id desc";
$selresult = mysqli_query($conn,$selquery);

$selqueryUM = "select * from tbl_user_master where uname='$user'";
$selresUM = mysqli_query($conn,$selqueryUM);
$selres1 = mysqli_fetch_array($selresUM);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];

if(isset($_GET['matchIdOg']))
{
  $matchIdOg = $_GET['matchIdOg'];
  $insquery = "update match_details set match_status='1' where id={$matchIdOg}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:private-match-list.php");
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

if(isset($_GET['matchIdFn']))
{
  $matchIdFn = $_GET['matchIdFn'];
  $insquery = "update match_details set match_status='2' where id={$matchIdFn}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:private-match-list.php");
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

if(isset($_POST['btnAddParti'])){
    $txtMid = $_POST['txtMid'];
    $txtCdate=date("Y-m-d H:m:s");
    for($i=0;$i<count($_POST['ParUname']);$i++)
    {
      //$del_id = $checkbox[$i]; 
      mysqli_query($conn,"insert into participant_details set match_id='$txtMid', user_id='{$_POST['txtUserId'][$i]}', pubg_id='{$_POST['ParUname'][$i]}', kills=0, win=0, position=0, prize=0, created='$txtCdate'");
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

		<title>Private Match list</title>

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
          return confirm('Are you sure you want to delete this Match?');
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

  					<!-- Page Content -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <div class="row">
                            <div class="col-sm-10">
                                <h4 class="m-t-0 header-title"><b>Private Match list</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Manage match here. update Upcoming match details.
                                </p>
                            </div>
                            <div class="col-sm-2">
                                <div class="m-t-0 text-right">
                                    <a href="match-detail.php" class="btn btn-default waves-effect waves-light"><i class="fa fa-plus"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                  <th>Id</th>
                                  <th>Title</th>
                                  <th>Time</th>
                                  <th>Entry Fee</th>
                                  <!-- <th>Winning Prize</th> -->
                                  <th>Match Type</th>
                                  <th style="text-align: center;">Status</th>
                                  <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php while ($selres = mysqli_fetch_array($selresult)){ ?>
                                <tr>
                                    <td><?php echo $selres['id']; ?></td>
                                    <td><?php echo $selres['title']; ?></td>
                                    <td><?php echo $selres['time']; ?></td>
                                    <td><?php echo $selres['entryFee']; ?></td>
                                    <!-- <td><?php //echo $selres['winPrize']; ?></td> -->
                                    <td><?php echo $selres['matchType']; ?></td>

                                    <?php if ($selres['match_status'] == 0){ ?>
                                      <td>
                                        <div class="flip-card">
                                          <div class="flip-card-inner">
                                            <div class="flip-card-front">
                                              Upcoming
                                            </div>
                                            <div class="flip-card-back">
                                              <a class="btn btn-default" href="private-match-list.php?matchIdOg=<?php echo $selres['id'];?>">Click To Ongoing</a>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    <?php } else if ($selres['match_status'] == 1) { ?>
                                      <td>
                                        <div class="flip-card">
                                          <div class="flip-card-inner">
                                            <div class="flip-card-front">
                                              <span style="color: green;">Ongoing</span>
                                            </div>
                                            <div class="flip-card-back">
                                              <a class="btn btn-warning" href="private-match-list.php?matchIdFn=<?php echo $selres['id'];?>">Click To Finished</a>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    <?php } else if ($selres['match_status'] == 2) { ?>
                                      <td style="text-align: center;">Finished</td>
                                    <?php } ?>
                                    
                                    <td>
                                      <?php if ($selres['match_status'] == 0){ ?>
                                      <a href="private-match-user.php?matchId=<?php echo $selres['id']; ?>" class="matchId4"><i class="fa fa-user"></i> Add Participants</a>
                                      <?php } else { ?>
                                        <i class="fa fa-ban" data-toggle="tooltip" data-placement="top" title="This Match is either Ongoing or Finished" data-original-title=""></i>
                                      <?php } ?>
                                    </td>
                                </tr>
                              <?php } ?>
                            </tbody>
                        </table>
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

    <!-- ./wrapper -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Spectate Video</h4>
          </div>
          <div class="modal-body">
            <iframe id="video" width="550" height="315" src="" frameborder="0" allowfullscreen></iframe>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
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
            //$('#datatable-responsive').DataTable();
            //$('#datatable-responsive1').DataTable();
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
            $('#datatable-responsive').DataTable( {
                "order": [[ 0, "desc" ]]
            } );
        });
        TableManageButtons.init();

    </script>
    <script type="text/javascript">
        $(document).on("click", ".matchId4", function () {
            var myrecordId = $(this).data('mid');
            $("#txtMid").val( myrecordId );
        });
    </script>
	  <script type="text/javascript">
      
      var limit = '100';
      $('input[type=checkbox]').on('change', function (e) {
          if ($('input[type=checkbox]:checked').length > limit) {
              $(this).prop('checked', false);
                alert("Maximum 100 Participant allow in match.");
          }
      });
    </script>
	</body>
</html>