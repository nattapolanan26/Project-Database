<?php session_start(); ?>
<html>
<!DOCTYPE html>

<head>
	<?php include('../h.php');
	error_reporting(error_reporting() & ~E_NOTICE); ?>
	<script type="text/javascript">
		function Asubmit() {
			document.cpnshow.action = "cpn_form_insert.php";
		}

		function Bsubmit() {
			document.cpnshow.action = "show.php";
		}
	</script>

</head>
<body>
	<?php include('../connectdb.php'); ?>
	<?php include('../navbar.php'); ?>
	<div class="container-fluid">
		<p></p>
		<div class="row">
			<div class="col-md-2">
				<!-- Left side column. contains the logo and sidebar -->
				<div class="color-login">
					<h6><i class="fas fa-user-circle"></i>&ensp;<a style="font-weight:bold;"><?php echo "ผู้ใช้"; ?></a><a style="color:#c92828;font-weight:bold;"><?php echo " : " . $_SESSION['user']; ?></a></h6>
					<h6><i class="fas fa-check-square"></i></i></i>&ensp;<a style="font-weight:bold;"><?php echo "ตำแหน่ง"; ?></a><a style="color:#1d4891;font-weight:bold;"><?php echo " : " . $_SESSION['posname']; ?></a></h6>
				</div>
				<?php include('../menu_left.php'); ?>
				<!-- Content Wrapper. Contains page content -->
			</div>
			<div class="col-md-10">
				<div class="card">
					<div class="card-body">
						<h5 class="card-header"><i class="fas fa-user-circle"></i> บริษัทคู่ค้า</h5>
						<p></p>
						<form action="#" name="cpnshow" id="cpnshow" method="get" enctype="multipart/form-data">
							<?php $idproduct = isset($_GET['p_id']) ? $_GET['p_id'] : ''; ?>
							<input type="hidden" name="idproduct" value="<?php echo $idproduct ?>">
							<div class="form-group">
								<a href="cpn_form_insert.php" class="btn-info btn-sm"><i class='fa fa-plus' aria-hidden='true'></i> เพิ่มบริษัทคู่ค้า</a>
							</div>

							<?php
							//select id ในตาราง มาเซิส
							$sql = "SELECT * FROM company ORDER BY cpn_id ASC";
							$result = $conn->query($sql);
							$row = mysqli_fetch_assoc($result); ?>
							<script>
								$(document).ready(function() {
									$('#example').DataTable({
										"pageLength": 10,
										"order": [
											[0, 'asc']
										]
									});
								});
							</script>

							<?php
							echo ' <table border="2" class="display table table-bordered" id="example"> ';
							//หัวข้อตาราง
							echo " <thead>
						<tr bgcolor='#AED6F1' align='center' class='info'>
						<th width='15%'>รหัสบริษัทคู่ค้า</th>
						<th width='25%'>ชื่อบริษัทคู่ค้า</th>
						<th width='12%'>จัดการ</th>
					
						</tr>
						</thead>";
							do {
								echo "<tr>";
								echo "<td align='center'>" . $row['cpn_id'] . "</td> ";
								echo "<td>" . $row['cpn_name'] . "</td> ";
								echo "<td><center><a href='cpn_data.php?data_id=$row[cpn_id]'  class='btn btn-info btn-xs'><i class='fa fa-folder'></i>&ensp;ข้อมูล</a>
						<a href='cpn_edit.php?submit=Edit&edit_id=$row[cpn_id]' class='btn btn-warning btn-xs'><i class='fa fa-pencil'></i>&ensp;แก้ไข</a>
						<a href='cpn_delete.php?delete_id=$row[cpn_id]' class='btn btn-danger btn-xs' onclick=\"return confirm('Do you want to delete this record? !!!')\"><i class='fa fa-trash'></i>&ensp;ลบ</a></center></td>";
								echo "</tr>";
							} while ($row =  mysqli_fetch_assoc($result));
							echo "</table>"; ?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	</body>

</html>