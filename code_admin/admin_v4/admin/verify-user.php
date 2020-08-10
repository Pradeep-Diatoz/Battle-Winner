<!DOCTYPE html>
<html>
<head>
	<title>Verify your account</title>
	<link href="assets/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet" type="text/css">
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
      	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="assets/js/modernizr.min.js"></script>
</head>
<body>

<?php
//echo "hi";
	include("include/conn.php");
	session_start();
	if(isset($_GET['verification_code']) && isset($_GET['uid'])){
	$user=$_GET['uid'];
	$code=$_GET['verification_code'];
 
	$query=mysqli_query($conn,"select * from tbl_user_master where user_id=$user");
	$row=mysqli_fetch_array($query);
 	

	if($row['verification_code']==$code){
		//activate account
		mysqli_query($conn,"update tbl_user_master set is_verify='1' where user_id='$user'");
		?>
		


		<div style="color:#000 !important; font-family: Arial, " helvetica="" neue',="" helvetica,="" sans-serif;="" width:100%;="" background:="" #eee;'="">
			<div style="width:600px; margin: 0 auto; ">
				<center>
					<div style="padding: 0.1em; background: #04183b;">
						<!--<img alt="GigSplash" src="http://www.gigsplash.com/images/logo/gigsplash.png" style="margin-left: 1em;">--><h4 style="color: #fff; font-size: 35px; ">SkyCoder</h4>
					</div>
					<h2 style="letter-spacing: 3px;">Hello, Welcome to SkyCoder</h2>
					
					<div style="margin: 1em !important; line-height: 150%;">
						<h4 style="letter-spacing: 1px; color: #000;">Thank you for being part of our team.</h4>
						<p style="color: #999;">NOTE: Please disregard this email if account is already activated.</p>
						<div style="margin: 2em; color: #000;">
							<span style="-webkit-border-radius: 28;-moz-border-radius: 28;border-radius: 28px;font-family: Arial;color: #ffffff;font-size: 25px;background: #04183b;padding: 10px 20px 10px 20px;text-decoration: none;" href="" target="_blank">Your Account is Activated, Login Now</span>
						</div>
		              	
		              
						<!-- <p><a href="http://abroadvisaexperts.com.au">abroadvisaexperts.com.au</a></p> -->
						
					</div>
				<center>
			</center></center></div>
		</div>




		<?php
	}
	else{
		/*$_SESSION['sign_msg'] = "Something went wrong. Please sign up again.";
  		header('location:register.php');*/
  		echo '<script type="text/javascript">';
	    echo 'setTimeout(function () { swal(
	                                          "Oops...",
	                                          "Something went wrong !! \n Contact Administrator.",
	                                          "error"
	                                        );';
	    echo '}, 1000);</script>';
	}
	}
	else{
		header('location:login.php');
	}

?>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<!-- Sweet-Alert  -->
<script src="../assets/plugins/bootstrap-sweetalert/sweet-alert.min.js"></script>
<script src="../assets/pages/jquery.sweet-alert.init.js"></script>

</body>
</html>
