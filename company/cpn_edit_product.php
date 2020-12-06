<!DOCTYPE html>
<?php include('../connectdb.php');
      include('../home.php');
?>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<script type="text/javascript">
function submitheadproduct1() {

document.costpriceedit.submit();

}

function submitcolorproduct() {

    document.costpriceedit.submit();

}

function submitsizeproduct() {

document.costpriceedit.submit();

}

function submitheadproduct2() {

    document.getElementById("costpriceedit").action = "cpn_insert_product.php";
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
  width: 45%;
}

th, td {
  /* text-align: left; */
  padding: 5px;
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
<title>Company</title>
</head>
<body>
<br>
    <?php
    $cpnid = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
    $proid = isset($_GET['pro_id']) ? $_GET['pro_id'] : '';
    $order = isset($_GET['order']) ? $_GET['order'] : '';
    if ($cpnid != '') { // ถ้า id ไม่เท่ากับ ค่าว่าง
        $sql = "SELECT * FROM company WHERE cpn_id='" . $cpnid . "'";
        $result = mysqli_query($conn, $sql); //คิวรี่ คำสั่งsql เก็บใน ตัวแปร result
        $row = mysqli_fetch_array($result); //เฟรดเก็บไว้ใน $row

        $sqlcostprice = "SELECT* FROM costprice WHERE cpn_id = '".$cpnid."' AND product_id = '".$proid."' AND product_order = '".$order."'";
        $resultcostprice = mysqli_query($conn, $sqlcostprice); 
        $row2 = mysqli_fetch_array($resultcostprice);
    }
    ?>
        <form action="#" name="costpriceedit" id="costpriceedit" method="post" enctype="multipart/form-data">
        <table align="center">
            <center><h3>แก้ไขสินค้าบริษัทคู่ค้า</h3></center>
            <tr>
                    <td align="right">รหัสบริษัทคู่ค้า : </td>
                    <td>&emsp;&ensp;<input type="text" name="cpn_id" id="cpn_id" value="<?php echo $row['cpn_id'];?>"></td>
                </tr>

                <tr>
                    <td align="right">ประเภทสินค้า :</td>
                    <td>
                        <div>
                            &emsp;&ensp;<select onchange="submitheadproduct1();" name="product" id="product">
                                <option value="0">-----------------โปรดเลือก-----------------</option>
                                <?php
                                $strSQL = "SELECT * FROM product";
                                $result = mysqli_query($conn, $strSQL);
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <option value="<?php echo $row['product_id']; ?>" <?php if (isset($_POST['product'])) {
                                        if ($_POST['product'] == $row['product_id']) {
                                            echo 'selected';
                                        }
                                    } ?>>
                                        <?php echo $row['product_name']; ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td  align="right">สี/ขนาด :</td>
                    <td>
                        <div>
                            &emsp;&ensp;<select onchange="submitcolorproduct();" name="colorproduct" id="colorproduct">
                                <option value=''>-----------------โปรดเลือก-----------------</option>
                                <?php
                                if (isset($_POST['product'])) {
                                    $strSQL = "SELECT * FROM detailproduct dp , color_product cpd , size_product spd WHERE dp.product_id = cpd.product_id AND dp.product_order = cpd.product_order AND dp.product_id = spd.product_id AND dp.product_order = spd.product_order AND dp.product_id = '".$_POST['product']."'";
                                    $result = mysqli_query($conn, $strSQL);
                                    while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                    <option value="<?php echo $row['product_order']; ?>" <?php if (isset($_POST['colorproduct'])) {
                                            if ($_POST['colorproduct'] == $row['product_order']) {
                                                echo 'selected';
                                            }
                                        } ?>>
                                        <?php echo $row['color_name']; ?>
                                        <?php echo $row['size_name']; ?></option>
                                <?php
                                    }
                                }?>
                            </select>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td  align="right">ราคารับ :</td>
                    <td>&emsp;&ensp;<input type="text" name="costprice" value="<?php echo $row2["costprice"]; ?>"></td>
                </tr>
                </tr>
                <td colspan="2"><center><input type="submit" name="update" value="UPDATE!!" style="width: 250px" onclick="submitForm();"></center></td>
    </table>
    </form>
</body>

</html>