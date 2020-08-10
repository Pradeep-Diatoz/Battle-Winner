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
$selquery = "select * from user_details order by id desc";
$selresult = mysqli_query($conn,$selquery);

$selquery = "select * from tbl_user_master where uname='$user'";
$selres = mysqli_query($conn,$selquery);
$selres1 = mysqli_fetch_array($selres);
//$full_name = $selres1['fname'] . " " . $selres1['lname'];
$userid = $selres1['user_id'];

$selqueryApp = "select * from tbl_app_details where id=1";
$selresultApp = mysqli_query($conn,$selqueryApp);
$selresApp = mysqli_fetch_array($selresultApp);
$appName = $selresApp['app_name'];

if(isset($_GET['did']))
{
  $did = $_GET['did'];
  $insquery = "delete from user_details where id={$did}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:reg-user-list.php");
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

if(isset($_GET['uid_ia']))
{
  $uid_ia = $_GET['uid_ia'];
  $insquery = "update user_details set status='0' where id={$uid_ia}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:reg-user-list.php");
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

if(isset($_GET['uid_a']))
{
  $uid_a = $_GET['uid_a'];
  $insquery = "update user_details set status='1' where id={$uid_a}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:reg-user-list.php");
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

if(isset($_GET['uid_b']))
{
  $uid_b = $_GET['uid_b'];
  $insquery = "update user_details set status='0', is_block=1 where id={$uid_b}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:reg-user-list.php");
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

if(isset($_GET['uid_ub']))
{
  $uid_ub = $_GET['uid_ub'];
  $insquery = "update user_details set status='1', is_block=0 where id={$uid_ub}";
  if(mysqli_query($conn,$insquery))
  {
    header("Location:reg-user-list.php");
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

if(isset($_POST['btnAddMoney']))
{
  $txtUid=mysqli_real_escape_string($conn,$_POST['txtUid']);
  $txtAmoney=mysqli_real_escape_string($conn,$_POST['txtAmoney']);
  $txtEmail=mysqli_real_escape_string($conn,$_POST['txtEmail']);
  $txtUname=mysqli_real_escape_string($conn,$_POST['txtUname']);
  $txtCdate=date("Y-m-d H:m:s");
  $orderid=time();
  
  $insquery = "insert into transaction_details (user_id,order_id,amount,remark,type,date,wallet) values($txtUid,'$orderid','{$txtAmoney}','Add Money to Wallet','credit','{$txtCdate}','offline')";
  if(mysqli_query($conn,$insquery))
  {
    $upquery = "update user_details set cur_balance=cur_balance+$txtAmoney where id={$txtUid}";
    if(mysqli_query($conn,$upquery))
    {
      $txtEmail = $txtEmail;
      $mailSubject = "Transaction Successful - $appName";
      $message="<h2>Hi, $txtUname</h2>
      <p>Thank you for transaction with us. Your payment was successfully completed and your wallet is credited with $txtAmoney.<br>
        $appName Transaction Id: $orderid <br>
        If you have query regarding this contact admin immediately.</p>";
      
      include("include/verify_mail.php");

      header("Location:reg-user-list.php");
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

if(isset($_POST['btnWithMoney']))
{
  $txtUidW=mysqli_real_escape_string($conn,$_POST['txtUidW']);
  $txtWmoney=mysqli_real_escape_string($conn,$_POST['txtWmoney']);
  $txtUnameW=mysqli_real_escape_string($conn,$_POST['txtUnameW']);
  $txtEmailW=mysqli_real_escape_string($conn,$_POST['txtEmailW']);
  $txtCdate=date("Y-m-d H:m:s");
  $orderidW=time();
  
  $selqueryWB = "select cur_balance from user_details where id=$txtUidW";
  $selresWB = mysqli_query($conn,$selqueryWB);
  $selres1WB = mysqli_fetch_array($selresWB);

  if($selres1WB['cur_balance'] >= $txtWmoney)
  {
      $insquery = "insert into transaction_details (user_id,order_id,amount,remark,type,date,wallet) values($txtUidW,'$orderidW','{$txtWmoney}','Withdraw Money from Wallet','debit','{$txtCdate}','offline')";
      if(mysqli_query($conn,$insquery))
      {
        $upquery = "update user_details set cur_balance=cur_balance-$txtWmoney where id={$txtUidW}";
        if(mysqli_query($conn,$upquery))
        {
            $txtEmail = $txtEmailW;
            $mailSubject = "Transaction Successful - $appName";
            $message="<h2>Hi, $txtUname</h2>
            <p>Thank you for transaction with us. Your payment was successfully completed and your wallet is debited with $txtWmoney.<br>
              $appName Transaction Id: $orderidW <br>
              If you have query regarding this contact admin immediately.</p>";
            
            include("include/verify_mail.php");

            header("Location:reg-user-list.php");
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
      else
      {
        // echo $insquery;
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
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal(
                                            "Oops...",
                                            "Insufficient Fund!",
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
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <div class="row">
                            <div class="col-sm-10">
                                <h4 class="m-t-0 header-title"><b>Manage User</b></h4>
                                <p class="text-muted font-13 m-b-30">
                                    List of register user. here you can manage User.
                                </p>
                            </div>
                            <!-- <div class="col-sm-2">
                                <div class="m-t-0 text-right">
                                    <a href="user.php" class="btn btn-default waves-effect waves-light"><i class="fa fa-plus"></i> Add</a>
                                </div>
                            </div> -->
                        </div>
                        
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                  <th>Full Name</th>
                                  <th>User Name</th>
                                  <th>Email</th>
                                  <th>Mobile</th>
                                  <th>Won Bal</th>
                                  <th>Bonus Bal</th>
                                  <th>Tot Bal</th>
                                  <th>Status</th>
                                  <th>Block</th>
                                  <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php while ($selres = mysqli_fetch_array($selresult)){ ?>
                                <tr>
                                    <td><?php echo $selres['fname']." ".$selres['lname']; ?></td>
                                    <td><?php echo $selres['username']; ?></td>
                                    <td><?php echo $selres['email']; ?></td>
                                    <td><?php echo $selres['mobile']; ?></td>
                                    <td><?php echo $selres['won_balance']; ?></td>
                                    <td><?php echo $selres['bonus_balance']; ?></td>
                                    <td><?php echo $selres['cur_balance']; ?></td>
                                    
                                    <?php if ($selres['status'] == 1){ ?>
                                      <td><a href="reg-user-list.php?uid_ia=<?php echo $selres['id'];?>" class="label label-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to Inactive">Active</a></td>
                                    <?php } else { ?>
                                      <td><a href="reg-user-list.php?uid_a=<?php echo $selres['id'];?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to Active" class="label label-danger">Inactive</a></td>
                                    <?php } ?>

                                    <?php if ($selres['is_block'] != 1){ ?>
                                      <td><a href="reg-user-list.php?uid_b=<?php echo $selres['id'];?>" class="label label-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to Block">Active</a></td>
                                    <?php } else { ?>
                                      <td><a href="reg-user-list.php?uid_ub=<?php echo $selres['id'];?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Click to Unblock" class="label label-danger">Blocked</a></td>
                                    <?php } ?>
                                    
                                    <td>
                                      <a href="reg-user-list.php?did=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Permanently" onclick="return checkDelete()"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp; 
                                      
                                      <a href="#" data-toggle="modal" data-id="<?php echo $selres['id']; ?>" data-uname="<?php echo $selres['username']; ?>" data-email="<?php echo $selres['email']; ?>" data-cbal="<?php echo $selres['cur_balance']; ?>" data-target="#myModal2" class="addBal" data-toggle="tooltip" data-placement="top" title="" data-original-title="Load Money"><i class="fa fa-money"></i> </a>&nbsp;&nbsp;

                                      <a href="view-user-details.php?userId=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="View User Details" data-original-title="User details"><i class="md md-exit-to-app"></i></a>&nbsp;&nbsp;

                                      <a href="referral-details.php?rcode=<?php echo $selres['refer'];?>&rid=<?php echo $selres['id'];?>" class="remove-row" style="color: #f05050;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Track User Refer Activity"><i class="fa  fa-line-chart"></i></a>&nbsp;&nbsp;

                                      <a href="participation-report.php?rid=<?php echo $selres['id'];?>" class="remove-row" style="color: #5FBEAA;" data-toggle="tooltip" data-placement="top" title="" data-original-title="User Participation"><i class="fa fa-list"></i></a>
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

      <!-- Modal -->
      <div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel2">Add/Withdraw Money</h4>
            </div>

            <div class="modal-body">

              <div class="row">
                  <div class="col-md-12">
                      <ul class="nav nav-tabs tabs">
                          <li class="tab">
                              <a href="#add-Money" data-toggle="tab" aria-expanded="false">
                                  <span class="visible-xs"><i class="fa fa-plus"></i></span>
                                  <span class="hidden-xs">Add Money</span>
                              </a>
                          </li>
                          <li class="tab">
                              <a href="#withdraw-Money" data-toggle="tab" aria-expanded="false">
                                  <span class="visible-xs"><i class="fa fa-minus"></i></span>
                                  <span class="hidden-xs">Withdraw Money</span>
                              </a>
                          </li>
                      </ul>
                      <div class="tab-content"> 
                          <div class="tab-pane active" id="add-Money"> 
                              <form role="form" action="reg-user-list.php" method="post" data-parsley-validate novalidate>
                                  <input type="text" hidden name="txtUid" id="txtUid">
                                    <div class="form-group">
                                        <label for="txtUname">User Name</label>
                                        <input type="text" readonly class="form-control" id="txtUname" name="txtUname" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtEmail">Email</label>
                                        <input type="text" readonly class="form-control" id="txtEmail" name="txtEmail" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtTbal">Total Balance</label>
                                        <input type="text" readonly class="form-control" id="txtTbal" name="txtTbal" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtAmoney">Add Money to Wallet </label>
                                        <input type="number" class="form-control" id="txtAmoney" required parsley-trigger="change" name="txtAmoney">
                                    </div>
                                    
                                    <button type="submit" name="btnAddMoney" class="btn btn-default waves-effect waves-light">Save</button>
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-danger waves-effect waves-light m-l-10">Cancel</button>
                              </form>
                          </div>
                          <div class="tab-pane" id="withdraw-Money">
                              <form role="form" action="reg-user-list.php" method="post" data-parsley-validate novalidate>
                                  <input type="text" hidden name="txtUidW" id="txtUidW">
                                    <div class="form-group">
                                        <label for="txtUnameW">User Name</label>
                                        <input type="text" readonly class="form-control" id="txtUnameW" name="txtUnameW" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtEmail">Email</label>
                                        <input type="text" readonly class="form-control" id="txtEmailW" name="txtEmailW" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtTbal">Total Balance</label>
                                        <input type="text" readonly class="form-control" id="txtTbalW" name="txtTbalW" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtWmoney">Withdraw Money from Wallet </label>
                                        <input type="number" class="form-control" id="txtWmoney" required parsley-trigger="change" name="txtWmoney">
                                    </div>
                                    
                                    <button type="submit" name="btnWithMoney" class="btn btn-default waves-effect waves-light">Save</button>
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-danger waves-effect waves-light m-l-10">Cancel</button>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
              
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
        });
        TableManageButtons.init();

    </script>
    <script type="text/javascript">
        $(document).on("click", ".addBal", function () {
             var myrecordId = $(this).data('id');
             var myRuname = $(this).data('uname');
             var myREmail = $(this).data('email');
             var myRcbal = $(this).data('cbal');
             $(".modal-body #txtUid").val( myrecordId );
             $(".modal-body #txtUname").val( myRuname );
             $(".modal-body #txtEmail").val( myREmail );
             $(".modal-body #txtTbal").val( myRcbal );
             $(".modal-body #txtUidW").val( myrecordId );
             $(".modal-body #txtUnameW").val( myRuname );
             $(".modal-body #txtEmailW").val( myREmail );
             $(".modal-body #txtTbalW").val( myRcbal );
             // As pointed out in comments, 
             // it is unnecessary to have to manually call the modal.
             // $('#addBookDialog').modal('show');
        });
    </script>
  </body>
</html>