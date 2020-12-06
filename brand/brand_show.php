<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<?php include('../h.php'); ?>
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
						<form action="#" name="brand" id="brand" method="get">
							<h4>ยี่ห้อ</h4>
							<div class="form-inline">
								<!-- เพิ่มขอมูล -->
								<div class="form-group mb-2">
									<a href="brand_insert.php" class="btn-info btn-sm">เพิ่มยี่ห้อ</a>
								</div>
							</div>
							<?php
							$search = isset($_GET['search']) ? $_GET['search'] : '';
							//select id ในตาราง มาเซิส
							$sql = "SELECT brand_id,brand_name FROM brand WHERE brand_name LIKE '%$search%'";
							$result = $conn->query($sql);
							$row = mysqli_fetch_array($result);
							?>

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

							<table border="2" class="display table table-bordered" id="example" align="center">
								<thead>
									<tr bgcolor="#AED6F1" align="center" style="font-weight:bold">
										<th width="25%"> รหัสยี่ห้อ </th>
										<th> ชื่อยี่ห้อ </th>
										<td> จัดการ </td>
									</tr>
								</thead>
								<?php
								do {
									if ($row['brand_id'] != '') { ?>
										<tr>
											<td align="center"><?php if (isset($row['brand_id'])) echo $row['brand_id']; ?></td>
											<td><?php if (isset($row['brand_id'])) echo $row['brand_name']; ?></td>
											<td align="center" width="16%"><a class="btn btn-warning btn-xs" href="brand_edit.php?edit_id=<?php echo $row['brand_id']; ?>"><i class="fa fa-pencil"></i>&ensp;แก้ไข</a>
												<a class="btn btn-danger btn-xs" onclick="return confirm('Do you want to delete this record? !!!')" href="brand_delete.php?delete_id=<?php echo $row['brand_id']; ?>"><i class="fa fa-trash"></i>&ensp;ลบ</a></td>
										</tr>
								<?php
									}
								} while ($row = mysqli_fetch_array($result));
								?>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>