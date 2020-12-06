<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<?php
	include('../h.php');
	?>
	<script type="text/javascript">
		function addform() {
			document.myformshow.action = "sbs_insert.php";
		}

		function backform() {
			document.myformshow.action = "product_show.php";
		}

		function del(data) {
			Swal.fire({
				title: 'ต้องการลบข้อมูลหรือไม่ ?',
				text: "ตรวจสอบข้อมูลให้ถูกต้อง!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete!'
			}).then((result) => {
				if (result.isConfirmed) {
					window.location = data;
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

						<form action="#" name="myformshow" method="get">
							<h4>สินค้าเบ็ดเตล็ด</h4>
							<div class="form-inline">
								<!-- เพิ่มขอมูล -->
								<div class="form-group mb-2">
									<a href="product.php?act=add" class="btn-danger btn-sm"><i class='fa fa-undo' aria-hidden='true'></i> ย้อนกลับ</a>&ensp;
									<a href="sbs_insert.php" class="btn-info btn-sm"><i class='fa fa-plus' aria-hidden='true'></i> เพิ่มสินค้าเบ็ดเตล็ด</a>
								</div>
							</div>

							<?php
							$search = isset($_POST['search']) ? $_POST['search'] : '';
							$sql = "SELECT * FROM product
					INNER JOIN brand ON product.brand_id = brand.brand_id 
					INNER JOIN unit ON product.unit_id = unit.unit_id
					WHERE product_status = '1'
					ORDER BY product_id ASC";
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

							<?php
							echo ' <table border="2" class="display table table-bordered" id="example" align="center">';
							//หัวข้อตาราง
							echo "
					<thead>
						<tr bgcolor='#AED6F1' align='center' style='font-weight:bold'>
						<th>รหัสสินค้า</th>
						<th>ชื่อสินค้า</th>
						<th>ยี่ห้อ</th>
						<th>หน่วย</th>
						<th>ราคาขาย</th>
						<th>บริษัทคู่ค้า</th>
						<th>จัดการ</th>
						</tr>
					</thead>";
							do {
								if ($row['product_id'] != '') {
									echo "<tr>";
									echo "<td>" . $row["product_id"] . "</td> ";
									echo "<td>" . $row["product_name"] . "</td> ";
									echo "<td>" . $row["brand_name"] . "</td> ";
									echo "<td align='center'>" . $row["unit_name"] . "</td> ";
									echo "<td align='center'>" . number_format($row["product_saleprice"], 2) . "</td> ";
									//เพิ่มราคาทุนสินค้า
									echo "<td align='center' width='10%'><a href='sbs_costprice.php?p_id=$row[product_id]'><img src='/picture/add.png' width='30' height='30' alt='เพิ่มราคาสินค้า'></a>&emsp;<a href='sbs_costprice_show.php?p_id=$row[product_id]'><img src='/picture/product.png' width='30' height='30' alt='ดูสินค้า'></a></td> ";
									//จัดการ
									echo "<td align='center' width='15%'><a class='btn btn-warning btn-xs' href='sbs_edit.php?p_id=$row[product_id]'><i class='fa fa-pencil'></i>&ensp;แก้ไข</a>
							<button type='button' onclick=\"JavaScript:del('sbs_delete.php?p_id=$row[product_id]')\" class='btn btn-danger btn-xs'><i class='fa fa-trash'></i>&ensp;ลบ</button></td> ";
									echo "</tr>";
								}
							} while ($row = mysqli_fetch_array($result));
							echo "</table>";
							//5. close connection
							mysqli_close($conn);
							?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>

</html>