<script type="text/javascript">
function confirmlogout(logout){
	Swal.fire({
	title: 'ตรวจสอบการยืนยันออกจากระบบ !',
	text: "กรุณายืนยืนเพื่อออกจากระบบ",
	icon: 'warning',
	showCancelButton: true,
	confirmButtonColor: '#3085d6',
	cancelButtonColor: '#d33',
	confirmButtonText: 'ออกจากระบบ'
	}).then((result) => {
	if (result.value) {
		console.log(logout);
		window.location = logout;
		// Swal.fire('Delete Success','','success')
	}
	})
}
</script>

<?php 
include('connectdb.php');

$sql="SELECT * FROM employee INNER JOIN position ON employee.pos_id = position.pos_id WHERE position.pos_id = '".$_SESSION['posid']."'";
$result=mysqli_query($conn, $sql);
$rowpos = mysqli_fetch_array($result);  
?>

<!DOCTYPE html>
<html>

<head>
<? include('h.php'); ?>
<div class="list-group">
	<!-- <a href="../login_form.php" class="list-group-item list-group-item-action"><img src='/icons/lock.svg' alt='login' width='24' height='24' title='Login'> Login</a> -->
	<?php if($substr = substr($rowpos['pos_status'], 9, 1)) { ?>
	<a href="../employee/show_position.php" class="list-group-item list-group-item-action"><i class="fas fa-check-square"></i></i></i>
	&ensp;ตำแหน่ง / สิทธิ์เข้าใช้งาน</a>
	<?php } ?>
	<?php if($substr = substr($rowpos['pos_status'], 12, 1)) { ?>
	<a href="../promotion/promotion_show.php" class="list-group-item list-group-item-action"><i class="fas fa-tags"></i>
	&ensp;โปรโมชั่น</a>
	<?php } ?>
	<?php if($substr = substr($rowpos['pos_status'], 14, 1)) { ?>
	<a href="../claim/claim_show.php" class="list-group-item list-group-item-action"><i class="fas fa-wrench"></i>
	&ensp;เคลมสินค้า</a>
	<?php } ?>
	<?php if($substr = substr($rowpos['pos_status'], 15, 1)) { ?>
	<a href="../report/select_report.php" class="list-group-item list-group-item-action"><i class="far fa-file-alt"></i>
	&ensp;รายงานรีพอร์ต</a>
	<?php } ?>
	<a style="font-weight:bold;background-color:#fff6e6;color:red;" href="#" class="list-group-item list-group-item-action" onclick="JavaScript:confirmlogout('../logout_form.php')"><i class="fa fa-sign-out" aria-hidden="true"></i>
	&ensp;ออกจากระบบ</a>
</div>
</head>
<body>
</body>
</html>