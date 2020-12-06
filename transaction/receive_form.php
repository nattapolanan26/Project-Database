<!DOCTYPE html>
<html>

<head>
<?php include('../h.php'); ?>
<script type="text/javascript">
	function updateProduct() {
		document.getElementById("recieveform").action = "update_receive.php";
	}

	function confirmReceive() {
		document.getElementById("receiveform").action = "receive_form.php";
	}
</script>
<style>
    .color-alert{
        background-color: #ffe3e3;

        margin: 0px;
        border-radius: 20px;
        padding: 5px;
    }
</style>
</head>

<body>
	<?php include('../connectdb.php'); ?>
	<?php include('../navbar.php'); ?>
	<div class="container-fluidid">
		<p></p>
		<div class="row">
			<div class="col-md-3">
				<div class="color-login">
				<h6><i class="fas fa-user-circle"></i>&ensp;<a style="font-weight:bold;"><?php echo "ผู้ใช้"; ?></a><a style="color:#c92828;font-weight:bold;"><?php echo " : " . $_SESSION['user']; ?></a></h6>
                    <h6><i class="fas fa-check-square"></i></i></i>&ensp;<a style="font-weight:bold;"><?php echo "ตำแหน่ง"; ?></a><a style="color:#1d4891;font-weight:bold;"><?php echo " : " . $_SESSION['posname']; ?></a></h6>
				</div>
				<?php include('../menu_left.php'); ?>
			</div>
			<div class="col-md-9">
				<h4>รายการรับสินค้า</h4>
				<form action="#" name="receiveform" id="receiveform" method="post">
					<div class="form-inline">
						<!-- ค้นหา -->
						<div class="form-group mb-2">
							<div>
								<button class="w3-button w3-red w3-round-xlarge" style="width:80px;width:120px;" type="submit" name="back" onclick="document.receiveform.action='receive_show.php'"><i class="fa fa-reply" aria-hidden="true"> ย้อนกลับ</i></button>
								<button class="w3-button w3-green w3-round-xlarge" style="width:120px;" type="submit" name="confirmreceive" onclick="return confirm('Do you want to confirm? !!!')"><i class="fa fa-check" aria-hidden="true"> ยืนยันการรับ</i></button>
							</div>
						</div>
					</div>
					<div class="form-alert mb-2">
						<div class="color-alert mb-2" align="center"><a style='color:#d60000;font-weight:bold;'>**หมายเหตุ : ว/ด/ป หมดอายุของสินค้า มีแค่บางประเภทเท่านั้น เช่น (สีทาอาคาร,เคมีภัณฑ์)**</a></div>
					</div>

					<?php
					$search = isset($_POST['search']) ? $_POST['search'] : '';
					$rp_id = isset($_GET['rp_id']) ? $_GET['rp_id'] : '';
					$p_id = isset($_GET['p_id']) ? $_GET['p_id'] : '';


					$sql = "SELECT detailorderpro.*,product.*,company.*,brand.brand_name,unit.unit_name,color_name,class,company.*,tbl_provinces.province_name,tbl_amphures.amphur_name,tbl_districts.district_name,tel_company.cpn_tel,tl_size,pb_size,pb_thick,cc_volume,ct_size,cs_volume,cm_volume,cpn_name,costprice,detailreceivepro.*
					FROM product
					INNER JOIN detailquotation ON detailquotation.product_id = product.product_id
					INNER JOIN detailorderpro ON detailorderpro.quo_id = detailquotation.quo_id
					INNER JOIN detailreceivepro ON detailreceivepro.order_id = detailorderpro.order_id
					INNER JOIN brand ON brand.brand_id = product.brand_id
					INNER JOIN unit ON unit.unit_id = product.unit_id
					LEFT JOIN cement ON cement.product_id = product.product_id
					LEFT JOIN categorycolor ON categorycolor.product_id = product.product_id
					LEFT JOIN toilet ON toilet.product_id = product.product_id
					LEFT JOIN chemicalsolution ON chemicalsolution.product_id = product.product_id
					LEFT JOIN craftmantool ON craftmantool.product_id = product.product_id
					LEFT JOIN plumbling ON plumbling.product_id = product.product_id
					 
					LEFT JOIN color ON color.color_id = categorycolor.color_id OR color.color_id = craftmantool.color_id OR color.color_id = toilet.color_id
					LEFT JOIN company ON company.cpn_id = detailquotation.cpn_id
					LEFT JOIN costprice ON costprice.cpn_id = company.cpn_id AND costprice.product_id = product.product_id
					LEFT JOIN tel_company ON tel_company.cpn_id = company.cpn_id 
					LEFT JOIN tbl_provinces ON tbl_provinces.province_id = company.province_id
					LEFT JOIN tbl_amphures ON tbl_amphures.amphur_id = company.amphur_id
					LEFT JOIN tbl_districts ON tbl_districts.district_id = company.district_id
					WHERE detailreceivepro.drp_order = detailorderpro.order_no AND detailquotation.quo_order = detailorderpro.order_no  AND detailreceivepro.rp_id = '$rp_id' AND detailreceivepro.drp_balance != '0'
					GROUP BY order_no
					ORDER BY detailreceivepro.drp_order ASC";
				
					$result = mysqli_query($conn, $sql) or die('Error query: ' . mysqli_error($conn));
					$row = mysqli_fetch_array($result);
					?>
					<script>
						$(document).ready(function() {
							$('#example').DataTable({
							"pageLength" : 10,
							"order": [[ 0, 'asc' ]]
							});
						} );
        			</script>
					<?php
					// echo $sqllot;
					echo ' <table border="2" class="display table table-bordered" id="example" align="center">';
					//หัวข้อตาราง


					echo "
						<thead>
						<tr bgcolor='#AED6F1' align='center' style='font-weight:bold'>
						<th width='13%'>รหัสการรับ</th>
						<th>#</th>
						<th><center>สินค้า</center></th>
						<th><center>บริษัทคู่ค้า</center></th>
						<th width='13%'><center>จำนวนอนุมัติ</center></th>
						<th width='13%'>จำนวนที่รับ</th>
						<th width='8%'>หน่วย</th>
						<th width='10%'>อนุมัติจำนวน</th>
						</tr>
						</thead>";


					do {
                        if ($row["rp_id"] != '') {
                            echo "<tr>";
                            echo "<td align='center'>" . $row["rp_id"] . '<input type="hidden" name="id" value="' . $row['rp_id'] . '">' . "</td>";
                            echo "<td align='center'>" . $row["drp_order"] . '<input type="hidden" name="order" value="' . $row["drp_order"] . '">' . "</td> ";
							if($row['product_name'] != ''){echo "<td>" . $row['product_name'];} ?>&ensp;<?php
							if($row['brand_name'] != ''){echo $row['brand_name']; ?>&ensp;<?php } 
							if($row['color_name'] != ''){echo "สี".$row['color_name']; ?>&ensp;<?php } 
							if($row['class'] != ''){echo "ชั้น"." ".$row['class']; ?>&ensp;<?php } 
							if($row['tl_size'] != ''){echo "ขนาด"." ".$row['tl_size'];} 
							if($row['pb_size'] != ''){echo "ขนาด"." ".$row['pb_size'];} 
							if($row['ct_size'] != ''){echo "ขนาด"." ".$row['ct_size']; ?>&ensp;<?php } 
							if($row['pb_thick'] != ''){echo " "."หนา"." ".$row['pb_thick']; ?>&ensp;<?php } 
							if($row['cc_volume'] != ''){echo $row['cc_volume'];}
							if($row['cs_volume'] != ''){echo $row['cs_volume'];} 
							if($row['cm_volume'] != ''){echo $row['cm_volume']. "</td> "; ?>&ensp;<?php } 
							echo "<td>" . $row["cpn_name"] . '<input type="hidden" name="id" value="' . $row['rp_id'] . '">' . "</td>";
                            echo "<td align='right' style='color:blue;'>" . $row["drp_balance"] . "</td> ";
                            echo "<td align='right' style='color:green;'>" . $row["drp_number"] . "</td>";
                            echo "<td align='center'>" . $row["unit_name"] . "</td> ";
                            echo "<td><center><a href='receive_addamount.php?rp_id=$row[rp_id]&order=$row[drp_order]&p_id=$row[product_id]' class='btn btn-info btn-xs' title='Update'><i class='fa fa-sign-language' aria-hidden='true'> จำนวนรับ</i></center></td> ";
                            echo "</tr>";
                        }
					} while ($row =  mysqli_fetch_array($result));
						echo '</table>'
						
					?>
					<input type="hidden" name="ID" id="ID" value="<?php echo $rp_id; ?>">
					<input  type="hidden" name="date" value="<? $date = date('Y-m-d') ?>" />
				</form>
			</div>
		</div>
	</div>
	<?php
	if (isset($_POST['confirmreceive'])) {

		//SQL เพื่อ + จำนวนครั้งที่รับสินค้า
		$sqlnum = "SELECT LPAD(max(rp_number)+1,10,'0') as RP_NUM FROM receiveproduct WHERE receiveproduct.rp_id = '$rp_id'";
		$resultnum = mysqli_query($conn, $sqlnum);
		$rownumber = mysqli_fetch_array($resultnum);
		$num = $rownumber['RP_NUM'];
		// echo $sqlnum;

		//fetch เอาจำนวนคงเหลือ และ เช็คสถานะ
		$sql = "SELECT * FROM receiveproduct inner join detailreceivepro on receiveproduct.rp_id = detailreceivepro.rp_id WHERE detailreceivepro.rp_id = '$rp_id' AND receiveproduct.rp_id = '$rp_id'";
		$result1 = mysqli_query($conn, $sql);
		// echo $sql;

		// คิวรี่เพื่อเอา จำนวนรับ Amount มาตรวจสอบทีละ column
		$result2 = mysqli_query($conn, $sql);
		$row2 = mysqli_fetch_array($result2);
		$amount = $row2['drp_number'];

		//วนเพื่อ fetch เอาจำนวนคงเหลือ และ สถานะการรับ เพื่อดัก
		while ($row1 = mysqli_fetch_array($result1)) {
			$balance = $row1['drp_balance'];
			$status = $row1['rp_status'];
		}

		if ($amount != 0 && $balance != 0) { //เช็ค จำนวนที่รับสินค้า ถ้ามากกว่า 0 และ จำนวนคงเหลือ ไม่เท่ากับ 0 ให้อัพเดท จำนวนครั้งที่รับ+1
			$sql = "UPDATE receiveproduct SET rp_number='$num' WHERE rp_id='" . $rp_id . "'";
			mysqli_query($conn, $sql);

			echo "<script>"; //คำสั่งสคิป
			echo "alert('รับสินค้าครั้งที่ $num สำเร็จ!!');"; //แสดงหน้าต่างเตือน
			echo "window.location.href='receive_show.php';"; //แสดงหน้าก่อนนี้
			echo "</script>";
		} elseif ($balance == 0 && $status == 0) { //เช็ค จำนวนคงเหลือ = 0 และ สถานะ = 0 ให้อัพเดท สถานะเป็น1และจำนวนครั้งที่รับ+1
			$sql = "UPDATE receiveproduct SET rp_status='1',rp_number='$num' WHERE rp_id='" . $rp_id . "'";
			mysqli_query($conn, $sql);

			echo "<script>"; //คำสั่งสคิป
			echo "alert('ยืนยันการรับสินค้าทั้งหมดในรายการเรียบร้อยแล้ว');"; //แสดงหน้าต่างเตือน
			echo "window.location.href='receive_show.php';"; //แสดงหน้าก่อนนี้
			echo "</script>";
		} elseif ($status == '1') { //เช็คสถานะ ถ้าเป็น 1 ให้แจ้งเตือนว่ารับไปแล้ว
			echo "<script>"; //คำสั่งสคิป
			echo "alert('คุณได้ทำการยืนยันการรับสินค้ารายนี้ไปแล้ว!!');"; //แสดงหน้าต่างเตือน
			echo "window.location.href='receive_show.php';"; //แสดงหน้าก่อนนี้
			echo "</script>";
		} else {
			echo "<script>"; //คำสั่งสคิป
			echo "alert('กรุณาตรวจสอบการรับรายสินค้าก่อน!!');"; //แสดงหน้าต่างเตือน
			echo "window.location.href='receive_form.php?rp_id=$rp_id&p_id=$p_id';"; //แสดงหน้าก่อนนี้
			echo "</script>";
		}
	}

	?>
	</form>
</body>

</html>