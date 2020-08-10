<?php 
include("include/conn.php");
include("include/security.php");

if (isset($_POST) & !empty($_POST)) 
{

	$txtUname = mysqli_real_escape_string($conn,$_POST['txtUname']);
	if ($txtUname!='') {
		
		$sql="select uname from tbl_user_master where uname='$txtUname'";
		$result=mysqli_query($conn,$sql);
		$count=mysqli_num_rows($result);

		if ($count>0) {
			echo "<small style=\"color:red;\"><i class=\"fa fa-times\"></i> $txtUname is not available</small>";
		}
		else
		{
			echo "<small style=\"color:green;\"><i class=\"fa fa-check\"></i> $txtUname is available</small>";
		}
	}
}

?>