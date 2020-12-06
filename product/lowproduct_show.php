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
        function quotation() {

            document.getElementById("showprolow").action = "quo_form.php";
        }
		
		function quoApprove() {
			document.getElementById("showprolow").action = "quo_status.php";
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
  width: 70;
}
</style>
</head>
<title>Product Loworder</title>
<body>
<form action="#" name="showprolow" id="showprolow" method="post" enctype="multipart/form-data">
	<table align="center">
		<tr>
		<h3><center>จุดสั่งซื้อสินค้า</center></h3>
		</tr>
        <?php

		$search=isset($_GET['search']) ? $_GET['search']:'';
		//select id ในตาราง มาเซิส

        $sql="SELECT * FROM product p, category c, category_sub cs, brand b WHERE p.category = c.cgr_id AND p.category_sub = cs.cgrs_id AND p.brand = b.brand_id AND p.product_id LIKE '%$search%'";
		$result=$conn->query($sql); 

        if ($result==true) {
            while ($row=$result->fetch_array()) {
                ?>
		<tr>
			<td><center><?php echo $row["product_id"]; ?></center></td>
			<!-- <td><?php echo $row["product_name"]; ?></td> -->
			<td><?php echo $row["cgrs_name"] ." "; if($row['brand_id'] == 'BND-001') echo ""; else echo $row['brand_name']; ?></td>
		</tr>
		<?php
            }
        }
        
		?>
	</table><center>
            <input type="submit" name="quotation1" value="ออกใบเสนอซื้อสินค้า" onclick="quotation();">
			<input type="submit" name="approve" value="หน้าอนุมัติใบเสนอซื้อสินค้า" style="background-color:#ff0000;" onclick="quoApprove();">	
			</center>
	</form>
</body>
</html>