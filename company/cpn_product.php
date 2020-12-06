<!DOCTYPE html>
<?php include('../connectdb.php'); 
      include('../home.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
<script type="text/javascript">

    function submitheadproduct1() {

    document.cpnproduct.submit();

    }
    
    function submitcolorproduct() {

        document.cpnproduct.submit();
    
    }

    function submitsizeproduct() {

    document.cpnproduct.submit();

    }

    function submitheadproduct2() {

        document.getElementById("cpnproduct").action = "cpn_product.php";
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
  width: 40%;
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
</head>
<body>
<br>
<?php
      $id=isset($_GET['product_id']) ? $_GET['product_id']:''; 
      if ($id!='') { // ถ้า id ไม่เท่ากับ ค่าว่าง
        $sql="SELECT * FROM company WHERE cpn_id='".$id."'";
        $result=mysqli_query($conn, $sql); //คิวรี่ คำสั่งsql เก็บใน ตัวแปร result
        $row=mysqli_fetch_array($result);
?>
<form action="#" name="cpnproduct" id="cpnproduct" method="post" enctype="multipart/form-data">
    <table align="center">
        <center><h3>เพิ่มสินค้าให้บริษัทคู่ค้า</h3></center>
                <tr>
                    <td align="right">รหัสบริษัทคู่ค้า : </td>
                    <td>&emsp;&ensp;<input type="hidden" name="cpn" id="cpn" value="<?php echo $row['cpn_id'];?>"><?php echo $row['cpn_id']; ?></td>
                </tr>

                <tr>
                    <td align="right">ประเภทสินค้า :</td>
                    <td>
                        <div>
                            &emsp;&ensp;<select onchange="submitheadproduct1();" name="product" id="product">
                            <option>-----------------โปรดเลือก-----------------</option>
                                <?php
                                $strSQL = "SELECT * FROM stonebricksand sbs,brand b WHERE b.brand_id = sbs.brand_id order by product_id";
                                $result = mysqli_query($conn, $strSQL);

                                $strSQL2 = "SELECT * FROM mortar m,brand b  WHERE b.brand_id = m.brand_id order by product_id";
                                $result2 = mysqli_query($conn, $strSQL2);

                                $strSQL3 = "SELECT * FROM category_color cc,brand b,color c WHERE cc.brand_id = b.brand_id AND cc.color_id = c.color_id order by product_id";
                                $result3 = mysqli_query($conn, $strSQL3);

                                $strSQL4 = "SELECT * FROM attachment att,brand b,size s WHERE att.brand_id = b.brand_id AND att.size_id = s.size_id order by product_id";
                                $result4 = mysqli_query($conn, $strSQL4);

                                $strSQL5 = "SELECT * FROM chemical c,brand b WHERE c.brand_id = b.brand_id order by product_id";
                                $result5 = mysqli_query($conn, $strSQL5);

                                $strSQL6 = "SELECT * FROM craftsman_tool ct,brand b WHERE ct.brand_id = b.brand_id order by product_id";
                                $result6 = mysqli_query($conn, $strSQL6);

                                $strSQL7 = "SELECT * FROM pvc,brand b,class cs,size s WHERE pvc.brand_id = b.brand_id AND pvc.class_id = cs.class_id AND pvc.size_id = s.size_id order by product_id";
                                $result7 = mysqli_query($conn, $strSQL7);
                                

                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <option value="<?php echo $row['product_id']; ?>" 
                                    <?php if (isset($_POST['product'])) {
                                        if ($_POST['product'] == $row['product_id']) {
                                            echo 'selected';
                                        }
                                    } ?>>
                                        <?php echo $row['product_name'] ." ". $row['brand_name']; ?></option>
                                <?php
                                } ?>
                                <?php while ($row2 = mysqli_fetch_array($result2)) {
                                    ?>
                                    <option value="<?php echo $row2['product_id']; ?>" 
                                    <?php if (isset($_POST['product'])) {
                                        if ($_POST['product'] == $row2['product_id']) {
                                            echo 'selected';
                                        }
                                    } ?>>
                                        <?php echo $row2['product_name'] ." ". $row2['brand_name']; ?></option>
                                <?php
                                } ?>
                                <?php while ($row3 = mysqli_fetch_array($result3)) {
                                    ?>
                                    <option value="<?php echo $row3['product_id']; ?>" 
                                    <?php if (isset($_POST['product'])) {
                                        if ($_POST['product'] == $row3['product_id']) {
                                            echo 'selected';
                                        }
                                    } ?>>
                                        <?php echo $row3['product_name'] ." ". $row3['brand_name'] ." "."สี". $row3['color_name']; ?></option>
                                <?php
                                } ?>
                                <?php while ($row4 = mysqli_fetch_array($result4)) {
                                    ?>
                                    <option value="<?php echo $row4['product_id']; ?>" 
                                    <?php if (isset($_POST['product'])) {
                                        if ($_POST['product'] == $row4['product_id']) {
                                            echo 'selected';
                                        }
                                    } ?>>
                                        <?php echo $row4['product_name'] ." ". $row4['brand_name'] ." ". $row4['size_name']; ?></option>
                                <?php
                                } ?>
                                <?php while ($row5 = mysqli_fetch_array($result5)) {
                                    ?>
                                    <option value="<?php echo $row5['product_id']; ?>" 
                                    <?php if (isset($_POST['product'])) {
                                        if ($_POST['product'] == $row5['product_id']) {
                                            echo 'selected';
                                        }
                                    } ?>>
                                        <?php echo $row5['product_name'] ." ". $row5['brand_name']; ?></option>
                                <?php
                                } ?>
                                <?php while ($row6 = mysqli_fetch_array($result6)) {
                                    ?>
                                    <option value="<?php echo $row6['product_id']; ?>" 
                                    <?php if (isset($_POST['product'])) {
                                        if ($_POST['product'] == $row6['product_id']) {
                                            echo 'selected';
                                        }
                                    } ?>>
                                        <?php echo $row6['product_name'] ." ". $row6['brand_name']; ?></option>
                                <?php
                                } ?>
                                <?php while ($row7 = mysqli_fetch_array($result7)) {
                                    ?>
                                    <option value="<?php echo $row7['product_id']; ?>" 
                                    <?php if (isset($_POST['product'])) {
                                        if ($_POST['product'] == $row7['product_id']) {
                                            echo 'selected';
                                        }
                                    } ?>>
                                        <?php echo $row7['product_name'] ." ". $row7['brand_name'] ." "."ชั้น". $row7['class_name'] ." ". $row7['size_name']; ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                    </td>
                </tr>
            
                <tr>
                    <td  align="right">ราคารับ :</td>
                    <td>&emsp;&ensp;<input type="text" name="costprice" value="<?php if(isset($_POST['costprice'])) echo $_POST['costprice']; ?>"></td>
                </tr>
                <tr>
                    <td><br><br></td>
                    <td colspan="2">&emsp;&ensp;<input type="submit" name="addcostprice" value="ตกลง" onclick="submitheadproduct2();" style="width: 250px"></td>
                </tr>
                 <?php } ?>
			</form>
    </table><br><br>
    <?php
     
            if (isset($_POST['addcostprice'])) {
                $companyid=$_POST['cpn'];
                $productid=$_POST['product'];
                $costprice=$_POST['costprice'];

                $sql = "INSERT INTO costprice (cpn_id,product_id,costprice) VALUES ('$companyid','$productid','$costprice')";
                // echo  $sql;
                 if ($conn->query($sql)==true) {
                    $result=mysqli_query($conn, $sql);
                    echo "<script>"; //คำสั่งสคิป
                    echo "alert('บันทึกสำเร็จ!');"; //แสดงหน้าต่างเตือน
                    echo "window.location.href='cpn_show.php';"; //แสดงหน้าก่อนนี้
                    echo "</script>";
                } else {
                    echo "<script>"; //คำสั่งสคิป
                    echo "alert('ผิดพลาด กรุณากรอกข้อมูลใหม่!');"; //แสดงหน้าต่างเตือน
                    echo "window.location.href='javascript:history.back(1)';"; //แสดงหน้าก่อนนี้
                    echo "</script>";
                }
            }
            

    ?>
</body>
</html>