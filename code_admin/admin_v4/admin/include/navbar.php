<?php

$selquery4Nav = "select * from tbl_app_details where id=1";
$selresult4Nav = mysqli_query($conn,$selquery4Nav);
$selres4Nav = mysqli_fetch_array($selresult4Nav);

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
  $pass = "select password from tbl_user_master where email='{$user}'";
  $passresult = mysqli_query($conn,$pass);
  $passres = mysqli_fetch_array($passresult);
  $password = $passres['0'];
  
  if($oldpass==$password)
  {
      $chngquery = "update tbl_user_master set password='{$enpassnew}' where email='{$user}' and password='{$oldpass}'";

      if(mysqli_query($conn,$chngquery))
      {
        header("Location:logout.php");
      }
      else
      {
        echo $chngquery;
      }
  }
  else
  {
      echo "Password is Incorrect";
  }
  

}

?>
<!-- Top Bar Start -->
<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center">
            <!-- <a href="index" class="logo"><i class="icon-magnet icon-c-logo"></i><span>Skyc<i class="md md-album"></i>der</span></a> -->
            <!-- Image Logo here -->
            <a href="index" class="logo">
                <i class="icon-c-logo"> <img src="<?php echo $selres4Nav['favicon']; ?>" height="80"/> </i>
                <span><img src="<?php echo $selres4Nav['logo']; ?>" height="45"/></span>
            </a>
        </div>
    </div>

    <!-- Button mobile view to collapse sidebar menu -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="">
                <div class="pull-left">
                    <button class="button-menu-mobile open-left waves-effect waves-light">
                        <i class="md md-menu"></i>
                    </button>
                    <span class="clearfix"></span>
                </div>

                <!-- <ul class="nav navbar-nav hidden-xs">
                    <li><a href="#" class="waves-effect waves-light">Files</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </li>
                </ul> -->

                <!-- <form role="search" class="navbar-left app-search pull-left hidden-xs">
                     <input type="text" placeholder="Search..." class="form-control">
                     <a href=""><i class="fa fa-search"></i></a>
                </form> -->


                <ul class="nav navbar-nav navbar-right pull-right">
                    <!-- <li class="dropdown top-menu-item-xs">
                        <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                            <i class="icon-bell"></i> <span class="badge badge-xs badge-danger">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg">
                            <li class="notifi-title"><span class="label label-default pull-right">New 3</span>Notification</li>
                            <li class="list-group slimscroll-noti notification-list">
                                list item
                               <a href="javascript:void(0);" class="list-group-item">
                                  <div class="media">
                                     <div class="pull-left p-r-10">
                                        <em class="fa fa-diamond noti-primary"></em>
                                     </div>
                                     <div class="media-body">
                                        <h5 class="media-heading">A new order has been placed A new order has been placed</h5>
                                        <p class="m-0">
                                            <small>There are new settings available</small>
                                        </p>
                                     </div>
                                  </div>
                               </a>

                                list item
                               <a href="javascript:void(0);" class="list-group-item">
                                  <div class="media">
                                     <div class="pull-left p-r-10">
                                        <em class="fa fa-cog noti-warning"></em>
                                     </div>
                                     <div class="media-body">
                                        <h5 class="media-heading">New settings</h5>
                                        <p class="m-0">
                                            <small>There are new settings available</small>
                                        </p>
                                     </div>
                                  </div>
                               </a>

                                list item
                               <a href="javascript:void(0);" class="list-group-item">
                                  <div class="media">
                                     <div class="pull-left p-r-10">
                                        <em class="fa fa-bell-o noti-custom"></em>
                                     </div>
                                     <div class="media-body">
                                        <h5 class="media-heading">Updates</h5>
                                        <p class="m-0">
                                            <small>There are <span class="text-primary font-600">2</span> new updates available</small>
                                        </p>
                                     </div>
                                  </div>
                               </a>

                                list item
                               <a href="javascript:void(0);" class="list-group-item">
                                  <div class="media">
                                     <div class="pull-left p-r-10">
                                        <em class="fa fa-user-plus noti-pink"></em>
                                     </div>
                                     <div class="media-body">
                                        <h5 class="media-heading">New user registered</h5>
                                        <p class="m-0">
                                            <small>You have 10 unread messages</small>
                                        </p>
                                     </div>
                                  </div>
                               </a>

                                 list item
                               <a href="javascript:void(0);" class="list-group-item">
                                  <div class="media">
                                     <div class="pull-left p-r-10">
                                        <em class="fa fa-diamond noti-primary"></em>
                                     </div>
                                     <div class="media-body">
                                        <h5 class="media-heading">A new order has been placed A new order has been placed</h5>
                                        <p class="m-0">
                                            <small>There are new settings available</small>
                                        </p>
                                     </div>
                                  </div>
                               </a>

                                list item
                               <a href="javascript:void(0);" class="list-group-item">
                                  <div class="media">
                                     <div class="pull-left p-r-10">
                                        <em class="fa fa-cog noti-warning"></em>
                                     </div>
                                     <div class="media-body">
                                        <h5 class="media-heading">New settings</h5>
                                        <p class="m-0">
                                            <small>There are new settings available</small>
                                        </p>
                                     </div>
                                  </div>
                               </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="list-group-item text-right">
                                    <small class="font-600">See all notifications</small>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                    <li class="hidden-xs">
                        <a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i class="icon-size-fullscreen"></i></a>
                    </li>
                    <li class="hidden-xs">
                        <a href="new-update" class="right-bar-toggle waves-effect waves-light" data-toggle="tooltip" data-placement="bottom" title="New Update" data-original-title="New Update"><i class="icon-settings"></i></a>
                    </li>
                    <li class="dropdown top-menu-item-xs">
                        <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo $selres4Nav['favicon']; ?>" alt="user-img" class="img-circle"> </a>
                        <ul class="dropdown-menu">
                            <!-- <li><a href="javascript:void(0)"><i class="ti-user m-r-10 text-custom"></i> Profile</a></li>
                            <li><a href="javascript:void(0)"><i class="ti-settings m-r-10 text-custom"></i> Settings</a></li> -->
                            <li><a href="#change_pass" data-toggle="modal" data-target="#change_pass"><i class="fa fa-key m-r-10 text-custom"></i> Change Password</a></li>
                            <li class="divider"></li>
                            <li><a href="logout.php"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>
<!-- Top Bar End -->


<!-- ========== Left Sidebar Start ========== -->

<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

            	<li class="text-muted menu-title">Navigation</li>

                <li class="has_sub">
                    <a href="index" class="waves-effect"><i class="fa fa-dashboard"></i> <span> Dashboard </span> </a>
                </li>
                <!-- <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-gamepad"></i> <span> Match </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="match-detail">Create New</a></li>
                        <li><a href="match-list">Match List</a></li>
                        <li><a href="private-match-list">Private Match List</a></li>
                    </ul>
                </li> -->
                <li class="has_sub">
                    <a href="match-list" class="waves-effect"><i class="fa fa-gamepad"></i> <span> Match</span> </a>
                </li>
                <li class="has_sub">
                    <a href="completed-match" class="waves-effect"><i class="fa fa-check-square-o"></i> <span> Completed Match</span> </a>
                </li>
                <li class="has_sub">
                    <a href="withdrawal-list" class="waves-effect"><i class="fa fa-money"></i> <span> Withdraw List</span> </a>
                </li>
                <li class="has_sub">
                    <a href="transaction-list" class="waves-effect"><i class="fa fa-inr"></i> <span> Transaction Details</span> </a>
                </li>
                <li class="has_sub">
                    <a href="reg-user-list" class="waves-effect"><i class="fa fa-user"></i> <span> User List</span> </a>
                </li>
                <li class="has_sub">
                    <a href="send-notification" class="waves-effect"><i class="fa fa-bell-o"></i> <span> Send Notification</span> </a>
                </li>
                <li class="has_sub">
                    <a href="announcement-history" class="waves-effect"><i class="fa fa-bullhorn"></i> <span> Announcement</span> </a>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-bars"></i> <span> Master </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="image-master">Images</a></li>
                        <li><a href="rules-master">Rules</a></li>
                        <li><a href="payout-master">Payout</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-gear"></i> <span> Configuration </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="about-us">About Us</a></li>
                        <li><a href="contact-us">Contact Us</a></li>
                        <li><a href="privacy-policy">Privacy Policy</a></li>
                        <li><a href="terms-and-condition">Terms and Condition</a></li>
                        <li><a href="push-notification-setting.php">Push Notification Key</a></li>
                        <li><a href="new-update">New Update</a></li>
                        <li><a href="app-details">App Details</a></li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End -->

<!-- modal for change password -->
<div class="modal wow flipInX" id="change_pass">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        Change Password
        <button class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form method="post" action="index.php" id="passwordForm" data-parsley-validate novalidate>
          <div class="container">
            <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <input class="form-control" type="password" name="oldpass" parsley-trigger="change" id="oldpass" placeholder="Old Password" required >
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" name="password1" id="password1" placeholder="New Password" required >
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" data-parsley-equalto="#password1" required name="password2" id="password2" placeholder="Repeat Password">
                  </div>
                </div>
            </div>
          </div>
        
      </div>      
      <div class="modal-footer">
        <button type="submit" class="col-xs-12 btn btn-primary btn-load" name="btnChngPass">Change Password</button>
        </form>
      </div>
    </div>
  </div>
</div>