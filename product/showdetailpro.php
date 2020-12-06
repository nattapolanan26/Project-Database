<?php session_start(); ?>
<!DOCTYPE html>
<?php
	include('../connectdb.php');
	include('../home.php');
?>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<script type="text/javascript">

		function backForm(){

			document.showDetailpro.action = "show.php";

		}

</script>



<style type="text/css">
@font-face {
	  font-family: PrintAble4U;  
	  src: url(font\sPrintAble4U.ttf)  format("truetype");
        }
body { font-family: 'PrintAble4U' , verdana, helvetica, sans-serif;}
body {
  font-family: 'lato', sans-serif;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  text-align: left;
  padding: 8px;
}

/* ปรับสี background ใน table */
tbody{
	background:#e8edff;
}
/* hover cursor เป็นสีฟ้า */
tbody tr:hover td{
	color:#339;
	background:#d0dafd;
}

th {
  background-color: #20B2AA;
  color: white;
}
</style>
</head>
<title>Product</title>
<body>
<form action="#" name="showDetailpro" method="post">
	<table>
		<tr>
		<h2>รายละเอียดสินค้า</h2>
		<tr>
		<thead>
			<th><center> รหัสสินค้า </center></th>
			<th><center> ลำดับ </center></th>
            <th><center> สี </center></th>
			<th><center> ขนาด </center></th>
            <th><center> จุดต่ำสุด </center></th>
            <th><center> ราคาขาย </center></th>
			<th><center> แก้ไข </center></th>
			<th><center> ลบ </center></th>
		</thead>
		</tr>
		<?php
		$search=isset($_GET['search']) ? $_GET['search']:'';
        $proid = isset($_GET['show_id']) ? $_GET['show_id'] : '';

		$sql1="SELECT * FROM detailproduct dp , color_product cp , size_product sp WHERE dp.product_id = '$proid' and cp.product_id = dp.product_id and cp.product_order = dp.product_order and sp.product_id = dp.product_id and sp.product_order = dp.product_order ";
		$result=$conn->query($sql1); 

        if ($result==true) {
            while ($row=$result->fetch_array()) {
                ?>
		<tr>
			<td><center><?php echo $row["product_id"]; ?></center></td>
			<td><?php echo $row["product_order"]; ?></td>
            <td><?php echo $row['color_name']; ?></td>
            <td><?php echo $row['size_name']; ?></td>
            <td><center><?php echo $row["loworder"]; ?></center></td>
            <td><?php echo $row["saleprice"]; ?></td>
			<td><center><a href="edit.php?edit_id=<?php echo $row["product_id"]; ?>"><img src="/picture/edit.png" width="30" height="30" alt="แก้ไข"></a></center></td>
            <td><center><a href="delete.php?delete_id=<?php echo $row["product_id"]; ?>"><img src="/picture/delete.png" width="30" height="30" alt="ลบ"></a></center></td>
		</tr>
		<?php
            }
        }
		?>

	</table>
		<center>
		<input type="submit" name="submit" value="<< Back" style="background-color:#ff0000;" onclick="backForm();">
		</center>
	</form>
</body>
</html>