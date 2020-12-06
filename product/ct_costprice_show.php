<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<?php include('../h.php'); ?>
	<script>
		function confirmdelete(data) {
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
						<form action="#" id="myformshow" name="myformshow" method="get">
							<h4>ราคาทุนสินค้าและบริษัทคู่ค้า</h4>
							<div class="form-inline mb-2">
								<a href="ct_show.php" class="btn-danger btn-sm"><i class='fa fa-undo' aria-hidden='true'></i> ย้อนกลับ</a>
							</div>
							<?php
							$search = isset($_GET['search']) ? $_GET['search'] : '';
							$idproduct = isset($_GET['p_id']) ? $_GET['p_id'] : '';

							$sql = "SELECT product.*,craftmantool.*,brand.brand_name,unit.unit_name,color_name,mt_name,costprice.*,cpn_name
                    FROM product
                    INNER JOIN craftmantool ON craftmantool.product_id = product.product_id
                    INNER JOIN unit ON unit.unit_id = product.unit_id 
                    INNER JOIN brand ON brand.brand_id = product.brand_id
                    INNER JOIN color ON color.color_id = craftmantool.color_id
                    INNER JOIN material ON material.mt_id = craftmantool.mt_id
					INNER JOIN costprice ON costprice.product_id = product.product_id
					INNER JOIN company ON costprice.cpn_id = company.cpn_id
                    WHERE craftmantool.product_id = '$idproduct'
					ORDER BY costprice.product_id ASC";

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
							<th>บริษัทคู่ค้า</th>
							<th>ราคาทุน</th>
							<th>จัดการ</th>
						</tr>
					</thead>";
							do {
								if ($row['product_id'] != '') {
									echo "<tr>";
									echo "<td align='center'>" . $row["product_id"] . "</td> ";
									echo "<td>" . $row["product_name"] . " " . $row['brand_name'] . " " . "สี" . $row['color_name'] . " " . "ขนาด" . " (" . $row['ct_size'] . ")" . "</td> ";
									echo "<td>" . $row["cpn_name"] . "</td> ";
									echo "<td align='right' width='10%'>" . number_format($row["costprice"], 2) . "</td> ";
									//แก้ไข
									echo "<td align='center' width='15%'><a href='ct_costprice_edit.php?p_id=$row[product_id]&cpn_id=$row[cpn_id]' class='btn btn-warning btn-xs'><i class='fa fa-pencil'></i>&ensp;แก้ไข</a>
							<button type='button' onclick=\"JavaScript:confirmdelete('ct_costprice_delete.php?p_id=$row[product_id]&cpn_id=$row[cpn_id]')\" class='btn btn-danger btn-xs'><i class='fa fa-trash'></i>&ensp;ลบ</button></td> ";
									echo "</tr>";
								}
							} while ($row = mysqli_fetch_array($result));
							echo '</table>'
							?>
							<input type="hidden" name="p_id" id="p_id" value="<?php echo $idproduct; ?>">

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>

</html>