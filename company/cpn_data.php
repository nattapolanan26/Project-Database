<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<?php include('../h.php'); ?>
</head>

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
					<form action="#" name="cpndata" id="cpndata" method="post" enctype="multipart/form-data">
						<?php $idproduct = isset($_GET['p_id']) ? $_GET['p_id'] : ''; ?>
						<input type="hidden" name="idproduct" value="<?php echo $idproduct ?>">
						<?php
						$search = isset($_GET['search']) ? $_GET['search'] : '';
						//select id ในตาราง มาเซิส
						$dataid = isset($_GET['data_id']) ? $_GET['data_id'] : '';

						$sql = "SELECT * FROM company INNER JOIN tbl_provinces ON (company.province_id = tbl_provinces.province_id) 
					INNER JOIN tbl_amphures ON (company.amphur_id = tbl_amphures.amphur_id) 
					INNER JOIN tbl_districts ON (company.district_id = tbl_districts.district_id) 
					INNER JOIN tbl_zipcodes ON (company.zipcode_id = tbl_zipcodes.zipcode_id)
					LEFT JOIN tel_company ON (company.cpn_id = tel_company.cpn_id) 
					WHERE company.cpn_id = '$dataid'  
					GROUP BY company.cpn_id";
						// echo $sql;
						$result = mysqli_query($conn, $sql);
						$row =  mysqli_fetch_assoc($result);
						?>
						<script>
							$(document).ready(function() {
								$('#example1').DataTable({
									"cpn_id": [
										[0, 'ASC']
									],
									// "lengthMenu":[[20,50, 100, -1], [20,50, 100,"All"]]
								});
							});
						</script>

						<?php
						echo ' <table border="2" class="display table table-bordered" id="example1"> ';
						//หัวข้อตาราง
						echo " <thead>
							<tr bgcolor='#AED6F1' align='center' class='info'>
							<th width='15%'>รหัสบริษัทคู่ค้า</th>
							<th width='25%'>ชื่อบริษัทคู่ค้า</th>
							<th>ที่อยู่</th>
							<th>อีเมลล์</th>
							<th width='15%'>เบอร์ติดต่อ</th>
							</tr>
						</thead>";
						do {
							echo "<tr>";
							echo "<td align='center'>" . $row['cpn_id'] . "</td> ";
							echo "<td>" . $row['cpn_name'] . "</td> ";
							echo "<td>" . "เลขที่&ensp;" . $row['cpn_address'] . '&ensp;จ.' . $row['province_name'] . 'อ.' . $row['amphur_name'] . 'ต.' . $row['district_name'] . $row['zipcode'] . "</td> ";
							echo "<td>" . $row['cpn_email'] . "</td> ";
							echo "<td align='center'>" . $row['cpn_tel'] . "</td> ";
							echo "</tr>";
							echo "</tr>";
						} while ($row =  mysqli_fetch_assoc($result));
						echo "</table>"; ?>
						<center><input class="w3-button w3-black w3-round-xlarge" type="submit" name="backcpn" value="ย้อนกลับ" style="width: 100px" onclick="document.cpndata.action='cpn_show.php'"></center>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</body>

</html>