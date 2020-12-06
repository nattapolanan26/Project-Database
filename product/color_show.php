<?php session_start(); 
include('../home.php');
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<link rel="stylesheet" href="../../styletable.css">
	<link rel="stylesheet" href="../../style.css">
	<style type="text/css">
		table {
			border-collapse: collapse;
			width: 50%;
		}
	</style>
	<script type="text/javascript">
		function Asubmit() {
			document.myformAdd.action = "form_insert.php";

		}

		function backform() {
			document.myformAdd.action = "idprocheck.php";

		}
	</script>
</head>
<title>Product</title>

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
						<form action="#" name="myformAdd" method="post">
							<table align="center">
								<center>
									<h3>ข้อมูล สี</h3>
								</center>
								<tr>
									<thead>
										<th>
											<center> รหัสสินค้า </center>
										</th>
										<th>
											<center> ชื่อประเภท </center>
										</th>
										<th>
											<center> สี </center>
										</th>
										<th>
											<center> แก้ไข </center>
										</th>
										<th>
											<center> ลบ </center>
										</th>
									</thead>
								</tr>
								<?php
								$search = isset($_GET['txtKeyword']) ? $_GET['txtKeyword'] : '';
								$sql1 = "SELECT cc.cgr_color_name,c.color_name,p.product_id,c.color_id
								FROM product AS p inner join category_color AS cc
								on (p.product_id = cc.product_id)
								inner join color AS c
								on (cc.cgr_color_id = c.cgr_color_id)
								WHERE color_name LIKE '%$search%'";
								$result = $conn->query($sql1);

								if ($result == true) {
									while ($row = $result->fetch_array()) {
								?>
										<tr>
											<td>
												<center><?php echo $row["product_id"]; ?></center>
											</td>
											<td><?php echo $row["cgr_color_name"]; ?></td>
											<td><?php echo $row["color_name"]; ?></td>
											<td>
												<center><a href="color_edit.php?edit_id=<?php echo $row["color_id"]; ?>&p_id=<?php echo $row['product_id']; ?>"><img src="/picture/edit.png" width="30" height="30" alt="แก้ไข"></a></center>
											</td>
											<td>
												<center><a href="color_delete.php?delete_id=<?php echo $row["color_id"]; ?>&p_id=<?php echo $row['product_id']; ?>"><img src="/picture/delete.png" width="30" height="30" alt="ลบ"></a></center>
											</td>
										</tr>
								<?php
									}
								}
								?>

							</table>
							<?php $idproduct = isset($_GET['p_id']) ? $_GET['p_id'] : ''; ?>
							<center>
								<input type="submit" name="cshowback" value="<< Back" onclick="backform();" style="background-color:#ff0000;">
								<input type="hidden" name="idproduct" value="<?php echo $idproduct; ?>">
							</center>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>

</html>