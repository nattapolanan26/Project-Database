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
						<form action="#" name="myformAdd" method="get">
						<h5 class="card-header"><i class="fab fa-product-hunt"></i> รายการสินค้า</h5>
						<p></p>
							<div class="form-inline">
								<div class="form-group">
									<!-- เพิ่มข้อมูล -->
									<div class="md-2 mr-sm-2">
										<a href="product.php?act=add" class="btn-primary btn-sm"><i class='fa fa-plus' aria-hidden='true'></i> เพิ่มสินค้า</a>
									</div>
									<div class="md-2 mr-sm-2">
										<a href="product_modal.php" class="btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"><i class='fa fa-gavel' aria-hidden='true'></i> เพิ่มสินค้าชำรุด</a>
									</div>
									<select onchange="location = this.value;" name="reorderpoint" id="reorderpoint" class="custom-select" style="width:300px;" required>
										<option value="#"><a>- - - - - ตรวจสอบจุดสั่งซื้อสินค้า - - - - -</option>
										<option value="product_c_hight.php"><a>- สินค้าที่สูงกว่าเกณฑ์จุดสั่งซื้อ . . .</option>
										<option value="product_c_below.php"><a>- สินค้าที่ต่ำกว่าเกณฑ์จุดสั่งซื้อ . . .</option>
									</select>
								</div>
							</div>
							<p></p>
							<?php
							$search = isset($_GET['search']) ? $_GET['search'] : '';

							$sql = "SELECT product.product_id,product.product_name,product_saleprice,product_stock,product_reorder,brand_name,color_name,class,tl_size,pb_size,ct_size,cs_volume,cc_volume,cm_volume,pb_thick
							FROM product
							LEFT JOIN cement ON cement.product_id = product.product_id
							LEFT JOIN toilet ON toilet.product_id = product.product_id
							LEFT JOIN plumbling ON plumbling.product_id = product.product_id
							LEFT JOIN categorycolor ON categorycolor.product_id = product.product_id
							LEFT JOIN craftmantool ON craftmantool.product_id = product.product_id
							LEFT JOIN chemicalsolution ON chemicalsolution.product_id = product.product_id
							INNER JOIN brand ON brand.brand_id = product.brand_id
							INNER JOIN unit ON unit.unit_id = product.unit_id
							LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = cement.color_id OR color.color_id = toilet.color_id OR plumbling.color_id = color.color_id
							ORDER BY product.product_id";

							$query = mysqli_query($conn, $sql);
							$row = mysqli_fetch_array($query);

							?>

							<script>
								$(document).ready(function() {
									$('#example').dataTable({
										"oLanguage": {
											"sLengthMenu": "แสดง _MENU_ แถว",
											"sZeroRecords": "ไม่เจอข้อมูลที่ค้นหา",
											"sInfo": "แสดง _START_ - _END_ ทั้งหมด _TOTAL_ แถว",
											"sInfoEmpty": "แสดง 0 - 0 ของ 0 แถว",
											"sInfoFiltered": "(จากแถวทั้งหมด _MAX_ แถว)",
											"sSearch": "ค้นหา :",
											"aaSorting": [
												[0, 'desc']
											],
											"oPaginate": {
												"sFirst": "หน้าแรก",
												"sPrevious": "ก่อนหน้า",
												"sNext": "ถัดไป",
												"sLast": "หน้าสุดท้าย"
											},
										}
									});
								});
							</script>

							<?php
							echo '<table id="example" class="table table-bordered" style="width:100%">';
							//หัวข้อตาราง
							echo "
					<thead>
						<tr bgcolor='#AED6F1' align='center' style='font-weight:bold'>
							<th width='15%'>รหัสสินค้า</th>
							<th>ชื่อสินค้า</th>
							<th width='10%'>จุดสั่งซื้อ</th>
							<th width='10%'>จำนวนสต๊อก</th>
							<th width='15%'>ราคาขาย</th>
						</tr>
					</thead>";
							do {
								if ($row['product_id'] != '') {
									echo "<tr>";
									echo "<td align='center'>" . $row['product_id'] . "</td> ";
									if ($row['product_name'] != '') {
										echo "<td>" . $row['product_name'] . " ";
									}
									if ($row['brand_name'] != '') {
										echo $row['brand_name'];
									}
									if ($row['color_name'] != '') {
										echo " สี" . $row['color_name'];
									}
									if ($row['class'] != '') {
										echo " ชั้น " . $row['class'];
									}
									if ($row['tl_size'] != '') {
										echo " ขนาด (" . $row['tl_size'] . ")";
									}
									if ($row['pb_size'] != '') {
										echo " ขนาด (" . $row['pb_size'] . ")";
									}
									if ($row['ct_size'] != '') {
										echo " ขนาด (" . $row['ct_size'] . ")";
									}
									if ($row['pb_thick'] != '') {
										echo " หนา " . $row['pb_thick'];
									}
									if ($row['cc_volume'] != '') {
										echo " ปริมาณ " . $row['cc_volume'];
									}
									if ($row['cs_volume'] != '') {
										echo " ปริมาณ " . $row['cs_volume'];
									}
									if ($row['cm_volume'] != '') {
										echo " ปริมาณ " . $row['cm_volume'] . "</td> ";
									}
									echo "<td align='center' style='color:red;'>" . $row['product_reorder'] . "</td> ";
									if($row['product_stock'] < $row['product_reorder']){
										echo "<td align='center' style='color:red;'>" . $row['product_stock'] . "</td> ";
									}else{
										echo "<td align='center' style='color:green;'>" . $row['product_stock'] . "</td> ";
									}
									echo "<td align='right'>" . number_format($row["product_saleprice"], 2) . "</td> ";
									echo "</tr>";
								}
							} while ($row = mysqli_fetch_array($query));
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


	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><i class='fa fa-gavel' aria-hidden='true'></i> เพิ่มสินค้าชำรุด</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label for="product_id" class="col-form-label">สินค้าชำรุด:</label>
							<input type="text" class="form-control rounded-0 border-info" id="search" name="search" placeholder="ใส่ชื่อสินค้า . . .">
						</div>
						<div class="form-group">
							<label for="def_date" class="col-form-label">วันที่ชำรุด:</label>
							<input type="date" class="form-control" id="def_date" name="def_date">
						</div>
						<div class="form-group">
							<label for="def_number" class="col-form-label">จำนวน:</label>
							<input type="text" class="form-control" id="def_number" name="def_number"></input>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
					<button type="button" class="btn btn-primary"><i class='fa fa-save' aria-hidden='true'></i> บันทึกข้อมูล</button>
				</div>
			</div>
		</div>
	</div>
</body>

</html>