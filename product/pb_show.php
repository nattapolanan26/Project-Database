<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>

<body>
	<?php include('../h.php'); ?>
	</style>
	<script type="text/javascript">
		function backform() {
			document.myformshow.action = "product_show.php";
		}

		function addform() {
			document.myformshow.action = "pvc_insert.php";
		}

		function confirmalert(data) {
			Swal.fire({
				title: 'ต้องการลบข้อมูลหรือไม่ ?',
				text: "ตรวจสอบข้อมูลให้ถูกต้อง!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete!'
			}).then((result) => {
				if (result.value) {
					console.log(data);
					window.location = data;
					// Swal.fire('Delete Success','','success')
				} else {
					Swal.fire('ยกเลิกการทำรายการ', '', 'error')
				}
			})
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
							<h4>งานประปา</h4>
							<form action="#" name="myformshow" method="get">
								<div class="form-inline mb-2">
									<!-- เพิ่มขอมูล -->
									<div class="form-group ">
										<a href="product.php?act=add" class="btn-danger btn-sm"><i class='fa fa-undo' aria-hidden='true'></i> ย้อนกลับ</a>&ensp;
										<a href="pb_insert.php" class="btn-info btn-sm"><i class='fa fa-plus' aria-hidden='true'></i> เพิ่มงานประปา</a>
									</div>
								</div>
								<?php
								$search = isset($_GET['search']) ? $_GET['search'] : '';
								$sql = "SELECT plumbling.product_id,product.product_name,brand_name,mt_name,class,pb_size,pb_thick,unit_name,product_saleprice FROM product
								INNER JOIN plumbling ON plumbling.product_id = product.product_id
								INNER JOIN brand ON brand.brand_id = product.brand_id
								INNER JOIN unit ON unit.unit_id = product.unit_id
								INNER JOIN material ON material.mt_id = plumbling.mt_id
								INNER JOIN color ON color.color_id = plumbling.color_id
								ORDER BY plumbling.product_id ASC";
								$result = $conn->query($sql) or die("Error description: " . mysqli_error($conn));
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
								<?php
								echo ' <table border="2" class="display table table-bordered" id="example" align="center">';
								//หัวข้อตาราง
								echo "
						<thead>
							<tr bgcolor='#AED6F1' align='center' style='font-weight:bold'>
								<th>รหัสสินค้า</th>
								<th>ชื่อสินค้า</th>
								<th>ยี่ห้อ</th>
								<th>วัสดุ</th>
								<th>ชั้น</th>
								<th>ขนาด</th>
								<th>หนา</th>
								<th>ราคาขาย</th>
								<th>หน่วย</th>
								<th>บริษัทคู่ค้า</th>
								<th>จัดการ</th>
							</tr>
						</thead>";

								do {
									if ($row['product_id'] != '') {
										echo "<tr>";
										echo "<td align='center'>" . $row["product_id"] . "</td> ";
										echo "<td>" . $row["product_name"] . "</td> ";
										echo "<td align='center'>" . $row["brand_name"] . "</td> ";
										echo "<td align='center'>" . $row["mt_name"] . "</td> ";
										if ($row['class'] != '') {
											echo "<td align='center'>" . $row["class"] . "</td> ";
										} elseif ($row['class'] == '') {
											echo "<td align='center'>" . "-" . "</td> ";
										}
										echo "<td align='center'>" . $row["pb_size"] . "</td> ";
										echo "<td align='center'>" . $row["pb_thick"] . "</td> ";
										echo "<td align='right' width='10%'>" . number_format($row["product_saleprice"], 2) . "</td> ";
										echo "<td align='center'>" . $row["unit_name"] . "</td> ";
										//เพิ่มราคาทุนสินค้า
										echo "<td align='center' width='10%'><a href='pb_costprice.php?&p_id=$row[product_id]'><img src='/picture/add.png' width='30' height='30' alt='เพิ่มราคาสินค้า'></a>&emsp;<a href='pb_costprice_show.php?p_id=$row[product_id]'><img src='/picture/product.png' width='30' height='30' alt='ดูสินค้า'></a></td> ";
										//แก้ไข
										echo "<td align='center' width='15%'><a href='pb_edit.php?submit=Edit&p_id=$row[product_id]' class='btn btn-warning btn-xs'><i class='fa fa-pencil'></i>&ensp;แก้ไข</a>
								<button type='button' onclick=\"JavaScript:confirmalert('pb_delete.php?p_id=$row[product_id]')\" class='btn btn-danger btn-xs'><i class='fa fa-trash'></i>&ensp;ลบ</button></td> ";
										echo "</tr>";
									}
								} while ($row = mysqli_fetch_array($result));
								echo "</table>";
								?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>

</html>