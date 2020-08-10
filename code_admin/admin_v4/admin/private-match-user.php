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
$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];

if(isset($_GET['matchId']))
{
  $matchId = $_GET['matchId'];
  $txtCdate=date("Y-m-d H:m:s");

    /*$selquery = "select U.id as usrid, U.fname, U.lname, U.username, U.email, U.mobile, U.user_type, U.cur_balance, U.won_balance, U.status, P.match_id from user_details as U
    left join participant_details as P on P.user_id=U.id and (P.match_id=$matchId or P.match_id is null)
    order by U.id desc";
    $selresult = mysqli_query($conn,$selquery);*/

    $selquery = "select id, fname, lname, username from user_details order by id desc";
    $selresult = mysqli_query($conn,$selquery);

    $selqueryP = "select p.id, p.match_id, p.user_id, p.pubg_id, U.username from participant_details as p left join user_details as U on U.id=p.user_id where match_id='$matchId' order by p.id desc";
    $selresultP = mysqli_query($conn,$selqueryP);

    $selqueryM = "select * from match_details as M left join room_details as R on R.match_id=M.id where M.id=$matchId";
    $selresM = mysqli_query($conn,$selqueryM);
    $selres1M = mysqli_fetch_array($selresM);
    $roomSize = $selres1M['room_size'];

    $selqueryCount ="select count(*) as totParti from participant_details where match_id='$matchId'";
    $selresPcount = mysqli_query($conn,$selqueryCount);
    $selres1Pcount = mysqli_fetch_array($selresPcount);
    $partiCount = $selres1Pcount['totParti'];

    if(isset($_POST['btnAddParti']))
    {
      $txtUid = mysqli_real_escape_string($conn,$_POST['txtUid']);
      $txtPbid = mysqli_real_escape_string($conn,$_POST['txtPbid']);
      $txtDate = date("Y-m-d H:i:s");

        if($partiCount<=$roomSize)
        {
          $selquery ="select * from participant_details where pubg_id='$txtPbid' and match_id='$matchId'";
          $selresult = mysqli_query($conn,$selquery);
          if($selres = mysqli_fetch_array($selresult))
          {
              echo "<script>alert(\"Already register\");</script>";
              header("Location:private-match-user.php?matchId=".$matchId);
          }
          else
          {
            $insquery = "insert into participant_details (match_id, user_id, pubg_id, kills, win, position, prize, created) values('$matchId','{$txtUid}','{$txtPbid}',0,0,0,0,'{$txtDate}')";
            if(mysqli_query($conn,$insquery))
            {
              header("Location:private-match-user.php?matchId=".$matchId);
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
        }
        else
        {
            echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "Oops..",
                            text: "Room Size is '.$roomSize.', you can not add more participants..",
                            type: "error"
                        }, function() {
                            window.location = "private-match-user.php?matchId='.$matchId.'";
                        });
                    }, 1000);
                </script>';
            //echo "<script>alert(\"Room Size is ".$roomSize.", you can not add more participants!!\");</script>";
        }
    }


  if(isset($_GET['matchId'])&isset($_GET['partiid']))
  {
    $matchId = $_GET['matchId'];
    $partiid = $_GET['partiid'];
    $delquery = "delete from participant_details where id=$partiid and match_id='$matchId'";
    if(mysqli_query($conn,$delquery))
    {
      header("Location:private-match-user.php?matchId=".$matchId);
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

    <title>User list</title>

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
                <div class="col-sm-6">
                    <div class="card-box table-responsive">
                        <div class="row">
                            <div class="col-sm-10">
                                <h4 class="m-t-0 header-title"><b>Add Participant to <u><?php echo $selres1M['title']; ?></u> </b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Add or remove user to private match.
                                </p>
                            </div>
                        </div>
                          <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>User Name</th>
                                    <th>Add</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php while ($selres = mysqli_fetch_array($selresult)){ ?>
                                  <tr>
                                      <td><?php echo $selres['fname']." ".$selres['lname']; ?></td>
                                      <td><?php echo $selres['username']; ?></td>
                                      <td>
                                        <a href="#" class="btn btn-default btn-xs addPart" data-toggle="modal" data-uid="<?php echo $selres['id']; ?>" data-target="#myModal2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Participant"> <i class="fa fa-long-arrow-right"></i> </a>
                                      </td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                          </table>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card-box table-responsive">
                        <div class="row">
                            <div class="col-sm-10">
                                <h4 class="m-t-0 header-title"><b>Participant of <u><?php echo $selres1M['title']; ?></u> </b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Add or remove user to private match.
                                </p>
                            </div>
                        </div>
                          <table id="datatable-responsive1" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                    <th>Remove</th>
                                    <th>PubG ID</th>
                                    <th>User Name</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php while ($selresP = mysqli_fetch_array($selresultP)){ ?>
                                  <tr>
                                      <td>
                                        <a href="private-match-user.php?matchId=<?php echo $matchId; ?>&partiid=<?php echo $selresP['id']; ?>" class="btn btn-danger btn-xs" data-target="#myModal2" data-toggle="tooltip" data-placement="right" title="" data-original-title="Remove Participant"> <i class="fa fa-long-arrow-left"></i> </a>
                                      </td>
                                      <td><?php echo $selresP['pubg_id']; ?></td>
                                      <td><?php echo $selresP['username']; ?></td>
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

      <!-- Modal -->
      <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2">Add Participant to <?php echo $selres1M['title']; ?></h4>
              </div>

              <div class="modal-body">
                <form role="form" action="private-match-user.php?matchId=<?php echo $_GET['matchId'];?>" method="post" data-parsley-validate novalidate>
                    <input type="hidden" name="txtUid" id="txtUid">
                      <div class="form-group">
                          <label for="txtPbid">Enter PubG Id</label>
                          <input type="text" required class="form-control" id="txtPbid" name="txtPbid" value="">
                      </div>
                      <button type="submit" name="btnAddParti" class="btn btn-default waves-effect waves-light">Add</button>
                      <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-danger waves-effect waves-light m-l-10">Cancel</button>
                </form>    
              </div>

          </div><!-- modal-content -->
        </div><!-- modal-dialog -->
      </div><!-- modal -->

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
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>

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
                "order": []
            } );
            $('#datatable-responsive1').DataTable( {
                "order": []
            } );
        });
        TableManageButtons.init();

    </script>
    <script type="text/javascript">
      
      var limit = <?php echo $selres1M['room_size']; ?>;
      $('input[type=checkbox]').on('change', function (e) {
          if ($('input[type=checkbox]:checked').length > limit) {
              $(this).prop('checked', false);
                alert("Maximum <?php echo $selres1M['room_size']; ?> Participant allow in this match.");
          }
      });
    </script>
    <script type="text/javascript">
        $(document).on("click", ".addPart", function () {
             var myrecordId = $(this).data('uid');
             $(".modal-body #txtUid").val( myrecordId );
             // As pointed out in comments, 
             // it is unnecessary to have to manually call the modal.
             // $('#addBookDialog').modal('show');
        });
    </script>
  </body>
</html>