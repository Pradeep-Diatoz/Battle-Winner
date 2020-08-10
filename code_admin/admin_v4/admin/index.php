<?php 
include("include/security.php");
include("include/conn.php");

if(isset($_POST['btnChngPass']))
{

  $opass=$_POST['oldpass'];
  $oldpass=sha1($opass);

  //$oldpass1 =$_POST['oldpass'];

//  $oldpass=sha1($oldpass1);
 // $opass = sha1($oldpass);

  $password2 = $_POST['password2'];
//  $npass = sha1($password2);
  $enpassnew = sha1($password2);
  $pass = "select password from tbl_user_master where uname='{$user}'";
  $passresult = mysqli_query($conn,$pass);
  $passres = mysqli_fetch_array($passresult);
  $password = $passres['0'];
  
  if($oldpass==$password)
  {
      $chngquery = "update tbl_user_master set password='{$enpassnew}' where uname='{$user}' and password='{$oldpass}'";

      if(mysqli_query($conn,$chngquery))
      {
        header("Location:logout.php");
      }
      else
      {
        //echo $chngquery;
        echo"<script>alert(\"Something went wrong\");</script>";
      }
  }
  else
  {
      //echo "Password is Incorrect";
      echo"<script>alert(\"Password is Incorrect\");</script>";
      //header("Location:index");
  }
  
}

$getquery42 = "select count(id) from match_details";
$getresult42 = mysqli_query($conn,$getquery42);
$getres42 = mysqli_fetch_array($getresult42);

$getquery43 = "select count(id) from match_details where match_status='1'";
$getresult43 = mysqli_query($conn,$getquery43);
$getres43 = mysqli_fetch_array($getresult43);

$getquery44 = "select count(id) from match_details where match_status='0'";
$getresult44 = mysqli_query($conn,$getquery44);
$getres44 = mysqli_fetch_array($getresult44);

$getquery45 = "select count(id) from match_details where match_status='2'";
$getresult45 = mysqli_query($conn,$getquery45);
$getres45 = mysqli_fetch_array($getresult45);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title></title>

        <?php include_once("include/head-section.php"); ?>

    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <?php include_once("include/navbar.php"); ?>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <!-- <div class="btn-group pull-right m-t-15">
                                    <button type="button" class="btn btn-default dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false">Settings <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
                                    <ul class="dropdown-menu drop-menu-right" role="menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div> -->

                                <h4 class="page-title">Dashboard</h4>
                                <p class="text-muted page-title-alt">Welcome to <?php echo $selres4Nav['app_name']; ?> admin panel !</p>
                            </div>
                        </div>

						<div class="row">
							<div class="col-lg-4">
								<div class="card-box">
									<div class="bar-widget">
										<div class="table-box">
											<div class="table-detail">
												<div class="iconbox bg-info">
													<i class="icon-layers"></i>
												</div>
											</div>

											<div class="table-detail">
											   <h4 class="m-t-0 m-b-5"><b><?php echo $getres42['0']; ?></b></h4>
											   <p class="text-muted m-b-0 m-t-0">Total Match</p>
											</div>
                                            <!-- <div class="table-detail text-right">
                                              <span data-plugin="peity-bar" data-colors="#34d3eb,#ebeff2" data-width="120" data-height="45">5,3,9,6,5,9,7,3,5,2,9,7,2,1</span>
                                            </div> -->

										</div>
									</div>
								</div>
							</div>

                            <div class="col-lg-4">
								<div class="card-box">
									<div class="bar-widget">
										<div class="table-box">
											<div class="table-detail">
												<div class="iconbox bg-custom">
													<i class="icon-layers"></i>
												</div>
											</div>

											<div class="table-detail">
											   <h4 class="m-t-0 m-b-5"><b><?php echo $getres43['0']; ?></b></h4>
											   <p class="text-muted m-b-0 m-t-0">Ongoing Match</p>
											</div>
                                            <!-- <div class="table-detail text-right">
                                              <span data-plugin="peity-pie" data-colors="#5fbeaa,#ebeff2" data-width="50" data-height="45">1/5</span>
                                            </div> -->

										</div>
									</div>
								</div>
							</div>

                            <div class="col-lg-4">
                                <div class="card-box">
                                    <div class="bar-widget">
                                        <div class="table-box">
                                            <div class="table-detail">
                                                <div class="iconbox bg-warning">
                                                    <i class="icon-layers"></i>
                                                </div>
                                            </div>

                                            <div class="table-detail">
                                               <h4 class="m-t-0 m-b-5"><b><?php echo $getres44['0']; ?></b></h4>
                                               <p class="text-muted m-b-0 m-t-0">Upcoming Match</p>
                                            </div>
                                            <!-- <div class="table-detail text-right">
                                              <span data-plugin="peity-pie" data-colors="#5fbeaa,#ebeff2" data-width="50" data-height="45">1/5</span>
                                            </div> -->

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
								<div class="card-box">
									<div class="bar-widget">
										<div class="table-box">
											<div class="table-detail">
												<div class="iconbox bg-danger">
													<i class="icon-layers"></i>
												</div>
											</div>

											<div class="table-detail">
											   <h4 class="m-t-0 m-b-5"><b><?php echo $getres45['0']; ?></b></h4>
											   <p class="text-muted m-b-0 m-t-0">Completed Match</p>
											</div>
                                            <!-- <div class="table-detail text-right">
                                              <span data-plugin="peity-donut" data-colors="#f05050,#ebeff2" data-width="50" data-height="45">1/5</span>
                                            </div> -->

										</div>
									</div>
								</div>
							</div>
						</div>
                        
                    </div> <!-- container -->
                               
                </div> <!-- content -->

                <?php include_once("include/footer.php"); ?>

            </div>
            
            
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
            <!-- <div class="side-bar right-bar nicescroll">
                <h4 class="text-center">Chat</h4>
                <div class="contact-list nicescroll">
                    <ul class="list-group contacts-list">
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-1.jpg" alt="">
                                </div>
                                <span class="name">Chadengle</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-2.jpg" alt="">
                                </div>
                                <span class="name">Tomaslau</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-3.jpg" alt="">
                                </div>
                                <span class="name">Stillnotdavid</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-4.jpg" alt="">
                                </div>
                                <span class="name">Kurafire</span>
                                <i class="fa fa-circle online"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-5.jpg" alt="">
                                </div>
                                <span class="name">Shahedk</span>
                                <i class="fa fa-circle away"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-6.jpg" alt="">
                                </div>
                                <span class="name">Adhamdannaway</span>
                                <i class="fa fa-circle away"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-7.jpg" alt="">
                                </div>
                                <span class="name">Ok</span>
                                <i class="fa fa-circle away"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-8.jpg" alt="">
                                </div>
                                <span class="name">Arashasghari</span>
                                <i class="fa fa-circle offline"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-9.jpg" alt="">
                                </div>
                                <span class="name">Joshaustin</span>
                                <i class="fa fa-circle offline"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                        <li class="list-group-item">
                            <a href="#">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-10.jpg" alt="">
                                </div>
                                <span class="name">Sortino</span>
                                <i class="fa fa-circle offline"></i>
                            </a>
                            <span class="clearfix"></span>
                        </li>
                    </ul>  
                </div>
            </div> -->
            <!-- /Right-bar -->

        </div>
        <!-- END wrapper -->


    
        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <?php include_once("include/common_js.php"); ?>

        <script src="assets/plugins/peity/jquery.peity.min.js"></script>

        <script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
		
		
		    <script src="assets/pages/jquery.dashboard_3.js"></script>

        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>
		<script type="text/javascript" src="assets/plugins/parsleyjs/parsley.min.js"></script>       

    </body>
</html>