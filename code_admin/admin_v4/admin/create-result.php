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

if(isset($_GET['matchId']))
{
  $matchId = $_GET['matchId'];
  $selquery1 = "select * from participant_details where match_id={$matchId} order by id desc";
  $getresult1 = mysqli_query($conn,$selquery1);
  
  $selquery1 = "select * from match_details where id={$matchId}";
  $getresult4 = mysqli_query($conn,$selquery1);
  $getres4 = mysqli_fetch_array($getresult4);

  /*disable button if winner calculation*/
  
  $selquery12 = "select * from participant_details where match_id={$matchId} and win=10";
  $getresult41 = mysqli_query($conn,$selquery12);
  //$getres41 = mysqli_fetch_array($getresult41);
  $selres41 = mysqli_num_rows($getresult41);

  /*end disable button if winner calculation*/

  if(isset($_POST['btnAddNote']))
  {
    $txtNote = mysqli_real_escape_string($conn,$_POST['txtNote']);

      $upquery4 = "update match_details set matchNotes='$txtNote' where id={$matchId}";
      if(mysqli_query($conn,$upquery4))
      {
        header("Location:create-result.php?matchId=".$matchId);
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
  /*UPDATE*/
  if(isset($_POST['btnUpdateKill']))
  {

    $txtTkills = mysqli_real_escape_string($conn,$_POST['txtTkills']);
    $recordId = mysqli_real_escape_string($conn,$_POST['recordId']);
    $txtUserId1 = mysqli_real_escape_string($conn,$_POST['txtUserId1']);
    $prize = $txtTkills*$getres4['perKill'];

    /*for update*/
    $txtTkillsU = mysqli_real_escape_string($conn,$_POST['txtTkillsU']);
    $recordIdU = mysqli_real_escape_string($conn,$_POST['recordIdU']);
    $txtUserId1U = mysqli_real_escape_string($conn,$_POST['txtUserId1U']);
    $prizeU = $txtTkillsU*$getres4['perKill'];

    $insqueryU = "update participant_details set kills=$txtTkillsU,prize=prize-$prizeU where id={$recordIdU}";
    if(mysqli_query($conn,$insqueryU))
    {

      $insquery4U = "update user_details set won_balance=won_balance-$prizeU,cur_balance=cur_balance-$prizeU where id={$txtUserId1U}";
      mysqli_query($conn,$insquery4U);

      //$did = $_GET['did'];
      $insquery = "update participant_details set kills=$txtTkills,prize=prize+$prize where id={$recordId}";
    
      if(mysqli_query($conn,$insquery))
      {
        //$lastId = mysqli_insert_id($conn);
        $insquery4 = "update user_details set won_balance=won_balance+$prize,cur_balance=cur_balance+$prize where id={$txtUserId1}";
        mysqli_query($conn,$insquery4);
        header("Location:create-result.php?matchId=".$matchId);
      }

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

  //update participant_details set kills=$txtTkills,prize=$prize 

  if(isset($_POST['btnSubmit'])){
    
    $getMatchType = $getres4['matchType'];

    /*result announced*/
    $upResAnnounced = "update match_details set match_status=3 where id={$matchId}";
    mysqli_query($conn,$upResAnnounced);
    /*result announced end*/
    if(isset($_POST['chkWinner']))
    {
      $checkbox = $_POST['chkWinner'];
      // $lenAry = sizeof($checkbox);
    }
    // else
    // {
    //   $lenAry = 1;
    // }

    if($getMatchType=='Solo')
    {
      $lenAry = 1;
    }
    else if($getMatchType=='Duo')
    {
      $lenAry = 2;
    }
    else if($getMatchType=='Squad')
    {
      $lenAry = 4;
    }
    else
    {
      $lenAry = 1;
    }
      //echo"<script>alert(\"$lenAry\");</script>";
      $winprizeDstrbn = $getres4['winPrize'] / $lenAry;
      $winprizeDstrbn1 = round($winprizeDstrbn);
      //echo"<script>alert(\"$winprizeDstrbn1\");</script>";
    

    /*for update*/

    $getUserIdU = mysqli_query($conn,"select user_id from participant_details where win=1 and match_id='$matchId'");
    /*$getUserIdU1 = mysqli_query($conn,"select count(user_id) from participant_details where win=1 and match_id='$matchId'");
      $getres88Ulen = mysqli_fetch_array($getUserIdU1);
      //$lenAry2 = count($getres88Ulen);
      $lenAry2 = $getres88Ulen['0'];
      if($lenAry2==0)
      {
        $lenAry2=1;
      }
      else
      {
        $lenAry2=$lenAry2;
      }*/

      if($getMatchType=='Solo')
      {
        $lenAry2 = 1;
      }
      else if($getMatchType=='Duo')
      {
        $lenAry2 = 2;
      }
      else if($getMatchType=='Squad')
      {
        $lenAry2 = 4;
      }
      else
      {
        $lenAry2 = 1;
      }

      //echo"<script>alert(\"success=$lenAry2\");</script>";
      $winprizeDstrbnUP = $getres4['winPrize']/$lenAry2;
      //$winprizeDstrbnUP = 10/2;
      //echo"<script>alert(\"Divi=$winprizeDstrbnUP\");</script>";
      $winprizeDstrbn1Up = round($winprizeDstrbnUP);
      //echo"<script>alert(\"round value=$winprizeDstrbn1Up\");</script>";
        
        while($getres88U = mysqli_fetch_array($getUserIdU))
        {
          $testQ="update user_details set won_balance=won_balance-$winprizeDstrbn1Up, cur_balance=cur_balance-$winprizeDstrbn1Up WHERE id='".$getres88U['user_id']."'";
            if(mysqli_query($conn,$testQ))
            {
              //echo"<script>alert(\"success=$testQ\");</script>";

            }
            else
            {
              //echo"<script>alert(\"fail=$testQ\");</script>"; 
              echo"<script>alert(\"Something went wrong\");</script>"; 
            }
        }
        


        //$pgID = $getres88U['pubg_id'];
        // $userId4U = $getres88U['user_id'];
        // $testSize = count($getres88U);
        // echo"<script>alert(\"$testSize\");</script>";

        /*for($j=0;$j<count($userId4U);$j++)
          //for($i=0;$i<2;$i++)
          $userId4U4 = $userId4U[$j];
          {*/
          /*for($i=0;$i<count($getres88U);$i++)
          {
            $del_id = $userId4U;
            //$usDecqry="update user_details set won_balance=won_balance-$winprizeDstrbn1, cur_balance=cur_balance-$winprizeDstrbn1 WHERE id='".$del_id."'";
            $testQ="update user_details set won_balance=won_balance-$winprizeDstrbn1, cur_balance=cur_balance-$winprizeDstrbn1 WHERE id='".$del_id."'";
            if(mysqli_query($conn,$testQ))
            {
              echo"<script>alert(\"$testQ\");</script>";
            }
            else
            {
              echo"<script>alert(\"$testQ\");</script>"; 
            }
          }*/
      //echo"<script>alert(\"round value=$winprizeDstrbn1Up\");</script>";
          
    $uppartWinPrize = "update participant_details set prize=prize-$winprizeDstrbn1Up, win=0 where win=1 and match_id='$matchId'";
    if(mysqli_query($conn,$uppartWinPrize))
    {
      
          for($i=0;$i<count($checkbox);$i++)
          {
            $del_id = $checkbox[$i]; 
            if(mysqli_query($conn,"update participant_details set prize=prize+$winprizeDstrbn1, win=1 WHERE id='".$del_id."'"))
            {
              //$last_id = mysqli_insert_id($conn);
              
              if($getUserId = mysqli_query($conn,"select user_id from participant_details WHERE id='".$del_id."'"))
              {
                $getres88 = mysqli_fetch_array($getUserId);
                $userId4 = $getres88['user_id'];

                mysqli_query($conn,"update user_details set won_balance=won_balance+$winprizeDstrbn1, cur_balance=cur_balance+$winprizeDstrbn1 WHERE id='".$userId4."'");
                //echo"<script>alert(\"test inc\");</script>";
              }

              $message = "Successfully Distribute Prize...";
            }
          }
        
    }
    else
    {
      echo"<script>alert(\"$uppartWinPrize\");</script>";
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
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Create Result</title>

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
                                      Add score to particular participant, generate result and prize.
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
                          <form name=form1 method="post" action="create-result.php?matchId=<?php echo $_GET['matchId'];?>">
                          <div class="row">
                            <div class="col-md-6">
                              <!-- <?php //if($selres41==0) { ?>
                                <a href="winner-and-prize.php?WmatchId=<?php //echo $matchId ;?>" class="btn btn-primary">Find Winner</a>
                              <?php //} else { ?>
                                <a class="btn btn-inverse" data-container="body" title="" data-toggle="popover" data-placement="right" data-content="Winner already declared" data-original-title="">Find Winner</a>
                              <?php //} ?> -->

                              <?php if($selres41==0) { ?>
                                <button class="btn btn-primary" name="btnSubmit" id="btnSubmit" type="submit">Submit Winner</button>
                              <?php } else { ?>
                                <a class="btn btn-inverse" data-container="body" title="" data-toggle="popover" data-placement="right" data-content="Winner already declared" data-original-title="">Submit Winner</a>
                              <?php } ?>
                              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal2A">Add Note</a>
                            </div>
                          </div><br>
                          
                          <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                    <th>User Id</th>
                                    <th>Pubg Id</th>
                                    <!-- <th>Access Key</th> -->
                                    <th>Name</th>
                                    <th>Total Kill</th>
                                    <th>Winning Amt</th>
                                    <th>Winner</th>
                                    <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>

                                <?php while ($selres = mysqli_fetch_array($getresult1)){ ?>
                                  <tr>
                                      <input type="hidden" value="<?php echo $selres['user_id']; ?>" name="txtUserId[]">
                                      <td><?php echo $selres['user_id']; ?></td>
                                      <td><?php echo $selres['pubg_id']; ?></td>
                                      <!-- <td><?php //echo $selres['access_key']; ?></td> -->
                                      <td><?php echo $selres['name']; ?></td>
                                      <td><?php echo $selres['kills']; ?></td>
                                      <td><?php echo $selres['prize']; ?></td>
                                      <?php if($selres41==0) { ?>
                                      <td><input type="checkbox" name="chkWinner[]" value="<?php echo $selres['id']; ?>" <?php if($selres['win']==1){ echo "checked"; } ?>></td>
                                      <?php } else { ?>
                                      <td><input type="checkbox" name="chkWinner[]" value="<?php echo $selres['id']; ?>" disabled <?php if($selres['win']==1){ echo "checked"; } ?>></td>
                                      <?php } ?> 
                                      <?php if($selres41==0) { ?>
                                      <td>
                                        <a href="#" data-toggle="modal" data-id="<?php echo $selres['id']; ?>" data-kills="<?php echo $selres['kills']; ?>" data-userid1="<?php echo $selres['user_id']; ?>" data-target="#myModal2" class="btn btn-default updateKill">Add Score</a>
                                      </td>
                                      <?php } else { ?>
                                      <td>
                                        <button disabled class="btn btn-default" data-toggle="tooltip" data-placement="top" title="" data-original-title="Winner declared, You can not modify!">Add Score</button>
                                      </td>
                                      <?php } ?>
                                  </tr>
                                <?php } ?>
                              </tbody>
                          </table>
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

      <!-- Modal -->
        <div class="modal right fade" id="myModal2A" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2">Add Note</h4>
              </div>

              <div class="modal-body">
                <form role="form" action="create-result.php?matchId=<?php echo $_GET['matchId'];?>" method="post" data-parsley-validate novalidate>
                      <div class="form-group">
                          <label for="txtNote">Note (Any notice to users of this match)</label>
                          <textarea class="form-control" id="txtNote" name="txtNote"><?php echo $getres4['matchNotes']; ?></textarea>
                      </div>
                      <button type="submit" name="btnAddNote" class="btn btn-default waves-effect waves-light">Save</button>
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
                      <input type="hidden" name="txtUserId1" id="txtUserId1" value=""/>
                      <input type="text" name="recordId" id="recordId" hidden value=""/>
                      <label for="txtTkills">Total Kills *</label>
                      <input type="number" max="100" name="txtTkills" placeholder="Enter Number of Kills" class="form-control" id="txtTkills">
                      <!-- for update -->
                      <input type="hidden" name="txtUserId1U" id="txtUserId1U">
                      <input type="hidden" name="recordIdU" id="recordIdU">
                      <input type="hidden" name="txtTkillsU" id="txtTkillsU">
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
      
      <?php if($getres4['matchType']=='Solo')
        {
          $selLimit=1;
        }
        elseif($getres4['matchType']=='Duo') 
        {
          $selLimit=2;
        }
        elseif($getres4['matchType']=='Squad') 
        {
          $selLimit=4;
        }
        else
        {
          $selLimit=1;
        }
      ?>
      var limit = <?php echo $selLimit; ?>;
      $('input[type=checkbox]').on('change', function (e) {
          if ($('input[type=checkbox]:checked').length > limit) {
              $(this).prop('checked', false);
              if (limit==1) {
                alert("Match type is SOLO, There is only 1 winner. ");
              }
              else if (limit==2) {
                alert("Match type is DUO, There are maximum 2 winner. ");
              }
              else if (limit==4) {
                alert("Match type is SQUAD, There are maximum 4 winner. ");
              }
              else{
                alert("Select at least One Winner. ");
              }
          }
      });
    </script>
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
             var myUserId = $(this).data('userid1');
             $(".modal-body #recordId").val( myrecordId );
             $(".modal-body #txtTkills").val( myrecordKills );
             $(".modal-body #txtUserId1").val( myUserId );
             /*for update*/
             $(".modal-body #recordIdU").val( myrecordId );
             $(".modal-body #txtTkillsU").val( myrecordKills );
             $(".modal-body #txtUserId1U").val( myUserId );
             // As pointed out in comments, 
             // it is unnecessary to have to manually call the modal.
             // $('#addBookDialog').modal('show');
        });
    </script>
  </body>
</html>