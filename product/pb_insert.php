<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<?php

	use Mpdf\Tag\Select;

	include('../h.php'); ?>
	<script type="text/javascript">
		function addSubmit() {
			document.getElementById("myformadd").action = "pvc_insert.php";
		}

		function backSubmit() {
			document.getElementById("myformadd").action = "idprocheck.php";
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
						<h4>เพิ่มงานประปา</h4>
						<form action="#" id="myformadd" name="myformadd" method="post">
							<p></p>
							<div class="form-row">
								<div class="col-sm-2">
									<label for="validationDefault01">รหัสสินค้า</label>
									<input type="hidden" name="idproduct" value="<?php $idsql = "SELECT concat('PRO',LPAD(ifnull(SUBSTR(max(product_id),4,10),'0')+1,7,'0')) as PRO_ID FROM product";
																					$resultid = mysqli_query($conn, $idsql);
																					$row = mysqli_fetch_array($resultid);
																					$idproduct = $row['PRO_ID']; ?><?php echo $idproduct; ?>">
									<input type="text" class="form-control" value="<?php echo $idproduct; ?>" disabled>
								</div>
								<div class="col-sm-4 mb-3">
									<label for="validationDefault02">ชื่อสินค้า</label>
									<input class="form-control" type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-4 mb-3">
									<label for="validationDefault03">ยี่ห้อ</label>
									<select name="brand" id="brand" onchange="document.myformadd.submit();" class="form-control" required>
										<option value=''>Please select a brand . . .</option>
										<?php
										$strSQL = "SELECT * FROM brand";
										$result = mysqli_query($conn, $strSQL);
										while ($row = mysqli_fetch_array($result)) {
										?>
											<option value="<?php echo $row["brand_id"]; ?>" <?php if (isset($_POST["brand"])) {
																								if ($_POST["brand"] == $row["brand_id"]) {
																									echo 'selected';
																								}
																							} ?>><?php echo $row["brand_name"]; ?>
											</option>
										<?php
										} ?>
									</select>
								</div>
							</div>

							<div class="form-row">
								<div class="col-sm-4 mb-3">
									<label for="validationDefault04">วัสดุ</label>
									<select name="material" id="material" class="form-control" required>
										<option value=''>Please select a material . . .</option>
										<?php
										$strSQL = "SELECT * FROM material";
										$result = mysqli_query($conn, $strSQL);
										while ($row = mysqli_fetch_array($result)) {
										?>
											<option value="<?php echo $row["mt_id"]; ?>" <?php if (isset($_POST["material"])) {
																								if ($_POST["material"] == $row["mt_id"]) {
																									echo 'selected';
																								}
																							} ?>><?php echo $row["mt_name"]; ?>
											</option>
										<?php
										} ?>
									</select>
								</div>
							</div>

							<div class="form-row">
								<div class="col-md-4 mb-3">
									<label for="validationDefault05">ชั้นพีวีซี</label>
									<select name="class" id="class" class="form-control" onchange="document.myformadd.submit();" required>
										<option value=''>Please select a class . . .</option>
										<option value='5' <?php if (isset($_POST["class"])) if ($_POST["class"] == '5') echo 'selected'; ?>>5</option>
										<option value='8.5' <?php if (isset($_POST["class"])) if ($_POST["class"] == '8.5') echo 'selected'; ?>>8.5</option>
										<option value='13.5' <?php if (isset($_POST["class"])) if ($_POST["class"] == '13.5') echo 'selected'; ?>>13.5</option>
									</select>
								</div>
							</div>


							<div class="form-row">
								<div class="col-sm-4 mb-3">
									<label for="validationDefault06">สี</label>
									<select name="color" id="color" onchange="document.myformadd.submit();" class="form-control" required>
										<option value=''>Please select a color . . .</option>
										<?php
										$strSQL = "SELECT * FROM color";
										$result = mysqli_query($conn, $strSQL);
										while ($row = mysqli_fetch_array($result)) {
										?>
											<option value="<?php echo $row["color_id"]; ?>" <?php if (isset($_POST["color"])) {
																								if ($_POST["color"] == $row["color_id"]) {
																									echo 'selected';
																								}
																							} ?>><?php echo $row["color_name"]; ?>
											</option>
										<?php
										} ?>
									</select>
								</div>
							</div>

							<div class="form-row">
								<div class="col-sm-2 mb-3 control-label">
									<label for="validationDefault07">กว้าง</label>
									<input class="form-control" type="text" name="wide" value="<?php if (isset($_POST['wide'])) echo $_POST['wide']; ?>">
								</div>
								<div class="col-sm-2 mb-3 control-label">
									<label for="validationDefault08">ลึก</label>
									<input class="form-control" type="text" name="deep" value="<?php if (isset($_POST['deep'])) echo $_POST['deep']; ?>">
								</div>
								<div class="col-sm-2 mb-3 control-label">
									<label for="validationDefault09">สูง</label>
									<input class="form-control" type="text" name="high" value="<?php if (isset($_POST['high'])) echo $_POST['high']; ?>">
								</div>
								<div class="col-sm-2 mb-3 control-label">
									<label for="validationDefault10">ความหนา</label>
									<input class="form-control" type="text" name="thick" value="<?php if (isset($_POST['thick'])) echo $_POST['thick']; ?>">
								</div>
							</div>

							<div class="form-row">
								<div class="col-md-4 mb-3">
									<label for="validationDefault11">หน่วย</label>
									<select name="unit" id="unit" onchange="document.myformadd.submit();" class="form-control" required>
										<option value=''>Please select a unit . . .</option>
										<?php
										$strSQL = "SELECT * FROM unit";
										$result = mysqli_query($conn, $strSQL);
										while ($row = mysqli_fetch_array($result)) {
										?>
											<option value="<?php echo $row["unit_id"]; ?>" <?php if (isset($_POST["unit"])) {
																								if ($_POST['unit'] == $row["unit_id"]) {
																									echo 'selected';
																								}
																							} ?>><?php echo $row["unit_name"]; ?>
											</option>
										<?php
										} ?>
									</select>
								</div>
							</div>

							<div class="form-row">
								<div class="col-sm-6 mb-3 control-label">
									<label for="validationDefault12">รายละเอียด</label><br>
									<textarea name="detail" rows="4" cols="51"><?php if (isset($_POST['detail'])) echo $_POST['detail']; ?></textarea>
								</div>
							</div>

							<div class="form-row">
								<div class="col-sm-2 mb-3 control-label">
									<label for="validationDefault13">ราคาขาย</label>
									<input class="form-control" type="text" name="saleprice" value="<?php if (isset($_POST['saleprice'])) echo $_POST['saleprice']; ?>">
									<small id="salepriceHelp" class="form-text text-muted"><a style="color:red;">*กรุณาใส่ราคาขายสินค้า*</a></small>
								</div>
								<div class="col-sm-2 mb-3 control-label">
									<label for="validationDefault14">จุดสั่งซื้อ</label>
									<input class="form-control" type="text" name="reorderpoint" value="<?php if (isset($_POST['reorderpoint'])) echo $_POST['reorderpoint']; ?>">
									<small id="salepriceHelp" class="form-text text-muted"><a style="color:red;">*กรุณาใส่จุดสั่งซื้อสินค้า*</a></small>
								</div>
							</div>

							<div>
								<input class="w3-button w3-black w3-round-xlarge" type="submit" name="pvcback" value="ย้อนกลับ" style="width: 100px" onclick="document.myformadd.action='pb_show.php'">
								<input class="w3-button w3-red w3-round-xlarge" type="submit" name="submitpvc" value="บันทึก" style="width: 100px">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>


	<?php
	if (isset($_POST['submitpvc'])) {
		$name = $_POST['name'];
		$idproduct = $_POST['idproduct'];
		$saleprice = $_POST['saleprice'];
		$unit = $_POST['unit'];
		$brand = $_POST['brand'];
		$class = isset($_POST['class']) ? $_POST['class'] : '';
		$color = $_POST['color'];
		$repoint = $_POST['reorderpoint'];
		$material = $_POST['material'];
		$detail = $_POST['detail'];
		$size = $_POST['wide'] . "*" . $_POST['deep'] . "*" . $_POST['high'];
		$thick = $_POST['thick'];

		$sql = "SELECT plumbling.product_id,count(product_name) AS p_name,count(brand_id) AS b_brand,count(class) AS c_class,count(pb_size) AS s_size,count(color_id) AS c_color ,count(pb_thick) AS pb_thick
		FROM product 
		INNER JOIN plumbling ON product.product_id = plumbling.product_id 
		
		WHERE product_name = '$name' AND brand_id = '$brand' AND pb_size = '$size' AND pb_thick = '$thick' AND class = '$class' AND color_id = '$color' AND plumbling.product_id != '$idproduct'";
		$query = mysqli_query($conn, $sql) or die("Error description: " . mysqli_error($conn));
		$row  = mysqli_fetch_array($query);
		$p_name = $row['p_name'];
		$b_brand = $row['b_brand'];
		$c_class = $row['c_class'];
		$s_size = $row['s_size'];
		$c_color = $row['c_color'];
		$pb_thick = $row['pb_thick'];

		//เช็คซ้ำระบบ เมื่อเพิ่ม PVC
		if ($p_name == '0' && $b_brand == '0' && $c_class == '0' && $s_size == '0' && $c_color == '0' && $pb_thick == '0' && $color != '' && $name != '' && $saleprice != '' && $unit != '' && $brand != '' && $class != '' && $size != '' && $repoint != '' && $material != '') {
			$sql = "INSERT INTO product (product_id,product_name,brand_id,unit_id,product_saleprice,product_reorder,product_stock,product_detail,product_status) VALUES ('$idproduct','$name','$brand','$unit','$saleprice','$repoint','0','$detail','3')";
			// echo $sql;
			if (mysqli_query($conn, $sql) == true) {
				$sql1 = "INSERT INTO plumbling (product_id,mt_id,color_id,pb_size,pb_thick,class) VALUES ('$idproduct','$material','$color','$size','$thick','$class')";
				// echo $sql1;
				if (mysqli_query($conn, $sql1) == true) {
					echo "<script type='text/javascript'>";
					echo "window.location.href='pb_show.php';";
					echo "</script>";
				}
			} else {
				echo "<script>"; //คำสั่งสคิป
				echo "alert('ผิดพลาด กรุณากรอกข้อมูลใหม่!');"; //แสดงหน้าต่างเตือน
				echo "window.location.href='pb_insert.php';"; //แสดงหน้าก่อนนี้
				echo "</script>";
			}
		} //เช็คซ้ำระบบ เมื่อเพิ่ม PP-R
		elseif ($p_name == '0' && $b_brand == '0' && $s_size == '0' && $c_color == '0' && $pb_thick == '0' && $color != '' && $name != '' && $saleprice != '' && $unit != '' && $brand != '' && $size != '' && $repoint != '') {
			$sql = "INSERT INTO product (product_id,product_name,brand_id,unit_id,product_saleprice,product_reorder,product_stock,product_detail,product_status) VALUES ('$idproduct','$name','$brand','$unit','$saleprice','$repoint','0','$detail','3')";
			// echo $sql;
			if (mysqli_query($conn, $sql) == true) {
				$sql1 = "INSERT INTO plumbling (product_id,mt_id,color_id,pb_size,pb_thick) VALUES ('$idproduct','$material','$color','$size','$thick')";
				// echo $sql1;
				mysqli_query($conn, $sql1);
				echo "<script type='text/javascript'>";
				echo "window.location.href='pb_show.php';";
				echo "</script>";
			} else {
				echo "<script>"; //คำสั่งสคิป
				echo "alert('ผิดพลาด กรุณากรอกข้อมูลใหม่!');"; //แสดงหน้าต่างเตือน
				echo "window.location.href='pb_insert.php';"; //แสดงหน้าก่อนนี้
				echo "</script>";
			}
		} elseif ($name == '' || $saleprice == '' || $unit == '' || $brand == '' || $class == '' || $size == '' || $repoint == '' || $color == '' || $thick == '' || $detail == '' || $material == '') {
			echo "<script>";
			echo "alert('กรุณากรอกข้อมูลให้ครบ !!');";
			echo "window.location.href='pb_insert.php';";
			echo "</script>";
		} else {
			echo "<script>";
			echo "alert('สินค้าซ้ำในระบบกรุณากรอกใหม่อีกครั้ง !!');";
			echo "window.location.href='pb_insert.php';";
			echo "</script>";
		}
	}

	?>
	<br>
</body>

</html>