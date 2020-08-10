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
$selqueryApp = "select * from tbl_app_details where id=1";
$selresultApp = mysqli_query($conn,$selqueryApp);
$selresApp = mysqli_fetch_array($selresultApp);
$appName = $selresApp['app_name'];

$selquery = "select t.*, u.fname, u.lname, u.email, u.mobile, u.cur_balance, u.won_balance, u.whatsapp_num, u.pubg_username from transaction_details as t
left join user_details as u on u.id=t.user_id
where t.type=0
order by t.id desc";
$selresult = mysqli_query($conn,$selquery);

/*$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];*/

if(isset($_GET['withdrawId']))
{
  $withdrawId = $_GET['withdrawId'];
  $insquery = "update transaction_details set status=1 where id={$withdrawId}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:withdrawal-list.php");
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


if(isset($_POST['btnReject']))
{
  $txtRid=mysqli_real_escape_string($conn,$_POST['txtRid']);
  $txtUid=mysqli_real_escape_string($conn,$_POST['txtUid']);
  $txtUcoin=mysqli_real_escape_string($conn,$_POST['txtUcoin']);
  $txtRemail=mysqli_real_escape_string($conn,$_POST['txtRemail']);
  $txtRejReason=mysqli_real_escape_string($conn,$_POST['txtRejReason']);
  $txtRname=mysqli_real_escape_string($conn,$_POST['txtRname']);

  $insquery = "update transaction_details set status=2, remark='$txtRejReason' where id=$txtRid";
    if(mysqli_query($conn,$insquery))
    {
      $seluDet = "select * from transaction_details where id=$txtRid";
      $selres4 = mysqli_query($conn,$seluDet);
      if($selres48 = mysqli_fetch_array($selres4))
      {
        $upUsrBal = "update user_details set cur_balance=cur_balance+".$selres48['coins'].", won_balance=won_balance+".$selres48['coins']." where id=".$selres48['user_id'];
        mysqli_query($conn,$upUsrBal);       

        $txtEmail = $txtRemail;
        $mailSubject = "Withdraw Money Request Decline - $appName";
        $message="<center><h2>Dear, $txtRname</h2></center>
        <p>Your request for withdrawal has been rejected due to following reason, $txtRejReason.</p>";
        
        include("include/verify_mail.php");

        header("Location:withdrawal-list");
      }
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

    <title>Withdrawal list</title>

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

            <!-- Page Content -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <div class="row">
                            <div class="col-sm-10">
                                <h4 class="m-t-0 header-title"><b>Withdraw List</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    Proceed withdraw request here.
                                </p>
                            </div>
                        </div>
                        
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                  <th>Id</th>
                                  <!-- <th>Order Id</th> -->
                                  <th>Register Name</th>
                                  <!-- <th>Email</th> -->
                                  <th>Coin</th>
                                  <!-- <th>Winning Prize</th> -->
                                  <th>Amount</th>
                                  <th>Wallet</th>
                                  <th>Holder Name</th>
                                  <th>Account Id</th>
                                  <th>Req. Date</th>
                                  <th style="text-align: center;">Status</th>
                                  <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php while ($selres = mysqli_fetch_array($selresult)){ ?>
                                <tr>
                                    <td><?php echo $selres['id']; ?></td>
                                    <!-- <td><?php //echo $selres['order_id']; ?></td> -->
                                    <td>
                                      <span data-toggle="tooltip" data-html="true" title="
                                        <h4 style='color:#fff;'>User Details :</h4>
                                        <ul style='text-align:left; padding-left:0px; list-style:none;'>
                                          <li>Pubg Name: <?php echo $selres['pubg_username']; ?></li>
                                          <li><i class='fa fa-envelope'></i> <?php echo $selres['email']; ?></li>
                                          <li><i class='fa fa-phone'></i> <?php echo $selres['mobile']; ?></li>
                                          <li><i class='fa fa-dollar'></i> <?php echo $selres['cur_balance']; ?></li>
                                          <li><i class='fa fa-whatsapp'></i> <?php echo $selres['whatsapp_num']; ?></li>
                                        </ul>" >
                                      <?php echo $selres['fname']." ".$selres['lname']; ?>
                                      </span>
                                    </td>
                                    <!-- <td><?php //echo $selres['email']; ?></td> -->
                                    <td><?php echo $selres['coins']; ?></td>
                                    <!-- <td><?php //echo $selres['winPrize']; ?></td> -->
                                    <td><?php echo $selres['amount']; ?></td>
                                    <td><?php echo $selres['wallet']; ?></td>
                                    <td><?php echo $selres['account_holder_name']; ?></td>
                                    <td><?php echo $selres['account_holder_id']; ?></td>
                                    <td><?php echo date('d-m-Y H:i:s', $selres['date']); ?></td>

                                    <?php if ($selres['status'] == 0){ ?>
                                      <td>
                                        <div class="flip-card">
                                          <div class="flip-card-inner">
                                            <div class="flip-card-front">
                                              Pending
                                            </div>
                                            <div class="flip-card-back">
                                              <a class="btn btn-success" href="withdrawal-list.php?withdrawId=<?php echo $selres['id'];?>" data-toggle="tooltip" data-placement="top" title="Accept" data-original-title="Accept"><i class="fa fa-check"></i></a>
                                              
                                              <a href="#" data-rid="<?php echo $selres['id'];?>" data-uid="<?php echo $selres['user_id'];?>" data-withCoin="<?php echo $selres['coins'];?>" data-remail="<?php echo $selres['email']; ?>" data-rname="<?php echo $selres['fname']." ".$selres['lname']; ?>" class="btn btn-danger rejectreq" data-toggle="modal" data-target="#myModal"><i class="fa fa-times"></i></a>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                    <?php } else { ?>
                                      <?php if ($selres['status']==1){ ?>
                                        <td style="text-align: center; color: green;"> Completed</td>
                                      <?php } else if ($selres['status']==2) { ?>
                                        <td style="text-align: center; color: red;"> Rejected</td>
                                      <?php } ?>
                                    <?php } ?>
                                    
                                    <td>
                                      <a class="btn btn-xs btn-primary" href="withdrawal-detail.php?withdrawId=<?php echo $selres['id'];?>" class="edit-row" style="color: #29b6f6;" data-toggle="tooltip" data-placement="top" title="View Details" data-original-title="View Details"><i class="fa fa-external-link"></i></a>&nbsp;
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

    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Reject Withdraw Request</h4>
          </div>
          <div class="modal-body">
            <form method="post" action="withdrawal-list">
            <input type="hidden" id="txtRid" value="" name="txtRid" >
            <input type="hidden" id="txtUid" value="" name="txtUid" >
            <input type="hidden" id="txtUcoin" value="" name="txtUcoin" >
            <input type="hidden" id="txtRemail" value="" name="txtRemail">
            <input type="hidden" id="txtRname" value="" name="txtRname">
            <label>Reject Reason</label>
            <textarea class="form-control" name="txtRejReason" placeholder="e.g Insufficient Fund" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" name="btnReject">Reject</button>
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
        $(document).on("click", ".rejectreq", function () {
             var myrecordId = $(this).data('rid');
             var myuserId = $(this).data('uid');
             var myuserCoin = $(this).data('withCoin');
             var myReemailId = $(this).data('remail');
             var myRname = $(this).data('rname');
             $(".modal-body #txtRid").val( myrecordId );
             $(".modal-body #txtUid").val( myuserId );
             $(".modal-body #txtUcoin").val( myuserCoin );
             $(".modal-body #txtRemail").val( myReemailId );
             $(".modal-body #txtRname").val( myRname );
        });
    </script>
  </body>
</html>