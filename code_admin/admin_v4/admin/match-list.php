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
$selquery = "select * from match_details order by id desc";
$selresult = mysqli_query($conn,$selquery);

$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];

if(isset($_GET['matchDid']))
{
  $matchDid = $_GET['matchDid'];
  $insquery = "delete from match_details where id={$matchDid}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:match-list.php");
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


if(isset($_GET['matchIdOg']))
{
  $matchIdOg = $_GET['matchIdOg'];
  $selMtitle = "select m.title, r.room_id, r.room_pass from match_details as m
  left join room_details as r on r.match_id=m.id
  WHERE m.id=$matchIdOg";
  $selResMtitle = mysqli_query($conn,$selMtitle);
  $resMtitle = mysqli_fetch_array($selResMtitle);
  $matchTitle = $resMtitle['title'];
  $roomId = $resMtitle['room_id'];
  $roomPass = $resMtitle['room_pass'];

  if($roomId != '' & $roomPass != '')
  {
    $insquery = "update match_details set match_status='1' where id={$matchIdOg}";
    if(mysqli_query($conn,$insquery))
    {
        $selEmail = "select distinct(u.email) as partiEmail FROM participant_details as p
                    left join user_details as u on u.id=p.user_id
                    WHERE match_id=$matchIdOg";
        $selResEmail = mysqli_query($conn,$selEmail);
        while($resMail = mysqli_fetch_array($selResEmail))
        {
            $addresses[] = $resMail['partiEmail'];
        }
        $to = implode(", ", $addresses);
        $subject = "Match is live now..";
        $body = "$matchTitle Match is live now...<br>
        Use following room details to join the match,<br>
        <b>Room Id:$roomId</b><br>
        <b>Password:$roomPass</b><br><br>
        All the very best to all participants.";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: your_mail@gmail.com";

        mail($to,$subject,$body,$headers);

        header("Location:match-list.php");
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
  else
  {
      /*echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal(
                                            "Oops...",
                                            "Room id and Password are empty, you must provide room id and password before changing match status to live or Ongoing.",
                                            "error"
                                          );';
      echo '}, 1000);</script>';*/

      echo '<script>
            setTimeout(function() {
                swal({
                    title: "Oops...",
                    text: "Room id and Password are empty, you must provide room id and password before changing match status to live or Ongoing.",
                    type: "error"
                }, function() {
                    window.location = "match-detail.php?matchId='.$matchIdOg.'";
                });
            }, 1000);
        </script>';
  }

}

if(isset($_GET['matchIdFn']))
{
  $matchIdFn = $_GET['matchIdFn'];
  $insquery = "update match_details set match_status='2' where id={$matchIdFn}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:match-list.php");
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

if(isset($_POST['btnCancel']))
{
  $txtCid=mysqli_real_escape_string($conn,$_POST['txtCid']);
  $txtCanReason=mysqli_real_escape_string($conn,$_POST['txtCanReason']);
  $txtMfee=mysqli_real_escape_string($conn,$_POST['txtMfee']);

  $orderId = time();
  $upsquery = "update match_details set is_cancel='1', cancel_reason='$txtCanReason' where id={$txtCid} and match_status <> 2 and is_cancel='0'";
  if(mysqli_query($conn,$upsquery))
  {
      $selqueryU = "select user_id from participant_details where match_id=$txtCid";
      $selresU = mysqli_query($conn,$selqueryU);
      
      while ($selres1U = mysqli_fetch_array($selresU)){
          $refundTouser = "insert into transaction_details (user_id, order_id, amount, remark, type, date, wallet) values(".$selres1U['user_id'].", '$orderId', $txtMfee, 'refund - cancel match', '1', $orderId, 'system')";
          if(mysqli_query($conn,$refundTouser))
          {
              $upUserBal = "update user_details set cur_balance=cur_balance+$txtMfee where id=".$selres1U['user_id'];
              mysqli_query($conn,$upUserBal);

          }
          else
          {
              //echo $upUserBal;
              echo '<script type="text/javascript">';
              echo 'setTimeout(function () { swal(
                                                    "Oops...",
                                                    "Something went wrong !!",
                                                    "error"
                                                  );';
              echo '}, 1000);</script>';
          }
      }

      header("Location:match-list.php");
  }
  else
  {
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
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Match list</title>

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
      function checkCancel(){
          return confirm('Are you sure you want to Cancel this Match?');
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
                                <h4 class="m-t-0 header-title"><b>Match List</b></h4>
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
                                  <th>Match Code</th>
                                  <th>Open/Private</th>
                                  <th style="text-align: center;">Status</th>
                                  <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php while ($selres = mysqli_fetch_array($selresult)){ ?>
                                <tr>
                                    <td><?php echo $selres['id']; ?></td>
                                    <td><?php if($selres['is_cancel']=='0') { echo $selres['title']; } else { ?><strike><?php echo $selres['title']; } ?></strike></td>
                                    <td><?php echo $selres['time']; ?></td>
                                    <td><?php echo $selres['entryFee']; ?></td>
                                    <!-- <td><?php //echo $selres['winPrize']; ?></td> -->
                                    <td><?php echo $selres['matchType']; ?></td>
                                    <td><?php if($selres['isPrivateMatch'] == 'yes') { ?><?php echo $selres['private_match_code']; ?><?php } else { ?> - <?php } ?></td>
                                    <td>
                                      <?php if($selres['isPrivateMatch'] == 'yes') { ?>
                                        <span class="label label-inverse">Private</span>
                                      <?php } else { ?>
                                        <span class="label label-success">Open</span>
                                      <?php } ?>
                                    </td>
                                    <?php if ($selres['match_status'] == 0){ ?>
                                      <td style="text-align: center;">
                                        <div class="flip-card">
                                          <div class="flip-card-inner">
                                            <div class="flip-card-front">
                                              Upcoming
                                            </div>
                                            <div class="flip-card-back">
                                              <a class="btn btn-default" href="match-list.php?matchIdOg=<?php echo $selres['id'];?>">Click To Ongoing</a>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    <?php } else if ($selres['match_status'] == 1) { ?>
                                      <td style="text-align: center;">
                                        <div class="flip-card">
                                          <div class="flip-card-inner">
                                            <div class="flip-card-front">
                                              <span style="color: green;">Ongoing</span>
                                            </div>
                                            <div class="flip-card-back">
                                              <a class="btn btn-warning" href="match-list.php?matchIdFn=<?php echo $selres['id'];?>">Click To Finished</a>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    <?php } else if ($selres['match_status'] == 2 or $selres['match_status'] == 3) { ?>
                                      <td style="text-align: center;">Finished</td>
                                    <?php } ?>
                                    
                                    <td class="text-center">
                                      <?php if ($selres['match_status'] == 0) { ?>
                                      <a href="match-detail.php?matchId=<?php echo $selres['id'];?>" class="edit-row" style="color: #29b6f6;" data-toggle="tooltip" data-placement="top" title="Update Match Detail" data-original-title="Update Match Detail"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                      <?php } else { ?>
                                        <i class="fa fa-ban" data-toggle="tooltip" data-placement="top" title="This Match is either Ongoing or Finished" data-original-title=""></i>&nbsp;&nbsp;
                                      <?php } ?>
                                      <a href="view-match-details.php?matchId=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="View Match Details" data-original-title="Room details"><i class="md md-exit-to-app"></i></a>&nbsp;&nbsp;
                                      <?php if ($selres['match_status'] != 2) { ?>
                                        <?php if($selres['is_cancel'] == '0') { ?>
                                            <a onclick="return checkCancel()" data-cid="<?php echo $selres['id'];?>" data-matchfee="<?php echo $selres['entryFee'];?>" href="#" class="remove-row canMatch" style="color: #f05050;" data-toggle="modal" data-target="#myModal"><i class="fa fa-times"></i></a>&nbsp;&nbsp;
                                        <?php } else { ?>
                                            <span data-toggle="tooltip" data-placement="top" title="Match was canceled" data-original-title="Match was canceled"><a href="javascript:void(0)" class="remove-row" style="color: grey;"><i class="fa fa-times" disabled></i></a></span>&nbsp;&nbsp;
                                        <?php } ?>
                                      <a onclick="return checkDelete()" href="match-list.php?matchDid=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="Delete Match" data-original-title="Room details"><i class="fa fa-trash"></i></a>
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
            <h4 class="modal-title">Cancel Match</h4>
          </div>
          <div class="modal-body">
            <form method="post" action="match-list">
            <input type="hidden" id="txtCid" value="" name="txtCid" >
            <input type="hidden" id="txtMfee" value="" name="txtMfee" >
            <label>Reason and Notes</label>
            <textarea class="form-control" name="txtCanReason" placeholder="e.g match was canceled due to fewer participants, your match fees will be credited soon. If you have any query contact admin." required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" name="btnCancel">Submit</button>
            </form>
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
        $(document).on("click", ".canMatch", function () {
             var myrecordId = $(this).data('cid');
             var myrecordmatchfee = $(this).data('matchfee');
             $(".modal-body #txtCid").val( myrecordId );
             $(".modal-body #txtMfee").val( myrecordmatchfee );
        });
    </script>
	</body>
</html>